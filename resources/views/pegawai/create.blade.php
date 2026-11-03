<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
                    <span>➕</span> {{ __('Tambah Pegawai & Hak Akses Baru') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Daftarkan akun pegawai baru beserta peran dan nomor WhatsApp aktif.</p>
            </div>
            <a href="{{ route('pegawai.index') }}" class="glass-pill px-3.5 py-1.5 rounded-xl hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto border border-white/15">
                <span>&larr;</span> Kembali ke Daftar Pegawai
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl text-slate-100">
                
                <!-- Menampilkan Error jika input tidak valid -->
                @if ($errors->any())
                    <div class="p-4 mb-6 rounded-2xl bg-rose-950/40 border border-rose-500/30 text-rose-200 shadow-lg">
                        <p class="font-bold flex items-center text-rose-400 text-sm">
                            <span class="mr-2">⛔</span> Gagal menyimpan data:
                        </p>
                        <ul class="list-disc ml-8 mt-1.5 text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm" required placeholder="Cth: Budi Santoso, S.Kom">
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">NIP / NIK / NIM <span class="text-rose-400">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}" class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm" required placeholder="Cth: 198501152010011005 / 3302... / 202301001">
                        <p class="text-[11px] text-slate-400 mt-1.5">Digunakan sebagai ID unik dan identitas login sistem.</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                        <input type="text" name="no_wa" value="{{ old('no_wa') }}" class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm" placeholder="Cth: 081234567890">
                        <p class="text-[11px] text-slate-400 mt-1.5">Nomor WhatsApp aktif untuk koordinasi notifikasi surat.</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">Password Akun (Opsional)</label>
                        <input type="password" name="password" class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white placeholder-slate-500 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm" placeholder="Biarkan kosong untuk menggunakan NIP/NIK/NIM sebagai password default">
                        <p class="text-[11px] text-blue-300 mt-1.5 font-medium">💡 Jika dikosongkan, password otomatis sama dengan NIP / NIK / NIM pegawai.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-slate-200 text-xs font-bold uppercase tracking-wider mb-2">Pilih Hak Akses (Role) <span class="text-rose-400">*</span></label>
                        <select name="role" class="shadow-inner border border-white/15 rounded-xl w-full py-2.5 px-3.5 text-white leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900/80 text-sm" required>
                            <option value="" class="bg-slate-900 text-slate-400">-- Pilih Hak Akses / Role --</option>
                            @foreach(\App\Models\User::getGroupedRoleLabels() as $groupLabel => $groupRoles)
                                <optgroup label="{{ $groupLabel }}" class="bg-slate-950 font-bold text-indigo-300">
                                    @foreach($groupRoles as $roleKey => $roleLabel)
                                        <option value="{{ $roleKey }}" {{ old('role') === $roleKey ? 'selected' : '' }} class="bg-slate-900 text-white font-normal">
                                            {{ $roleLabel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-5 border-t border-white/10 mt-8">
                        <a href="{{ route('pegawai.index') }}" class="text-slate-400 hover:text-white font-medium text-xs transition">
                            &larr; Batal & Kembali
                        </a>
                        <button type="submit" class="btn-aksi-blue inline-flex items-center gap-2 font-bold py-2.5 px-6 rounded-xl shadow-lg transition text-xs">
                            <span>💾</span> Simpan Akun Pegawai
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>