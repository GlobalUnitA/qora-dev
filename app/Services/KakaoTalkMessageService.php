<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KakaoTalkMessageService
{
    private $apiKey;
    private $apiEndpoint = 'https://kapi.kakao.com/v2/api/talk/memo/default/send';

    public function __construct()
    {
        $this->apiKey = config('services.kakao.rest_api_key');
    }

    /**
     * 카카오톡 메시지 전송
     *
     * @param string $message 전송할 메시지 내용
     * @param array $links 첨부할 링크 배열 (선택사항)
     * @return array
     */
    public function sendMessage(string $message, array $links = [])
    {
        try {
            $template = [
                'object_type' => 'text',
                'text' => $message,
                'link' => [
                    'web_url' => $links['web_url'] ?? '',
                    'mobile_web_url' => $links['mobile_web_url'] ?? ''
                ],
                'button_title' => $links['button_title'] ?? '자세히 보기'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->post($this->apiEndpoint, [
                'template_object' => json_encode($template)
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}