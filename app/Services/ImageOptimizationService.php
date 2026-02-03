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
        
        // Get source path - handle both TemporaryUploadedFile and regular UploadedFile
        $sourcePath = null;
        if ($file instanceof TemporaryUploadedFile) {
            $sourcePath = $file->getRealPath();
            // Fallback: try to get path from temporary disk
            if (!$sourcePath || !file_exists($sourcePath)) {
                $sourcePath = $file->path();
            }
        } else {
            $sourcePath = $file->path();
        }
        
        // Debug logging
        \Log::info("processUpload called", [
            'directory' => $directory,
            'maxSizeKb' => $maxSizeKb,
            'file_class' => get_class($file),
            'sourcePath' => $sourcePath,
            'source_exists' => $sourcePath ? file_exists($sourcePath) : false,
        ]);
        
        // Validate source file exists
        if (!$sourcePath || !file_exists($sourcePath)) {
            \Log::error("Source file not found, storing with default method", [
                'sourcePath' => $sourcePath
            ]);
            $originalPath = $file->store($directory, 'public');
            return $originalPath;
        }
        
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
            // For hero slides, use larger width, for others use smaller
            $maxWidth = str_contains($directory, 'hero') ? 1600 : 1200;
            $minWidth = 800;
            $quality = 80;
            $minQuality = 25;
            
            // Get original dimensions
            $originalInfo = getimagesize($sourcePath);
            $originalWidth = $originalInfo[0] ?? 1920;
            
            // Don't upscale if original is smaller
            if ($originalWidth < $maxWidth) {
                $maxWidth = $originalWidth;
            }
            
            // First pass with initial settings
            Image::useImageDriver(ImageDriver::Gd)
                ->load($sourcePath)
                ->width($maxWidth)
                ->quality($quality)
                ->save($fullPath);
            
            $fileSize = filesize($fullPath) / 1024; // KB
            
            // Reduce quality first (faster than resizing)
            while ($fileSize > $maxSizeKb && $quality > $minQuality) {
                $quality -= 5;
                Image::useImageDriver(ImageDriver::Gd)
                    ->load($sourcePath)
                    ->width($maxWidth)
                    ->quality($quality)
                    ->save($fullPath);
                $fileSize = filesize($fullPath) / 1024;
            }
            
            // If still too large, reduce dimensions
            while ($fileSize > $maxSizeKb && $maxWidth > $minWidth) {
                $maxWidth -= 100;
                Image::useImageDriver(ImageDriver::Gd)
                    ->load($sourcePath)
                    ->width($maxWidth)
                    ->quality($minQuality)
                    ->save($fullPath);
                $fileSize = filesize($fullPath) / 1024;
            }
            
            \Log::info("Image converted to WebP: {$storagePath} ({$fileSize}KB, quality: {$quality}, width: {$maxWidth})");
            
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
