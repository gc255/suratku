<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisSuratTest extends TestCase
{
    use RefreshDatabase;

    protected function createSuperAdmin()
    {
        return User::create([
            'name' => 'Super Admin Test',
            'nip' => '99990001',
            'role' => 'super_admin',
            'password' => bcrypt('password'),
        ]);
    }

    protected function createPenyusun()
    {
        return User::create([
            'name' => 'Penyusun Test',
            'nip' => '99990002',
            'role' => 'penyusun',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_super_admin_can_access_jenis_surat_index(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $response = $this->actingAs($superAdmin)->get(route('jenis-surat.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Jenis Surat');
    }

    public function test_penyusun_cannot_access_jenis_surat_management(): void
    {
        $penyusun = $this->createPenyusun();

        $response = $this->actingAs($penyusun)->get(route('jenis-surat.index'));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_super_admin_can_create_new_jenis_surat(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $namaUnik = 'Surat Rekomendasi Khusus';
        $response = $this->actingAs($superAdmin)->post(route('jenis-surat.store'), [
            'nama' => $namaUnik,
            'kode' => 'SR',
            'keterangan' => 'Surat rekomendasi untuk keperluan dinas',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('jenis-surat.index'));
        $this->assertDatabaseHas('jenis_surats', [
            'nama' => $namaUnik,
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_toggle_jenis_surat_on_and_off(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $jenis = JenisSurat::create([
            'nama' => 'Surat Toggle Test',
            'kode' => 'STT',
            'is_active' => true,
        ]);

        // Toggle to OFF
        $response = $this->actingAs($superAdmin)->patch(route('jenis-surat.toggle', $jenis->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('jenis_surats', [
            'id' => $jenis->id,
            'is_active' => false,
        ]);

        // Toggle back to ON
        $response = $this->actingAs($superAdmin)->patch(route('jenis-surat.toggle', $jenis->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('jenis_surats', [
            'id' => $jenis->id,
            'is_active' => true,
        ]);
    }

    public function test_penyusun_only_sees_active_jenis_surat_on_upload_page(): void
    {
        $penyusun = $this->createPenyusun();

        $activeName = 'Surat Aktif Terlihat';
        $inactiveName = 'Surat Nonaktif Tersembunyi';

        JenisSurat::create([
            'nama' => $activeName,
            'is_active' => true,
        ]);

        JenisSurat::create([
            'nama' => $inactiveName,
            'is_active' => false,
        ]);

        $response = $this->actingAs($penyusun)->get(route('surat.create'));
        $response->assertStatus(200);
        $response->assertSee($activeName);
        $response->assertDontSee($inactiveName);
    }

    public function test_super_admin_can_delete_jenis_surat(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $jenis = JenisSurat::create([
            'nama' => 'Surat Dihapus',
            'kode' => 'SD',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superAdmin)->delete(route('jenis-surat.destroy', $jenis->id));
        $response->assertRedirect(route('jenis-surat.index'));
        $this->assertDatabaseMissing('jenis_surats', [
            'id' => $jenis->id,
        ]);
    }

    public function test_dashboard_pagination_defaults_to_5_and_allows_custom_limit(): void
    {
        $superAdmin = $this->createSuperAdmin();

        // Buat 12 surat
        for ($i = 1; $i <= 12; $i++) {
            \App\Models\SuratKeluar::create([
                'judul' => "Surat Uji Pagination {$i}",
                'file_draft' => "draft_surat/sample_{$i}.pdf",
                'pembuat_id' => $superAdmin->id,
                'status' => 'DIBUAT',
            ]);
        }

        // 1. Default per_page adalah 5
        $responseDefault = $this->actingAs($superAdmin)->get(route('dashboard'));
        $responseDefault->assertStatus(200);
        $this->assertCount(5, $responseDefault->viewData('surats'));
        $this->assertEquals(5, $responseDefault->viewData('perPage'));

        // 2. Opsi per_page = 10
        $response10 = $this->actingAs($superAdmin)->get(route('dashboard', ['per_page' => 10]));
        $response10->assertStatus(200);
        $this->assertCount(10, $response10->viewData('surats'));
        $this->assertEquals(10, $response10->viewData('perPage'));

        // 3. Opsi per_page = 20 (menampilkan seluruh 12 surat)
        $response20 = $this->actingAs($superAdmin)->get(route('dashboard', ['per_page' => 20]));
        $response20->assertStatus(200);
        $this->assertCount(12, $response20->viewData('surats'));
        $this->assertEquals(20, $response20->viewData('perPage'));
    }

    public function test_super_admin_sees_single_action_button_with_vertical_popup_menu(): void
    {
        $superAdmin = $this->createSuperAdmin();

        // 1. Surat draft tanpa file final: Download Final TIDAK boleh muncul
        \App\Models\SuratKeluar::create([
            'judul' => 'Surat Draft Belum Final',
            'file_draft' => 'draft_surat/sample_draft.pdf',
            'pembuat_id' => $superAdmin->id,
            'status' => 'DIBUAT',
        ]);

        $responseDraft = $this->actingAs($superAdmin)->get(route('dashboard'));
        $responseDraft->assertStatus(200);
        $responseDraft->assertSee('⚙️ Aksi');
        $responseDraft->assertSee('View Surat');
        $responseDraft->assertSee('Tracking Surat');
        $responseDraft->assertSee('Hapus Surat');
        $responseDraft->assertDontSee('Download Final');

        // 2. Surat yang sudah memiliki file_final: Download Final HARUS muncul
        \App\Models\SuratKeluar::create([
            'judul' => 'Surat Selesai Final',
            'file_draft' => 'draft_surat/sample_draft2.pdf',
            'file_final' => 'surat_final/sample_final.pdf',
            'pembuat_id' => $superAdmin->id,
            'status' => 'DIARSIPKAN',
        ]);

        $responseFinal = $this->actingAs($superAdmin)->get(route('dashboard'));
        $responseFinal->assertStatus(200);
        $responseFinal->assertSee('Download Final');
    }

    public function test_hierarchical_surat_status_shows_next_level_approval_waiting(): void
    {
        $superAdmin = $this->createSuperAdmin();

        $surat = \App\Models\SuratKeluar::create([
            'judul' => 'Surat Hierarki Status',
            'file_draft' => 'draft_surat/test.pdf',
            'pembuat_id' => $superAdmin->id,
            'status' => \App\Models\SuratKeluar::STATUS_MENUNGGU_KASUBBAG,
        ]);

        $this->assertEquals('Menunggu Paraf Kasubbag', $surat->status_label);

        $surat->status = \App\Models\SuratKeluar::STATUS_MENUNGGU_KABAG;
        $this->assertEquals('Menunggu Paraf Kabag', $surat->status_label);

        $surat->status = \App\Models\SuratKeluar::STATUS_MENUNGGU_WADIR;
        $this->assertEquals('Menunggu Paraf Wadir', $surat->status_label);

        // Contoh spesifik user: Setelah Wadir paraf, status menunjukkan Menunggu TTD Direktur
        $surat->status = \App\Models\SuratKeluar::STATUS_MENUNGGU_DIREKTUR;
        $this->assertEquals('Menunggu TTD Direktur', $surat->status_label);

        $surat->status = \App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL;
        $this->assertEquals('Menunggu Upload Final', $surat->status_label);

        $surat->status = \App\Models\SuratKeluar::STATUS_DIARSIPKAN;
        $this->assertEquals('Diarsipkan', $surat->status_label);

        // Pastikan muncul pada tampilan dashboard
        $surat->status = \App\Models\SuratKeluar::STATUS_MENUNGGU_DIREKTUR;
        $surat->save();

        $response = $this->actingAs($superAdmin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Menunggu TTD Direktur');
    }

    public function test_complete_approval_workflow_with_10_roles(): void
    {
        // 1. Setup Users for roles
        $penyusun = \App\Models\User::firstOrCreate(
            ['nip' => 'penyusun_test'],
            ['name' => 'Penyusun Test', 'role' => 'penyusun', 'password' => 'secret']
        );
        $kasubbagAkademik = \App\Models\User::firstOrCreate(
            ['nip' => 'kasubbag_akademik_test'],
            ['name' => 'Kasubbag Akademik Test', 'role' => 'kasubbag_akademik', 'password' => 'secret']
        );
        $kasubbagPsi = \App\Models\User::firstOrCreate(
            ['nip' => 'kasubbag_psi_test'],
            ['name' => 'Kasubbag PSI Test', 'role' => 'kasubbag_psi', 'password' => 'secret']
        );
        $sekretarisKabag = \App\Models\User::firstOrCreate(
            ['nip' => 'sek_kabag_test'],
            ['name' => 'Sekretaris Kabag Test', 'role' => 'sekretaris_kabag', 'password' => 'secret']
        );
        $sekretarisWadir1 = \App\Models\User::firstOrCreate(
            ['nip' => 'sek_wadir1_test'],
            ['name' => 'Sekretaris Wadir I Test', 'role' => 'sekretaris_wadir_1', 'password' => 'secret']
        );
        $sekretarisWadir2 = \App\Models\User::firstOrCreate(
            ['nip' => 'sek_wadir2_test'],
            ['name' => 'Sekretaris Wadir II Test', 'role' => 'sekretaris_wadir_2', 'password' => 'secret']
        );
        $sekretarisDirektur = \App\Models\User::firstOrCreate(
            ['nip' => 'sek_direktur_test'],
            ['name' => 'Sekretaris Direktur Test', 'role' => 'sekretaris_direktur', 'password' => 'secret']
        );

        $jenisSurat = \App\Models\JenisSurat::create([
            'nama' => 'Surat Keputusan Workflow',
            'kode' => 'SK-WF',
            'is_active' => true,
        ]);

        // 2. Tahap 1: Penyusun upload surat memilih Kasubbag Akademik
        $file = \Illuminate\Http\UploadedFile::fake()->create('draft.pdf', 500, 'application/pdf');
        $uploadResponse = $this->actingAs($penyusun)->post(route('surat.store'), [
            'judul' => 'Surat Pengujian Alur 10 Roles',
            'jenis_surat_id' => $jenisSurat->id,
            'tujuan_kasubbag' => 'kasubbag_akademik',
            'file_draft' => $file,
        ]);
        $uploadResponse->assertRedirect(route('dashboard'));

        $surat = \App\Models\SuratKeluar::where('judul', 'Surat Pengujian Alur 10 Roles')->firstOrFail();
        $this->assertEquals(\App\Models\SuratKeluar::STATUS_MENUNGGU_KASUBBAG, $surat->status);
        $this->assertEquals('kasubbag_akademik', $surat->tujuan_kasubbag);

        // Kasubbag Akademik melihat surat di dashboard
        $resKasubbagAkademik = $this->actingAs($kasubbagAkademik)->get(route('dashboard'));
        $resKasubbagAkademik->assertSee('Surat Pengujian Alur 10 Roles');
        $resKasubbagAkademik->assertSee('✅ Berikan Paraf');

        // Kasubbag PSI TIDAK melihat surat ini (karena ditujukan ke Kasubbag Akademik)
        $resKasubbagPsi = $this->actingAs($kasubbagPsi)->get(route('dashboard'));
        $resKasubbagPsi->assertDontSee('Surat Pengujian Alur 10 Roles');

        // 3. Tahap 2: Kasubbag Akademik paraf -> Status menjadi MENUNGGU_PARAF_KABAG
        $parafKasubbagRes = $this->actingAs($kasubbagAkademik)->post(route('surat.paraf', $surat->id));
        $parafKasubbagRes->assertSessionHas('success');
        $surat->refresh();
        $this->assertEquals(\App\Models\SuratKeluar::STATUS_MENUNGGU_KABAG, $surat->status);

        // Sekretaris Kabag melihat surat
        $resSekretarisKabag = $this->actingAs($sekretarisKabag)->get(route('dashboard'));
        $resSekretarisKabag->assertSee('Surat Pengujian Alur 10 Roles');
        $resSekretarisKabag->assertSee('Paraf & Pilih Wadir', false);

        // 4. Tahap 3: Sekretaris Kabag paraf & memilih Sekretaris Wadir II
        $parafKabagRes = $this->actingAs($sekretarisKabag)->post(route('surat.paraf', $surat->id), [
            'tujuan_wadir' => 'sekretaris_wadir_2',
        ]);
        $parafKabagRes->assertSessionHas('success');
        $surat->refresh();
        $this->assertEquals(\App\Models\SuratKeluar::STATUS_MENUNGGU_WADIR, $surat->status);
        $this->assertEquals('sekretaris_wadir_2', $surat->tujuan_wadir);

        // Sekretaris Wadir II melihat surat
        $resWadir2 = $this->actingAs($sekretarisWadir2)->get(route('dashboard'));
        $resWadir2->assertSee('Surat Pengujian Alur 10 Roles');
        $resWadir2->assertSee('✅ Paraf Wadir');

        // Sekretaris Wadir I TIDAK melihat surat ini
        $resWadir1 = $this->actingAs($sekretarisWadir1)->get(route('dashboard'));
        $resWadir1->assertDontSee('Surat Pengujian Alur 10 Roles');

        // 5. Tahap 4: Sekretaris Wadir II paraf -> Status menjadi MENUNGGU_TTD_DIREKTUR
        $parafWadirRes = $this->actingAs($sekretarisWadir2)->post(route('surat.paraf', $surat->id));
        $parafWadirRes->assertSessionHas('success');
        $surat->refresh();
        $this->assertEquals(\App\Models\SuratKeluar::STATUS_MENUNGGU_DIREKTUR, $surat->status);

        // Sekretaris Direktur melihat surat dan tombol TTD Direktur
        $resSekDir = $this->actingAs($sekretarisDirektur)->get(route('dashboard'));
        $resSekDir->assertSee('Surat Pengujian Alur 10 Roles');
        $resSekDir->assertSee('✍️ Berikan TTD Direktur');

        // 6. Tahap 5: Sekretaris Direktur berikan TTD Direktur -> Status menjadi MENUNGGU_UPLOAD_FINAL
        $ttdRes = $this->actingAs($sekretarisDirektur)->post(route('surat.paraf', $surat->id));
        $ttdRes->assertSessionHas('success');
        $surat->refresh();
        $this->assertEquals(\App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, $surat->status);

        $resSekDirFinal = $this->actingAs($sekretarisDirektur)->get(route('dashboard'));
        $resSekDirFinal->assertSee('Upload TTD Final');

        // 7. Tahap 6: Sekretaris Direktur upload final -> Status menjadi DIARSIPKAN
        $finalFile = \Illuminate\Http\UploadedFile::fake()->create('final_ttd.pdf', 600, 'application/pdf');
        $simpanFinalRes = $this->actingAs($sekretarisDirektur)->post(route('surat.simpanFinal', $surat->id), [
            'file_final' => $finalFile,
        ]);
        $simpanFinalRes->assertRedirect(route('dashboard'));
        $surat->refresh();
        $this->assertEquals(\App\Models\SuratKeluar::STATUS_DIARSIPKAN, $surat->status);
        $this->assertNotNull($surat->file_final);
    }
}


