<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationCode;

class VerificationController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'account' => 'nullable|string',
            'mode' => 'required|in:account,password',
        ]);

        if ($request->mode === 'account') {
            $user_exists = User::whereHas('profile', function ($query) use ($request) {
                $query->where('email', $request->email);
            })->exists();

            if (!$user_exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('auth.email_not_registered_notice'),
                ]);
            }
        } else if ($request->mode === 'password') {
            $user = User::where('account', $request->account)
                ->whereHas('profile', function ($query) use ($request) {
                    $query->where('email', $request->email);
                })
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('auth.user_not_found'),
                ]);
            }
        } 

        $code = rand(100000, 999999);

        session([
            'verification_code' => $code,
            'verified_email' => $request->email,
            'email_verified' => false,
        ]);

        try {
            Mail::to($request->email)->send(new EmailVerificationCode($code));
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            \Log::error('메일 전송 실패: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()]);
        }
        
        return response()->json(['message' =>  __('auth.verify_code_sent_notice')]);
    }

    public function checkCode(Request $request)
    {
        $request->validate(['code' => 'required']);

        if (session('verification_code') == $request->code) {
            session(['email_verified' => true]);

            if ($request->mode === 'account') {
                return response()->json([
                    'status' => 'success',
                    'message' => __('auth.email_verification_notice'),
                    'url' => route('account.result'),
                ]);
            } else if ($request->mode === 'password') {
                return response()->json([
                    'status' => 'success',
                    'message' => __('auth.email_verification_notice'),
                    'url' => route('password.reset'),
                ]);
            } else if ($request->mode === 'otp') {
                return response()->json([
                    'status' => 'success',
                    'message' => __('auth.email_verification_notice'),
                    'url' => route('otp.email.verify'),
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' =>  __('auth.email_verification_failed_notice'),
        ]);
    }
}