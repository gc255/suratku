<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Di sinilah kita memanggil UserSeeder
        $this->call([
            UserSeeder::class,
        ]);
    }
}
