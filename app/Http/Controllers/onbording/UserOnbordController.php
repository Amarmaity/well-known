<?php

namespace App\Http\Controllers\onbording;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\OtpMail;
use App\Models\AllClient;
use App\Models\SuperAddUser;
use App\Models\SuperUserTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;




class UserOnbordController extends Controller
{
    public function indexUserLogin()
    {
        $superUser = null;
        return view("loginusers/userlogin", compact('superUser'));
    }

    public function loginUserAutenticacaon(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
            'user_type' => 'required|string',
        ]);

        $email = $validated['email'];

        // ✅ NEW FEATURE: Special handling for company email
        if (str_ends_with($email, '@delostylestudio.com')) {

            $user = SuperAddUser::where('email', $email)->first();

            // 👉 If NOT exists → send OTP (NEW FLOW)
            if (!$user) {
                $otp = random_int(100000, 999999);

                Session::put('otp', $otp);
                Session::put('otp_email', $email);
                Session::put('otp_sent_time', now());
                Session::put('new_user_email', $email);

                try {
                    Mail::to($email)->send(new OtpMail($otp));

                    return response()->json([
                        'status' => 'new_user',
                        'message' => 'OTP sent to your email. Please verify.',
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send OTP email.',
                    ]);
                }
            }

        } else {
            // ✅ OLD FLOW (unchanged)
            $user = SuperAddUser::where('email', $email)->first();
        }

        // ✅ OLD FALLBACK LOGIC (unchanged)
        if (!$user) {
            $user = SuperUserTable::where('email', $email)->first();
        }

        if (!$user) {
            $user = AllClient::where('client_email', $email)->first();
        }

        // ❌ Still not found
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email address!',
            ]);
        }

        // ✅ Status Check
        if (
            ($user instanceof SuperAddUser && $user->status == 0) ||
            ($user instanceof AllClient && $user->status == 0)
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is inactive. Please contact support.',
            ]);
        }

        $userEmail = $user instanceof AllClient ? $user->client_email : $user->email;

        // ✅ User type check
        if ($user->user_type !== $validated['user_type']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect user type!',
            ]);
        }

        // ✅ Password check
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password!',
            ]);
        }

        // ✅ OTP for login
        $otp = random_int(100000, 999999);

        Session::put('user_email', $userEmail);
        Session::put('user_type', $user->user_type);
        Session::put('employee_id', $user->employee_id ?? null);
        Session::put('otp', $otp);
        Session::put('otp_email', $userEmail);
        Session::put('otp_sent_time', now());

        if ($user instanceof AllClient) {
            Session::put('client_id', $user->id);
        }

        try {
            Mail::to($userEmail)->send(new OtpMail($otp));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent to your email!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP email.',
                'debug' => env('APP_DEBUG') ? $e->getMessage() : null,
            ]);
        }
    }

    public function loginUserverifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        // Retrieve OTP info from session
        $otpSession = Session::get('otp');
        $otpEmail = Session::get('otp_email');
        $otpSentTime = Session::get('otp_sent_time');

        if ($otpSentTime && now()->diffInMinutes($otpSentTime) > 10) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired. Please request a new one.',
            ]);
        }

        if ($validated['otp'] == $otpSession && $validated['email'] == $otpEmail) {

            // Try fetching user from all tables
            $user = SuperAddUser::where('email', $validated['email'])->first();
            $superUser = SuperUserTable::where('email', $validated['email'])->first();
            $client = AllClient::where('client_email', $validated['email'])->first();

            if ($user) {
                // For SuperAddUser
                Session::put('user_email', $user->email);
                Session::put('user_type', $user->user_type);
                Session::put('user_id', $user->id);

                // Define redirects based on user_type
                $redirectRoute = match ($user->user_type) {
                    'Super User' => route('super-admin-view'),
                    'admin' => route('admin-dashboard'),
                    'hr' => route('hr-dashboard'),
                    'users' => route('users-dashboard'),
                    'manager' => route('manager-dashboard'),
                    default => route('all-user-login'),
                };
            } elseif ($superUser) {
                // For SuperUserTable
                Session::put('user_email', $superUser->email);
                Session::put('user_type', $superUser->user_type);
                Session::put('user_id', $superUser->id);

                $redirectRoute = match ($superUser->user_type) {
                    'Super User' => route('super-admin-view'),
                    'admin' => route('admin-dashboard'),
                    'hr' => route('hr-dashboard'),
                    'users' => route('users-dashboard'),
                    'manager' => route('manager-dashboard'),
                    default => route('all-user-login'),
                };
            } elseif ($client) {
                // For AllClient
                Session::put('user_email', $client->client_email);
                Session::put('user_type', $client->user_type);
                Session::put('client_id', $client->id);  // Store client id

                $redirectRoute = match ($client->user_type) {
                    'client' => route('client-dashboard'),
                    default => route('login'),
                };
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ]);
            }


            // *** RUN YOUR COMMANDS HERE AFTER SUCCESSFUL LOGIN ***
            $outPut1 = Artisan::call('apply:probation-appraisal');
            $outPut2 = Artisan::call('employee:update-status');
            $outPut3 = Artisan::call('email:send-anniversaries');

            // Log::info('Command outputs:', [
            //     'apply:probation-appraisal' => Artisan::output(),
            //     'employee:update-status' => Artisan::output(),
            //     'email:send-anniversaries' => Artisan::output(),
            // ]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully!',
                'redirect' => $redirectRoute
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid OTP. Please try again.',
        ]);
    }



}
