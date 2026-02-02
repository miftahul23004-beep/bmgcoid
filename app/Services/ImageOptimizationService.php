<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\ImageDriver;

class ImageOptimizationService
{
    /**
     * Convert image to WebP format with size optimization
     * 
     * @param string $path Original file path in storage
     * @param int $maxSizeKb Maximum file size in KB (default 200KB)
     * @param int $maxWidth Maximum width in pixels (default 1200)
     * @return string New WebP file path
     */
    public function convertToWebp(string $path, int $maxSizeKb = 200, int $maxWidth = 1200): string
    {
        $storagePath = Storage::disk('public')->path($path);
        
        // Skip if file doesn't exist
        if (!file_exists($storagePath)) {
            return $path;
        }
        
        // Skip if already webp and under size limit
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $currentSize = filesize($storagePath) / 1024; // KB
        
        if ($extension === 'webp' && $currentSize <= $maxSizeKb) {
            return $path;
        }
        
        // Generate new webp path
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $newPath = $directory . '/' . $filename . '.webp';
        $newStoragePath = Storage::disk('public')->path($newPath);
        
        try {
            // Start with quality 85 and decrease until under max size
            $quality = 85;
            $minQuality = 30;
            
            do {
                Image::useImageDriver(ImageDriver::Gd)
                    ->load($storagePath)
                    ->width($maxWidth)
                    ->quality($quality)
                    ->save($newStoragePath);
                
                $newSize = filesize($newStoragePath) / 1024; // KB
                
                if ($newSize <= $maxSizeKb) {
                    break;
                }
                
                $quality -= 10;
                
            } while ($quality >= $minQuality);
            
            // If still too large, reduce dimensions
            if ($newSize > $maxSizeKb && $maxWidth > 800) {
                $this->convertToWebp($path, $maxSizeKb, $maxWidth - 200);
            }
            
            // Delete original file if different extension
            if ($extension !== 'webp' && file_exists($storagePath)) {
                unlink($storagePath);
            }
            
            return $newPath;
            
        } catch (\Exception $e) {
            \Log::error('Image conversion failed: ' . $e->getMessage());
            return $path;
        }
    }
    
    /**
     * Process uploaded file and convert to WebP
     * 
     * @param UploadedFile $file
     * @param string $directory Storage directory
     * @param int $maxSizeKb Maximum file size in KB
     * @return string WebP file path
     */
    public function processUpload(UploadedFile $file, string $directory, int $maxSizeKb = 200): string
    {
        // Store original file first
        $originalPath = $file->store($directory, 'public');
        
        // Convert to WebP
        return $this->convertToWebp($originalPath, $maxSizeKb);
    }
    
    /**
     * Batch convert existing images in a directory
     * 
     * @param string $directory
     * @param int $maxSizeKb
     * @return array Results
     */
    public function batchConvert(string $directory, int $maxSizeKb = 200): array
    {
        $results = ['success' => 0, 'failed' => 0, 'skipped' => 0];
        $files = Storage::disk('public')->files($directory);
        
        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $results['skipped']++;
                continue;
            }
            
            try {
                $this->convertToWebp($file, $maxSizeKb);
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
            }
        }
        
        return $results;
    }
}
