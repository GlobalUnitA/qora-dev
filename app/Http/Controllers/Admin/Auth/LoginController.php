<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('auth:admin')->only('logout');
    }

    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
 
        if (Auth::guard('admin')->attempt(['account' => $request->account, 'password' => $request->password], $request->has('remember'))) {
            return response()->json([
                'status' => 'success',
                'message' => '로그인 되었습니다.',
                'url' => route('admin'),
            ]);
        }
       
        return response()->json([
            'status' => 'error',
            'message' => '아이디 또는 비밀번호가 일치하지 않습니다.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('admin');
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
