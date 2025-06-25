<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('file') && !$request->hasFile('image')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    
        $file = $request->file('file') ?? $request->file('image');
        $type = $request->input('type', 'tmp');
    
        $timestamp = time();
        $random = Str::random(20);
        $extension = $file->getClientOriginalExtension();
        $filename = "{$timestamp}_{$random}.{$extension}";
    
        $folder = "uploads/{$type}";
        $path = $file->storeAs($folder, $filename, 'public');
    
        return response()->json([
            'url' => asset("storage/{$path}"),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'type' => $type,
        ]);
    }
}