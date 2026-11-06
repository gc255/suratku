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

        // Notifikasi Web Push: pantau perpindahan surat antar meja (tidak mengubah alur surat)
        \App\Models\SuratKeluar::observe(\App\Observers\SuratKeluarObserver::class);
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, \App\Listeners\RevokePushSubscriptionOnLogout::class);

        // Pastikan symlink public/storage terhubung secara otomatis di server
        if (!file_exists(public_path('storage')) && file_exists(storage_path('app/public'))) {
            @symlink(storage_path('app/public'), public_path('storage'));
        }

        // Self-healing: Pastikan kolom surat & tabel push subscriptions selalu siap di database
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

            if (!Schema::hasTable('push_subscriptions') && Schema::hasTable('users')) {
                Schema::create('push_subscriptions', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                    $table->text('endpoint');
                    $table->char('endpoint_hash', 64)->unique();
                    $table->string('public_key', 255);
                    $table->string('auth_token', 255);
                    $table->string('content_encoding', 20)->default('aes128gcm');
                    $table->string('user_agent', 255)->nullable();
                    $table->timestamps();
                    $table->index('user_id');
                });
            }
        } catch (\Throwable $e) {
            // Abaikan jika migrasi/koneksi belum siap atau tidak memiliki izin DDL
        }
    }
}
