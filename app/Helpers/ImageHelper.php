<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the correct image URL for any product
     */
    public static function getProductImage($product, $default = 'images/placeholder.jpg')
    {
        if (!$product || !$product->image) {
            return asset($default);
        }

        $image = $product->image;

        // Case 1: It's already a full URL (http:// or https://)
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        // Case 2: It's a local storage path
        if (Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        // Case 3: Check if it's in public directory
        if (file_exists(public_path($image))) {
            return asset($image);
        }

        // Fallback to default
        return asset($default);
    }

    /**
     * Get image with error handling and fallback
     */
    public static function getProductImageWithFallback($product, $default = 'images/placeholder.jpg')
    {
        $url = self::getProductImage($product, $default);

        // Add onerror fallback for browser
        return $url;
    }
}
