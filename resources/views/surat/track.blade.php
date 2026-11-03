<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
                    <span>📍</span> {{ __('Riwayat Perjalanan & Tracking Surat') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Pantau seluruh alur disposisi, verifikasi paraf, dan riwayat revisi surat secara real-time.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="glass-pill px-3.5 py-1.5 rounded-xl hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto border border-white/15">
                <span>&larr;</span> Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl text-slate-100">
                
                <!-- Header Detail Dokumen Surat -->
                <div class="mb-8 border-b border-white/10 pb-6">
                    <div class="flex items-center gap-2 text-xs font-semibold text-blue-400 mb-2">
                        <span>📄</span>
                        <span>INFORMASI DOKUMEN</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $surat->judul }}</h3>
                    
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        @if($surat->nomor_surat)
                            <span class="glass-pill text-emerald-300 border border-emerald-400/30 px-3 py-1 rounded-full text-xs font-mono font-bold" title="Nomor Surat Resmi">
                                📄 {{ $surat->nomor_surat }}
                            </span>
                        @endif
                        @if($surat->jenisSurat)
                            <span class="glass-pill text-blue-300 border border-blue-400/30 px-3 py-1 rounded-full text-xs font-bold">
                                📑 {{ $surat->jenisSurat->nama }}
                            </span>
                        @endif
                        @if($surat->tujuan_unit)
                            <span class="glass-pill text-emerald-300 border border-emerald-400/30 px-3 py-1 rounded-full text-xs font-bold" title="Pemeriksa Unit/Prodi/Lab yang dituju">
                                🏢 {{ $surat->tujuan_unit_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_kasubbag)
                            <span class="glass-pill text-amber-300 border border-amber-400/30 px-3 py-1 rounded-full text-xs font-bold">
                                📌 {{ $surat->tujuan_kasubbag_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_wadir)
                            <span class="glass-pill text-purple-300 border border-purple-400/30 px-3 py-1 rounded-full text-xs font-bold">
                                🎯 {{ $surat->tujuan_wadir_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_penandatangan)
                            <span class="glass-pill text-cyan-300 border border-cyan-400/30 px-3 py-1 rounded-full text-xs font-bold" title="Penandatangan Dokumen">
                                ✍️ TTD: {{ $surat->tujuan_penandatangan_label }}
                            </span>
                        @endif
                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-xs {{ $surat->status_badge_class }}">
                            Status: {{ $surat->status_label }}
                        </span>
                    </div>

                    <!-- Tombol Lihat Dokumen -->
                    <div class="mt-5 flex flex-wrap gap-2.5">
                        @if($surat->file_draft)
                            <a href="{{ asset('storage/' . $surat->file_draft) }}" target="_blank" class="btn-aksi-blue inline-flex items-center gap-2 text-xs font-bold py-2 px-4 rounded-xl shadow-md transition">
                                <span>👁️</span> Lihat File Draft (Awal)
                            </a>
                        @endif
                        @if($surat->file_final)
                            <a href="{{ asset('storage/' . $surat->file_final) }}" target="_blank" class="btn-aksi-emerald inline-flex items-center gap-2 text-xs font-bold py-2 px-4 rounded-xl shadow-md transition">
                                <span>📥</span> Unduh File Final (TTD)
                            </a>
                        @endif
                    </div>
                </div>

                @php
                    $isStatusDikembalikan = str_contains($surat->status, 'DIKEMBALIKAN') || str_contains(strtolower($surat->status_label), 'kembali') || str_contains(strtolower($surat->status_label), 'revisi');
                    $catatanTerakhir = $surat->catatan_terakhir;
                    if (!$catatanTerakhir) {
                        $lastKembali = $trackings->filter(function($t) {
                            $aksi = strtolower($t->aksi);
                            return str_contains($aksi, 'kembali') || str_contains($aksi, 'tolak') || (str_contains($aksi, 'revisi') && !str_contains($aksi, 'draft direvisi'));
                        })->last();
                        if ($lastKembali) {
                            $catatanTerakhir = $lastKembali->catatan;
                        }
                    }
                @endphp

                <!-- Alert Highlight jika status surat sedang Dikembalikan / Perlu Revisi -->
                @if($isStatusDikembalikan && $catatanTerakhir)
                    <div class="mb-8 p-5 rounded-2xl bg-rose-950/40 border border-rose-500/30 backdrop-blur-md shadow-lg shadow-rose-950/30">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-xl shrink-0 shadow-xs">
                                ⚠️
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h4 class="text-xs font-extrabold text-rose-300 uppercase tracking-wider">
                                        Surat Perlu Revisi / Dikembalikan
                                    </h4>
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-400/30">
                                        {{ $surat->status_label }}
                                    </span>
                                </div>
                                <p class="text-xs text-rose-200/80 mt-0.5">Surat ini memerlukan perbaikan sebelum dapat diproses ke tingkat selanjutnya.</p>
                                
                                <div class="mt-3 p-3.5 bg-slate-900/80 border border-rose-500/30 rounded-xl shadow-inner">
                                    <span class="text-xs font-bold text-rose-400 flex items-center gap-1.5 mb-1.5">
                                        <span>💬</span> Catatan / Instruksi Revisi Terakhir:
                                    </span>
                                    <p class="text-sm text-slate-100 font-medium whitespace-pre-line leading-relaxed">{{ $catatanTerakhir }}</p>
                                </div>

                                @if($surat->lampiran_revisi_terakhir)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $surat->lampiran_revisi_terakhir) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3.5 py-2 glass-pill hover:bg-white/10 text-rose-300 hover:text-white text-xs font-bold rounded-xl border border-rose-500/30 transition">
                                            <span>📎</span> Unduh File Koreksi / Lampiran Revisi
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Visualisasi Timeline -->
                <div class="mb-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-2 mb-6">
                        <span>⏱️</span>
                        <span>Log Jejak Audit & Kronologi Perjalanan</span>
                    </h4>

                    <div class="relative border-l-2 border-blue-500/40 ml-4 space-y-8 pb-4">
                        @forelse($trackings as $track)
                            @php
                                $aksiLower = strtolower($track->aksi);
                                $isItemKembali = str_contains($aksiLower, 'kembali') || str_contains($aksiLower, 'tolak') || (str_contains($aksiLower, 'revisi') && !str_contains($aksiLower, 'draft direvisi'));
                                $isCurrent = $loop->last;
                            @endphp
                            <div class="pl-8 relative">
                                <!-- Titik Bulat Timeline -->
                                @if($isCurrent)
                                    <!-- Titik Saat Ini (Posisi Terakhir) Berkedip / Radar Ping -->
                                    <div class="absolute -left-[13px] top-0.5 flex items-center justify-center w-6 h-6">
                                        <!-- Efek Radar Ping Berkedip -->
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-80 {{ $isItemKembali ? 'bg-rose-400' : 'bg-emerald-400' }}"></span>
                                        <!-- Lingkaran Inti Bercahaya -->
                                        <span class="relative inline-flex w-5 h-5 rounded-full border-2 border-slate-900 shadow-lg flex items-center justify-center text-white {{ $isItemKembali ? 'bg-rose-500 ring-4 ring-rose-500/40 shadow-[0_0_15px_rgba(244,63,94,0.9)]' : 'bg-emerald-500 ring-4 ring-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.9)]' }}">
                                            @if($isItemKembali)
                                                <span class="text-[10px] font-black leading-none">!</span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <!-- Titik Riwayat Sebelumnya -->
                                    <div class="absolute -left-[9px] top-1.5">
                                        @if($isItemKembali)
                                            <div class="w-4 h-4 bg-rose-600 rounded-full border-2 border-[#090e1a] shadow-sm flex items-center justify-center text-white">
                                                <span class="text-[8px] font-black leading-none">!</span>
                                            </div>
                                        @else
                                            <div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-[#090e1a] shadow-sm"></div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Header Log -->
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h5 class="font-bold text-base {{ $isItemKembali ? 'text-rose-400' : 'text-white' }}">{{ $surat->formatTrackingAksi($track, $isCurrent) }}</h5>
                                        
                                        @if($isCurrent)
                                            <!-- Badge Khusus Titik Posisi Saat Ini (Berkedip) -->
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wider shadow-sm {{ $isItemKembali ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-[0_0_10px_rgba(244,63,94,0.3)]' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-[0_0_10px_rgba(16,185,129,0.3)]' }}">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-80 {{ $isItemKembali ? 'bg-rose-300' : 'bg-emerald-300' }}"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $isItemKembali ? 'bg-rose-400' : 'bg-emerald-400' }}"></span>
                                                </span>
                                                <span>POSISI SAAT INI</span>
                                            </span>
                                        @elseif($isItemKembali)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-400/30">
                                                ↩️ DIKEMBALIKAN / REVISI
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400">{{ $track->created_at->translatedFormat('d M Y - H:i') }} WIB</p>
                                </div>

                                <p class="text-xs text-slate-300 mt-1">
                                    Diproses oleh: <strong class="text-white">{{ $track->aktor->name ?? 'User' }}</strong> 
                                    <span class="text-blue-300 font-mono">({{ strtoupper($track->aktor->role ?? '-') }})</span>
                                </p>

                                <!-- Catatan Log -->
                                @if($track->catatan)
                                    @if($isItemKembali)
                                        <div class="mt-3 p-4 rounded-xl bg-rose-950/30 border border-rose-500/30 shadow-xs">
                                            <div class="flex items-center text-xs font-bold text-rose-400 uppercase tracking-wider mb-2">
                                                <span class="text-sm mr-1.5">💬</span>
                                                <span>Catatan Revisi / Alasan Pengembalian:</span>
                                            </div>
                                            <div class="p-3 bg-slate-900/80 border border-white/10 rounded-lg text-xs sm:text-sm text-slate-200 font-medium whitespace-pre-line leading-relaxed shadow-inner">
                                                {{ $track->catatan }}
                                            </div>
                                            @if($track->file_lampiran)
                                                <div class="mt-3 pt-2.5 border-t border-rose-500/20">
                                                    <a href="{{ asset('storage/' . $track->file_lampiran) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 glass-pill hover:bg-white/10 text-rose-300 hover:text-white text-xs font-bold rounded-lg border border-rose-500/30 transition">
                                                        <span>📎</span> Unduh File Koreksi / Catatan Revisi
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-2.5 p-3.5 rounded-xl bg-slate-800/60 border border-white/10 text-xs text-slate-300">
                                            <span class="font-bold text-blue-400">Catatan:</span>
                                            <span class="text-slate-200 ml-1 italic">{{ $track->catatan }}</span>
                                        </div>
                                        @if($track->file_lampiran)
                                            <div class="mt-2.5">
                                                <a href="{{ asset('storage/' . $track->file_lampiran) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 glass-pill hover:bg-white/10 text-blue-300 hover:text-white text-xs font-bold rounded-lg border border-white/15 transition">
                                                    <span>📎</span> Unduh File Lampiran
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @elseif($track->file_lampiran)
                                    <div class="mt-2.5">
                                        <a href="{{ asset('storage/' . $track->file_lampiran) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 glass-pill hover:bg-white/10 text-rose-300 hover:text-white text-xs font-bold rounded-lg border border-rose-500/30 transition">
                                            <span>📎</span> Unduh File Koreksi / Catatan Revisi
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm italic pl-6">Belum ada riwayat tracking untuk surat ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>