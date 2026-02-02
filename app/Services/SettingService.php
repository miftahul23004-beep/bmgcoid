<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected int $cacheTime = 3600; // 1 hour
    
    // In-memory cache to prevent duplicate DB/cache lookups within same request
    protected static array $memoryCache = [];

    /**
     * Get a setting value by key
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $group = count($parts) > 1 ? $parts[0] : 'general';
        $settingKey = count($parts) > 1 ? $parts[1] : $parts[0];

        $settings = $this->getGroup($group);

        return $settings[$settingKey] ?? $default;
    }

    /**
     * Get all settings from a group
     */
    public function getGroup(string $group): array
    {
        // Check in-memory cache first
        if (isset(static::$memoryCache["group.{$group}"])) {
            return static::$memoryCache["group.{$group}"];
        }
        
        $result = Cache::remember("settings.{$group}", $this->cacheTime, function () use ($group) {
            return Setting::where('group', $group)
                ->pluck('value', 'key')
                ->map(function ($value, $key) {
                    // Try to decode JSON
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return $decoded;
                    }
                    // Handle boolean
                    if ($value === '1' || $value === '0') {
                        return (bool) $value;
                    }
                    return $value;
                })
                ->toArray();
        });
        
        // Store in memory cache
        static::$memoryCache["group.{$group}"] = $result;
        
        return $result;
    }

    /**
     * Get all settings
     */
    public function all(): array
    {
        return Cache::remember('settings.all', $this->cacheTime, function () {
            return Setting::all()
                ->groupBy('group')
                ->map(function ($items) {
                    return $items->pluck('value', 'key')->toArray();
                })
                ->toArray();
        });
    }

    /**
     * Set a setting value
     */
    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        $type = 'text';
        
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
            $type = 'boolean';
        } elseif (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        Setting::updateOrCreate(
            ['key' => $key, 'group' => $group],
            ['value' => $value, 'type' => $type]
        );

        $this->clearCache($group);
    }

    /**
     * Clear settings cache
     */
    public function clearCache(?string $group = null): void
    {
        if ($group) {
            Cache::forget("settings.{$group}");
            unset(static::$memoryCache["group.{$group}"]);
        } else {
            Cache::forget('settings.all');
            $groups = ['general', 'contact', 'social', 'marketplace', 'seo'];
            foreach ($groups as $g) {
                Cache::forget("settings.{$g}");
                unset(static::$memoryCache["group.{$g}"]);
            }
        }
        // Clear companyInfo and socialLinks memory cache too
        unset(static::$memoryCache['companyInfo']);
        unset(static::$memoryCache['socialLinks']);
    }

    /**
     * Get company info
     */
    public function getCompanyInfo(): array
    {
        // Check in-memory cache first
        if (isset(static::$memoryCache['companyInfo'])) {
            return static::$memoryCache['companyInfo'];
        }
        
        $result = array_merge(
            $this->getGroup('general'),
            $this->getGroup('contact')
        );
        
        static::$memoryCache['companyInfo'] = $result;
        
        return $result;
    }

    /**
     * Get social media links
     */
    public function getSocialLinks(): array
    {
        // Check in-memory cache first
        if (isset(static::$memoryCache['socialLinks'])) {
            return static::$memoryCache['socialLinks'];
        }
        
        $result = $this->getGroup('social');
        
        static::$memoryCache['socialLinks'] = $result;
        
        return $result;
    }

    /**
     * Get marketplace links
     */
    public function getMarketplaceLinks(): array
    {
        return $this->getGroup('marketplace');
    }

    /**
     * Get SEO settings
     */
    public function getSeoSettings(): array
    {
        return $this->getGroup('seo');
    }
}
