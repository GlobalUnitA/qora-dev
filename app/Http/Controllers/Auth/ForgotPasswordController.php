<?php 

namespace App\Http\Controllers\Auth;

use App\Mail\PasswordResetMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{


    public function index()
    {
        return view('auth.password-request');
    }
}