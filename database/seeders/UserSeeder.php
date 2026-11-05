<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'nip' => 'superadmin',
                'role' => 'super_admin',
                'password' => Hash::make('superadmin123'),
            ],
            [
                'name' => 'Staf Penyusun',
                'nip' => 'penyusun',
                'role' => 'penyusun',
                'password' => Hash::make('penyusun123'),
            ],
            [
                'name' => 'Kasubbag Akademik',
                'nip' => 'kasubbag_akademik',
                'role' => 'kasubbag_akademik',
                'password' => Hash::make('akademik123'),
            ],
            [
                'name' => 'Kasubbag PSI',
                'nip' => 'kasubbag_psi',
                'role' => 'kasubbag_psi',
                'password' => Hash::make('psi123'),
            ],
            [
                'name' => 'Kasubbag Keuangan',
                'nip' => 'kasubbag_keuangan',
                'role' => 'kasubbag_keuangan',
                'password' => Hash::make('keuangan123'),
            ],
            [
                'name' => 'Sekretaris Kabag',
                'nip' => 'sekretaris_kabag',
                'role' => 'sekretaris_kabag',
                'password' => Hash::make('sekretaris_kabag123'),
            ],
            [
                'name' => 'Sekretaris Wadir I',
                'nip' => 'sekretaris_wadir_1',
                'role' => 'sekretaris_wadir_1',
                'password' => Hash::make('wadir1_123'),
            ],
            [
                'name' => 'Sekretaris Wadir II',
                'nip' => 'sekretaris_wadir_2',
                'role' => 'sekretaris_wadir_2',
                'password' => Hash::make('wadir2_123'),
            ],
            [
                'name' => 'Sekretaris Wadir III',
                'nip' => 'sekretaris_wadir_3',
                'role' => 'sekretaris_wadir_3',
                'password' => Hash::make('wadir3_123'),
            ],
            [
                'name' => 'Sekretaris Direktur',
                'nip' => 'sekretaris_direktur',
                'role' => 'sekretaris_direktur',
                'password' => Hash::make('sekretaris123'),
            ],
            [
                'name' => 'PPK 1 (Modal dan Vokasi)',
                'nip' => 'ppk_1',
                'role' => 'ppk_1',
                'password' => Hash::make('ppk1_123'),
            ],
            [
                'name' => 'PPK 2 (Pemeliharaan Rutin)',
                'nip' => 'ppk_2',
                'role' => 'ppk_2',
                'password' => Hash::make('ppk2_123'),
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['nip' => $u['nip']],
                $u
            );
        }
    }
}