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

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
        'currency' => env('PAYSTACK_CURRENCY', 'NGN'),
    ],

    'instructor_payouts' => [
        // Platform commission percentage (e.g., 20 means platform takes 20%, instructor gets 80%)
        'platform_commission' => env('PLATFORM_COMMISSION_PERCENTAGE', 20.0),

        // Number of days to hold funds before they're available for payout
        // This protects against refunds and chargebacks
        'holding_period_days' => env('PAYOUT_HOLDING_PERIOD_DAYS', 7),

        // Minimum payout amount
        'minimum_payout' => env('MINIMUM_PAYOUT_AMOUNT', 5000), // 5000 NGN

        // Default payout method
        'default_method' => env('DEFAULT_PAYOUT_METHOD', 'bank_transfer'),
    ],

];
