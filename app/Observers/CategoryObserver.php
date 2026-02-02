<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\ImageDriver;

class CategoryObserver
{
    /**
     * Handle the Category "saved" event.
     * Process image conversion after save to ensure proper UI update
     */
    public function saved(Category $category): void
    {
        $needsUpdate = false;
        
        // Convert icon to optimized WebP
        if ($category->icon && !str_ends_with($category->icon, '.webp')) {
            $newPath = $this->convertToOptimizedWebp($category->icon, 'categories/icons');
            if ($newPath) {
                $category->icon = $newPath;
                $needsUpdate = true;
            }
        }
        
        // Convert image to optimized WebP
        if ($category->image && !str_ends_with($category->image, '.webp')) {
            $newPath = $this->convertToOptimizedWebp($category->image, 'categories/images');
            if ($newPath) {
                $category->image = $newPath;
                $needsUpdate = true;
            }
        }
        
        // Update without triggering observer again
        if ($needsUpdate) {
            $category->saveQuietly();
        }
        
        // Clear category cache so changes appear immediately on frontend
        $this->clearCategoryCache();
    }

    /**
     * Clear all category-related cache
     */
    protected function clearCategoryCache(): void
    {
        Cache::forget('categories.active');
        Cache::forget('categories.navigation');
        Cache::forget('categories.featured');
    }

    /**
     * Convert image to optimized WebP format
     */
    protected function convertToOptimizedWebp(string $imagePath, string $directory): ?string
    {
        $fullPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        // Generate new filename with webp extension
        $pathInfo = pathinfo($imagePath);
        $newFilename = $pathInfo['filename'] . '.webp';
        $newPath = $directory . '/' . $newFilename;
        $newFullPath = storage_path('app/public/' . $newPath);

        // Ensure directory exists
        $newDir = dirname($newFullPath);
        if (!is_dir($newDir)) {
            mkdir($newDir, 0755, true);
        }

        try {
            // Start with quality 85 and reduce until under 200KB
            $quality = 85;
            $maxSize = 200 * 1024; // 200KB

            // First conversion
            Image::useImageDriver(ImageDriver::Gd)
                ->loadFile($fullPath)
                ->width(1200)
                ->quality($quality)
                ->save($newFullPath);

            // Check file size and reduce quality if needed
            $fileSize = filesize($newFullPath);
            while ($fileSize > $maxSize && $quality > 20) {
                $quality -= 10;
                Image::useImageDriver(ImageDriver::Gd)
                    ->loadFile($fullPath)
                    ->width(1200)
                    ->quality($quality)
                    ->save($newFullPath);
                $fileSize = filesize($newFullPath);
            }

            // Delete original file if conversion successful
            if (file_exists($newFullPath) && $fullPath !== $newFullPath) {
                unlink($fullPath);
            }

            return $newPath;

        } catch (\Exception $e) {
            \Log::error('Failed to convert category image to WebP: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        // Clean up images when category is deleted
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }
        
        // Clear category cache
        $this->clearCategoryCache();
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->clearCategoryCache();
    }
}
