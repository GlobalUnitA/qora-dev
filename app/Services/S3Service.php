<?php
namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Config;

class S3Service
{
    protected S3Client $s3;
    protected string $bucket;

    public function __construct()
    {
        $region   = Config::get('filesystems.disks.s3.region');
        $key      = Config::get('filesystems.disks.s3.key');
        $secret   = Config::get('filesystems.disks.s3.secret');
        $endpoint = Config::get('filesystems.disks.s3.endpoint');               // ← 추가
        $pathStyle = Config::get('filesystems.disks.s3.use_path_style_endpoint', false); // ← 추가
        $this->bucket = Config::get('filesystems.disks.s3.bucket');

        $options = [
            'version'     => 'latest',
            'region'      => $region,
            'credentials' => ['key' => $key, 'secret' => $secret],
        ];

        // 옵션 키는 null이면 생략
        if (!empty($endpoint)) {
            $options['endpoint'] = $endpoint;
        }
        $options['use_path_style_endpoint'] = (bool) $pathStyle;

        $this->s3 = new S3Client($options);
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
