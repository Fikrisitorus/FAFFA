<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans payment gateway integration
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sandbox' => env('MIDTRANS_IS_SANDBOX', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    'is_save_card' => env('MIDTRANS_IS_SAVE_CARD', true),

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | Available payment methods for the application
    |
    */
    'payment_methods' => [
        'credit_card' => 'Credit Card',
        'bca_va' => 'BCA Virtual Account',
        'bni_va' => 'BNI Virtual Account',
        'bri_va' => 'BRI Virtual Account',
        'mandiri_va' => 'Mandiri Virtual Account',
        'permata_va' => 'Permata Virtual Account',
        'gopay' => 'GoPay',
        'shopeepay' => 'ShopeePay',
        'qris' => 'QRIS',
        'bca_klikbca' => 'BCA KlikBCA',
        'bca_klikpay' => 'BCA KlikPay',
        'bri_epay' => 'BRI e-Pay',
        'cimb_clicks' => 'CIMB Clicks',
        'danamon_online' => 'Danamon Online',
        'kredivo' => 'Kredivo',
        'akulaku' => 'Akulaku',
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    |
    | URLs for payment callbacks
    |
    */
    'callbacks' => [
        'finish' => '/payment/finish',
        'unfinish' => '/payment/unfinish',
        'error' => '/payment/error',
        'webhook' => '/payment/webhook',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for sandbox environment
    |
    */
    'sandbox' => [
        'server_key' => '',
        'client_key' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for production environment
    |
    */
    'production' => [
        'server_key' => env('MIDTRANS_PRODUCTION_SERVER_KEY'),
        'client_key' => env('MIDTRANS_PRODUCTION_CLIENT_KEY'),
    ],
];
