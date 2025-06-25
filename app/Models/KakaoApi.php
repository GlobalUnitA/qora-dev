<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class KakaoApi extends Model
{
    protected $table = 'kakao_tokens'; 
 
    private $clientId;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.kakao.client_id');
        $this->redirectUri = config('services.kakao.redirect_uri');
        
    }

    public function sendPurchaseNotification($message)
    {
                    

        $tokenData = DB::table('kakao_tokens')->where('user_role', 'admin')->first();
        
        if ($tokenData) {
           
            $accessToken = $tokenData->access_token;
            $refreshToken = $tokenData->refresh_token;

            if (now()->greaterThan($tokenData->expires_at)) {
                try {
                  
                    $newAccessToken = $this->refreshAccessToken($refreshToken);
                
                    $this->saveAccessToken($newAccessToken);
                    $accessToken = $newAccessToken['access_token'];
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to refresh access token'], 500);
                }
            }
          
            $sent = $this->sendMessage($accessToken, $message);


            if ($sent) {
                return response()->json(['message' => 'Message sent successfully']);
            }

            return response()->json(['error' => 'Failed to send message'], 500);
        }

        return response()->json(['error' => 'Access token not available'], 400);
    }
  
    public function getAccessToken($authorizationCode)
    {
        $client = new Client();
        
        $response = $client->post('https://kauth.kakao.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'code' => $authorizationCode,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function saveAccessToken($accessTokenData)
    {
        $token = DB::table('kakao_tokens')->where('user_role', 'admin')->first();

        DB::table('kakao_tokens')->updateOrInsert(
            ['user_role' => 'admin'],
            [
                'access_token' => $accessTokenData['access_token'],
                'refresh_token' => $accessTokenData['refresh_token'] ?? $token->refresh_token ?? null,
                'expires_at' => now()->addSeconds($accessTokenData['expires_in']),
                'created_at' => $token->created_at ?? now(),
                'updated_at' => now(),
            ]
        );
    }
    
    protected function refreshAccessToken($refreshToken)
    {
        $client = new Client();

        $response = $client->post('https://kauth.kakao.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'refresh_token' => $refreshToken,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

 
    protected function sendMessage($accessToken, $message)
    {

        $client = new Client();

        $response = $client->post('https://kapi.kakao.com/v2/api/talk/memo/default/send', [
            'headers' => [
                'Content-Type' => "application/x-www-form-urlencoded;charset=utf-8",
                'Authorization' => "Bearer $accessToken",
            ],
            'form_params' => [
                'template_object' => json_encode([
                    'object_type' => 'text',
                    'text' => $message,
                    'link' => [
                        'web_url' => 'https://developers.kakao.com',
                        'mobile_web_url' => 'https://developers.kakao.com',
                    ],
                    'button_title' => '바로 확인'
                ]),
            ],
        ]);

        return $response->getStatusCode() === 200;
    }
   
}