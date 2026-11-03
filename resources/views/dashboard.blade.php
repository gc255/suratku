<x-app-layout>
    <style>
        /* Efek Lampu Memutar pada Tombol (Glowing Rotating Border) */
        @keyframes putarLampu {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .btn-lampu-putar {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 2.5px;
            border-radius: 0.375rem;
            overflow: hidden;
            background: transparent;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.45);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-lampu-putar:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 18px rgba(74, 222, 128, 0.75);
        }

        .btn-lampu-sinar {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400%;
            height: 400%;
            transform: translate(-50%, -50%) rotate(0deg);
            background: conic-gradient(
                from 0deg,
                transparent 0deg,
                transparent 160deg,
                rgba(74, 222, 128, 0.2) 200deg,
                #22c55e 260deg,
                #4ade80 290deg,
                #ffffff 330deg,
                #86efac 350deg,
                transparent 360deg
            );
            animation: putarLampu 2.5s linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        .btn-lampu-isi {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 0.25rem 0.75rem;
            border-radius: calc(0.375rem - 2px);
            background-color: #16a34a;
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 700;
            transition: background-color 0.2s ease;
            user-select: none;
            cursor: pointer;
        }

        .btn-lampu-putar:hover .btn-lampu-isi {
            background-color: #15803d;
        }

        /* 1. Tombol Aksi saat Dokumen Butuh Persetujuan / Tindakan (Hijau Terang Bersinar) */
        .btn-aksi-menunggu {
            background-color: #15803d !important;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
            color: #ffffff !important;
            border: 1px solid #22c55e !important;
            box-shadow: 0 2px 10px rgba(34, 197, 94, 0.45) !important;
            border-radius: 0.75rem !important;
        }
        .btn-aksi-menunggu:hover {
            background-color: #166534 !important;
            background: linear-gradient(135deg, #15803d 0%, #14532d 100%) !important;
            box-shadow: 0 4px 16px rgba(34, 197, 94, 0.7) !important;
        }

        /* 2. Tombol Aksi Default (Glass Midnight Pill) */
        .btn-aksi-default {
            background: rgba(30, 41, 59, 0.85) !important;
            backdrop-filter: blur(10px) !important;
            color: #f8fafc !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
            border-radius: 0.75rem !important;
        }
        .btn-aksi-default:hover {
            background: rgba(51, 65, 85, 0.95) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* 3. Tombol Tinjauan & Keputusan di Dalam Menu Popup Aksi */
        .btn-menu-tinjau {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(52, 211, 153, 0.4) !important;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35) !important;
            border-radius: 0.75rem !important;
        }
        .btn-menu-tinjau:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.55) !important;
        }

        /* 4. Tombol Modal: Setujui & Teruskan Dokumen (Hijau Mantap) */
        .btn-modal-teruskan {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(52, 211, 153, 0.5) !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.45) !important;
            border-radius: 0.75rem !important;
        }
        .btn-modal-teruskan:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.65) !important;
        }

        /* 5. Tombol Modal: Kembalikan Dokumen Surat (Merah Tegas) */
        .btn-modal-kembalikan {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(251, 113, 133, 0.5) !important;
            box-shadow: 0 4px 14px rgba(244, 63, 94, 0.45) !important;
            border-radius: 0.75rem !important;
        }
        .btn-modal-kembalikan:hover {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important;
            box-shadow: 0 6px 18px rgba(244, 63, 94, 0.65) !important;
        }

        /* 6. Kartu Radio Pilihan Teruskan Aktif */
        .card-radio-teruskan-active {
            background-color: rgba(6, 78, 59, 0.5) !important;
            border: 2px solid rgba(52, 211, 153, 0.9) !important;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.35) !important;
            border-radius: 0.875rem !important;
        }

        /* 7. Kartu Radio Pilihan Kembalikan Aktif */
        .card-radio-kembalikan-active {
            background-color: rgba(136, 19, 55, 0.5) !important;
            border: 2px solid rgba(244, 63, 94, 0.9) !important;
            box-shadow: 0 0 15px rgba(244, 63, 94, 0.35) !important;
            border-radius: 0.875rem !important;
        }

        /* 8. Tombol Aksi Informasi Surat (Cyan Glow) */
        .btn-aksi-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(6, 182, 212, 0.4) !important;
            box-shadow: 0 2px 8px rgba(6, 182, 212, 0.35) !important;
            border-radius: 0.75rem !important;
        }
        .btn-aksi-cyan:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%) !important;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.55) !important;
        }
    </style>

        <script>
        function dashboardData() {
            return {
                showModal: false,
                previewUrl: '',
                previewTitle: '',
                previewType: '',
                openPreview(url, title, type) {
                    this.previewUrl = url;
                    this.previewTitle = title;
                    this.previewType = (type || '').toLowerCase();
                    this.showModal = true;
                },
                closePreview() {
                    this.showModal = false;
                    this.previewUrl = '';
                    this.previewTitle = '';
                    this.previewType = '';
                    if (this.hasSubmittedAjax) {
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('tab', 'sudah_paraf');
                        window.location.href = currentUrl.toString();
                    }
                },
                showTrackingModal: false,
                trackingJudul: '',
                trackingNomorSurat: '',
                trackingJenis: '',
                trackingUnit: '',
                trackingPenandatangan: '',
                trackingStatus: '',
                trackingStatusRaw: '',
                trackingStatusBadge: '',
                trackingCatatanTerakhir: '',
                trackingLampiranTerakhir: '',
                trackingTrackUrl: '',
                trackingDraft: '',
                trackingDraftType: 'pdf',
                trackingFinal: '',
                trackingList: [],
                openTrackingFromElement(el) {
                    this.trackingJudul = el.dataset.judul || '';
                    this.trackingNomorSurat = el.dataset.nomor || '';
                    this.trackingJenis = el.dataset.jenis || '';
                    this.trackingUnit = el.dataset.unit || '';
                    this.trackingPenandatangan = el.dataset.penandatangan || '';
                    this.trackingStatus = el.dataset.status || '';
                    this.trackingStatusRaw = el.dataset.statusRaw || '';
                    this.trackingStatusBadge = el.dataset.statusBadge || '';
                    this.trackingCatatanTerakhir = el.dataset.catatanTerakhir || '';
                    this.trackingLampiranTerakhir = el.dataset.lampiranTerakhir || '';
                    try {
                        this.trackingList = JSON.parse(el.dataset.trackings || '[]');
                    } catch (e) {
                        this.trackingList = [];
                    }
                    this.trackingTrackUrl = el.dataset.trackUrl || '';
                    this.trackingDraft = el.dataset.draft || '';
                    this.trackingDraftType = el.dataset.draftType || 'pdf';
                    this.trackingFinal = el.dataset.final || '';
                    this.showTrackingModal = true;
                },
                closeTracking() {
                    this.showTrackingModal = false;
                    this.trackingList = [];
                    this.trackingJudul = '';
                    this.trackingNomorSurat = '';
                    this.trackingJenis = '';
                    this.trackingUnit = '';
                    this.trackingPenandatangan = '';
                    this.trackingStatus = '';
                    this.trackingStatusRaw = '';
                    this.trackingStatusBadge = '';
                    this.trackingCatatanTerakhir = '';
                    this.trackingLampiranTerakhir = '';
                    this.trackingTrackUrl = '';
                    this.trackingDraft = '';
                    this.trackingDraftType = 'pdf';
                    this.trackingFinal = '';
                },
                // Modal Informasi Surat
                showInfoModal: false,
                infoId: null,
                infoJudul: '',
                infoNomorSurat: '',
                infoJenis: '',
                infoPembuat: '',
                infoTanggal: '',
                infoUnit: '',
                infoKasubbag: '',
                infoWadir: '',
                infoPenandatangan: '',
                infoDitandatanganiOleh: '',
                infoPengunggahArsip: '',
                infoStatus: '',
                infoStatusBadge: '',
                infoStatusRaw: '',
                infoDraftUrl: '',
                infoDraftType: 'pdf',
                infoFinalUrl: '',
                infoTrackUrl: '',
                infoTrackingsJson: '[]',
                openInfoModalFromElement(el) {
                    this.infoId = el.dataset.id || null;
                    this.infoJudul = el.dataset.judul || '';
                    this.infoNomorSurat = el.dataset.nomor || '';
                    this.infoJenis = el.dataset.jenis || '-';
                    this.infoPembuat = el.dataset.pembuat || '-';
                    this.infoTanggal = el.dataset.tanggal || '-';
                    this.infoUnit = el.dataset.unit || '-';
                    this.infoKasubbag = el.dataset.kasubbag || '-';
                    this.infoWadir = el.dataset.wadir || '-';
                    this.infoPenandatangan = el.dataset.penandatangan || '-';
                    this.infoDitandatanganiOleh = el.dataset.ditandatanganiOleh || '';
                    this.infoPengunggahArsip = el.dataset.pengunggahArsip || '';
                    this.infoStatus = el.dataset.status || '';
                    this.infoStatusBadge = el.dataset.statusBadge || '';
                    this.infoStatusRaw = el.dataset.statusRaw || '';
                    this.infoDraftUrl = el.dataset.draft || '';
                    this.infoDraftType = el.dataset.draftType || 'pdf';
                    this.infoFinalUrl = el.dataset.final || '';
                    this.infoTrackUrl = el.dataset.trackUrl || '';
                    this.infoTrackingsJson = el.dataset.trackings || '[]';
                    this.showInfoModal = true;
                },
                closeInfoModal() {
                    this.showInfoModal = false;
                    this.infoId = null;
                    this.infoJudul = '';
                    this.infoNomorSurat = '';
                    this.infoJenis = '';
                    this.infoPembuat = '';
                    this.infoTanggal = '';
                    this.infoUnit = '';
                    this.infoKasubbag = '';
                    this.infoWadir = '';
                    this.infoPenandatangan = '';
                    this.infoDitandatanganiOleh = '';
                    this.infoPengunggahArsip = '';
                    this.infoStatus = '';
                    this.infoStatusBadge = '';
                    this.infoStatusRaw = '';
                    this.infoDraftUrl = '';
                    this.infoDraftType = 'pdf';
                    this.infoFinalUrl = '';
                    this.infoTrackUrl = '';
                    this.infoTrackingsJson = '[]';
                },
                openTrackingFromInfo() {
                    const trackingsData = this.infoTrackingsJson;
                    const judul = this.infoJudul;
                    const nomor = this.infoNomorSurat;
                    const jenis = this.infoJenis;
                    const unit = this.infoUnit;
                    const penandatangan = this.infoPenandatangan;
                    const status = this.infoStatus;
                    const statusRaw = this.infoStatusRaw;
                    const statusBadge = this.infoStatusBadge;
                    const trackUrl = this.infoTrackUrl;
                    const draft = this.infoDraftUrl;
                    const draftType = this.infoDraftType || 'pdf';
                    const final = this.infoFinalUrl;

                    this.closeInfoModal();

                    this.trackingJudul = judul;
                    this.trackingNomorSurat = nomor;
                    this.trackingJenis = jenis;
                    this.trackingUnit = unit;
                    this.trackingPenandatangan = penandatangan;
                    this.trackingStatus = status;
                    this.trackingStatusRaw = statusRaw;
                    this.trackingStatusBadge = statusBadge;
                    try {
                        this.trackingList = JSON.parse(trackingsData);
                    } catch (e) {
                        this.trackingList = [];
                    }
                    this.trackingTrackUrl = trackUrl;
                    this.trackingDraft = draft;
                    this.trackingDraftType = draftType;
                    this.trackingFinal = final;
                    this.showTrackingModal = true;
                },
                isItemDikembalikan(item) {
                    const aksi = (item && item.aksi) ? item.aksi.toUpperCase() : '';
                    return aksi.includes('DIKEMBALIKAN') || aksi.includes('PERLU REVISI');
                },
                isStatusDikembalikan(status) {
                    return (status || '').toUpperCase().includes('DIKEMBALIKAN');
                },
                getLatestRevisiCatatan() {
                    if (!this.trackingList || this.trackingList.length === 0) return '';
                    for (let i = this.trackingList.length - 1; i >= 0; i--) {
                        const item = this.trackingList[i];
                        if (this.isItemDikembalikan(item) && item.catatan && item.catatan.trim() !== '') {
                            return item.catatan;
                        }
                    }
                    return '';
                },
                // Modal Tinjauan & Keputusan Paraf (Teruskan / Kembalikan)
                showTinjauModal: false,
                tinjauSuratId: null,
                tinjauJudul: '',
                tinjauActionUrl: '',
                tinjauRole: '',
                tinjauKembaliTarget: '',
                tinjauDraftUrl: '',
                tinjauDraftType: 'pdf',
                tinjauCatatanRevisi: '',
                tinjauLampiranRevisi: '',
                tinjauOpsiKembali: [],
                tinjauTargetKembali: '',
                tinjauKeputusan: 'teruskan',
                tinjauTujuanWadir: '',
                tinjauTujuanKasubbag: '',
                tinjauKomentar: '',
                tinjauIsFinalSigner: false,
                tinjauPenandatanganLabel: '',
                openTinjauModal(id, judul, actionUrl, role, kembaliTarget, draftUrl, catatanRevisi = '', lampiranRevisi = '', opsiKembali = [], defaultWadir = '', isFinalSigner = false, penandatanganLabel = '', defaultKasubbag = '', draftType = 'pdf') {
                    this.tinjauSuratId = id;
                    this.tinjauJudul = judul;
                    this.tinjauActionUrl = actionUrl;
                    this.tinjauRole = role;
                    this.tinjauKembaliTarget = kembaliTarget;
                    this.tinjauDraftUrl = draftUrl;
                    this.tinjauDraftType = draftType || 'pdf';
                    this.tinjauCatatanRevisi = catatanRevisi;
                    this.tinjauLampiranRevisi = lampiranRevisi;
                    this.tinjauOpsiKembali = opsiKembali;
                    this.tinjauTargetKembali = (opsiKembali && opsiKembali.length > 0) ? opsiKembali[0].key : '';
                    this.tinjauKeputusan = 'teruskan';
                    this.tinjauTujuanWadir = defaultWadir || '';
                    this.tinjauTujuanKasubbag = defaultKasubbag || '';
                    this.tinjauKomentar = '';
                    this.tinjauIsFinalSigner = !!isFinalSigner;
                    this.tinjauPenandatanganLabel = penandatanganLabel || '';
                    this.showTinjauModal = true;
                },
                openTinjauFromElement(el) {
                    let opsiKembali = [];
                    try {
                        opsiKembali = JSON.parse(el.dataset.opsiKembali || '[]');
                    } catch (e) {
                        opsiKembali = [];
                    }
                    this.openTinjauModal(
                        el.dataset.id,
                        el.dataset.judul,
                        el.dataset.actionUrl,
                        el.dataset.role,
                        el.dataset.kembaliTarget,
                        el.dataset.draft,
                        el.dataset.catatanRevisi || '',
                        el.dataset.lampiranRevisi || '',
                        opsiKembali,
                        el.dataset.tujuanWadir || '',
                        el.dataset.isFinalSigner === '1',
                        el.dataset.penandatanganLabel || '',
                        el.dataset.tujuanKasubbag || '',
                        el.dataset.draftType || 'pdf'
                    );
                },
                closeTinjauModal() {
                    this.showTinjauModal = false;
                    this.tinjauSuratId = null;
                    this.tinjauJudul = '';
                    this.tinjauActionUrl = '';
                    this.tinjauRole = '';
                    this.tinjauKembaliTarget = '';
                    this.tinjauDraftUrl = '';
                    this.tinjauDraftType = 'pdf';
                    this.tinjauCatatanRevisi = '';
                    this.tinjauLampiranRevisi = '';
                    this.tinjauOpsiKembali = [];
                    this.tinjauTargetKembali = '';
                    this.tinjauKeputusan = 'teruskan';
                    this.tinjauTujuanWadir = '';
                    this.tinjauTujuanKasubbag = '';
                    this.tinjauKomentar = '';
                    this.tinjauIsFinalSigner = false;
                    this.tinjauPenandatanganLabel = '';
                },
                handleTinjauSetujui(event) {
                    if (this.tinjauIsFinalSigner) {
                        const id = this.tinjauSuratId;
                        const judul = this.tinjauJudul;
                        const penandatanganLabel = this.tinjauPenandatanganLabel;
                        const catatan = this.tinjauKomentar || '';
                        this.closeTinjauModal();
                        this.openUploadFinalModal(id, judul, penandatanganLabel, '', catatan);
                    } else {
                        event.target.closest('form').submit();
                    }
                },
                // Modal Upload File Final (Telah Di-TTD)
                showUploadFinalModal: false,
                uploadFinalId: null,
                uploadFinalJudul: '',
                uploadFinalNomorSurat: '',
                uploadFinalPenandatanganLabel: 'Direktur',
                uploadFinalCatatan: '',
                uploadFinalActionUrl: '',
                isUploadingFinal: false,
                hasSubmittedAjax: false,
                openUploadFinalModal(id, judul, penandatanganLabel = 'Direktur', nomorSurat = '', catatan = '') {
                    this.uploadFinalId = id;
                    this.uploadFinalJudul = judul;
                    this.uploadFinalPenandatanganLabel = penandatanganLabel || 'Direktur';
                    this.uploadFinalNomorSurat = nomorSurat || '';
                    this.uploadFinalCatatan = catatan || '';
                    this.uploadFinalActionUrl = '{{ url('/surat') }}/' + id + '/final';
                    this.isUploadingFinal = false;
                    this.showUploadFinalModal = true;
                },
                closeUploadFinalModal() {
                    this.showUploadFinalModal = false;
                    this.uploadFinalId = null;
                    this.uploadFinalJudul = '';
                    this.uploadFinalPenandatanganLabel = 'Direktur';
                    this.uploadFinalNomorSurat = '';
                    this.uploadFinalCatatan = '';
                    this.uploadFinalActionUrl = '';
                    this.isUploadingFinal = false;
                },
                async submitUploadFinal(event) {
                    const form = event.target;
                    const nomorInput = form.querySelector('input[name="nomor_surat"]');
                    if (!nomorInput || !nomorInput.value.trim()) {
                        alert('Silakan masukkan Nomor Surat resmi terlebih dahulu.');
                        if (nomorInput) nomorInput.focus();
                        return;
                    }

                    const fileInput = form.querySelector('input[type="file"][name="file_final"]');
                    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                        alert('Silakan pilih file PDF final yang telah ditandatangani.');
                        return;
                    }

                    this.isUploadingFinal = true;
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(this.uploadFinalActionUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        if (response.ok) {
                            const result = await response.json();
                            this.hasSubmittedAjax = true;
                            // 1. Popup ditutup
                            this.closeUploadFinalModal();
                            // 2. Diberikan informasi bahwa dokumen telah diarsipkan
                            this.arsipData = result.dokumen || {
                                id: this.uploadFinalId,
                                nomor_surat: this.uploadFinalNomorSurat,
                                judul: this.uploadFinalJudul,
                                penandatangan: this.uploadFinalPenandatanganLabel,
                                file_url: '',
                                waktu: 'Baru saja'
                            };
                            this.showArsipModal = true;
                        } else {
                            let errMsg = 'Gagal mengunggah file final.';
                            try {
                                const errData = await response.json();
                                if (errData.errors) {
                                    errMsg = Object.values(errData.errors).flat().join('\n');
                                } else if (errData.message) {
                                    errMsg = errData.message;
                                }
                            } catch (e) {}
                            alert(errMsg);
                            this.isUploadingFinal = false;
                        }
                    } catch (err) {
                        console.error(err);
                        form.submit();
                    }
                },
                // Modal Informasi Dokumen Telah Diarsipkan
                showArsipModal: @json(session('dokumen_diarsipkan') ? true : false),
                arsipData: @json(session('dokumen_diarsipkan')),
                closeArsipModal(refresh = false) {
                    this.showArsipModal = false;
                    const hadAjax = this.hasSubmittedAjax;
                    this.arsipData = null;
                    if (refresh && hadAjax) {
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('tab', 'sudah_paraf');
                        window.location.href = currentUrl.toString();
                    }
                },
                simpanTtdNanti() {
                    if (!this.uploadFinalId) return;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url('/surat') }}/' + this.uploadFinalId + '/paraf';
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const kep = document.createElement('input');
                    kep.type = 'hidden';
                    kep.name = 'keputusan';
                    kep.value = 'teruskan';
                    form.appendChild(kep);

                    if (this.uploadFinalCatatan) {
                        const com = document.createElement('input');
                        com.type = 'hidden';
                        com.name = 'komentar';
                        com.value = this.uploadFinalCatatan;
                        form.appendChild(com);
                    }

                    document.body.appendChild(form);
                    form.submit();
                },
                // Modal Edit / Revisi Draft untuk Penyusun
                showEditDraftModal: false,
                editDraftId: null,
                editDraftJudul: '',
                editDraftJenisId: '',
                editDraftKasubbag: '',
                editDraftActionUrl: '',
                editDraftCatatan: '',
                editDraftLampiran: '',
                editDraftHasKasubbag: true,
                editDraftIsRevisi: false,
                openEditDraftModal(id, judul, jenisId, kasubbag, actionUrl, catatan, lampiran = '', hasKasubbag = true, isRevisi = false) {
                    this.editDraftId = id;
                    this.editDraftJudul = judul;
                    this.editDraftJenisId = jenisId;
                    this.editDraftKasubbag = kasubbag || '';
                    this.editDraftActionUrl = actionUrl;
                    this.editDraftCatatan = catatan;
                    this.editDraftLampiran = lampiran;
                    this.editDraftHasKasubbag = !!hasKasubbag;
                    this.editDraftIsRevisi = !!isRevisi;
                    this.showEditDraftModal = true;
                },
                closeEditDraftModal() {
                    this.showEditDraftModal = false;
                    this.editDraftId = null;
                    this.editDraftJudul = '';
                    this.editDraftJenisId = '';
                    this.editDraftKasubbag = '';
                    this.editDraftActionUrl = '';
                    this.editDraftCatatan = '';
                    this.editDraftLampiran = '';
                    this.editDraftHasKasubbag = true;
                    this.editDraftIsRevisi = false;
                },
                // Modal Edit Dokumen Arsip & Nomor Surat
                showEditArsipModal: false,
                editArsipId: null,
                editArsipJudul: '',
                editArsipNomorSurat: '',
                editArsipCanChangeFile: false,
                editArsipPengunggahName: '',
                editArsipPenandatanganLabel: '',
                editArsipFileFinalUrl: '',
                editArsipActionUrl: '',
                isUpdatingArsip: false,
                openEditArsipModal(id, judul, nomorSurat, canChangeFile, pengunggahName, penandatanganLabel, fileFinalUrl) {
                    this.editArsipId = id;
                    this.editArsipJudul = judul;
                    this.editArsipNomorSurat = nomorSurat || '';
                    this.editArsipCanChangeFile = !!canChangeFile;
                    this.editArsipPengunggahName = pengunggahName || '-';
                    this.editArsipPenandatanganLabel = penandatanganLabel || '-';
                    this.editArsipFileFinalUrl = fileFinalUrl || '';
                    this.editArsipActionUrl = '{{ url('/surat') }}/' + id + '/update-arsip';
                    this.isUpdatingArsip = false;
                    this.showEditArsipModal = true;
                },
                closeEditArsipModal() {
                    this.showEditArsipModal = false;
                    this.editArsipId = null;
                    this.editArsipJudul = '';
                    this.editArsipNomorSurat = '';
                    this.editArsipCanChangeFile = false;
                    this.editArsipPengunggahName = '';
                    this.editArsipPenandatanganLabel = '';
                    this.editArsipFileFinalUrl = '';
                    this.editArsipActionUrl = '';
                    this.isUpdatingArsip = false;
                }
            };
        }
    </script>

    <div class="py-8 sm:py-10" x-data="dashboardData()" @keydown.escape.window="if (showModal) { closePreview(); } else if (showTrackingModal) { closeTracking(); } else if (showInfoModal) { closeInfoModal(); } else if (showTinjauModal) { closeTinjauModal(); } else if (showEditDraftModal) { closeEditDraftModal(); } else if (showUploadFinalModal) { closeUploadFinalModal(); } else if (showArsipModal) { closeArsipModal(true); } else if (showEditArsipModal) { closeEditArsipModal(); }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Deretan Ucapan Selamat Datang & Tombol Aksi (Menyatu dengan Background, Tanpa Box / Batasan) -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight leading-tight">
                        Selamat datang kembali, <span class="text-blue-400">{{ Auth::user()->name }}</span> <span class="text-slate-300 font-medium">({{ Auth::user()->role_label }}).</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 flex items-center gap-2 flex-wrap">
                        <span>Portal Rekapitulasi & Tracking Surat Keluar Politeknik Pekerjaan Umum</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-400/30">
                            Versi Beta
                        </span>
                    </p>
                </div>

                @if(in_array(Auth::user()->role, ['penyusun', 'super_admin', 'superadmin']))
                    <div class="flex items-center shrink-0">
                        <a href="{{ route('surat.create') }}" class="btn-aksi-blue inline-flex items-center justify-center gap-2 font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-blue-600/25 hover:shadow-blue-500/40 text-xs sm:text-sm transition-all duration-200 active:scale-95 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Upload Draft Baru</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Notifikasi Berhasil -->
            @if (session('success'))
                <div class="bg-emerald-500/15 border border-emerald-500/40 text-emerald-200 p-4 mb-6 rounded-2xl shadow-lg flex items-center gap-2 backdrop-blur-md">
                    <span class="text-xl">✅</span>
                    <p class="font-medium text-xs sm:text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Notifikasi Error -->
            @if (session('error'))
                <div class="bg-red-500/15 border border-red-500/40 text-red-200 p-4 mb-6 rounded-2xl shadow-lg flex items-center gap-2 backdrop-blur-md">
                    <span class="text-xl">⛔</span>
                    <p class="font-bold text-xs sm:text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <!-- KARTU STATISTIK WIDGET -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Kartu 1: Total Surat -->
                <div class="glass-panel rounded-2xl p-6 border border-white/10 hover:border-blue-500/40 transition duration-300 shadow-xl group">
                    <div class="flex items-center">
                        <div class="p-3.5 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-400/30 mr-4 group-hover:scale-110 transition duration-200 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Surat</p>
                            <p class="text-3xl font-extrabold text-white mt-1">{{ $statTotal }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kartu 2: Menunggu Tindakan / Diproses -->
                <div class="glass-panel rounded-2xl p-6 border border-white/10 hover:border-amber-500/40 transition duration-300 shadow-xl group">
                    <div class="flex items-center">
                        <div class="p-3.5 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-400/30 mr-4 group-hover:scale-110 transition duration-200 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Butuh Tindakan</p>
                            <p class="text-3xl font-extrabold text-amber-300 mt-1">{{ $statProses }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kartu 3: Selesai / Diarsipkan -->
                <div class="glass-panel rounded-2xl p-6 border border-white/10 hover:border-emerald-500/40 transition duration-300 shadow-xl group">
                    <div class="flex items-center">
                        <div class="p-3.5 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 mr-4 group-hover:scale-110 transition duration-200 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Surat Diarsipkan</p>
                            <p class="text-3xl font-extrabold text-emerald-300 mt-1">{{ $statSelesai }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AKHIR KARTU STATISTIK -->

            <!-- Bagian Judul Halaman -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                <div>
                    <h3 class="text-xl font-extrabold text-white">
                        {{ in_array(Auth::user()->role, ['super_admin', 'superadmin']) ? __('Daftar Seluruh Surat di Sistem') : __('Daftar Surat Keluar') }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Menampilkan arsip dokumen surat beserta status penerusan dan riwayat paraf.
                    </p>
                </div>
            </div>

            <!-- Toolbar Kontrol: Tampilkan di Paling Kiri & Kolom Cari di Paling Kanan Sejajar Widget Surat Diarsipkan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5 items-center">
                
                <!-- 1. Kolom Tampilkan (Paling Kiri - Sejajar Kolom 1 Widget Total Surat) -->
                <div class="flex items-center justify-start">
                    <form action="{{ route('dashboard') }}" method="GET" id="perPageForm" class="glass-pill flex items-center gap-2 text-xs text-slate-300 rounded-xl px-3.5 py-2 shadow-md">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        <label for="per_page_select" class="font-bold whitespace-nowrap text-slate-400">Tampilkan:</label>
                        <select id="per_page_select" name="per_page" onchange="document.getElementById('perPageForm').submit()" class="text-xs font-extrabold border-none p-0 pr-6 text-white focus:ring-0 bg-transparent cursor-pointer">
                            <option value="5" {{ ($perPage ?? 10) == 5 ? 'selected' : '' }} class="bg-slate-900 text-white">5 surat</option>
                            <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }} class="bg-slate-900 text-white">10 surat</option>
                            <option value="20" {{ ($perPage ?? 10) == 20 ? 'selected' : '' }} class="bg-slate-900 text-white">20 surat</option>
                            <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }} class="bg-slate-900 text-white">50 surat</option>
                            <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }} class="bg-slate-900 text-white">100 surat</option>
                        </select>
                    </form>
                </div>

                <!-- 2. Kolom Tengah (Spacer Kosong agar Kolom Cari Berada di Paling Kanan) -->
                <div class="hidden md:block"></div>

                <!-- 3. Kolom Cari Surat (Paling Kanan - Lebar 100% Sejajar Kolom 3 Widget Surat Diarsipkan) -->
                <div class="w-full">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center w-full shadow-lg rounded-xl overflow-hidden border border-white/15">
                        <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
                        @if(request('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul surat..." class="text-xs border-none py-2.5 px-3.5 w-full focus:ring-2 focus:ring-blue-500 bg-slate-900/90 text-white placeholder-slate-400">
                            @if(request('search'))
                                <a href="{{ route('dashboard', array_filter(['per_page' => $perPage ?? 10, 'tab' => request('tab')])) }}" title="Hapus Pencarian" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-white text-xs font-bold">&times;</a>
                            @endif
                        </div>
                        <button type="submit" class="btn-aksi-blue font-bold py-2.5 px-5 text-xs transition flex items-center whitespace-nowrap">
                            <span class="mr-1">🔍</span> Cari
                        </button>
                    </form>
                </div>
            </div>

            <!-- TAB NAVIGASI HAK AKSES -->
            @if(in_array(Auth::user()->role, ['super_admin', 'superadmin']))
                <!-- TAB KHUSUS SUPER ADMIN: Tampilkan Semua Surat (Default), Sedang Diproses, dan Arsip -->
                <div class="flex items-center space-x-2 border-b border-white/10 mb-5 overflow-x-auto">
                    <a href="{{ route('dashboard', array_merge(request()->query(), ['tab' => 'semua'])) }}" 
                       class="inline-flex items-center py-2.5 px-4 text-xs font-bold rounded-t-xl transition-all border-b-2 {{ ($activeTab ?? 'semua') === 'semua' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/15' : 'border-transparent text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <span class="mr-1.5">📂</span> Semua Surat (Sistem)
                        <span class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-500/30 text-indigo-200">
                            {{ $countSemua ?? 0 }}
                        </span>
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->query(), ['tab' => 'antrean'])) }}" 
                       class="inline-flex items-center py-2.5 px-4 text-xs font-bold rounded-t-xl transition-all border-b-2 {{ ($activeTab ?? 'semua') === 'antrean' ? 'border-blue-500 text-blue-400 bg-blue-500/15' : 'border-transparent text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <span class="mr-1.5">⏳</span> Sedang Diproses
                        @if(($countAntrean ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-amber-500 text-white shadow-xs">
                                {{ $countAntrean }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->query(), ['tab' => 'sudah_paraf'])) }}" 
                       class="inline-flex items-center py-2.5 px-4 text-xs font-bold rounded-t-xl transition-all border-b-2 {{ ($activeTab ?? 'semua') === 'sudah_paraf' ? 'border-emerald-500 text-emerald-400 bg-emerald-500/15' : 'border-transparent text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <span class="mr-1.5">🗄️</span> Selesai / Diarsipkan
                        @if(($countSudahParaf ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-emerald-500 text-white shadow-xs">
                                {{ $countSudahParaf }}
                            </span>
                        @endif
                    </a>
                </div>
            @elseif(!in_array(Auth::user()->role, ['penyusun']))
                <!-- TAB NAVIGASI PEMERIKSA (Kasubbag, Sek Kabag, Sek Wadir, Sek Direktur, Unit) -->
                <div class="flex items-center space-x-2 border-b border-white/10 mb-5 overflow-x-auto">
                    <a href="{{ route('dashboard', array_merge(request()->query(), ['tab' => 'antrean'])) }}" 
                       class="inline-flex items-center py-2.5 px-4 text-xs font-bold rounded-t-xl transition-all border-b-2 {{ ($activeTab ?? 'antrean') === 'antrean' ? 'border-blue-500 text-blue-400 bg-blue-500/15' : 'border-transparent text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <span class="mr-1.5">⏳</span> Menunggu Tindakan
                        @if(($countAntrean ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-amber-500 text-white shadow-xs">
                                {{ $countAntrean }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->query(), ['tab' => 'sudah_paraf'])) }}" 
                       class="inline-flex items-center py-2.5 px-4 text-xs font-bold rounded-t-xl transition-all border-b-2 {{ ($activeTab ?? 'antrean') === 'sudah_paraf' ? 'border-emerald-500 text-emerald-400 bg-emerald-500/15' : 'border-transparent text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <span class="mr-1.5">✅</span> Riwayat Sudah Diparaf
                        @if(($countSudahParaf ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-emerald-500 text-white shadow-xs">
                                {{ $countSudahParaf }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->query(), ['tab' => 'semua'])) }}" 
                       class="inline-flex items-center py-2.5 px-4 text-xs font-bold rounded-t-xl transition-all border-b-2 {{ ($activeTab ?? 'antrean') === 'semua' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/15' : 'border-transparent text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <span class="mr-1.5">📂</span> Semua Surat Masuk
                        <span class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-700 text-slate-300">
                            {{ $countSemua ?? 0 }}
                        </span>
                    </a>
                </div>
            @endif

            <!-- Bagian Tabel Data -->
            <div class="glass-panel overflow-hidden shadow-2xl rounded-2xl border border-white/10">
                <div class="p-6 text-slate-100 overflow-x-auto min-h-[360px]">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-slate-950/70 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-extrabold text-slate-300 uppercase tracking-wider whitespace-nowrap">Nomor Surat</th>
                                <th class="px-6 py-3.5 text-left text-xs font-extrabold text-slate-300 uppercase tracking-wider">Judul Surat</th>
                                <th class="px-6 py-3.5 text-left text-xs font-extrabold text-slate-300 uppercase tracking-wider">Pembuat</th>
                                <th class="px-6 py-3.5 text-left text-xs font-extrabold text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-left text-xs font-extrabold text-slate-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3.5 text-left pl-6 text-xs font-extrabold text-slate-300 uppercase tracking-wider transition-all duration-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @php
                                $uRole = Auth::user()->role;
                            @endphp
                            @forelse ($surats as $surat)
                                @php
                                    $isArchivedOrFinal = in_array($surat->status, ['DIARSIPKAN', \App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD']) || !empty($surat->file_final);
                                    $isPengunggah = ($surat->pengunggah_final_id && $surat->pengunggah_final_id === Auth::id());
                                    $isSekdir = ($uRole === 'sekretaris_direktur');
                                    $isSuperAdmin = in_array($uRole, ['super_admin', 'superadmin']);
                                    $canEditArsip = $isArchivedOrFinal && ($isPengunggah || $isSekdir || $isSuperAdmin);
                                    $canChangeFinalFile = ($isPengunggah || $isSuperAdmin);
                                @endphp
                                <tr>
                                    <!-- 1. Nomor Surat -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($surat->nomor_surat)
                                            <div class="flex items-center gap-1.5">
                                                <div class="font-mono font-bold text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 px-2.5 py-1 rounded-lg inline-flex items-center gap-1.5 shadow-sm" title="Nomor Surat Resmi">
                                                    <span>📄</span>
                                                    <span>{{ $surat->nomor_surat }}</span>
                                                </div>
                                                @if($canEditArsip)
                                                    <button type="button"
                                                            @click="openEditArsipModal(
                                                                {{ $surat->id }}, 
                                                                '{{ addslashes($surat->judul) }}', 
                                                                '{{ addslashes($surat->nomor_surat ?? '') }}', 
                                                                {{ $canChangeFinalFile ? 'true' : 'false' }}, 
                                                                '{{ addslashes($surat->pengunggahFinal->name ?? 'Pengunggah') }}', 
                                                                '{{ addslashes($surat->tujuan_penandatangan_label) }}', 
                                                                '{{ $surat->file_final ? asset('storage/' . $surat->file_final) : '' }}'
                                                            )"
                                                            class="p-1 text-slate-400 hover:text-amber-300 hover:bg-white/10 rounded-lg transition cursor-pointer"
                                                            title="{{ $canChangeFinalFile ? 'Edit Berkas Final & Nomor Surat' : 'Edit Nomor Surat' }}">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500 italic bg-white/5 px-2 py-0.5 rounded-md border border-white/5" title="Belum memiliki nomor surat resmi (masih draft/proses paraf)">
                                                    <span class="text-slate-600">—</span> Belum Ada
                                                </span>
                                                @if($canEditArsip)
                                                    <button type="button"
                                                            @click="openEditArsipModal(
                                                                {{ $surat->id }}, 
                                                                '{{ addslashes($surat->judul) }}', 
                                                                '', 
                                                                {{ $canChangeFinalFile ? 'true' : 'false' }}, 
                                                                '{{ addslashes($surat->pengunggahFinal->name ?? 'Pengunggah') }}', 
                                                                '{{ addslashes($surat->tujuan_penandatangan_label) }}', 
                                                                '{{ $surat->file_final ? asset('storage/' . $surat->file_final) : '' }}'
                                                            )"
                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 transition cursor-pointer"
                                                            title="Beri Nomor Surat">
                                                        <span>✏️ Beri No</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 2. Judul Surat & Info Alur Cepat -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white text-sm leading-snug">{{ $surat->judul }}</div>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            @if($surat->jenisSurat)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                                    📑 {{ $surat->jenisSurat->nama }}
                                                </span>
                                            @endif

                                            <!-- Tombol Popup Informasi Surat & Alur Disposisi -->
                                            <button type="button" 
                                                    data-id="{{ $surat->id }}"
                                                    data-nomor="{{ $surat->nomor_surat ?? '' }}"
                                                    data-judul="{{ addslashes($surat->judul) }}"
                                                    data-jenis="{{ addslashes($surat->jenisSurat->nama ?? '-') }}"
                                                    data-pembuat="{{ addslashes($surat->pembuat->name ?? '-') }}{{ isset($surat->pembuat->role_label) ? ' (' . addslashes($surat->pembuat->role_label) . ')' : '' }}"
                                                    data-tanggal="{{ $surat->created_at->translatedFormat('d F Y, H:i') }} WIB"
                                                    data-unit="{{ addslashes($surat->tujuan_unit_label ?? '-') }}"
                                                    data-kasubbag="{{ addslashes($surat->tujuan_kasubbag_label ?? '-') }}"
                                                    data-wadir="{{ addslashes($surat->tujuan_wadir_label ?? '-') }}"
                                                    data-penandatangan="{{ addslashes($surat->tujuan_penandatangan_label ?? '-') }}"
                                                    data-ditandatangani-oleh="{{ ($surat->tujuan_penandatangan !== 'direktur' && in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', 'DIARSIPKAN'])) ? addslashes($surat->tujuan_penandatangan_label) : '' }}"
                                                    data-pengunggah-arsip="{{ $surat->pengunggahFinal ? (addslashes($surat->pengunggahFinal->name) . ' (' . addslashes($surat->pengunggahFinal->role_label) . ')') : '' }}"
                                                    data-status="{{ addslashes($surat->status_label) }}"
                                                    data-status-raw="{{ $surat->status }}"
                                                    data-status-badge="{{ $surat->status_badge_class }}"
                                                    data-draft="{{ $surat->file_draft ? route('surat.viewDraftFile', $surat->id) : '' }}"
                                                    data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                    data-final="{{ $surat->file_final ? route('surat.viewFinalFile', $surat->id) : '' }}"
                                                    data-track-url="{{ route('surat.track', $surat->id) }}"
                                                    data-trackings="{{ json_encode($surat->trackings->values()->map(fn($t, $idx) => [
                                                        'id' => $t->id,
                                                        'waktu' => $t->created_at->translatedFormat('d M Y - H:i') . ' WIB',
                                                        'aksi' => $surat->formatTrackingAksi($t, $idx === $surat->trackings->count() - 1),
                                                        'aktor' => $t->aktor->name ?? 'User',
                                                        'role' => strtoupper($t->aktor->role ?? '-'),
                                                        'catatan' => $t->catatan,
                                                        'file_lampiran' => $t->file_lampiran ? route('surat.viewLampiranRevisi', $surat->id) : null
                                                    ])) }}"
                                                    @click="openInfoModalFromElement($el)"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-bold bg-cyan-500/10 hover:bg-cyan-500/25 text-cyan-300 hover:text-white border border-cyan-500/25 hover:border-cyan-400/50 transition cursor-pointer shadow-2xs"
                                                    title="Lihat rincian tujuan alur, penandatangan, dan pengarsipan">
                                                <span>ℹ️</span> Info Alur Surat
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-300">{{ $surat->pembuat->name ?? 'Tidak diketahui' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $surat->status_badge_class }}">
                                            {{ $surat->status_label }}
                                        </span>
                                        @if($surat->catatan_terakhir)
                                            <div class="mt-1 text-xs text-red-200 bg-red-900/30 p-2 rounded-lg border border-red-500/40 max-w-xs shadow-xs" title="Catatan Terakhir">
                                                <span class="font-bold">💬 Catatan:</span> {{ $surat->catatan_terakhir }}
                                            </div>
                                        @endif
                                        @if($surat->lampiran_revisi_terakhir)
                                            <div class="mt-1.5">
                                                <a href="{{ route('surat.viewLampiranRevisi', $surat->id) }}" target="_blank" download class="inline-flex items-center gap-1 text-[11px] font-bold text-red-300 hover:text-white bg-red-900/40 hover:bg-red-800/60 border border-red-500/40 px-2 py-0.5 rounded-md shadow-2xs transition">
                                                    <span>📎</span> Unduh File Koreksi
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 font-medium">{{ $surat->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-3 text-left pl-6 whitespace-nowrap transition-all duration-300"
                                        x-data="{ open: false }" 
                                        @click.outside="open = false"
                                        :style="open ? 'min-width: 285px;' : ''">
                                        
                                        @php
                                            $canReview = false;
                                            $reviewRoleType = '';
                                            $reviewKembaliTarget = '';
                                            $reviewBtnLabel = '';
                                            $reviewBtnIcon = '✅';
                                            $isFinalSignerThisStep = false;
                                            $targetTtd = $surat->tujuan_penandatangan ?? 'direktur';

                                            if (\App\Models\User::isPemeriksaUnit($uRole)) {
                                                if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_UNIT, \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_UNIT]) && (!$surat->tujuan_unit || $surat->tujuan_unit === $uRole)) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'unit';
                                                    $reviewKembaliTarget = 'Penyusun (' . ($surat->pembuat->name ?? 'Pembuat') . ')';
                                                    if ($targetTtd === 'unit') {
                                                        $isFinalSignerThisStep = true;
                                                        $reviewBtnLabel = 'Tinjau & TTD ' . ($surat->tujuan_unit_label ?? 'Unit');
                                                        $reviewBtnIcon = '✍️';
                                                    } else {
                                                        $reviewBtnLabel = 'Tinjau & Berikan Paraf';
                                                        $reviewBtnIcon = '✅';
                                                    }
                                                }
                                            } elseif (in_array($uRole, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan'])) {
                                                if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_KASUBBAG, 'DIBUAT', \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_KASUBBAG]) && (!$surat->tujuan_kasubbag || $surat->tujuan_kasubbag === $uRole)) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'kasubbag';
                                                    $reviewKembaliTarget = $surat->tujuan_unit ? ($surat->tujuan_unit_label ?? 'Unit/Prodi/Lab') : ('Penyusun (' . ($surat->pembuat->name ?? 'Pembuat') . ')');
                                                    if ($targetTtd === 'kasubbag') {
                                                        $isFinalSignerThisStep = true;
                                                        $reviewBtnLabel = 'Tinjau & TTD ' . ($surat->tujuan_kasubbag_label ?? 'Kasubbag');
                                                        $reviewBtnIcon = '✍️';
                                                    } else {
                                                        $reviewBtnLabel = 'Tinjau & Berikan Paraf';
                                                        $reviewBtnIcon = '✅';
                                                    }
                                                }
                                            } elseif ($uRole === 'sekretaris_kabag') {
                                                if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_KABAG, 'PARAF_KASUBBAG', \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_KABAG])) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'sekretaris_kabag';
                                                    $reviewKembaliTarget = $surat->tujuan_kasubbag_label ?? 'Kasubbag Terkait';
                                                    if ($targetTtd === 'kabag') {
                                                        $isFinalSignerThisStep = true;
                                                        $reviewBtnLabel = 'Tinjau & TTD Kabag';
                                                        $reviewBtnIcon = '✍️';
                                                    } else {
                                                        $reviewBtnLabel = 'Tinjau & Paraf Kabag';
                                                        $reviewBtnIcon = '✅';
                                                    }
                                                }
                                            } elseif (in_array($uRole, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3'])) {
                                                if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_WADIR, 'PARAF_KABAG', \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_WADIR]) && (!$surat->tujuan_wadir || $surat->tujuan_wadir === $uRole)) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'sekretaris_wadir';
                                                    $reviewKembaliTarget = 'Sekretaris Kepala Bagian';
                                                    $wadirPejabatLabel = str_replace('Sekretaris ', '', Auth::user()->role_label);
                                                    if ($targetTtd === 'wadir') {
                                                        $isFinalSignerThisStep = true;
                                                        $reviewBtnLabel = 'Tinjau & TTD ' . $wadirPejabatLabel;
                                                        $reviewBtnIcon = '✍️';
                                                    } else {
                                                        $reviewBtnLabel = 'Tinjau & Paraf Wadir';
                                                        $reviewBtnIcon = '✅';
                                                    }
                                                }
                                            } elseif ($uRole === 'sekretaris_direktur') {
                                                if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_DIREKTUR, 'PARAF_WADIR'])) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'sekretaris_direktur';
                                                    $reviewKembaliTarget = $surat->tujuan_wadir_label ?? 'Sekretaris Wakil Direktur';
                                                    $isFinalSignerThisStep = true;
                                                    $reviewBtnLabel = 'Tinjau & TTD Direktur';
                                                    $reviewBtnIcon = '✍️';
                                                }
                                            }

                                            // Evaluasi apakah user saat ini berhak mengunggah berkas TTD final
                                            $canUploadFinal = false;
                                            if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD'])) {
                                                if (in_array($uRole, ['super_admin', 'superadmin'])) {
                                                    $canUploadFinal = true;
                                                } elseif ($targetTtd === 'direktur' && $uRole === 'sekretaris_direktur') {
                                                    $canUploadFinal = true;
                                                } elseif ($targetTtd === 'wadir' && in_array($uRole, ['sekretaris_wadir_1', 'sekretaris_wadir_2', 'sekretaris_wadir_3']) && (!$surat->tujuan_wadir || $surat->tujuan_wadir === $uRole)) {
                                                    $canUploadFinal = true;
                                                } elseif ($targetTtd === 'kabag' && $uRole === 'sekretaris_kabag') {
                                                    $canUploadFinal = true;
                                                } elseif ($targetTtd === 'kasubbag' && in_array($uRole, ['kasubbag_akademik', 'kasubbag_psi', 'kasubbag_keuangan']) && (!$surat->tujuan_kasubbag || $surat->tujuan_kasubbag === $uRole)) {
                                                    $canUploadFinal = true;
                                                } elseif ($targetTtd === 'unit' && \App\Models\User::isPemeriksaUnit($uRole) && (!$surat->tujuan_unit || $surat->tujuan_unit === $uRole)) {
                                                    $canUploadFinal = true;
                                                }
                                            }

                                            $canPenyusunEdit = $surat->canPenyusunEdit(Auth::id());
                                            $canPenyusunDelete = $surat->canPenyusunDelete(Auth::id());
                                            $isSuperAdmin = in_array($uRole, ['super_admin', 'superadmin']);
                                            $isPenyusunRevisi = ($surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN);
                                            $hasActionNeeded = $canReview || ($canPenyusunEdit && $isPenyusunRevisi) || $canUploadFinal;
                                        @endphp

                                        <div class="inline-flex items-center justify-start transition-all duration-300 ease-in-out"
                                             :style="open ? 'min-width: 280px;' : 'min-width: 0px;'">
                                            
                                            <!-- Wrapper relatif khusus tombol & popup agar popup selalu menempel dekat tombol -->
                                            <div class="relative inline-flex items-center shrink-0">
                                                
                                                <!-- Tombol Utama Aksi (Mengikuti Halaman Super Admin) -->
                                                <button @click="open = !open" 
                                                        type="button" 
                                                        class="inline-flex items-center justify-center px-4 py-2 {{ $hasActionNeeded ? 'btn-aksi-menunggu' : 'btn-aksi-default' }} text-white text-xs font-bold rounded-xl shadow-lg transition focus:outline-none gap-2 whitespace-nowrap cursor-pointer">
                                                    @if($hasActionNeeded)
                                                        <span class="relative flex h-2.5 w-2.5">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-80"></span>
                                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                                                        </span>
                                                    @endif
                                                    <span>⚙️ Aksi</span>
                                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </button>

                                                <!-- Popup Menu Muncul Tepat di Sebelah Kanan Tombol Aksi (Jarak Pas 8px dari Tombol) -->
                                                <div x-show="open" 
                                                     x-transition:enter="transition ease-out duration-150"
                                                     x-transition:enter-start="transform opacity-0 -translate-x-2 scale-95"
                                                     x-transition:enter-end="transform opacity-100 translate-x-0 scale-100"
                                                     x-transition:leave="transition ease-in duration-100"
                                                     x-transition:leave-start="transform opacity-100 translate-x-0 scale-100"
                                                     x-transition:leave-end="transform opacity-0 -translate-x-2 scale-95"
                                                     class="absolute left-full top-0 ml-2 z-50 w-52 glass-panel bg-slate-900/95 backdrop-blur-2xl border border-white/20 p-2 rounded-2xl shadow-2xl flex flex-col gap-1.5 whitespace-nowrap text-left"
                                                     style="display: none; left: 100%; margin-left: 0.5rem; top: 0;">
                                                    
                                                    <!-- 1. View Surat (Draft) -->
                                                    @if($surat->file_draft)
                                                        <button type="button" 
                                                                data-url="{{ route('surat.viewDraftFile', $surat->id) }}"
                                                                data-title="{{ $surat->judul }}"
                                                                data-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                                @click="open = false; openPreview($el.dataset.url, $el.dataset.title, $el.dataset.type)"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-blue font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                title="Lihat Draft Dokumen Surat">
                                                            <span class="mr-2">👁️</span> View Surat
                                                        </button>
                                                    @endif

                                                    <!-- 2. Download / View Final (Jika Sudah Diupload) -->
                                                    @if($surat->file_final)
                                                        <button type="button" 
                                                                data-url="{{ route('surat.viewFinalFile', $surat->id) }}"
                                                                data-title="{{ $surat->judul }} (Final)"
                                                                data-type="{{ strtolower(pathinfo($surat->file_final, PATHINFO_EXTENSION)) }}"
                                                                @click="open = false; openPreview($el.dataset.url, $el.dataset.title, $el.dataset.type)"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-teal font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                title="Unduh / Lihat Dokumen Final Ber-TTD">
                                                            <span class="mr-2">📥</span> Download Final
                                                        </button>
                                                    @endif

                                                    <!-- 3. Tracking Surat (Popup Modal) -->
                                                    <button type="button" 
                                                            data-nomor="{{ $surat->nomor_surat ?? '' }}"
                                                            data-trackings="{{ json_encode($surat->trackings->values()->map(fn($t, $idx) => [
                                                                'id' => $t->id,
                                                                'waktu' => $t->created_at->translatedFormat('d M Y - H:i') . ' WIB',
                                                                'aksi' => $surat->formatTrackingAksi($t, $idx === $surat->trackings->count() - 1),
                                                                'aktor' => $t->aktor->name ?? 'User',
                                                                'role' => strtoupper($t->aktor->role ?? '-'),
                                                                'catatan' => $t->catatan,
                                                                'file_lampiran' => $t->file_lampiran ? route('surat.viewLampiranRevisi', $surat->id) : null
                                                            ])) }}"
                                                            data-judul="{{ $surat->judul }}"
                                                            data-jenis="{{ $surat->jenisSurat->nama ?? '' }}"
                                                            data-unit="{{ $surat->tujuan_unit_label ?? '' }}"
                                                            data-penandatangan="{{ $surat->tujuan_penandatangan_label ?? '' }}"
                                                            data-status="{{ $surat->status_label }}"
                                                            data-status-raw="{{ $surat->status }}"
                                                            data-status-badge="{{ $surat->status_badge_class }}"
                                                            data-catatan-terakhir="{{ $surat->catatan_terakhir ?? '' }}"
                                                            data-lampiran-terakhir="{{ $surat->lampiran_revisi_terakhir ? route('surat.viewLampiranRevisi', $surat->id) : '' }}"
                                                            data-track-url="{{ route('surat.track', $surat->id) }}"
                                                            data-draft="{{ $surat->file_draft ? route('surat.viewDraftFile', $surat->id) : '' }}"
                                                            data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                            data-final="{{ $surat->file_final ? route('surat.viewFinalFile', $surat->id) : '' }}"
                                                            @click="open = false; openTrackingFromElement($el)"
                                                            class="flex items-center w-full px-3 py-2 btn-aksi-indigo font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                            title="Lihat Riwayat Perjalanan Surat">
                                                        <span class="mr-2">🔍</span> Tracking Surat
                                                    </button>

                                                    <!-- 4. Informasi Surat (Popup Modal Rincian & Alur) -->
                                                    <button type="button" 
                                                            data-id="{{ $surat->id }}"
                                                            data-nomor="{{ $surat->nomor_surat ?? '' }}"
                                                            data-judul="{{ addslashes($surat->judul) }}"
                                                            data-jenis="{{ addslashes($surat->jenisSurat->nama ?? '-') }}"
                                                            data-pembuat="{{ addslashes($surat->pembuat->name ?? '-') }}{{ isset($surat->pembuat->role_label) ? ' (' . addslashes($surat->pembuat->role_label) . ')' : '' }}"
                                                            data-tanggal="{{ $surat->created_at->translatedFormat('d F Y, H:i') }} WIB"
                                                            data-unit="{{ addslashes($surat->tujuan_unit_label ?? '-') }}"
                                                            data-kasubbag="{{ addslashes($surat->tujuan_kasubbag_label ?? '-') }}"
                                                            data-wadir="{{ addslashes($surat->tujuan_wadir_label ?? '-') }}"
                                                            data-penandatangan="{{ addslashes($surat->tujuan_penandatangan_label ?? '-') }}"
                                                            data-ditandatangani-oleh="{{ ($surat->tujuan_penandatangan !== 'direktur' && in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', 'DIARSIPKAN'])) ? addslashes($surat->tujuan_penandatangan_label) : '' }}"
                                                            data-pengunggah-arsip="{{ $surat->pengunggahFinal ? (addslashes($surat->pengunggahFinal->name) . ' (' . addslashes($surat->pengunggahFinal->role_label) . ')') : '' }}"
                                                            data-status="{{ addslashes($surat->status_label) }}"
                                                            data-status-raw="{{ $surat->status }}"
                                                            data-status-badge="{{ $surat->status_badge_class }}"
                                                            data-draft="{{ $surat->file_draft ? route('surat.viewDraftFile', $surat->id) : '' }}"
                                                            data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                            data-final="{{ $surat->file_final ? route('surat.viewFinalFile', $surat->id) : '' }}"
                                                            data-track-url="{{ route('surat.track', $surat->id) }}"
                                                            data-trackings="{{ json_encode($surat->trackings->values()->map(fn($t, $idx) => [
                                                                'id' => $t->id,
                                                                'waktu' => $t->created_at->translatedFormat('d M Y - H:i') . ' WIB',
                                                                'aksi' => $surat->formatTrackingAksi($t, $idx === $surat->trackings->count() - 1),
                                                                'aktor' => $t->aktor->name ?? 'User',
                                                                'role' => strtoupper($t->aktor->role ?? '-'),
                                                                'catatan' => $t->catatan,
                                                                'file_lampiran' => $t->file_lampiran ? route('surat.viewLampiranRevisi', $surat->id) : null
                                                            ])) }}"
                                                            @click="open = false; openInfoModalFromElement($el)"
                                                            class="flex items-center w-full px-3 py-2 btn-aksi-cyan font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                            title="Lihat Rincian & Informasi Dokumen Surat">
                                                        <span class="mr-2">ℹ️</span> Informasi Surat
                                                    </button>

                                                    <!-- 5. AKSI TINJAU / PARAF / TTD (Muncul Sesuai Hak Akses & Status Dokumen) -->
                                                    @if($canReview)
                                                        <button type="button" 
                                                                data-id="{{ $surat->id }}"
                                                                data-judul="{{ $surat->judul }}"
                                                                data-action-url="{{ route('surat.paraf', $surat->id) }}"
                                                                data-role="{{ $reviewRoleType }}"
                                                                data-kembali-target="{{ $reviewKembaliTarget }}"
                                                                data-is-final-signer="{{ $isFinalSignerThisStep ? '1' : '0' }}"
                                                                data-penandatangan-label="{{ $surat->tujuan_penandatangan_label }}"
                                                                data-draft="{{ $surat->file_draft ? route('surat.viewDraftFile', $surat->id) : '' }}"
                                                                data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                                data-catatan-revisi="{{ $surat->catatan_terakhir ?? '' }}"
                                                                data-lampiran-revisi="{{ $surat->lampiran_revisi_terakhir ? route('surat.viewLampiranRevisi', $surat->id) : '' }}"
                                                                data-opsi-kembali="{{ json_encode($surat->getOpsiPengembalian($uRole)) }}"
                                                                data-tujuan-wadir="{{ $surat->tujuan_wadir ?? '' }}"
                                                                data-tujuan-kasubbag="{{ $surat->tujuan_kasubbag ?? '' }}"
                                                                @click="open = false; openTinjauFromElement($el)"
                                                                class="flex items-center w-full px-3 py-2 btn-menu-tinjau font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                title="{{ $reviewBtnLabel }}">
                                                            <span class="mr-2">{{ $reviewBtnIcon }}</span> {{ $reviewBtnLabel }}
                                                        </button>
                                                    @endif

                                                    <!-- 6. AKSI UPLOAD TTD FINAL (Muncul Untuk Pejabat Penandatangan Terpilih Saat Status Menunggu Upload Final) -->
                                                    @if($canUploadFinal)
                                                        <button type="button" 
                                                                @click="open = false; openUploadFinalModal({{ $surat->id }}, '{{ addslashes($surat->judul) }}', '{{ addslashes($surat->tujuan_penandatangan_label) }}', '{{ addslashes($surat->nomor_surat ?? '') }}')"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-teal font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                title="Unggah Dokumen Final Ber-TTD">
                                                            <span class="mr-2">📤</span> Upload TTD Final
                                                        </button>
                                                    @endif

                                                    <!-- 7. AKSI EDIT DRAFT / REVISI DRAFT (Khusus Penyusun) -->
                                                    @if($canPenyusunEdit)
                                                        <button type="button" 
                                                                @click="open = false; openEditDraftModal(
                                                                    {{ $surat->id }}, 
                                                                    '{{ addslashes($surat->judul) }}', 
                                                                    '{{ $surat->jenis_surat_id }}', 
                                                                    '{{ $surat->tujuan_kasubbag ?? '' }}', 
                                                                    '{{ route('surat.updateDraft', $surat->id) }}', 
                                                                    '{{ addslashes($surat->catatan_terakhir ?? '') }}', 
                                                                    '{{ $surat->lampiran_revisi_terakhir ? route('surat.viewLampiranRevisi', $surat->id) : '' }}',
                                                                    {{ $surat->tujuan_unit ? 'false' : 'true' }},
                                                                    {{ $surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN ? 'true' : 'false' }}
                                                                )"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-amber font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                title="{{ $surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN ? 'Perbaiki Draft Surat Sesuai Catatan' : 'Edit Judul atau Ganti Berkas Draft' }}">
                                                            <span class="mr-2">✏️</span> {{ $surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN ? 'Revisi Draft' : 'Edit Surat' }}
                                                        </button>
                                                    @endif

                                                    <!-- 8. AKSI EDIT BERKAS & NOMOR SURAT ARSIP -->
                                                    @if($canEditArsip)
                                                        <button type="button" 
                                                                @click="open = false; openEditArsipModal(
                                                                    {{ $surat->id }}, 
                                                                    '{{ addslashes($surat->judul) }}', 
                                                                    '{{ addslashes($surat->nomor_surat ?? '') }}', 
                                                                    {{ $canChangeFinalFile ? 'true' : 'false' }}, 
                                                                    '{{ addslashes($surat->pengunggahFinal->name ?? 'Pengunggah') }}', 
                                                                    '{{ addslashes($surat->tujuan_penandatangan_label) }}', 
                                                                    '{{ $surat->file_final ? route('surat.viewFinalFile', $surat->id) : '' }}'
                                                                )"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-amber font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                title="{{ $canChangeFinalFile ? 'Edit Berkas Final & Nomor Surat' : 'Berikan / Edit Nomor Surat' }}">
                                                            <span class="mr-2">📝</span>
                                                            <span>{{ $canChangeFinalFile ? 'Edit Berkas & No. Surat' : 'Edit / Berikan No. Surat' }}</span>
                                                        </button>
                                                    @endif

                                                    <!-- 9. Hapus Surat (Super Admin & Penyusun jika belum final/arsip) -->
                                                    @if($isSuperAdmin || $canPenyusunDelete)
                                                        <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" class="w-full m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus dokumen surat ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-3 py-2 btn-aksi-red font-bold text-xs rounded-xl transition text-left cursor-pointer shadow-sm hover:scale-[1.02]"
                                                                    title="Hapus Dokumen Surat">
                                                                <span class="mr-2">🗑️</span> Hapus Surat
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada surat yang diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Pagination -->
            <div class="mt-6">
                {{ $surats->links() }}
            </div>

        </div>

        <!-- MODAL PREVIEW SURAT -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] overflow-y-auto bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4 sm:px-6 lg:px-8"
             style="display: none;">
             
            <div @click.away="closePreview()" 
                 x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-7xl w-full overflow-hidden flex flex-col text-slate-100"
                 style="height: 88vh; max-height: 92vh;">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-3.5 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3 truncate mr-4">
                        <div class="p-2.5 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-xl flex-shrink-0 shadow-[0_0_12px_rgba(59,130,246,0.3)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="truncate">
                            <h3 class="text-base font-extrabold text-white truncate" x-text="previewTitle">Pratinjau Surat</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-md uppercase border"
                                      :class="previewType === 'pdf' ? 'bg-rose-500/20 text-rose-300 border-rose-500/30' : 'bg-blue-500/20 text-blue-300 border-blue-500/30'"
                                      x-text="previewType"></span>
                                <span class="text-xs text-slate-400">File Unggahan</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <!-- Buka Tab Baru -->
                        <a :href="previewUrl" target="_blank" 
                           class="inline-flex items-center px-3 py-1.5 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 hover:text-white border border-white/10 text-xs font-semibold rounded-xl transition shadow-sm"
                           title="Buka di tab baru">
                            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Tab Baru
                        </a>
                        
                        <!-- Download -->
                        <a :href="previewUrl" download
                           class="inline-flex items-center px-3.5 py-1.5 btn-aksi-blue text-white text-xs font-bold rounded-xl transition shadow-md hover:scale-[1.02]"
                           title="Unduh file">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh
                        </a>

                        <!-- Tutup -->
                        <button type="button" @click="closePreview()" 
                                class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer"
                                title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Body Modal -->
                <div class="p-3 flex-1 overflow-hidden bg-slate-950/60 flex items-center justify-center" style="min-height: 0;">
                    <!-- Jika file PDF -->
                    <template x-if="previewType === 'pdf'">
                        <iframe :src="previewUrl" class="w-full h-full rounded-xl border border-white/10 shadow-inner bg-white" style="width: 100%; height: 100%; min-height: 100%;"></iframe>
                    </template>

                    <!-- Jika file Word / Dokumen non-PDF -->
                    <template x-if="previewType !== 'pdf'">
                        <div class="text-center p-8 glass-panel bg-slate-900/90 rounded-2xl shadow-2xl border border-white/15 max-w-sm my-auto text-slate-100">
                            <div class="w-16 h-16 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-2xl flex items-center justify-center mx-auto mb-3.5 shadow-[0_0_20px_rgba(59,130,246,0.3)]">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 7V3.5L18.5 9H15z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-extrabold text-white mb-1.5">Dokumen Word (.doc / .docx)</h4>
                            <p class="text-xs text-slate-400 mb-5 leading-relaxed">
                                Format file Word tidak dapat dipratinjau langsung di dalam browser. Silakan unduh dokumen untuk melihat isinya.
                            </p>
                            <a :href="previewUrl" download
                               class="inline-flex items-center px-4 py-2.5 btn-aksi-blue text-white text-xs font-bold rounded-xl shadow-md transition hover:scale-[1.02]">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Dokumen Sekarang
                            </a>
                        </div>
                    </template>
                </div>
                
                <!-- Footer Modal -->
                <div class="px-6 py-3 bg-slate-950/80 border-t border-white/10 flex justify-between items-center text-xs text-slate-400 flex-shrink-0">
                    <span>Tekan <kbd class="px-2 py-0.5 bg-slate-800 border border-white/15 rounded-md shadow-xs font-mono text-slate-300">Esc</kbd> untuk menutup</span>
                    <button type="button" @click="closePreview()" class="px-4 py-1.5 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl transition text-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL TRACKING SURAT -->
        <div x-show="showTrackingModal" 
             @click.self="closeTracking()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:px-6 lg:px-8"
             style="display: none;">
             
            <div @click.away="closeTracking()" 
                 x-show="showTrackingModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-3xl w-full overflow-hidden flex flex-col max-h-[90vh] text-slate-100">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3 truncate mr-4">
                        <div class="p-2.5 bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-xl flex-shrink-0 shadow-[0_0_12px_rgba(99,102,241,0.3)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="truncate">
                            <h3 class="text-base font-extrabold text-white truncate" x-text="trackingJudul">Riwayat Perjalanan Surat</h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <template x-if="trackingNomorSurat">
                                    <span class="text-xs font-mono font-bold px-2.5 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"
                                          x-text="'📄 ' + trackingNomorSurat"></span>
                                </template>
                                <template x-if="trackingJenis">
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30"
                                          x-text="'📑 ' + trackingJenis"></span>
                                </template>
                                <template x-if="trackingUnit">
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"
                                          x-text="'🏢 ' + trackingUnit"></span>
                                </template>
                                <template x-if="trackingPenandatangan">
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg bg-cyan-500/20 text-cyan-300 border border-cyan-500/30"
                                          x-text="'✍️ TTD: ' + trackingPenandatangan"></span>
                                </template>
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg shadow-sm"
                                      :class="trackingStatusBadge || (isStatusDikembalikan(trackingStatusRaw) ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30')"
                                      x-text="'Status: ' + trackingStatus"></span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" @click="closeTracking()" 
                            class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer"
                            title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="p-6 overflow-y-auto flex-1 bg-slate-900/40">
                    <!-- Alert Highlight jika status surat sedang Dikembalikan / Perlu Revisi -->
                    <template x-if="isStatusDikembalikan(trackingStatusRaw) || isStatusDikembalikan(trackingStatus)">
                        <div class="mb-5 p-4 bg-gradient-to-r from-rose-950/60 via-red-950/40 to-slate-900/60 border-l-4 border-rose-500 rounded-r-2xl border-t border-r border-b border-rose-500/30 shadow-lg">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-300 text-lg shrink-0 shadow-[0_0_10px_rgba(244,63,94,0.3)]">
                                    ⚠️
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="text-xs font-extrabold text-rose-300 uppercase tracking-wider flex items-center gap-1.5">
                                            <span>Surat Perlu Revisi / Dikembalikan</span>
                                        </h4>
                                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-500/30 text-rose-200 border border-rose-500/40 shadow-xs" x-text="trackingStatus"></span>
                                    </div>
                                    <p class="text-xs text-rose-200/80 mt-0.5">Surat ini memerlukan perbaikan sebelum dapat diproses ke tingkat selanjutnya.</p>
                                    
                                    <template x-if="getLatestRevisiCatatan()">
                                        <div class="mt-2.5 p-3.5 bg-slate-950/70 border border-rose-500/30 rounded-xl shadow-xs">
                                            <span class="text-xs font-bold text-rose-300 flex items-center gap-1.5 mb-1">
                                                <span>💬</span> Catatan / Instruksi Revisi Terakhir:
                                            </span>
                                            <p class="text-sm text-rose-100 font-semibold whitespace-pre-line leading-relaxed" x-text="getLatestRevisiCatatan()"></p>
                                        </div>
                                    </template>
                                    <template x-if="trackingLampiranTerakhir">
                                        <div class="mt-2.5">
                                            <a :href="trackingLampiranTerakhir" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 text-xs font-bold rounded-xl border border-rose-500/40 transition shadow-sm">
                                                <span>📎</span> Unduh File Koreksi / Lampiran Revisi
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Tombol Preview Cepat Jika Ada File -->
                    <div class="mb-6 flex flex-wrap gap-2.5" x-show="trackingDraft || trackingFinal">
                        <template x-if="trackingDraft">
                            <button type="button" 
                                    @click="openPreview(trackingDraft, trackingJudul, trackingDraftType)"
                                    class="inline-flex items-center btn-aksi-blue text-white text-xs font-bold py-1.5 px-3.5 rounded-xl shadow-md transition hover:scale-[1.02] cursor-pointer">
                                👁️ Lihat File Draft (Awal)
                            </button>
                        </template>
                        <template x-if="trackingFinal">
                            <button type="button" 
                                    @click="openPreview(trackingFinal, trackingJudul + ' (Final)', 'pdf')"
                                    class="inline-flex items-center btn-aksi-teal text-white text-xs font-bold py-1.5 px-3.5 rounded-xl shadow-md transition hover:scale-[1.02] cursor-pointer">
                                📥 Unduh File Final (TTD)
                            </button>
                        </template>
                    </div>

                    <!-- Visualisasi Timeline -->
                    <div class="relative border-l-2 border-blue-500/50 ml-3">
                        <template x-for="(item, index) in trackingList" :key="item.id">
                            <div class="mb-6 pl-7 relative">
                                <!-- Titik Bulat Timeline -->
                                <!-- 1. Titik Saat Ini (Posisi Terakhir) Berkedip / Radar Ping -->
                                <template x-if="index === trackingList.length - 1">
                                    <div class="absolute -left-[13px] top-0.5 flex items-center justify-center w-6 h-6">
                                        <!-- Efek Radar Ping Berkedip -->
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-80"
                                              :class="isItemDikembalikan(item) ? 'bg-rose-400' : 'bg-emerald-400'"></span>
                                        <!-- Lingkaran Inti Bercahaya -->
                                        <span class="relative inline-flex w-5 h-5 rounded-full border-2 border-slate-900 shadow-lg flex items-center justify-center text-white"
                                              :class="isItemDikembalikan(item) ? 'bg-rose-500 ring-4 ring-rose-500/40 shadow-[0_0_15px_rgba(244,63,94,0.9)]' : 'bg-emerald-500 ring-4 ring-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.9)]'">
                                            <template x-if="isItemDikembalikan(item)">
                                                <span class="text-[10px] font-black leading-none">!</span>
                                            </template>
                                            <template x-if="!isItemDikembalikan(item)">
                                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                            </template>
                                        </span>
                                    </div>
                                </template>

                                <!-- 2. Titik Riwayat Sebelumnya (Past Steps) -->
                                <template x-if="index < trackingList.length - 1">
                                    <div class="absolute -left-[9px] top-1.5">
                                        <template x-if="isItemDikembalikan(item)">
                                            <div class="w-4 h-4 bg-rose-600 rounded-full border-2 border-slate-900 shadow-sm flex items-center justify-center text-white">
                                                <span class="text-[8px] font-black leading-none">!</span>
                                            </div>
                                        </template>
                                        <template x-if="!isItemDikembalikan(item)">
                                            <div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-slate-900 shadow-sm"></div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Konten Log -->
                                <p class="text-xs text-slate-400 font-mono" x-text="item.waktu"></p>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <h4 class="font-extrabold text-base" :class="isItemDikembalikan(item) ? 'text-rose-400' : 'text-white'" x-text="item.aksi"></h4>
                                    
                                    <!-- Badge Khusus Titik Posisi Saat Ini (Berkedip) -->
                                    <template x-if="index === trackingList.length - 1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wider shadow-sm"
                                              :class="isItemDikembalikan(item) ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-[0_0_10px_rgba(244,63,94,0.3)]' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-[0_0_10px_rgba(16,185,129,0.3)]'">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-80"
                                                      :class="isItemDikembalikan(item) ? 'bg-rose-300' : 'bg-emerald-300'"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2"
                                                      :class="isItemDikembalikan(item) ? 'bg-rose-400' : 'bg-emerald-400'"></span>
                                            </span>
                                            <span>POSISI SAAT INI</span>
                                        </span>
                                    </template>

                                    <!-- Badge Dikembalikan untuk Riwayat Lampau -->
                                    <template x-if="isItemDikembalikan(item) && index < trackingList.length - 1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-500/40 shadow-xs">
                                            ↩️ DIKEMBALIKAN / REVISI
                                        </span>
                                    </template>
                                </div>
                                <p class="text-slate-300 text-sm mt-0.5">
                                    Oleh: <strong class="text-white" x-text="item.aktor"></strong> (<span class="text-slate-400" x-text="item.role"></span>)
                                </p>

                                <!-- Catatan Log: Jika Dikembalikan / Revisi Diberi Desain Alert Merah Spesial -->
                                <template x-if="item.catatan">
                                    <div>
                                        <!-- Model Revisi / Dikembalikan -->
                                        <template x-if="isItemDikembalikan(item)">
                                            <div class="mt-2.5 p-3.5 bg-rose-950/40 border-l-4 border-rose-500 rounded-r-xl border-t border-r border-b border-rose-500/30 shadow-sm">
                                                <div class="flex items-center text-xs font-bold text-rose-300 uppercase tracking-wider mb-1.5">
                                                    <span class="text-sm mr-1.5">💬</span>
                                                    <span>Catatan Revisi / Alasan Pengembalian:</span>
                                                </div>
                                                <div class="p-2.5 bg-slate-950/70 border border-rose-500/20 rounded-lg text-sm text-rose-100 font-medium whitespace-pre-line leading-relaxed shadow-inner" x-text="item.catatan"></div>
                                                <template x-if="item.file_lampiran">
                                                    <div class="mt-2.5 pt-2 border-t border-rose-500/20">
                                                        <a :href="item.file_lampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 text-xs font-bold rounded-xl border border-rose-500/40 transition shadow-sm">
                                                            <span>📎</span> Unduh File Koreksi / Catatan Revisi
                                                        </a>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Model Normal / Persetujuan / Info -->
                                        <template x-if="!isItemDikembalikan(item)">
                                            <div>
                                                <div class="mt-2 p-3 bg-slate-950/50 border-l-4 border-blue-500/70 rounded-r-xl border-t border-r border-b border-white/10 text-xs text-slate-300">
                                                    <span class="font-bold text-blue-400">Catatan:</span>
                                                    <span class="text-slate-200 ml-1 italic" x-text="item.catatan"></span>
                                                </div>
                                                <template x-if="item.file_lampiran">
                                                    <div class="mt-2">
                                                        <a :href="item.file_lampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-200 text-xs font-bold rounded-xl border border-blue-500/40 transition shadow-sm">
                                                            <span>📎</span> Unduh File Lampiran
                                                        </a>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="!item.catatan && item.file_lampiran">
                                    <div class="mt-2.5">
                                        <a :href="item.file_lampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 text-xs font-bold rounded-xl border border-rose-500/40 transition shadow-sm">
                                            <span>📎</span> Unduh File Koreksi / Catatan Revisi
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </template>
                        
                        <template x-if="trackingList.length === 0">
                            <p class="text-slate-400 text-sm italic pl-6">Belum ada riwayat tracking untuk surat ini.</p>
                        </template>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="px-6 py-3.5 bg-slate-950/80 border-t border-white/10 flex justify-between items-center text-xs text-slate-400 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <span>Tekan <kbd class="px-2 py-0.5 bg-slate-800 border border-white/15 rounded-md shadow-xs font-mono text-slate-300">Esc</kbd> untuk menutup</span>
                        <template x-if="trackingTrackUrl">
                            <a :href="trackingTrackUrl" target="_blank" class="text-blue-400 hover:text-blue-300 font-bold underline flex items-center gap-1 transition">
                                <span>Buka Halaman Penuh</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </template>
                    </div>
                    <button type="button" @click="closeTracking()" class="px-4 py-1.5 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl transition text-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL TINJAUAN & KEPUTUSAN PARAF (TERUSKAN / KEMBALIKAN) -->
        <div x-show="showTinjauModal" 
             @click.self="closeTinjauModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeTinjauModal()" 
                 x-show="showTinjauModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-xl w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-100">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl shrink-0 shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                            ⚖️
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Tinjauan & Keputusan Dokumen Surat</h3>
                            <p class="text-xs text-slate-400">Pilih keputusan untuk meneruskan ke tahap selanjutnya atau mengembalikan untuk perbaikan.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeTinjauModal()" class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Keputusan -->
                <form :action="tinjauActionUrl" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 bg-slate-900/40">
                    @csrf
                    
                    <!-- Informasi Dokumen -->
                    <div class="mb-5 p-4 bg-slate-950/60 rounded-xl border border-white/10 shadow-inner">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Dokumen Surat</label>
                        <p class="text-sm font-bold text-white" x-text="tinjauJudul"></p>
                        <template x-if="tinjauDraftUrl">
                            <div class="mt-2.5 pt-2.5 border-t border-white/10">
                                <button type="button" 
                                        @click="openPreview(tinjauDraftUrl, tinjauJudul, tinjauDraftType)"
                                        class="inline-flex items-center text-xs font-bold text-blue-400 hover:text-blue-300 hover:underline transition cursor-pointer">
                                    <span class="mr-1.5">👁️</span> Pratinjau File Draft Dokumen
                                </button>
                            </div>
                        </template>
                        <template x-if="tinjauCatatanRevisi">
                            <div class="mt-3 p-3.5 bg-rose-950/40 border border-rose-500/30 rounded-xl text-xs text-rose-200 shadow-sm">
                                <span class="font-bold flex items-center gap-1.5 text-rose-300">
                                    <span>💬</span> Catatan / Instruksi Revisi Sebelumnya:
                                </span>
                                <p class="mt-1 font-semibold text-rose-100 text-xs whitespace-pre-line leading-relaxed" x-text="tinjauCatatanRevisi"></p>
                            </div>
                        </template>
                        <template x-if="tinjauLampiranRevisi">
                            <div class="mt-2.5 pt-2.5 border-t border-white/10">
                                <a :href="tinjauLampiranRevisi" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 text-xs font-bold rounded-xl border border-rose-500/40 transition shadow-sm">
                                    <span>📎</span> Unduh File Koreksi / Lampiran Revisi Sebelumnya
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Pilihan Keputusan: Teruskan vs Kembalikan -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">
                            Pilih Keputusan Tindakan <span class="text-rose-400">*</span>
                        </label>
                        <div class="space-y-2.5">
                            <!-- Opsi 1: Teruskan / Setujui & Diarsipkan -->
                            <label class="flex items-start p-3.5 border rounded-xl cursor-pointer transition select-none"
                                   :class="tinjauKeputusan === 'teruskan' ? 'card-radio-teruskan-active' : 'border-white/10 hover:border-white/20 bg-slate-950/40'">
                                <input type="radio" name="keputusan" value="teruskan" x-model="tinjauKeputusan" class="mt-1 text-emerald-500 focus:ring-emerald-500 bg-slate-900 border-white/20">
                                <div class="ml-3">
                                    <span class="block text-sm font-extrabold text-white" 
                                          x-text="tinjauIsFinalSigner ? ('✅ Setujui & TTD ' + tinjauPenandatanganLabel) : '✅ Setujui & Teruskan ke Tingkat Berikutnya'"></span>
                                    <span class="block text-xs text-slate-300 mt-0.5"
                                          x-text="tinjauIsFinalSigner ? ('Dokumen disetujui & ditandatangani oleh ' + tinjauPenandatanganLabel + ' untuk kemudian diunggah berkas finalnya.') : 'Dokumen telah diverifikasi dan disetujui untuk dialirkan ke tahap proses berikutnya.'"></span>
                                </div>
                            </label>

                            <!-- Opsi 2: Kembalikan -->
                            <label class="flex items-start p-3.5 border rounded-xl cursor-pointer transition select-none"
                                   :class="tinjauKeputusan === 'kembalikan' ? 'card-radio-kembalikan-active' : 'border-white/10 hover:border-white/20 bg-slate-950/40'">
                                <input type="radio" name="keputusan" value="kembalikan" x-model="tinjauKeputusan" class="mt-1 text-rose-500 focus:ring-rose-500 bg-slate-900 border-white/20">
                                <div class="ml-3">
                                    <span class="block text-sm font-extrabold text-rose-300">↩️ Kembalikan / Perlu Revisi</span>
                                    <template x-if="!tinjauOpsiKembali || tinjauOpsiKembali.length <= 1">
                                        <span class="block text-xs text-rose-200/90 mt-0.5">
                                            Surat akan dikembalikan ke: <strong class="underline font-bold text-white" x-text="(tinjauOpsiKembali && tinjauOpsiKembali.length === 1) ? tinjauOpsiKembali[0].label : tinjauKembaliTarget"></strong> untuk diperbaiki sesuai instruksi.
                                        </span>
                                    </template>
                                    <template x-if="tinjauOpsiKembali && tinjauOpsiKembali.length > 1">
                                        <span class="block text-xs text-rose-200/90 mt-0.5">
                                            Surat dapat dikembalikan ke tingkatan alur yang telah dilewati sebelumnya (dapat dipilih di bawah) untuk perbaikan.
                                        </span>
                                    </template>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- KONTEN BILA KEPUTUSAN = TERUSKAN -->
                    <div x-show="tinjauKeputusan === 'teruskan'">
                        <!-- Khusus Unit / Prodi / Lab (Jika bukan penandatangan final): Wajib Memilih Kasubbag Tujuan -->
                        <div x-show="tinjauRole === 'unit' && !tinjauIsFinalSigner" class="mb-4 p-3.5 bg-amber-950/30 border border-amber-500/30 rounded-xl">
                            <label for="pilih_tujuan_kasubbag_modal" class="block text-xs font-bold text-amber-300 uppercase tracking-wider mb-1.5">
                                Pilih Kepala Subbagian (Kasubbag) yang Dituju <span class="text-rose-400">*</span>
                            </label>
                            <select id="pilih_tujuan_kasubbag_modal" 
                                    name="tujuan_kasubbag" 
                                    x-model="tinjauTujuanKasubbag"
                                    :disabled="tinjauKeputusan !== 'teruskan' || tinjauRole !== 'unit' || tinjauIsFinalSigner"
                                    :required="tinjauKeputusan === 'teruskan' && tinjauRole === 'unit' && !tinjauIsFinalSigner" 
                                    class="w-full text-xs font-medium border border-white/15 rounded-xl shadow-inner focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-slate-900 text-white p-2.5">
                                <option value="" disabled selected class="bg-slate-900 text-slate-400">-- Pilih Kasubbag Pemeriksa Lanjutan --</option>
                                @foreach(\App\Models\User::getKasubbagRoles() as $kRoleKey => $kRoleName)
                                    <option value="{{ $kRoleKey }}" class="bg-slate-900 text-white">{{ $kRoleName }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-amber-300/80 mt-1.5">
                                Surat akan diparaf oleh pemeriksa unit dan dialirkan langsung ke antrean Kasubbag terpilih.
                            </p>
                        </div>

                        <!-- Khusus Sekretaris Kabag (Jika bukan penandatangan final): Wajib Memilih Sekretaris Wadir yang Dituju -->
                        <div x-show="tinjauRole === 'sekretaris_kabag' && !tinjauIsFinalSigner" class="mb-4 p-3.5 bg-blue-950/30 border border-blue-500/30 rounded-xl">
                            <label for="pilih_tujuan_wadir_modal" class="block text-xs font-bold text-blue-300 uppercase tracking-wider mb-1.5">
                                Pilih Sekretaris Wakil Direktur yang Dituju <span class="text-rose-400">*</span>
                            </label>
                            <select id="pilih_tujuan_wadir_modal" 
                                    name="tujuan_wadir" 
                                    x-model="tinjauTujuanWadir"
                                    :disabled="tinjauKeputusan !== 'teruskan' || tinjauRole !== 'sekretaris_kabag' || tinjauIsFinalSigner"
                                    :required="tinjauKeputusan === 'teruskan' && tinjauRole === 'sekretaris_kabag' && !tinjauIsFinalSigner" 
                                    class="w-full text-xs font-medium border border-white/15 rounded-xl shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900 text-white p-2.5">
                                <option value="" disabled selected class="bg-slate-900 text-slate-400">-- Pilih Sekretaris Wakil Direktur --</option>
                                @foreach(\App\Models\User::getSekretarisWadirRoles() as $wRoleKey => $wRoleName)
                                    <option value="{{ $wRoleKey }}" class="bg-slate-900 text-white">{{ $wRoleName }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-blue-300/80 mt-1.5">
                                Surat akan diparaf atas nama Kabag dan dialirkan langsung ke antrean Wakil Direktur terpilih.
                            </p>
                        </div>

                        <!-- Catatan / Arahan Tambahan (Opsional) saat Diteruskan -->
                        <div class="mb-2">
                            <label for="komentar_teruskan" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                Catatan / Arahan Tambahan (Opsional)
                            </label>
                            <textarea id="komentar_teruskan" 
                                      name="komentar" 
                                      :disabled="tinjauKeputusan !== 'teruskan'"
                                      rows="2" 
                                      placeholder="Tuliskan catatan tambahan jika ada..." 
                                      class="w-full text-xs border border-white/15 rounded-xl shadow-inner focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-950/70 text-white placeholder-slate-500 p-2.5"></textarea>
                            <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan tersimpan pada riwayat tracking surat.</p>
                        </div>
                    </div>

                    <!-- KONTEN BILA KEPUTUSAN = KEMBALIKAN -->
                    <div x-show="tinjauKeputusan === 'kembalikan'">
                        <!-- Pilihan Tujuan Pengembalian Berdasarkan Alur yang Sudah Dilewati (DROPDOWN) -->
                        <div class="mb-4">
                            <label for="pilih_target_kembali_dropdown" class="block text-xs font-bold text-rose-300 uppercase tracking-wider mb-1.5">
                                Pilih Tingkat Tujuan Pengembalian <span class="text-rose-400">*</span>
                            </label>
                            <p class="text-[11px] text-slate-400 mb-2">
                                Pilih tingkatan dari alur yang sudah dilalui dokumen untuk menerima instruksi perbaikan revisi:
                            </p>
                            
                            <!-- Template dropdown bila ada opsi kembali -->
                            <template x-if="tinjauOpsiKembali && tinjauOpsiKembali.length > 0">
                                <select id="pilih_target_kembali_dropdown" 
                                        name="target_kembali" 
                                        x-model="tinjauTargetKembali"
                                        :disabled="tinjauKeputusan !== 'kembalikan'"
                                        class="w-full text-xs font-semibold border border-rose-500/40 rounded-xl shadow-inner focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-slate-900 text-white p-2.5">
                                    <template x-for="(opsi, idx) in tinjauOpsiKembali" :key="opsi.key">
                                        <option :value="opsi.key" class="bg-slate-900 text-white" x-text="opsi.label + ' (' + opsi.desc + ')'"></option>
                                    </template>
                                </select>
                            </template>

                            <!-- Fallback jika array opsi kosong -->
                            <template x-if="!tinjauOpsiKembali || tinjauOpsiKembali.length === 0">
                                <select name="target_kembali" 
                                        :disabled="tinjauKeputusan !== 'kembalikan'"
                                        class="w-full text-xs font-semibold border border-rose-500/40 rounded-xl shadow-inner bg-slate-900 text-white p-2.5">
                                    <option value="penyusun" selected class="bg-slate-900 text-white" x-text="tinjauKembaliTarget || 'Penyusun'"></option>
                                </select>
                            </template>
                        </div>

                        <!-- Textarea Komentar Revisi (Wajib Diisi) -->
                        <div class="mb-3.5">
                            <label for="komentar_kembalikan" class="block text-xs font-bold text-rose-300 uppercase tracking-wider mb-1.5">
                                Alasan Pengembalian / Catatan Revisi <span class="text-rose-400">*</span>
                            </label>
                            <textarea id="komentar_kembalikan" 
                                      name="komentar" 
                                      x-model="tinjauKomentar" 
                                      :disabled="tinjauKeputusan !== 'kembalikan'"
                                      :required="tinjauKeputusan === 'kembalikan'" 
                                      rows="3" 
                                      placeholder="Contoh: Lampiran belum lengkap, format nomor surat mohon disesuaikan dengan pedoman tata naskah..." 
                                      class="w-full text-xs border border-rose-500/40 rounded-xl shadow-inner focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-slate-950/70 text-white placeholder-slate-500 p-2.5"></textarea>
                            <p class="text-[11px] text-rose-300/90 mt-1 font-medium">Catatan ini akan ditampilkan pada kolom Status dan tercatat di Tracking Surat.</p>
                        </div>

                        <!-- Attach File Lampiran Koreksi (Opsional) -->
                        <div class="mb-2 p-3.5 bg-slate-950/60 border border-white/10 rounded-xl shadow-inner">
                            <label for="lampiran_koreksi_kembalikan" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                📎 Lampirkan File Koreksi / Catatan Revisi (Opsional)
                            </label>
                            <input type="file" 
                                   id="lampiran_koreksi_kembalikan" 
                                   name="file_lampiran" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   :disabled="tinjauKeputusan !== 'kembalikan'"
                                   class="w-full text-xs text-slate-300 border border-white/15 rounded-xl p-2 bg-slate-900/90 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-500/20 file:text-rose-300 hover:file:bg-rose-500/30 transition cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1.5">
                                Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Maksimal 10MB). Unggah berkas berisi coretan atau catatan perbaikan jika ada.
                            </p>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 mt-5 border-t border-white/10">
                        <button type="button" @click="closeTinjauModal()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl text-xs transition cursor-pointer">
                            Batal
                        </button>
                        
                        <!-- Tombol Submit Teruskan / Setujui & Diarsipkan -->
                        <template x-if="tinjauKeputusan === 'teruskan'">
                            <button type="button" 
                                    @click="handleTinjauSetujui($event)"
                                    class="px-5 py-2.5 btn-modal-teruskan text-white font-bold rounded-xl text-xs transition shadow-lg flex items-center gap-1.5 cursor-pointer">
                                <span class="text-sm">✅</span>
                                <span x-text="tinjauIsFinalSigner ? ('Setujui & TTD ' + tinjauPenandatanganLabel) : 'Setujui & Teruskan Dokumen'"></span>
                            </button>
                        </template>

                        <!-- Tombol Submit Kembalikan -->
                        <template x-if="tinjauKeputusan === 'kembalikan'">
                            <button type="submit" class="px-5 py-2.5 btn-modal-kembalikan text-white font-bold rounded-xl text-xs transition shadow-lg flex items-center gap-1.5 cursor-pointer">
                                <span class="text-sm">↩️</span>
                                <span>Kembalikan Dokumen Surat</span>
                            </button>
                        </template>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL REVISI DRAFT SURAT (KHUSUS PENYUSUN) -->
        <div x-show="showEditDraftModal" 
             @click.self="closeEditDraftModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeEditDraftModal()" 
                 x-show="showEditDraftModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-100">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl shrink-0 shadow-[0_0_12px_rgba(245,158,11,0.3)]">
                            ✏️
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white" x-text="editDraftIsRevisi ? 'Perbaiki & Ajukan Ulang Draft Surat' : 'Edit / Perbaiki Dokumen Draft Surat'">Perbaiki & Ajukan Ulang Draft Surat</h3>
                            <p class="text-xs text-slate-400" x-text="editDraftIsRevisi ? 'Perbarui judul, jenis, tujuan, atau unggah file draft yang sudah direvisi.' : 'Perbaiki judul, jenis surat, atau berkas draft sebelum diperiksa oleh atasan.'">Perbarui judul, jenis, tujuan, atau unggah file draft yang sudah direvisi.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEditDraftModal()" class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Revisi Draft -->
                <form :action="editDraftActionUrl" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 bg-slate-900/40">
                    @csrf
                    
                    <!-- Catatan Revisi dari Pemeriksa -->
                    <div class="mb-4 p-3.5 bg-rose-950/40 border border-rose-500/30 rounded-xl text-xs text-rose-200" x-show="editDraftCatatan || editDraftLampiran">
                        <template x-if="editDraftCatatan">
                            <div>
                                <span class="font-bold flex items-center gap-1.5 text-rose-300">
                                    <span>💬</span> Catatan Revisi dari Pemeriksa:
                                </span>
                                <p class="mt-1.5 italic bg-slate-950/80 p-3 rounded-xl border border-rose-500/20 text-rose-100 leading-relaxed whitespace-pre-line" x-text="editDraftCatatan"></p>
                            </div>
                        </template>
                        <template x-if="editDraftLampiran">
                            <div class="mt-2.5" :class="editDraftCatatan ? 'pt-2.5 border-t border-rose-500/20' : ''">
                                <a :href="editDraftLampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 text-xs font-bold rounded-xl border border-rose-500/40 transition shadow-sm">
                                    <span>📎</span> Unduh File Koreksi / Lampiran Revisi dari Pemeriksa
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Input Judul Surat -->
                    <div class="mb-4">
                        <label for="edit_judul" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Judul Dokumen Surat <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="edit_judul" name="judul" x-model="editDraftJudul" required class="w-full text-xs border border-white/15 rounded-xl shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-950/70 text-white placeholder-slate-500 p-2.5">
                    </div>

                    <!-- Input Jenis Surat -->
                    <div class="mb-4">
                        <label for="edit_jenis_surat" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Jenis Surat <span class="text-rose-400">*</span>
                        </label>
                        <select id="edit_jenis_surat" name="jenis_surat_id" x-model="editDraftJenisId" required class="w-full text-xs border border-white/15 rounded-xl shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900 text-white p-2.5">
                            @foreach($allJenisSurat ?? [] as $jenis)
                                <option value="{{ $jenis->id }}" class="bg-slate-900 text-white">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Kasubbag yang Dituju (Jika bukan dari Unit) -->
                    <div class="mb-4" x-show="editDraftHasKasubbag">
                        <label for="edit_tujuan_kasubbag" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Kepala Subbagian (Kasubbag) yang Dituju <span class="text-rose-400">*</span>
                        </label>
                        <select id="edit_tujuan_kasubbag" name="tujuan_kasubbag" x-model="editDraftKasubbag" :required="editDraftHasKasubbag" class="w-full text-xs border border-white/15 rounded-xl shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-900 text-white p-2.5">
                            @foreach($allKasubbagRoles ?? [] as $kKey => $kLabel)
                                <option value="{{ $kKey }}" class="bg-slate-900 text-white">{{ $kLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input File Draft Baru (Opsional) -->
                    <div class="mb-5">
                        <label for="edit_file_draft" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            <span x-text="editDraftIsRevisi ? 'Unggah File Draft Hasil Revisi (PDF / Word)' : 'Ganti File Draft (Opsional, PDF / Word)'">Unggah File Draft Hasil Revisi (PDF / Word)</span>
                        </label>
                        <input type="file" id="edit_file_draft" name="file_draft" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-300 border border-white/15 rounded-xl p-2 bg-slate-900/90 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-500/20 file:text-blue-300 hover:file:bg-blue-500/30 transition cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">
                            Format yang didukung: PDF, DOC, DOCX (Maksimal 5MB). Biarkan kosong jika tidak mengubah file draft.
                        </p>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-white/10">
                        <button type="button" @click="closeEditDraftModal()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl text-xs transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 btn-aksi-blue font-bold rounded-xl text-xs transition shadow-lg flex items-center gap-1.5 cursor-pointer">
                            <span>🚀</span>
                            <span x-text="editDraftIsRevisi ? 'Simpan & Ajukan Ulang Draft' : 'Simpan Perubahan Draft'">Simpan & Ajukan Ulang Draft</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL UPLOAD FILE FINAL (TELAH DI-TTD) KHUSUS SEKRETARIS DIREKTUR -->
        <div x-show="showUploadFinalModal" 
             @click.self="closeUploadFinalModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeUploadFinalModal()" 
                 @keydown.escape.window="closeUploadFinalModal()"
                 x-show="showUploadFinalModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-100">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-teal-400 text-xl shrink-0 shadow-[0_0_12px_rgba(20,184,166,0.3)]">
                            📤
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Upload File Final (Telah Di-TTD)</h3>
                            <p class="text-xs text-slate-400">Unggah dokumen resmi ber-TTD <span x-text="uploadFinalPenandatanganLabel"></span> untuk menyelesaikan dan mengarsipkan surat.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeUploadFinalModal()" class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Upload Final -->
                <form :action="uploadFinalActionUrl" method="POST" enctype="multipart/form-data" @submit.prevent="submitUploadFinal($event)" class="p-6 overflow-y-auto flex-1 bg-slate-900/40">
                    @csrf
                    <input type="hidden" name="catatan" :value="uploadFinalCatatan">
                    
                    <!-- Info Surat -->
                    <div class="mb-4 p-4 bg-slate-950/60 rounded-xl border border-white/10 shadow-inner">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Dokumen Surat</label>
                        <p class="text-sm font-bold text-white" x-text="uploadFinalJudul"></p>
                    </div>

                    <!-- Instruksi -->
                    <div class="mb-4 p-4 bg-teal-950/40 border-l-4 border-teal-500 rounded-r-xl border-t border-r border-b border-teal-500/30 text-xs text-teal-200 shadow-sm">
                        <p class="font-extrabold flex items-center gap-1.5 text-teal-300 mb-1">
                            <span>📌</span> Instruksi Pengarsipan:
                        </p>
                        <p class="text-teal-200/90 leading-relaxed">
                            Pastikan file yang diunggah adalah dokumen yang telah <strong x-text="'ditandatangani oleh ' + uploadFinalPenandatanganLabel"></strong>, diberi stempel/cap resmi, dan berformat <strong>PDF (Maksimal 5MB)</strong>.
                        </p>
                    </div>

                    <!-- Input Nomor Surat Resmi (Wajib) -->
                    <div class="mb-4">
                        <label for="input_nomor_surat_modal" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Nomor Surat Resmi <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                📄
                            </div>
                            <input type="text" 
                                   id="input_nomor_surat_modal" 
                                   name="nomor_surat" 
                                   x-model="uploadFinalNomorSurat" 
                                   required 
                                   placeholder="Contoh: 005/PU/TU.02.01/2026" 
                                   class="w-full text-xs text-white placeholder-slate-500 border border-white/15 rounded-xl pl-10 pr-3.5 py-2.5 bg-slate-900/90 shadow-inner focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:border-teal-400 transition font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Nomor surat resmi wajib dimasukkan sesuai dengan nomor yang tertera pada dokumen surat fisik/ber-TTD.</p>
                    </div>

                    <!-- Input File Final (.pdf) -->
                    <div class="mb-5">
                        <label for="input_file_final_modal" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Pilih File Final (.pdf) <span class="text-rose-400">*</span>
                        </label>
                        <input type="file" 
                               id="input_file_final_modal" 
                               name="file_final" 
                               accept=".pdf,application/pdf" 
                               required 
                               class="w-full text-xs text-slate-300 border border-white/15 rounded-xl p-2.5 bg-slate-900/90 shadow-inner file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-teal-500/20 file:text-teal-300 hover:file:bg-teal-500/30 transition cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Dokumen ini akan menjadi arsip digital resmi dan dapat diunduh oleh pihak terkait.</p>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-white/10">
                        <button type="button" 
                                @click="simpanTtdNanti()" 
                                class="text-xs text-cyan-400 hover:text-cyan-300 hover:underline font-bold transition"
                                title="Setujui TTD sekarang dan unggah file PDF nanti">
                            ⏳ Unggah File Nanti
                        </button>
                        
                        <div class="flex items-center gap-2.5">
                            <button type="button" @click="closeUploadFinalModal()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl text-xs transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" 
                                    :disabled="isUploadingFinal" 
                                    class="px-5 py-2.5 btn-aksi-teal font-bold rounded-xl text-xs transition shadow-lg flex items-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="!isUploadingFinal">
                                    <span class="flex items-center gap-1.5">
                                        <span>📥</span>
                                        <span>Upload & Arsipkan Surat</span>
                                    </span>
                                </template>
                                <template x-if="isUploadingFinal">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="animate-spin -ml-0.5 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        <span>Mengunggah & Mengarsipkan...</span>
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL INFORMASI: DOKUMEN TELAH DIARSIPKAN -->
        <div x-show="showArsipModal" 
             @click.self="closeArsipModal(true)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeArsipModal(true)" 
                 @keydown.escape.window="closeArsipModal(true)"
                 x-show="showArsipModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-3xl border border-emerald-500/30 shadow-[0_0_50px_rgba(16,185,129,0.25)] max-w-lg w-full overflow-hidden flex flex-col text-slate-100 relative">
                
                <!-- Top Accent Bar (Gradient Hijau-Teal) -->
                <div class="h-1.5 w-full bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-500"></div>

                <!-- Tombol Silang Kanan Atas -->
                <button type="button" 
                        @click="closeArsipModal(true)" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-white hover:bg-white/10 p-2 rounded-xl transition cursor-pointer z-10" 
                        title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="p-6 sm:p-8 flex flex-col items-center text-center">
                    
                    <!-- Badge Icon Arsip -->
                    <div class="w-20 h-20 rounded-3xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-4xl mb-4 shadow-[0_0_25px_rgba(16,185,129,0.35)] text-emerald-300">
                        <span>🗄️</span>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-xs font-bold tracking-wider uppercase mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Dokumen Resmi Telah Diarsipkan</span>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-extrabold text-white mb-2">
                        Dokumen Berhasil Diarsipkan!
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6">
                        Seluruh rangkaian tinjauan dan tanda tangan telah selesai. Berkas final dokumen resmi telah berhasil diarsipkan ke dalam sistem <span class="text-emerald-400 font-bold">SURATKU</span>.
                    </p>

                    <!-- Card Rincian Dokumen yang Diarsipkan -->
                    <div class="w-full bg-slate-950/70 border border-white/10 rounded-2xl p-4 text-left mb-6 space-y-3 shadow-inner">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Judul Dokumen Surat</span>
                            <p class="text-sm font-bold text-white leading-snug" x-text="arsipData?.judul || '-'"></p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-white/5">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Nomor Surat Resmi</span>
                                <div class="flex items-center gap-1.5 text-xs font-mono font-bold text-emerald-400">
                                    <span>📄</span>
                                    <span x-text="arsipData?.nomor_surat || '-'"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Status Dokumen</span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">
                                    <span>✅</span> DIARSIPKAN
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-white/5">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Ditandatangani Oleh</span>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-200">
                                    <span>✍️</span>
                                    <span x-text="arsipData?.penandatangan || 'Pejabat Penandatangan'"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Waktu Pengarsipan</span>
                                <div class="text-xs text-slate-300" x-text="arsipData?.waktu || '-'"></div>
                            </div>
                        </div>

                        <div class="pt-2.5 border-t border-white/5">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Diunggah / Diarsipkan Oleh</span>
                            <div class="text-xs text-slate-300 truncate" x-text="arsipData?.diarsipkan_oleh || '-'"></div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="w-full p-3 rounded-xl bg-blue-950/40 border border-blue-500/30 text-[11px] text-blue-200 text-left mb-6 flex items-start gap-2.5">
                        <span class="text-blue-400 text-base leading-none mt-0.5">💡</span>
                        <p class="leading-relaxed">
                            Dokumen ini dapat ditemukan di tab <strong>"Riwayat Sudah Diparaf"</strong>. Pembuat surat dan pihak berwenang dapat melihat riwayat perjalanan serta mengunduh dokumen final kapan saja.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full">
                        <!-- Tombol Pratinjau File Final -->
                        <template x-if="arsipData?.file_url">
                            <button type="button" 
                                    @click="const fileUrl = arsipData.file_url; const title = arsipData.judul; closeArsipModal(false); openPreview(fileUrl, title, 'pdf');"
                                    class="w-full sm:flex-1 py-3 px-4 btn-aksi-teal font-bold rounded-xl text-xs transition shadow-lg flex items-center justify-center gap-2 cursor-pointer">
                                <span>👁️</span>
                                <span>Pratinjau File Final</span>
                            </button>
                        </template>

                        <!-- Tombol Tutup -->
                        <button type="button" 
                                @click="closeArsipModal(true)" 
                                class="w-full sm:w-auto px-6 py-3 bg-slate-800/90 hover:bg-slate-700/90 text-slate-200 hover:text-white border border-white/10 font-bold rounded-xl text-xs transition cursor-pointer">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL EDIT DOKUMEN ARSIP & NOMOR SURAT -->
        <div x-show="showEditArsipModal" 
             @click.self="closeEditArsipModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeEditArsipModal()" 
                 @keydown.escape.window="closeEditArsipModal()"
                 x-show="showEditArsipModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-100">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl shrink-0 shadow-[0_0_12px_rgba(245,158,11,0.3)]">
                            📝
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">
                                <span x-show="editArsipCanChangeFile">Edit Berkas & Nomor Surat</span>
                                <span x-show="!editArsipCanChangeFile">Edit / Berikan Nomor Surat</span>
                            </h3>
                            <p class="text-xs text-slate-400">Kelola dan perbarui data arsip dokumen resmi pada sistem.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEditArsipModal()" class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Edit Arsip -->
                <form :action="editArsipActionUrl" method="POST" enctype="multipart/form-data" @submit="isUpdatingArsip = true" class="p-6 overflow-y-auto flex-1 bg-slate-900/40">
                    @csrf
                    
                    <!-- Info Surat Ringkas -->
                    <div class="mb-4 p-4 bg-slate-950/60 rounded-xl border border-white/10 shadow-inner space-y-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Judul Dokumen Surat</label>
                            <p class="text-sm font-bold text-white leading-snug" x-text="editArsipJudul"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-white/5 text-xs">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Penandatangan</span>
                                <span class="text-slate-200 font-medium" x-text="editArsipPenandatanganLabel"></span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Pengunggah Dokumen</span>
                                <span class="text-slate-200 font-medium" x-text="editArsipPengunggahName"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Input Nomor Surat Resmi (Wajib) -->
                    <div class="mb-4">
                        <label for="input_edit_nomor_surat" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Nomor Surat Resmi <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                                📄
                            </div>
                            <input type="text" 
                                   id="input_edit_nomor_surat" 
                                   name="nomor_surat" 
                                   x-model="editArsipNomorSurat" 
                                   required 
                                   placeholder="Contoh: 005/PU/TU.02.01/2026" 
                                   class="w-full text-xs text-white placeholder-slate-500 border border-white/15 rounded-xl pl-10 pr-3.5 py-2.5 bg-slate-900/90 shadow-inner focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-400 transition font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Dapat diisi atau disunting oleh akun pengunggah, Sekretaris Direktur, maupun Super Admin.
                        </p>
                    </div>

                    <!-- Input Ganti Berkas Final PDF (Khusus Pengunggah / Super Admin) -->
                    <template x-if="editArsipCanChangeFile">
                        <div class="mb-4">
                            <label for="input_edit_file_final" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                Ganti Berkas Final PDF <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                            </label>
                            <input type="file" 
                                   id="input_edit_file_final" 
                                   name="file_final" 
                                   accept=".pdf,application/pdf" 
                                   class="w-full text-xs text-slate-300 border border-white/15 rounded-xl p-2.5 bg-slate-900/90 shadow-inner file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 transition cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1">
                                Kosongkan jika hanya ingin mengubah Nomor Surat tanpa mengganti file PDF.
                            </p>
                        </div>
                    </template>

                    <!-- Pemberitahuan Hak Akses Berkas Final jika Bukan Pengunggah (misal Sekretaris Direktur) -->
                    <template x-if="!editArsipCanChangeFile">
                        <div class="mb-4 p-3.5 rounded-xl bg-blue-950/40 border border-blue-500/30 text-xs text-blue-200 shadow-sm flex items-start gap-2.5">
                            <span class="text-base text-blue-400 leading-none mt-0.5">ℹ️</span>
                            <div class="leading-relaxed">
                                <span class="font-bold text-blue-300">Hak Akses Berkas Fisik:</span>
                                Anda dapat mengisi atau merevisi Nomor Surat. Penggantian berkas PDF dokumen hanya diizinkan bagi akun yang melakukan pengarsipan (<span class="font-bold text-white" x-text="editArsipPengunggahName"></span>) atau Super Admin.
                            </div>
                        </div>
                    </template>

                    <!-- Link Pratinjau Berkas Saat Ini -->
                    <template x-if="editArsipFileFinalUrl">
                        <div class="mb-4 flex items-center justify-between p-3 rounded-xl bg-slate-950/50 border border-white/10 text-xs">
                            <div class="flex items-center gap-2 text-slate-300 truncate">
                                <span>📎</span>
                                <span class="truncate">Berkas PDF saat ini telah tersimpan</span>
                            </div>
                            <a :href="editArsipFileFinalUrl" target="_blank" class="px-2.5 py-1 rounded-lg bg-teal-500/20 text-teal-300 hover:bg-teal-500/30 border border-teal-500/30 font-bold transition whitespace-nowrap inline-flex items-center gap-1">
                                <span>👁️</span> Pratinjau
                            </a>
                        </div>
                    </template>

                    <!-- Catatan Perubahan (Opsional) -->
                    <div class="mb-5">
                        <label for="input_catatan_edit" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                            Alasan / Catatan Perubahan <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="input_catatan_edit" 
                               name="catatan_edit" 
                               placeholder="Contoh: Penyesuaian nomor surat resmi dari TU..." 
                               class="w-full text-xs text-white placeholder-slate-500 border border-white/15 rounded-xl px-3.5 py-2.5 bg-slate-900/90 shadow-inner focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-400 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan tercatat dalam log riwayat perjalanan surat.</p>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-white/10">
                        <button type="button" @click="closeEditArsipModal()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl text-xs transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                :disabled="isUpdatingArsip" 
                                class="px-5 py-2.5 btn-aksi-amber font-bold rounded-xl text-xs transition shadow-lg flex items-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="!isUpdatingArsip">
                                <span class="flex items-center gap-1.5">
                                    <span>💾</span>
                                    <span>Simpan Perubahan</span>
                                </span>
                            </template>
                            <template x-if="isUpdatingArsip">
                                <span class="flex items-center gap-1.5">
                                    <svg class="animate-spin -ml-0.5 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Menyimpan...</span>
                                </span>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL INFORMASI LENGKAP & ALUR SURAT -->
        <div x-show="showInfoModal" 
             @click.self="closeInfoModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:px-6 lg:px-8"
             style="display: none;">
             
            <div @click.away="closeInfoModal()" 
                 @keydown.escape.window="closeInfoModal()"
                 x-show="showInfoModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="glass-panel bg-slate-900/95 backdrop-blur-2xl rounded-2xl border border-white/15 shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-100">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 bg-slate-950/80 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-xl shrink-0 shadow-[0_0_12px_rgba(6,182,212,0.3)]">
                            ℹ️
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Informasi & Rincian Surat</h3>
                            <p class="text-xs text-slate-400">Rincian identitas, alur tujuan, penandatangan, dan riwayat arsip.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeInfoModal()" class="text-slate-400 hover:text-white hover:bg-white/10 p-1.5 rounded-xl transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="p-6 overflow-y-auto flex-1 bg-slate-900/40 space-y-4">
                    
                    <!-- 1. Identitas Surat -->
                    <div class="p-4 bg-slate-950/60 rounded-xl border border-white/10 shadow-inner space-y-3">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">Judul Dokumen Surat</span>
                            <h4 class="text-sm sm:text-base font-extrabold text-white leading-snug" x-text="infoJudul"></h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-white/5">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Nomor Surat Resmi</span>
                                <div class="flex items-center gap-1.5 text-xs font-mono font-bold text-emerald-400">
                                    <span>📄</span>
                                    <span x-text="infoNomorSurat || 'Belum Ada (Draft/Proses)'"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Jenis Dokumen Surat</span>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-indigo-300">
                                    <span>📑</span>
                                    <span x-text="infoJenis"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-white/5">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Pembuat / Penyusun</span>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-200">
                                    <span>👤</span>
                                    <span x-text="infoPembuat"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Waktu Pengajuan</span>
                                <div class="flex items-center gap-1.5 text-xs text-slate-300">
                                    <span>📅</span>
                                    <span x-text="infoTanggal"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Alur Disposisi & Pemeriksaan -->
                    <div class="p-4 bg-slate-950/60 rounded-xl border border-white/10 shadow-inner">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-2.5">
                            🛤️ Alur Disposisi & Pemeriksaan:
                        </span>
                        <div class="space-y-2">
                            <!-- Unit / Prodi / Lab -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-white/5 text-xs">
                                <span class="text-slate-400 flex items-center gap-1.5">
                                    <span>🏢</span> Tingkat Unit / Prodi / Lab
                                </span>
                                <span class="font-bold" :class="infoUnit && infoUnit !== '-' ? 'text-emerald-300' : 'text-slate-500'" x-text="infoUnit && infoUnit !== '-' ? infoUnit : 'Langsung ke Kasubbag (Tanpa Unit)'"></span>
                            </div>

                            <!-- Kasubbag -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-white/5 text-xs">
                                <span class="text-slate-400 flex items-center gap-1.5">
                                    <span>📌</span> Tingkat Kasubbag
                                </span>
                                <span class="font-bold text-amber-300" x-text="infoKasubbag"></span>
                            </div>

                            <!-- Wadir -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-white/5 text-xs">
                                <span class="text-slate-400 flex items-center gap-1.5">
                                    <span>🎯</span> Tingkat Wakil Direktur
                                </span>
                                <span class="font-bold" :class="infoWadir && infoWadir !== '-' ? 'text-purple-300' : 'text-slate-500'" x-text="infoWadir && infoWadir !== '-' ? infoWadir : 'Bukan Alur Wadir'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Tanda Tangan & Pengesahan -->
                    <div class="p-4 bg-slate-950/60 rounded-xl border border-white/10 shadow-inner space-y-2.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">
                            ✍️ Pengesahan & Tanda Tangan:
                        </span>
                        
                        <!-- Target Penandatangan (Titik Berhenti) -->
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-white/5 text-xs">
                            <span class="text-slate-400 flex items-center gap-1.5">
                                <span>🎯</span> Titik Berhenti Penandatangan
                            </span>
                            <span class="font-bold text-cyan-300" x-text="infoPenandatangan"></span>
                        </div>

                        <!-- Status Penandatanganan Selesai -->
                        <template x-if="infoDitandatanganiOleh">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-teal-500/15 border border-teal-500/30 text-xs">
                                <span class="text-teal-300 font-bold flex items-center gap-1.5">
                                    <span>✍️</span> Telah Ditandatangani Oleh
                                </span>
                                <span class="font-extrabold text-white" x-text="infoDitandatanganiOleh"></span>
                            </div>
                        </template>

                        <!-- Akun Pengunggah Arsip Dokumen -->
                        <template x-if="infoPengunggahArsip">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-xs">
                                <span class="text-emerald-300 font-bold flex items-center gap-1.5">
                                    <span>🗄️</span> Berkas Final Diarsipkan Oleh
                                </span>
                                <span class="font-extrabold text-white" x-text="infoPengunggahArsip"></span>
                            </div>
                        </template>
                    </div>

                    <!-- 4. Status Dokumen Terkini -->
                    <div class="p-3.5 rounded-xl bg-slate-950/70 border border-white/10 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Dokumen Saat Ini:</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm" :class="infoStatusBadge" x-text="infoStatus"></span>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="px-6 py-4 bg-slate-950/80 border-t border-white/10 flex flex-wrap items-center justify-between gap-2.5 flex-shrink-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Tombol Pratinjau Draft -->
                        <template x-if="infoDraftUrl">
                            <button type="button" 
                                    @click="openPreview(infoDraftUrl, infoJudul, infoDraftType)"
                                    class="px-3.5 py-2 btn-aksi-blue font-bold rounded-xl text-xs transition shadow-md flex items-center gap-1.5 cursor-pointer">
                                <span>👁️</span> Pratinjau Draft
                            </button>
                        </template>

                        <!-- Tombol Pratinjau / Unduh Final -->
                        <template x-if="infoFinalUrl">
                            <button type="button" 
                                    @click="openPreview(infoFinalUrl, infoJudul + ' (Final)', 'pdf')"
                                    class="px-3.5 py-2 btn-aksi-teal font-bold rounded-xl text-xs transition shadow-md flex items-center gap-1.5 cursor-pointer">
                                <span>📥</span> Berkas Final
                            </button>
                        </template>

                        <!-- Tombol Buka Tracking Lengkap -->
                        <button type="button" 
                                @click="openTrackingFromInfo()" 
                                class="px-3.5 py-2 btn-aksi-indigo font-bold rounded-xl text-xs transition shadow-md flex items-center gap-1.5 cursor-pointer">
                            <span>🔍</span> Riwayat Tracking
                        </button>
                    </div>

                    <button type="button" 
                            @click="closeInfoModal()" 
                            class="px-5 py-2 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 hover:text-white border border-white/10 font-bold rounded-xl text-xs transition cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
            </div>
        </div>
    </div>
</x-app-layout>