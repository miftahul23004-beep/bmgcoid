<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflarePurgeService
{
    protected ?string $zoneId;
    protected ?string $apiToken;
    protected bool $enabled;

    public function __construct()
    {
        $this->zoneId = config('services.cloudflare.zone_id');
        $this->apiToken = config('services.cloudflare.api_token');
        $this->enabled = !empty($this->zoneId) && !empty($this->apiToken);
    }

    /**
     * Purge specific URLs from Cloudflare cache
     */
    public function purgeUrls(array $urls): bool
    {
        if (!$this->enabled) {
            Log::info('Cloudflare purge skipped - not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/purge_cache", [
                'files' => $urls,
            ]);

            if ($response->successful()) {
                Log::info('Cloudflare cache purged', ['urls' => $urls]);
                return true;
            }

            Log::error('Cloudflare purge failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Cloudflare purge error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Purge everything (use sparingly)
     */
    public function purgeAll(): bool
    {
        if (!$this->enabled) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/purge_cache", [
                'purge_everything' => true,
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Cloudflare purge all error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Purge homepage and related URLs
     */
    public function purgeHomepage(): bool
    {
        $baseUrl = config('app.url');
        return $this->purgeUrls([
            $baseUrl,
            $baseUrl . '/',
            $baseUrl . '?lang=id',
            $baseUrl . '?lang=en',
        ]);
    }
}
