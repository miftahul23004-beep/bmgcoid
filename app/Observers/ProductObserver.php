<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "saved" event.
     */
    public function saved(Product $product): void
    {
        $this->clearProductCache();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // Clean up images
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        $this->clearProductCache();
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->clearProductCache();
    }

    /**
     * Clear all product-related cache
     */
    protected function clearProductCache(): void
    {
        Cache::forget('products.featured');
        Cache::forget('products.popular');
        Cache::forget('homepage_data:id');
        Cache::forget('homepage_data:en');
    }
}
