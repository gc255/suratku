<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Pastikan symlink public/storage terhubung secara otomatis di server
        if (!file_exists(public_path('storage')) && file_exists(storage_path('app/public'))) {
            @symlink(storage_path('app/public'), public_path('storage'));
        }

        // Self-healing: Pastikan kolom tujuan_ppk dan tujuan_rahasia_role selalu ada di database
        try {
            if (Schema::hasTable('surat_keluars')) {
                if (!Schema::hasColumn('surat_keluars', 'tujuan_ppk') || !Schema::hasColumn('surat_keluars', 'tujuan_rahasia_role')) {
                    Schema::table('surat_keluars', function (Blueprint $table) {
                        if (!Schema::hasColumn('surat_keluars', 'tujuan_ppk')) {
                            $table->string('tujuan_ppk')->nullable()->after('tujuan_unit');
                        }
                        if (!Schema::hasColumn('surat_keluars', 'tujuan_rahasia_role')) {
                            $table->string('tujuan_rahasia_role')->nullable()->after('tujuan_penandatangan');
                        }
                    });
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika migrasi/koneksi belum siap atau tidak memiliki izin DDL
        }
    }
}
