<?php

namespace App\Observers;

use App\Models\HeroSlide;
use App\Services\CloudflarePurgeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HeroSlideObserver
{
    /**
     * Handle the HeroSlide "saved" event (after create or update)
     */
    public function saved(HeroSlide $heroSlide): void
    {
        $this->clearCacheAndPurge();
    }

    /**
     * Handle the HeroSlide "deleted" event.
     */
    public function deleted(HeroSlide $heroSlide): void
    {
        // Delete associated images
        if ($heroSlide->image) {
            Storage::disk('public')->delete($heroSlide->image);
        }
        
        if ($heroSlide->mobile_image) {
            Storage::disk('public')->delete($heroSlide->mobile_image);
        }

        $this->clearCacheAndPurge();
    }

    /**
     * Clear Laravel cache and purge Cloudflare
     */
    protected function clearCacheAndPurge(): void
    {
        // Clear hero slides cache
        Cache::forget('hero_slides.displayable');

        // Purge Cloudflare homepage
        try {
            app(CloudflarePurgeService::class)->purgeHomepage();
        } catch (\Exception $e) {
            // Silently fail - don't break the save
        }
    }
}
