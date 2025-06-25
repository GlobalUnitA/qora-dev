<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KakaoApi;
use Illuminate\Http\Request;

class KakaoController extends Controller
{
    private $clientId;
    private $redirectUri;
    private $kakaoApi;

    public function __construct(KakaoApi $kakaoApi)
    {
        $this->clientId = config('services.kakao.client_id');
        $this->redirectUri = config('services.kakao.redirect_uri');
        $this->kakaoApi = $kakaoApi;
    }

    public function redirectToKakao()
    {
        $loginUrl = "https://kauth.kakao.com/oauth/authorize"
            . "?client_id={$this->clientId}"
            . "&redirect_uri={$this->redirectUri}"
            . "&response_type=code"
            . "&scope=talk_message";

        return redirect($loginUrl);
    }

    public function logoutFromKakao()
    {
        $token = \DB::table('kakao_tokens')->where('user_role', 'admin')->first();
        if ($token) {
            \DB::table('kakao_tokens')->where('user_role', 'admin')->delete();
        }
        return response()->json(['message' => 'Logged out from Kakao']);
    }

    public function handleKakaoCallback(Request $request)
    {
        $authorizationCode = $request->query('code');

        if (!$authorizationCode) {
            return response()->json(['error' => 'Authorization code not provided'], 400);
        }

        
        $accessTokenData = $this->kakaoApi->getAccessToken($authorizationCode);

        if (!empty($accessTokenData['access_token'])) {
            
            $this->kakaoApi->saveAccessToken($accessTokenData);

            return response()->json(['message' => 'Access Token saved successfully']);
        }

        return response()->json(['error' => 'Failed to get Access Token'], 500);
    }
}