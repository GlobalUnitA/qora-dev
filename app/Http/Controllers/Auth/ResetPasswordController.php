<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{

    public function index(Request $request, $token = null)
    {
        if (!session('email_verified') || !session('verified_email')) {
            return response()->json([
                'status' => 'error',
                'message' => '이메일 인증이 필요합니다.',
                'url' => route('password.request'),
            ]);
        }

        return view('auth.password-reset');
    }


    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:16', 'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/', 'confirmed'],
        ]);

        $email = session('verified_email');

        $user = User::whereHas('profile', function ($query) use ($email) {
            $query->where('email', $email);
        })->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => '해당 이메일로 등록된 사용자를 찾을 수 없습니다.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['verification_code', 'verified_email', 'email_verified']);

        return response()->json([
            'status' => 'error',
            'message' => '비밀번호가 성공적으로 변경되었습니다.',
            'url' => route('home'),
        ]);
    }
}
