<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\TrackingSurat;
use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    // Fungsi untuk menampilkan halaman form pengajuan surat
    public function create()
    {
        // Ambil hanya jenis surat yang statusnya AKTIF (ON)
        $jenisSurats = JenisSurat::where('is_active', true)->orderBy('nama', 'asc')->get();
        
        // Daftar 29 Unit/Prodi/Lab
        $kaprodis = User::getKaprodiRoles();
        $ketuaUnits = User::getKetuaUnitRoles();
        $kepalaLabs = User::getKepalaLabRoles();
        $pemeriksaUnits = User::getPemeriksaUnitRoles();

        // Daftar 3 Kasubbag
        $kasubbags = User::getKasubbagRoles();

        // Opsi Penandatangan
        $opsiPenandatangan = SuratKeluar::getOpsiPenandatangan();

        return view('surat.create', compact(
            'jenisSurats', 
            'kaprodis', 
            'ketuaUnits', 
            'kepalaLabs', 
            'pemeriksaUnits', 
            'kasubbags', 
            'opsiPenandatangan'
        ));
    }

    // Fungsi untuk memproses data yang dikirim dari form
    public function store(Request $request)
    {
        $unitKeys = array_keys(User::getPemeriksaUnitRoles());
        $kasubbagKeys = array_keys(User::getKasubbagRoles());
        $penandatanganKeys = array_keys(SuratKeluar::getOpsiPenandatangan());

        // 1. Validasi input (Penyusun hanya memilih pemeriksa awal & penandatangan akhir)
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'pemeriksa_awal' => 'required|string',
            'tujuan_penandatangan' => 'required|in:' . implode(',', $penandatanganKeys),
            'file_draft' => 'required|mimes:pdf,doc,docx|max:5120', // Maks 5MB
        ], [
            'judul.required' => 'Judul surat wajib diisi.',
            'jenis_surat_id.required' => 'Silakan pilih jenis surat.',
            'jenis_surat_id.exists' => 'Jenis surat yang dipilih tidak valid atau sudah dinonaktifkan.',
            'pemeriksa_awal.required' => 'Silakan tentukan tujuan pemeriksaan awal surat.',
            'tujuan_penandatangan.required' => 'Silakan pilih tujuan penandatangan akhir surat.',
            'file_draft.required' => 'File draft surat wajib diunggah.',
            'file_draft.mimes' => 'File draft harus berformat PDF, DOC, atau DOCX.',
            'file_draft.max' => 'Ukuran file draft maksimal 5MB.',
        ]);

        $pemeriksaAwal = $request->pemeriksa_awal;
        $tujuanUnit = null;
        $tujuanKasubbag = null;
        $initialStatus = '';
        $targetAwalLabel = '';

        if (in_array($pemeriksaAwal, $unitKeys)) {
            // Memulai dari Unit / Prodi / Lab (Kasubbag tujuan akan dipilih nanti oleh pemeriksa pertama)
            $tujuanUnit = $pemeriksaAwal;
            $tujuanKasubbag = null;
            $initialStatus = SuratKeluar::STATUS_MENUNGGU_UNIT;
            $targetAwalLabel = User::getPemeriksaUnitRoles()[$tujuanUnit] ?? 'Unit';
        } elseif (in_array($pemeriksaAwal, $kasubbagKeys)) {
            // Memulai langsung ke Kasubbag (by-pass unit)
            $tujuanUnit = null;
            $tujuanKasubbag = $pemeriksaAwal;
            $initialStatus = SuratKeluar::STATUS_MENUNGGU_KASUBBAG;
            $targetAwalLabel = User::getKasubbagRoles()[$tujuanKasubbag] ?? 'Kasubbag';
        } else {
            return back()->withInput()->withErrors(['pemeriksa_awal' => 'Pilihan pemeriksa awal tidak valid.']);
        }

        // Jika penandatangan dipilih 'unit', pastikan pemeriksaan awal memang berasal dari unit
        if ($request->tujuan_penandatangan === 'unit' && !$tujuanUnit) {
            return back()->withInput()->withErrors(['tujuan_penandatangan' => 'Penandatangan tingkat Unit hanya dapat dipilih jika surat diawali dari tingkat Unit/Prodi/Laboratorium.']);
        }

        // 2. Simpan file ke folder storage
        $path = $request->file('file_draft')->store('draft_surat', 'public');

        // 3. Simpan data ke tabel surat_keluars
        $surat = SuratKeluar::create([
            'judul' => $request->judul,
            'jenis_surat_id' => $request->jenis_surat_id,
            'tujuan_unit' => $tujuanUnit,
            'tujuan_kasubbag' => $tujuanKasubbag,
            'tujuan_penandatangan' => $request->tujuan_penandatangan,
            'file_draft' => $path,
            'pembuat_id' => Auth::id(),
            'status' => $initialStatus,
        ]);

        // 4. Simpan catatan ke tabel tracking_surats
        $isAwalFinal = ($surat->tujuan_penandatangan === ($tujuanUnit ? 'unit' : 'kasubbag'));
        $targetAksiWord = $isAwalFinal ? 'tanda tangan ' : 'paraf ';

        TrackingSurat::create([
            'surat_keluar_id' => $surat->id,
            'aktor_id' => Auth::id(),
            'aksi' => 'DRAFT DIBUAT dan menunggu ' . $targetAksiWord . $targetAwalLabel,
            'catatan' => 'Penyusun mengunggah draft awal ditujukan ke ' . $targetAwalLabel . ' (Penandatangan: ' . $surat->tujuan_penandatangan_label . ').',
        ]);

        // 5. Kembalikan ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Draft surat berhasil diunggah dan diteruskan ke ' . $targetAwalLabel . '!');
    }

    // Fungsi untuk memproses tombol paraf dan penerusan surat bertingkat (Teruskan atau Kembalikan/Revisi)
    public function paraf(Request $request, $id)
    {
        // 1. Cari surat berdasarkan ID
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();
        $keputusan = $request->input('keputusan', 'teruskan'); // 'teruskan' | 'kembalikan'

        // =========================================================================
        // SKENARIO A: DITOLAK / DIKEMBALIKAN KE TINGKAT SEBELUMNYA
        // =========================================================================
        if ($keputusan === 'kembalikan') {
            // Bersihkan suffix 'dan menunggu...' pada log sebelumnya jika surat dikembalikan/revisi
            TrackingSurat::where('surat_keluar_id', $surat->id)
                ->where('aksi', 'like', '% dan menunggu %')
                ->get()
                ->each(function($t) {
                    $t->aksi = preg_replace('/\s+dan menunggu.*$/i', '', $t->aksi);
                    $t->save();
                });

            $request->validate([
                'komentar' => 'required|string|min:3|max:1000',
                'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                'target_kembali' => 'nullable|string|in:penyusun,unit,kasubbag,kabag,wadir',
            ], [
                'komentar.required' => 'Wajib menuliskan catatan/alasan saat mengembalikan atau meminta revisi surat.',
                'komentar.min' => 'Catatan revisi minimal 3 karakter.',
                'file_lampiran.mimes' => 'Format file lampiran harus PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
                'file_lampiran.max' => 'Ukuran file lampiran maksimal 10MB.',
            ]);

            // Validasi hak akses peninjau
            $isSuperAdmin = in_array($user->role, ['super_admin', 'superadmin']);

            // Validasi status alur agar hanya pihak yang sedang menunggu surat yang dapat mengembalikannya
            if (!$isSuperAdmin) {
                $canKembalikan = false;
                if (User::isPemeriksaUnit($user->role) && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT])) {
                    $canKembalikan = true;
                } elseif (in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan']) && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG])) {
                    $canKembalikan = true;
                } elseif ($user->role === 'sekretaris_kabag' && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG])) {
                    $canKembalikan = true;
                } elseif (in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3']) && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR])) {
                    $canKembalikan = true;
                } elseif ($user->role === 'sekretaris_direktur' && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR'])) {
                    $canKembalikan = true;
                }

                if (!$canKembalikan) {
                    return back()->with('error', 'Akses Ditolak! Dokumen saat ini tidak sedang menunggu tindakan dari jabatan Anda.');
                }
            }

            if (User::isPemeriksaUnit($user->role)) {
                if ($surat->tujuan_unit && $surat->tujuan_unit !== $user->role && !$isSuperAdmin) {
                    return back()->with('error', 'Akses Ditolak! Dokumen surat ini ditujukan ke ' . ($surat->tujuan_unit_label ?? 'Unit lain') . '.');
                }
            } elseif (in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])) {
                if ($surat->tujuan_kasubbag && $surat->tujuan_kasubbag !== $user->role && !$isSuperAdmin) {
                    return back()->with('error', 'Akses Ditolak! Dokumen surat ini ditujukan ke ' . ($surat->tujuan_kasubbag_label ?? 'Kasubbag lain') . '.');
                }
            } elseif (in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3'])) {
                if ($surat->tujuan_wadir && $surat->tujuan_wadir !== $user->role && !$isSuperAdmin) {
                    return back()->with('error', 'Akses Ditolak! Dokumen surat ini diteruskan ke ' . ($surat->tujuan_wadir_label ?? 'Wakil Direktur lain') . '.');
                }
            }

            $komentar = trim($request->komentar);
            $pathLampiran = null;
            if ($request->hasFile('file_lampiran')) {
                $pathLampiran = $request->file('file_lampiran')->store('lampiran_revisi', 'public');
            }

            $surat->catatan_terakhir = $komentar;
            $surat->lampiran_revisi_terakhir = $pathLampiran;

            // Dapatkan opsi pengembalian yang valid berdasarkan alur yang sudah dilewati
            $opsiList = $surat->getOpsiPengembalian($user->role);
            $validKeys = array_column($opsiList, 'key');

            $targetPilihan = $request->input('target_kembali');
            if (!$targetPilihan || !in_array($targetPilihan, $validKeys)) {
                $targetPilihan = !empty($validKeys) ? $validKeys[0] : 'penyusun';
            }

            $statusBaru = '';
            $targetLabel = '';
            $pesanSukses = '';

            if ($targetPilihan === 'wadir') {
                $statusBaru = SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR;
                $targetLabel = $surat->tujuan_wadir_pejabat_label ?? 'Wakil Direktur';
                $pesanSukses = 'Surat berhasil dikembalikan ke ' . $targetLabel . ' beserta catatan revisi.';
            } elseif ($targetPilihan === 'kabag') {
                $statusBaru = SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG;
                $targetLabel = 'Sekretaris Kepala Bagian (Kabag)';
                $pesanSukses = 'Surat berhasil dikembalikan ke Sekretaris Kepala Bagian untuk perbaikan.';
            } elseif ($targetPilihan === 'kasubbag') {
                $statusBaru = SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG;
                $targetLabel = $surat->tujuan_kasubbag_label ?? 'Kasubbag';
                $pesanSukses = 'Surat berhasil dikembalikan ke ' . $targetLabel . ' beserta catatan revisi.';
            } elseif ($targetPilihan === 'unit') {
                $statusBaru = SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT;
                $targetLabel = $surat->tujuan_unit_label ?? 'Unit/Prodi/Lab';
                $pesanSukses = 'Surat berhasil dikembalikan ke ' . $targetLabel . ' beserta catatan revisi.';
            } else { // 'penyusun'
                $statusBaru = SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN;
                $targetLabel = 'Penyusun (' . ($surat->pembuat->name ?? 'Pembuat Surat') . ')';
                $pesanSukses = 'Surat berhasil dikembalikan ke Penyusun beserta catatan revisi.';
            }

            $surat->status = $statusBaru;
            $surat->save();

            TrackingSurat::create([
                'surat_keluar_id' => $surat->id,
                'aktor_id' => $user->id,
                'aksi' => 'Dikembalikan ke ' . $targetLabel . ' (Revisi)',
                'catatan' => $komentar,
                'file_lampiran' => $pathLampiran,
            ]);

            return back()->with('success', $pesanSukses);
        }

        // =========================================================================
        // SKENARIO B: DISETUJUI & DITERUSKAN / DITANDATANGANI
        // =========================================================================
        $komentarPersetujuan = $request->filled('komentar') ? trim($request->komentar) : null;
        $surat->catatan_terakhir = $komentarPersetujuan;
        $surat->lampiran_revisi_terakhir = null;

        $statusBaru = '';
        $namaAksi = '';
        $catatan = 'Dokumen telah diperiksa dan disetujui.';
        $pesan = 'Surat berhasil diparaf dan diteruskan!';

        $targetPenandatangan = $surat->tujuan_penandatangan ?? 'direktur';

        // TAHAP 0: Unit / Prodi / Lab Memberikan Paraf atau TTD
        if (User::isPemeriksaUnit($user->role)) {
            if (!in_array($user->role, ['super_admin', 'superadmin'])) {
                if (!in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_UNIT, SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT])) {
                    return back()->with('error', 'Aksi Ditolak! Dokumen saat ini tidak sedang menunggu tindakan dari Unit/Prodi/Lab.');
                }
            }

            if ($surat->tujuan_unit && $surat->tujuan_unit !== $user->role && !in_array($user->role, ['super_admin', 'superadmin'])) {
                return back()->with('error', 'Akses Ditolak! Dokumen surat ini ditujukan ke ' . ($surat->tujuan_unit_label ?? 'Unit lain') . '.');
            }

            $unitLabel = in_array($user->role, ['super_admin', 'superadmin'])
                ? ($surat->tujuan_unit_label ?? 'Unit')
                : $user->role_label;

            // Cek apakah surat berhenti dan ditandatangani di level Unit
            if ($targetPenandatangan === 'unit') {
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_FINAL;
                $namaAksi = 'Ditandatangani ' . $unitLabel . ' dan menunggu upload berkas final';
                $catatan = $unitLabel . ' telah memeriksa, menyetujui, dan menandatangani dokumen surat.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Dokumen berhasil ditandatangani! Silakan unggah file final yang telah ditandatangani dan dicap.';
            } else {
                // Unit memilih Kasubbag tujuan sebagai pemeriksa lanjutan
                $kasubbagRoles = array_keys(User::getKasubbagRoles());
                $request->validate([
                    'tujuan_kasubbag' => 'required|in:' . implode(',', $kasubbagRoles),
                ], [
                    'tujuan_kasubbag.required' => 'Silakan pilih Kepala Subbagian (Kasubbag) yang dituju sebagai pemeriksa lanjutan.',
                    'tujuan_kasubbag.in' => 'Pilihan Kepala Subbagian tidak valid.',
                ]);

                $surat->tujuan_kasubbag = $request->tujuan_kasubbag;
                $kasubbagTujuanLabel = User::getKasubbagRoles()[$request->tujuan_kasubbag] ?? 'Kasubbag Terkait';
                $isKasubbagFinal = ($targetPenandatangan === 'kasubbag');
                $targetWord = $isKasubbagFinal ? 'tanda tangan ' : 'paraf ';
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_KASUBBAG;
                $namaAksi = 'Diparaf ' . $unitLabel . ' dan menunggu ' . $targetWord . $kasubbagTujuanLabel;
                $catatan = 'Dokumen telah diperiksa dan diparaf oleh ' . $unitLabel . '. Diteruskan ke ' . $kasubbagTujuanLabel . '.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Surat berhasil diparaf dan diteruskan ke ' . $kasubbagTujuanLabel . '!';
            }
        }

        // TAHAP 1: Kasubbag Memberikan Paraf atau TTD
        elseif (in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])) {
            if (!in_array($user->role, ['super_admin', 'superadmin'])) {
                if (!in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG])) {
                    return back()->with('error', 'Aksi Ditolak! Dokumen saat ini tidak sedang menunggu tindakan dari Kasubbag.');
                }
            }

            if ($surat->tujuan_kasubbag && $surat->tujuan_kasubbag !== $user->role && !in_array($user->role, ['super_admin', 'superadmin'])) {
                return back()->with('error', 'Akses Ditolak! Dokumen surat ini ditujukan ke ' . ($surat->tujuan_kasubbag_label ?? 'Kasubbag lain') . '.');
            }

            $kasubbagLabel = in_array($user->role, ['super_admin', 'superadmin']) 
                ? ($surat->tujuan_kasubbag_label ?? 'Kasubbag') 
                : $user->role_label;

            // Cek apakah surat berhenti dan ditandatangani di level Kasubbag
            if ($targetPenandatangan === 'kasubbag') {
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_FINAL;
                $namaAksi = 'Ditandatangani ' . $kasubbagLabel . ' dan menunggu upload berkas final';
                $catatan = $kasubbagLabel . ' telah memeriksa, menyetujui, dan menandatangani dokumen surat.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Dokumen berhasil ditandatangani! Silakan unggah file final yang telah ditandatangani.';
            } else {
                $isKabagFinal = ($targetPenandatangan === 'kabag');
                $targetWord = $isKabagFinal ? 'tanda tangan ' : 'paraf ';
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_KABAG;
                $namaAksi = 'Diparaf ' . $kasubbagLabel . ' dan menunggu ' . $targetWord . 'Kepala Bagian (Kabag)';
                $catatan = $komentarPersetujuan 
                    ? $komentarPersetujuan 
                    : 'Dokumen telah diperiksa dan disetujui oleh ' . $kasubbagLabel . '. Diteruskan ke Sekretaris Kepala Administrasi Akademik dan Umum.';
                $pesan = 'Surat berhasil diparaf dan diteruskan ke Kepala Bagian (Kabag)!';
            }
        } 
        
        // TAHAP 2: Sekretaris Kabag Memberikan Paraf/TTD Kabag
        elseif ($user->role === 'sekretaris_kabag') {
            if (!in_array($user->role, ['super_admin', 'superadmin'])) {
                if (!in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG])) {
                    return back()->with('error', 'Aksi Ditolak! Dokumen saat ini tidak sedang menunggu tindakan dari Sekretaris Kabag.');
                }
            }

            // Cek apakah surat berhenti dan ditandatangani di level Kabag
            if ($targetPenandatangan === 'kabag') {
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_FINAL;
                $namaAksi = 'Ditandatangani Kepala Bagian (Kabag) dan menunggu upload berkas final';
                $catatan = 'Kepala Bagian (Kabag) memproses persetujuan & tanda tangan dokumen melalui Sekretaris Kabag.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Dokumen yang telah ditandatangani Kabag berhasil diproses! Silakan unggah file final yang telah ditandatangani.';
            } else {
                // Perlu memilih Wadir tujuan jika alur lanjut ke Wadir/Direktur
                $wadirRoles = array_keys(User::getSekretarisWadirRoles());
                $request->validate([
                    'tujuan_wadir' => 'required|in:' . implode(',', $wadirRoles),
                ], [
                    'tujuan_wadir.required' => 'Silakan pilih Sekretaris Wakil Direktur yang dituju.',
                    'tujuan_wadir.in' => 'Pilihan Sekretaris Wakil Direktur tidak valid.',
                ]);

                $surat->tujuan_wadir = $request->tujuan_wadir;
                $wadirRawLabel = User::getSekretarisWadirRoles()[$request->tujuan_wadir] ?? 'Wakil Direktur';
                $wadirPejabatLabel = str_replace('Sekretaris ', '', $wadirRawLabel);
                $isWadirFinal = ($targetPenandatangan === 'wadir');
                $targetWord = $isWadirFinal ? 'tanda tangan ' : 'paraf ';
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_WADIR;
                $namaAksi = 'Diparaf Kepala Bagian (Kabag) dan menunggu ' . $targetWord . $wadirPejabatLabel;
                $catatan = 'Sekretaris Kabag memproses paraf Kabag dan meneruskan surat ke ' . $wadirPejabatLabel . '.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Surat berhasil diparaf dan diteruskan ke ' . $wadirPejabatLabel . '!';
            }
        } 
        
        // TAHAP 3: Sekretaris Wakil Direktur Memberikan Paraf/TTD Wadir
        elseif (in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3'])) {
            if (!in_array($user->role, ['super_admin', 'superadmin'])) {
                if (!in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR])) {
                    return back()->with('error', 'Aksi Ditolak! Dokumen saat ini tidak sedang menunggu tindakan dari Sekretaris Wakil Direktur.');
                }
            }

            if ($surat->tujuan_wadir && $surat->tujuan_wadir !== $user->role && !in_array($user->role, ['super_admin', 'superadmin'])) {
                return back()->with('error', 'Akses Ditolak! Dokumen surat ini diteruskan ke ' . ($surat->tujuan_wadir_label ?? 'Wakil Direktur lain') . '.');
            }

            $wadirPejabatLabel = str_replace('Sekretaris ', '', $user->role_label);

            // Cek apakah surat berhenti dan ditandatangani di level Wadir
            if ($targetPenandatangan === 'wadir') {
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_FINAL;
                $namaAksi = 'Ditandatangani ' . $wadirPejabatLabel . ' dan menunggu upload berkas final';
                $catatan = $wadirPejabatLabel . ' telah memeriksa dan menandatangani dokumen melalui ' . $user->role_label . '.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Dokumen yang telah ditandatangani ' . $wadirPejabatLabel . ' berhasil diproses! Silakan unggah file final yang telah ditandatangani.';
            } else {
                $statusBaru = SuratKeluar::STATUS_MENUNGGU_DIREKTUR;
                $namaAksi = 'Diparaf ' . $wadirPejabatLabel . ' dan menunggu tanda tangan Direktur';
                $catatan = 'Wakil Direktur telah memeriksa dan menyetujui dokumen melalui ' . $user->role_label . '. Diteruskan ke Sekretaris Direktur.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
                $pesan = 'Surat berhasil diparaf dan diteruskan ke Sekretaris Direktur!';
            }
        } 
        
        // TAHAP 4: Sekretaris Direktur Memproses Dokumen Ditandatangani Direktur
        elseif ($user->role === 'sekretaris_direktur') {
            if (!in_array($user->role, ['super_admin', 'superadmin'])) {
                if (!in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR'])) {
                    return back()->with('error', 'Aksi Ditolak! Dokumen saat ini tidak sedang menunggu tanda tangan Direktur.');
                }
            }

            $statusBaru = SuratKeluar::STATUS_MENUNGGU_FINAL;
            $namaAksi = 'Ditandatangani Direktur dan menunggu upload berkas final';
            $catatan = 'Sekretaris Direktur memproses dokumen yang telah di tanda tangan Direktur.' . ($komentarPersetujuan ? ' Catatan: ' . $komentarPersetujuan : '');
            $pesan = 'Dokumen yang telah ditandatangani Direktur berhasil diproses! Silakan unggah file final yang telah ditandatangani.';
        }

        // Simpan perubahan ke database
        $surat->status = $statusBaru;
        $surat->save();

        // Bersihkan suffix 'dan menunggu...' pada riwayat log sebelumnya
        TrackingSurat::where('surat_keluar_id', $surat->id)
            ->where('aksi', 'like', '% dan menunggu %')
            ->get()
            ->each(function($t) {
                $t->aksi = preg_replace('/\s+dan menunggu.*$/i', '', $t->aksi);
                $t->save();
            });

        // Catat riwayat perjalanan surat ke tracking_surats
        TrackingSurat::create([
            'surat_keluar_id' => $surat->id,
            'aktor_id' => $user->id,
            'aksi' => $namaAksi,
            'catatan' => $catatan,
        ]);

        return back()->with('success', $pesan);
    }

    // Fungsi bagi Penyusun untuk memperbarui draft saat ada kesalahan atau saat diminta revisi
    public function updateDraft(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        // Validasi hak akses edit Penyusun sesuai aturan:
        // Dapat diedit jika belum diparaf oleh pemeriksa level pertama ATAU berstatus revisi
        if (!$surat->canPenyusunEdit($user->id)) {
            return back()->with('error', 'Akses Ditolak! Dokumen surat ini sudah diparaf oleh pemeriksa level pertama dan tidak dapat diedit lagi (hanya dapat dibatalkan/dihapus jika ada kekeliruan).');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'tujuan_kasubbag' => 'nullable|string',
            'file_draft' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ], [
            'judul.required' => 'Judul surat wajib diisi.',
            'jenis_surat_id.required' => 'Silakan pilih jenis surat.',
            'file_draft.mimes' => 'File draft harus berformat PDF, DOC, atau DOCX.',
            'file_draft.max' => 'Ukuran file draft maksimal 5MB.',
        ]);

        if ($request->hasFile('file_draft')) {
            $path = $request->file('file_draft')->store('draft_surat', 'public');
            $surat->file_draft = $path;
        }

        $surat->judul = $request->judul;
        $surat->jenis_surat_id = $request->jenis_surat_id;

        if ($request->filled('tujuan_kasubbag') && array_key_exists($request->tujuan_kasubbag, User::getKasubbagRoles())) {
            $surat->tujuan_kasubbag = $request->tujuan_kasubbag;
        }

        // Tentukan kembali ke pemeriksa awal surat
        $targetPertamaLabel = '';
        $isTargetFinal = false;
        if ($surat->tujuan_unit) {
            $surat->status = SuratKeluar::STATUS_MENUNGGU_UNIT;
            $targetPertamaLabel = $surat->tujuan_unit_label ?? 'Unit/Prodi/Lab';
            $isTargetFinal = ($surat->tujuan_penandatangan === 'unit');
        } else {
            $surat->status = SuratKeluar::STATUS_MENUNGGU_KASUBBAG;
            $targetPertamaLabel = $surat->tujuan_kasubbag_label ?? 'Kasubbag';
            $isTargetFinal = ($surat->tujuan_penandatangan === 'kasubbag');
        }

        $surat->catatan_terakhir = null;
        $surat->lampiran_revisi_terakhir = null;
        $surat->save();

        // Bersihkan suffix 'dan menunggu...' pada riwayat sebelumnya jika ada
        TrackingSurat::where('surat_keluar_id', $surat->id)
            ->where('aksi', 'like', '% dan menunggu %')
            ->get()
            ->each(function($t) {
                $t->aksi = preg_replace('/\s+dan menunggu.*$/i', '', $t->aksi);
                $t->save();
            });

        $targetAksiWord = $isTargetFinal ? 'tanda tangan ' : 'paraf ';

        TrackingSurat::create([
            'surat_keluar_id' => $surat->id,
            'aktor_id' => $user->id,
            'aksi' => 'DRAFT DIPERBARUI dan menunggu ' . $targetAksiWord . $targetPertamaLabel,
            'catatan' => 'Penyusun telah memperbarui data draft dokumen dan meneruskannya ke ' . $targetPertamaLabel . '.',
        ]);

        return redirect()->route('dashboard')->with('success', 'Draft surat berhasil diperbarui dan diteruskan ke ' . $targetPertamaLabel . '!');
    }

    // Fungsi untuk melihat detail tracking surat
    public function track($id)
    {
        $surat = SuratKeluar::with('jenisSurat')->findOrFail($id);
        $user = Auth::user();

        // Pencegahan IDOR: Hanya pihak yang berwenang yang dapat melihat riwayat dan berkas surat
        if (!$this->canUserViewSurat($user, $surat)) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk melihat detail atau riwayat dokumen surat ini.');
        }
        
        // Ambil semua log tracking, urutkan dari yang terlama ke terbaru
        $trackings = TrackingSurat::with('aktor')
                        ->where('surat_keluar_id', $id)
                        ->orderBy('created_at', 'asc')
                        ->get();

        return view('surat.track', compact('surat', 'trackings'));
    }

    // ==========================================
    // FUNGSI UNTUK UPLOAD FINAL (DITANDATANGANI)
    // ==========================================

    // 1. Menampilkan form upload final
    public function formFinal($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        // Validasi status alur surat (izinkan jika sudah menunggu final atau sedang di tahap tanda tangan akhir)
        if (!$this->isSuratAtFinalSigningStage($surat, $user)) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Dokumen surat belum berada pada tahap tanda tangan akhir / upload berkas final.');
        }

        // Validasi otorisasi pengunggah final
        if (!$this->canUserUploadFinal($user, $surat)) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda bukan pihak yang berwenang mengunggah berkas final untuk dokumen ini.');
        }

        return view('surat.upload-final', compact('surat'));
    }

    // 2. Memproses upload file final
    public function simpanFinal(Request $request, $id)
    {
        // Validasi nomor surat dan file harus PDF maksimal 5MB
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'file_final' => 'required|mimes:pdf|max:5120', 
        ], [
            'nomor_surat.required' => 'Nomor Surat resmi wajib diisi saat mengunggah berkas final.',
            'nomor_surat.max' => 'Nomor Surat maksimal 100 karakter.',
            'file_final.required' => 'File surat final (.pdf) wajib diunggah.',
            'file_final.mimes' => 'File final harus berformat PDF.',
            'file_final.max' => 'Ukuran file final maksimal 5MB.',
        ]);

        $surat = SuratKeluar::with('jenisSurat')->findOrFail($id);
        $user = Auth::user();

        // Validasi status alur surat (izinkan jika sudah menunggu final atau sedang di tahap tanda tangan akhir)
        if (!$this->isSuratAtFinalSigningStage($surat, $user)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Ditolak! Dokumen surat belum berada pada tahap tanda tangan akhir / upload berkas final.'
                ], 403);
            }
            return back()->with('error', 'Akses Ditolak! Dokumen surat belum berada pada tahap tanda tangan akhir / upload berkas final.');
        }

        // Validasi otorisasi pengunggah final
        if (!$this->canUserUploadFinal($user, $surat)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses Ditolak! Anda bukan pihak yang berwenang mengunggah berkas final untuk dokumen ini.'
                ], 403);
            }
            return back()->with('error', 'Akses Ditolak! Anda bukan pihak yang berwenang mengunggah berkas final untuk dokumen ini.');
        }
        
        // Simpan file ke folder storage/app/public/surat_final
        $path = $request->file('file_final')->store('surat_final', 'public');

        // Pastikan ada riwayat Ditandatangani jika sebelumnya belum tercatat
        $penandatanganLabel = $surat->tujuan_penandatangan_label;
        $hasTtdLog = TrackingSurat::where('surat_keluar_id', $surat->id)
            ->where('aksi', 'like', 'Ditandatangani%')
            ->exists();

        if (!$hasTtdLog) {
            $catatanTambahan = $request->input('catatan');
            TrackingSurat::create([
                'surat_keluar_id' => $surat->id,
                'aktor_id' => $user->id,
                'aksi' => 'Ditandatangani ' . $penandatanganLabel,
                'catatan' => $catatanTambahan 
                    ? ($user->role_label . ' memproses dokumen yang telah ditandatangani ' . $penandatanganLabel . '. Catatan: ' . $catatanTambahan)
                    : ($user->role_label . ' memproses dokumen yang telah ditandatangani ' . $penandatanganLabel . '.'),
            ]);
        }

        // Update status dan nomor surat di database
        $surat->update([
            'nomor_surat' => $request->nomor_surat,
            'file_final' => $path,
            'pengunggah_final_id' => $user->id,
            'status' => 'DIARSIPKAN',
            'catatan_terakhir' => null,
        ]);

        // Bersihkan suffix 'dan menunggu...' pada log sebelumnya
        TrackingSurat::where('surat_keluar_id', $surat->id)
            ->where('aksi', 'like', '% dan menunggu %')
            ->get()
            ->each(function($t) {
                $t->aksi = preg_replace('/\s+dan menunggu.*$/i', '', $t->aksi);
                $t->save();
            });

        // Catat riwayat bahwa surat sudah diarsipkan
        TrackingSurat::create([
            'surat_keluar_id' => $surat->id,
            'aktor_id' => $user->id,
            'aksi' => 'Surat Selesai & Diarsipkan',
            'catatan' => 'Dokumen final dengan Nomor Surat ' . $surat->nomor_surat . ' dan tanda tangan basah/digital telah diunggah oleh ' . $user->name . ' (' . $user->role_label . ').',
        ]);

        \Carbon\Carbon::setLocale('id');
        $arsipInfo = [
            'id' => $surat->id,
            'nomor_surat' => $surat->nomor_surat,
            'judul' => $surat->judul,
            'jenis_surat' => $surat->jenisSurat->nama ?? 'Surat',
            'penandatangan' => $penandatanganLabel,
            'diarsipkan_oleh' => $user->name . ' (' . $user->role_label . ')',
            'file_url' => asset('storage/' . $path),
            'waktu' => now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB',
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumen Surat berhasil ditandatangani dan diarsipkan!',
                'dokumen' => $arsipInfo,
                'redirect_url' => route('dashboard', ['tab' => 'sudah_paraf']),
            ]);
        }

        return redirect()->route('dashboard', ['tab' => 'sudah_paraf'])
            ->with('success', 'File Surat Final (No: ' . $surat->nomor_surat . ') berhasil diunggah dan diarsipkan!')
            ->with('dokumen_diarsipkan', $arsipInfo);
    }

    // 3. Memperbarui Berkas Final dan/atau Nomor Surat Arsip
    public function updateArsip(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        $isPengunggah = ($surat->pengunggah_final_id === $user->id);
        $isSekdir = ($user->role === 'sekretaris_direktur');
        $isSuperAdmin = in_array($user->role, ['super_admin', 'superadmin']);

        // Otorisasi: Hanya pengunggah, Sekdir (untuk nomor surat), atau Super Admin
        if (!$isPengunggah && !$isSekdir && !$isSuperAdmin) {
            return back()->with('error', 'Akses Ditolak! Hanya akun pengunggah dokumen atau Sekretaris Direktur yang dapat mengubah data arsip.');
        }

        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'file_final' => 'nullable|mimes:pdf|max:5120',
            'catatan_edit' => 'nullable|string|max:255',
        ], [
            'nomor_surat.required' => 'Nomor Surat resmi wajib diisi.',
            'nomor_surat.max' => 'Nomor Surat maksimal 100 karakter.',
            'file_final.mimes' => 'Berkas pengganti harus berformat PDF.',
            'file_final.max' => 'Ukuran berkas PDF maksimal 5MB.',
        ]);

        $nomorLama = $surat->nomor_surat;
        $berkasDiganti = false;

        // Penggantian file PDF hanya boleh oleh akun pengunggah atau Super Admin
        if ($request->hasFile('file_final')) {
            if (!$isPengunggah && !$isSuperAdmin) {
                return back()->with('error', 'Akses Ditolak! Penggantian berkas PDF dokumen hanya dapat dilakukan oleh akun yang melakukan pengarsipan/upload.');
            }
            $path = $request->file('file_final')->store('surat_final', 'public');
            $surat->file_final = $path;
            $berkasDiganti = true;
        }

        $surat->nomor_surat = $request->nomor_surat;
        $surat->save();

        // Rangkum catatan perubahan untuk tracking
        $perubahan = [];
        if ($nomorLama !== $surat->nomor_surat) {
            $perubahan[] = 'Nomor Surat diubah menjadi "' . $surat->nomor_surat . '"' . ($nomorLama ? ' (sebelumnya: ' . $nomorLama . ')' : '');
        }
        if ($berkasDiganti) {
            $perubahan[] = 'berkas dokumen PDF final diganti';
        }
        if (empty($perubahan)) {
            $perubahan[] = 'data arsip diperbarui';
        }

        $catatanLog = $user->name . ' (' . $user->role_label . ') memperbarui arsip: ' . implode(', ', $perubahan) . '.' . 
            ($request->catatan_edit ? ' Catatan: ' . $request->catatan_edit : '');

        TrackingSurat::create([
            'surat_keluar_id' => $surat->id,
            'aktor_id' => $user->id,
            'aksi' => 'Pembaruan Dokumen & Nomor Surat',
            'catatan' => $catatanLog,
        ]);

        return redirect()->back()->with('success', 'Data arsip dokumen dan Nomor Surat (' . $surat->nomor_surat . ') berhasil diperbarui!');
    }

    // Fungsi untuk membatalkan/menghapus draft surat
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        // 1. Jika Super Admin, berikan wewenang menghapus dokumen surat apapun
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            $surat->delete();
            TrackingSurat::create([
                'surat_keluar_id' => $surat->id,
                'aktor_id' => $user->id,
                'aksi' => 'Dihapus Super Admin',
                'catatan' => 'Super Admin menghapus dokumen surat dari sistem.',
            ]);
            return back()->with('success', 'Surat berhasil dihapus oleh Super Admin.');
        }

        // 2. Proteksi Penyusun: Pembuat surat boleh membatalkan/menghapus draft selama belum ditandatangani final / diarsipkan
        if ($surat->canPenyusunDelete($user->id)) {
            $surat->delete(); // Laravel otomatis akan melakukan Soft Delete
            
            TrackingSurat::create([
                'surat_keluar_id' => $surat->id,
                'aktor_id' => $user->id,
                'aksi' => 'Draft Dibatalkan / Dihapus',
                'catatan' => 'Penyusun membatalkan dan menghapus dokumen draft surat ini.',
            ]);

            return back()->with('success', 'Draft surat berhasil dibatalkan dan dihapus.');
        }

        // Jika ada yang mencoba menghapus surat yang sudah diproses final / diarsipkan
        return back()->with('error', 'Gagal! Dokumen surat yang telah selesai atau diarsipkan tidak dapat ditarik kembali.');
    }

    /**
     * Memeriksa otorisasi apakah pengguna berhak melihat detail dan riwayat surat (Mencegah IDOR)
     */
    protected function canUserViewSurat(User $user, SuratKeluar $surat): bool
    {
        // 1. Super Admin memiliki akses penuh ke seluruh surat
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            return true;
        }

        // 2. Pembuat surat / Penyusun selalu dapat melihat surat buatannya sendiri
        if ($surat->pembuat_id === $user->id) {
            return true;
        }

        // 3. Pengguna yang pernah terlibat dalam riwayat tracking surat
        if ($surat->trackings()->where('aktor_id', $user->id)->exists()) {
            return true;
        }

        // 4. Pemeriksa Unit yang dituju
        if (User::isPemeriksaUnit($user->role) && ($surat->tujuan_unit === $user->role || is_null($surat->tujuan_unit))) {
            return true;
        }

        // 5. Kasubbag yang dituju
        if (in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan']) && ($surat->tujuan_kasubbag === $user->role || is_null($surat->tujuan_kasubbag))) {
            return true;
        }

        // 6. Sekretaris Kabag
        if ($user->role === 'sekretaris_kabag') {
            return true;
        }

        // 7. Sekretaris Wadir yang dituju
        if (in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3']) && ($surat->tujuan_wadir === $user->role || is_null($surat->tujuan_wadir))) {
            return true;
        }

        // 8. Sekretaris Direktur
        if ($user->role === 'sekretaris_direktur') {
            return true;
        }

        return false;
    }

    /**
     * Memeriksa apakah dokumen surat sedang berada pada tahap tanda tangan akhir atau upload berkas final
     */
    protected function isSuratAtFinalSigningStage(SuratKeluar $surat, User $user): bool
    {
        // 1. Jika sudah dalam status menunggu upload berkas final atau selesai ttd
        if (in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])) {
            return true;
        }

        // 2. Jika Super Admin, selalu diizinkan
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            return true;
        }

        // 3. Cek apakah surat saat ini sedang menunggu tindakan dari penandatangan final
        $penandatangan = $surat->tujuan_penandatangan ?? 'direktur';

        if ($penandatangan === 'direktur') {
            return ($user->role === 'sekretaris_direktur')
                && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR']);
        }

        if ($penandatangan === 'wadir') {
            return in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3'])
                && (!$surat->tujuan_wadir || $surat->tujuan_wadir === $user->role)
                && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR]);
        }

        if ($penandatangan === 'kabag') {
            return ($user->role === 'sekretaris_kabag')
                && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG]);
        }

        if ($penandatangan === 'kasubbag') {
            return in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])
                && (!$surat->tujuan_kasubbag || $surat->tujuan_kasubbag === $user->role)
                && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG]);
        }

        if ($penandatangan === 'unit') {
            return User::isPemeriksaUnit($user->role)
                && (!$surat->tujuan_unit || $surat->tujuan_unit === $user->role)
                && in_array($surat->status, [SuratKeluar::STATUS_MENUNGGU_UNIT, 'DIBUAT', SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT]);
        }

        return false;
    }

    /**
     * Memeriksa otorisasi apakah pengguna berhak mengunggah berkas final surat
     */
    protected function canUserUploadFinal(User $user, SuratKeluar $surat): bool
    {
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            return true;
        }

        $penandatangan = $surat->tujuan_penandatangan ?? 'direktur';

        if ($penandatangan === 'direktur') {
            return $user->role === 'sekretaris_direktur';
        }

        if ($penandatangan === 'wadir') {
            return in_array($user->role, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3']) 
                && (!$surat->tujuan_wadir || $surat->tujuan_wadir === $user->role);
        }

        if ($penandatangan === 'kabag') {
            return $user->role === 'sekretaris_kabag';
        }

        if ($penandatangan === 'kasubbag') {
            return in_array($user->role, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])
                && (!$surat->tujuan_kasubbag || $surat->tujuan_kasubbag === $user->role);
        }

        if ($penandatangan === 'unit') {
            return User::isPemeriksaUnit($user->role)
                && (!$surat->tujuan_unit || $surat->tujuan_unit === $user->role);
        }

        return false;
    }

    /**
     * Menampilkan atau streaming berkas draft surat secara aman dan bebas 404
     */
    public function viewDraftFile($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        if (!$this->canUserViewSurat($user, $surat)) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk melihat berkas dokumen draft surat ini.');
        }

        if (!$surat->file_draft) {
            abort(404, 'Berkas draft surat belum diunggah.');
        }

        $path = storage_path('app/public/' . $surat->file_draft);
        if (!file_exists($path)) {
            $path = storage_path('app/' . $surat->file_draft);
        }
        if (!file_exists($path)) {
            $path = public_path('storage/' . $surat->file_draft);
        }

        if (!file_exists($path)) {
            abort(404, 'Berkas fisik draft surat tidak ditemukan pada penyimpanan server.');
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        $filename = basename($path);

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * Menampilkan atau streaming berkas surat final yang telah ditandatangani
     */
    public function viewFinalFile($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        if (!$this->canUserViewSurat($user, $surat)) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk melihat berkas surat final ini.');
        }

        if (!$surat->file_final) {
            abort(404, 'Berkas surat final belum tersedia.');
        }

        $path = storage_path('app/public/' . $surat->file_final);
        if (!file_exists($path)) {
            $path = storage_path('app/' . $surat->file_final);
        }
        if (!file_exists($path)) {
            $path = public_path('storage/' . $surat->file_final);
        }

        if (!file_exists($path)) {
            abort(404, 'Berkas fisik surat final tidak ditemukan pada penyimpanan server.');
        }

        $mime = mime_content_type($path) ?: 'application/pdf';
        $filename = basename($path);

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * Mengunduh atau menampilkan berkas lampiran revisi terakhir
     */
    public function viewLampiranRevisi($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        if (!$this->canUserViewSurat($user, $surat)) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk melihat lampiran revisi ini.');
        }

        if (!$surat->lampiran_revisi_terakhir) {
            abort(404, 'Berkas lampiran revisi tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $surat->lampiran_revisi_terakhir);
        if (!file_exists($path)) {
            $path = storage_path('app/' . $surat->lampiran_revisi_terakhir);
        }
        if (!file_exists($path)) {
            $path = public_path('storage/' . $surat->lampiran_revisi_terakhir);
        }

        if (!file_exists($path)) {
            abort(404, 'Berkas fisik lampiran revisi tidak ditemukan pada penyimpanan server.');
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        $filename = basename($path);

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
