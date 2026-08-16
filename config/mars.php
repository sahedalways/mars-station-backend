<?php

return [
    'admin_email' => env('ADMIN_EMAIL', 'marsstation.dev@gmail.com'),

    'otp' => [
        'length' => 6,
        'expires_minutes' => (int) env('ADMIN_OTP_EXPIRES_MINUTES', 5),
        'max_attempts' => (int) env('ADMIN_OTP_MAX_ATTEMPTS', 5),
        'fixed_code' => env('ADMIN_OTP_TEST_CODE'),
    ],

    'admin_session' => [
        'hours' => (int) env('ADMIN_SESSION_HOURS', 24),
    ],

    'agreement_otp' => [
        'expires_minutes' => (int) env('AGREEMENT_OTP_EXPIRES_MINUTES', 5),
        'max_attempts' => (int) env('AGREEMENT_OTP_MAX_ATTEMPTS', 5),
    ],

    'agreement_access' => [
        'hours' => (int) env('AGREEMENT_ACCESS_HOURS', 24),
    ],

    'agreement_number' => [
        'length' => 8,
        'alphabet' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'gbp'),
    ],

    'services' => [
        'max' => 12,
        'max_projects' => 3,
    ],
];
