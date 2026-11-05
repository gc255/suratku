<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>📤</span> {{ __('Upload Draft Surat Baru') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Unggah berkas naskah surat dan tentukan alur verifikasi serta penandatangan akhir.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-pop-white px-3.5 py-1.5 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                <span>&larr;</span> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Notifikasi Error Validasi -->
            @if ($errors->any())
                <div class="p-4 mb-6 rounded-2xl bg-rose-50 border-2 border-rose-600 text-rose-950 shadow-[3px_3px_0px_#E11D48]">
                    <p class="font-bold flex items-center text-rose-700 text-sm">
                        <span class="mr-2">⛔</span> Terjadi kesalahan pengisian formulir:
                    </p>
                    <ul class="list-disc ml-8 mt-1.5 text-xs space-y-1 font-medium text-rose-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white text-slate-800"
                 x-data="{
                     pemeriksaAwal: '{{ old('pemeriksa_awal', '') }}',
                     tujuanPenandatangan: '{{ old('tujuan_penandatangan', 'direktur') }}'
                 }">
                
                <div class="mb-6 pb-4 border-b-2 border-slate-100">
                    <h3 class="text-lg font-cartoon font-bold text-[#003366]">Formulir Pengajuan Dokumen Draft</h3>
                    <p class="text-xs text-slate-600 mt-1 font-medium">
                        Lengkapi informasi surat, tentukan jalur verifikasi bertingkat, dan tentukan pejabat penandatangan akhir dokumen.
                    </p>
                </div>

                <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Pilihan Jenis Surat (Hanya yang Aktif/ON) -->
                    <div class="mb-5">
                        <label class="block text-[#003366] text-xs font-cartoon font-bold uppercase tracking-wider mb-2">
                            Jenis Surat <span class="text-red-500">*</span>
                        </label>
                        
                        @if($jenisSurats->isEmpty())
                            <div class="bg-amber-50 border-2 border-amber-500 text-amber-950 p-4 rounded-xl text-xs mb-2">
                                <p class="font-bold flex items-center text-amber-800">
                                    <span class="text-base mr-2">⚠️</span> Belum Ada Jenis Surat yang Aktif
                                </p>
                                <p class="mt-1 font-medium">
                                    Seluruh jenis surat saat ini sedang dinonaktifkan (OFF) oleh Super Admin. Harap hubungi Super Admin untuk mengaktifkan jenis surat sebelum mengunggah draft baru.
                                </p>
                            </div>
                            <select name="jenis_surat_id" disabled class="bg-slate-100 border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-500 cursor-not-allowed text-sm">
                                <option>-- Tidak ada jenis surat yang aktif --</option>
                            </select>
                        @else
                            <select name="jenis_surat_id" required class="border-2 border-[#003366] rounded-xl w-full py-2.5 px-3.5 text-slate-900 font-semibold leading-tight focus:outline-none focus:ring-2 focus:ring-[#F5B800]/50 bg-slate-50 text-sm">
                                <option value="" disabled {{ old('jenis_surat_id') ? '' : 'selected' }} class="text-slate-400">-- Pilih Jenis Surat --</option>
                                @foreach($jenisSurats as $js)
                                    <option value="{{ $js->id }}" {{ old('jenis_surat_id') == $js->id ? 'selected' : '' }}>
                                        {{ $js->nama }} {{ $js->kode ? "({$js->kode})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-500 mt-1.5 flex items-center font-medium">
                                <span class="text-emerald-600 font-bold mr-1.5">●</span> Menampilkan klasifikasi surat yang aktif (ON) dan siap diajukan.
                            </p>
                        @endif
                    </div>

                    <!-- 1. Pilihan Pemeriksa Pertama (Tujuan Awal) -->
                    <div class="mb-5">
                        <label class="block text-[#003366] text-xs font-cartoon font-bold uppercase tracking-wider mb-2">
                            Pemeriksa Pertama (Tujuan Awal) <span class="text-red-500">*</span>
                        </label>
                        <select name="pemeriksa_awal" 
                                x-model="pemeriksaAwal" 
                                required 
                                class="border-2 border-[#003366] rounded-xl w-full py-2.5 px-3.5 text-slate-900 font-semibold leading-tight focus:outline-none focus:ring-2 focus:ring-[#F5B800]/50 bg-slate-50 text-sm">
                            <option value="" disabled selected class="text-slate-400">-- Pilih Tujuan Pemeriksaan Pertama --</option>
                            
                            <!-- Opsi A: Langsung ke Kasubbag -->
                            <optgroup label="🏛️ Kepala Subbagian (Kasubbag)" class="text-amber-800 font-bold bg-amber-50">
                                @foreach($kasubbags as $key => $label)
                                    <option value="{{ $key }}" class="text-slate-800 font-normal bg-white">{{ $label }}</option>
                                @endforeach
                            </optgroup>

                            <!-- Opsi B: Program Studi (Kaprodi) -->
                            <optgroup label="🎓 Program Studi (Kaprodi)" class="text-blue-800 font-bold bg-blue-50">
                                @foreach($kaprodis as $key => $label)
                                    <option value="{{ $key }}" class="text-slate-800 font-normal bg-white">{{ $label }}</option>
                                @endforeach
                            </optgroup>

                            <!-- Opsi C: Ketua Unit & Komisi -->
                            <optgroup label="🏢 Ketua Unit & Komisi" class="text-emerald-800 font-bold bg-emerald-50">
                                @foreach($ketuaUnits as $key => $label)
                                    <option value="{{ $key }}" class="text-slate-800 font-normal bg-white">{{ $label }}</option>
                                @endforeach
                            </optgroup>

                            <!-- Opsi D: Kepala Laboratorium -->
                            <optgroup label="🔬 Kepala Laboratorium" class="text-purple-800 font-bold bg-purple-50">
                                @foreach($kepalaLabs as $key => $label)
                                    <option value="{{ $key }}" class="text-slate-800 font-normal bg-white">{{ $label }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1.5 flex items-center font-medium">
                            <span class="text-[#003366] font-bold mr-1.5">ℹ️</span> Tentukan pejabat pertama yang akan memverifikasi draft surat Anda (tingkat Unit/Prodi/Lab atau Kasubbag).
                        </p>
                    </div>

                    <!-- 2. Tujuan Penandatangan Dokumen (Titik Berhenti) DROPDOWN -->
                    <div class="mb-5">
                        <label class="block text-[#003366] text-xs font-cartoon font-bold uppercase tracking-wider mb-2">
                            Tujuan Penandatangan Dokumen (Titik Berhenti) <span class="text-red-500">*</span>
                        </label>
                        <select name="tujuan_penandatangan" 
                                x-model="tujuanPenandatangan" 
                                required 
                                class="border-2 border-[#003366] rounded-xl w-full py-2.5 px-3.5 text-slate-900 font-semibold leading-tight focus:outline-none focus:ring-2 focus:ring-[#F5B800]/50 bg-slate-50 text-sm">
                            <option value="direktur">Direktur (Alur Lengkap s.d. Direktur)</option>
                            <option value="wadir">Wakil Direktur (Berhenti di Wakil Direktur Terkait)</option>
                            <option value="kabag">Kepala Bagian / Kabag (Berhenti di Kepala Bagian)</option>
                            <option value="kasubbag">Kepala Subbagian / Kasubbag (Berhenti di Kasubbag Terkait)</option>
                            <option value="unit">Tingkat Unit / Prodi / Laboratorium (Berhenti di Unit Terkait)</option>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1.5 flex items-center font-medium">
                            <span class="text-[#003366] font-bold mr-1.5">ℹ️</span> Tentukan batas akhir pejabat yang menandatangani dokumen surat ini. Alur akan otomatis berhenti di pejabat tersebut untuk ditandatangani.
                        </p>
                    </div>

                    <!-- Input Judul Surat -->
                    <div class="mb-5">
                        <label class="block text-[#003366] text-xs font-cartoon font-bold uppercase tracking-wider mb-2">
                            Judul / Perihal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul') }}" 
                               class="border-2 border-[#003366] rounded-xl w-full py-2.5 px-3.5 text-slate-900 font-semibold placeholder-slate-400 leading-tight focus:outline-none focus:ring-2 focus:ring-[#F5B800]/50 bg-slate-50 text-sm" 
                               required placeholder="Contoh: Permohonan Penugasan Narasumber Workshop Digitalisasi">
                    </div>

                    <!-- Input File -->
                    <div class="mb-6">
                        <label class="block text-[#003366] text-xs font-cartoon font-bold uppercase tracking-wider mb-2">
                            File Draft Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file_draft" 
                               class="block w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-2 file:border-[#002244] file:text-xs file:font-cartoon file:font-bold file:bg-[#F5B800] file:text-[#002244] hover:file:bg-amber-400 bg-slate-50 border-2 border-[#003366] rounded-xl p-1.5 cursor-pointer font-medium" 
                               required accept=".pdf,.doc,.docx">
                        <p class="text-[11px] text-slate-500 mt-1.5 font-medium">Format file yang didukung: <strong class="text-slate-800">PDF, DOC, atau DOCX</strong> (Ukuran maksimal: 5MB).</p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-between pt-5 border-t-2 border-slate-100">
                        <a href="{{ route('dashboard') }}" class="btn-pop-white py-2 px-4 rounded-xl text-xs font-cartoon font-bold transition">
                            &larr; Batal & Kembali
                        </a>
                        <button type="submit" 
                                {{ $jenisSurats->isEmpty() ? 'disabled' : '' }}
                                class="btn-pop-gold inline-flex items-center gap-2 font-cartoon font-bold py-2.5 px-6 rounded-2xl text-xs cursor-pointer {{ $jenisSurats->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span>🚀</span> Upload Draft Surat
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
