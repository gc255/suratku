<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>📑</span> {{ __('Kelola Klasifikasi & Jenis Surat') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Konfigurasi jenis surat kedinasan, kode singkatan, serta saklar visibilitas (ON/OFF).</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-pop-white px-3.5 py-1.5 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                <span>&larr;</span> Kembali
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
                <div class="p-4 mb-6 cartoon-card rounded-2xl bg-emerald-100 border-2 border-emerald-500 text-emerald-950 shadow-[3px_3px_0px_#059669] flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Notifikasi Error Validasi -->
            @if ($errors->any())
                <div class="p-4 mb-6 cartoon-card rounded-2xl bg-rose-100 border-2 border-rose-500 text-rose-950 shadow-[3px_3px_0px_#E11D48]">
                    <p class="font-black flex items-center text-rose-900 text-sm">
                        <span class="mr-2">⛔</span> Terjadi kesalahan input:
                    </p>
                    <ul class="list-disc ml-8 mt-1.5 text-xs font-semibold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KARTU STATISTIK WIDGET -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Kartu 1: Total Jenis Surat -->
                <div class="cartoon-card rounded-3xl p-5 sm:p-6 bg-white text-slate-800 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-cartoon font-bold uppercase tracking-wider text-slate-500">Total Jenis Surat</p>
                            <p class="text-3xl font-cartoon font-bold text-[#003366] mt-1">{{ $statTotal }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 border-2 border-[#003366] shadow-[2.5px_2.5px_0px_#003366] flex items-center justify-center text-[#003366]">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kartu 2: Aktif (ON) -->
                <div class="cartoon-card rounded-3xl p-5 sm:p-6 bg-emerald-50 text-slate-800 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-cartoon font-bold uppercase tracking-wider text-emerald-800">Aktif (Tampil di Penyusun)</p>
                            <p class="text-3xl font-cartoon font-bold text-emerald-900 mt-1">{{ $statActive }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-200 border-2 border-[#003366] shadow-[2.5px_2.5px_0px_#003366] flex items-center justify-center text-emerald-800">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kartu 3: Nonaktif (OFF) -->
                <div class="cartoon-card rounded-3xl p-5 sm:p-6 bg-slate-50 text-slate-800 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-cartoon font-bold uppercase tracking-wider text-slate-500">Nonaktif (Disembunyikan)</p>
                            <p class="text-3xl font-cartoon font-bold text-slate-700 mt-1">{{ $statInactive }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-slate-200 border-2 border-slate-500 shadow-[2.5px_2.5px_0px_#64748B] flex items-center justify-center text-slate-600">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Informasi -->
            <div class="cartoon-card rounded-2xl p-4 border-2 border-[#003366] mb-8 bg-blue-50 text-slate-800 shadow-[3px_3px_0px_#003366] flex items-start gap-3.5">
                <span class="text-2xl shrink-0">💡</span>
                <div class="text-xs leading-relaxed font-medium">
                    <strong class="font-cartoon font-bold text-[#003366] block mb-0.5 text-sm">Informasi Kontrol Tampilan:</strong>
                    Gunakan saklar toggle <span class="bg-[#F5B800] text-[#002244] font-black px-2 py-0.5 rounded-full text-[11px] border border-[#002244]">ON / OFF</span> pada kolom <strong>Status</strong> untuk mengaktifkan atau menonaktifkan jenis surat. Jenis surat yang berstatus <strong>ON (Aktif)</strong> otomatis muncul pada formulir upload draft surat bagi peran <strong>Penyusun</strong>.
                </div>
            </div>

            <!-- Header Bar: Judul, Search & Tombol Tambah -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-cartoon font-bold text-[#003366]">Daftar Jenis Surat</h3>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">Kelola master data klasifikasi dokumen persuratan kedinasan.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                    <!-- Form Pencarian -->
                    <form action="{{ route('jenis-surat.index') }}" method="GET" class="flex w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jenis surat..." class="text-xs border-2 border-r-0 border-[#003366] rounded-l-xl w-full focus:ring-0 bg-white text-slate-900 placeholder-slate-400 px-3 py-2 font-semibold shadow-[2px_2px_0px_#003366]">
                        <button type="submit" class="btn-pop-navy font-bold px-3.5 py-2 rounded-r-xl text-xs transition cursor-pointer">
                            🔍
                        </button>
                    </form>

                    <!-- Tombol Buka Modal Tambah -->
                    <button @click="showAddModal = true" class="btn-pop-gold inline-flex items-center justify-center gap-1.5 font-cartoon font-bold py-2 px-4 rounded-xl text-xs transition whitespace-nowrap cursor-pointer">
                        <span class="text-sm leading-none">+</span> Tambah Jenis Surat
                    </button>
                </div>
            </div>

            <!-- TABEL DATA JENIS SURAT -->
            <div class="cartoon-table-card rounded-3xl bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y-2 divide-slate-200">
                        <thead class="bg-[#003366] text-white border-b-2 border-[#002244] font-cartoon text-xs">
                            <tr>
                                <th class="px-4 py-4 text-center font-bold uppercase tracking-wider text-white w-12">No</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-white">Nama Jenis Surat</th>
                                <th class="px-4 py-4 text-center font-bold uppercase tracking-wider text-white w-24">Kode</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-white">Keterangan</th>
                                <th class="px-4 py-4 text-center font-bold uppercase tracking-wider text-white w-28">Total Surat</th>
                                <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-white w-44">Status (ON/OFF)</th>
                                <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-white w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100 bg-white">
                            @forelse($jenisSurats as $index => $item)
                                <tr class="hover:bg-amber-50/30 transition">
                                    <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-700 text-center font-mono font-bold">
                                        {{ $jenisSurats->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 text-sm flex items-center">
                                            <span class="w-2.5 h-2.5 rounded-full mr-2.5 shrink-0 {{ $item->is_active ? 'bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.5)]' : 'bg-slate-400' }}"></span>
                                            <span class="text-slate-900 font-extrabold">{{ $item->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if($item->kode)
                                            <span class="bg-blue-50 text-[#003366] border-2 border-[#003366] shadow-[1.5px_1.5px_0px_#003366] px-2.5 py-1 rounded-xl text-xs font-mono font-bold">
                                                {{ $item->kode }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-700 max-w-xs truncate" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan ?: '-' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if($item->surat_keluars_count > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-[#002244] border-2 border-amber-300">
                                                {{ $item->surat_keluars_count }} surat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-300">
                                                0 surat
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <!-- Form Tombol Switch ON / OFF -->
                                        <form action="{{ route('jenis-surat.toggle', $item->id) }}" method="POST" class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <button type="submit" 
                                                    title="{{ $item->is_active ? 'Klik untuk Menonaktifkan (OFF)' : 'Klik untuk Mengaktifkan (ON)' }}"
                                                    class="relative inline-flex items-center h-7 rounded-full w-14 transition-colors focus:outline-none border-2 border-[#003366] {{ $item->is_active ? 'bg-emerald-500' : 'bg-slate-300' }} shadow-[2px_2px_0px_#003366] cursor-pointer">
                                                
                                                <!-- Bulatan Slider -->
                                                <span class="inline-block w-5 h-5 transform bg-white rounded-full transition-transform border border-[#003366] shadow-sm {{ $item->is_active ? 'translate-x-7' : 'translate-x-0.5' }}"></span>
                                                
                                                <!-- Teks Label di dalam Slider -->
                                                <span class="absolute text-[9px] font-black tracking-wider {{ $item->is_active ? 'left-1.5 text-white' : 'right-1.5 text-slate-700' }}">
                                                    {{ $item->is_active ? 'ON' : 'OFF' }}
                                                </span>
                                            </button>
                                        </form>

                                        <!-- Badge Deskriptif -->
                                        <div class="mt-1">
                                            @if($item->is_active)
                                                <span class="inline-flex items-center text-[10px] font-bold text-emerald-800">
                                                    ● Tampil di Penyusun
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-[10px] font-medium text-slate-500">
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
                                                    class="btn-aksi-amber font-bold px-2.5 py-1.5 rounded-xl transition text-xs flex items-center gap-1 cursor-pointer"
                                                    title="Edit Data Jenis Surat">
                                                ✏️ Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('jenis-surat.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis surat \'{{ $item->nama }}\'?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-aksi-red font-bold px-2.5 py-1.5 rounded-xl transition text-xs flex items-center gap-1 cursor-pointer" title="Hapus Jenis Surat">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <div class="text-4xl mb-2">📭</div>
                                        <p class="font-bold text-slate-800 text-sm">Belum ada jenis surat yang terdaftar.</p>
                                        <p class="text-xs text-slate-500 mt-1">Klik tombol "+ Tambah Jenis Surat" di atas untuk menambahkan jenis surat baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($jenisSurats->hasPages())
                    <div class="p-4 border-t-2 border-slate-200 bg-slate-50">
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
                         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                         @click="showAddModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showAddModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom cartoon-card rounded-3xl text-left overflow-hidden shadow-[8px_8px_0px_#003366] border-2 border-[#003366] bg-white text-slate-800 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full font-cartoon">
                        
                        <form action="{{ route('jenis-surat.store') }}" method="POST">
                            @csrf
                            <div class="bg-[#003366] px-6 py-4 border-b-2 border-[#002244] flex items-center justify-between text-white">
                                <h3 class="text-base font-bold text-white flex items-center" id="modal-title">
                                    <span class="mr-2">➕</span> Tambah Jenis Surat Baru
                                </h3>
                                <button type="button" @click="showAddModal = false" class="text-white/80 hover:text-white p-1 rounded-xl transition text-2xl font-bold leading-none cursor-pointer">&times;</button>
                            </div>

                            <div class="px-6 py-5 space-y-4 bg-[#F8FAFC]">
                                <!-- Input Nama Jenis Surat -->
                                <div>
                                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                                        Nama Jenis Surat <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="nama" required 
                                           placeholder="Contoh: Surat Tugas, Surat Keputusan"
                                           class="shadow-xs border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:ring-0 focus:border-[#003366] bg-white text-sm font-bold">
                                    <p class="text-[11px] text-slate-500 mt-1">Nama resmi jenis surat yang akan tampil dalam dropdown pilihan.</p>
                                </div>

                                <!-- Input Kode / Singkatan -->
                                <div>
                                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                                        Kode / Singkatan <span class="text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="text" name="kode" 
                                           placeholder="Contoh: ST, SK, SU, ND"
                                           class="shadow-xs border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:ring-0 focus:border-[#003366] bg-white text-sm font-bold uppercase">
                                    <p class="text-[11px] text-slate-500 mt-1">Kode identifikasi untuk format nomor surat jika diperlukan.</p>
                                </div>

                                <!-- Input Keterangan -->
                                <div>
                                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                                        Keterangan / Penggunaan <span class="text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea name="keterangan" rows="2" 
                                              placeholder="Jelaskan secara singkat kegunaan jenis surat ini..."
                                              class="shadow-xs border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:ring-0 focus:border-[#003366] bg-white text-sm font-medium"></textarea>
                                </div>

                                <!-- Checkbox Status Awal Aktif -->
                                <div class="bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-3.5 text-emerald-950">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-2 border-emerald-400 text-emerald-600 shadow-xs focus:ring-0 w-4 h-4">
                                        <span class="ml-2 text-xs font-extrabold text-emerald-900">Langsung Aktifkan (ON)</span>
                                    </label>
                                    <p class="text-[11px] text-emerald-800 ml-6 mt-0.5">Jika dicentang, jenis surat ini akan langsung dapat dipilih oleh penyusun saat upload surat.</p>
                                </div>
                            </div>

                            <div class="bg-white px-6 py-4 sm:flex sm:flex-row-reverse border-t-2 border-slate-200 gap-3">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl btn-pop-navy font-bold px-5 py-2.5 sm:w-auto text-xs cursor-pointer">
                                    Simpan Jenis Surat
                                </button>
                                <button type="button" @click="showAddModal = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-700 transition sm:w-auto cursor-pointer">
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
                         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                         @click="showEditModal = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showEditModal" 
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom cartoon-card rounded-3xl text-left overflow-hidden shadow-[8px_8px_0px_#003366] border-2 border-[#003366] bg-white text-slate-800 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full font-cartoon">
                        
                        <form :action="'{{ url('jenis-surat') }}/' + editData.id + '/update'" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="bg-[#003366] px-6 py-4 border-b-2 border-[#002244] flex items-center justify-between text-white">
                                <h3 class="text-base font-bold text-white flex items-center">
                                    <span class="mr-2">✏️</span> Edit Jenis Surat
                                </h3>
                                <button type="button" @click="showEditModal = false" class="text-white/80 hover:text-white p-1 rounded-xl transition text-2xl font-bold leading-none cursor-pointer">&times;</button>
                            </div>

                            <div class="px-6 py-5 space-y-4 bg-[#F8FAFC]">
                                <!-- Input Nama Jenis Surat -->
                                <div>
                                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                                        Nama Jenis Surat <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="nama" x-model="editData.nama" required 
                                           class="shadow-xs border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:ring-0 focus:border-[#003366] bg-white text-sm font-bold">
                                </div>

                                <!-- Input Kode / Singkatan -->
                                <div>
                                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                                        Kode / Singkatan <span class="text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <input type="text" name="kode" x-model="editData.kode" 
                                           class="shadow-xs border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:ring-0 focus:border-[#003366] bg-white text-sm font-bold uppercase">
                                </div>

                                <!-- Input Keterangan -->
                                <div>
                                    <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                                        Keterangan / Penggunaan <span class="text-slate-500 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea name="keterangan" rows="2" x-model="editData.keterangan"
                                              class="shadow-xs border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:ring-0 focus:border-[#003366] bg-white text-sm font-medium"></textarea>
                                </div>
                            </div>

                            <div class="bg-white px-6 py-4 sm:flex sm:flex-row-reverse border-t-2 border-slate-200 gap-3">
                                <button type="submit" class="w-full inline-flex justify-center rounded-xl btn-pop-gold font-bold px-5 py-2.5 sm:w-auto text-xs cursor-pointer">
                                    Perbarui Jenis Surat
                                </button>
                                <button type="button" @click="showEditModal = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-700 transition sm:w-auto cursor-pointer">
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
