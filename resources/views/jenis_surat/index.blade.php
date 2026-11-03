<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
                    <span>📑</span> {{ __('Kelola Klasifikasi & Jenis Surat') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Konfigurasi jenis surat kedinasan, kode singkatan, serta saklar visibilitas (ON/OFF).</p>
            </div>
            <a href="{{ route('dashboard') }}" class="glass-pill px-3.5 py-1.5 rounded-xl hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto border border-white/15">
                <span>&larr;</span> Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10" x-data="{
        showAddModal: false,
        showEditModal: false,
        editData: { id: '', nama: '', kode: '', keterangan: '' },
        openEdit(item) {
            this.editData = {
                id: item.id,
                nama: item.nama,
                kode: item.kode || '',
                keterangan: item.keterangan || ''
            };
            this.showEditModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Notifikasi Berhasil -->
            @if (session('success'))
                <div class="p-4 mb-6 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 shadow-lg flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Notifikasi Error Validasi -->
            @if ($errors->any())
                <div class="p-4 mb-6 rounded-2xl bg-rose-950/40 border border-rose-500/30 text-rose-200 shadow-lg">
                    <p class="font-bold flex items-center text-rose-400 text-sm">
                        <span class="mr-2">⛔</span> Terjadi kesalahan input:
                    </p>
                    <ul class="list-disc ml-8 mt-1.5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KARTU STATISTIK WIDGET -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Kartu 1: Total Jenis Surat -->
                <div class="glass-panel rounded-2xl p-6 border border-white/10 shadow-2xl relative overflow-hidden group hover:border-blue-500/40 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Jenis Surat</p>
                            <p class="text-3xl font-black text-white mt-2">{{ $statTotal }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kartu 2: Aktif (ON) -->
                <div class="glass-panel rounded-2xl p-6 border border-emerald-500/30 shadow-2xl relative overflow-hidden group hover:border-emerald-500/50 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-emerald-400">Aktif (Tampil di Penyusun)</p>
                            <p class="text-3xl font-black text-emerald-300 mt-2">{{ $statActive }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kartu 3: Nonaktif (OFF) -->
                <div class="glass-panel rounded-2xl p-6 border border-slate-700/50 shadow-2xl relative overflow-hidden group hover:border-slate-500/50 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Nonaktif (Disembunyikan)</p>
                            <p class="text-3xl font-black text-slate-300 mt-2">{{ $statInactive }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-800/60 border border-white/10 flex items-center justify-center text-slate-400 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Informasi -->
            <div class="glass-panel rounded-2xl p-5 border border-blue-500/30 mb-8 bg-blue-950/30 text-slate-200 shadow-xl flex items-start gap-3.5">
                <span class="text-2xl shrink-0">💡</span>
                <div class="text-xs leading-relaxed">
                    <strong class="font-extrabold text-blue-300 block mb-0.5">Informasi Kontrol Tampilan:</strong>
                    Gunakan saklar toggle <span class="bg-emerald-500/20 text-emerald-300 font-bold px-2 py-0.5 rounded-full text-[11px] border border-emerald-400/30">ON / OFF</span> pada kolom <strong>Status</strong> untuk mengaktifkan atau menonaktifkan jenis surat. Jenis surat yang berstatus <strong>ON (Aktif)</strong> otomatis muncul pada formulir upload draft surat bagi peran <strong>Penyusun</strong>.
                </div>
            </div>

            <!-- Header Bar: Judul, Search & Tombol Tambah -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-extrabold text-white">Daftar Jenis Surat</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola master data klasifikasi dokumen persuratan kedinasan.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                    <!-- Form Pencarian -->
                    <form action="{{ route('jenis-surat.index') }}" method="GET" class="flex w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jenis surat..." class="text-xs border border-white/15 rounded-l-xl w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-white placeholder-slate-400 px-3 py-2 shadow-inner">
                        <button type="submit" class="btn-aksi-blue font-bold px-3.5 py-2 rounded-r-xl text-xs transition">
                            🔍
                        </button>
                    </form>

                    <!-- Tombol Buka Modal Tambah -->
                    <button @click="showAddModal = true" class="btn-aksi-blue inline-flex items-center justify-center gap-1.5 font-bold py-2 px-4 rounded-xl shadow-md text-xs transition whitespace-nowrap cursor-pointer">
                        <span class="text-sm leading-none">+</span> Tambah Jenis Surat
                    </button>
                </div>
            </div>

            <!-- TABEL DATA JENIS SURAT -->
            <div class="glass-panel rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-slate-950/70 border-b border-white/10">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Jenis Surat</th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-24">Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-28">Total Surat</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-44">Status (ON/OFF)</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-transparent">
                            @forelse($jenisSurats as $index => $item)
                                <tr class="hover:bg-white/[0.04] transition">
                                    <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-400 text-center font-mono">
                                        {{ $jenisSurats->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-white text-sm flex items-center">
                                            <span class="w-2.5 h-2.5 rounded-full mr-2.5 {{ $item->is_active ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]' : 'bg-slate-600' }}"></span>
                                            {{ $item->nama }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if($item->kode)
                                            <span class="glass-pill text-blue-300 border border-blue-400/30 px-2.5 py-1 rounded-md text-xs font-mono font-bold">
                                                {{ $item->kode }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-500 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-300 max-w-xs truncate" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan ?: '-' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item->surat_keluars_count > 0 ? 'bg-blue-500/20 text-blue-300 border border-blue-400/30' : 'bg-slate-800 text-slate-400 border border-white/10' }}">
                                            {{ $item->surat_keluars_count }} surat
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <!-- Form Tombol Switch ON / OFF -->
                                        <form action="{{ route('jenis-surat.toggle', $item->id) }}" method="POST" class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <button type="submit" 
                                                    title="{{ $item->is_active ? 'Klik untuk Menonaktifkan (OFF)' : 'Klik untuk Mengaktifkan (ON)' }}"
                                                    class="relative inline-flex items-center h-7 rounded-full w-14 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $item->is_active ? 'bg-emerald-500 focus:ring-emerald-500' : 'bg-slate-700 focus:ring-slate-400' }} shadow-inner cursor-pointer">
                                                
                                                <!-- Bulatan Slider -->
                                                <span class="inline-block w-5 h-5 transform bg-white rounded-full transition-transform shadow-md {{ $item->is_active ? 'translate-x-8' : 'translate-x-1' }}"></span>
                                                
                                                <!-- Teks Label di dalam Slider -->
                                                <span class="absolute text-[10px] font-black tracking-wider {{ $item->is_active ? 'left-2 text-white' : 'right-2 text-slate-300' }}">
                                                    {{ $item->is_active ? 'ON' : 'OFF' }}
                                                </span>
                                            </button>
                                        </form>

                                        <!-- Badge Deskriptif -->
                                        <div class="mt-1">
                                            @if($item->is_active)
                                                <span class="inline-flex items-center text-[10px] font-semibold text-emerald-400">
                                                    ● Tampil di Penyusun
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-[10px] font-semibold text-slate-500">
                                                    ○ Disembunyikan
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Tombol Edit -->
                                            <button type="button" 
                                                    @click="openEdit({{ json_encode($item) }})" 
                                                    class="btn-aksi-indigo font-bold px-2.5 py-1.5 rounded-xl transition text-xs flex items-center gap-1 cursor-pointer">
                                                ✏️ Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('jenis-surat.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis surat \'{{ $item->nama }}\'?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-aksi-red font-bold px-2.5 py-1.5 rounded-xl transition text-xs flex items-center gap-1 cursor-pointer">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        <div class="text-4xl mb-2">📭</div>
                                        <p class="font-medium text-slate-300">Belum ada jenis surat yang terdaftar.</p>
                                        <p class="text-xs text-slate-500 mt-1">Klik tombol "+ Tambah Jenis Surat" di atas untuk menambahkan jenis surat baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($jenisSurats->hasPages())
                    <div class="p-4 border-t border-white/10 bg-slate-950/40">
                        {{ $jenisSurats->links() }}
                    </div>
                @endif
            </div>

            <!-- ======================================================== -->
            <!-- MODAL TAMBAH JENIS SURAT -->
            <!-- ======================================================== -->
            <div x-show="showAddModal" 
                 x-cloak 
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                
                <!-- Backdrop -->
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showAddModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" 
                         @click="showAddModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showAddModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom glass-panel rounded-2xl text-left overflow-hidden shadow-2xl border border-white/15 bg-slate-900/95 text-slate-100 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        
                        <form action="{{ route('jenis-surat.store') }}" method="POST">
                            @csrf
                            <div class="px-6 pt-6 pb-4">
                                <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-5">
                                    <h3 class="text-lg font-extrabold text-white flex items-center" id="modal-title">
                                        <span class="mr-2">➕</span> Tambah Jenis Surat Baru
                                    </h3>
                                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-white text-2xl font-bold transition">&times;</button>
                                </div>

                                <div class="space-y-4">
                                    <!-- Input Nama Jenis Surat -->
                                    <div>
                                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-1.5">
                                            Nama Jenis Surat <span class="text-rose-400">*</span>
                                        </label>
                                        <input type="text" name="nama" required 
                                               placeholder="Contoh: Surat Tugas, Surat Keputusan"
                                               class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/90 text-sm">
                                        <p class="text-[11px] text-slate-400 mt-1">Nama resmi jenis surat yang akan tampil dalam dropdown pilihan.</p>
                                    </div>

                                    <!-- Input Kode / Singkatan -->
                                    <div>
                                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-1.5">
                                            Kode / Singkatan <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <input type="text" name="kode" 
                                               placeholder="Contoh: ST, SK, SU, ND"
                                               class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/90 text-sm uppercase">
                                        <p class="text-[11px] text-slate-400 mt-1">Kode identifikasi untuk format nomor surat jika diperlukan.</p>
                                    </div>

                                    <!-- Input Keterangan -->
                                    <div>
                                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-1.5">
                                            Keterangan / Penggunaan <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <textarea name="keterangan" rows="2" 
                                                  placeholder="Jelaskan secara singkat kegunaan jenis surat ini..."
                                                  class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/90 text-sm"></textarea>
                                    </div>

                                    <!-- Checkbox Status Awal Aktif -->
                                    <div class="bg-emerald-950/40 border border-emerald-500/30 rounded-xl p-3.5">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-white/20 bg-slate-900 text-emerald-500 shadow-sm focus:ring-emerald-500 w-4 h-4">
                                            <span class="ml-2 text-xs font-extrabold text-emerald-300">Langsung Aktifkan (ON)</span>
                                        </label>
                                        <p class="text-[11px] text-emerald-400/90 ml-6 mt-0.5">Jika dicentang, jenis surat ini akan langsung dapat dipilih oleh penyusun saat upload surat.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-950/70 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-white/10 gap-3">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl btn-aksi-blue font-bold px-5 py-2.5 sm:w-auto text-xs">
                                    Simpan Jenis Surat
                                </button>
                                <button type="button" @click="showAddModal = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl glass-pill px-4 py-2.5 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 transition border border-white/15 sm:w-auto">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- MODAL EDIT JENIS SURAT -->
            <!-- ======================================================== -->
            <div x-show="showEditModal" 
                 x-cloak 
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title" role="dialog" aria-modal="true">
                
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showEditModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" 
                         @click="showEditModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showEditModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom glass-panel rounded-2xl text-left overflow-hidden shadow-2xl border border-white/15 bg-slate-900/95 text-slate-100 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        
                        <form :action="'{{ url('jenis-surat') }}/' + editData.id + '/update'" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="px-6 pt-6 pb-4">
                                <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-5">
                                    <h3 class="text-lg font-extrabold text-white flex items-center">
                                        <span class="mr-2">✏️</span> Edit Jenis Surat
                                    </h3>
                                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-white text-2xl font-bold transition">&times;</button>
                                </div>

                                <div class="space-y-4">
                                    <!-- Input Nama Jenis Surat -->
                                    <div>
                                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-1.5">
                                            Nama Jenis Surat <span class="text-rose-400">*</span>
                                        </label>
                                        <input type="text" name="nama" x-model="editData.nama" required 
                                               class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/90 text-sm">
                                    </div>

                                    <!-- Input Kode / Singkatan -->
                                    <div>
                                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-1.5">
                                            Kode / Singkatan <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <input type="text" name="kode" x-model="editData.kode" 
                                               class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/90 text-sm uppercase">
                                    </div>

                                    <!-- Input Keterangan -->
                                    <div>
                                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-1.5">
                                            Keterangan / Penggunaan <span class="text-slate-400 font-normal">(Opsional)</span>
                                        </label>
                                        <textarea name="keterangan" rows="2" x-model="editData.keterangan"
                                                  class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/90 text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-950/70 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-white/10 gap-3">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl btn-aksi-indigo font-bold px-5 py-2.5 sm:w-auto text-xs">
                                    Perbarui Jenis Surat
                                </button>
                                <button type="button" @click="showEditModal = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl glass-pill px-4 py-2.5 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 transition border border-white/15 sm:w-auto">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
