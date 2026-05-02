<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $businessShortCode;
    protected $passkey;
    protected $environment;
    protected $callbackUrl;

    public function __construct()
{
    // Load from config
    $this->consumerKey = config('mpesa.consumer_key');
    $this->consumerSecret = config('mpesa.consumer_secret');
    $this->businessShortCode = config('mpesa.business_shortcode', '174379');
    $this->passkey = config('mpesa.passkey');
    $this->environment = config('mpesa.environment', 'sandbox');

    // CRITICAL: Get callback URL directly from env (since config isn't working)
    $this->callbackUrl = env('MPESA_CALLBACK_URL');

    // Log what we got
    Log::info('M-Pesa Config Load Attempt', [
        'consumer_key_exists' => !empty($this->consumerKey),
        'consumer_key_length' => strlen($this->consumerKey ?? ''),
        'consumer_secret_exists' => !empty($this->consumerSecret),
        'business_shortcode' => $this->businessShortCode,
        'environment' => $this->environment,
        'callback_url_from_env' => $this->callbackUrl,
        'callback_url_from_config' => config('mpesa.callback_url'),
    ]);

    // FALLBACK: Use hardcoded credentials if config/env fails
    if (empty($this->consumerKey) || empty($this->consumerSecret)) {
        Log::warning('M-Pesa config failed, using hardcoded credentials');
        $this->consumerKey = 'WCY0J7i3iTtwVGX7VnVD0yrRQJBod1OWVztuBFRHkAkuv0fc';
        $this->consumerSecret = 'wZclGxgHGt7LGWjnJAomA2AUoul0ZyEg4w756v0OuCGF0KheSJ83E0eQaBKvhWB6';
        $this->businessShortCode = '174379';
        $this->passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    }

    // ULTIMATE FALLBACK: If callback URL is still empty, hardcode it
    if (empty($this->callbackUrl)) {
        $this->callbackUrl = 'https://generative-regina-drowsy.ngrok-free.dev/api/mpesa/callback';
        Log::warning('Callback URL was empty, using hardcoded URL: ' . $this->callbackUrl);
    }
}
    /**
     * Get OAuth Token
     */
    protected function getAccessToken()
    {
        Log::info('Getting M-Pesa access token', [
            'consumer_key_exists' => !empty($this->consumerKey),
            'consumer_secret_exists' => !empty($this->consumerSecret),
            'environment' => $this->environment
        ]);

        $url = $this->environment === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->withOptions(['verify' => false]) // CRITICAL: SSL fix for Windows
                ->get($url);

            if ($response->successful()) {
                $token = $response->json()['access_token'];
                Log::info('M-Pesa access token obtained successfully');
                return $token;
            }

            Log::error('M-Pesa token error response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('M-Pesa token exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }

    /**
     * Initiate STK Push
     */
    public function stkPush($phoneNumber, $amount, $accountReference, $transactionDesc, $orderId = null)
    {
        Log::info('Initiating STK Push', [
            'phone' => $phoneNumber,
            'amount' => $amount,
            'reference' => $accountReference,
            'order_id' => $orderId
        ]);

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            Log::error('STK Push failed: No access token');
            return [
                'success' => false,
                'message' => 'Failed to get access token'
            ];
        }

        // Format phone number to 254XXXXXXXXX
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);

        $url = $this->environment === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $timestamp = date('YmdHis');
        $password = base64_encode($this->businessShortCode . $this->passkey . $timestamp);

        try {
            $response = Http::withToken($accessToken)
                ->withOptions(['verify' => false]) // CRITICAL: SSL fix for Windows
                ->post($url, [
                    'BusinessShortCode' => $this->businessShortCode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => round($amount),
                    'PartyA' => $phoneNumber,
                    'PartyB' => $this->businessShortCode,
                    'PhoneNumber' => $phoneNumber,
                    'CallBackURL' => $this->callbackUrl,
                    'AccountReference' => $accountReference,
                    'TransactionDesc' => $transactionDesc,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('STK Push initiated successfully', [
                    'checkout_request_id' => $result['CheckoutRequestID'] ?? null
                ]);

                return [
                    'success' => true,
                    'checkout_request_id' => $result['CheckoutRequestID'] ?? null,
                    'response_code' => $result['ResponseCode'] ?? null,
                    'response_description' => $result['ResponseDescription'] ?? null,
                    'customer_message' => $result['CustomerMessage'] ?? null,
                    'order_id' => $orderId
                ];
            }

            Log::error('M-Pesa STK Push Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return [
            'success' => false,
            'message' => 'Failed to initiate payment'
        ];
    }

    /**
     * Query STK Push Status
     */
    public function queryStatus($checkoutRequestId)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to get access token'
            ];
        }

        $url = $this->environment === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query'
            : 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query';

        $timestamp = date('YmdHis');
        $password = base64_encode($this->businessShortCode . $this->passkey . $timestamp);

        try {
            $response = Http::withToken($accessToken)
                ->withOptions(['verify' => false])
                ->post($url, [
                    'BusinessShortCode' => $this->businessShortCode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'CheckoutRequestID' => $checkoutRequestId,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('M-Pesa Query Status Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to query status'
            ];
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Check if it starts with 0
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }
        // Check if it starts with 7 (assuming local number without 0)
        elseif (substr($phone, 0, 1) === '7') {
            $phone = '254' . $phone;
        }
        // Check if it starts with 254
        elseif (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }

        return $phone;
    }
}
