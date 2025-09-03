<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
 
        if (Auth::attempt(['account' => $request->account, 'password' => $request->password], $request->has('remember'))) {
     //       session(['login_time' => now()]);

            return response()->json([
                'status' => 'success',
                'message' => __('auth.login_success_notice'),
                'url' => route('home'),
            ]);
        }
       
        return response()->json([
            'status' => 'error',
            'message' => __('auth.user_not_found'),
        ]);
    }

    public function logout(Request $request)
    {
       
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => __('auth.session_expired_notice'),
                'url' => route('login'),
            ]);
        }
  
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => __('user.logged_out_notice'),
            'url' => route('home'),
        ]);
    }

    protected function credentials(Request $request)
    {
        return [
            'account' => $request->get('account'),
            'password' => $request->get('password'),
        ];
    }

    protected function account()
    {
        return 'account';
    }
  
}
