<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Simpan / perbarui langganan notifikasi perangkat milik user yang sedang login.
     */
    public function store(Request $request): JsonResponse
    {
        if (!PushNotificationService::isConfigured() || !PushNotificationService::tableReady()) {
            return response()->json(['ok' => false, 'message' => 'Notifikasi belum diaktifkan di server.'], 503);
        }

        $data = $request->validate([
            'endpoint' => ['required', 'string', 'url', 'max:2000'],
            'keys.p256dh' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-=]+$/'],
            'keys.auth' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-=]+$/'],
            'contentEncoding' => ['nullable', 'string', 'in:aes128gcm,aesgcm'],
        ]);

        if (!PushNotificationService::isAllowedEndpoint($data['endpoint'])) {
            return response()->json(['ok' => false, 'message' => 'Layanan notifikasi perangkat tidak dikenali.'], 422);
        }

        $user = $request->user();
        $hash = PushSubscription::hashEndpoint($data['endpoint']);

        // Satu perangkat = satu langganan. Bila perangkat dipakai akun lain, kepemilikan dipindahkan
        // ke akun yang sedang login (akun sebelumnya tidak lagi menerima notifikasi di perangkat ini).
        PushSubscription::updateOrCreate(
            ['endpoint_hash' => $hash],
            [
                'user_id' => $user->id,
                'endpoint' => $data['endpoint'],
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
                'content_encoding' => $data['contentEncoding'] ?? 'aes128gcm',
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]
        );

        // Batasi jumlah perangkat per akun (hapus yang paling lama) untuk mencegah penyalahgunaan.
        $ids = PushSubscription::where('user_id', $user->id)->orderByDesc('updated_at')->pluck('id');
        if ($ids->count() > 10) {
            PushSubscription::whereIn('id', $ids->slice(10)->all())->delete();
        }

        // Disimpan di sesi agar saat logout langganan perangkat ini otomatis dicabut.
        $request->session()->put('push_endpoint_hash', $hash);

        return response()->json([
            'ok' => true,
            'badge_count' => PushNotificationService::antreanCountFor($user),
        ]);
    }

    /**
     * Cabut langganan perangkat ini (hanya milik user yang login).
     */
    public function destroy(Request $request): JsonResponse
    {
        if (!PushNotificationService::tableReady()) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'endpoint' => ['required', 'string', 'max:2000'],
        ]);

        PushSubscription::where('endpoint_hash', PushSubscription::hashEndpoint($data['endpoint']))
            ->where('user_id', $request->user()->id)
            ->delete();

        $request->session()->forget('push_endpoint_hash');

        return response()->json(['ok' => true]);
    }

    /**
     * Jumlah antrean surat untuk angka badge pada ikon aplikasi.
     */
    public function badge(Request $request): JsonResponse
    {
        return response()->json([
            'badge_count' => PushNotificationService::antreanCountFor($request->user()),
        ]);
    }
}

