<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>➕</span> {{ __('Tambah Pegawai & Hak Akses Baru') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Daftarkan akun pegawai baru beserta peran dan nomor WhatsApp aktif.</p>
            </div>
            <a href="{{ route('pegawai.index') }}" class="btn-pop-white px-3.5 py-1.5 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                <span>&larr;</span> Kembali ke Daftar Pegawai
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] text-slate-800 font-cartoon">
                
                <!-- Menampilkan Error jika input tidak valid -->
                @if ($errors->any())
                    <div class="p-4 mb-6 cartoon-card rounded-2xl bg-rose-100 border-2 border-rose-500 text-rose-950 shadow-[3px_3px_0px_#E11D48]">
                        <p class="font-black flex items-center text-rose-900 text-sm">
                            <span class="mr-2">⛔</span> Gagal menyimpan data:
                        </p>
                        <ul class="list-disc ml-8 mt-1.5 text-xs font-semibold space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:border-[#003366] bg-white text-sm shadow-xs font-semibold" required placeholder="Cth: Budi Santoso, S.Kom">
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">NIP / NIK / NIM <span class="text-rose-500">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}" class="border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:border-[#003366] bg-white text-sm shadow-xs font-mono font-bold" required placeholder="Cth: 198501152010011005 / 3302... / 202301001">
                        <p class="text-[11px] text-slate-500 mt-1.5 font-medium">Digunakan sebagai ID unik dan identitas login sistem.</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                        <input type="text" name="no_wa" value="{{ old('no_wa') }}" class="border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:border-[#003366] bg-white text-sm shadow-xs font-semibold" placeholder="Cth: 081234567890">
                        <p class="text-[11px] text-slate-500 mt-1.5 font-medium">Nomor WhatsApp aktif untuk koordinasi notifikasi surat.</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">Password Akun (Opsional)</label>
                        <input type="password" name="password" class="border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 placeholder-slate-400 leading-tight focus:outline-none focus:border-[#003366] bg-white text-sm shadow-xs" placeholder="Biarkan kosong untuk menggunakan NIP/NIK/NIM sebagai password default">
                        <p class="text-[11px] text-blue-900 mt-1.5 font-bold">💡 Jika dikosongkan, password otomatis sama dengan NIP / NIK / NIM pegawai.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-2">Pilih Hak Akses (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" class="border-2 border-slate-300 rounded-xl w-full py-2.5 px-3.5 text-slate-900 leading-tight focus:outline-none focus:border-[#003366] bg-white text-sm shadow-xs font-semibold cursor-pointer" required>
                            <option value="" class="text-slate-400">-- Pilih Hak Akses / Role --</option>
                            @foreach(\App\Models\User::getGroupedRoleLabels() as $groupLabel => $groupRoles)
                                <optgroup label="{{ $groupLabel }}" class="font-bold text-[#003366]">
                                    @foreach($groupRoles as $roleKey => $roleLabel)
                                        <option value="{{ $roleKey }}" {{ old('role') === $roleKey ? 'selected' : '' }} class="text-slate-800 font-medium">
                                            {{ $roleLabel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-5 border-t-2 border-slate-200 mt-8">
                        <a href="{{ route('pegawai.index') }}" class="text-slate-600 hover:text-slate-900 font-bold text-xs transition">
                            &larr; Batal & Kembali
                        </a>
                        <button type="submit" class="btn-pop-gold inline-flex items-center gap-2 font-bold py-2.5 px-6 rounded-xl text-xs cursor-pointer">
                            <span>💾</span> Simpan Akun Pegawai
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
