<?php

namespace App\Http\Controllers\Proc;

use App\Http\Controllers\Controller;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
 
    public function generatePresignedUrl(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
            'directory' => 'required|string',
        ]);

        $folder = $request->input('folder', 'uploads/'.$request->directory);
        $s3Service = app(S3Service::class);

        try {
            $data = $s3Service->generateUploadUrl($folder, 'jpg');
            return response()->json([
                'status' => 'success',
                'upload_url' => $data['upload_url'],
                'file_key' => $data['file_key'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
