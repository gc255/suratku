<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan middleware alias untuk otorisasi jabatan
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Tambahkan HTTP Security Headers secara global ke semua respons web
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Saat CSRF token kadaluarsa (419 Page Expired): logout otomatis & redirect ke login
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                    'redirect' => route('login'),
                ], 419);
            }

            return redirect()->route('login')->with('status', 'Sesi Anda telah berakhir. Silakan login kembali.');
        });
    })->create();
