<?php

namespace App\Observers;

use App\Models\HeroSlide;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HeroSlideObserver
{
    public function __construct(
        protected ImageOptimizationService $imageService
    ) {}

    /**
     * Handle the HeroSlide "creating" event.
     */
    public function creating(HeroSlide $heroSlide): void
    {
        $this->processImages($heroSlide);
    }

    /**
     * Handle the HeroSlide "created" event.
     */
    public function created(HeroSlide $heroSlide): void
    {
        $this->clearCache();
    }

    /**
     * Handle the HeroSlide "updating" event.
     */
    public function updating(HeroSlide $heroSlide): void
    {
        $this->processImages($heroSlide);
    }

    /**
     * Handle the HeroSlide "updated" event.
     */
    public function updated(HeroSlide $heroSlide): void
    {
        $this->clearCache();
    }

    /**
     * Process and optimize images
     */
    protected function processImages(HeroSlide $heroSlide): void
    {
        // Process main image (desktop) - max 95KB, max width 1920px
        if ($heroSlide->isDirty('image') && $heroSlide->image) {
            $heroSlide->image = $this->imageService->convertToWebp(
                $heroSlide->image,
                95,   // max 95KB for optimal LCP
                1920  // max width for desktop hero
            );
        }

        // Process mobile image - max 80KB, max width 750px
        if ($heroSlide->isDirty('mobile_image') && $heroSlide->mobile_image) {
            $heroSlide->mobile_image = $this->imageService->convertToWebp(
                $heroSlide->mobile_image,
                80,   // max 80KB for mobile LCP
                750   // max width for mobile
            );
        }
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

        $this->clearCache();
    }

    /**
     * Clear hero slides cache
     */
    protected function clearCache(): void
    {
        Cache::forget('hero_slides.displayable');
    }
}
