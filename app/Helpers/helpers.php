<?php

use App\Helpers\ImageHelper;

if (!function_exists('product_image')) {
    /**
     * Global helper function for product images
     */
    function product_image($product, $default = 'images/placeholder.jpg')
    {
        return ImageHelper::getProductImage($product, $default);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Global helper for currency formatting
     */
    function format_currency($amount)
    {
        return 'KES ' . number_format($amount, 2);
    }
}
