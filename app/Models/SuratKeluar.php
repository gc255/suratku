<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes;

    // Konstanta Status Surat yang Menunjukkan Tingkat/Tahap Selanjutnya yang Ditunggu
    const STATUS_MENUNGGU_UNIT = 'MENUNGGU_PARAF_UNIT';
    const STATUS_MENUNGGU_KASUBBAG = 'MENUNGGU_PARAF_KASUBBAG';
    const STATUS_MENUNGGU_KABAG = 'MENUNGGU_PARAF_KABAG';
    const STATUS_MENUNGGU_WADIR = 'MENUNGGU_PARAF_WADIR';
    const STATUS_MENUNGGU_DIREKTUR = 'MENUNGGU_TTD_DIREKTUR';
    const STATUS_MENUNGGU_FINAL = 'MENUNGGU_UPLOAD_FINAL';
    const STATUS_DIARSIPKAN = 'DIARSIPKAN';

    // Status Pengembalian / Revisi Dokumen
    const STATUS_DIKEMBALIKAN_KE_PENYUSUN = 'DIKEMBALIKAN_KE_PENYUSUN';
    const STATUS_DIKEMBALIKAN_KE_UNIT = 'DIKEMBALIKAN_KE_UNIT';
    const STATUS_DIKEMBALIKAN_KE_KASUBBAG = 'DIKEMBALIKAN_KE_KASUBBAG';
    const STATUS_DIKEMBALIKAN_KE_KABAG = 'DIKEMBALIKAN_KE_KABAG';
    const STATUS_DIKEMBALIKAN_KE_WADIR = 'DIKEMBALIKAN_KE_WADIR';

    // Mengizinkan kolom ini diisi dari form
    protected $fillable = [
        'nomor_surat',
        'judul', 
        'jenis_surat_id', 
        'tujuan_unit',
        'tujuan_kasubbag', 
        'tujuan_wadir', 
        'tujuan_penandatangan',
        'file_draft', 
        'file_final', 
        'pembuat_id', 
        'pengunggah_final_id',
        'status',
        'catatan_terakhir',
        'lampiran_revisi_terakhir'
    ];

    /**
     * Label Unit/Prodi/Lab yang dituju
     */
    public function getTujuanUnitLabelAttribute(): ?string
    {
        return User::getPemeriksaUnitRoles()[$this->tujuan_unit] ?? $this->tujuan_unit;
    }

    /**
     * Opsi penandatangan yang valid
     */
    public static function getOpsiPenandatangan(): array
    {
        return [
            'direktur' => 'Direktur (Sekretaris Direktur)',
            'wadir' => 'Wakil Direktur',
            'kabag' => 'Kepala Bagian Administrasi Akademik dan Umum (Kabag)',
            'kasubbag' => 'Kepala Subbagian (Kasubbag Terkait)',
            'unit' => 'Tingkat Unit / Prodi / Laboratorium',
        ];
    }

    /**
     * Label penandatangan yang dituju
     */
    public function getTujuanPenandatanganLabelAttribute(): string
    {
        $penandatangan = $this->tujuan_penandatangan ?? 'direktur';
        if ($penandatangan === 'unit') {
            return $this->tujuan_unit_label ?? 'Unit / Prodi / Laboratorium';
        } elseif ($penandatangan === 'kasubbag') {
            return $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait';
        } elseif ($penandatangan === 'kabag') {
            return 'Kepala Bagian (Kabag)';
        } elseif ($penandatangan === 'wadir') {
            return $this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur';
        }
        return 'Direktur';
    }

    /**
     * Cek apakah surat ini ditandatangani di level tertentu
     */
    public function isFinalSignerLevel(string $level): bool
    {
        return ($this->tujuan_penandatangan ?? 'direktur') === $level;
    }

    /**
     * Label Kasubbag yang dituju
     */
    public function getTujuanKasubbagLabelAttribute(): ?string
    {
        return User::getKasubbagRoles()[$this->tujuan_kasubbag] ?? $this->tujuan_kasubbag;
    }

    /**
     * Label Sekretaris Wakil Direktur yang dituju
     */
    public function getTujuanWadirLabelAttribute(): ?string
    {
        return User::getSekretarisWadirRoles()[$this->tujuan_wadir] ?? $this->tujuan_wadir;
    }

    /**
     * Label Pejabat Wakil Direktur yang dituju (tanpa kata 'Sekretaris')
     */
    public function getTujuanWadirPejabatLabelAttribute(): ?string
    {
        $label = $this->tujuan_wadir_label;
        if (!$label) return 'Wakil Direktur';
        return str_replace('Sekretaris ', '', $label);
    }

    // Relasi: 1 Surat dibuat oleh 1 User (Pembuat)
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    // Relasi: Akun yang mengunggah berkas final resmi (Pengarsipan)
    public function pengunggahFinal()
    {
        return $this->belongsTo(User::class, 'pengunggah_final_id');
    }

    // Relasi: 1 Surat memiliki 1 Jenis Surat
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    // Relasi: 1 Surat memiliki banyak log riwayat tracking
    public function trackings()
    {
        return $this->hasMany(TrackingSurat::class, 'surat_keluar_id')->orderBy('created_at', 'asc');
    }

    /**
     * Label status yang rapi, manusiawi, dan menunjukkan langkah/tingkat berikutnya yang ditunggu
     */
    public function getStatusLabelAttribute(): string
    {
        $penandatangan = $this->tujuan_penandatangan ?? 'direktur';

        return match ($this->status) {
            self::STATUS_MENUNGGU_UNIT => ($penandatangan === 'unit')
                ? ('Menunggu TTD ' . ($this->tujuan_unit_label ?? 'Unit/Prodi/Lab'))
                : ('Menunggu Paraf ' . ($this->tujuan_unit_label ?? 'Unit/Prodi/Lab')),

            self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT' => ($penandatangan === 'kasubbag')
                ? ('Menunggu TTD ' . ($this->tujuan_kasubbag_label ?? 'Kasubbag'))
                : ('Menunggu Paraf ' . ($this->tujuan_kasubbag_label ?? 'Kasubbag')),

            self::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG' => ($penandatangan === 'kabag')
                ? 'Menunggu TTD Kepala Bagian (Kabag)'
                : 'Menunggu Paraf Kabag',

            self::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG' => ($penandatangan === 'wadir')
                ? ('Menunggu TTD ' . ($this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur'))
                : 'Menunggu Paraf Wadir',

            self::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR' => 'Menunggu TTD Direktur',

            self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD' => 'Menunggu Upload Final (' . $this->tujuan_penandatangan_label . ')',

            self::STATUS_DIARSIPKAN => 'Diarsipkan',

            self::STATUS_DIKEMBALIKAN_KE_PENYUSUN => 'Dikembalikan ke Penyusun (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_UNIT => 'Dikembalikan ke ' . ($this->tujuan_unit_label ?? 'Unit') . ' (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_KASUBBAG => 'Dikembalikan ke Kasubbag (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_KABAG => 'Dikembalikan ke Sekretaris Kabag (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_WADIR => 'Dikembalikan ke Wadir (Revisi)',

            default => ucwords(strtolower(str_replace('_', ' ', $this->status))),
        };
    }

    /**
     * Kelas CSS warna badge status agar tampilan modern dan informatif
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $penandatangan = $this->tujuan_penandatangan ?? 'direktur';

        // Jika surat sedang menunggu tanda tangan akhir (TTD), beri aksen ungu/indigo khas TTD
        if (
            ($this->status === self::STATUS_MENUNGGU_UNIT && $penandatangan === 'unit') ||
            (in_array($this->status, [self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT']) && $penandatangan === 'kasubbag') ||
            (in_array($this->status, [self::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG']) && $penandatangan === 'kabag') ||
            (in_array($this->status, [self::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG']) && $penandatangan === 'wadir') ||
            (in_array($this->status, [self::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR']))
        ) {
            return 'bg-purple-100 text-purple-800 border border-purple-300';
        }

        return match ($this->status) {
            self::STATUS_MENUNGGU_UNIT => 'bg-indigo-100 text-indigo-800 border border-indigo-300',
            self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT' => 'bg-amber-100 text-amber-800 border border-amber-300',
            self::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG' => 'bg-orange-100 text-orange-800 border border-orange-300',
            self::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG' => 'bg-blue-100 text-blue-800 border border-blue-300',
            self::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR' => 'bg-purple-100 text-purple-800 border border-purple-300',
            self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD' => 'bg-teal-100 text-teal-800 border border-teal-300',
            self::STATUS_DIARSIPKAN => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
            self::STATUS_DIKEMBALIKAN_KE_PENYUSUN,
            self::STATUS_DIKEMBALIKAN_KE_UNIT,
            self::STATUS_DIKEMBALIKAN_KE_KASUBBAG,
            self::STATUS_DIKEMBALIKAN_KE_KABAG,
            self::STATUS_DIKEMBALIKAN_KE_WADIR => 'bg-red-100 text-red-800 border border-red-300',
            default => 'bg-gray-100 text-gray-800 border border-gray-300',
        };
    }

    /**
     * Format judul aksi tracking agar posisi saat ini menampilkan keterangan menunggu tingkat selanjutnya
     * dan riwayat sebelumnya tetap bersih dan rapi.
     */
    public function formatTrackingAksi($track, bool $isCurrent = false): string
    {
        $rawAksi = is_object($track) ? $track->aksi : (string)$track;

        // Bersihkan penyebutan 'Sekretaris Wakil Direktur' menjadi 'Wakil Direktur' pada aksi tracking
        $rawAksi = str_replace('Diparaf Sekretaris Wakil Direktur', 'Diparaf Wakil Direktur', $rawAksi);
        $rawAksi = str_replace('paraf Sekretaris Wakil Direktur', 'paraf Wakil Direktur', $rawAksi);
        $rawAksi = str_replace('ke Sekretaris Wakil Direktur', 'ke Wakil Direktur', $rawAksi);

        $baseAksi = preg_replace('/\s+dan menunggu.*$/i', '', $rawAksi);

        if (!$isCurrent) {
            return $baseAksi;
        }

        // Jika merupakan posisi saat ini, tambahkan keterangan menunggu tingkat selanjutnya sesuai status surat
        $wadirLabel = $this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur';
        $penandatangan = $this->tujuan_penandatangan ?? 'direktur';

        return match ($this->status) {
            self::STATUS_MENUNGGU_UNIT => $baseAksi . ' dan menunggu ' . ($penandatangan === 'unit' ? 'tanda tangan ' : 'paraf ') . ($this->tujuan_unit_label ?? 'Unit/Prodi/Lab'),
            self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT' => $baseAksi . ' dan menunggu ' . ($penandatangan === 'kasubbag' ? 'tanda tangan ' : 'paraf ') . ($this->tujuan_kasubbag_label ?? 'Kasubbag'),
            self::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG' => $baseAksi . ' dan menunggu ' . ($penandatangan === 'kabag' ? 'tanda tangan ' : 'paraf ') . 'Kepala Bagian (Kabag)',
            self::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG' => $baseAksi . ' dan menunggu ' . ($penandatangan === 'wadir' ? 'tanda tangan ' : 'paraf ') . $wadirLabel,
            self::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR' => $baseAksi . ' dan menunggu tanda tangan Direktur',
            self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD' => $baseAksi . ' dan menunggu upload berkas final',
            default => $baseAksi,
        };
    }

    /**
     * Cek apakah pemberi paraf tingkat pertama sudah memberikan paraf / menyetujui
     */
    public function isLevelPertamaSudahParaf(): bool
    {
        // 1. Jika masih di status awal pengajuan saat baru dibuat:
        if ($this->tujuan_unit) {
            // Memulai dari Unit: jika status masih menunggu unit, belum pernah diparaf level 1
            if ($this->status === self::STATUS_MENUNGGU_UNIT) {
                return false;
            }
        } else {
            // Memulai dari Kasubbag: jika status masih menunggu kasubbag atau dibuat, belum pernah diparaf level 1
            if (in_array($this->status, [self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT'])) {
                return false;
            }
        }

        // 2. Periksa riwayat tracking apakah ada paraf atau tanda tangan dari pemeriksa (bukan pembuat)
        return $this->trackings()
            ->where('aktor_id', '!=', $this->pembuat_id)
            ->where(function($q) {
                $q->where('aksi', 'like', 'Diparaf%')
                  ->orWhere('aksi', 'like', 'Ditandatangani%');
            })
            ->exists();
    }

    /**
     * Cek apakah Penyusun masih berhak mengedit draft surat
     * Aturan:
     * - Boleh edit jika surat berstatus dikembalikan ke penyusun (revisi)
     * - Boleh edit jika pemeriksa level pertama belum melakukan paraf
     * - TIDAK boleh edit jika pemeriksa level pertama sudah paraf (namun tetap bisa hapus/batalkan)
     */
    public function canPenyusunEdit(?int $userId = null): bool
    {
        $userId = $userId ?? (auth()->check() ? auth()->id() : 0);
        $userRole = auth()->check() ? auth()->user()->role : '';

        // Super Admin selalu memiliki akses edit selama belum diarsipkan
        if (in_array($userRole, ['super_admin', 'superadmin'])) {
            return !in_array($this->status, [self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', self::STATUS_DIARSIPKAN]);
        }

        // Hanya pembuat surat yang berhak
        if ($this->pembuat_id !== $userId) {
            return false;
        }

        // Surat yang sudah menunggu upload final atau diarsipkan tidak dapat diedit
        if (in_array($this->status, [self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', self::STATUS_DIARSIPKAN])) {
            return false;
        }

        // Jika berstatus dikembalikan ke penyusun (revisi), PASTI boleh diedit
        if ($this->status === self::STATUS_DIKEMBALIKAN_KE_PENYUSUN) {
            return true;
        }

        // Jika pemeriksa level pertama belum melakukan paraf, penyusun boleh mengedit draft
        return !$this->isLevelPertamaSudahParaf();
    }

    /**
     * Cek apakah Penyusun berhak membatalkan / menghapus draft surat
     * Aturan:
     * - Pembuat surat boleh membatalkan/menghapus draft selama belum ditandatangani final / diarsipkan
     */
    public function canPenyusunDelete(?int $userId = null): bool
    {
        $userId = $userId ?? (auth()->check() ? auth()->id() : 0);
        $userRole = auth()->check() ? auth()->user()->role : '';

        if (in_array($userRole, ['super_admin', 'superadmin'])) {
            return true;
        }

        if ($this->pembuat_id !== $userId) {
            return false;
        }

        // Dokumen yang belum final / belum diarsipkan masih bisa dibatalkan/dihapus oleh pembuat
        return !in_array($this->status, [self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', self::STATUS_DIARSIPKAN]);
    }

    /**
     * Ambil opsi tujuan pengembalian surat berdasarkan alur yang telah dilewati sebelumnya
     */
    public function getOpsiPengembalian(?string $role = null): array
    {
        $role = $role ?? (auth()->check() ? auth()->user()->role : null);
        $opsi = [];

        $unitOpsi = $this->tujuan_unit ? [
            'key' => 'unit',
            'label' => $this->tujuan_unit_label ?? 'Unit / Prodi / Laboratorium',
            'desc' => 'Tingkat Unit Pengusul (' . ($this->tujuan_unit_label ?? 'Unit') . ')',
            'badge' => 'Unit/Lab',
        ] : null;

        // 1. Jika peninjau adalah Sekretaris Direktur: alur sebelumnya adalah Wadir, Kabag, Kasubbag, Unit, Penyusun
        if ($role === 'sekretaris_direktur') {
            if ($this->tujuan_wadir) {
                $opsi[] = [
                    'key' => 'wadir',
                    'label' => $this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur',
                    'desc' => 'Tingkat Wakil Direktur (' . ($this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur') . ')',
                    'badge' => 'Wakil Direktur',
                ];
            }
            $opsi[] = [
                'key' => 'kabag',
                'label' => 'Sekretaris Kepala Bagian (Kabag)',
                'desc' => 'Tingkat Kepala Bagian',
                'badge' => 'Kabag',
            ];
            if ($this->tujuan_kasubbag) {
                $opsi[] = [
                    'key' => 'kasubbag',
                    'label' => $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait',
                    'desc' => 'Tingkat Kepala Subbagian',
                    'badge' => 'Kasubbag',
                ];
            }
            if ($unitOpsi) {
                $opsi[] = $unitOpsi;
            }
            $opsi[] = [
                'key' => 'penyusun',
                'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                'desc' => 'Pembuat draft awal surat',
                'badge' => 'Penyusun',
            ];
        }

        // 2. Jika peninjau adalah Sekretaris Wakil Direktur: alur sebelumnya adalah Kabag, Kasubbag, Unit, Penyusun
        elseif (in_array($role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3', 'sekretaris_wadir'])) {
            $opsi[] = [
                'key' => 'kabag',
                'label' => 'Sekretaris Kepala Bagian (Kabag)',
                'desc' => 'Tingkat Kepala Bagian',
                'badge' => 'Kabag',
            ];
            if ($this->tujuan_kasubbag) {
                $opsi[] = [
                    'key' => 'kasubbag',
                    'label' => $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait',
                    'desc' => 'Tingkat Kepala Subbagian',
                    'badge' => 'Kasubbag',
                ];
            }
            if ($unitOpsi) {
                $opsi[] = $unitOpsi;
            }
            $opsi[] = [
                'key' => 'penyusun',
                'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                'desc' => 'Pembuat draft awal surat',
                'badge' => 'Penyusun',
            ];
        }

        // 3. Jika peninjau adalah Sekretaris Kabag: alur sebelumnya adalah Kasubbag, Unit, Penyusun
        elseif ($role === 'sekretaris_kabag') {
            if ($this->tujuan_kasubbag) {
                $opsi[] = [
                    'key' => 'kasubbag',
                    'label' => $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait',
                    'desc' => 'Tingkat Kepala Subbagian',
                    'badge' => 'Kasubbag',
                ];
            }
            if ($unitOpsi) {
                $opsi[] = $unitOpsi;
            }
            $opsi[] = [
                'key' => 'penyusun',
                'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                'desc' => 'Pembuat draft awal surat',
                'badge' => 'Penyusun',
            ];
        }

        // 4. Jika peninjau adalah Kasubbag: alur sebelumnya adalah Unit (jika ada) dan Penyusun
        elseif (in_array($role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan', 'kasubbag'])) {
            if ($unitOpsi) {
                $opsi[] = $unitOpsi;
            }
            $opsi[] = [
                'key' => 'penyusun',
                'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                'desc' => 'Pembuat draft awal surat',
                'badge' => 'Penyusun',
            ];
        }

        // 5. Jika peninjau adalah Pemeriksa Unit (Kaprodi / Ka Unit / Ka Lab): alur sebelumnya hanya Penyusun
        elseif (User::isPemeriksaUnit($role)) {
            $opsi[] = [
                'key' => 'penyusun',
                'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                'desc' => 'Pembuat draft awal surat',
                'badge' => 'Penyusun',
            ];
        }

        // Fallback untuk Super Admin / peran lainnya
        else {
            if ($this->tujuan_wadir) {
                $opsi[] = [
                    'key' => 'wadir',
                    'label' => $this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur',
                    'desc' => 'Tingkat Wakil Direktur',
                    'badge' => 'Wakil Direktur',
                ];
            }
            $opsi[] = [
                'key' => 'kabag',
                'label' => 'Sekretaris Kepala Bagian (Kabag)',
                'desc' => 'Tingkat Kepala Bagian',
                'badge' => 'Kabag',
            ];
            if ($this->tujuan_kasubbag) {
                $opsi[] = [
                    'key' => 'kasubbag',
                    'label' => $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait',
                    'desc' => 'Tingkat Kepala Subbagian',
                    'badge' => 'Kasubbag',
                ];
            }
            if ($unitOpsi) {
                $opsi[] = $unitOpsi;
            }
            $opsi[] = [
                'key' => 'penyusun',
                'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                'desc' => 'Pembuat draft awal surat',
                'badge' => 'Penyusun',
            ];
        }

        return $opsi;
    }
}
