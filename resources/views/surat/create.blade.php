<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
                    <span>📤</span> {{ __('Upload Draft Surat Baru') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Unggah berkas naskah surat dan tentukan alur verifikasi serta penandatangan akhir.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="glass-pill px-3.5 py-1.5 rounded-xl hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto border border-white/15">
                <span>&larr;</span> Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Notifikasi Error Validasi -->
            @if ($errors->any())
                <div class="p-4 mb-6 rounded-2xl bg-rose-950/40 border border-rose-500/30 text-rose-200 shadow-lg">
                    <p class="font-bold flex items-center text-rose-400 text-sm">
                        <span class="mr-2">⛔</span> Terjadi kesalahan pengisian formulir:
                    </p>
                    <ul class="list-disc ml-8 mt-1.5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl text-slate-100"
                 x-data="{
                     pemeriksaAwal: '{{ old('pemeriksa_awal', '') }}',
                     tujuanPenandatangan: '{{ old('tujuan_penandatangan', 'direktur') }}'
                 }">
                
                <div class="mb-6 pb-4 border-b border-white/10">
                    <h3 class="text-lg font-extrabold text-white">Formulir Pengajuan Dokumen Draft</h3>
                    <p class="text-xs text-slate-400 mt-1">
                        Lengkapi informasi surat, tentukan jalur verifikasi bertingkat, dan tentukan pejabat penandatangan akhir dokumen.
                    </p>
                </div>

                <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Pilihan Jenis Surat (Hanya yang Aktif/ON) -->
                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">
                            Jenis Surat <span class="text-rose-400">*</span>
                        </label>
                        
                        @if($jenisSurats->isEmpty())
                            <div class="bg-amber-950/40 border border-amber-500/30 text-amber-200 p-4 rounded-xl text-xs mb-2">
                                <p class="font-bold flex items-center text-amber-300">
                                    <span class="text-base mr-2">⚠️</span> Belum Ada Jenis Surat yang Aktif
                                </p>
                                <p class="mt-1">
                                    Seluruh jenis surat saat ini sedang dinonaktifkan (OFF) oleh Super Admin. Harap hubungi Super Admin untuk mengaktifkan jenis surat sebelum mengunggah draft baru.
                                </p>
                            </div>
                            <select name="jenis_surat_id" disabled class="bg-slate-900/60 border border-white/10 rounded-xl w-full py-2.5 px-3.5 text-slate-500 cursor-not-allowed text-sm">
                                <option>-- Tidak ada jenis surat yang aktif --</option>
                            </select>
                        @else
                            <select name="jenis_surat_id" required class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm">
                                <option value="" disabled {{ old('jenis_surat_id') ? '' : 'selected' }} class="bg-slate-900 text-slate-400">-- Pilih Jenis Surat --</option>
                                @foreach($jenisSurats as $js)
                                    <option value="{{ $js->id }}" {{ old('jenis_surat_id') == $js->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                        {{ $js->nama }} {{ $js->kode ? "({$js->kode})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center">
                                <span class="text-emerald-400 font-bold mr-1.5">●</span> Menampilkan klasifikasi surat yang aktif (ON) dan siap diajukan.
                            </p>
                        @endif
                    </div>

                    <!-- 1. Pilihan Pemeriksa Pertama (Tujuan Awal) -->
                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">
                            Pemeriksa Pertama (Tujuan Awal) <span class="text-rose-400">*</span>
                        </label>
                        <select name="pemeriksa_awal" 
                                x-model="pemeriksaAwal" 
                                required 
                                class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm">
                            <option value="" disabled selected class="bg-slate-900 text-slate-400">-- Pilih Tujuan Pemeriksaan Pertama --</option>
                            
                            <!-- Opsi A: Langsung ke Kasubbag -->
                            <optgroup label="🏛️ Kepala Subbagian (Kasubbag)" class="bg-slate-900 text-amber-300 font-bold">
                                @foreach($kasubbags as $key => $label)
                                    <option value="{{ $key }}" class="bg-slate-900 text-white font-normal">{{ $label }}</option>
                                @endforeach
                            </optgroup>

                            <!-- Opsi B: Program Studi (Kaprodi) -->
                            <optgroup label="🎓 Program Studi (Kaprodi)" class="bg-slate-900 text-blue-300 font-bold">
                                @foreach($kaprodis as $key => $label)
                                    <option value="{{ $key }}" class="bg-slate-900 text-white font-normal">{{ $label }}</option>
                                @endforeach
                            </optgroup>

                            <!-- Opsi C: Ketua Unit & Komisi -->
                            <optgroup label="🏢 Ketua Unit & Komisi" class="bg-slate-900 text-emerald-300 font-bold">
                                @foreach($ketuaUnits as $key => $label)
                                    <option value="{{ $key }}" class="bg-slate-900 text-white font-normal">{{ $label }}</option>
                                @endforeach
                            </optgroup>

                            <!-- Opsi D: Kepala Laboratorium -->
                            <optgroup label="🔬 Kepala Laboratorium" class="bg-slate-900 text-purple-300 font-bold">
                                @foreach($kepalaLabs as $key => $label)
                                    <option value="{{ $key }}" class="bg-slate-900 text-white font-normal">{{ $label }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1.5 flex items-center">
                            <span class="text-blue-400 font-bold mr-1.5">ℹ️</span> Tentukan pejabat pertama yang akan memverifikasi draft surat Anda (tingkat Unit/Prodi/Lab atau Kasubbag).
                        </p>
                    </div>

                    <!-- 2. Tujuan Penandatangan Dokumen (Titik Berhenti) DROPDOWN -->
                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">
                            Tujuan Penandatangan Dokumen (Titik Berhenti) <span class="text-rose-400">*</span>
                        </label>
                        <select name="tujuan_penandatangan" 
                                x-model="tujuanPenandatangan" 
                                required 
                                class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm">
                            <option value="direktur" class="bg-slate-900 text-white">Direktur (Alur Lengkap s.d. Direktur)</option>
                            <option value="wadir" class="bg-slate-900 text-white">Wakil Direktur (Berhenti di Wakil Direktur Terkait)</option>
                            <option value="kabag" class="bg-slate-900 text-white">Kepala Bagian / Kabag (Berhenti di Kepala Bagian)</option>
                            <option value="kasubbag" class="bg-slate-900 text-white">Kepala Subbagian / Kasubbag (Berhenti di Kasubbag Terkait)</option>
                            <option value="unit" class="bg-slate-900 text-white">Tingkat Unit / Prodi / Laboratorium (Berhenti di Unit Terkait)</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1.5 flex items-center">
                            <span class="text-blue-400 font-bold mr-1.5">ℹ️</span> Tentukan batas akhir pejabat yang menandatangani dokumen surat ini. Alur akan otomatis berhenti di pejabat tersebut untuk ditandatangani.
                        </p>
                    </div>

                    <!-- Input Judul Surat -->
                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">
                            Judul / Perihal Surat <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul') }}" 
                               class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm" 
                               required placeholder="Contoh: Permohonan Penugasan Narasumber Workshop Digitalisasi">
                    </div>

                    <!-- Input File -->
                    <div class="mb-6">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">
                            File Draft Dokumen <span class="text-rose-400">*</span>
                        </label>
                        <input type="file" name="file_draft" 
                               class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-500 bg-slate-900/80 border border-white/15 rounded-xl p-1.5 shadow-inner cursor-pointer" 
                               required accept=".pdf,.doc,.docx">
                        <p class="text-[11px] text-slate-400 mt-1.5">Format file yang didukung: <strong class="text-slate-300">PDF, DOC, atau DOCX</strong> (Ukuran maksimal: 5MB).</p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-between pt-5 border-t border-white/10">
                        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white font-medium text-xs transition">
                            &larr; Batal & Kembali
                        </a>
                        <button type="submit" 
                                {{ $jenisSurats->isEmpty() ? 'disabled' : '' }}
                                class="btn-aksi-blue inline-flex items-center gap-2 font-bold py-2.5 px-6 rounded-xl shadow-lg transition text-xs {{ $jenisSurats->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span>🚀</span> Upload Draft Surat
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
