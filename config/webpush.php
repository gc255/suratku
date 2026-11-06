<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web Push Notification (VAPID)
    |--------------------------------------------------------------------------
    | Kunci VAPID dibuat sekali per server dengan perintah:
    |   php artisan webpush:vapid
    | Kunci PRIVAT hanya disimpan di file .env server (tidak pernah dikirim ke browser).
    */

    'enabled' => env('WEBPUSH_ENABLED', true),

    'subject' => env('VAPID_SUBJECT', env('APP_URL', 'mailto:admin@localhost')),

    'public_key' => env('VAPID_PUBLIC_KEY'),

    'private_key' => env('VAPID_PRIVATE_KEY'),

    // Batas waktu (detik) koneksi ke server push (FCM/Apple/Mozilla) agar tidak membebani request.
    'timeout' => (int) env('WEBPUSH_TIMEOUT', 5),

    // Daftar host layanan push resmi yang diizinkan (mencegah SSRF lewat endpoint palsu).
    'allowed_hosts' => [
        'fcm.googleapis.com',
        'android.googleapis.com',
        'updates.push.services.mozilla.com',
        'push.services.mozilla.com',
        'web.push.apple.com',
        'push.apple.com',
        'notify.windows.com',
        'wns.windows.com',
    ],
];

