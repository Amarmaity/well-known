<?php

namespace App\Http\Controllers;

use App\Models\AllClient;
use Illuminate\Http\Request;
use App\Models\SuperAddUser;
use App\Models\SuperUserTable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    // Step 1: Show email form
    public function showForgotPasswordForm()
    {
        return view('forgotpassword.forgotPassword');
    }

    // Step 2: Send OTP
    public function sendOtp(Request $request)
    {
        validator($request->all(), [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {

                    $exists =
                        DB::table('super_add_users')->where('email', $value)->exists()
                        || DB::table('super_user_tables')->where('email', $value)->exists()
                        || DB::table('all_clients')->where('client_email', $value)->exists();

                    if (!$exists) {
                        $fail('Email not found');
                    }
                }
            ]
        ])->validate();

        // Generate OTP
        $otp = random_int(100000, 999999);

        Session::put('forgot_password_otp', $otp);
        Session::put('forgot_password_email', $request->email);
        Session::put('otp_sent_time', now());

        try {
            Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));
            return redirect()->route('forgot-password.verifyForm')->with('success', 'OTP sent to your email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    // Step 3: Show OTP form
    public function showVerifyOtpForm()
    {
        return view('forgotpassword.verify');
    }

    // Step 4: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $otpSentTime = Session::get('otp_sent_time');

        if (!$otpSentTime || now()->greaterThan(\Carbon\Carbon::parse($otpSentTime)->addMinutes(5))) {
            Session::forget(['forgot_password_otp', 'forgot_password_email', 'otp_sent_time']);

            return back()->with('error', 'OTP has expired. Please request a new one.');
        }

        if (Session::get('forgot_password_otp') != $request->otp) {
            return back()->with('error', 'Invalid OTP.');
        }

        $email = Session::get('forgot_password_email');

        // Check which table user belongs to
        if ($user = SuperAddUser::where('email', $email)->first()) {
            Session::put('forgot_password_user_table', 'SuperAddUser');
        } elseif ($user = SuperUserTable::where('email', $email)->first()) {
            Session::put('forgot_password_user_table', 'SuperUserTable');
        } elseif ($user = AllClient::where('client_email', $email)->first()) {
            Session::put('forgot_password_user_table', 'AllClient');
        } else {
            return back()->with('error', 'User not found.');
        }
        return redirect()->route('forgot-password.resetForm');
    }

    // Step 5: Show reset password form
    public function showResetPasswordForm()
    {
        return view('forgotpassword.resetPassword');
    }

    // Step 6: Save new password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:4|confirmed'
        ]);

        $email = Session::get('forgot_password_email');
        $table = Session::get('forgot_password_user_table');

        if (!$email || !$table) {
            return back()->with('error', 'Session expired. Please try again.');
        }

        switch ($table) {

            case 'SuperAddUser':
                $user = SuperAddUser::where('email', $email)->first();
                break;

            case 'SuperUserTable':
                $user = SuperUserTable::where('email', $email)->first();
                break;

            case 'AllClient':
                $user = AllClient::where('client_email', $email)->first();
                break;

            default:
                $user = null;
        }

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        Session::forget([
            'forgot_password_otp',
            'forgot_password_email',
            'forgot_password_user_table',
            'otp_sent_time'
        ]);

        return redirect()->route('all-user-login')->with('success', 'Password reset successfully.');
    }
}