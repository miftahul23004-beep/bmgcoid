<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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
        
        // Check current file size and extension
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $currentSize = filesize($storagePath) / 1024; // KB
        
        // Skip if already webp and under size limit
        if ($extension === 'webp' && $currentSize <= $maxSizeKb) {
            return $path;
        }
        
        // Generate new webp path
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $newPath = $directory . '/' . $filename . '_opt.webp';
        $newStoragePath = Storage::disk('public')->path($newPath);
        
        try {
            // Start with quality 85 and decrease until under max size
            $quality = 85;
            $minQuality = 20;
            $currentWidth = $maxWidth;
            
            do {
                // Clear file stat cache to get accurate size
                clearstatcache(true, $newStoragePath);
                
                Image::useImageDriver(ImageDriver::Gd)
                    ->load($storagePath)
                    ->width($currentWidth)
                    ->quality($quality)
                    ->save($newStoragePath);
                
                clearstatcache(true, $newStoragePath);
                $newSize = filesize($newStoragePath) / 1024; // KB
                
                \Log::info("Image optimization: quality={$quality}, width={$currentWidth}, size={$newSize}KB, target={$maxSizeKb}KB");
                
                if ($newSize <= $maxSizeKb) {
                    break;
                }
                
                $quality -= 5;
                
                // If quality is too low, reduce dimensions instead
                if ($quality < $minQuality && $currentWidth > 800) {
                    $quality = 70;
                    $currentWidth -= 200;
                }
                
            } while ($quality >= $minQuality || $currentWidth > 800);
            
            // Delete original file
            if (file_exists($storagePath) && $storagePath !== $newStoragePath) {
                unlink($storagePath);
            }
            
            // Rename optimized file to clean name
            $finalPath = $directory . '/' . $filename . '.webp';
            $finalStoragePath = Storage::disk('public')->path($finalPath);
            
            if (file_exists($finalStoragePath) && $finalStoragePath !== $newStoragePath) {
                unlink($finalStoragePath);
            }
            
            rename($newStoragePath, $finalStoragePath);
            
            return $finalPath;
            
        } catch (\Exception $e) {
            \Log::error('Image conversion failed: ' . $e->getMessage());
            return $path;
        }
    }
    
    /**
     * Process uploaded file and convert to WebP
     * 
     * @param UploadedFile|TemporaryUploadedFile $file
     * @param string $directory Storage directory
     * @param int $maxSizeKb Maximum file size in KB
     * @return string WebP file path
     */
    public function processUpload(UploadedFile|TemporaryUploadedFile $file, string $directory, int $maxSizeKb = 200): string
    {
        // Ensure directory exists
        $dirPath = Storage::disk('public')->path($directory);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }
        
        // Get source path
        $sourcePath = $file instanceof TemporaryUploadedFile 
            ? $file->getRealPath() 
            : $file->path();
        
        // Check if GD is available and supports WebP
        if (!$this->canConvertToWebp()) {
            \Log::warning('WebP conversion not available, storing original file');
            $originalPath = $file->store($directory, 'public');
            return $originalPath;
        }
        
        try {
            // Generate unique filename
            $filename = Str::uuid() . '.webp';
            $storagePath = $directory . '/' . $filename;
            $fullPath = Storage::disk('public')->path($storagePath);
            
            // Convert to WebP with optimization
            $quality = 85;
            $maxWidth = 1200;
            
            Image::useImageDriver(ImageDriver::Gd)
                ->load($sourcePath)
                ->width($maxWidth)
                ->quality($quality)
                ->save($fullPath);
            
            // Check file size and reduce quality if needed
            $fileSize = filesize($fullPath) / 1024; // KB
            
            while ($fileSize > $maxSizeKb && $quality > 30) {
                $quality -= 10;
                Image::useImageDriver(ImageDriver::Gd)
                    ->load($sourcePath)
                    ->width($maxWidth)
                    ->quality($quality)
                    ->save($fullPath);
                $fileSize = filesize($fullPath) / 1024;
            }
            
            \Log::info("Image converted to WebP: {$storagePath} ({$fileSize}KB, quality: {$quality})");
            
            return $storagePath;
            
        } catch (\Exception $e) {
            \Log::error('Image upload processing failed: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            
            // Fallback: store original file
            $originalPath = $file->store($directory, 'public');
            return $originalPath;
        }
    }
    
    /**
     * Check if WebP conversion is available
     */
    protected function canConvertToWebp(): bool
    {
        // Check if GD extension is loaded
        if (!extension_loaded('gd')) {
            return false;
        }
        
        // Check if WebP support is available
        $gdInfo = gd_info();
        if (!isset($gdInfo['WebP Support']) || !$gdInfo['WebP Support']) {
            return false;
        }
        
        return true;
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
