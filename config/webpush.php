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

    'subject' => env('VAPID_SUBJECT', 'mailto:admin@politeknikpu.ac.id'),

    'public_key' => env('VAPID_PUBLIC_KEY', 'BEcno86UjTzkLi6D8F8aT1-BTxHqFxqfsGYRn1e1BEUU9tjR5zsre079vyOOkq52OXajfl8xql2eTmU5uGEmm14'),

    'private_key' => env('VAPID_PRIVATE_KEY', 't27DGnIOXyyqSMKfb7HGWeayDSvnZsQ7Ly2_YZ5qjw8'),

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

