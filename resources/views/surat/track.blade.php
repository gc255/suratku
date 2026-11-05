<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>📍</span> {{ __('Riwayat Perjalanan & Tracking Surat') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Pantau seluruh alur disposisi, verifikasi paraf, dan riwayat revisi surat secara real-time.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-pop-white px-3.5 py-1.5 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                <span>&larr;</span> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white text-slate-800">
                
                <!-- Header Detail Dokumen Surat -->
                <div class="mb-8 border-b-2 border-slate-100 pb-6">
                    <div class="flex items-center gap-2 text-xs font-cartoon font-bold text-[#003366] mb-2">
                        <span>📄</span>
                        <span>INFORMASI DOKUMEN</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-cartoon font-bold text-[#003366] tracking-tight leading-snug">{{ $surat->judul }}</h3>
                    
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        @if($surat->isRahasia())
                            <span class="inline-flex items-center gap-1.5 font-bold text-xs text-rose-900 bg-rose-100 border-2 border-rose-500 shadow-xs px-3 py-1 rounded-xl">
                                🔒 DOKUMEN RAHASIA
                            </span>
                        @endif
                        @if($surat->isSpt())
                            <span class="inline-flex items-center gap-1.5 font-bold text-xs text-amber-950 bg-amber-100 border-2 border-amber-500 shadow-xs px-3 py-1 rounded-xl">
                                💼 SPT
                            </span>
                        @endif
                        @if($surat->nomor_surat)
                            <span class="inline-flex items-center gap-1.5 font-mono font-bold text-xs text-[#003366] bg-sky-50 border-2 border-[#003366] shadow-[2px_2px_0px_#003366] px-3 py-1 rounded-xl" title="Nomor Surat Resmi">
                                📄 {{ $surat->nomor_surat }}
                            </span>
                        @endif
                        @if($surat->jenisSurat)
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-[#003366] bg-blue-100 border border-blue-300 px-3 py-1 rounded-xl">
                                📑 {{ $surat->jenisSurat->nama }}
                            </span>
                        @endif
                        @if($surat->isRahasia() && $surat->tujuan_rahasia_label)
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-rose-800 bg-rose-50 border border-rose-300 px-3 py-1 rounded-xl" title="Tujuan Surat Rahasia">
                                🎯 Ditujukan ke: {{ $surat->tujuan_rahasia_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_unit)
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-emerald-800 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-xl" title="Pemeriksa Unit/Prodi/Lab yang dituju">
                                🏢 {{ $surat->tujuan_unit_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_ppk)
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-amber-900 bg-amber-100 border border-amber-300 px-3 py-1 rounded-xl" title="PPK yang dituju">
                                💼 {{ $surat->tujuan_ppk_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_kasubbag)
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-amber-900 bg-amber-100 border border-amber-300 px-3 py-1 rounded-xl">
                                📌 {{ $surat->tujuan_kasubbag_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_wadir)
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-purple-900 bg-purple-100 border border-purple-300 px-3 py-1 rounded-xl">
                                🎯 {{ $surat->tujuan_wadir_label }}
                            </span>
                        @endif
                        @if($surat->tujuan_penandatangan && !$surat->isRahasia())
                            <span class="inline-flex items-center gap-1 font-bold text-xs text-cyan-900 bg-cyan-100 border border-cyan-300 px-3 py-1 rounded-xl" title="Penandatangan Dokumen">
                                ✍️ TTD: {{ $surat->tujuan_penandatangan_label }}
                            </span>
                        @endif
                    </div>

                    @if($surat->isRahasia())
                        <div class="mt-4 p-4 rounded-2xl bg-rose-50 border-2 border-rose-500 text-rose-950 shadow-[3px_3px_0px_#BE123C]">
                            <div class="flex items-center gap-2 font-cartoon font-bold text-xs sm:text-sm text-rose-800">
                                <span>🔒</span>
                                <span>STATUS DOKUMEN RAHASIA (TERTUTUP)</span>
                            </div>
                            <p class="text-[11px] text-rose-900 mt-1 font-medium leading-relaxed">
                                Dokumen ini berklasifikasi rahasia. Akses membaca detail dan berkas dibatasi khusus untuk pembuat draf dan pejabat yang dituju.
                            </p>
                        </div>
                    @endif

                    <!-- MODEL 2 BALON KOMIK STATUS DI HALAMAN TRACKING -->
                    @php
                        $bubble = $surat->speech_bubble_data;
                    @endphp
                    <div class="mt-4">
                        <div class="speech-bubble rounded-2xl px-4 py-2.5 {{ $bubble['bg_class'] }} inline-block max-w-md shadow-[3px_3px_0px_#003366]">
                            <div class="flex items-center gap-1.5 font-cartoon font-bold text-xs sm:text-sm">
                                <span class="text-base">{{ $bubble['emoji'] }}</span>
                                <span>"{{ $bubble['dialog'] }}"</span>
                            </div>
                            <p class="text-[11px] opacity-90 font-medium leading-tight mt-1">
                                {{ $bubble['subtext'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Lihat Dokumen -->
                    @php
                        $userAuth = Auth::user();
                        $isSuperAdmin = $userAuth && in_array($userAuth->role, ['super_admin', 'superadmin']);
                        $isRahasiaLockedForAdmin = $surat->isRahasia() && $isSuperAdmin;
                    @endphp

                    @if(!$isRahasiaLockedForAdmin)
                        <div class="mt-5 flex flex-wrap gap-2.5">
                            @if($surat->file_draft)
                                <a href="{{ route('surat.viewDraftFile', $surat->id) }}" target="_blank" class="btn-pop-navy inline-flex items-center gap-2 text-xs font-cartoon py-2 px-4 rounded-xl transition">
                                    <span>👁️</span> Lihat File Draft (Awal)
                                </a>
                            @endif
                            @if($surat->file_final)
                                <a href="{{ route('surat.viewFinalFile', $surat->id) }}" target="_blank" class="btn-pop-gold inline-flex items-center gap-2 text-xs font-cartoon py-2 px-4 rounded-xl transition">
                                    <span>📥</span> Unduh File Final (TTD)
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="mt-5 p-3.5 rounded-2xl bg-amber-50 border-2 border-[#003366] text-[#003366] flex items-center gap-2.5 text-xs font-cartoon font-bold shadow-[2px_2px_0px_#003366]">
                            <span class="text-lg">🔒</span>
                            <span>Akses Berkas Rahasia Dikunci: Super Admin hanya berhak memantau log tracking, mengelola nomor surat, dan menghapus dokumen jika diperlukan.</span>
                        </div>
                    @endif
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
                    <div class="mb-8 p-5 rounded-3xl bg-rose-50 border-2 border-rose-600 shadow-[3px_3px_0px_#E11D48]">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-rose-200 border-2 border-rose-600 flex items-center justify-center text-rose-800 text-xl shrink-0">
                                ⚠️
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h4 class="text-xs font-cartoon font-bold text-rose-800 uppercase tracking-wider">
                                        Surat Perlu Revisi / Dikembalikan
                                    </h4>
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 border border-rose-400">
                                        {{ $surat->status_label }}
                                    </span>
                                </div>
                                <p class="text-xs text-rose-800 mt-0.5 font-medium">Surat ini memerlukan perbaikan sebelum dapat diproses ke tingkat selanjutnya.</p>
                                
                                <div class="mt-3 p-3.5 bg-white border-2 border-rose-300 rounded-2xl shadow-xs">
                                    <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5 mb-1.5">
                                        <span>💬</span> Catatan / Instruksi Revisi Terakhir:
                                    </span>
                                    <p class="text-sm text-slate-800 font-semibold whitespace-pre-line leading-relaxed">{{ $catatanTerakhir }}</p>
                                </div>

                                @if($surat->lampiran_revisi_terakhir && !$isRahasiaLockedForAdmin)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $surat->lampiran_revisi_terakhir) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border border-rose-400 transition">
                                            <span>📎</span> Unduh File Koreksi / Lampiran Revisi
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($surat->status === \App\Models\SuratKeluar::STATUS_DITOLAK)
                    <div class="mb-8 p-5 rounded-3xl bg-rose-50 border-2 border-rose-600 shadow-[3px_3px_0px_#be123c]">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-rose-200 border-2 border-rose-600 flex items-center justify-center text-rose-800 text-xl shrink-0">
                                ❌
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h4 class="text-xs font-cartoon font-bold text-rose-900 uppercase tracking-wider">
                                        Dokumen Surat Telah Ditolak
                                    </h4>
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 border border-rose-400">
                                        {{ $surat->status_label }}
                                    </span>
                                </div>
                                <p class="text-xs text-rose-800 mt-0.5 font-medium">Dokumen ini telah ditolak oleh pemeriksa dan proses alur surat telah dihentikan secara permanen.</p>
                                
                                @if($catatanTerakhir)
                                    <div class="mt-3 p-3.5 bg-white border-2 border-rose-300 rounded-2xl shadow-xs">
                                        <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5 mb-1.5">
                                            <span>💬</span> Alasan Penolakan:
                                        </span>
                                        <p class="text-sm text-slate-800 font-semibold whitespace-pre-line leading-relaxed">{{ $catatanTerakhir }}</p>
                                    </div>
                                @endif

                                @if($surat->lampiran_revisi_terakhir && !$isRahasiaLockedForAdmin)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $surat->lampiran_revisi_terakhir) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border border-rose-400 transition">
                                            <span>📎</span> Unduh Berkas Catatan Penolakan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($surat->status === \App\Models\SuratKeluar::STATUS_DIBATALKAN)
                    <div class="mb-8 p-5 rounded-3xl bg-slate-100 border-2 border-slate-400 shadow-[3px_3px_0px_#64748b]">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-slate-200 border-2 border-slate-400 flex items-center justify-center text-slate-700 text-xl shrink-0">
                                🚫
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h4 class="text-xs font-cartoon font-bold text-slate-800 uppercase tracking-wider">
                                        Proses Dokumen Dibatalkan
                                    </h4>
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 border border-slate-400">
                                        {{ $surat->status_label }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 mt-0.5 font-medium">Proses surat ini telah dibatalkan atas inisiatif Penyusun (Pembuat Dokumen).</p>
                                
                                <div class="mt-3 p-3.5 bg-white border-2 border-slate-300 rounded-2xl shadow-xs">
                                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-1.5">
                                        <span>💬</span> Pesan Pembatalan:
                                    </span>
                                    <p class="text-sm text-slate-800 font-semibold whitespace-pre-line leading-relaxed">{{ $catatanTerakhir ?: 'Mohon maaf Bpk/Ibu suratnya saya batalkan prosesnya' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Banner Dialog Maskot Naskah -->
                <div class="mb-6 p-4 bg-gradient-to-r from-sky-50 via-amber-50 to-emerald-50 border-2 border-[#003366] rounded-2xl shadow-[3px_3px_0px_#003366] flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-white border-2 border-[#003366] shadow-[2px_2px_0px_#003366] flex items-center justify-center text-2xl shrink-0">
                        📬
                    </div>
                    <div>
                        <h4 class="text-xs font-cartoon font-bold uppercase tracking-wider text-[#003366]">
                            Catatan Perjalanan Naskah Dinas
                        </h4>
                        <p class="text-xs text-slate-600 font-medium mt-0.5">
                            Pantau setiap langkah naskah surat dari draf awal, persetujuan paraf berjenjang, hingga resmi bertanda tangan dan terarsip!
                        </p>
                    </div>
                </div>

                <!-- Visualisasi Timeline -->
                <div class="mb-4">
                    <h4 class="text-xs font-cartoon font-bold uppercase tracking-wider text-[#003366] flex items-center gap-2 mb-6">
                        <span>⏱️</span>
                        <span>Log Jejak Audit & Kronologi Perjalanan</span>
                    </h4>

                    <div class="relative border-l-2 border-[#003366] ml-4 space-y-8 pb-4">
                        @forelse($trackings as $track)
                            @php
                                $aksiLower = strtolower($track->aksi);
                                $isItemKembali = str_contains($aksiLower, 'kembali') || str_contains($aksiLower, 'tolak') || str_contains($aksiLower, 'batal') || str_contains($aksiLower, 'balik dulu') || (str_contains($aksiLower, 'revisi') && !str_contains($aksiLower, 'draft direvisi'));
                                $isCurrent = $loop->last;
                            @endphp
                            <div class="pl-8 relative">
                                <!-- Titik Bulat Timeline -->
                                @if($isCurrent)
                                    <div class="absolute -left-[13px] top-0.5 flex items-center justify-center w-6 h-6">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-80 {{ $isItemKembali ? 'bg-rose-400' : 'bg-amber-400' }}"></span>
                                        <span class="relative inline-flex w-5 h-5 rounded-full border-2 border-[#003366] shadow-sm flex items-center justify-center text-white {{ $isItemKembali ? 'bg-rose-500' : 'bg-[#F5B800]' }}">
                                            @if($isItemKembali)
                                                <span class="text-[10px] font-black leading-none text-white">!</span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-[#002244]"></span>
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <div class="absolute -left-[9px] top-1.5">
                                        @if($isItemKembali)
                                            <div class="w-4 h-4 bg-rose-600 rounded-full border-2 border-white shadow-sm flex items-center justify-center text-white">
                                                <span class="text-[8px] font-black leading-none">!</span>
                                            </div>
                                        @else
                                            <div class="w-4 h-4 bg-[#003366] rounded-full border-2 border-white shadow-sm"></div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Header Log Card -->
                                <div class="p-4 rounded-2xl border-2 {{ $isCurrent ? ($isItemKembali ? 'bg-rose-50/70 border-rose-500 shadow-[2px_2px_0px_#e11d48]' : 'bg-amber-50/60 border-[#003366] shadow-[2px_2px_0px_#003366]') : ($isItemKembali ? 'bg-rose-50/30 border-rose-200' : 'bg-white border-slate-200 shadow-xs') }}">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h5 class="font-cartoon font-bold text-sm sm:text-base leading-snug {{ $isItemKembali ? 'text-rose-700' : 'text-[#003366]' }}">{{ $surat->formatTrackingAksi($track, $isCurrent) }}</h5>
                                            
                                            @if($isCurrent)
                                                @if(str_contains($aksiLower, 'tolak'))
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-200 text-rose-950 border border-rose-500">
                                                        <span>❌ DOKUMEN DITOLAK</span>
                                                    </span>
                                                @elseif(str_contains($aksiLower, 'batal'))
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-200 text-slate-900 border border-slate-500">
                                                        <span>🚫 PROSES DIBATALKAN</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isItemKembali ? 'bg-rose-200 text-rose-900 border border-rose-400' : 'bg-[#F5B800] text-[#002244] border border-[#002244]' }}">
                                                        <span>📍 POSISI SAAT INI</span>
                                                    </span>
                                                @endif
                                            @elseif(str_contains($aksiLower, 'tolak'))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                                    ❌ DITOLAK
                                                </span>
                                            @elseif(str_contains($aksiLower, 'batal'))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-800 border border-slate-400">
                                                    🚫 DIBATALKAN
                                                </span>
                                            @elseif($isItemKembali)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                                    ↩️ CATATAN REVISI
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs font-semibold text-slate-500 font-mono">{{ $track->created_at->translatedFormat('d M Y - H:i') }} WIB</p>
                                    </div>

                                    <p class="text-xs text-slate-600 mt-1 font-medium">
                                        Diproses oleh: <strong class="text-slate-900">{{ $track->aktor->name ?? 'User' }}</strong> 
                                        <span class="text-[#003366] font-mono font-bold">({{ strtoupper($track->aktor->role ?? '-') }})</span>
                                    </p>

                                    <!-- Catatan Log -->
                                    @if($track->catatan)
                                        @if($isItemKembali)
                                            <div class="mt-3 p-3.5 rounded-xl bg-rose-100/70 border border-rose-300">
                                                <div class="flex items-center text-xs font-bold text-rose-800 uppercase tracking-wider mb-1.5">
                                                    <span class="text-sm mr-1.5">💬</span>
                                                    <span>{{ str_contains($aksiLower, 'tolak') ? 'Alasan Penolakan Dokumen:' : (str_contains($aksiLower, 'batal') ? 'Alasan Pembatalan Surat:' : 'Catatan Revisi / Alasan:') }}</span>
                                                </div>
                                                <div class="p-2.5 bg-white border border-rose-200 rounded-lg text-xs sm:text-sm text-slate-800 font-medium whitespace-pre-line leading-relaxed">
                                                    {{ $track->catatan }}
                                                </div>
                                                @if($track->file_lampiran && !$isRahasiaLockedForAdmin)
                                                    <div class="mt-2.5 pt-2 border-t border-rose-200">
                                                        <a href="{{ asset('storage/' . $track->file_lampiran) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-rose-50 text-rose-800 text-xs font-bold rounded-lg border border-rose-300 transition">
                                                            <span>{{ str_contains($aksiLower, 'tolak') ? '📎 Unduh Berkas Catatan Penolakan' : '📎 Unduh File Koreksi / Catatan Revisi' }}</span>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="mt-2.5 p-3 rounded-xl bg-white border border-slate-200 text-xs text-slate-700">
                                                <span class="font-bold text-[#003366]">Catatan:</span>
                                                <span class="text-slate-800 ml-1 italic">{{ $track->catatan }}</span>
                                            </div>
                                            @if($track->file_lampiran && !$isRahasiaLockedForAdmin)
                                                <div class="mt-2.5">
                                                    <a href="{{ asset('storage/' . $track->file_lampiran) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-slate-50 text-[#003366] text-xs font-bold rounded-lg border border-slate-300 transition">
                                                        <span>📎</span> Unduh File Lampiran
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    @elseif($track->file_lampiran && !$isRahasiaLockedForAdmin)
                                        <div class="mt-2.5">
                                            <a href="{{ asset('storage/' . $track->file_lampiran) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-slate-50 text-rose-800 text-xs font-bold rounded-lg border border-rose-300 transition">
                                                <span>📎</span> Unduh File Koreksi / Catatan Revisi
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm italic pl-6 font-medium">Belum ada riwayat tracking untuk surat ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
