<?php
namespace App\Services;

use Aws\S3\S3Client;

class S3Service
{
    protected S3Client $s3;
    protected string $bucket;

    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);

        $this->bucket = env('AWS_BUCKET');
    }

    public function generateUploadUrl(string $folder, string $extension = 'jpg', int $expires = 600): array
    {
        $key = trim($folder, '/') . '/' . uniqid() . '.' . $extension;

        $cmd = $this->s3->getCommand('PutObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
            'ContentType' => 'application/octet-stream'
        ]);

        $request = $this->s3->createPresignedRequest($cmd, '+' . $expires . ' seconds');

        return [
            'upload_url' => (string)$request->getUri(),
            'file_key' => $key
        ];
    }
  
    public function generateDownloadUrl(string $key, int $expires = 300): string
    {
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        $request = $this->s3->createPresignedRequest($cmd, '+' . $expires . ' seconds');

        return (string)$request->getUri();
    }
  
    public function deleteFile(string $key): bool
    {
        $result = $this->s3->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        return isset($result['DeleteMarker']) ? true : false;
    }
}