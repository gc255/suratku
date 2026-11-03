<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Surat Keputusan',
                'kode' => 'SK',
                'keterangan' => 'Surat penetapan kebijakan atau keputusan resmi pimpinan.',
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Tugas',
                'kode' => 'ST',
                'keterangan' => 'Surat penugasan pegawai/dosen untuk menjalankan kegiatan kedinasan.',
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Undangan',
                'kode' => 'SU',
                'keterangan' => 'Surat undangan pertemuan, rapat, atau acara resmi.',
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Edaran',
                'kode' => 'SE',
                'keterangan' => 'Surat pemberitahuan resmi untuk disebarluaskan ke unit kerja.',
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Keterangan',
                'kode' => 'SKet',
                'keterangan' => 'Surat pernyataan atau keterangan resmi terkait status/hal tertentu.',
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Permohonan',
                'kode' => 'SPm',
                'keterangan' => 'Surat pengajuan atau permohonan ke pihak internal/eksternal.',
                'is_active' => true,
            ],
            [
                'nama' => 'Nota Dinas',
                'kode' => 'ND',
                'keterangan' => 'Naskah dinas intern untuk komunikasi kedinasan antar unit kerja.',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            JenisSurat::firstOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
