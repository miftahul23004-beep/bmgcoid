<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceLog extends Model
{
    protected $fillable = [
        'url',
        'route_name',
        'response_time',
        'memory_usage',
        'query_count',
        'query_time',
        'method',
        'status_code',
        'user_agent',
        'ip_address',
        'extra_data',
    ];

    protected $casts = [
        'response_time' => 'decimal:4',
        'memory_usage' => 'integer',
        'query_count' => 'integer',
        'query_time' => 'decimal:4',
        'status_code' => 'integer',
        'extra_data' => 'array',
    ];

    public function scopeForUrl($query, string $url)
    {
        return $query->where('url', $url);
    }

    public function scopeForRoute($query, string $routeName)
    {
        return $query->where('route_name', $routeName);
    }

    public function scopeMethod($query, string $method)
    {
        return $query->where('method', strtoupper($method));
    }

    public function scopeSlow($query, float $threshold = 1.0)
    {
        return $query->where('response_time', '>', $threshold);
    }

    public function scopeWithErrors($query)
    {
        return $query->where('status_code', '>=', 400);
    }

    public function scopeSuccessful($query)
    {
        return $query->whereBetween('status_code', [200, 299]);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function isSlow(float $threshold = 1.0): bool
    {
        return $this->response_time > $threshold;
    }

    public function isError(): bool
    {
        return $this->status_code >= 400;
    }

    public function getMemoryForHumans(): string
    {
        $bytes = $this->memory_usage;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
