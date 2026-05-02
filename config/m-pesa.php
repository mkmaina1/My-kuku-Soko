<?php

return [
    /*
    |--------------------------------------------------------------------------
    | M-Pesa Environment
    |--------------------------------------------------------------------------
    |
    | This option determines the environment you're running in.
    | 'sandbox' for testing, 'production' for live transactions
    |
    */
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Credentials
    |--------------------------------------------------------------------------
    |
    | Your M-Pesa API credentials from Safaricom
    |
    */
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'business_shortcode' => env('MPESA_BUSINESS_SHORTCODE', '174379'),
    'passkey' => env('MPESA_PASSKEY'),

    /*
    |--------------------------------------------------------------------------
    | M-Pesa URLs
    |--------------------------------------------------------------------------
    |
    | The URLs for callbacks and validation
    |
    */
    'callback_url' => env('MPESA_CALLBACK_URL'),
];
