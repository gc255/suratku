<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
    }
}
