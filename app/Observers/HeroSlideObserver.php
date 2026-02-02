<?php

namespace App\Observers;

use App\Models\HeroSlide;
use App\Services\ImageOptimizationService;
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
     * Handle the HeroSlide "updating" event.
     */
    public function updating(HeroSlide $heroSlide): void
    {
        $this->processImages($heroSlide);
    }

    /**
     * Process and optimize images
     */
    protected function processImages(HeroSlide $heroSlide): void
    {
        // Process main image (desktop) - max 200KB, max width 1920px
        if ($heroSlide->isDirty('image') && $heroSlide->image) {
            $heroSlide->image = $this->imageService->convertToWebp(
                $heroSlide->image,
                200,  // max 200KB
                1920  // max width for desktop hero
            );
        }

        // Process mobile image - max 150KB, max width 750px
        if ($heroSlide->isDirty('mobile_image') && $heroSlide->mobile_image) {
            $heroSlide->mobile_image = $this->imageService->convertToWebp(
                $heroSlide->mobile_image,
                150,  // max 150KB for mobile
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
    }
}
