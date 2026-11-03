<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\PegawaiImportService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('name', 'asc');
        
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('no_wa', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        return view('pegawai.index', compact('users'));
    }

    public function create()
    {
        $roles = User::getRoleLabels();
        return view('pegawai.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $roles = User::getRoleLabels();

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip',
            'no_wa' => 'nullable|string|max:25',
            'password' => 'nullable|string|min:4',
            'role' => 'required|string|in:' . implode(',', array_keys($roles)),
        ]);

        $nip = trim($request->nip);
        $passwordPlain = $request->filled('password') ? $request->password : $nip;

        User::create([
            'name' => $request->name,
            'nip' => $nip,
            'no_wa' => $request->filled('no_wa') ? trim($request->no_wa) : null,
            'email' => null,
            'password' => Hash::make($passwordPlain),
            'role' => $request->role,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Akun pegawai dan hak akses berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = User::getRoleLabels();
        return view('pegawai.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = User::getRoleLabels();

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip,' . $id,
            'no_wa' => 'nullable|string|max:25',
            'role' => 'required|string|in:' . implode(',', array_keys($roles)),
            'password' => 'nullable|string|min:4',
        ]);

        $userData = [
            'name' => $request->name,
            'nip' => trim($request->nip),
            'no_wa' => $request->filled('no_wa') ? trim($request->no_wa) : null,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai dan hak akses berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan!');
        }

        // Amankan agar tidak menghapus satu-satunya akun Super Admin di sistem
        if (in_array($user->role, ['super_admin', 'superadmin'])) {
            $superAdminCount = User::whereIn('role', ['super_admin', 'superadmin'])->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Aksi Ditolak! Tidak dapat menghapus satu-satunya akun Super Admin pada sistem.');
            }
        }

        // Amankan relasi log tracking dan pembuat surat ke Super Admin agar riwayat dokumen tidak hilang
        $superAdmin = User::whereIn('role', ['super_admin', 'superadmin'])->first();
        if ($superAdmin) {
            \App\Models\TrackingSurat::where('aktor_id', $user->id)->update(['aktor_id' => $superAdmin->id]);
            \App\Models\SuratKeluar::where('pembuat_id', $user->id)->update(['pembuat_id' => $superAdmin->id]);
        }

        $namaPegawai = $user->name;
        $user->delete();

        return back()->with('success', "Akun pegawai {$namaPegawai} berhasil dihapus permanen!");
    }

    /**
     * Download format template import pegawai resmi Excel (.xls)
     */
    public function template(PegawaiImportService $importService)
    {
        $xlsContent = $importService->getTemplateXlsContent();

        return response($xlsContent, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="format_import_pegawai.xls"',
        ]);
    }

    /**
     * Import data pegawai dari file Excel (.xlsx) atau CSV (.csv)
     */
    public function import(Request $request, PegawaiImportService $importService)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv,tsv,txt|max:5120',
        ], [
            'file.required' => 'File data pegawai wajib diunggah.',
            'file.mimes' => 'Format file harus berupa Excel (.xls, .xlsx) atau format teks/CSV (.csv, .tsv, .txt).',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['xls', 'xlsx', 'csv', 'tsv', 'txt'])) {
            return back()->with('error', 'Format file tidak didukung! Harap unggah file berformat .xls, .xlsx, atau .csv');
        }

        $records = $importService->parseFile($file->getRealPath(), $ext);

        if (empty($records)) {
            return back()->with('error', 'File kosong atau kolom tidak sesuai template! Silakan unduh format template terlebih dahulu.');
        }

        $result = $importService->import($records);

        $pesan = "Proses import selesai! {$result['imported']} data pegawai baru berhasil ditambahkan, {$result['updated']} data berhasil diperbarui.";

        return redirect()->route('pegawai.index')->with('success', $pesan);
    }

    /**
     * Reset password pegawai dari halaman Super Admin (Fungsi Lupa Password)
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Generate kode password baru acak yang aman dan mudah dibaca
        // Format: PU dilanjutkan 6 karakter alfanumerik unik (contoh: PU-8K2X9M)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $randomCode = '';
        for ($i = 0; $i < 6; $i++) {
            $randomCode .= $characters[random_int(0, strlen($characters) - 1)];
        }
        $newPassword = 'PU-' . $randomCode;

        // Update password di database
        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()->route('pegawai.index')->with('reset_password_data', [
            'user_id' => $user->id,
            'name' => $user->name,
            'nip' => $user->nip,
            'no_wa' => $user->no_wa,
            'role' => $user->role_label,
            'password' => $newPassword,
            'created_at' => now()->format('d M Y - H:i') . ' WIB',
        ])->with('success', "Password baru untuk pegawai {$user->name} berhasil dibuat!");
    }
}