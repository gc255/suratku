<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    /**
     * Tampilkan daftar jenis surat untuk Super Admin
     */
    public function index(Request $request)
    {
        $query = JenisSurat::withCount('suratKeluars');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $jenisSurats = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();

        $statTotal = JenisSurat::count();
        $statActive = JenisSurat::where('is_active', true)->count();
        $statInactive = JenisSurat::where('is_active', false)->count();

        return view('jenis_surat.index', compact('jenisSurats', 'statTotal', 'statActive', 'statInactive'));
    }

    /**
     * Tambah jenis surat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_surats,nama',
            'kode' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama jenis surat wajib diisi.',
            'nama.unique' => 'Nama jenis surat ini sudah terdaftar.',
            'nama.max' => 'Nama jenis surat maksimal 100 karakter.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        JenisSurat::create($validated);

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat "' . $validated['nama'] . '" berhasil ditambahkan!');
    }

    /**
     * Update data jenis surat (nama, kode, keterangan)
     */
    public function update(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_surats,nama,' . $id,
            'kode' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama jenis surat wajib diisi.',
            'nama.unique' => 'Nama jenis surat sudah digunakan.',
            'nama.max' => 'Nama jenis surat maksimal 100 karakter.',
        ]);

        $jenisSurat->update($validated);

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat "' . $jenisSurat->nama . '" berhasil diperbarui!');
    }

    /**
     * Toggle On/Off status aktif jenis surat
     */
    public function toggleStatus(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);
        $jenisSurat->is_active = !$jenisSurat->is_active;
        $jenisSurat->save();

        $statusText = $jenisSurat->is_active ? 'diaktifkan (ON)' : 'dinonaktifkan (OFF)';
        $message = "Jenis surat \"{$jenisSurat->nama}\" berhasil {$statusText}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $jenisSurat->is_active,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Hapus jenis surat
     */
    public function destroy($id)
    {
        $jenisSurat = JenisSurat::withCount('suratKeluars')->findOrFail($id);
        $nama = $jenisSurat->nama;
        $count = $jenisSurat->surat_keluars_count;

        $jenisSurat->delete();

        $pesan = "Jenis surat \"{$nama}\" berhasil dihapus.";
        if ($count > 0) {
            $pesan .= " (Catatan: {$count} arsip surat yang sebelumnya menggunakan jenis surat ini tetap aman disimpan di sistem).";
        }

        return redirect()->route('jenis-surat.index')->with('success', $pesan);
    }
}
