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




class UserOnbordController extends Controller
{
    public function indexUserLogin()
    {
        $superUser = null;
        return view("loginusers/userlogin", compact('superUser'));
    }


    // public function loginUserAutenticacaon(Request $request)
    // {
    //     $validated = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:4',
    //         'user_type' => 'required|string',
    //     ]);

    //     if (str_ends_with($validated['email'], '@delostylestudio.com')) {
    //         $user = SuperAddUser::where('email', $validated['email'])->first();

    //         if (!$user) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid email address!',
    //             ]);
    //         }
    //     } else {
    //         $user = AllClient::where('client_email', $validated['email'])->first();
    //     }

    //     if (!$user) {
    //         $user = SuperUserTable::where('email', $validated['email'])->first();
    //     }

    //     if (!$user) {
    //         $user = AllClient::where('client_email', $validated['email'])->first();
    //     }

    //     // ✅ Status Check for SuperAddUser
    //     if ($user instanceof SuperAddUser && $user->status == 0) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Your account is inactive. Please contact support.',
    //         ]);
    //     }

    //     // ✅ Status Check for AllClient
    //     if ($user instanceof AllClient && $user->status == 0) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Your account is inactive. Please contact support.',
    //         ]);
    //     }

    //     if (!$user) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid email address!',
    //         ]);
    //     }

    //     $userEmail = $user instanceof AllClient ? $user->client_email : $user->email;

    //     if ($user->user_type !== $validated['user_type']) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Incorrect user type!',
    //         ]);
    //     }

    //     if (!Hash::check($validated['password'], $user->password)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Incorrect password!',
    //         ]);
    //     }

    //     $otp = random_int(100000, 999999);

    //     Session::put('user_email', $userEmail);
    //     Session::put('user_type', $user->user_type);
    //     Session::put('employee_id', $user->employee_id ?? null);
    //     Session::put('otp', $otp);
    //     Session::put('otp_email', $userEmail);
    //     Session::put('otp_sent_time', now());

    //     if ($user instanceof AllClient) {
    //         Session::put('client_id', $user->id);
    //     }

    //     try {
    //         Mail::to($userEmail)->send(new OtpMail($otp));

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'OTP has been sent to your email!',
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Failed to send OTP email. Please try again later.',
    //             'debug' => env('APP_DEBUG') ? $e->getMessage() : null,
    //         ]);
    //     }
    // }



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

}
