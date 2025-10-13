<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Google OAuth
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8000/auth/google/callback'),
    ],

    // Facebook OAuth
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT', env('FACEBOOK_REDIRECT_URI', 'https://unsimmering-presley-speakably.ngrok-free.dev/auth/facebook/callback')),
    ],

    // GHN API Configuration
    'ghn' => [
        'api_url' => env('GHN_API_URL', 'https://dev-online-gateway.ghn.vn'),
        'token' => env('GHN_TOKEN'),
        'shop_id' => env('GHN_SHOP_ID'),
        'from_district_id' => env('GHN_FROM_DISTRICT_ID'),
        'from_ward_code' => env('GHN_FROM_WARD_CODE'),
    ],

    // Google AI Studio (Generative Language API for Gemini)
    'google_ai' => [
        'api_key' => env('GOOGLE_API_KEY'),
        'model' => env('GOOGLE_AI_MODEL', 'gemini-2.5-flash-lite'),
        // Bật/tắt kiểm tra chứng chỉ SSL (DEV có thể đặt false để tránh lỗi cURL 60)
        'verify_ssl' => env('GOOGLE_AI_VERIFY_SSL', true),
        // Nếu muốn chỉ định CA bundle cụ thể: đặt đường dẫn tuyệt đối tới cacert.pem
        'ca_bundle' => env('GOOGLE_AI_CA_BUNDLE'),
    ],
];
