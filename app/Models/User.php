<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'no_wa',
        'email',
        'role',
        'password',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_changed_at' => 'datetime',
        ];
    }

    /**
     * Memeriksa apakah akun masih menggunakan password default dan perlu mengubah passwordnya.
     */
    public function needsPasswordChange(): bool
    {
        // Jika sudah pernah mengubah password mandiri, tidak perlu ganti lagi
        if ($this->password_changed_at !== null) {
            return false;
        }

        // Cek jika password saat ini masih sama dengan username/NIP (password default akun baru)
        if (!empty($this->nip) && \Illuminate\Support\Facades\Hash::check($this->nip, $this->password)) {
            return true;
        }

        // Cek jika masih menggunakan password default institusi umum
        if (\Illuminate\Support\Facades\Hash::check('pegawai123', $this->password)) {
            return true;
        }

        return false;
    }

    /**
     * Daftar Kaprodi (3 Program Studi)
     */
    public static function getKaprodiRoles(): array
    {
        return [
            'kaprodi_tkba' => 'Kaprodi TKBA',
            'kaprodi_tkjj' => 'Kaprodi TKJJ',
            'kaprodi_tkbg' => 'Kaprodi TKBG',
        ];
    }

    /**
     * Daftar Ketua Unit & Komisi (15 Unit)
     */
    public static function getKetuaUnitRoles(): array
    {
        return [
            'ka_unit_spm' => 'Ketua Unit SPM',
            'ka_unit_spi' => 'Ketua Unit SPI',
            'ka_unit_ppm' => 'Ketua Unit PPM',
            'ka_unit_p2ai' => 'Ketua Unit P2AI',
            'ka_unit_perpustakaan' => 'Ketua Unit Perpustakaan & E-learning',
            'ka_unit_kemahasiswaan' => 'Ketua Unit Kemahasiswaan & Pengembangan Karakter',
            'ka_unit_kompu_ppid' => 'Ketua Unit Kompu & PPID',
            'ka_unit_bim' => 'Ketua Unit BIM',
            'ka_unit_poliklinik' => 'Ketua Unit Poliklinik',
            'ketua_komisi_disiplin' => 'Ketua Komisi Disiplin',
            'ka_unit_ptsp' => 'Ketua Unit PTSP',
            'ka_unit_career_center' => 'Ketua Unit Career Center & Alumni',
            'ka_unit_ppkpt_cst' => 'Ketua Unit PPKPT & CST',
            'ka_unit_k3' => 'Ketua Unit K3',
            'ka_unit_asrama' => 'Ketua Unit Pengelola Asrama',
        ];
    }

    /**
     * Daftar Kepala Laboratorium (11 Laboratorium)
     */
    public static function getKepalaLabRoles(): array
    {
        return [
            'ka_lab_mektan' => 'Kepala Laboratorium Mekanika Tanah',
            'ka_lab_geodesi' => 'Kepala Laboratorium Geodesi & Geomatika',
            'ka_lab_bahan_struktur' => 'Kepala Laboratorium Bahan & Struktur',
            'ka_lab_hidrolika' => 'Kepala Laboratorium Hidrolika & Bangunan Air',
            'ka_lab_fisika' => 'Kepala Laboratorium Fisika Terapan',
            'ka_lab_komputer' => 'Kepala Laboratorium Komputer & Gambar',
            'ka_lab_kayu_baja' => 'Kepala Laboratorium Kayu & Baja',
            'ka_lab_batu_beton' => 'Kepala Laboratorium Batu & Beton',
            'ka_lab_utilitas' => 'Kepala Laboratorium Utilitas',
            'ka_lab_jalan_jembatan' => 'Kepala Laboratorium Jalan & Jembatan',
            'ka_lab_sanitasi' => 'Kepala Laboratorium Sanitasi & Green Campus',
        ];
    }

    /**
     * Gabungan seluruh 29 level di bawah Kasubbag (Kaprodi, Unit, & Laboratorium)
     */
    public static function getPemeriksaUnitRoles(): array
    {
        return array_merge(
            self::getKaprodiRoles(),
            self::getKetuaUnitRoles(),
            self::getKepalaLabRoles()
        );
    }

    /**
     * Cek apakah suatu role adalah pemeriksa unit (Kaprodi / Ka Unit / Ka Lab)
     */
    public static function isPemeriksaUnit(?string $role = null): bool
    {
        $role = $role ?? (auth()->check() ? auth()->user()->role : null);
        return array_key_exists($role, self::getPemeriksaUnitRoles());
    }

    /**
     * Daftar Pejabat Pembuat Komitmen (PPK)
     */
    public static function getPpkRoles(): array
    {
        return [
            'ppk_1' => 'PPK 1 (Modal dan Vokasi)',
            'ppk_2' => 'PPK 2 (Pemeliharaan Rutin)',
        ];
    }

    /**
     * Cek apakah suatu role adalah Pejabat Pembuat Komitmen (PPK)
     */
    public static function isPpk(?string $role = null): bool
    {
        $role = $role ?? (auth()->check() ? auth()->user()->role : null);
        return array_key_exists($role, self::getPpkRoles());
    }

    /**
     * Daftar Kasubbag yang dapat dipilih Penyusun saat upload draft surat
     */
    public static function getKasubbagRoles(): array
    {
        return [
            'kasubbag_akademik' => 'Kasubbag Administrasi Akademik dan Kemahasiswaan',
            'kasubbag_psi' => 'Kasubbag Perencanaan dan Sistem Informasi',
            'kasubbag_keuangan' => 'Kasubbag Keuangan dan Umum',
        ];
    }

    /**
     * Daftar Sekretaris Wakil Direktur yang dapat dipilih Sekretaris Kabag saat meneruskan surat
     */
    public static function getSekretarisWadirRoles(): array
    {
        return [
            'sekretaris_wadir_1' => 'Sekretaris Wakil Direktur I',
            'sekretaris_wadir_2' => 'Sekretaris Wakil Direktur II',
            'sekretaris_wadir_3' => 'Sekretaris Wakil Direktur III',
        ];
    }

    /**
     * Daftar seluruh hak akses / role resmi dalam sistem
     */
    public static function getRoleLabels(): array
    {
        return array_merge(
            [
                'super_admin' => 'Super Admin',
                'penyusun' => 'Penyusun',
            ],
            self::getKaprodiRoles(),
            self::getKetuaUnitRoles(),
            self::getKepalaLabRoles(),
            self::getPpkRoles(),
            self::getKasubbagRoles(),
            [
                'sekretaris_kabag' => 'Sekretaris Kepala Administrasi Akademik dan Umum',
                'sekretaris_wadir_1' => 'Sekretaris Wakil Direktur I',
                'sekretaris_wadir_2' => 'Sekretaris Wakil Direktur II',
                'sekretaris_wadir_3' => 'Sekretaris Wakil Direktur III',
                'sekretaris_direktur' => 'Sekretaris Direktur',
            ]
        );
    }

    /**
     * Pengelompokan role rapi untuk dropdown select / optgroup
     */
    public static function getGroupedRoleLabels(): array
    {
        return [
            'Administrator & Staf' => [
                'super_admin' => 'Super Admin',
                'penyusun' => 'Penyusun',
            ],
            'Program Studi (Kaprodi)' => self::getKaprodiRoles(),
            'Ketua Unit & Komisi' => self::getKetuaUnitRoles(),
            'Kepala Laboratorium' => self::getKepalaLabRoles(),
            'Pejabat Pembuat Komitmen (PPK)' => self::getPpkRoles(),
            'Kepala Subbagian (Kasubbag)' => self::getKasubbagRoles(),
            'Pimpinan Bagian & Direksi' => [
                'sekretaris_kabag' => 'Sekretaris Kepala Administrasi Akademik dan Umum',
                'sekretaris_wadir_1' => 'Sekretaris Wakil Direktur I',
                'sekretaris_wadir_2' => 'Sekretaris Wakil Direktur II',
                'sekretaris_wadir_3' => 'Sekretaris Wakil Direktur III',
                'sekretaris_direktur' => 'Sekretaris Direktur',
            ],
        ];
    }

    /**
     * Accessor untuk nama role yang rapi dan mudah dibaca
     */
    public function getRoleLabelAttribute(): string
    {
        $labels = self::getRoleLabels();
        return $labels[$this->role] ?? ucwords(str_replace('_', ' ', $this->role));
    }
}
