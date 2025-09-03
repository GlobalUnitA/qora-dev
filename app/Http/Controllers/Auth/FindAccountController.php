<?php

namespace App\Http\Controllers\Auth;

use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FindAccountController extends Controller
{
    public function index()
    {
        return view('auth.account-request');
    }

    public function result()
    {
        
        if (!session('email_verified')) {
            return redirect()->route('account.request')->withErrors('이메일 인증이 필요합니다.');
        }

        $user = UserProfile::where('email', session('verified_email'))->first();
        $user_id = $user->user->account;

        session()->forget(['email_verified', 'verified_email', 'verification_code']);
        
        return view('auth.account-result', compact('user_id'));
    }
}