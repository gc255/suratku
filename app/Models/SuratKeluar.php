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
    const STATUS_MENUNGGU_PPK = 'MENUNGGU_PARAF_PPK';
    const STATUS_MENUNGGU_KASUBBAG = 'MENUNGGU_PARAF_KASUBBAG';
    const STATUS_MENUNGGU_KABAG = 'MENUNGGU_PARAF_KABAG';
    const STATUS_MENUNGGU_WADIR = 'MENUNGGU_PARAF_WADIR';
    const STATUS_MENUNGGU_DIREKTUR = 'MENUNGGU_TTD_DIREKTUR';
    const STATUS_MENUNGGU_RAHASIA = 'MENUNGGU_PARAF_RAHASIA';
    const STATUS_MENUNGGU_FINAL = 'MENUNGGU_UPLOAD_FINAL';
    const STATUS_DIARSIPKAN = 'DIARSIPKAN';
    const STATUS_DITOLAK = 'DITOLAK';
    const STATUS_DIBATALKAN = 'DIBATALKAN';

    // Status Pengembalian / Revisi Dokumen
    const STATUS_DIKEMBALIKAN_KE_PENYUSUN = 'DIKEMBALIKAN_KE_PENYUSUN';
    const STATUS_DIKEMBALIKAN_KE_UNIT = 'DIKEMBALIKAN_KE_UNIT';
    const STATUS_DIKEMBALIKAN_KE_PPK = 'DIKEMBALIKAN_KE_PPK';
    const STATUS_DIKEMBALIKAN_KE_KASUBBAG = 'DIKEMBALIKAN_KE_KASUBBAG';
    const STATUS_DIKEMBALIKAN_KE_KABAG = 'DIKEMBALIKAN_KE_KABAG';
    const STATUS_DIKEMBALIKAN_KE_WADIR = 'DIKEMBALIKAN_KE_WADIR';
    const STATUS_DIKEMBALIKAN_KE_RAHASIA = 'DIKEMBALIKAN_KE_RAHASIA';

    // Mengizinkan kolom ini diisi dari form
    protected $fillable = [
        'nomor_surat',
        'judul', 
        'jenis_surat_id', 
        'tujuan_unit',
        'tujuan_ppk',
        'tujuan_kasubbag', 
        'tujuan_wadir', 
        'tujuan_penandatangan',
        'tujuan_rahasia_role',
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
     * Label PPK (Pejabat Pembuat Komitmen) yang dituju
     */
    public function getTujuanPpkLabelAttribute(): ?string
    {
        return User::getPpkRoles()[$this->tujuan_ppk] ?? $this->tujuan_ppk;
    }

    /**
     * Label Pejabat Tujuan Surat Rahasia
     */
    public function getTujuanRahasiaLabelAttribute(): ?string
    {
        return User::getRoleLabels()[$this->tujuan_rahasia_role] ?? $this->tujuan_rahasia_role;
    }

    /**
     * Cek apakah surat ini adalah Surat Perintah Tugas (SPT)
     */
    public function isSpt(): bool
    {
        $kode = strtoupper($this->jenisSurat->kode ?? '');
        $nama = strtoupper($this->jenisSurat->nama ?? '');
        return $kode === 'SPT' || str_contains($nama, 'SPT') || str_contains($nama, 'PERINTAH TUGAS');
    }

    /**
     * Cek apakah surat ini adalah Surat Rahasia
     */
    public function isRahasia(): bool
    {
        $kode = strtoupper($this->jenisSurat->kode ?? '');
        $nama = strtoupper($this->jenisSurat->nama ?? '');
        return $kode === 'SR' || str_contains($nama, 'RAHASIA');
    }

    /**
     * Memeriksa otorisasi apakah suatu user berhak melihat/membuka surat rahasia
     */
    public function canUserAccessRahasia(?User $user): bool
    {
        if (!$this->isRahasia()) {
            return true;
        }

        if (!$user) {
            return false;
        }

        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            return false;
        }

        // Pembuat draf awal
        if ($this->pembuat_id === $user->id) {
            return true;
        }

        // Pihak yang sedang dituju saat ini
        if ($this->tujuan_rahasia_role && $this->tujuan_rahasia_role === $user->role) {
            return true;
        }

        // Pihak yang mengunggah berkas final resmi
        if ($this->pengunggah_final_id && $this->pengunggah_final_id === $user->id) {
            return true;
        }

        // Pihak yang pernah memproses di riwayat tracking
        if ($this->relationLoaded('trackings')) {
            return $this->trackings->contains('aktor_id', $user->id);
        }

        return $this->trackings()->where('aktor_id', $user->id)->exists();
    }

    /**
     * Scope untuk menyaring visibilitas surat di dashboard (menyembunyikan surat rahasia dari user yang tidak berhak)
     */
    public function scopeVisibleForUser($query, User $user)
    {
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            return $query;
        }

        static $hasRahasiaCol = null;
        if ($hasRahasiaCol === null) {
            $hasRahasiaCol = \Illuminate\Support\Facades\Schema::hasColumn('surat_keluars', 'tujuan_rahasia_role');
        }

        return $query->where(function($q) use ($user, $hasRahasiaCol) {
            // Dokumen biasa (non-rahasia): selalu tampil sesuai alur/filter role
            $q->whereDoesntHave('jenisSurat', function($js) {
                $js->where('kode', 'SR')->orWhere('nama', 'like', '%Rahasia%');
            })
            // ATAU Dokumen rahasia: HANYA tampil jika pembuat, sedang dituju, pernah memproses, atau pengunggah final
            ->orWhere(function($sub) use ($user, $hasRahasiaCol) {
                $sub->whereHas('jenisSurat', function($js) {
                    $js->where('kode', 'SR')->orWhere('nama', 'like', '%Rahasia%');
                })->where(function($authQ) use ($user, $hasRahasiaCol) {
                    $authQ->where('pembuat_id', $user->id);
                    if ($hasRahasiaCol) {
                        $authQ->orWhere('tujuan_rahasia_role', $user->role);
                    }
                    $authQ->orWhere('pengunggah_final_id', $user->id)
                          ->orWhereHas('trackings', function($t) use ($user) {
                              $t->where('aktor_id', $user->id);
                          });
                });
            });
        });
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

            self::STATUS_MENUNGGU_PPK => 'Menunggu Paraf ' . ($this->tujuan_ppk_label ?? 'PPK'),

            self::STATUS_MENUNGGU_RAHASIA => 'Menunggu Paraf Rahasia (' . ($this->tujuan_rahasia_label ?? 'Pihak Terkait') . ')',

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
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_DIBATALKAN => 'Dibatalkan',

            self::STATUS_DIKEMBALIKAN_KE_PENYUSUN => 'Dikembalikan ke Penyusun (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_UNIT => 'Dikembalikan ke ' . ($this->tujuan_unit_label ?? 'Unit') . ' (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_PPK => 'Dikembalikan ke ' . ($this->tujuan_ppk_label ?? 'PPK') . ' (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_KASUBBAG => 'Dikembalikan ke Kasubbag (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_KABAG => 'Dikembalikan ke Sekretaris Kabag (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_WADIR => 'Dikembalikan ke Wadir (Revisi)',
            self::STATUS_DIKEMBALIKAN_KE_RAHASIA => 'Dikembalikan ke ' . ($this->tujuan_rahasia_label ?? 'Pihak Terkait') . ' (Revisi)',

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
            self::STATUS_MENUNGGU_PPK => 'bg-amber-100 text-amber-900 border border-amber-300',
            self::STATUS_MENUNGGU_RAHASIA => 'bg-rose-100 text-rose-900 border-2 border-rose-500 font-bold',
            self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT' => 'bg-amber-100 text-amber-800 border border-amber-300',
            self::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG' => 'bg-orange-100 text-orange-800 border border-orange-300',
            self::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG' => 'bg-blue-100 text-blue-800 border border-blue-300',
            self::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR' => 'bg-purple-100 text-purple-800 border border-purple-300',
            self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD' => 'bg-teal-100 text-teal-800 border border-teal-300',
            self::STATUS_DIARSIPKAN => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
            self::STATUS_DITOLAK => 'bg-rose-100 text-rose-800 border-2 border-rose-500 font-bold',
            self::STATUS_DIBATALKAN => 'bg-slate-200 text-slate-700 border-2 border-slate-400 font-bold',
            self::STATUS_DIKEMBALIKAN_KE_PENYUSUN,
            self::STATUS_DIKEMBALIKAN_KE_UNIT,
            self::STATUS_DIKEMBALIKAN_KE_PPK,
            self::STATUS_DIKEMBALIKAN_KE_KASUBBAG,
            self::STATUS_DIKEMBALIKAN_KE_KABAG,
            self::STATUS_DIKEMBALIKAN_KE_WADIR,
            self::STATUS_DIKEMBALIKAN_KE_RAHASIA => 'bg-red-100 text-red-800 border border-red-300',
            default => 'bg-gray-100 text-gray-800 border border-gray-300',
        };
    }

    /**
     * Konfigurasi Tampilan Balon Komik (Speech Bubble Model 2)
     * Memberikan emoji, dialog santai informatif, dan subtext alur naskah
     */
    public function getSpeechBubbleDataAttribute(): array
    {
        $penandatangan = $this->tujuan_penandatangan ?? 'direktur';

        if (str_starts_with($this->status, 'DIKEMBALIKAN_KE_')) {
            return [
                'emoji' => '💬',
                'dialog' => 'Ada Catatan Revisi Nih!',
                'subtext' => 'Periksa rincian catatan perbaikan pada menu tracking',
                'bg_class' => 'bg-rose-100 text-rose-950 border-rose-600',
                'shadow_color' => '#BE123C',
                'bubble_tail' => 'border-rose-600',
                'icon_bg' => 'bg-rose-200 text-rose-800',
            ];
        }

        return match ($this->status) {
            self::STATUS_MENUNGGU_UNIT => [
                'emoji' => '🏢',
                'dialog' => ($penandatangan === 'unit' ? 'Menunggu TTD ' : 'Antre Paraf ') . ($this->tujuan_unit_label ?? 'Unit/Prodi'),
                'subtext' => 'Sedang diverifikasi di tingkat Unit/Prodi',
                'bg_class' => 'bg-sky-100 text-[#003366] border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-sky-200 text-[#003366]',
            ],
            self::STATUS_MENUNGGU_PPK => [
                'emoji' => '💼',
                'dialog' => 'Antre Paraf ' . ($this->tujuan_ppk_label ?? 'PPK'),
                'subtext' => 'SPT diverifikasi Pejabat Pembuat Komitmen',
                'bg_class' => 'bg-amber-100 text-amber-950 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-amber-200 text-amber-900',
            ],
            self::STATUS_MENUNGGU_RAHASIA => [
                'emoji' => '🔒',
                'dialog' => 'Surat Rahasia Tertutup!',
                'subtext' => 'Khusus ditujukan ke ' . ($this->tujuan_rahasia_label ?? 'Pihak Terkait'),
                'bg_class' => 'bg-rose-100 text-rose-950 border-rose-600',
                'shadow_color' => '#BE123C',
                'bubble_tail' => 'border-rose-600',
                'icon_bg' => 'bg-rose-200 text-rose-800',
            ],
            self::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT' => [
                'emoji' => '☕',
                'dialog' => ($penandatangan === 'kasubbag' ? 'Menunggu TTD ' : 'Antre Paraf ') . ($this->tujuan_kasubbag_label ?? 'Kasubbag'),
                'subtext' => 'Draft masuk, menunggu paraf Kasubbag',
                'bg_class' => 'bg-amber-100 text-amber-950 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-amber-200 text-amber-900',
            ],
            self::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG' => [
                'emoji' => '📑',
                'dialog' => ($penandatangan === 'kabag' ? 'Menunggu TTD ' : 'Di Meja ') . 'Kepala Bagian (Kabag)',
                'subtext' => 'Pemeriksaan tata naskah Bagian Umum & Akademik',
                'bg_class' => 'bg-orange-100 text-orange-950 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-orange-200 text-orange-900',
            ],
            self::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG' => [
                'emoji' => '🏛️',
                'dialog' => ($penandatangan === 'wadir' ? 'Menunggu TTD ' : 'Di Meja ') . ($this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur'),
                'subtext' => 'Pemeriksaan tingkat pimpinan wadir',
                'bg_class' => 'bg-blue-100 text-blue-950 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-blue-200 text-[#003366]',
            ],
            self::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR' => [
                'emoji' => '✍️',
                'dialog' => 'Lagi di Meja Pak Direktur!',
                'subtext' => 'Tinggal tanda tangan akhir, lalu siap diarsipkan',
                'bg_class' => 'bg-amber-200 text-[#002244] border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-amber-300 text-[#002244]',
            ],
            self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD' => [
                'emoji' => '📤',
                'dialog' => 'Menunggu Upload Berkas Final!',
                'subtext' => 'Tanda tangan selesai (' . $this->tujuan_penandatangan_label . '), unggah dokumen resmi',
                'bg_class' => 'bg-teal-100 text-teal-950 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-teal-200 text-teal-900',
            ],
            self::STATUS_DIARSIPKAN => [
                'emoji' => '🎉',
                'dialog' => 'Beres & Resmi Terarsip!',
                'subtext' => 'Surat sah bernomor resmi, siap disebarkan',
                'bg_class' => 'bg-emerald-100 text-emerald-950 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-emerald-200 text-emerald-900',
            ],
            self::STATUS_DITOLAK => [
                'emoji' => '❌',
                'dialog' => 'Dokumen Ditolak!',
                'subtext' => 'Seluruh alur proses surat telah dihentikan',
                'bg_class' => 'bg-rose-100 text-rose-950 border-rose-600',
                'shadow_color' => '#BE123C',
                'bubble_tail' => 'border-rose-600',
                'icon_bg' => 'bg-rose-200 text-rose-800',
            ],
            self::STATUS_DIBATALKAN => [
                'emoji' => '🚫',
                'dialog' => 'Proses Dibatalkan',
                'subtext' => 'Surat dibatalkan oleh penyusun',
                'bg_class' => 'bg-slate-200 text-slate-900 border-slate-500',
                'shadow_color' => '#64748B',
                'bubble_tail' => 'border-slate-500',
                'icon_bg' => 'bg-slate-300 text-slate-800',
            ],
            default => [
                'emoji' => '📄',
                'dialog' => $this->status_label,
                'subtext' => 'Dokumen dalam proses penanganan',
                'bg_class' => 'bg-slate-100 text-slate-800 border-[#003366]',
                'shadow_color' => '#003366',
                'bubble_tail' => 'border-[#003366]',
                'icon_bg' => 'bg-slate-200 text-slate-700',
            ],
        };
    }

    /**
     * Format judul aksi tracking menjadi dialog maskot naskah yang ramah, to-the-point, dan santai.
     * Mengadopsi gaya bahasa balon komik status dokumen (maskot perjalanan naskah).
     */
    public function formatTrackingAksi($track, bool $isCurrent = false): string
    {
        $rawAksi = is_object($track) ? $track->aksi : (string)$track;

        // Normalisasi penyebutan jabatan sekretaris menjadi nama pejabat terkait
        $rawAksi = str_ireplace(['Sekretaris Wakil Direktur', 'Sekretaris Wadir'], 'Wakil Direktur', $rawAksi);
        $rawAksi = str_ireplace('Sekretaris Kabag', 'Kepala Bagian (Kabag)', $rawAksi);

        // Bersihkan suffix 'dan menunggu...'
        $aksiClean = trim(preg_replace('/\s+dan menunggu.*$/i', '', $rawAksi));

        $penandatangan = $this->tujuan_penandatangan ?? 'direktur';
        $wadirLabel = $this->tujuan_wadir_pejabat_label ?? 'Wakil Direktur';
        $kasubbagLabel = $this->tujuan_kasubbag_label ?? 'Kasubbag';
        $unitLabel = $this->tujuan_unit_label ?? 'Unit/Prodi';

        // 1. SURAT DIARSIPKAN / SELESAI
        if (stripos($aksiClean, 'Arsip') !== false || stripos($aksiClean, 'Selesai') !== false) {
            return '🎉 Hore Beres! Nomor Terbit & Resmi Terarsip';
        }

        // 2A. DITOLAK
        if (stripos($aksiClean, 'Tolak') !== false) {
            if (preg_match('/oleh\s+([^(]+)/i', $aksiClean, $m)) {
                $penolak = trim($m[1]);
                return "❌ Dokumen Ditolak oleh {$penolak} (Proses Dihentikan)";
            }
            return "❌ Dokumen Ditolak! Seluruh Proses Dihentikan";
        }

        // 2B. DIKEMBALIKAN / PERLU REVISI
        if (stripos($aksiClean, 'Kembali') !== false || stripos($aksiClean, 'Revisi') !== false || stripos($aksiClean, 'Balik Dulu') !== false) {
            if (preg_match('/(?:ke|oleh)\s+([^(]+)/i', $aksiClean, $m)) {
                $target = trim(str_ireplace(['Sekretaris ', 'Bapak/Ibu '], '', $m[1]));
                return $isCurrent 
                    ? "↩️ Eits Balik Dulu! Ada Catatan Revisi untuk {$target}"
                    : "↩️ Dikembalikan ke Meja {$target} (Ada Catatan Revisi)";
            }
            return $isCurrent ? "↩️ Eits Balik Dulu! Ada Catatan Revisi Nih" : "↩️ Dikembalikan untuk Revisi Masukan";
        }

        // 3. DRAFT DIBUAT (Awal)
        if (stripos($aksiClean, 'DRAFT DIBUAT') !== false || stripos($aksiClean, 'Draft Awal') !== false) {
            if ($isCurrent) {
                $targetAwal = $this->tujuan_unit ? $unitLabel : $kasubbagLabel;
                $targetWord = (($penandatangan === 'unit' && $this->tujuan_unit) || ($penandatangan === 'kasubbag' && !$this->tujuan_unit)) ? 'Tanda Tangan' : 'Paraf';
                return "🚀 Draf Baru Siap! Menuju Meja {$targetAwal} ({$targetWord})";
            }
            return "📝 Draf Awal Disusun & Siap Meluncur";
        }

        // 4. DRAFT DIPERBARUI (Setelah perbaikan)
        if (stripos($aksiClean, 'DIPERBARUI') !== false || stripos($aksiClean, 'Diperbaiki') !== false) {
            if ($isCurrent) {
                $targetAwal = $this->tujuan_unit ? $unitLabel : $kasubbagLabel;
                return "✨ Draf Sudah Diperbaiki! Meluncur Kembali ke Meja {$targetAwal}";
            }
            return "✏️ Draf Diperbaiki Sesuai Catatan Masukan";
        }

        // 5. PEMBARUAN NOMOR SURAT / ARSIP
        if (stripos($aksiClean, 'Pembaruan') !== false || stripos($aksiClean, 'Nomor Surat') !== false) {
            return "📝 Catatan Arsip & Nomor Surat Diperbarui";
        }

        // PPK (Pejabat Pembuat Komitmen)
        $isPpkActor = is_object($track) && isset($track->aktor) && \App\Models\User::isPpk($track->aktor->role ?? null);
        if ($isPpkActor || stripos($aksiClean, 'PPK') !== false) {
            $ppkName = $this->tujuan_ppk_label ?? 'PPK';
            if ($this->isSpt()) {
                if (stripos($rawAksi, 'Kabag') !== false || $this->tujuan_unit === null || $this->status === self::STATUS_MENUNGGU_KABAG) {
                    return "💼 PPK sudah verifikasi! Draf Meluncur ke Meja Kabag";
                }
                $targetKasubbag = $this->tujuan_kasubbag_label ?? 'Kasubbag';
                return "💼 PPK sudah verifikasi! Draf Meluncur ke Meja {$targetKasubbag}";
            }
            if ($isCurrent) {
                return "💼 Verifikasi PPK! Draf Meluncur ke Meja {$ppkName}";
            }
            return "💼 Disetujui & Diparaf oleh {$ppkName}";
        }

        // SURAT RAHASIA
        if ($this->isRahasia() || stripos($aksiClean, 'Rahasia') !== false) {
            $targetRahasia = $this->tujuan_rahasia_label ?? 'Pihak Terkait';
            if ($isCurrent) {
                return "🔒 Dokumen Rahasia Tertutup! Menuju Meja {$targetRahasia}";
            }
            return "🔒 Disetujui & Diteruskan Rahasia ke Meja {$targetRahasia}";
        }

        // 6. DITANDATANGANI
        if (stripos($aksiClean, 'Ditandatangani') !== false || stripos($aksiClean, 'Tanda Tangan') !== false) {
            if (stripos($aksiClean, 'Direktur') !== false) {
                $pejabat = 'Pak Direktur';
            } elseif (stripos($aksiClean, 'Wadir') !== false || stripos($aksiClean, 'Wakil Direktur') !== false) {
                $pejabat = 'Pak Wadir';
            } elseif (stripos($aksiClean, 'Kabag') !== false || stripos($aksiClean, 'Kepala Bagian') !== false) {
                $pejabat = 'Kepala Bagian (Kabag)';
            } elseif (stripos($aksiClean, 'Kasubbag') !== false) {
                $pejabat = $kasubbagLabel;
            } elseif (stripos($aksiClean, 'Unit') !== false) {
                $pejabat = $unitLabel;
            } else {
                $pejabat = $this->tujuan_penandatangan_label ?? 'Pejabat';
            }

            if ($isCurrent) {
                return "✍️ Sah! Ditandatangani {$pejabat}, Menunggu Upload Berkas Final";
            }
            return "✍️ Dokumen Resmi Ditandatangani {$pejabat}";
        }

        // 7. DIPARAF UNIT (Kaprodi / Ka Unit / Ka Lab)
        $isUnitActor = is_object($track) && isset($track->aktor) && \App\Models\User::isPemeriksaUnit($track->aktor->role ?? null);
        $isUnitText = stripos($aksiClean, 'Unit') !== false 
            || stripos($aksiClean, 'Kaprodi') !== false 
            || stripos($aksiClean, 'Ka Unit') !== false 
            || stripos($aksiClean, 'Ka Lab') !== false 
            || stripos($aksiClean, 'Kepala Unit') !== false 
            || stripos($aksiClean, 'Kepala Lab') !== false
            || ($this->tujuan_unit_label && stripos($aksiClean, $this->tujuan_unit_label) !== false)
            || ($this->tujuan_unit && stripos($aksiClean, $this->tujuan_unit) !== false);

        if ($isUnitActor || $isUnitText) {
            $unitName = $this->tujuan_unit_label;
            if (!$unitName && preg_match('/(?:Diparaf|Disetujui)\s+(.+)/i', $aksiClean, $m)) {
                $unitName = trim($m[1]);
            }
            $unitName = $unitName ?: 'Unit/Prodi';

            if ($this->isSpt() || stripos($rawAksi, 'PPK') !== false) {
                return "🏢 {$unitName} sudah paraf nih, saatnya antre verifikasi PPK";
            }

            if ($isCurrent) {
                $targetWord = ($penandatangan === 'kasubbag') ? 'Tanda Tangan' : 'Paraf';
                return "🏢 Paraf Unit Beres! Draf Meluncur ke Meja {$kasubbagLabel} ({$targetWord})";
            }
            return "🏢 Disetujui & Diparaf oleh {$unitName}";
        }

        // 8. DIPARAF KASUBBAG
        if (stripos($aksiClean, 'Kasubbag') !== false) {
            if ($isCurrent) {
                if ($this->isSpt() && $this->tujuan_ppk && (stripos($rawAksi, 'PPK') !== false || $this->status === self::STATUS_MENUNGGU_PPK)) {
                    $ppkName = $this->tujuan_ppk_label ?? 'PPK';
                    return "⚡ Disetujui {$kasubbagLabel}! Saatnya antre verifikasi {$ppkName}";
                }
                if ($penandatangan === 'kabag') {
                    return "⚡ Disetujui Kasubbag! Draf Meluncur ke Meja Kabag (Siap TTD)";
                }
                return "⚡ Disetujui Kasubbag! Draf Meluncur ke Meja Kepala Bagian (Kabag)";
            }
            if ($this->isSpt() && $this->tujuan_ppk && stripos($rawAksi, 'PPK') !== false) {
                $ppkName = $this->tujuan_ppk_label ?? 'PPK';
                return "⚡ Disetujui {$kasubbagLabel}! Draf Meluncur ke Meja {$ppkName}";
            }
            return "⚡ Disetujui & Diparaf oleh {$kasubbagLabel}";
        }

        // 9. DIPARAF KABAG
        if (stripos($aksiClean, 'Kabag') !== false || stripos($aksiClean, 'Kepala Bagian') !== false) {
            if ($isCurrent) {
                if ($penandatangan === 'kabag') {
                    return "📋 Disetujui Kabag! Menunggu Upload Berkas Final";
                } elseif ($penandatangan === 'wadir' || $this->tujuan_wadir) {
                    return "📋 Tata Naskah Beres dari Kabag! Meluncur ke Meja {$wadirLabel}";
                } else {
                    return "📋 Tata Naskah Beres dari Kabag! Meluncur ke Meja Pak Direktur";
                }
            }
            return "📋 Disetujui & Diparaf Kepala Bagian (Kabag)";
        }

        // 10. DIPARAF WADIR
        if (stripos($aksiClean, 'Wadir') !== false || stripos($aksiClean, 'Wakil Direktur') !== false) {
            if ($isCurrent) {
                if ($penandatangan === 'wadir') {
                    return "🏛️ Ditandatangani Pak Wadir! Menunggu Upload Berkas Final";
                }
                return "🏛️ Paraf Pak Wadir Beres! Tinggal Tanda Tangan Pak Direktur";
            }
            return "🏛️ Disetujui & Diparaf {$wadirLabel}";
        }

        // 11. DIBATALKAN / DIHAPUS
        if (stripos($aksiClean, 'Batal') !== false || (is_object($track) && stripos($track->catatan ?? '', 'batalkan prosesnya') !== false)) {
            return "🚫 Mohon maaf Bpk/Ibu suratnya saya batalkan prosesnya";
        }
        if (stripos($aksiClean, 'Hapus') !== false) {
            return "🗑️ Dokumen Draf Dihapus";
        }

        // 12. DEFAULT FALLBACK
        return $isCurrent ? ("📄 " . $aksiClean . " (Posisi Saat Ini)") : ("📄 " . $aksiClean);
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

        // Super Admin selalu memiliki akses edit selama belum diarsipkan (KECUALI surat rahasia)
        if (in_array($userRole, ['super_admin', 'superadmin'])) {
            if ($this->isRahasia()) {
                return false;
            }
            return !in_array($this->status, [self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', self::STATUS_DIARSIPKAN, self::STATUS_DITOLAK, self::STATUS_DIBATALKAN]);
        }

        // Hanya pembuat surat yang berhak
        if ($this->pembuat_id !== $userId) {
            return false;
        }

        // Surat yang sudah menunggu upload final, diarsipkan, ditolak, atau dibatalkan tidak dapat diedit
        if (in_array($this->status, [self::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', self::STATUS_DIARSIPKAN, self::STATUS_DITOLAK, self::STATUS_DIBATALKAN])) {
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
     * Cek apakah Penyusun berhak membatalkan proses surat
     * Aturan:
     * - Pembuat surat atau Super Admin berhak membatalkan surat selama alur masih berjalan (belum selesai/arsip/final, dan belum ditolak/dibatalkan)
     */
    public function canPenyusunBatalkan(?int $userId = null): bool
    {
        $userId = $userId ?? (auth()->check() ? auth()->id() : 0);
        $userRole = auth()->check() ? auth()->user()->role : '';

        $isOwner = ($this->pembuat_id === $userId);
        $isSuperAdmin = in_array($userRole, ['super_admin', 'superadmin']);

        if (!$isOwner && !$isSuperAdmin) {
            return false;
        }

        // Tidak dapat dibatalkan jika sudah selesai/arsip, menunggu upload final, atau sudah ditolak/dibatalkan
        $forbiddenStatuses = [
            self::STATUS_MENUNGGU_FINAL,
            'SELESAI_TTD',
            self::STATUS_DIARSIPKAN,
            self::STATUS_DITOLAK,
            self::STATUS_DIBATALKAN,
        ];

        return !in_array($this->status, $forbiddenStatuses);
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

        $ppkOpsi = $this->tujuan_ppk ? [
            'key' => 'ppk',
            'label' => $this->tujuan_ppk_label ?? 'Pejabat Pembuat Komitmen (PPK)',
            'desc' => 'Tingkat PPK (' . ($this->tujuan_ppk_label ?? 'PPK') . ')',
            'badge' => 'PPK',
        ] : null;

        // Jika surat rahasia: pengembalian langsung ke pembuat draf surat
        if ($this->isRahasia()) {
            return [
                [
                    'key' => 'penyusun',
                    'label' => 'Penyusun (' . ($this->pembuat->name ?? 'Pembuat Surat') . ')',
                    'desc' => 'Kembalikan ke pembuat draf surat rahasia untuk revisi',
                    'badge' => 'Penyusun',
                ]
            ];
        }

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
            if ($this->isSpt() && $ppkOpsi && !$this->tujuan_unit) {
                // Kasus 2: Kasubbag -> PPK -> Kabag (PPK berada tepat sebelum Kabag)
                $opsi[] = $ppkOpsi;
            }
            if ($this->tujuan_kasubbag) {
                $opsi[] = [
                    'key' => 'kasubbag',
                    'label' => $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait',
                    'desc' => 'Tingkat Kepala Subbagian',
                    'badge' => 'Kasubbag',
                ];
            }
            if ($this->isSpt() && $ppkOpsi && $this->tujuan_unit) {
                // Kasus 3: Unit -> PPK -> Kasubbag -> Kabag (PPK berada sebelum Kasubbag)
                $opsi[] = $ppkOpsi;
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

        // 4. Jika peninjau adalah Kasubbag: alur sebelumnya adalah PPK (jika Kasus 3), Unit (jika ada), dan Penyusun
        elseif (in_array($role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan', 'kasubbag'])) {
            if ($this->tujuan_unit && $ppkOpsi) {
                // Hanya jika SPT berasal dari Unit lalu ke PPK lalu ke Kasubbag
                $opsi[] = $ppkOpsi;
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

        // 5. Jika peninjau adalah PPK: alur sebelumnya adalah Unit (jika ada), Kasubbag (jika Kasus 2), dan Penyusun
        elseif (User::isPpk($role)) {
            if ($this->tujuan_unit) {
                $opsi[] = $unitOpsi;
            } elseif ($this->tujuan_kasubbag) {
                $opsi[] = [
                    'key' => 'kasubbag',
                    'label' => $this->tujuan_kasubbag_label ?? 'Kasubbag Terkait',
                    'desc' => 'Tingkat Kepala Subbagian (' . ($this->tujuan_kasubbag_label ?? 'Kasubbag') . ')',
                    'badge' => 'Kasubbag',
                ];
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
