<?php

namespace App\Observers;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Storage;

class HeroSlideObserver
{
    /**
     * Handle the HeroSlide "deleted" event.
     * Only handle file cleanup - image processing is done in HeroSlideForm
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
