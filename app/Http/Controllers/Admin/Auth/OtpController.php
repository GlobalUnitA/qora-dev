<?php

namespace App\Http\Controllers\Admin\Auth;

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
        
        $user = auth('admin')->user();

        if($user->otp->secret_key) {
            $secret_key = $user->otp->secret_key;
        } else {
            $secret_key = $this->google2fa->generateSecretKey();
            $is_new = true;
        }

        $is_mobile = $this->agent->isMobile();

        $company = config('app.name').' Admin';
        $otp_url = $this->google2fa->getQRCodeUrl(
            $company,
            $user->account,
            $secret_key
        );

        $qrcode_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($otp_url) . '&size=200x200';

        return view('admin.auth.otp', compact('user', 'secret_key', 'is_new', 'is_mobile', 'qrcode_url'));
        
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $user = auth('admin')->user();

        $secret_key = $user->otp->secret_key ?? $request->input('secret_key');

        if ($this->google2fa->verifyKey($secret_key, $request->input('otp_code'))) {

            session([
                "otp_verified_admin" => true,
                "otp_verified_at_admin" => now(),
            ]);
           
            $intended_url = session("otp.intended_url_admin", route('admin'));
            session()->forget("otp.intended_url_admin");

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
            'url' => route('admin'),
        ]);
    }
}


