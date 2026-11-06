<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

/**
 * Membuat pasangan kunci VAPID untuk Web Push dan menyimpannya ke file .env.
 * Jalankan SEKALI di setiap server:  php artisan webpush:vapid
 */
class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:vapid {--force : Timpa kunci yang sudah ada (perangkat harus berlangganan ulang)} {--show : Hanya tampilkan kunci tanpa menyimpan}';

    protected $description = 'Membuat kunci VAPID untuk notifikasi Web Push dan menyimpannya ke .env';

    public function handle(): int
    {
        $envPath = base_path('.env');

        if (!$this->option('show') && !$this->option('force') && env('VAPID_PUBLIC_KEY') && env('VAPID_PRIVATE_KEY')) {
            $this->info('Kunci VAPID sudah ada di .env. Gunakan --force untuk membuat ulang.');
            return self::SUCCESS;
        }

        try {
            $keys = VAPID::createVapidKeys();
        } catch (\Throwable $e) {
            $this->error('Gagal membuat kunci: ' . $e->getMessage());
            if (PHP_OS_FAMILY === 'Windows') {
                $this->line('Windows/XAMPP: jalankan dengan  set OPENSSL_CONF=C:\\xampp\\php\\extras\\ssl\\openssl.cnf  terlebih dahulu.');
            }
            return self::FAILURE;
        }

        if ($this->option('show')) {
            $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
            $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);
            return self::SUCCESS;
        }

        if (!is_file($envPath) || !is_writable($envPath)) {
            $this->error('.env tidak ditemukan / tidak dapat ditulis. Tambahkan manual:');
            $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
            $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);
            return self::FAILURE;
        }

        $env = file_get_contents($envPath);
        foreach (['VAPID_PUBLIC_KEY' => $keys['publicKey'], 'VAPID_PRIVATE_KEY' => $keys['privateKey']] as $name => $value) {
            if (preg_match('/^' . $name . '=.*$/m', $env)) {
                $env = preg_replace('/^' . $name . '=.*$/m', $name . '=' . $value, $env);
            } else {
                $env = rtrim($env) . PHP_EOL . $name . '=' . $value . PHP_EOL;
            }
        }
        file_put_contents($envPath, $env);

        $this->info('Kunci VAPID berhasil dibuat dan disimpan ke .env.');
        $this->line('Jalankan: php artisan config:clear (atau config:cache bila memakai cache konfigurasi).');

        return self::SUCCESS;
    }
}

