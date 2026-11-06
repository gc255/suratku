<?php

namespace App\Listeners;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Auth\Events\Logout;

/**
 * Saat user logout, langganan notifikasi perangkat yang sedang dipakai dicabut,
 * agar notifikasi surat (termasuk surat rahasia) tidak muncul lagi di perangkat tersebut.
 */
class RevokePushSubscriptionOnLogout
{
    public function handle(Logout $event): void
    {
        try {
            if (!$event->user || !PushNotificationService::tableReady() || !app()->bound('session')) {
                return;
            }

            $hash = session('push_endpoint_hash');
            if ($hash) {
                PushSubscription::where('endpoint_hash', $hash)
                    ->where('user_id', $event->user->id)
                    ->delete();
                session()->forget('push_endpoint_hash');
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}

