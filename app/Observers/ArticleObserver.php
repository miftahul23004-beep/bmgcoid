<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\ImageDriver;

class ArticleObserver
{
    /**
     * Handle the Article "saving" event.
     * Converts featured image to optimized WebP format
     */
    public function saving(Article $article): void
    {
        if ($article->isDirty('featured_image') && $article->featured_image) {
            $this->optimizeImage($article);
        }
    }

    /**
     * Optimize and convert image to WebP
     */
    protected function optimizeImage(Article $article): void
    {
        $originalPath = $article->featured_image;
        
        // Skip if already webp or if it's a URL
        if (str_ends_with(strtolower($originalPath), '.webp') || str_starts_with($originalPath, 'http')) {
            return;
        }

        $storagePath = storage_path('app/public/' . $originalPath);
        
        if (!file_exists($storagePath)) {
            return;
        }

        // Generate new webp filename
        $pathInfo = pathinfo($originalPath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        $webpStoragePath = storage_path('app/public/' . $webpPath);

        try {
            // Use Spatie Image to convert and optimize
            Image::useImageDriver(ImageDriver::Gd)
                ->loadFile($storagePath)
                ->width(1200)
                ->quality(85)
                ->save($webpStoragePath);

            // Check file size and reduce quality if > 200KB
            $fileSize = filesize($webpStoragePath);
            $maxSize = 200 * 1024; // 200KB
            
            if ($fileSize > $maxSize) {
                $quality = 85;
                while ($fileSize > $maxSize && $quality > 20) {
                    $quality -= 10;
                    Image::useImageDriver(ImageDriver::Gd)
                        ->loadFile($storagePath)
                        ->width(1200)
                        ->quality($quality)
                        ->save($webpStoragePath);
                    $fileSize = filesize($webpStoragePath);
                }
            }

            // Delete original file if different from webp
            if ($originalPath !== $webpPath && file_exists($storagePath)) {
                unlink($storagePath);
            }

            // Update the article with new webp path
            $article->featured_image = $webpPath;
            
        } catch (\Exception $e) {
            // Log error but don't fail the save
            \Log::error('Failed to optimize article image: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        // Delete featured image when article is deleted
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
    }
}
