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
            \Log::warning('WebP conversion: file not found', ['path' => $storagePath]);
            return $path;
        }
        
        // Skip if already webp and under size limit
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $currentSize = filesize($storagePath) / 1024; // KB
        
        if ($extension === 'webp' && $currentSize <= $maxSizeKb) {
            return $path;
        }
        
        // Generate new webp path with UUID to avoid conflicts
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $newPath = $directory . '/' . Str::uuid() . '.webp';
        $newStoragePath = Storage::disk('public')->path($newPath);
        
        try {
            // Suppress GD warnings (like libpng iCCP warnings)
            $errorLevel = error_reporting();
            error_reporting($errorLevel & ~E_WARNING);
            
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
            
            // Restore error reporting
            error_reporting($errorLevel);
            
            // If still too large, reduce dimensions
            if ($newSize > $maxSizeKb && $maxWidth > 400) {
                // Clean up current attempt
                if (file_exists($newStoragePath)) {
                    unlink($newStoragePath);
                }
                return $this->convertToWebp($path, $maxSizeKb, $maxWidth - 100);
            }
            
            // Delete original file if conversion successful
            if (file_exists($newStoragePath) && file_exists($storagePath)) {
                unlink($storagePath);
            }
            
            \Log::info("Image converted to WebP: {$newPath} ({$newSize}KB, quality: {$quality})");
            
            return $newPath;
            
        } catch (\Exception $e) {
            \Log::error('Image conversion failed: ' . $e->getMessage());
            return $path;
        }
    }

    /**
     * Convert an existing stored image to ICO format
     * 
     * @param string $path Storage path of the image
     * @param string $directory Output directory
     * @return string New ICO file path or original path if conversion fails
     */
    public function convertToIco(string $path, string $directory = 'settings'): string
    {
        $storagePath = Storage::disk('public')->path($path);
        
        if (!file_exists($storagePath)) {
            \Log::warning('ICO conversion: file not found', ['path' => $storagePath]);
            return $path;
        }
        
        // Already ICO, return as is
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension === 'ico') {
            return $path;
        }
        
        try {
            // Suppress warnings from GD (like libpng iCCP warnings)
            $errorLevel = error_reporting();
            error_reporting($errorLevel & ~E_WARNING);
            
            // Get image info
            $sourceInfo = @getimagesize($storagePath);
            if (!$sourceInfo) {
                error_reporting($errorLevel);
                \Log::warning('ICO conversion: cannot get image size', ['path' => $storagePath]);
                return $path;
            }
            
            $mimeType = $sourceInfo['mime'] ?? '';
            $sourceImage = null;
            
            // Create GD image from source
            switch ($mimeType) {
                case 'image/jpeg':
                    $sourceImage = @imagecreatefromjpeg($storagePath);
                    break;
                case 'image/png':
                    $sourceImage = @imagecreatefrompng($storagePath);
                    break;
                case 'image/gif':
                    $sourceImage = @imagecreatefromgif($storagePath);
                    break;
                case 'image/webp':
                    $sourceImage = @imagecreatefromwebp($storagePath);
                    break;
                default:
                    $sourceImage = @imagecreatefrompng($storagePath) ?: @imagecreatefromjpeg($storagePath);
            }
            
            // Restore error reporting
            error_reporting($errorLevel);
            
            if (!$sourceImage) {
                \Log::warning('ICO conversion: failed to create GD image', ['mime' => $mimeType]);
                return $path;
            }
            
            // Generate new ICO path
            $filename = Str::uuid() . '.ico';
            $newPath = $directory . '/' . $filename;
            $fullPath = Storage::disk('public')->path($newPath);
            
            // Create ICO with 16x16 and 32x32 sizes
            $sizes = [16, 32];
            $icoData = $this->createIcoData($sourceImage, $sizes);
            
            // Write ICO file
            file_put_contents($fullPath, $icoData);
            
            // Clean up
            imagedestroy($sourceImage);
            
            $fileSize = filesize($fullPath) / 1024;
            \Log::info("Favicon converted to ICO: {$newPath} ({$fileSize}KB)");
            
            return $newPath;
            
        } catch (\Exception $e) {
            \Log::error('ICO conversion failed: ' . $e->getMessage());
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
            $minWidth = 600; // Lower minimum for aggressive compression
            $quality = 75;   // Start lower for better compression
            $minQuality = 20; // Lower minimum quality for <50KB target
            
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

    /**
     * Process favicon upload and convert to ICO format
     * Creates a small, optimized favicon.ico file
     * 
     * @param UploadedFile|TemporaryUploadedFile $file
     * @param string $directory Storage directory
     * @param int $size Favicon size in pixels (16, 32, 48, etc)
     * @return string ICO file path
     */
    public function processFaviconUpload(UploadedFile|TemporaryUploadedFile $file, string $directory, int $size = 32): string
    {
        // Ensure directory exists
        $dirPath = Storage::disk('public')->path($directory);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }
        
        // Get source path
        $sourcePath = null;
        if ($file instanceof TemporaryUploadedFile) {
            $sourcePath = $file->getRealPath();
            if (!$sourcePath || !file_exists($sourcePath)) {
                $sourcePath = $file->path();
            }
        } else {
            $sourcePath = $file->path();
        }
        
        // Validate source file exists
        if (!$sourcePath || !file_exists($sourcePath)) {
            \Log::error("Favicon source file not found");
            $originalPath = $file->store($directory, 'public');
            return $originalPath;
        }
        
        try {
            // Generate unique filename
            $filename = Str::uuid() . '.ico';
            $storagePath = $directory . '/' . $filename;
            $fullPath = Storage::disk('public')->path($storagePath);
            
            // Create GD image from source
            $sourceInfo = getimagesize($sourcePath);
            $mimeType = $sourceInfo['mime'] ?? '';
            
            switch ($mimeType) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($sourcePath);
                    break;
                case 'image/webp':
                    $sourceImage = imagecreatefromwebp($sourcePath);
                    break;
                case 'image/x-icon':
                case 'image/vnd.microsoft.icon':
                    // Already ICO, just copy and optimize if needed
                    $file->storeAs($directory, $filename, 'public');
                    return $storagePath;
                default:
                    // Try to load as PNG by default
                    $sourceImage = @imagecreatefrompng($sourcePath) ?: @imagecreatefromjpeg($sourcePath);
            }
            
            if (!$sourceImage) {
                throw new \Exception("Failed to create image from source");
            }
            
            // Create favicon with multiple sizes for best compatibility
            // ICO format can contain multiple sizes: 16x16, 32x32, 48x48
            $sizes = [16, 32]; // Minimal sizes for smallest file
            $icoData = $this->createIcoData($sourceImage, $sizes);
            
            // Write ICO file
            file_put_contents($fullPath, $icoData);
            
            // Clean up
            imagedestroy($sourceImage);
            
            $fileSize = filesize($fullPath) / 1024;
            \Log::info("Favicon converted to ICO: {$storagePath} ({$fileSize}KB, sizes: " . implode(',', $sizes) . ")");
            
            return $storagePath;
            
        } catch (\Exception $e) {
            \Log::error('Favicon conversion failed: ' . $e->getMessage());
            
            // Fallback: store original file
            $originalPath = $file->store($directory, 'public');
            return $originalPath;
        }
    }
    
    /**
     * Create ICO binary data from GD image
     * 
     * @param resource $image GD image resource
     * @param array $sizes Array of sizes to include
     * @return string Binary ICO data
     */
    protected function createIcoData($image, array $sizes = [16, 32]): string
    {
        $images = [];
        
        foreach ($sizes as $size) {
            // Create resized image
            $resized = imagecreatetruecolor($size, $size);
            
            // Preserve transparency
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $transparent);
            imagealphablending($resized, true);
            
            // High quality resize
            imagecopyresampled(
                $resized, $image,
                0, 0, 0, 0,
                $size, $size,
                imagesx($image), imagesy($image)
            );
            
            // Convert to PNG binary
            ob_start();
            imagepng($resized, null, 9); // Max compression
            $pngData = ob_get_clean();
            
            $images[] = [
                'size' => $size,
                'data' => $pngData
            ];
            
            imagedestroy($resized);
        }
        
        // Build ICO file structure
        $iconDir = '';
        $iconImages = '';
        $offset = 6 + (count($images) * 16); // Header + directory entries
        
        // ICONDIR header
        $iconDir .= pack('v', 0);        // Reserved (0)
        $iconDir .= pack('v', 1);        // Image type (1 = ICO)
        $iconDir .= pack('v', count($images)); // Number of images
        
        // ICONDIRENTRY for each image
        foreach ($images as $img) {
            $size = $img['size'];
            $data = $img['data'];
            $dataLen = strlen($data);
            
            $iconDir .= pack('C', $size == 256 ? 0 : $size); // Width
            $iconDir .= pack('C', $size == 256 ? 0 : $size); // Height
            $iconDir .= pack('C', 0);        // Color palette (0 = no palette)
            $iconDir .= pack('C', 0);        // Reserved
            $iconDir .= pack('v', 1);        // Color planes
            $iconDir .= pack('v', 32);       // Bits per pixel
            $iconDir .= pack('V', $dataLen); // Size of image data
            $iconDir .= pack('V', $offset);  // Offset to image data
            
            $iconImages .= $data;
            $offset += $dataLen;
        }
        
        return $iconDir . $iconImages;
    }
}
