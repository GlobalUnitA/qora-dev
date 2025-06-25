<?php

namespace App\Http\Controllers\Auth;

use App\Models\AdminOtp;
use App\Models\UserOtp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Jenssegers\Agent\Agent;

class OtpController extends Controller
{
    protected $google2fa;
    protected $agent;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
        $this->agent = new Agent();
    }

    public function index(Request $request)
    {
        $is_new = false;
       
        $user = auth()->user();

        $secret_key = session("otp.secret_key");
        session()->forget("otp.secret_key");

        if ((!$user->otp || !$user->otp->secret_key) && !$secret_key) {
           return redirect()->route('otp.email');
        }
        
        if ($user->otp->secret_key) {
            $secret_key = $user->otp->secret_key;
        } else {
            $is_new = true;
        }
        
        $is_mobile = $this->agent->isMobile();

        $company = config('app.name');
        $otp_url = $this->google2fa->getQRCodeUrl(
            $company,
            $user->account,
            $secret_key
        );

        $qrcode_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($otp_url) . '&size=200x200';

        return view('auth.otp', compact('user', 'secret_key', 'is_new', 'is_mobile', 'qrcode_url'));
      
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $user = auth()->user();

        $secret_key = $user->otp->secret_key ?? $request->input('secret_key');

        if ($this->google2fa->verifyKey($secret_key, $request->input('otp_code'))) {

            session([
                "otp_verified_web" => true,
            ]);
           
            $intended_url = session("otp.intended_url_web", route('home'));
            session()->forget("otp.intended_url_web");

            if (!$user->otp->secret_key) {
               $user->otp()->update(['secret_key' => $secret_key]);
            }

            $user->otp()->update(['last_verified_at' => now()]);
            
            return response()->json([
                'status' => 'success',
                'message' => __('auth.email_verification_notice'),
                'url' => $intended_url,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('auth.email_verification_failed_notice'),
            'url' => route('home'),
        ]);
    }

    public function email()
    {

        return view('auth.otp-email-verify');
    }

    public function email_verify(Request $request)
    {
      
        session([
            "otp.secret_key" => $this->google2fa->generateSecretKey(),   
            ]);

        return redirect()->route('otp');
    }
}


