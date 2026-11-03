<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
                    <span>👥</span> {{ in_array(Auth::user()->role, ['super_admin', 'superadmin']) ? __('Kelola Hak Akses & Pegawai') : __('Manajemen Pegawai') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Kelola data seluruh akun pegawai, NIP, peran hak akses, kontak WhatsApp, serta reset password pengguna.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="glass-pill px-3.5 py-1.5 rounded-xl hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto border border-white/15">
                <span>&larr;</span> Kembali ke Dashboard
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
                <div class="p-5 mb-6 rounded-2xl bg-gradient-to-r from-emerald-950/80 via-slate-900/90 to-teal-950/80 border-2 border-emerald-500/50 text-white shadow-2xl relative overflow-hidden backdrop-blur-md">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 relative z-10">
                        <div class="flex items-start gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                                🔑
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-extrabold text-base text-emerald-300">Kode Password Baru Berhasil Dibuat!</h4>
                                    <span class="text-[11px] bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 px-2.5 py-0.5 rounded-full font-bold">Lupa Password</span>
                                </div>
                                <p class="text-xs text-slate-300 mt-1">
                                    Password baru untuk pegawai <strong class="text-white">{{ $resetInfo['name'] }}</strong> (NIP: <span class="font-mono text-emerald-200 font-semibold">{{ $resetInfo['nip'] ?? '-' }}</span>) telah diperbarui di sistem.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                            <!-- Display Kode Password -->
                            <div class="bg-slate-950/90 border border-emerald-400/50 rounded-xl px-4 py-2 flex items-center gap-3 shadow-inner">
                                <span class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Password Baru:</span>
                                <span class="font-mono text-xl font-extrabold text-emerald-300 tracking-widest select-all">{{ $resetInfo['password'] }}</span>
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
                            <button type="button" @click="showResultModal = true" class="glass-pill text-slate-300 hover:text-white py-2 px-3 rounded-xl text-xs font-semibold border border-white/15 transition">
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
                    <h3 class="text-lg font-extrabold text-white flex items-center gap-2">
                        <span>Daftar Akun Pegawai & Hak Akses</span>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30">
                            Total: {{ $users->count() }} Pegawai
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola identitas, NIP/NIK, peran, reset password, dan kontak koordinasi pegawai.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                    <!-- Pencarian Pegawai -->
                    <form method="GET" action="{{ route('pegawai.index') }}" class="relative flex-1 sm:w-64 lg:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, WhatsApp..." class="w-full bg-slate-900/80 border border-white/15 rounded-xl pl-8 pr-7 py-2 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-inner">
                        <span class="absolute left-2.5 top-2.5 text-xs text-slate-400 pointer-events-none">🔍</span>
                        @if(request('search'))
                            <a href="{{ route('pegawai.index') }}" class="absolute right-2.5 top-2 text-xs text-slate-400 hover:text-white" title="Reset pencarian">&times;</a>
                        @endif
                    </form>

                    <!-- Tombol Download Format Excel (.xls) -->
                    <a href="{{ route('pegawai.template') }}" class="btn-aksi-emerald inline-flex items-center gap-1.5 font-bold py-2 px-3 rounded-xl shadow-md text-xs transition whitespace-nowrap">
                        <span>📥</span> Format (.xls)
                    </a>

                    <!-- Tombol Buka Panel Import -->
                    <button @click="showImport = !showImport" class="btn-aksi-indigo inline-flex items-center gap-1.5 font-bold py-2 px-3 rounded-xl shadow-md text-xs transition whitespace-nowrap">
                        <span>📤</span> Import (.xls)
                    </button>

                    <!-- Tombol Tambah Pegawai Manual -->
                    <a href="{{ route('pegawai.create') }}" class="btn-aksi-blue inline-flex items-center gap-1.5 font-bold py-2 px-3.5 rounded-xl shadow-md text-xs transition whitespace-nowrap">
                        <span>+</span> Tambah Pegawai
                    </a>
                </div>
            </div>

            <!-- Panel Box Import Data Excel (.xls) -->
            <div x-show="showImport" x-transition class="mb-6 glass-panel rounded-2xl p-6 border border-indigo-500/30 shadow-2xl bg-slate-900/90 text-slate-200" style="display: none;">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="text-base font-extrabold text-white flex items-center">
                            <span class="mr-2">📤</span> Import Data Pegawai dari Excel (.xls)
                        </h4>
                        <p class="text-xs text-indigo-300 mt-1">
                            Unggah file <strong>.xls</strong> dengan 3 kolom wajib: <strong>NAMA</strong>, <strong>NIP/NIK/NIM</strong>, dan <strong>Nomor Whatsapp</strong>. Seluruh pegawai yang diimpor akan otomatis mendapatkan hak akses sebagai <strong>Penyusun</strong>.
                        </p>
                    </div>
                    <button @click="showImport = false" class="text-slate-400 hover:text-white text-xl font-bold transition">&times;</button>
                </div>

                <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <div class="flex flex-col md:flex-row items-center gap-4">
                        <div class="flex-1 w-full">
                            <input type="file" name="file" required accept=".xls,.xlsx" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 bg-slate-900/80 border border-white/15 rounded-xl p-1.5 shadow-inner cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1.5">Format file yang didukung: <strong class="text-slate-300">.xls</strong> (Microsoft Excel) (Maks: 5MB). Pastikan menggunakan template yang telah disediakan.</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <button type="submit" class="btn-aksi-indigo font-bold py-2.5 px-5 rounded-xl shadow-md text-xs transition whitespace-nowrap">
                                🚀 Mulai Import
                            </button>
                            <a href="{{ route('pegawai.template') }}" class="text-indigo-400 hover:text-indigo-300 text-xs font-semibold underline whitespace-nowrap transition">
                                Unduh Template (.xls)
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Pegawai -->
            <div class="glass-panel rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-slate-950/70 border-b border-white/10">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Pegawai</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">NIP / NIK / NIM</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Nomor WhatsApp</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Hak Akses (Role)</th>
                                <th class="px-5 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi & Password</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-transparent">
                            @forelse ($users as $u)
                                <tr class="hover:bg-white/[0.04] transition">
                                    <td class="px-5 py-4 font-bold text-white flex items-center space-x-2">
                                        <span>{{ $u->name }}</span>
                                        @if($u->id === Auth::id())
                                            <span class="text-[10px] bg-blue-500/20 text-blue-300 border border-blue-400/30 font-bold px-2 py-0.5 rounded-full">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-300">
                                        @if(!empty($u->nip))
                                            <span class="glass-pill text-slate-200 border border-white/15 px-2.5 py-1 rounded-md text-xs font-mono">{{ $u->nip }}</span>
                                        @else
                                            <span class="text-slate-500 italic text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-300">
                                        @if(!empty($u->no_wa))
                                            @php
                                                $cleanWa = preg_replace('/[^0-9]/', '', $u->no_wa);
                                                if (str_starts_with($cleanWa, '0')) {
                                                    $cleanWa = '62' . substr($cleanWa, 1);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 font-semibold transition">
                                                <span class="mr-1">💬</span> {{ $u->no_wa }}
                                            </a>
                                        @else
                                            <span class="text-slate-500 italic text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $badgeClass = match($u->role) {
                                                'super_admin', 'superadmin' => 'bg-purple-500/20 text-purple-300 border border-purple-400/30',
                                                'penyusun' => 'bg-blue-500/20 text-blue-300 border border-blue-400/30',
                                                'sekretaris_direktur' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30',
                                                default => 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                            {{ $u->role_label }}
                                        </span>
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
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                        @if(request('search'))
                                            Tidak ditemukan data pegawai dengan kata kunci "<span class="text-white">{{ request('search') }}</span>". <a href="{{ route('pegawai.index') }}" class="text-blue-400 underline font-semibold ml-1">Tampilkan semua</a>
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
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             style="display: none;">
            
            <div class="glass-panel rounded-2xl p-6 sm:p-7 border border-teal-500/40 shadow-2xl bg-slate-900/95 text-slate-100 max-w-md w-full relative overflow-hidden"
                 @click.outside="showConfirmModal = false">
                
                <!-- Ambient Glow Background -->
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/20 border border-teal-400/40 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                        🔑
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-extrabold text-white">Konfirmasi Lupa Password</h3>
                        <p class="text-xs text-slate-400 mt-1">Buat kode password baru secara instan untuk pegawai ini.</p>
                    </div>
                    <button type="button" @click="showConfirmModal = false" class="text-slate-400 hover:text-white text-xl font-bold transition">&times;</button>
                </div>

                <!-- Info Pegawai yang Akan Direset -->
                <div class="bg-slate-950/70 border border-white/10 rounded-xl p-4 my-4 space-y-2 text-xs">
                    <div class="flex justify-between items-center pb-2 border-b border-white/5">
                        <span class="text-slate-400">Nama Pegawai:</span>
                        <span class="font-bold text-white text-right" x-text="targetUserName"></span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-white/5">
                        <span class="text-slate-400">NIP / NIK / NIM:</span>
                        <span class="font-mono text-emerald-300 font-bold text-right" x-text="targetUserNip"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Peran / Hak Akses:</span>
                        <span class="text-indigo-300 font-semibold text-right" x-text="targetUserRole"></span>
                    </div>
                </div>

                <div class="p-3 bg-teal-950/40 border border-teal-500/30 rounded-xl text-[11px] text-teal-200 flex items-start gap-2">
                    <span class="text-sm">ℹ️</span>
                    <span>Sistem akan membuat <strong>kode password baru yang unik</strong> dan menampilkannya langsung di halaman ini agar dapat segera diberikan kepada pegawai bersangkutan.</span>
                </div>

                <form :action="'{{ url('/pegawai') }}/' + targetUserId + '/reset-password'" method="POST" class="mt-6 flex items-center justify-end gap-3">
                    @csrf
                    <button type="button" @click="showConfirmModal = false" class="glass-pill px-4 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white border border-white/15 transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-aksi-teal font-extrabold px-5 py-2 rounded-xl text-xs shadow-lg transition flex items-center gap-1.5 cursor-pointer">
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
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md"
                 style="display: none;">
                
                <div class="glass-panel rounded-3xl p-6 sm:p-8 border-2 border-emerald-500/50 shadow-2xl bg-slate-900/98 text-slate-100 max-w-lg w-full relative overflow-hidden"
                     @click.outside="showResultModal = false">
                    
                    <!-- Decorative Light Beam -->
                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Header Modal -->
                    <div class="flex items-start justify-between gap-4 relative z-10 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-3xl shadow-inner">
                                🔑
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-white">Kode Password Baru</h3>
                                <p class="text-xs text-emerald-400 font-semibold">Berhasil dibuat untuk pegawai di bawah ini</p>
                            </div>
                        </div>
                        <button type="button" @click="showResultModal = false" class="text-slate-400 hover:text-white text-2xl font-bold transition">&times;</button>
                    </div>

                    <!-- Data Pegawai -->
                    <div class="bg-slate-950/70 border border-white/10 rounded-2xl p-4 my-5 space-y-2 text-xs relative z-10">
                        <div class="flex justify-between items-center pb-2 border-b border-white/5">
                            <span class="text-slate-400">Nama Pegawai:</span>
                            <span class="font-extrabold text-white text-sm text-right">{{ $res['name'] }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-white/5">
                            <span class="text-slate-400">NIP / NIK / NIM:</span>
                            <span class="font-mono text-emerald-300 font-bold text-sm text-right">{{ $res['nip'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-white/5">
                            <span class="text-slate-400">Peran Hak Akses:</span>
                            <span class="text-indigo-300 font-bold text-right">{{ $res['role'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Waktu Reset:</span>
                            <span class="text-slate-300 text-right">{{ $res['created_at'] }}</span>
                        </div>
                    </div>

                    <!-- Box Display Kode Password Baru Besar -->
                    <div class="relative z-10 my-4 text-center">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Kode Password Baru (Silakan Dicatat/Salin):</span>
                        <div class="bg-slate-950/95 border-2 border-emerald-400/60 rounded-2xl py-4 px-6 shadow-2xl relative group">
                            <span class="font-mono text-3xl sm:text-4xl font-extrabold text-emerald-300 tracking-widest select-all block py-1 drop-shadow-[0_0_12px_rgba(52,211,153,0.5)]">
                                {{ $res['password'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Salin & Kirim WhatsApp -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 my-5 relative z-10">
                        <!-- Tombol Salin -->
                        <button type="button" 
                                @click="copyPassword('{{ $res['password'] }}')" 
                                class="w-full sm:w-auto flex-1 btn-aksi-emerald font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="!copied">📋 Salin Password</span>
                            <span x-show="copied" class="text-white font-extrabold" style="display: none;">✅ Berhasil Disalin ke Clipboard!</span>
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
                            <a href="https://wa.me/{{ $cleanWa }}?text={{ $waMsg }}" target="_blank" class="w-full sm:w-auto flex-1 btn-aksi-teal font-extrabold py-3 px-5 rounded-2xl text-xs shadow-lg transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>💬</span> Kirim ke WhatsApp
                            </a>
                        @endif
                    </div>

                    <!-- Petunjuk / Keamanan -->
                    <div class="p-3 bg-amber-950/30 border border-amber-500/30 rounded-xl text-[11px] text-amber-200 flex items-start gap-2 relative z-10">
                        <span class="text-sm">⚠️</span>
                        <span>Pegawai dapat langsung masuk di halaman Login menggunakan <strong>NIP</strong> dan <strong>Password Baru</strong> di atas. Setelah jendela ini ditutup, password tetap tersimpan di akun pegawai.</span>
                    </div>

                    <!-- Tombol Tutup -->
                    <div class="mt-6 flex justify-end relative z-10">
                        <button type="button" @click="showResultModal = false" class="glass-pill px-6 py-2.5 rounded-xl text-xs font-bold text-slate-300 hover:text-white border border-white/15 transition">
                            Tutup Jendela
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>