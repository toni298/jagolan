<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Bus;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use App\Jobs\OptimizeImageJob;
use Illuminate\Http\UploadedFile;
use Cloudinary\Cloudinary;

class ImageService
{
    protected ?ImageManager $manager = null;
    protected string $driver = 'none';

    /**
     * Default sizes for responsive images
     */
    protected array $defaultSizes = [
        'sm' => 480,
        'md' => 768,
        'lg' => 1024,
        'xl' => 1280,
        '2xl' => 1920,
    ];

    public function __construct()
    {
        $this->initManager();
    }

    /**
     * Auto-detect available image driver
     */
    protected function initManager(): void
    {
        // Try GD first (with try-catch for robustness)
        try {
            $this->manager = new ImageManager(new GdDriver());
            // Test if driver actually works
            $this->manager->create(1, 1);
            $this->driver = 'gd';
            Log::info('ImageService: Using GD driver');
            return;
        } catch (\Exception $e) {
            Log::warning('ImageService: GD driver failed', ['error' => $e->getMessage()]);
        }

        // Try Imagick
        try {
            $this->manager = new ImageManager(new ImagickDriver());
            // Test if driver actually works
            $this->manager->create(1, 1);
            $this->driver = 'imagick';
            Log::info('ImageService: Using Imagick driver');
            return;
        } catch (\Exception $e) {
            Log::warning('ImageService: Imagick driver failed', ['error' => $e->getMessage()]);
        }

        // No driver available - will use fallback methods
        $this->manager = null;
        $this->driver = 'none';
        Log::warning('ImageService: No image driver available (GD/Imagick). Using fallback storage.');
    }

    /**
     * Check if image processing is available
     */
    public function canProcessImages(): bool
    {
        return $this->driver !== 'none';
    }

    /**
     * Get current driver name
     */
    public function getDriverName(): string
    {
        return $this->driver;
    }

    /**
     * Optimize uploaded image (resize + convert to WebP)
     * Returns the relative path for storage
     * Falls back to original file storage if no driver available
     */
    public function optimizeUpload($file, string $directory = 'uploads', int $maxWidth = 1920, int $quality = 80): string
    {
        if (!$this->canProcessImages()) {
            return $this->storeOnCloudinary($file, $directory);
        }

        $fileName = Str::random(20) . '.webp';
        $storagePath = "{$directory}/{$fileName}";

        try {
            $image = $this->manager->read($file->getRealPath());

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $encoded = $image->encode(new WebpEncoder($quality));
            $tempFile = tempnam(sys_get_temp_dir(), 'cloudinary_');
            file_put_contents($tempFile, (string) $encoded);

            $response = app(Cloudinary::class)->uploadApi()->upload($tempFile, [
                'folder' => trim($directory, '/'),
                'public_id' => $fileName,
                'resource_type' => 'image',
            ]);

            @unlink($tempFile);

            return $response['secure_url'] ?? $response['url'] ?? $storagePath;
        } catch (\Throwable $e) {
            Log::warning('ImageService: Cloudinary direct upload fallback triggered', ['error' => $e->getMessage()]);
            return $this->storeOnCloudinary($file, $directory);
        }
    }

    /**
     * Optimize and store image, returning path
     * More flexible version with custom filename
     * Falls back to original file storage if no driver available
     * 
     * Optimized for performance:
     * - Default max width: 1280px (faster processing)
     * - Default quality: 75 (good balance of quality/speed)
     * - Extended time limit to prevent timeout
     */
    public function optimizeAndStore($file, string $directory = 'products', ?string $filename = null, int $maxWidth = 1280, int $quality = 75): string
    {
        @set_time_limit(120);

        if (!$this->canProcessImages()) {
            return $this->storeOnCloudinary($file, $directory, $filename);
        }

        $extension = 'webp';
        $fileName = $filename ?? Str::random(20) . '.' . $extension;
        $storagePath = "{$directory}/{$fileName}";

        try {
            $image = $this->manager->read($file->getRealPath());

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $encoded = $image->encode(new WebpEncoder($quality));
            $tempFile = tempnam(sys_get_temp_dir(), 'cloudinary_');
            file_put_contents($tempFile, (string) $encoded);

            $response = app(Cloudinary::class)->uploadApi()->upload($tempFile, [
                'folder' => trim($directory, '/'),
                'public_id' => $fileName,
                'resource_type' => 'image',
            ]);

            @unlink($tempFile);
            unset($image, $encoded, $tempFile);

            return $response['secure_url'] ?? $response['url'] ?? $storagePath;
        } catch (\Exception $e) {
            Log::error('ImageService: Error during optimization', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            return $this->storeOnCloudinary($file, $directory, $filename);
        }
    }

    /**
     * Store image quickly without conversion (for async processing later)
     * Returns the path immediately - use for fast uploads
     */
    public function storeQuick($file, string $directory = 'products'): string
    {
        return $this->storeOnCloudinary($file, $directory);
    }

    public function storeOnCloudinary(UploadedFile $file, string $directory = 'products', ?string $filename = null): string
    {
        $fileName = $filename ?? Str::random(20) . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $safeDirectory = trim($directory, '/');

        $response = app(Cloudinary::class)->uploadApi()->upload($file->getRealPath(), [
            'folder' => $safeDirectory,
            'public_id' => $fileName,
            'resource_type' => 'image',
        ]);

        return $response['secure_url'] ?? $response['url'] ?? $file->getClientOriginalName();
    }

    /**
     * Optimize and store image asynchronously (for hosting with queue support)
     * Stores original file first, then converts to WebP in background
     * Returns the final WebP path (file will be converted by queue worker)
     */
    public function optimizeAndStoreAsync($file, string $directory = 'products', ?string $filename = null, int $maxWidth = 1280, int $quality = 75): string
    {
        // Store original file first (fast)
        $originalExtension = $file->getClientOriginalExtension();
        $originalFileName = Str::random(20) . '.' . $originalExtension;
        $originalPath = "{$directory}/temp/{$originalFileName}";

        Storage::disk('public')->put($originalPath, file_get_contents($file->getRealPath()));

        // Determine final WebP path
        $webpFileName = $filename ?? Str::random(20) . '.webp';
        $webpPath = "{$directory}/{$webpFileName}";

        // Dispatch job to convert in background
        OptimizeImageJob::dispatch($originalPath, $webpPath, $maxWidth, $quality);

        return $webpPath;
    }

    /**
     * Generate responsive images and WebP versions
     */
    public function generateResponsiveImages(string $sourcePath, array $sizes = []): array
    {
        $sizes = empty($sizes) ? $this->defaultSizes : $sizes;
        $results = [];

        $sourceFullPath = Storage::disk('public')->path($sourcePath);
        if (!file_exists($sourceFullPath)) {
            return ['original' => $sourcePath];
        }

        $pathInfo = pathinfo($sourcePath);
        $basePath = $pathInfo['dirname'];
        $fileName = $pathInfo['filename'];

        // Generate different sizes
        foreach ($sizes as $sizeName => $width) {
            $newFileName = "{$fileName}-{$sizeName}.webp";
            $newPath = "{$basePath}/responsive/{$newFileName}";

            try {
                $image = $this->manager->read($sourceFullPath);

                if ($image->width() > $width) {
                    $image->scale(width: $width);
                }

                $encoded = $image->encode(new WebpEncoder(80));
                Storage::disk('public')->put($newPath, (string) $encoded);

                $results[$sizeName] = [
                    'path' => $newPath,
                    'width' => $width,
                ];
            } catch (\Exception $e) {
                // Skip this size if error
                continue;
            }
        }

        // Also generate WebP of original size
        $webpFileName = "{$fileName}.webp";
        $webpPath = "{$basePath}/responsive/{$webpFileName}";

        try {
            $image = $this->manager->read($sourceFullPath);
            $encoded = $image->encode(new WebpEncoder(80));
            Storage::disk('public')->put($webpPath, (string) $encoded);
            $results['original_webp'] = ['path' => $webpPath];
        } catch (\Exception $e) {
            // Skip if error
        }

        $results['original'] = ['path' => $sourcePath];

        return $results;
    }

    /**
     * Convert image to WebP format
     */
    public function convertToWebP(string $source, string $destination, ?int $maxWidth = null, int $quality = 80): bool
    {
        $sourceFullPath = Storage::disk('public')->path($source);

        if (!file_exists($sourceFullPath)) {
            return false;
        }

        try {
            $image = $this->manager->read($sourceFullPath);

            // Resize if maxWidth specified
            if ($maxWidth !== null && $image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $encoded = $image->encode(new WebpEncoder($quality));
            Storage::disk('public')->put($destination, (string) $encoded);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate srcset string for responsive images
     */
    public function generateSrcset(string $sourcePath, array $sizes = []): string
    {
        $images = $this->generateResponsiveImages($sourcePath, $sizes);
        $srcset = [];

        foreach ($images as $sizeName => $data) {
            if ($sizeName === 'original' || $sizeName === 'original_webp') {
                continue;
            }
            if (isset($data['width'])) {
                $srcset[] = image_url($data['path']) . ' ' . $data['width'] . 'w';
            }
        }

        return implode(', ', $srcset);
    }

    /**
     * Get WebP path for an image
     */
    public function getWebPPath(string $sourcePath): string
    {
        $pathInfo = pathinfo($sourcePath);
        $basePath = $pathInfo['dirname'];
        $fileName = $pathInfo['filename'];

        $webpPath = "{$basePath}/responsive/{$fileName}.webp";

        if (Storage::disk('public')->exists($webpPath)) {
            return $webpPath;
        }

        return $sourcePath;
    }

    /**
     * Delete image and its responsive versions
     */
    public function deleteImageWithResponsive(string $sourcePath): bool
    {
        $pathInfo = pathinfo($sourcePath);
        $basePath = $pathInfo['dirname'];
        $fileName = $pathInfo['filename'];

        // Delete original
        Storage::disk('public')->delete($sourcePath);

        // Delete responsive versions
        $responsivePath = "{$basePath}/responsive";
        if (Storage::disk('public')->exists($responsivePath)) {
            $files = Storage::disk('public')->files($responsivePath);
            foreach ($files as $file) {
                if (str_contains($file, $fileName)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        return true;
    }
}
