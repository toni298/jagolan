<?php

use Illuminate\Support\Str;

if (! function_exists('image_url')) {
    /**
     * Return a usable image URL for both local storage and external cloud URLs.
     */
    function image_url($value, $fallback = null): string
    {
        if (empty($value)) {
            return $fallback ?? '';
        }

        if (is_string($value) && preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        if (is_string($value) && preg_match('/^https?:\/\//i', (string) $fallback)) {
            return $fallback;
        }

        if (is_string($value) && preg_match('/^\/\//', $value)) {
            return 'https:' . $value;
        }

        if (is_string($value) && Str::startsWith($value, 'data:')) {
            return $value;
        }

        if (is_string($value) && Str::startsWith($value, 'storage/')) {
            return asset($value);
        }

        if (is_string($value) && Str::startsWith($value, '/storage/')) {
            return asset(ltrim($value, '/'));
        }

        if (is_string($value) && Str::contains($value, 'cloudinary.com')) {
            return $value;
        }

        return asset('storage/' . ltrim((string) $value, '/'));
    }
}
