<?php

namespace App\Http\Controllers\Policy;

use App\Models\Policy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function store()
    {

        
    }

    public function update(Request $request, $id)
    {
        // 기존 약관 업데이트
        $policy = Policy::findOrFail($id);
        $policy->content = [
            'title' => 'Updated Privacy Policy',
            'introduction' => 'This is the updated privacy policy. We’ve made changes to improve clarity.',
            'sections' => [
                [
                    'heading' => '1. Data Collection',
                    'content' => 'We now collect additional data, such as your location and device information.'
                ],
                [
                    'heading' => '2. Data Usage',
                    'content' => 'We use your personal data to provide tailored content and enhance your experience.'
                ],
                [
                    'heading' => '3. Data Protection',
                    'content' => 'We implement industry-standard encryption techniques to protect your personal data.'
                ]
            ]
        ];
        $policy->save();

        return response()->json($policy);
    }
}