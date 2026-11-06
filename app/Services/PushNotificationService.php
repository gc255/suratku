<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/**
 * Layanan Web Push Notification + App Badge untuk PWA Suratku.
 *
 * Prinsip keamanan & stabilitas:
 * - Semua proses dibungkus try/catch: kegagalan notifikasi TIDAK PERNAH menggagalkan alur surat.
 * - Pengiriman dijalankan setelah respons dikirim ke browser (defer), sehingga tidak memperlambat aksi paraf.
 * - Surat rahasia: isi notifikasi TIDAK memuat judul/isi surat (tampil di layar kunci HP).
 * - Super Admin tidak pernah menjadi penerima notifikasi alur surat.
 * - Endpoint langganan dibatasi ke host layanan push resmi (anti-SSRF).
 */
class PushNotificationService
{
    /** Status yang berarti surat sedang menunggu tindakan pejabat tertentu. */
    public const PENDING_STATUSES = [
        SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT,
        SuratKeluar::STATUS_MENUNGGU_PPK, SuratKeluar::STATUS_DIKEMBALIKAN_KE_PPK,
        SuratKeluar::STATUS_MENUNGGU_KASUBBAG, SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG, 'DIBUAT',
        SuratKeluar::STATUS_MENUNGGU_KABAG, SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG, 'PARAF_KASUBBAG',
        SuratKeluar::STATUS_MENUNGGU_WADIR, SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR, 'PARAF_KABAG',
        SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR',
        SuratKeluar::STATUS_MENUNGGU_RAHASIA, SuratKeluar::STATUS_DIKEMBALIKAN_KE_RAHASIA,
        SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD',
    ];

    /** Status yang ditujukan kembali ke penyusun (pembuat surat). */
    public const PENYUSUN_STATUSES = [
        SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN,
        SuratKeluar::STATUS_DITOLAK,
        SuratKeluar::STATUS_DIARSIPKAN,
    ];

    /** Kolom tujuan yang bila berubah (walau status sama) berarti surat pindah meja. */
    public const TARGET_FIELDS = ['tujuan_unit', 'tujuan_ppk', 'tujuan_kasubbag', 'tujuan_wadir', 'tujuan_rahasia_role'];

    public static function isConfigured(): bool
    {
        return (bool) config('webpush.enabled')
            && !empty(config('webpush.public_key'))
            && !empty(config('webpush.private_key'));
    }

    public static function tableReady(): bool
    {
        static $ready = null;
        if ($ready === null) {
            try {
                $ready = Schema::hasTable('push_subscriptions');
            } catch (\Throwable $e) {
                $ready = false;
            }
        }
        return $ready;
    }

    /**
     * Validasi endpoint langganan: wajib HTTPS & host layanan push resmi.
     */
    public static function isAllowedEndpoint(string $endpoint): bool
    {
        $parts = parse_url($endpoint);
        if (!$parts || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])) {
            return false;
        }
        if (isset($parts['user']) || isset($parts['pass']) || (isset($parts['port']) && (int) $parts['port'] !== 443)) {
            return false;
        }

        $host = strtolower($parts['host']);
        foreach ((array) config('webpush.allowed_hosts', []) as $allowed) {
            $allowed = strtolower($allowed);
            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Menentukan role pejabat yang SEKARANG harus bertindak atas surat ini.
     * Mengembalikan array role (bisa lebih dari satu bila tujuan belum spesifik).
     */
    public static function resolveRecipientRoles(SuratKeluar $surat): array
    {
        $status = $surat->status;
        $all = fn (array $roles) => array_keys($roles);

        // Surat rahasia: hanya pejabat yang dituju secara eksplisit.
        if (in_array($status, [SuratKeluar::STATUS_MENUNGGU_RAHASIA, SuratKeluar::STATUS_DIKEMBALIKAN_KE_RAHASIA], true)) {
            return $surat->tujuan_rahasia_role ? [$surat->tujuan_rahasia_role] : [];
        }

        switch ($status) {
            case SuratKeluar::STATUS_MENUNGGU_UNIT:
            case SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT:
                return $surat->tujuan_unit ? [$surat->tujuan_unit] : [];

            case SuratKeluar::STATUS_MENUNGGU_PPK:
            case SuratKeluar::STATUS_DIKEMBALIKAN_KE_PPK:
                return $surat->tujuan_ppk ? [$surat->tujuan_ppk] : $all(User::getPpkRoles());

            case SuratKeluar::STATUS_MENUNGGU_KASUBBAG:
            case SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG:
            case 'DIBUAT':
                return $surat->tujuan_kasubbag ? [$surat->tujuan_kasubbag] : $all(User::getKasubbagRoles());

            case SuratKeluar::STATUS_MENUNGGU_KABAG:
            case SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG:
            case 'PARAF_KASUBBAG':
                return ['sekretaris_kabag'];

            case SuratKeluar::STATUS_MENUNGGU_WADIR:
            case SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR:
            case 'PARAF_KABAG':
                return $surat->tujuan_wadir ? [$surat->tujuan_wadir] : $all(User::getSekretarisWadirRoles());

            case SuratKeluar::STATUS_MENUNGGU_DIREKTUR:
            case 'PARAF_WADIR':
                return ['sekretaris_direktur'];

            case SuratKeluar::STATUS_MENUNGGU_FINAL:
            case 'SELESAI_TTD':
                if ($surat->isRahasia()) {
                    return $surat->tujuan_rahasia_role ? [$surat->tujuan_rahasia_role] : [];
                }
                switch ($surat->tujuan_penandatangan ?? 'direktur') {
                    case 'unit':
                        return $surat->tujuan_unit ? [$surat->tujuan_unit] : [];
                    case 'kasubbag':
                        return $surat->tujuan_kasubbag ? [$surat->tujuan_kasubbag] : $all(User::getKasubbagRoles());
                    case 'kabag':
                        return ['sekretaris_kabag'];
                    case 'wadir':
                        return $surat->tujuan_wadir ? [$surat->tujuan_wadir] : $all(User::getSekretarisWadirRoles());
                    default:
                        return ['sekretaris_direktur'];
                }
        }

        return [];
    }

    /**
     * Dipanggil oleh Observer saat surat dibuat / berpindah tahap.
     */
    public static function notifySuratChanged(SuratKeluar $surat, ?int $actorId = null): void
    {
        if (!self::isConfigured() || !self::tableReady()) {
            return;
        }

        $suratId = $surat->id;

        try {
            $fresh = SuratKeluar::with('jenisSurat')->find($suratId);
            if ($fresh) {
                (new self())->dispatchForSurat($fresh, $actorId);
            }
        } catch (\Throwable $e) {
            Log::warning('[WebPush] Gagal memproses notifikasi surat #' . $suratId . ': ' . $e->getMessage());
        }
    }

    protected function dispatchForSurat(SuratKeluar $surat, ?int $actorId): void
    {
        $status = $surat->status;
        $rahasia = $surat->isRahasia();
        $jenis = $surat->jenisSurat->nama ?? 'Surat';
        $judul = \Illuminate\Support\Str::limit((string) $surat->judul, 80);
        $url = route('surat.track', $surat->id, false);

        // ============================================================
        // 1. NOTIFIKASI UNTUK PEJABAT / PEMERIKSA DI LEVEL/POSISI SAAT INI
        // ============================================================
        if (in_array($status, self::PENDING_STATUSES, true)) {
            $roles = self::resolveRecipientRoles($surat);
            if (!empty($roles)) {
                $officers = User::whereIn('role', $roles)
                    ->whereNotIn('role', ['super_admin', 'superadmin'])
                    ->when($actorId, fn($q) => $q->where('id', '!=', $actorId))
                    ->get();

                $isFinal = in_array($status, [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'], true);
                $isReturn = str_starts_with((string) $status, 'DIKEMBALIKAN_KE_');

                if ($isFinal) {
                    $title = 'Menunggu Unggah Dokumen Final';
                    $body = $rahasia
                        ? 'Surat rahasia telah ditandatangani. Silakan unggah dokumen final.'
                        : 'Surat "' . $judul . '" telah ditandatangani. Silakan unggah dokumen final.';
                } elseif ($isReturn) {
                    $title = 'Surat Dikembalikan ke Meja Anda';
                    $body = $rahasia
                        ? 'Ada SURAT RAHASIA dikembalikan ke meja Anda. Buka aplikasi untuk melihat.'
                        : $jenis . ': ' . $judul . ' dikembalikan ke meja Anda.';
                } else {
                    $title = 'Surat Baru Menunggu Paraf Anda';
                    $body = $rahasia
                        ? 'Ada SURAT RAHASIA yang menunggu paraf/tindakan Anda.'
                        : $jenis . ': ' . $judul;
                }

                foreach ($officers as $officer) {
                    $this->sendToUser($officer, [
                        'title' => $title,
                        'body' => $body,
                        'url' => $url,
                        'tag' => 'surat-' . $surat->id,
                        'badge_count' => self::antreanCountFor($officer),
                    ]);
                }
            }
        }

        // ============================================================
        // 2. NOTIFIKASI KHUSUS UNTUK PENYUSUN (PEMBUAT SURAT)
        // a) Surat sudah ditandatangani (STATUS_MENUNGGU_FINAL / SELESAI_TTD)
        // b) Surat ditolak (STATUS_DITOLAK)
        // c) Surat direvisi / dikembalikan ke penyusun (STATUS_DIKEMBALIKAN_KE_PENYUSUN)
        // d) Surat selesai & diarsipkan (STATUS_DIARSIPKAN)
        // ============================================================
        if ($surat->pembuat_id) {
            $pembuat = User::find($surat->pembuat_id);
            if ($pembuat && (!$actorId || (int) $pembuat->id !== (int) $actorId)) {
                $penandatanganLabel = $surat->tujuan_penandatangan_label ?? 'Penandatangan';
                $label = $rahasia ? 'Surat rahasia Anda' : 'Surat "' . $judul . '"';

                $penyusunTitle = null;
                $penyusunBody = null;

                if (in_array($status, [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'], true)) {
                    $penyusunTitle = 'Surat Telah Ditandatangani';
                    $penyusunBody = $label . ' telah selesai ditandatangani oleh ' . $penandatanganLabel . '.';
                } elseif ($status === SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN) {
                    $penyusunTitle = 'Surat Dikembalikan untuk Revisi';
                    $penyusunBody = $label . ' dikembalikan ke meja Anda. Silakan cek catatan revisi.';
                } elseif ($status === SuratKeluar::STATUS_DITOLAK) {
                    $penyusunTitle = 'Surat Ditolak';
                    $penyusunBody = $label . ' ditolak. Buka aplikasi untuk melihat alasannya.';
                } elseif ($status === SuratKeluar::STATUS_DIARSIPKAN) {
                    $penyusunTitle = 'Surat Selesai & Diarsipkan';
                    $penyusunBody = $label . ' telah selesai diproses dan diarsipkan.';
                }

                if ($penyusunTitle && $penyusunBody) {
                    $this->sendToUser($pembuat, [
                        'title' => $penyusunTitle,
                        'body' => $penyusunBody,
                        'url' => $url,
                        'tag' => 'surat-penyusun-' . $surat->id,
                        'badge_count' => self::antreanCountFor($pembuat),
                    ]);
                }
            }
        }
    }

    /**
     * Jumlah surat yang sedang menunggu tindakan user (dipakai untuk angka badge di ikon aplikasi).
     */
    public static function antreanCountFor(User $user): int
    {
        try {
            if (in_array($user->role, ['super_admin', 'superadmin'], true)) {
                return 0;
            }

            if ($user->role === 'penyusun') {
                return SuratKeluar::where('pembuat_id', $user->id)
                    ->where('status', SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN)
                    ->count();
            }

            $candidates = SuratKeluar::visibleForUser($user)
                ->with('jenisSurat')
                ->whereIn('status', self::PENDING_STATUSES)
                ->get();

            return $candidates->filter(
                fn ($s) => in_array($user->role, self::resolveRecipientRoles($s), true)
            )->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Kirim payload ke semua perangkat milik user. Langganan kedaluwarsa otomatis dihapus.
     */
    public function sendToUser(User $user, array $payload): int
    {
        if (!self::isConfigured() || !self::tableReady()) {
            return 0;
        }

        $subscriptions = PushSubscription::where('user_id', $user->id)->get();
        if ($subscriptions->isEmpty()) {
            return 0;
        }

        $webPush = $this->makeClient();
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $byEndpoint = [];
        foreach ($subscriptions as $sub) {
            if (!self::isAllowedEndpoint($sub->endpoint)) {
                $sub->delete();
                continue;
            }
            try {
                $webPush->queueNotification(Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                    'contentEncoding' => $sub->content_encoding ?: 'aes128gcm',
                ]), $json, ['TTL' => 86400, 'urgency' => 'high', 'topic' => substr(preg_replace('/[^A-Za-z0-9_-]/', '', $payload['tag'] ?? 'suratku'), 0, 32)]);
                $byEndpoint[$sub->endpoint] = $sub;
            } catch (\Throwable $e) {
                Log::warning('[WebPush] Gagal menyiapkan notifikasi user #' . $user->id . ': ' . $e->getMessage());
            }
        }

        $sent = 0;
        try {
            foreach ($webPush->flush() as $report) {
                if ($report->isSuccess()) {
                    $sent++;
                } elseif ($report->isSubscriptionExpired()) {
                    // Perangkat sudah mencabut izin / uninstall -> hapus langganan
                    optional($byEndpoint[$report->getEndpoint()] ?? null)->delete();
                } else {
                    Log::info('[WebPush] Pengiriman gagal (user #' . $user->id . '): ' . $report->getReason());
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[WebPush] Gagal mengirim notifikasi user #' . $user->id . ': ' . $e->getMessage());
        }

        return $sent;
    }

    protected function makeClient(): WebPush
    {
        $timeout = max(1, (int) config('webpush.timeout', 5));

        $httpClient = new \GuzzleHttp\Client([
            'timeout' => $timeout,
            'connect_timeout' => $timeout,
            'allow_redirects' => false, // anti-SSRF: jangan ikuti redirect
            'http_errors' => false,
        ]);

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.subject'),
                'publicKey' => config('webpush.public_key'),
                'privateKey' => config('webpush.private_key'),
            ],
        ], ['TTL' => 86400], $httpClient);

        $webPush->setReuseVAPIDHeaders(true);

        return $webPush;
    }
}
