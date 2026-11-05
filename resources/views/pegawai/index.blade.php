<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>👥</span> {{ in_array(Auth::user()->role, ['super_admin', 'superadmin']) ? __('Kelola Hak Akses & Pegawai') : __('Manajemen Pegawai') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Kelola data seluruh akun pegawai, NIP, peran hak akses, kontak WhatsApp, serta reset password pengguna.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-pop-white px-3.5 py-1.5 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                <span>&larr;</span> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ 
        showImport: false,
        showConfirmModal: false,
        targetUserId: null,
        targetUserName: '',
        targetUserNip: '',
        targetUserRole: '',
        showResultModal: {{ session('reset_password_data') ? 'true' : 'false' }},
        copied: false,
        openResetConfirm(id, name, nip, role) {
            this.targetUserId = id;
            this.targetUserName = name;
            this.targetUserNip = nip;
            this.targetUserRole = role;
            this.showConfirmModal = true;
        },
        copyPassword(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2500);
                });
            } else {
                let textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2500);
                } catch (err) {
                    console.error('Gagal menyalin password:', err);
                }
                textArea.remove();
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Banner Khusus: Hasil Pembuatan Password Baru (Lupa Password) -->
            @if (session('reset_password_data'))
                @php $resetInfo = session('reset_password_data'); @endphp
                <div class="p-5 mb-6 cartoon-card rounded-3xl bg-emerald-50 border-2 border-emerald-600 text-slate-800 shadow-[4px_4px_0px_#059669] relative overflow-hidden font-cartoon">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 relative z-10">
                        <div class="flex items-start gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-100 border-2 border-emerald-600 flex items-center justify-center text-2xl flex-shrink-0 shadow-[2px_2px_0px_#059669]">
                                🔑
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-extrabold text-base text-emerald-950">Kode Password Baru Berhasil Dibuat!</h4>
                                    <span class="text-[11px] bg-emerald-200 border border-emerald-400 text-emerald-900 px-2.5 py-0.5 rounded-full font-black">Lupa Password</span>
                                </div>
                                <p class="text-xs text-slate-700 mt-1 font-medium">
                                    Password baru untuk pegawai <strong class="text-slate-900 font-extrabold">{{ $resetInfo['name'] }}</strong> (NIP: <span class="font-mono text-emerald-900 font-bold">{{ $resetInfo['nip'] ?? '-' }}</span>) telah diperbarui di sistem.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                            <!-- Display Kode Password -->
                            <div class="bg-white border-2 border-emerald-500 rounded-xl px-4 py-2 flex items-center gap-3 shadow-[2px_2px_0px_#059669]">
                                <span class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Password Baru:</span>
                                <span class="font-mono text-xl font-black text-emerald-900 tracking-widest select-all">{{ $resetInfo['password'] }}</span>
                            </div>

                            <!-- Tombol Salin Password -->
                            <button type="button" 
                                    @click="copyPassword('{{ $resetInfo['password'] }}')" 
                                    class="btn-aksi-emerald font-bold py-2 px-3.5 rounded-xl text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
                                <span x-show="!copied">📋 Salin Password</span>
                                <span x-show="copied" class="text-white font-extrabold" style="display: none;">✅ Tersalin!</span>
                            </button>

                            <!-- Tombol WhatsApp Jika Ada Nomor -->
                            @if(!empty($resetInfo['no_wa']))
                                @php
                                    $cleanWa = preg_replace('/[^0-9]/', '', $resetInfo['no_wa']);
                                    if (str_starts_with($cleanWa, '0')) {
                                        $cleanWa = '62' . substr($cleanWa, 1);
                                    }
                                    $waMsg = rawurlencode("Halo Bapak/Ibu " . $resetInfo['name'] . ",\n\nPassword akun SURATKU (Politeknik Pekerjaan Umum) Anda telah diatur ulang oleh Super Admin.\n\n👤 NIP / Username: " . ($resetInfo['nip'] ?? '-') . "\n🔑 Password Baru: " . $resetInfo['password'] . "\n\nSilakan langsung login ke aplikasi SURATKU. Terima kasih.");
                                @endphp
                                <a href="https://wa.me/{{ $cleanWa }}?text={{ $waMsg }}" target="_blank" class="btn-aksi-teal font-bold py-2 px-3.5 rounded-xl text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
                                    <span>💬</span> Kirim WhatsApp
                                </a>
                            @endif

                            <!-- Tombol Buka Modal Detail -->
                            <button type="button" @click="showResultModal = true" class="btn-pop-white py-2 px-3 rounded-xl text-xs font-bold transition">
                                🔍 Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notifikasi Berhasil Standard -->
            @if (session('success') && !session('reset_password_data'))
                <div class="p-4 mb-6 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 shadow-lg flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Notifikasi Gagal -->
            @if (session('error'))
                <div class="p-4 mb-6 rounded-2xl bg-rose-950/40 border border-rose-500/30 text-rose-200 shadow-lg flex items-center gap-3">
                    <span class="text-xl">⛔</span>
                    <p class="font-semibold text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Notifikasi Error Validasi -->
            @if (isset($errors) && $errors->any())
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

            <!-- Top Header & Action Buttons & Search -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-cartoon font-bold text-[#003366] flex items-center gap-2">
                        <span>Daftar Akun Pegawai & Hak Akses</span>
                        <span class="text-xs font-black px-2.5 py-0.5 rounded-full bg-[#F5B800] text-[#002244] border-2 border-[#002244]">
                            Total: {{ $users->count() }} Pegawai
                        </span>
                    </h3>
                    <p class="text-xs text-slate-600 mt-0.5 font-medium">Kelola identitas, NIP/NIK, peran, reset password, dan kontak koordinasi pegawai.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                    <!-- Pencarian Pegawai -->
                    <form method="GET" action="{{ route('pegawai.index') }}" class="relative flex-1 sm:w-64 lg:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, WhatsApp..." class="w-full bg-white border-2 border-[#003366] rounded-xl pl-8 pr-7 py-2 text-xs text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:border-[#003366] focus:ring-2 focus:ring-[#F5B800]/50 shadow-[2px_2px_0px_#003366]">
                        <span class="absolute left-2.5 top-2.5 text-xs text-slate-500 pointer-events-none">🔍</span>
                        @if(request('search'))
                            <a href="{{ route('pegawai.index') }}" class="absolute right-2.5 top-2 text-xs text-slate-500 hover:text-slate-800" title="Reset pencarian">&times;</a>
                        @endif
                    </form>

                    <!-- Tombol Download Format Excel (.xls) -->
                    <a href="{{ route('pegawai.template') }}" class="btn-pop-white inline-flex items-center gap-1.5 font-cartoon py-2 px-3 rounded-xl text-xs transition whitespace-nowrap cursor-pointer">
                        <span>📥</span> Format (.xls)
                    </a>

                    <!-- Tombol Buka Panel Import -->
                    <button @click="showImport = !showImport" class="btn-pop-navy inline-flex items-center gap-1.5 font-cartoon py-2 px-3 rounded-xl text-xs transition whitespace-nowrap cursor-pointer">
                        <span>📤</span> Import (.xls)
                    </button>

                    <!-- Tombol Tambah Pegawai Manual -->
                    <a href="{{ route('pegawai.create') }}" class="btn-pop-gold inline-flex items-center gap-1.5 font-cartoon py-2 px-3.5 rounded-xl text-xs transition whitespace-nowrap cursor-pointer">
                        <span>+</span> Tambah Pegawai
                    </a>
                </div>
            </div>

            <!-- Panel Box Import Data Excel (.xls) -->
            <div x-show="showImport" x-transition class="mb-6 cartoon-card rounded-3xl p-6 border-2 border-[#003366] shadow-[4px_4px_0px_#003366] bg-white text-slate-800 font-cartoon" style="display: none;">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="text-base font-bold text-[#003366] flex items-center">
                            <span class="mr-2">📤</span> Import Data Pegawai dari Excel (.xls)
                        </h4>
                        <p class="text-xs text-slate-600 mt-1">
                            Unggah file <strong>.xls</strong> dengan 3 kolom wajib: <strong>NAMA</strong>, <strong>NIP/NIK/NIM</strong>, dan <strong>Nomor Whatsapp</strong>. Seluruh pegawai yang diimpor akan otomatis mendapatkan hak akses sebagai <strong>Penyusun</strong>.
                        </p>
                    </div>
                    <button @click="showImport = false" class="text-slate-400 hover:text-slate-700 text-2xl font-bold transition cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <div class="flex flex-col md:flex-row items-center gap-4">
                        <div class="flex-1 w-full">
                            <input type="file" name="file" required accept=".xls,.xlsx" class="block w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-2 file:border-[#003366] file:text-xs file:font-bold file:bg-blue-100 file:text-[#003366] hover:file:bg-blue-200 bg-slate-50 border-2 border-slate-300 rounded-xl p-1.5 shadow-xs cursor-pointer">
                            <p class="text-[11px] text-slate-500 mt-1.5">Format file yang didukung: <strong class="text-slate-700">.xls</strong> (Microsoft Excel) (Maks: 5MB). Pastikan menggunakan template yang telah disediakan.</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <button type="submit" class="btn-pop-navy font-bold py-2.5 px-5 rounded-xl shadow-xs text-xs transition whitespace-nowrap cursor-pointer">
                                🚀 Mulai Import
                            </button>
                            <a href="{{ route('pegawai.template') }}" class="text-[#003366] hover:text-blue-700 text-xs font-bold underline whitespace-nowrap transition">
                                Unduh Template (.xls)
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Pegawai -->
            <div class="cartoon-table-card rounded-3xl bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y-2 divide-slate-200">
                        <thead class="bg-[#003366] text-white border-b-2 border-[#002244] font-cartoon text-xs">
                            <tr>
                                <th class="px-5 py-4 text-left font-bold uppercase tracking-wider text-white">Nama Pegawai</th>
                                <th class="px-5 py-4 text-left font-bold uppercase tracking-wider text-white">NIP / NIK / NIM</th>
                                <th class="px-5 py-4 text-left font-bold uppercase tracking-wider text-white">Nomor WhatsApp</th>
                                <th class="px-5 py-4 text-left font-bold uppercase tracking-wider text-white">Hak Akses (Role)</th>
                                <th class="px-5 py-4 text-center font-bold uppercase tracking-wider text-white">Aksi & Password</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100 bg-white">
                            @forelse ($users as $u)
                                <tr class="hover:bg-amber-50/30 transition">
                                    <td class="px-5 py-4 font-bold text-slate-900 flex items-center space-x-2">
                                        <span>{{ $u->name }}</span>
                                        @if($u->id === Auth::id())
                                            <span class="text-[10px] bg-blue-100 text-[#003366] border border-blue-300 font-bold px-2 py-0.5 rounded-full">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs font-bold text-slate-800">
                                        @if(!empty($u->nip))
                                            {{ $u->nip }}
                                        @else
                                            <span class="text-slate-400 italic font-sans font-normal">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-xs font-semibold text-slate-700">
                                        @if(!empty($u->no_wa))
                                            @php
                                                $cleanWa = preg_replace('/[^0-9]/', '', $u->no_wa);
                                                if (str_starts_with($cleanWa, '0')) {
                                                    $cleanWa = '62' . substr($cleanWa, 1);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="inline-flex items-center text-emerald-800 hover:text-emerald-950 font-bold transition">
                                                <span class="mr-1">💬</span> {{ $u->no_wa }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-xs font-bold text-slate-800">
                                        {{ $u->role_label }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center flex-wrap gap-1.5">
                                            <!-- Tombol Lupa Password -->
                                            <button type="button" 
                                                    @click="openResetConfirm({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->nip ?? '-') }}', '{{ addslashes($u->role_label) }}')"
                                                    class="btn-aksi-teal font-bold py-1.5 px-3 rounded-xl text-xs transition shadow flex items-center gap-1 cursor-pointer whitespace-nowrap"
                                                    title="Lupa password? Klik untuk membuat kode password baru">
                                                🔑 Lupa Password
                                            </button>

                                            <!-- Tombol Edit -->
                                            <a href="{{ route('pegawai.edit', $u->id) }}" class="btn-aksi-amber font-bold py-1.5 px-3 rounded-xl text-xs transition shadow flex items-center gap-1 whitespace-nowrap">
                                                ✏️ Edit
                                            </a>

                                            <!-- Tombol Hapus (Mencegah menghapus akun sendiri yang sedang login) -->
                                            @if($u->id !== Auth::id())
                                                <form action="{{ route('pegawai.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pegawai {{ $u->name }} secara permanen?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-aksi-red font-bold py-1.5 px-3 rounded-xl text-xs transition shadow flex items-center gap-1 cursor-pointer whitespace-nowrap">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-600 font-medium">
                                        @if(request('search'))
                                            Tidak ditemukan data pegawai dengan kata kunci "<span class="text-[#003366] font-bold">{{ request('search') }}</span>". <a href="{{ route('pegawai.index') }}" class="text-[#003366] underline font-bold ml-1">Tampilkan semua</a>
                                        @else
                                            Belum ada data pegawai yang terdaftar.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- MODAL 1: KONFIRMASI LUPA PASSWORD -->
        <!-- ================================================================= -->
        <div x-show="showConfirmModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs font-cartoon"
             style="display: none;">
            
            <div class="cartoon-card rounded-3xl p-6 sm:p-7 border-2 border-[#003366] shadow-[8px_8px_0px_#003366] bg-white text-slate-800 max-w-md w-full relative overflow-hidden"
                 @click.outside="showConfirmModal = false">

                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 border-2 border-teal-500 flex items-center justify-center text-2xl flex-shrink-0 shadow-xs text-teal-900">
                        🔑
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-[#003366]">Konfirmasi Lupa Password</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Buat kode password baru secara instan untuk pegawai ini.</p>
                    </div>
                    <button type="button" @click="showConfirmModal = false" class="text-slate-400 hover:text-slate-700 text-2xl font-bold transition cursor-pointer">&times;</button>
                </div>

                <!-- Info Pegawai yang Akan Direset -->
                <div class="bg-[#F8FAFC] border-2 border-slate-200 rounded-2xl p-4 my-4 space-y-2 text-xs">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                        <span class="text-slate-500">Nama Pegawai:</span>
                        <span class="font-bold text-slate-900 text-right" x-text="targetUserName"></span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                        <span class="text-slate-500">NIP / NIK / NIM:</span>
                        <span class="font-mono text-emerald-700 font-bold text-right" x-text="targetUserNip"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Peran / Hak Akses:</span>
                        <span class="text-indigo-700 font-bold text-right" x-text="targetUserRole"></span>
                    </div>
                </div>

                <div class="p-3.5 bg-teal-50 border-2 border-teal-300 rounded-2xl text-[11px] text-teal-950 flex items-start gap-2 shadow-xs">
                    <span class="text-sm">ℹ️</span>
                    <span>Sistem akan membuat <strong>kode password baru yang unik</strong> dan menampilkannya langsung di halaman ini agar dapat segera diberikan kepada pegawai bersangkutan.</span>
                </div>

                <form :action="'{{ url('/pegawai') }}/' + targetUserId + '/reset-password'" method="POST" class="mt-6 flex items-center justify-end gap-3">
                    @csrf
                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="btn-pop-navy font-bold px-5 py-2 rounded-xl text-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        🔑 Buat Password Baru
                    </button>
                </form>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- MODAL 2: TAMPILAN KODE PASSWORD BARU (POPUP KODE BARU) -->
        <!-- ================================================================= -->
        @if (session('reset_password_data'))
            @php $res = session('reset_password_data'); @endphp
            <div x-show="showResultModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs font-cartoon"
                 style="display: none;">
                
                <div class="cartoon-card rounded-3xl p-6 sm:p-8 border-2 border-[#003366] shadow-[8px_8px_0px_#003366] bg-white text-slate-800 max-w-lg w-full relative overflow-hidden"
                     @click.outside="showResultModal = false">

                    <!-- Header Modal -->
                    <div class="flex items-start justify-between gap-4 pb-4 border-b-2 border-slate-200">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 border-2 border-emerald-500 flex items-center justify-center text-3xl shadow-xs text-emerald-900">
                                🔑
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-[#003366]">Kode Password Baru</h3>
                                <p class="text-xs text-emerald-700 font-bold">Berhasil dibuat untuk pegawai di bawah ini</p>
                            </div>
                        </div>
                        <button type="button" @click="showResultModal = false" class="text-slate-400 hover:text-slate-700 text-2xl font-bold transition cursor-pointer">&times;</button>
                    </div>

                    <!-- Data Pegawai -->
                    <div class="bg-[#F8FAFC] border-2 border-slate-200 rounded-2xl p-4 my-5 space-y-2 text-xs">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                            <span class="text-slate-500">Nama Pegawai:</span>
                            <span class="font-extrabold text-slate-900 text-sm text-right">{{ $res['name'] }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                            <span class="text-slate-500">NIP / NIK / NIM:</span>
                            <span class="font-mono text-emerald-700 font-bold text-sm text-right">{{ $res['nip'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                            <span class="text-slate-500">Peran Hak Akses:</span>
                            <span class="text-indigo-700 font-bold text-right">{{ $res['role'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Waktu Reset:</span>
                            <span class="text-slate-700 font-medium text-right">{{ $res['created_at'] }}</span>
                        </div>
                    </div>

                    <!-- Box Display Kode Password Baru Besar -->
                    <div class="my-4 text-center">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest block mb-2">Kode Password Baru (Silakan Dicatat/Salin):</span>
                        <div class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl py-4 px-6 shadow-[3px_3px_0px_#059669] relative group">
                            <span class="font-mono text-3xl sm:text-4xl font-black text-emerald-900 tracking-widest select-all block py-1">
                                {{ $res['password'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Salin & Kirim WhatsApp -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 my-5">
                        <!-- Tombol Salin -->
                        <button type="button" 
                                @click="copyPassword('{{ $res['password'] }}')" 
                                class="w-full sm:w-auto flex-1 btn-pop-gold font-bold py-3 px-5 rounded-2xl text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="!copied">📋 Salin Password</span>
                            <span x-show="copied" class="text-[#002244] font-extrabold" style="display: none;">✅ Berhasil Disalin ke Clipboard!</span>
                        </button>

                        <!-- Tombol WhatsApp Jika Ada Nomor -->
                        @if(!empty($res['no_wa']))
                            @php
                                $cleanWa = preg_replace('/[^0-9]/', '', $res['no_wa']);
                                if (str_starts_with($cleanWa, '0')) {
                                    $cleanWa = '62' . substr($cleanWa, 1);
                                }
                                $waMsg = rawurlencode("Halo Bapak/Ibu " . $res['name'] . ",\n\nPassword akun SURATKU (Politeknik Pekerjaan Umum) Anda telah diatur ulang oleh Super Admin.\n\n👤 NIP / Username: " . ($res['nip'] ?? '-') . "\n🔑 Password Baru: " . $res['password'] . "\n\nSilakan langsung login ke aplikasi SURATKU. Terima kasih.");
                            @endphp
                            <a href="https://wa.me/{{ $cleanWa }}?text={{ $waMsg }}" target="_blank" class="w-full sm:w-auto flex-1 btn-pop-navy font-bold py-3 px-5 rounded-2xl text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>💬</span> Kirim ke WhatsApp
                            </a>
                        @endif
                    </div>

                    <!-- Petunjuk / Keamanan -->
                    <div class="p-3.5 bg-amber-50 border-2 border-amber-300 rounded-2xl text-[11px] text-amber-950 flex items-start gap-2 shadow-xs">
                        <span class="text-sm">⚠️</span>
                        <span>Pegawai dapat langsung masuk di halaman Login menggunakan <strong>NIP</strong> dan <strong>Password Baru</strong> di atas. Setelah jendela ini ditutup, password tetap tersimpan di akun pegawai.</span>
                    </div>

                    <!-- Tombol Tutup -->
                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="showResultModal = false" class="px-6 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border-2 border-slate-300 transition cursor-pointer">
                            Tutup Jendela
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>