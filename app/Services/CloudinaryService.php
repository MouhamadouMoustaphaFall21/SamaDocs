<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CloudinaryService
{
    protected ?string $cloudName;
    protected ?string $apiKey;
    protected ?string $apiSecret;
    protected ?string $uploadPreset;

    public function __construct()
    {
        $this->cloudName = env('CLOUDINARY_CLOUD_NAME');
        $this->apiKey = env('CLOUDINARY_API_KEY');
        $this->apiSecret = env('CLOUDINARY_API_SECRET');
        $this->uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');
    }

    public function isConfigured(): bool
    {
        return !empty($this->cloudName) && !empty($this->apiKey) && !empty($this->apiSecret);
    }

    public function upload(string $localPath): ?string
    {
        if (!$this->isConfigured() || !Storage::disk('public')->exists($localPath)) {
            return null;
        }

        $publicId = 'samadocs/' . uniqid('doc_', true);
        $timestamp = time();

        $signature = $this->sign(['public_id' => $publicId, 'timestamp' => $timestamp]);

        try {
            $response = Http::attach(
                'file',
                Storage::disk('public')->get($localPath),
                basename($localPath)
            )->post($this->uploadEndpoint(), [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'api_key' => $this->apiKey,
            ]);

            $data = $response->json();
            if (isset($data['secure_url'])) {
                return $data['secure_url'];
            }
        } catch (\Throwable $e) {
            // fallback local
        }

        return null;
    }

    public function delete(string $url): bool
    {
        if (!$this->isConfigured() || empty($url)) {
            return false;
        }

        $publicId = $this->publicIdFromUrl($url);
        if (empty($publicId)) {
            return false;
        }

        $params = [
            'public_id' => $publicId,
            'timestamp' => time(),
        ];
        $params['signature'] = $this->sign($params);
        $params['api_key'] = $this->apiKey;

        try {
            $response = Http::asForm()->post($this->baseUrl() . '/image/destroy', $params);
            return ($response->json()['result'] ?? 'not_found') === 'ok';
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function isStoredOnCloud(string $filePath): bool
    {
        return str_starts_with($filePath, 'http');
    }

    public function signedDownloadUrl(string $url): string
    {
        // Ajoute un flag de transformation pour forcer le téléchargement
        // Format Cloudinary: <base>/fl_attachment/<path>
        // Ex: https://res.cloudinary.com/<cloud>/image/upload/fl_attachment/doc.pdf
        $separator = str_contains($url, '/upload/') ? '/upload/' : '/';
        $parts = explode($separator, $url, 2);
        if (count($parts) === 2) {
            return $parts[0] . $separator . 'fl_attachment/' . $parts[1];
        }
        return $url;
    }

    protected function sign(array $params): string
    {
        ksort($params);
        $toSign = http_build_query($params);
        return sha1($toSign . $this->apiSecret);
    }

    protected function baseUrl(): string
    {
        return "https://api.cloudinary.com/v1_1/{$this->cloudName}";
    }

    protected function uploadEndpoint(): string
    {
        return $this->baseUrl() . '/image/upload';
    }

    public function publicIdFromUrl(string $url): ?string
    {
        // Ex: ...res.cloudinary.com/.../v12345/samadocs/doc_abc.png
        if (preg_match('#/v\d+/([a-zA-Z0-9_/.-]+)(?:\.[a-zA-Z0-9]+)?$#', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
