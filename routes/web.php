<?php

use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\UserController;
use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', function (Request $request) { 
    $user = Auth::user();
    
    // ==========================================
    // 1. MENGHITUNG ANGKA UNTUK KARTU STATISTIK
    // ==========================================
    $statTotal = 0;
    $statProses = 0; // Menunggu tindakan
    $statSelesai = 0;

    if ($user->role === 'penyusun') {
        $statTotal = SuratKeluar::where('pembuat_id', $user->id)->count();
        $statProses = SuratKeluar::where('pembuat_id', $user->id)->whereNotIn('status', ['DIARSIPKAN'])->count();
        $statSelesai = SuratKeluar::where('pembuat_id', $user->id)->where('status', 'DIARSIPKAN')->count();
    } elseif (in_array($user->role, ['super_admin', 'superadmin'])) {
        // SUPER ADMIN: Kontrol & Monitoring Seluruh Sistem
        $statTotal = SuratKeluar::count();
        $statProses = SuratKeluar::whereNotIn('status', ['DIARSIPKAN'])->count();
        $statSelesai = SuratKeluar::where('status', 'DIARSIPKAN')->count();
    } else {
        // Untuk Atasan (Kasubbag) & Sekretaris
        $statTotal = SuratKeluar::count();
        $statSelesai = SuratKeluar::where('status', 'DIARSIPKAN')->count();
        
        // Hitung spesifik surat yang butuh tindakan mereka saat ini
        if (User::isPemeriksaUnit($user->role)) {
            $statProses = SuratKeluar::where(function($q) use ($user) {
                $q->where(function($sub) use ($user) {
                    $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT])
                        ->where(function($u) use ($user) {
                            $u->where('tujuan_unit', $user->role)->orWhereNull('tujuan_unit');
                        });
                })->orWhere(function($sub) use ($user) {
                    $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])
                        ->where('tujuan_penandatangan', 'unit')
                        ->where(function($u) use ($user) {
                            $u->where('tujuan_unit', $user->role)->orWhereNull('tujuan_unit');
                        });
                });
            })->count();
        } elseif (in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])) {
            $statProses = SuratKeluar::whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG
            ])->where(function($q) use ($user) {
                $q->where('tujuan_kasubbag', $user->role)->orWhereNull('tujuan_kasubbag');
            })->count();
        } elseif ($user->role === 'sekretaris_kabag') {
            $statProses = SuratKeluar::whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG
            ])->count();
        } elseif (in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3'])) {
            $statProses = SuratKeluar::whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
            ])->where(function($q) use ($user) {
                $q->where('tujuan_wadir', $user->role)->orWhereNull('tujuan_wadir');
            })->count();
        } elseif ($user->role === 'sekretaris_direktur') {
            $statProses = SuratKeluar::where(function($q) {
                $q->whereIn('status', [
                    SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR'
                ])->orWhere(function($sub) {
                    $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])
                        ->where('tujuan_penandatangan', 'direktur');
                });
            })->count();
        }
    }

    // ==========================================
    // 2. QUERY TABEL DENGAN SISTEM TAB HAK AKSES
    // ==========================================
    $isSuperAdmin = in_array($user->role, ['super_admin', 'superadmin']);
    $defaultTab = ($user->role === 'penyusun' || $isSuperAdmin) ? 'semua' : 'antrean';
    $activeTab = $request->input('tab', $defaultTab); // 'antrean' | 'sudah_paraf' | 'semua'
    $countAntrean = 0;
    $countSudahParaf = 0;
    $countSemua = 0;

    $query = SuratKeluar::with(['pembuat', 'jenisSurat', 'trackings.aktor', 'pengunggahFinal']);

    if ($request->has('search') && $request->search != '') {
        $searchTerm = '%' . $request->search . '%';
        $query->where(function($q) use ($searchTerm) {
            $q->where('judul', 'like', $searchTerm)
              ->orWhere('nomor_surat', 'like', $searchTerm);
        });
    }

    if ($user->role === 'penyusun') {
        $query->where('pembuat_id', $user->id);

        if (in_array($activeTab, ['sudah_paraf', 'diarsipkan'])) {
            $query->where('status', 'DIARSIPKAN');
        } elseif (in_array($activeTab, ['antrean', 'butuh_tindakan', 'proses'])) {
            $query->whereNotIn('status', ['DIARSIPKAN']);
        } else {
            // 'semua' -> Menampilkan seluruh surat milik penyusun tanpa filter
        }

        $countSemua = $statTotal;
        $countAntrean = $statProses;
        $countSudahParaf = $statSelesai;
    } elseif ($isSuperAdmin) {
        // SUPER ADMIN: Monitoring & Kontrol Penuh Seluruh Surat di Sistem
        $antreanQuery = SuratKeluar::whereNotIn('status', ['DIARSIPKAN']);
        $countAntrean = (clone $antreanQuery)->count();

        $sudahParafQuery = SuratKeluar::where('status', 'DIARSIPKAN');
        $countSudahParaf = (clone $sudahParafQuery)->count();

        $semuaQuery = SuratKeluar::query();
        $countSemua = (clone $semuaQuery)->count();

        if ($activeTab === 'sudah_paraf') {
            $query->where('status', 'DIARSIPKAN');
        } elseif ($activeTab === 'antrean') {
            $query->whereNotIn('status', ['DIARSIPKAN']);
        } else {
            // Default untuk super admin: 'semua' -> Seluruh dokumen tanpa filter status (semua 14 surat)
        }
    } elseif (User::isPemeriksaUnit($user->role)) {
        // PEMERIKSA UNIT / PRODI / LAB
        $antreanQuery = SuratKeluar::where(function($q) use ($user) {
            $q->where(function($sub) use ($user) {
                $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT])
                    ->where(function($u) use ($user) {
                        $u->where('tujuan_unit', $user->role)->orWhereNull('tujuan_unit');
                    });
            })->orWhere(function($sub) use ($user) {
                $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])
                    ->where('tujuan_penandatangan', 'unit')
                    ->where(function($u) use ($user) {
                        $u->where('tujuan_unit', $user->role)->orWhereNull('tujuan_unit');
                    });
            });
        });
        $countAntrean = (clone $antreanQuery)->count();

        $sudahParafQuery = SuratKeluar::whereHas('trackings', function($q) use ($user) {
            $q->where('aktor_id', $user->id);
        })->whereNotIn('status', [
            SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT
        ]);
        $countSudahParaf = (clone $sudahParafQuery)->count();

        $semuaQuery = SuratKeluar::where(function($q) use ($user) {
            $q->where('tujuan_unit', $user->role)
              ->orWhereHas('trackings', function($t) use ($user) {
                  $t->where('aktor_id', $user->id);
              });
        });
        $countSemua = (clone $semuaQuery)->count();

        if ($activeTab === 'sudah_paraf') {
            $query->whereHas('trackings', function($q) use ($user) {
                $q->where('aktor_id', $user->id);
            })->whereNotIn('status', [
                SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT
            ]);
        } elseif ($activeTab === 'semua') {
            $query->where(function($q) use ($user) {
                $q->where('tujuan_unit', $user->role)
                  ->orWhereHas('trackings', function($t) use ($user) {
                      $t->where('aktor_id', $user->id);
                  });
            });
        } else {
            // Default: antrean
            $query->where(function($q) use ($user) {
                $q->where(function($sub) use ($user) {
                    $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT])
                        ->where(function($u) use ($user) {
                            $u->where('tujuan_unit', $user->role)->orWhereNull('tujuan_unit');
                        });
                })->orWhere(function($sub) use ($user) {
                    $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])
                        ->where('tujuan_penandatangan', 'unit')
                        ->where(function($u) use ($user) {
                            $u->where('tujuan_unit', $user->role)->orWhereNull('tujuan_unit');
                        });
                });
            });
        }
    } elseif (in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])) {
        // KASUBBAG
        $antreanQuery = SuratKeluar::whereIn('status', [
            SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG
        ])->where(function($q) use ($user) {
            $q->where('tujuan_kasubbag', $user->role)->orWhereNull('tujuan_kasubbag');
        });
        $countAntrean = (clone $antreanQuery)->count();

        $sudahParafQuery = SuratKeluar::whereHas('trackings', function($q) use ($user) {
            $q->where('aktor_id', $user->id);
        })->whereNotIn('status', [
            SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG
        ]);
        $countSudahParaf = (clone $sudahParafQuery)->count();

        $semuaQuery = SuratKeluar::where(function($q) use ($user) {
            $q->where('tujuan_kasubbag', $user->role)
              ->orWhereHas('trackings', function($t) use ($user) {
                  $t->where('aktor_id', $user->id);
              });
        });
        $countSemua = (clone $semuaQuery)->count();

        if ($activeTab === 'sudah_paraf') {
            $query->whereHas('trackings', function($q) use ($user) {
                $q->where('aktor_id', $user->id);
            })->whereNotIn('status', [
                SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG
            ]);
        } elseif ($activeTab === 'semua') {
            $query->where(function($q) use ($user) {
                $q->where('tujuan_kasubbag', $user->role)
                  ->orWhereHas('trackings', function($t) use ($user) {
                      $t->where('aktor_id', $user->id);
                  });
            });
        } else {
            // Default: antrean
            $query->whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG
            ])->where(function($q) use ($user) {
                $q->where('tujuan_kasubbag', $user->role)->orWhereNull('tujuan_kasubbag');
            });
        }
    } elseif ($user->role === 'sekretaris_kabag') {
        // SEKRETARIS KABAG
        $antreanQuery = SuratKeluar::whereIn('status', [
            SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG
        ]);
        $countAntrean = (clone $antreanQuery)->count();

        $sudahParafQuery = SuratKeluar::whereHas('trackings', function($q) use ($user) {
            $q->where('aktor_id', $user->id);
        })->whereNotIn('status', [
            SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG
        ]);
        $countSudahParaf = (clone $sudahParafQuery)->count();

        $semuaQuery = SuratKeluar::where(function($q) use ($user) {
            $q->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG])
              ->orWhereHas('trackings', function($t) use ($user) {
                  $t->where('aktor_id', $user->id);
              });
        });
        $countSemua = (clone $semuaQuery)->count();

        if ($activeTab === 'sudah_paraf') {
            $query->whereHas('trackings', function($q) use ($user) {
                $q->where('aktor_id', $user->id);
            })->whereNotIn('status', [
                SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG
            ]);
        } elseif ($activeTab === 'semua') {
            $query->where(function($q) use ($user) {
                $q->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG])
                  ->orWhereHas('trackings', function($t) use ($user) {
                      $t->where('aktor_id', $user->id);
                  });
            });
        } else {
            $query->whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG
            ]);
        }
    } elseif (in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3'])) {
        // SEKRETARIS WADIR
        $antreanQuery = SuratKeluar::whereIn('status', [
            SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
        ])->where(function($q) use ($user) {
            $q->where('tujuan_wadir', $user->role)->orWhereNull('tujuan_wadir');
        });
        $countAntrean = (clone $antreanQuery)->count();

        $sudahParafQuery = SuratKeluar::whereHas('trackings', function($q) use ($user) {
            $q->where('aktor_id', $user->id);
        })->whereNotIn('status', [
            SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
        ]);
        $countSudahParaf = (clone $sudahParafQuery)->count();

        $semuaQuery = SuratKeluar::where(function($q) use ($user) {
            $q->where('tujuan_wadir', $user->role)
              ->orWhereHas('trackings', function($t) use ($user) {
                  $t->where('aktor_id', $user->id);
              });
        });
        $countSemua = (clone $semuaQuery)->count();

        if ($activeTab === 'sudah_paraf') {
            $query->whereHas('trackings', function($q) use ($user) {
                $q->where('aktor_id', $user->id);
            })->whereNotIn('status', [
                SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
            ]);
        } elseif ($activeTab === 'semua') {
            $query->where(function($q) use ($user) {
                $q->where('tujuan_wadir', $user->role)
                  ->orWhereHas('trackings', function($t) use ($user) {
                      $t->where('aktor_id', $user->id);
                  });
            });
        } else {
            $query->whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
            ])->where(function($q) use ($user) {
                $q->where('tujuan_wadir', $user->role)->orWhereNull('tujuan_wadir');
            });
        }
    } elseif ($user->role === 'sekretaris_direktur') {
        // SEKRETARIS DIREKTUR:
        // Tab 1 'antrean' (Menunggu Tindakan): Hanya surat alur Direktur yang butuh tindakan Direktur/Sekdir
        $antreanQuery = SuratKeluar::where(function($q) {
            $q->whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR'
            ])->orWhere(function($sub) {
                $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])
                    ->where('tujuan_penandatangan', 'direktur');
            });
        });
        $countAntrean = (clone $antreanQuery)->count();

        // Tab 2 'sudah_paraf' (Riwayat Sudah Diparaf / Diarsipkan)
        $sudahParafQuery = SuratKeluar::where('status', 'DIARSIPKAN');
        $countSudahParaf = (clone $sudahParafQuery)->count();

        // Tab 3 'semua' (Semua Surat Masuk): Termasuk surat alur Direktur & seluruh surat yang sudah di-TTD selain Direktur
        $semuaQuery = SuratKeluar::where(function($q) {
            $q->whereIn('status', [
                SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR',
                SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD',
                'DIARSIPKAN',
                SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
            ])->orWhere(function($sub) {
                // Surat yang sudah di-TTD selain Direktur masuk di list semua surat masuk Sekdir
                $sub->where('tujuan_penandatangan', '!=', 'direktur')
                    ->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', 'DIARSIPKAN']);
            });
        });
        $countSemua = (clone $semuaQuery)->count();

        if ($activeTab === 'sudah_paraf') {
            $query->where('status', 'DIARSIPKAN');
        } elseif ($activeTab === 'semua') {
            $query->where(function($q) {
                $q->whereIn('status', [
                    SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR',
                    SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD',
                    'DIARSIPKAN',
                    SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR
                ])->orWhere(function($sub) {
                    $sub->where('tujuan_penandatangan', '!=', 'direktur')
                        ->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', 'DIARSIPKAN']);
                });
            });
        } else {
            // Default: 'antrean' (Menunggu Tindakan)
            $query->where(function($q) {
                $q->whereIn('status', [
                    SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR'
                ])->orWhere(function($sub) {
                    $sub->whereIn('status', [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])
                        ->where('tujuan_penandatangan', 'direktur');
                });
            });
        }
    }

    // Pilihan pagination (default 20 surat untuk Super Admin, 10 untuk peran lain, opsi: 5, 10, 20, 50, 100)
    $defaultPerPage = $isSuperAdmin ? 20 : 10;
    $perPage = (int) $request->input('per_page', $defaultPerPage);
    if (!in_array($perPage, [5, 10, 20, 50, 100])) {
        $perPage = $defaultPerPage;
    }

    $surats = $query->latest()->paginate($perPage)->withQueryString();

    $allJenisSurat = JenisSurat::where('is_active', true)->orderBy('nama', 'asc')->get();
    $allKasubbagRoles = User::getKasubbagRoles();
    $allSekretarisWadirRoles = User::getSekretarisWadirRoles();

    // Kirim variabel angka statistik & tab ke tampilan
    return view('dashboard', compact(
        'surats', 
        'statTotal', 
        'statProses', 
        'statSelesai', 
        'perPage',
        'activeTab',
        'countAntrean',
        'countSudahParaf',
        'countSemua',
        'allJenisSurat',
        'allKasubbagRoles',
        'allSekretarisWadirRoles'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rute Profil Bawaan Laravel Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. Rute Penyusun
    Route::middleware(['role:penyusun,super_admin,superadmin'])->group(function () {
        Route::get('/surat/tambah', [SuratKeluarController::class, 'create'])->name('surat.create');
        Route::post('/surat/simpan', [SuratKeluarController::class, 'store'])->middleware('throttle:30,1')->name('surat.store');
        Route::post('/surat/{id}/update-draft', [SuratKeluarController::class, 'updateDraft'])->middleware('throttle:30,1')->name('surat.updateDraft');
    });

    // Rute Hapus Surat (Bisa dilakukan oleh Penyusun untuk draft miliknya, atau Super Admin)
    Route::delete('/surat/{id}/hapus', [SuratKeluarController::class, 'destroy'])
        ->middleware('role:penyusun,super_admin,superadmin')
        ->name('surat.destroy');

    // 2. Rute Tombol Paraf / TTD (Sesuai Hak Akses Jabatan)
    Route::post('/surat/{id}/paraf', [SuratKeluarController::class, 'paraf'])
        ->middleware(['role:super_admin,superadmin,kasubbag_akademik,kasubbag_psi,kasubbag_keuangan,sekretaris_kabag,sekretaris_wadir_1,sekretaris_wadir_2,sekretaris_wadir_3,sekretaris_direktur,pemeriksa_unit', 'throttle:60,1'])
        ->name('surat.paraf');

    // 3. Rute Upload Dokumen Final TTD & Pembaruan Arsip / Nomor Surat
    Route::middleware(['role:sekretaris_direktur,super_admin,superadmin,sekretaris_wadir_1,sekretaris_wadir_2,sekretaris_wadir_3,sekretaris_kabag,kasubbag_akademik,kasubbag_psi,kasubbag_keuangan,pemeriksa_unit'])->group(function () {
        Route::get('/surat/{id}/final', [SuratKeluarController::class, 'formFinal'])->name('surat.final');
        Route::post('/surat/{id}/final', [SuratKeluarController::class, 'simpanFinal'])->middleware('throttle:30,1')->name('surat.simpanFinal');
        Route::post('/surat/{id}/update-arsip', [SuratKeluarController::class, 'updateArsip'])->middleware('throttle:30,1')->name('surat.updateArsip');
    });

    // 4. Rute Halaman Kelola Hak Akses / Pegawai (Khusus Super Admin)
    Route::middleware(['role:super_admin,superadmin'])->group(function () {
        Route::get('/pegawai', [UserController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/tambah', [UserController::class, 'create'])->name('pegawai.create');
        Route::post('/pegawai/simpan', [UserController::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/template/download', [UserController::class, 'template'])->name('pegawai.template');
        Route::post('/pegawai/import', [UserController::class, 'import'])->middleware('throttle:10,1')->name('pegawai.import');
        Route::get('/pegawai/{id}/edit', [UserController::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/{id}/update', [UserController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{id}/hapus', [UserController::class, 'destroy'])->name('pegawai.destroy');
        Route::post('/pegawai/{id}/reset-password', [UserController::class, 'resetPassword'])->middleware('throttle:10,1')->name('pegawai.resetPassword');
    });

    // 5. Rute Halaman Kelola Jenis Surat (Khusus Super Admin)
    Route::middleware(['role:super_admin,superadmin'])->prefix('jenis-surat')->name('jenis-surat.')->group(function () {
        Route::get('/', [JenisSuratController::class, 'index'])->name('index');
        Route::post('/simpan', [JenisSuratController::class, 'store'])->name('store');
        Route::patch('/{id}/toggle', [JenisSuratController::class, 'toggleStatus'])->name('toggle');
        Route::put('/{id}/update', [JenisSuratController::class, 'update'])->name('update');
        Route::delete('/{id}/hapus', [JenisSuratController::class, 'destroy'])->name('destroy');
    });

    // Rute Tracking (Semua orang yang login boleh melihat)
    Route::get('/surat/{id}/track', [SuratKeluarController::class, 'track'])->name('surat.track');

    // Rute Pratinjau & Unduh Berkas Dokumen (Aman IDOR & Bebas 404)
    Route::get('/surat/{id}/draft-file', [SuratKeluarController::class, 'viewDraftFile'])->name('surat.viewDraftFile');
    Route::get('/surat/{id}/final-file', [SuratKeluarController::class, 'viewFinalFile'])->name('surat.viewFinalFile');
    Route::get('/surat/{id}/lampiran-revisi', [SuratKeluarController::class, 'viewLampiranRevisi'])->name('surat.viewLampiranRevisi');
});

require __DIR__.'/auth.php';
