<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckRole
{
    // Fungsi handle() adalah pos penjagaan utamanya
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Super Admin selalu memiliki akses penuh ke seluruh rute dan aksi
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            return $next($request);
        }

        // 2. Jika role pengguna saat ini terdaftar secara eksplisit
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika rute mengizinkan grup 'pemeriksa_unit' dan user adalah salah satu role Unit/Prodi/Lab
        if (in_array('pemeriksa_unit', $roles) && User::isPemeriksaUnit($user->role)) {
            return $next($request);
        }

        // 4. Jika rute mengizinkan grup 'ppk' dan user adalah salah satu role PPK
        if (in_array('ppk', $roles) && User::isPpk($user->role)) {
            return $next($request);
        }

        // Jika tidak lolos verifikasi, arahkan kembali ke dashboard dengan peringatan
        return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda tidak memiliki izin untuk membuka halaman atau melakukan aksi tersebut.');
    }
}
