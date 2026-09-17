<?php

namespace App\Helpers;

class ImageHelper
{
    public static function resolveImageUrl(string $src): string
    {
        return image_url($src);
    }

    public static function isRemoteUrl(string $src): bool
    {
        return is_string($src) && preg_match('/^https?:\/\//i', $src) === 1;
    }

    /**
     * Generate lazy loading image tag with optional srcset
     */
    public static function optimizedImage(
        string $src,
        string $alt = '',
        ?string $class = null,
        ?int $width = null,
        ?int $height = null,
        string $loading = 'lazy',
        bool $fetchpriority = false
    ): string {
        $attributes = [
            'src' => self::resolveImageUrl($src),
            'alt' => $alt,
            'loading' => $loading,
            'decoding' => 'async',
        ];

        if ($class) {
            $attributes['class'] = $class;
        }

        if ($width) {
            $attributes['width'] = $width;
        }

        if ($height) {
            $attributes['height'] = $height;
        }

        if ($fetchpriority) {
            $attributes['fetchpriority'] = 'high';
        }

        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= ' ' . $key . '="' . e($value) . '"';
        }

        return '<img' . $attrString . '>';
    }

    /**
     * Generate picture element with WebP support (if files exist)
     */
    public static function picture(
        string $src,
        string $alt = '',
        ?string $class = null,
        ?int $width = null,
        ?int $height = null,
        string $loading = 'lazy'
    ): string {
        $webpSrc = self::getWebPPath($src);
        
        $html = '<picture>';
        
        // Add WebP source if available
        if ($webpSrc !== $src) {
            $html .= '<source srcset="' . self::resolveImageUrl($webpSrc) . '" type="image/webp">';
        }
        
        // Add fallback image
        $html .= self::optimizedImage($src, $alt, $class, $width, $height, $loading);
        
        $html .= '</picture>';
        
        return $html;
    }

    /**
     * Get WebP path if exists
     */
    public static function getWebPPath(string $src): string
    {
        if (self::isRemoteUrl($src)) {
            return $src;
        }

        $pathInfo = pathinfo($src);
        $basePath = $pathInfo['dirname'] ?? '';
        $fileName = $pathInfo['filename'] ?? '';
        
        $webpPath = $basePath . '/responsive/' . $fileName . '.webp';
        
        if (file_exists(public_path($webpPath))) {
            return $webpPath;
        }

        return $src;
    }

    /**
     * Generate srcset string if responsive images exist
     */
    public static function generateSrcset(string $src): ?string
    {
        $pathInfo = pathinfo($src);
        $basePath = $pathInfo['dirname'] ?? '';
        $fileName = $pathInfo['filename'] ?? '';
        
        $sizes = [
            'sm' => 480,
            'md' => 768,
            'lg' => 1024,
            'xl' => 1280,
            '2xl' => 1920,
        ];

        $srcset = [];
        
        foreach ($sizes as $sizeName => $width) {
            $responsivePath = $basePath . '/responsive/' . $fileName . '-' . $sizeName . '.webp';
            
            if (file_exists(public_path($responsivePath))) {
                $srcset[] = self::resolveImageUrl($responsivePath) . ' ' . $width . 'w';
            }
        }

        return empty($srcset) ? null : implode(', ', $srcset);
    }

    /**
     * Generate responsive picture element with srcset
     */
    public static function responsivePicture(
        string $src,
        string $alt = '',
        ?string $class = null,
        string $sizes = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw',
        string $loading = 'lazy'
    ): string {
        $srcset = self::generateSrcset($src);
        $webpSrc = self::getWebPPath($src);
        
        $html = '<picture>';
        
        // Add WebP source with srcset if available
        if ($srcset) {
            $html .= '<source srcset="' . $srcset . '" sizes="' . $sizes . '" type="image/webp">';
        } elseif ($webpSrc !== $src) {
            $html .= '<source srcset="' . self::resolveImageUrl($webpSrc) . '" type="image/webp">';
        }
        
        // Add fallback image with srcset if available
        $imgAttrs = 'src="' . self::resolveImageUrl($src) . '" alt="' . e($alt) . '" decoding="async" loading="' . $loading . '"';
        
        if ($class) {
            $imgAttrs .= ' class="' . e($class) . '"';
        }
        
        if ($srcset) {
            $imgAttrs .= ' srcset="' . $srcset . '" sizes="' . $sizes . '"';
        }
        
        $html .= '<img ' . $imgAttrs . '>';
        $html .= '</picture>';
        
        return $html;
    }
}
