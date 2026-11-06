<?php

namespace App\Observers;

use App\Models\SuratKeluar;
use App\Services\PushNotificationService;
use Illuminate\Support\Facades\Auth;

/**
 * Memicu Web Push Notification setiap kali surat dibuat atau berpindah meja.
 * Observer hanya "mendengar" perubahan; tidak mengubah data/alur surat sama sekali.
 */
class SuratKeluarObserver
{
    public function created(SuratKeluar $surat): void
    {
        $this->trigger($surat);
    }

    public function updated(SuratKeluar $surat): void
    {
        $changed = $surat->wasChanged('status');

        if (!$changed) {
            foreach (PushNotificationService::TARGET_FIELDS as $field) {
                if ($surat->wasChanged($field)) {
                    $changed = true;
                    break;
                }
            }
        }

        if ($changed) {
            $this->trigger($surat);
        }
    }

    protected function trigger(SuratKeluar $surat): void
    {
        try {
            PushNotificationService::notifySuratChanged($surat, Auth::id());
        } catch (\Throwable $e) {
            // Notifikasi tidak boleh mengganggu proses surat
            report($e);
        }
    }
}

