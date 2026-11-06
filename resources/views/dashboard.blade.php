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

        /* SOFT CARTOON NEO-BRUTALIST STYLES FOR ACTION & DECISION BUTTONS */
        .card-radio-teruskan-active {
            background-color: #ECFDF5 !important;
            border: 2px solid #059669 !important;
            box-shadow: 3px 3px 0px 0px #059669 !important;
            border-radius: 1rem !important;
        }

        .card-radio-kembalikan-active {
            background-color: #FFF1F2 !important;
            border: 2px solid #E11D48 !important;
            box-shadow: 3px 3px 0px 0px #E11D48 !important;
            border-radius: 1rem !important;
        }

        .btn-modal-teruskan {
            background-color: #10B981 !important;
            color: #FFFFFF !important;
            border: 2px solid #065F46 !important;
            box-shadow: 3px 3px 0px 0px #065F46 !important;
            font-weight: 800 !important;
            border-radius: 0.875rem !important;
            transition: all 0.1s ease !important;
        }
        .btn-modal-teruskan:hover {
            transform: translate(-1.5px, -1.5px) !important;
            box-shadow: 4.5px 4.5px 0px 0px #065F46 !important;
            background-color: #059669 !important;
        }
        .btn-modal-teruskan:active {
            transform: translate(2px, 2px) !important;
            box-shadow: 1px 1px 0px 0px #065F46 !important;
        }

        .btn-modal-kembalikan {
            background-color: #E11D48 !important;
            color: #FFFFFF !important;
            border: 2px solid #9F1239 !important;
            box-shadow: 3px 3px 0px 0px #9F1239 !important;
            font-weight: 800 !important;
            border-radius: 0.875rem !important;
            transition: all 0.1s ease !important;
        }
        .btn-modal-kembalikan:hover {
            transform: translate(-1.5px, -1.5px) !important;
            box-shadow: 4.5px 4.5px 0px 0px #9F1239 !important;
            background-color: #BE123C !important;
        }
        .btn-modal-kembalikan:active {
            transform: translate(2px, 2px) !important;
            box-shadow: 1px 1px 0px 0px #9F1239 !important;
        }

        .card-radio-ditolak-active {
            background-color: #FFF1F2 !important;
            border: 2px solid #BE123C !important;
            box-shadow: 3px 3px 0px 0px #BE123C !important;
            border-radius: 1rem !important;
        }

        .btn-modal-ditolak {
            background-color: #BE123C !important;
            color: #FFFFFF !important;
            border: 2px solid #881337 !important;
            box-shadow: 3px 3px 0px 0px #881337 !important;
            font-weight: 800 !important;
            border-radius: 0.875rem !important;
            transition: all 0.1s ease !important;
        }
        .btn-modal-ditolak:hover {
            transform: translate(-1.5px, -1.5px) !important;
            box-shadow: 4.5px 4.5px 0px 0px #881337 !important;
            background-color: #9F1239 !important;
        }
        .btn-modal-ditolak:active {
            transform: translate(2px, 2px) !important;
            box-shadow: 1px 1px 0px 0px #881337 !important;
        }

        .btn-aksi-rose,
        .btn-aksi-batal {
            background-color: #EC4899 !important;
            color: #FFFFFF !important;
            border: 2px solid #9D174D !important;
            box-shadow: 2.5px 2.5px 0px 0px #9D174D !important;
            font-weight: 800 !important;
            border-radius: 0.75rem !important;
            transition: all 0.12s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer !important;
        }
        .btn-aksi-rose:hover,
        .btn-aksi-batal:hover {
            background-color: #DB2777 !important;
            color: #FFFFFF !important;
            transform: translate(-1.5px, -1.5px) !important;
            box-shadow: 3.5px 3.5px 0px 0px #9D174D !important;
        }
        .btn-aksi-rose:active,
        .btn-aksi-batal:active {
            transform: translate(2px, 2px) !important;
            box-shadow: 0px 0px 0px 0px #9D174D !important;
            color: #FFFFFF !important;
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
                    let fileType = (type || '').toLowerCase();
                    if (!fileType && url) {
                        const cleanUrl = url.split('#')[0].split('?')[0];
                        fileType = cleanUrl.split('.').pop().toLowerCase();
                    }
                    if (url && url.toLowerCase().includes('.pdf')) {
                        fileType = 'pdf';
                    }
                    this.previewType = fileType;
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
                infoIsRahasia: false,
                infoIsSpt: false,
                infoPpk: '',
                infoRahasiaTujuan: '',
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
                    this.infoIsRahasia = el.dataset.isRahasia === '1';
                    this.infoIsSpt = el.dataset.isSpt === '1';
                    this.infoPpk = el.dataset.ppk || '-';
                    this.infoRahasiaTujuan = el.dataset.rahasiaTujuan || '-';
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
                    this.infoIsRahasia = false;
                    this.infoIsSpt = false;
                    this.infoPpk = '';
                    this.infoRahasiaTujuan = '';
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
                    return aksi.includes('DIKEMBALIKAN') || aksi.includes('PERLU REVISI') || aksi.includes('BALIK DULU') || aksi.includes('REVISI') || aksi.includes('TOLAK') || aksi.includes('BATAL');
                },
                isStatusDikembalikan(status) {
                    return (status || '').toUpperCase().includes('DIKEMBALIKAN');
                },
                isItemDitolak(item) {
                    const aksi = (item && item.aksi) ? item.aksi.toUpperCase() : '';
                    return aksi.includes('DITOLAK') || aksi.includes('TOLAK');
                },
                isStatusDitolak(status) {
                    return (status || '').toUpperCase().includes('DITOLAK');
                },
                isItemDibatalkan(item) {
                    const aksi = (item && item.aksi) ? item.aksi.toUpperCase() : '';
                    return aksi.includes('DIBATALKAN') || aksi.includes('BATAL') || aksi.includes('BATALKAN PROSESNYA');
                },
                isStatusDibatalkan(status) {
                    return (status || '').toUpperCase().includes('DIBATALKAN');
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
                tinjauTujuanUnit: '',
                tinjauTujuanPpk: '',
                tinjauTujuanRahasiaRole: '',
                tinjauIsSpt: false,
                tinjauIsRahasia: false,
                tinjauIsRahasiaFinal: '0',
                tinjauKomentar: '',
                tinjauIsFinalSigner: false,
                tinjauPenandatanganLabel: '',
                openTinjauModal(id, judul, actionUrl, role, kembaliTarget, draftUrl, catatanRevisi = '', lampiranRevisi = '', opsiKembali = [], defaultWadir = '', isFinalSigner = false, penandatanganLabel = '', defaultKasubbag = '', draftType = 'pdf', isSpt = false, isRahasia = false, defaultPpk = '', defaultRahasiaRole = '', defaultUnit = '') {
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
                    this.tinjauTujuanUnit = defaultUnit || '';
                    this.tinjauTujuanPpk = defaultPpk || '';
                    this.tinjauTujuanRahasiaRole = defaultRahasiaRole || '';
                    this.tinjauIsSpt = !!isSpt;
                    this.tinjauIsRahasia = !!isRahasia;
                    this.tinjauIsRahasiaFinal = '0';
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
                        el.dataset.draftType || 'pdf',
                        el.dataset.isSpt === '1',
                        el.dataset.isRahasia === '1',
                        el.dataset.tujuanPpk || '',
                        el.dataset.tujuanRahasiaRole || '',
                        el.dataset.tujuanUnit || ''
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
                    this.tinjauTujuanUnit = '';
                    this.tinjauTujuanPpk = '';
                    this.tinjauTujuanRahasiaRole = '';
                    this.tinjauIsSpt = false;
                    this.tinjauIsRahasia = false;
                    this.tinjauIsRahasiaFinal = '0';
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
            
            <!-- Deretan Ucapan Selamat Datang & Tombol Aksi -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-xl sm:text-2xl font-cartoon font-bold text-[#003366] tracking-tight leading-tight">
                        Selamat datang kembali, <span class="text-[#002244]">{{ Auth::user()->name }}</span> <span class="text-slate-600 font-medium">({{ Auth::user()->role_label }}).</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 flex items-center gap-2 flex-wrap font-medium">
                        <span>Portal Rekapitulasi & Tracking Surat Keluar Politeknik Pekerjaan Umum</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1.5px_1.5px_0px_#002244]">
                            Versi Beta
                        </span>
                    </p>
                </div>

                @if(in_array(Auth::user()->role, ['penyusun', 'super_admin', 'superadmin']))
                    <div class="flex items-center shrink-0">
                        <a href="{{ route('surat.create') }}" class="btn-pop-gold inline-flex items-center justify-center gap-2 font-cartoon py-2.5 px-5 rounded-2xl text-xs sm:text-sm transition-all duration-200 active:scale-95 whitespace-nowrap cursor-pointer">
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
                <div class="cartoon-card bg-emerald-50 border-2 border-emerald-600 text-emerald-950 p-4 mb-6 rounded-2xl shadow-[3px_3px_0px_#059669] flex items-center gap-2.5">
                    <span class="text-xl">✅</span>
                    <p class="font-bold text-xs sm:text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Notifikasi Error -->
            @if (session('error'))
                <div class="cartoon-card bg-rose-50 border-2 border-rose-600 text-rose-950 p-4 mb-6 rounded-2xl shadow-[3px_3px_0px_#E11D48] flex items-center gap-2.5">
                    <span class="text-xl">⛔</span>
                    <p class="font-bold text-xs sm:text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <!-- KARTU STATISTIK WIDGET (INTERAKTIF & FILTERABLE - SOFT CARTOON PU NEO-BRUTALIST) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Kartu 1: Total Surat (Semua Surat Tanpa Filter) -->
                @php
                    $isTabSemuaActive = in_array(($activeTab ?? 'semua'), ['semua', 'all']);
                @endphp
                <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'semua'])) }}" 
                   class="cartoon-card rounded-3xl p-5 sm:p-6 transition-all duration-200 group block cursor-pointer {{ $isTabSemuaActive ? 'bg-blue-50/90 border-4 border-[#003366] shadow-[6px_6px_0px_#003366] ring-2 ring-[#003366] -translate-y-1' : 'bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] hover:-translate-y-1 hover:shadow-[6px_6px_0px_#003366] hover:bg-blue-50/40' }}"
                   title="Klik untuk menampilkan semua surat tanpa filter">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-[#003366] border-2 border-[#003366] shadow-[2.5px_2.5px_0px_#003366] flex items-center justify-center mr-4 group-hover:scale-105 transition duration-200 shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-cartoon font-bold uppercase tracking-wider text-slate-500">Total Surat</p>
                                <p class="text-3xl font-cartoon font-extrabold text-[#003366] mt-0.5">{{ $statTotal }}</p>
                            </div>
                        </div>
                        @if($isTabSemuaActive)
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-[#003366] text-white border border-[#003366] shadow-[1px_1px_0px_#002244]">
                                    Aktif
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-200/80 flex items-center justify-between text-[11px] font-cartoon">
                        <span class="text-slate-500 font-semibold">Tampilkan semua dokumen</span>
                        <span class="{{ $isTabSemuaActive ? 'text-[#003366] font-black' : 'text-slate-400 group-hover:text-[#003366] font-bold' }}">
                            {{ $isTabSemuaActive ? '● Sedang Tampil' : 'Klik untuk lihat →' }}
                        </span>
                    </div>
                </a>

                <!-- Kartu 2: Menunggu Tindakan / Butuh Tindakan -->
                @php
                    $isTabAntreanActive = in_array(($activeTab ?? ''), ['antrean', 'butuh_tindakan', 'proses']);
                @endphp
                <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'antrean'])) }}" 
                   class="cartoon-card rounded-3xl p-5 sm:p-6 transition-all duration-200 group block cursor-pointer {{ $isTabAntreanActive ? 'bg-amber-100/90 border-4 border-[#003366] shadow-[6px_6px_0px_#003366] ring-2 ring-[#F5B800] -translate-y-1' : 'bg-amber-50 border-2 border-[#003366] shadow-[4px_4px_0px_#003366] hover:-translate-y-1 hover:shadow-[6px_6px_0px_#003366] hover:bg-amber-100/60' }}"
                   title="Klik untuk menampilkan surat yang butuh tindakan">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 rounded-2xl bg-[#F5B800] text-[#002244] border-2 border-[#003366] shadow-[2.5px_2.5px_0px_#003366] flex items-center justify-center mr-4 group-hover:scale-105 transition duration-200 shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-cartoon font-bold uppercase tracking-wider text-amber-900">Butuh Tindakan</p>
                                <p class="text-3xl font-cartoon font-extrabold text-[#002244] mt-0.5">{{ $statProses }}</p>
                            </div>
                        </div>
                        @if($isTabAntreanActive)
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-[#F5B800] text-[#002244] border border-[#002244] shadow-[1px_1px_0px_#002244]">
                                    Aktif
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-amber-200/80 flex items-center justify-between text-[11px] font-cartoon">
                        <span class="text-amber-800 font-semibold">Tampilkan butuh tindakan</span>
                        <span class="{{ $isTabAntreanActive ? 'text-amber-950 font-black' : 'text-amber-700/70 group-hover:text-amber-950 font-bold' }}">
                            {{ $isTabAntreanActive ? '● Sedang Tampil' : 'Klik untuk lihat →' }}
                        </span>
                    </div>
                </a>

                <!-- Kartu 3: Selesai / Diarsipkan -->
                @php
                    $isTabSudahParafActive = in_array(($activeTab ?? ''), ['sudah_paraf', 'diarsipkan']);
                @endphp
                <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'sudah_paraf'])) }}" 
                   class="cartoon-card rounded-3xl p-5 sm:p-6 transition-all duration-200 group block cursor-pointer {{ $isTabSudahParafActive ? 'bg-emerald-100/90 border-4 border-[#003366] shadow-[6px_6px_0px_#003366] ring-2 ring-emerald-600 -translate-y-1' : 'bg-emerald-50 border-2 border-[#003366] shadow-[4px_4px_0px_#003366] hover:-translate-y-1 hover:shadow-[6px_6px_0px_#003366] hover:bg-emerald-100/60' }}"
                   title="Klik untuk menampilkan surat yang telah diarsipkan">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-200 text-emerald-900 border-2 border-[#003366] shadow-[2.5px_2.5px_0px_#003366] flex items-center justify-center mr-4 group-hover:scale-105 transition duration-200 shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-cartoon font-bold uppercase tracking-wider text-emerald-800">Surat Diarsipkan</p>
                                <p class="text-3xl font-cartoon font-extrabold text-emerald-950 mt-0.5">{{ $statSelesai }}</p>
                            </div>
                        </div>
                        @if($isTabSudahParafActive)
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white border border-emerald-800 shadow-[1px_1px_0px_#002244]">
                                    Aktif
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-emerald-200/80 flex items-center justify-between text-[11px] font-cartoon">
                        <span class="text-emerald-800 font-semibold">Tampilkan arsip resmi</span>
                        <span class="{{ $isTabSudahParafActive ? 'text-emerald-950 font-black' : 'text-emerald-700/70 group-hover:text-emerald-950 font-bold' }}">
                            {{ $isTabSudahParafActive ? '● Sedang Tampil' : 'Klik untuk lihat →' }}
                        </span>
                    </div>
                </a>
            </div>
            <!-- AKHIR KARTU STATISTIK -->
            <!-- AKHIR KARTU STATISTIK -->

            <!-- Bagian Judul Halaman -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                <div>
                    <h3 class="text-xl font-cartoon font-bold text-[#003366]">
                        {{ in_array(Auth::user()->role, ['super_admin', 'superadmin']) ? __('Daftar Seluruh Surat di Sistem') : __('Daftar Surat Keluar') }}
                    </h3>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">
                        Menampilkan rekapitulasi dokumen surat beserta status dialog alur dan riwayat paraf.
                    </p>
                </div>
            </div>

            <!-- Toolbar Kontrol: Tampilkan di Paling Kiri & Kolom Cari di Paling Kanan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5 items-center">
                
                <!-- 1. Kolom Tampilkan -->
                <div class="flex items-center justify-start">
                    <form action="{{ route('dashboard') }}" method="GET" id="perPageForm" class="bg-white border-2 border-[#003366] shadow-[2.5px_2.5px_0px_#003366] flex items-center gap-2 text-xs text-slate-800 rounded-xl px-3.5 py-2">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        <label for="per_page_select" class="font-cartoon font-bold whitespace-nowrap text-[#003366]">Tampilkan:</label>
                        <select id="per_page_select" name="per_page" onchange="document.getElementById('perPageForm').submit()" class="text-xs font-bold border-none p-0 pr-6 text-slate-900 focus:ring-0 bg-transparent cursor-pointer">
                            <option value="5" {{ ($perPage ?? 10) == 5 ? 'selected' : '' }}>5 surat</option>
                            <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 surat</option>
                            <option value="20" {{ ($perPage ?? 10) == 20 ? 'selected' : '' }}>20 surat</option>
                            <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 surat</option>
                            <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100 surat</option>
                        </select>
                    </form>
                </div>

                <!-- 2. Kolom Tengah (Spacer) -->
                <div class="hidden md:block"></div>

                <!-- 3. Kolom Cari Surat -->
                <div class="w-full">
                    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center w-full shadow-[2.5px_2.5px_0px_#003366] rounded-xl overflow-hidden border-2 border-[#003366] bg-white">
                        <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
                        @if(request('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul surat..." class="text-xs border-none py-2 px-3.5 w-full focus:ring-0 bg-transparent text-slate-900 placeholder-slate-400 font-semibold">
                            @if(request('search'))
                                <a href="{{ route('dashboard', array_filter(['per_page' => $perPage ?? 10, 'tab' => request('tab')])) }}" title="Hapus Pencarian" class="absolute right-2.5 top-2 text-slate-400 hover:text-slate-800 text-xs font-bold">&times;</a>
                            @endif
                        </div>
                        <button type="submit" class="btn-pop-navy font-cartoon py-2 px-4 text-xs transition flex items-center whitespace-nowrap cursor-pointer">
                            <span class="mr-1">🔍</span> Cari
                        </button>
                    </form>
                </div>
            </div>

            <!-- TAB NAVIGASI HAK AKSES -->
            @if(in_array(Auth::user()->role, ['super_admin', 'superadmin']))
                <!-- TAB KHUSUS SUPER ADMIN: Tampilkan Semua Surat (Default), Sedang Diproses, dan Arsip -->
                <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1">
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'semua'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'semua') === 'semua' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">📂</span> Semua Surat (Sistem)
                        <span class="ml-2 px-2 py-0.5 text-[10px] font-black rounded-full bg-[#F5B800] text-[#002244] border border-[#002244]">
                            {{ $countSemua ?? 0 }}
                        </span>
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'antrean'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'semua') === 'antrean' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">⏳</span> Sedang Diproses
                        @if(($countAntrean ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-amber-400 text-slate-900 border border-slate-900 shadow-xs">
                                {{ $countAntrean }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'sudah_paraf'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'semua') === 'sudah_paraf' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">🗄️</span> Selesai / Diarsipkan
                        @if(($countSudahParaf ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-emerald-500 text-white shadow-xs">
                                {{ $countSudahParaf }}
                            </span>
                        @endif
                    </a>
                </div>
            @elseif(Auth::user()->role === 'penyusun')
                <!-- TAB KHUSUS PENYUSUN: Semua Surat (Default), Butuh Tindakan, dan Arsip -->
                <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1">
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'semua'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'semua') === 'semua' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">📂</span> Semua Surat
                        <span class="ml-2 px-2 py-0.5 text-[10px] font-black rounded-full bg-[#F5B800] text-[#002244] border border-[#002244]">
                            {{ $statTotal ?? 0 }}
                        </span>
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'antrean'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'semua') === 'antrean' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">⏳</span> Butuh Tindakan
                        @if(($statProses ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-amber-400 text-slate-900 border border-slate-900 shadow-xs">
                                {{ $statProses }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'sudah_paraf'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'semua') === 'sudah_paraf' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">🗄️</span> Surat Diarsipkan
                        @if(($statSelesai ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-emerald-500 text-white shadow-xs">
                                {{ $statSelesai }}
                            </span>
                        @endif
                    </a>
                </div>
            @else
                <!-- TAB NAVIGASI PEMERIKSA (Kasubbag, Sek Kabag, Sek Wadir, Sek Direktur, Unit) -->
                <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1">
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'antrean'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'antrean') === 'antrean' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">⏳</span> Menunggu Tindakan
                        @if(($countAntrean ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-amber-400 text-slate-900 border border-slate-900 shadow-xs">
                                {{ $countAntrean }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'sudah_paraf'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'antrean') === 'sudah_paraf' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">✅</span> Riwayat Sudah Diparaf
                        @if(($countSudahParaf ?? 0) > 0)
                            <span class="ml-2 px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-emerald-500 text-white shadow-xs">
                                {{ $countSudahParaf }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard', array_merge(request()->except(['page']), ['tab' => 'semua'])) }}" 
                       class="inline-flex items-center py-2 px-4 text-xs font-cartoon font-bold rounded-xl transition-all border-2 {{ ($activeTab ?? 'antrean') === 'semua' ? 'bg-[#003366] text-white border-[#003366] shadow-[2.5px_2.5px_0px_#F5B800]' : 'bg-white text-slate-700 border-[#003366] hover:bg-slate-50' }}">
                        <span class="mr-1.5">📂</span> Semua Surat Masuk
                        <span class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700 border border-slate-300">
                            {{ $countSemua ?? 0 }}
                        </span>
                    </a>
                </div>
            @endif

            <!-- Bagian Tabel Data -->
            <div class="cartoon-table-card rounded-3xl bg-white overflow-hidden">
                <div class="text-slate-800 overflow-x-auto min-h-[360px]">
                    <table class="min-w-full divide-y-2 divide-slate-200">
                        <thead class="bg-[#003366] text-white border-b-2 border-[#002244] font-cartoon text-xs">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-white whitespace-nowrap">Nomor Surat</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-white">Judul Surat</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-white">Pembuat</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-amber-300 w-80">
                                    <span class="flex items-center gap-1.5">
                                        <span>💬</span> Status Dokumen
                                    </span>
                                </th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-white">Tanggal</th>
                                <th class="px-6 py-4 text-left pl-6 font-bold uppercase tracking-wider text-white transition-all duration-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100 bg-white font-medium text-slate-800">
                            @php
                                $uRole = Auth::user()->role;
                            @endphp
                            @forelse ($surats as $surat)
                                @php
                                    $isArchivedOrFinal = in_array($surat->status, ['DIARSIPKAN', \App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD']) || !empty($surat->file_final);
                                    $isPengunggah = ($surat->pengunggah_final_id && $surat->pengunggah_final_id === Auth::id());
                                    $isSekdir = ($uRole === 'sekretaris_direktur');
                                    $isSuperAdmin = in_array($uRole, ['super_admin', 'superadmin']);
                                    $canEditArsip = $isArchivedOrFinal && ($isPengunggah || $isSekdir || ($isSuperAdmin && !$surat->isRahasia()));
                                    $canChangeFinalFile = ($isPengunggah || ($isSuperAdmin && !$surat->isRahasia()));
                                    $canChangeFileThisSurat = $canChangeFinalFile && !($surat->isRahasia() && $isSuperAdmin);
                                    $fileFinalPreviewUrl = ($surat->file_final && !($surat->isRahasia() && $isSuperAdmin)) ? asset('storage/' . $surat->file_final) : '';
                                @endphp
                                <tr>
                                    <!-- 1. Nomor Surat -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($surat->nomor_surat)
                                            <div class="flex items-center gap-1.5">
                                                <div class="font-mono font-bold text-xs text-[#003366] bg-sky-50 border-2 border-[#003366] shadow-[2px_2px_0px_#003366] px-2.5 py-1 rounded-xl inline-flex items-center gap-1.5" title="Nomor Surat Resmi">
                                                    <span>📄</span>
                                                    <span>{{ $surat->nomor_surat }}</span>
                                                </div>
                                                @if($canEditArsip)
                                                    <button type="button"
                                                            @click="openEditArsipModal(
                                                                {{ $surat->id }}, 
                                                                '{{ addslashes($surat->judul) }}', 
                                                                '{{ addslashes($surat->nomor_surat ?? '') }}', 
                                                                {{ $canChangeFileThisSurat ? 'true' : 'false' }}, 
                                                                '{{ addslashes($surat->pengunggahFinal->name ?? 'Pengunggah') }}', 
                                                                '{{ addslashes($surat->tujuan_penandatangan_label) }}', 
                                                                '{{ $fileFinalPreviewUrl }}'
                                                            )"
                                                            class="p-1 text-[#003366] hover:text-[#002244] bg-amber-100 hover:bg-amber-200 border border-amber-300 rounded-lg transition cursor-pointer"
                                                            title="{{ $canChangeFileThisSurat ? 'Edit Berkas Final & Nomor Surat' : 'Edit Nomor Surat' }}">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-500 italic bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-300" title="Belum memiliki nomor surat resmi (masih draft/proses paraf)">
                                                    <span class="text-slate-400">—</span> Draft Awal
                                                </span>
                                                @if($canEditArsip)
                                                    <button type="button"
                                                            @click="openEditArsipModal(
                                                                {{ $surat->id }}, 
                                                                '{{ addslashes($surat->judul) }}', 
                                                                '', 
                                                                {{ $canChangeFileThisSurat ? 'true' : 'false' }}, 
                                                                '{{ addslashes($surat->pengunggahFinal->name ?? 'Pengunggah') }}', 
                                                                '{{ addslashes($surat->tujuan_penandatangan_label) }}', 
                                                                '{{ $fileFinalPreviewUrl }}'
                                                            )"
                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-bold bg-[#F5B800] text-[#002244] border border-[#002244] hover:bg-amber-400 transition cursor-pointer"
                                                            title="Beri Nomor Surat">
                                                        <span>✏️ + No Surat</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 2. Judul Surat -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 text-sm leading-snug">{{ $surat->judul }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ $surat->pembuat->name ?? 'Tidak diketahui' }}</td>
                                    
                                    <!-- 3. Status Dokumen: MODEL 2 BALON KOMIK (SPEECH BUBBLE) -->
                                    <td class="px-6 py-4">
                                        @php
                                            $bubble = $surat->speech_bubble_data;
                                        @endphp
                                        <div class="speech-bubble rounded-2xl px-3.5 py-2 {{ $bubble['bg_class'] }} inline-block max-w-xs">
                                            <div class="flex items-center gap-1.5 font-cartoon font-bold text-xs">
                                                <span class="text-sm">{{ $bubble['emoji'] }}</span>
                                                <span>"{{ $bubble['dialog'] }}"</span>
                                            </div>
                                            <p class="text-[10px] opacity-90 font-medium leading-tight mt-0.5">
                                                {{ $bubble['subtext'] }}
                                            </p>
                                        </div>

                                        @if($surat->lampiran_revisi_terakhir)
                                            <div class="mt-1.5">
                                                <a href="{{ asset('storage/' . $surat->lampiran_revisi_terakhir) }}" target="_blank" download class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-800 hover:text-rose-950 bg-rose-100 hover:bg-rose-200 border-2 border-rose-400 px-2.5 py-1 rounded-xl shadow-xs transition">
                                                    <span>📎</span> Unduh File Koreksi
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- 4. Tanggal -->
                                    <td class="px-6 py-4 text-xs text-slate-600 font-semibold">{{ $surat->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-3 text-left pl-6 whitespace-nowrap transition-all duration-300"
                                        x-data="{ open: false }" 
                                        @click.outside="open = false">
                                        
                                        @php
                                            $canReview = false;
                                            $reviewRoleType = '';
                                            $reviewKembaliTarget = '';
                                            $reviewBtnLabel = '';
                                            $reviewBtnIcon = '✅';
                                            $isFinalSignerThisStep = false;
                                            $targetTtd = $surat->tujuan_penandatangan ?? 'direktur';

                                            if ($surat->isRahasia()) {
                                                if ($surat->status === \App\Models\SuratKeluar::STATUS_MENUNGGU_RAHASIA && ($surat->tujuan_rahasia_role === $uRole)) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'rahasia';
                                                    $reviewKembaliTarget = 'Penyusun (' . ($surat->pembuat->name ?? 'Pembuat') . ')';
                                                    $reviewBtnLabel = 'Tinjau Surat Rahasia';
                                                    $reviewBtnIcon = '🔒';
                                                }
                                            } elseif (\App\Models\User::isPpk($uRole)) {
                                                if (in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_PPK, \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PPK]) && (!$surat->tujuan_ppk || $surat->tujuan_ppk === $uRole)) {
                                                    $canReview = true;
                                                    $reviewRoleType = 'ppk';
                                                    $reviewKembaliTarget = $surat->tujuan_unit_label ?? 'Pemeriksa Pertama';
                                                    $reviewBtnLabel = 'Verifikasi & Paraf PPK';
                                                    $reviewBtnIcon = '💼';
                                                }
                                            } elseif (\App\Models\User::isPemeriksaUnit($uRole)) {
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
                                                if ($surat->isRahasia()) {
                                                    if ($surat->tujuan_rahasia_role === $uRole || $surat->pembuat_id === Auth::id()) {
                                                        $canUploadFinal = true;
                                                    }
                                                } elseif (in_array($uRole, ['super_admin', 'superadmin'])) {
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
                                            $canPenyusunBatalkan = $surat->canPenyusunBatalkan(Auth::id());
                                            $isSuperAdmin = in_array($uRole, ['super_admin', 'superadmin']);
                                            $isPenyusunRevisi = ($surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN);
                                            $hasActionNeeded = $canReview || ($canPenyusunEdit && $isPenyusunRevisi) || $canUploadFinal;
                                        @endphp

                                        <div class="inline-flex items-center justify-start transition-all duration-300 ease-in-out"
                                             :style="open ? 'min-width: 280px;' : 'min-width: 0px;'">
                                            
                                            <!-- Wrapper relatif khusus tombol & popup agar popup selalu menempel dekat tombol -->
                                            <div class="relative inline-flex items-center shrink-0">
                                                
                                                <!-- Tombol Utama Aksi -->
                                                <button @click="open = !open" 
                                                        type="button" 
                                                        class="inline-flex items-center justify-center px-4 py-2 {{ $hasActionNeeded ? 'btn-pop-gold' : 'btn-pop-navy' }} text-xs font-cartoon font-bold rounded-xl transition focus:outline-none gap-2 whitespace-nowrap cursor-pointer">
                                                    @if($hasActionNeeded)
                                                        <span class="relative flex h-2.5 w-2.5">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-80"></span>
                                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                                        </span>
                                                    @endif
                                                    <span>⚙️ Aksi</span>
                                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
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
                                                     class="absolute left-full top-0 ml-2 z-50 w-56 bg-white border-2 border-[#003366] p-2 rounded-2xl shadow-[5px_5px_0px_#003366] flex flex-col gap-1.5 whitespace-nowrap text-left"
                                                     style="display: none; left: 100%; margin-left: 0.5rem; top: 0;">
                                                    
                                                    <div class="px-2.5 py-1 text-[10px] font-cartoon font-extrabold tracking-wider text-slate-500 uppercase border-b-2 border-slate-100 flex items-center justify-between">
                                                        <span>PILIHAN AKSI</span>
                                                        <span>⚡</span>
                                                    </div>

                                                    <!-- 1. View Surat (Draft) -->
                                                    @if($surat->file_draft && !($surat->isRahasia() && $isSuperAdmin))
                                                        <button type="button" 
                                                                data-url="{{ asset('storage/' . $surat->file_draft) }}"
                                                                data-title="{{ $surat->judul }}"
                                                                data-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                                @click="open = false; openPreview($el.dataset.url, $el.dataset.title, $el.dataset.type)"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-blue font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                                title="Lihat Draft Dokumen Surat">
                                                            <span class="mr-2">👁️</span> View Surat
                                                        </button>
                                                    @endif

                                                    <!-- 2. Download / View Final (Jika Sudah Diupload) -->
                                                    @if($surat->file_final && !($surat->isRahasia() && $isSuperAdmin))
                                                        <button type="button" 
                                                                data-url="{{ asset('storage/' . $surat->file_final) }}"
                                                                data-title="{{ $surat->judul }} (Final)"
                                                                data-type="{{ strtolower(pathinfo($surat->file_final, PATHINFO_EXTENSION)) }}"
                                                                @click="open = false; openPreview($el.dataset.url, $el.dataset.title, $el.dataset.type)"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-teal font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
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
                                                                'file_lampiran' => ($t->file_lampiran && !($surat->isRahasia() && $isSuperAdmin)) ? asset('storage/' . $t->file_lampiran) : null
                                                            ])) }}"
                                                            data-judul="{{ $surat->judul }}"
                                                            data-jenis="{{ $surat->jenisSurat->nama ?? '' }}"
                                                            data-unit="{{ $surat->tujuan_unit_label ?? '' }}"
                                                            data-penandatangan="{{ $surat->tujuan_penandatangan_label ?? '' }}"
                                                            data-status="{{ $surat->status_label }}"
                                                            data-status-raw="{{ $surat->status }}"
                                                            data-status-badge="{{ $surat->status_badge_class }}"
                                                            data-catatan-terakhir="{{ $surat->catatan_terakhir ?? '' }}"
                                                            data-lampiran-terakhir="{{ ($surat->lampiran_revisi_terakhir && !($surat->isRahasia() && $isSuperAdmin)) ? asset('storage/' . $surat->lampiran_revisi_terakhir) : '' }}"
                                                            data-track-url="{{ route('surat.track', $surat->id) }}"
                                                            data-draft="{{ ($surat->file_draft && !($surat->isRahasia() && $isSuperAdmin)) ? asset('storage/' . $surat->file_draft) : '' }}"
                                                            data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                            data-final="{{ ($surat->file_final && !($surat->isRahasia() && $isSuperAdmin)) ? asset('storage/' . $surat->file_final) : '' }}"
                                                            @click="open = false; openTrackingFromElement($el)"
                                                            class="flex items-center w-full px-3 py-2 btn-aksi-indigo font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                            title="Lihat Riwayat Perjalanan Surat">
                                                        <span class="mr-2">🔍</span> Tracking Surat
                                                    </button>

                                                    <!-- 4. Informasi Surat (Popup Modal Rincian & Alur - Disembunyikan untuk Super Admin jika dokumen rahasia) -->
                                                    @if(!($surat->isRahasia() && $isSuperAdmin))
                                                        <button type="button" 
                                                                data-id="{{ $surat->id }}"
                                                                data-nomor="{{ $surat->nomor_surat ?? '' }}"
                                                                data-judul="{{ $surat->judul }}"
                                                                data-jenis="{{ $surat->jenisSurat->nama ?? '-' }}"
                                                                data-pembuat="{{ $surat->pembuat->name ?? '-' }}{{ isset($surat->pembuat->role_label) ? ' (' . $surat->pembuat->role_label . ')' : '' }}"
                                                                data-tanggal="{{ $surat->created_at->translatedFormat('d F Y, H:i') }} WIB"
                                                                data-unit="{{ $surat->tujuan_unit_label ?? '-' }}"
                                                                data-kasubbag="{{ $surat->tujuan_kasubbag_label ?? '-' }}"
                                                                data-wadir="{{ $surat->tujuan_wadir_label ?? '-' }}"
                                                                data-penandatangan="{{ $surat->tujuan_penandatangan_label ?? '-' }}"
                                                                data-ditandatangani-oleh="{{ ($surat->tujuan_penandatangan !== 'direktur' && in_array($surat->status, [\App\Models\SuratKeluar::STATUS_MENUNGGU_FINAL, 'SELESAI_TTD', 'DIARSIPKAN'])) ? $surat->tujuan_penandatangan_label : '' }}"
                                                                data-pengunggah-arsip="{{ $surat->pengunggahFinal ? ($surat->pengunggahFinal->name . ' (' . $surat->pengunggahFinal->role_label . ')') : '' }}"
                                                                data-status="{{ $surat->status_label }}"
                                                                data-status-raw="{{ $surat->status }}"
                                                                data-status-badge="{{ $surat->status_badge_class }}"
                                                                data-draft="{{ $surat->file_draft ? asset('storage/' . $surat->file_draft) : '' }}"
                                                                data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                                data-final="{{ $surat->file_final ? asset('storage/' . $surat->file_final) : '' }}"
                                                                data-track-url="{{ route('surat.track', $surat->id) }}"
                                                                data-trackings="{{ json_encode($surat->trackings->values()->map(fn($t, $idx) => [
                                                                    'id' => $t->id,
                                                                    'waktu' => $t->created_at->translatedFormat('d M Y - H:i') . ' WIB',
                                                                    'aksi' => $surat->formatTrackingAksi($t, $idx === $surat->trackings->count() - 1),
                                                                    'aktor' => $t->aktor->name ?? 'User',
                                                                    'role' => strtoupper($t->aktor->role ?? '-'),
                                                                    'catatan' => $t->catatan,
                                                                    'file_lampiran' => $t->file_lampiran ? asset('storage/' . $t->file_lampiran) : null
                                                                ])) }}"
                                                                @click="openInfoModalFromElement($el); open = false;"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-cyan font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                                title="Lihat Rincian & Informasi Dokumen Surat">
                                                            <span class="mr-2">ℹ️</span> Informasi Surat
                                                        </button>
                                                    @endif

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
                                                                data-draft="{{ $surat->file_draft ? asset('storage/' . $surat->file_draft) : '' }}"
                                                                data-draft-type="{{ strtolower(pathinfo($surat->file_draft, PATHINFO_EXTENSION)) }}"
                                                                data-catatan-revisi="{{ $surat->catatan_terakhir ?? '' }}"
                                                                data-lampiran-revisi="{{ $surat->lampiran_revisi_terakhir ? asset('storage/' . $surat->lampiran_revisi_terakhir) : '' }}"
                                                                data-opsi-kembali="{{ json_encode($surat->getOpsiPengembalian($uRole)) }}"
                                                                data-tujuan-wadir="{{ $surat->tujuan_wadir ?? '' }}"
                                                                data-tujuan-kasubbag="{{ $surat->tujuan_kasubbag ?? '' }}"
                                                                data-tujuan-unit="{{ $surat->tujuan_unit ?? '' }}"
                                                                data-is-spt="{{ $surat->isSpt() ? '1' : '0' }}"
                                                                data-is-rahasia="{{ $surat->isRahasia() ? '1' : '0' }}"
                                                                data-tujuan-ppk="{{ $surat->tujuan_ppk ?? '' }}"
                                                                data-tujuan-rahasia-role="{{ $surat->tujuan_rahasia_role ?? '' }}"
                                                                @click="open = false; openTinjauFromElement($el)"
                                                                class="flex items-center w-full px-3 py-2 btn-menu-tinjau font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                                title="{{ $reviewBtnLabel }}">
                                                            <span class="mr-2">{{ $reviewBtnIcon }}</span> {{ $reviewBtnLabel }}
                                                        </button>
                                                    @endif

                                                    <!-- 6. AKSI UPLOAD TTD FINAL (Muncul Untuk Pejabat Penandatangan Terpilih Saat Status Menunggu Upload Final) -->
                                                    @if($canUploadFinal)
                                                        <button type="button" 
                                                                @click="open = false; openUploadFinalModal({{ $surat->id }}, '{{ addslashes($surat->judul) }}', '{{ addslashes($surat->tujuan_penandatangan_label) }}', '{{ addslashes($surat->nomor_surat ?? '') }}')"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-teal font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
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
                                                                    '{{ $surat->lampiran_revisi_terakhir ? asset('storage/' . $surat->lampiran_revisi_terakhir) : '' }}',
                                                                    {{ $surat->tujuan_unit ? 'false' : 'true' }},
                                                                    {{ $surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN ? 'true' : 'false' }}
                                                                )"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-amber font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                                title="{{ $surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN ? 'Perbaiki Draft Surat Sesuai Catatan' : 'Edit Judul atau Ganti Berkas Draft' }}">
                                                            <span class="mr-2">✏️</span> {{ $surat->status === \App\Models\SuratKeluar::STATUS_DIKEMBALIKAN_KE_PENYUSUN ? 'Revisi Draft' : 'Edit Surat' }}
                                                        </button>
                                                    @endif

                                                    <!-- 8. AKSI EDIT BERKAS & NOMOR SURAT ARSIP -->
                                                    @php
                                                        $canChangeFileThisSurat = $canChangeFinalFile && !($surat->isRahasia() && $isSuperAdmin);
                                                    @endphp
                                                    @if($canEditArsip)
                                                        <button type="button" 
                                                                @click="open = false; openEditArsipModal(
                                                                    {{ $surat->id }}, 
                                                                    '{{ addslashes($surat->judul) }}', 
                                                                    '{{ addslashes($surat->nomor_surat ?? '') }}', 
                                                                    {{ $canChangeFileThisSurat ? 'true' : 'false' }}, 
                                                                    '{{ addslashes($surat->pengunggahFinal->name ?? 'Pengunggah') }}', 
                                                                    '{{ addslashes($surat->tujuan_penandatangan_label) }}', 
                                                                    '{{ ($surat->file_final && !($surat->isRahasia() && $isSuperAdmin)) ? asset('storage/' . $surat->file_final) : '' }}'
                                                                )"
                                                                class="flex items-center w-full px-3 py-2 btn-aksi-amber font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                                title="{{ $canChangeFileThisSurat ? 'Edit Berkas Final & Nomor Surat' : 'Berikan / Edit Nomor Surat' }}">
                                                            <span class="mr-2">📝</span>
                                                            <span>{{ $canChangeFileThisSurat ? 'Edit Berkas & No. Surat' : 'Edit / Berikan No. Surat' }}</span>
                                                        </button>
                                                    @endif

                                                    <!-- 9. AKSI BATALKAN PROSES SURAT (Khusus Penyusun) -->
                                                    @if($canPenyusunBatalkan)
                                                        <form action="{{ route('surat.batalkan', $surat->id) }}" method="POST" class="w-full m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan proses dokumen surat ini? Seluruh alur proses surat akan dihentikan.');">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-3 py-2 btn-aksi-rose font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
                                                                    title="Batalkan Proses Surat">
                                                                <span class="mr-2">🚫</span> Batalkan Surat
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- 10. Hapus Surat (Super Admin & Penyusun jika belum final/arsip) -->
                                                    @if($isSuperAdmin || $canPenyusunDelete)
                                                        <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" class="w-full m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus dokumen surat ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="flex items-center w-full px-3 py-2 btn-aksi-red font-cartoon font-bold text-xs rounded-xl transition text-left cursor-pointer"
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
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-cartoon">
                                        <div class="max-w-sm mx-auto flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-800 border-2 border-[#003366] shadow-[3px_3px_0px_#003366] flex items-center justify-center text-2xl mb-3">
                                                📭
                                            </div>
                                            <p class="font-black text-sm text-[#003366]">Tidak Ada Dokumen Surat</p>
                                            <p class="text-xs text-slate-500 font-semibold mt-1">
                                                @if(request('search'))
                                                    Tidak ditemukan surat dengan kata kunci "{{ request('search') }}".
                                                @elseif(($activeTab ?? '') === 'antrean')
                                                    Saat ini tidak ada surat yang butuh tindakan atau sedang dalam proses.
                                                @elseif(($activeTab ?? '') === 'sudah_paraf')
                                                    Belum ada surat yang telah selesai diarsipkan.
                                                @else
                                                    Belum ada dokumen surat yang dibuat atau diunggah.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
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
             class="fixed inset-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:px-6 lg:px-8"
             style="display: none;">
             
            <div @click.away="closePreview()" 
                 x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-7xl w-full overflow-hidden flex flex-col text-slate-800"
                 style="height: 88vh; max-height: 92vh;">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3 truncate mr-4">
                        <div class="p-2.5 bg-white/10 text-white border-2 border-white/20 rounded-xl flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="truncate">
                            <h3 class="text-base font-cartoon font-bold text-white truncate" x-text="previewTitle">Pratinjau Surat</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] font-cartoon font-bold px-2 py-0.5 rounded-lg uppercase border"
                                      :class="previewType === 'pdf' ? 'bg-rose-100 text-rose-800 border-rose-300' : 'bg-blue-100 text-blue-800 border-blue-300'"
                                      x-text="previewType"></span>
                                <span class="text-xs text-blue-100/90 font-cartoon">File Unggahan</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <!-- Buka Tab Baru -->
                        <a :href="previewUrl" target="_blank" 
                           class="inline-flex items-center px-3 py-1.5 bg-white/15 hover:bg-white/25 text-white border-2 border-white/30 text-xs font-cartoon font-bold rounded-xl transition shadow-sm"
                           title="Buka di tab baru">
                            <svg class="w-4 h-4 mr-1 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Tab Baru
                        </a>
                        
                        <!-- Download -->
                        <a :href="previewUrl" download
                           class="inline-flex items-center px-3.5 py-1.5 btn-pop-gold text-xs font-cartoon font-bold rounded-xl transition shadow-sm"
                           title="Unduh file">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh
                        </a>

                        <!-- Tutup -->
                        <button type="button" @click="closePreview()" 
                                class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer"
                                title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Body Modal -->
                <div class="p-4 flex-1 overflow-hidden bg-[#F4F6F9] flex items-center justify-center" style="min-height: 0;">
                    <!-- Jika file PDF -->
                    <template x-if="previewType === 'pdf'">
                        <iframe :src="previewUrl" class="w-full h-full rounded-2xl border-2 border-slate-300 shadow-sm bg-white" style="width: 100%; height: 100%; min-height: 100%;"></iframe>
                    </template>

                    <!-- Jika file Word / Dokumen non-PDF -->
                    <template x-if="previewType !== 'pdf'">
                        <div class="text-center p-8 bg-white rounded-3xl shadow-[4px_4px_0px_#003366] border-2 border-[#003366] max-w-sm my-auto text-slate-800">
                            <div class="w-16 h-16 bg-blue-50 text-[#003366] border-2 border-[#003366] rounded-2xl flex items-center justify-center mx-auto mb-3.5 shadow-[2px_2px_0px_#003366]">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 7V3.5L18.5 9H15z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-cartoon font-extrabold text-[#003366] mb-1.5">Dokumen Word (.doc / .docx)</h4>
                            <p class="text-xs text-slate-600 mb-5 leading-relaxed font-cartoon">
                                Format file Word tidak dapat dipratinjau langsung di dalam browser. Silakan unduh dokumen untuk melihat isinya.
                            </p>
                            <a :href="previewUrl" download
                               class="inline-flex items-center px-4 py-2.5 btn-pop-navy text-xs font-cartoon font-bold rounded-xl transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Dokumen Sekarang
                            </a>
                        </div>
                    </template>
                </div>
                
                <!-- Footer Modal -->
                <div class="px-6 py-3.5 bg-white border-t-2 border-slate-200 flex justify-between items-center text-xs text-slate-600 flex-shrink-0 font-cartoon">
                    <span>Tekan <kbd class="px-2 py-0.5 bg-slate-100 border-2 border-slate-300 rounded-md shadow-xs font-mono text-slate-700">Esc</kbd> untuk menutup</span>
                    <button type="button" @click="closePreview()" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 hover:border-slate-400 font-cartoon font-bold rounded-xl transition text-xs cursor-pointer">
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:px-6 lg:px-8"
             style="display: none;">
             
            <div @click.away="closeTracking()" 
                 x-show="showTrackingModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-3xl w-full overflow-hidden flex flex-col max-h-[90vh] text-slate-800">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3 truncate mr-4">
                        <div class="p-2.5 bg-white/10 text-white border-2 border-white/20 rounded-xl flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="truncate">
                            <h3 class="text-base font-cartoon font-bold text-white truncate" x-text="trackingJudul">Riwayat Perjalanan Surat</h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <template x-if="trackingNomorSurat">
                                    <span class="text-xs font-mono font-bold px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-900 border-2 border-emerald-400"
                                          x-text="'📄 ' + trackingNomorSurat"></span>
                                </template>
                                <template x-if="trackingJenis">
                                    <span class="text-xs font-cartoon font-bold px-2.5 py-0.5 rounded-lg bg-indigo-100 text-indigo-900 border-2 border-indigo-400"
                                          x-text="'📑 ' + trackingJenis"></span>
                                </template>
                                <template x-if="trackingUnit">
                                    <span class="text-xs font-cartoon font-bold px-2.5 py-0.5 rounded-lg bg-blue-100 text-blue-900 border-2 border-blue-400"
                                          x-text="'🏢 ' + trackingUnit"></span>
                                </template>
                                <template x-if="trackingPenandatangan">
                                    <span class="text-xs font-cartoon font-bold px-2.5 py-0.5 rounded-lg bg-amber-100 text-amber-900 border-2 border-amber-400"
                                          x-text="'✍️ TTD: ' + trackingPenandatangan"></span>
                                </template>
                                <span class="text-xs font-cartoon font-bold px-2.5 py-0.5 rounded-lg shadow-sm"
                                      :class="trackingStatusBadge || (isStatusDikembalikan(trackingStatusRaw) ? 'bg-rose-100 text-rose-900 border-2 border-rose-300' : 'bg-amber-100 text-amber-900 border-2 border-amber-300')"
                                      x-text="'Status: ' + trackingStatus"></span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" @click="closeTracking()" 
                            class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer"
                            title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="p-6 overflow-y-auto flex-1 bg-[#F8FAFC] text-slate-800 font-cartoon">
                    <!-- Alert Highlight jika status surat sedang Dikembalikan / Perlu Revisi -->
                    <template x-if="isStatusDikembalikan(trackingStatusRaw) || isStatusDikembalikan(trackingStatus)">
                        <div class="mb-5 p-4 bg-rose-50 border-2 border-rose-300 rounded-2xl shadow-[3px_3px_0px_#e11d48]">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-100 border-2 border-rose-400 flex items-center justify-center text-rose-700 text-lg shrink-0">
                                    ⚠️
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="text-xs font-extrabold text-rose-900 uppercase tracking-wider flex items-center gap-1.5">
                                            <span>Surat Perlu Revisi / Dikembalikan</span>
                                        </h4>
                                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 border border-rose-300 shadow-xs" x-text="trackingStatus"></span>
                                    </div>
                                    <p class="text-xs text-rose-800 mt-0.5">Surat ini memerlukan perbaikan sebelum dapat diproses ke tingkat selanjutnya.</p>
                                    
                                    <template x-if="getLatestRevisiCatatan()">
                                        <div class="mt-2.5 p-3.5 bg-white border-2 border-rose-200 rounded-xl shadow-xs">
                                            <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5 mb-1">
                                                <span>💬</span> Catatan / Instruksi Revisi Terakhir:
                                            </span>
                                            <p class="text-sm text-rose-950 font-semibold whitespace-pre-line leading-relaxed" x-text="getLatestRevisiCatatan()"></p>
                                        </div>
                                    </template>
                                    <template x-if="trackingLampiranTerakhir">
                                        <div class="mt-2.5">
                                            <a :href="trackingLampiranTerakhir" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border-2 border-rose-300 transition shadow-sm">
                                                <span>📎</span> Unduh File Koreksi / Lampiran Revisi
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Alert Highlight jika status surat Ditolak -->
                    <template x-if="isStatusDitolak(trackingStatusRaw) || isStatusDitolak(trackingStatus)">
                        <div class="mb-5 p-4 bg-rose-50 border-2 border-rose-500 rounded-2xl shadow-[3px_3px_0px_#be123c]">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-200 border-2 border-rose-500 flex items-center justify-center text-rose-800 text-lg shrink-0">
                                    ❌
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="text-xs font-extrabold text-rose-900 uppercase tracking-wider">
                                            Dokumen Surat Telah Ditolak
                                        </h4>
                                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-200 text-rose-900 border border-rose-400" x-text="trackingStatus"></span>
                                    </div>
                                    <p class="text-xs text-rose-800 mt-0.5 font-medium">Dokumen ini telah ditolak oleh pemeriksa dan seluruh proses alur surat telah dihentikan secara permanen.</p>
                                    
                                    <template x-if="trackingCatatanTerakhir">
                                        <div class="mt-2.5 p-3.5 bg-white border-2 border-rose-200 rounded-xl shadow-xs">
                                            <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5 mb-1">
                                                <span>💬</span> Alasan Penolakan:
                                            </span>
                                            <p class="text-sm text-rose-950 font-semibold whitespace-pre-line leading-relaxed" x-text="trackingCatatanTerakhir"></p>
                                        </div>
                                    </template>
                                    <template x-if="trackingLampiranTerakhir">
                                        <div class="mt-2.5">
                                            <a :href="trackingLampiranTerakhir" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border-2 border-rose-300 transition shadow-sm">
                                                <span>📎</span> Unduh Berkas Catatan Penolakan
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Alert Highlight jika status surat Dibatalkan oleh Penyusun -->
                    <template x-if="isStatusDibatalkan(trackingStatusRaw) || isStatusDibatalkan(trackingStatus)">
                        <div class="mb-5 p-4 bg-slate-100 border-2 border-slate-400 rounded-2xl shadow-[3px_3px_0px_#64748b]">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-200 border-2 border-slate-400 flex items-center justify-center text-slate-700 text-lg shrink-0">
                                    🚫
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                                            Proses Dokumen Dibatalkan
                                        </h4>
                                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800 border border-slate-400" x-text="trackingStatus"></span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-0.5 font-medium">Proses surat ini telah dibatalkan atas permintaan Penyusun (Pembuat Dokumen).</p>
                                    <div class="mt-2.5 p-3.5 bg-white border-2 border-slate-200 rounded-xl shadow-xs">
                                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-1">
                                            <span>💬</span> Pesan Penyusun:
                                        </span>
                                        <p class="text-sm text-slate-900 font-semibold whitespace-pre-line leading-relaxed" x-text="trackingCatatanTerakhir || 'Mohon maaf Bpk/Ibu suratnya saya batalkan prosesnya'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Tombol Preview Cepat Jika Ada File -->
                    <div class="mb-6 flex flex-wrap gap-2.5" x-show="trackingDraft || trackingFinal">
                        <template x-if="trackingDraft">
                            <button type="button" 
                                    @click="openPreview(trackingDraft, trackingJudul, trackingDraftType)"
                                    class="inline-flex items-center btn-pop-navy text-xs font-cartoon font-bold py-1.5 px-3.5 rounded-xl shadow-[2px_2px_0px_#002244] transition cursor-pointer">
                                👁️ Lihat File Draft (Awal)
                            </button>
                        </template>
                        <template x-if="trackingFinal">
                            <button type="button" 
                                    @click="openPreview(trackingFinal, trackingJudul + ' (Final)', 'pdf')"
                                    class="inline-flex items-center btn-pop-gold text-xs font-cartoon font-bold py-1.5 px-3.5 rounded-xl shadow-[2px_2px_0px_#002244] transition cursor-pointer">
                                📥 Unduh File Final (TTD)
                            </button>
                        </template>
                    </div>

                    <!-- Banner Dialog Maskot Naskah -->
                    <div class="mb-5 p-3.5 bg-gradient-to-r from-sky-50 via-amber-50 to-emerald-50 border-2 border-[#003366] rounded-2xl shadow-[3px_3px_0px_#003366] flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white border-2 border-[#003366] shadow-[2px_2px_0px_#003366] flex items-center justify-center text-xl shrink-0">
                            📬
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-cartoon font-bold uppercase tracking-wider text-[#003366]">
                                Catatan Perjalanan Naskah Dinas
                            </h4>
                            <p class="text-xs text-slate-600 font-medium">
                                Ikuti perjalanan naskah surat dari draf awal hingga resmi bertanda tangan dan terarsip!
                            </p>
                        </div>
                    </div>

                    <!-- Visualisasi Timeline -->
                    <div class="relative border-l-2 border-[#003366] ml-3 pb-2">
                        <template x-for="(item, index) in trackingList" :key="item.id">
                            <div class="mb-6 pl-7 relative">
                                <!-- Titik Bulat Timeline -->
                                <!-- 1. Titik Saat Ini (Posisi Terakhir) Berkedip / Radar Ping -->
                                <template x-if="index === trackingList.length - 1">
                                    <div class="absolute -left-[13px] top-1 flex items-center justify-center w-6 h-6">
                                        <!-- Efek Radar Ping Berkedip -->
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-80"
                                              :class="isItemDikembalikan(item) ? 'bg-rose-400' : 'bg-amber-400'"></span>
                                        <!-- Lingkaran Inti Bercahaya -->
                                        <span class="relative inline-flex w-5 h-5 rounded-full border-2 border-[#003366] shadow-md flex items-center justify-center text-white"
                                              :class="isItemDikembalikan(item) ? 'bg-rose-500 ring-2 ring-rose-300' : 'bg-[#F5B800] ring-2 ring-amber-300'">
                                            <template x-if="isItemDikembalikan(item)">
                                                <span class="text-[10px] font-black leading-none">!</span>
                                            </template>
                                            <template x-if="!isItemDikembalikan(item)">
                                                <span class="w-2 h-2 rounded-full bg-[#002244]"></span>
                                            </template>
                                        </span>
                                    </div>
                                </template>

                                <!-- 2. Titik Riwayat Sebelumnya (Past Steps) -->
                                <template x-if="index < trackingList.length - 1">
                                    <div class="absolute -left-[9px] top-2">
                                        <template x-if="isItemDikembalikan(item)">
                                            <div class="w-4 h-4 bg-rose-600 rounded-full border-2 border-white shadow-xs flex items-center justify-center text-white">
                                                <span class="text-[8px] font-black leading-none">!</span>
                                            </div>
                                        </template>
                                        <template x-if="!isItemDikembalikan(item)">
                                            <div class="w-4 h-4 bg-[#003366] rounded-full border-2 border-white shadow-xs"></div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Kartu Log Maskot -->
                                <div class="p-3.5 rounded-2xl border-2 transition"
                                     :class="index === trackingList.length - 1 
                                        ? (isItemDikembalikan(item) ? 'bg-rose-50/70 border-rose-500 shadow-[2px_2px_0px_#e11d48]' : 'bg-amber-50/60 border-[#003366] shadow-[2px_2px_0px_#003366]') 
                                        : (isItemDikembalikan(item) ? 'bg-rose-50/30 border-rose-200 shadow-xs' : 'bg-white border-slate-200 shadow-xs')">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="font-cartoon font-bold text-sm sm:text-base leading-snug" 
                                                :class="isItemDikembalikan(item) ? 'text-rose-700' : 'text-[#003366]'" 
                                                x-text="item.aksi"></h4>
                                            
                                            <!-- Badge Khusus Titik Posisi Saat Ini -->
                                            <template x-if="index === trackingList.length - 1">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm"
                                                      :class="isItemDitolak(item) ? 'bg-rose-200 text-rose-950 border border-rose-500' : (isItemDibatalkan(item) ? 'bg-slate-200 text-slate-900 border border-slate-500' : (isItemDikembalikan(item) ? 'bg-rose-200 text-rose-950 border border-rose-400' : 'bg-[#F5B800] text-[#002244] border border-[#002244]'))">
                                                    <span x-text="isItemDitolak(item) ? '❌ DOKUMEN DITOLAK' : (isItemDibatalkan(item) ? '🚫 PROSES DIBATALKAN' : '📍 POSISI SAAT INI')"></span>
                                                </span>
                                            </template>

                                            <!-- Badge Dikembalikan / Ditolak / Dibatalkan untuk Riwayat Lampau -->
                                            <template x-if="index < trackingList.length - 1">
                                                <span>
                                                    <template x-if="isItemDitolak(item)">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                                            ❌ DITOLAK
                                                        </span>
                                                    </template>
                                                    <template x-if="isItemDibatalkan(item)">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-800 border border-slate-400">
                                                            🚫 DIBATALKAN
                                                        </span>
                                                    </template>
                                                    <template x-if="!isItemDitolak(item) && !isItemDibatalkan(item) && isItemDikembalikan(item)">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                                            ↩️ CATATAN REVISI
                                                        </span>
                                                    </template>
                                                </span>
                                            </template>
                                        </div>

                                        <p class="text-xs text-slate-500 font-mono font-bold" x-text="item.waktu"></p>
                                    </div>

                                    <p class="text-xs text-slate-600 mt-1 font-medium">
                                        Diproses oleh: <strong class="text-slate-900" x-text="item.aktor"></strong> 
                                        <span class="text-[#003366] font-mono font-bold" x-text="'(' + item.role + ')'"></span>
                                    </p>

                                    <!-- Catatan Log: Jika Dikembalikan / Revisi Diberi Desain Alert Merah Spesial -->
                                    <template x-if="item.catatan">
                                        <div>
                                            <!-- Model Revisi / Dikembalikan / Ditolak / Dibatalkan -->
                                            <template x-if="isItemDikembalikan(item)">
                                                <div class="mt-2.5 p-3.5 bg-rose-50 border-2 border-rose-300 rounded-2xl shadow-[2px_2px_0px_#e11d48]">
                                                    <div class="flex items-center text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">
                                                        <span class="text-sm mr-1.5">💬</span>
                                                        <span x-text="isItemDitolak(item) ? 'Alasan Penolakan Dokumen:' : (isItemDibatalkan(item) ? 'Alasan Pembatalan Surat:' : 'Catatan Revisi / Alasan Pengembalian:')"></span>
                                                    </div>
                                                    <div class="p-2.5 bg-white border border-rose-200 rounded-xl text-sm text-rose-950 font-medium whitespace-pre-line leading-relaxed shadow-xs" x-text="item.catatan"></div>
                                                    <template x-if="item.file_lampiran">
                                                        <div class="mt-2.5 pt-2 border-t border-rose-200">
                                                            <a :href="item.file_lampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border-2 border-rose-300 transition shadow-sm">
                                                                <span x-text="isItemDitolak(item) ? '📎 Unduh Berkas Catatan Penolakan' : '📎 Unduh File Koreksi / Catatan Revisi'"></span>
                                                            </a>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Model Normal / Persetujuan / Info -->
                                            <template x-if="!isItemDikembalikan(item)">
                                                <div>
                                                    <div class="mt-2 p-3 bg-white border-2 border-slate-200 rounded-xl text-xs text-slate-700 shadow-xs">
                                                        <span class="font-bold text-[#003366]">Catatan:</span>
                                                        <span class="text-slate-800 ml-1 italic" x-text="item.catatan"></span>
                                                    </div>
                                                    <template x-if="item.file_lampiran">
                                                        <div class="mt-2">
                                                            <a :href="item.file_lampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-[#003366] text-xs font-bold rounded-xl border-2 border-blue-300 transition shadow-sm">
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
                                            <a :href="item.file_lampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border-2 border-rose-300 transition shadow-sm">
                                                <span>📎</span> Unduh File Koreksi / Catatan Revisi
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="trackingList.length === 0">
                            <p class="text-slate-500 text-sm italic pl-6">Belum ada riwayat tracking untuk surat ini.</p>
                        </template>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="px-6 py-3.5 bg-white border-t-2 border-slate-200 flex justify-between items-center text-xs text-slate-600 flex-shrink-0 font-cartoon">
                    <div class="flex items-center gap-3">
                        <span>Tekan <kbd class="px-2 py-0.5 bg-slate-100 border-2 border-slate-300 rounded-md shadow-xs font-mono text-slate-700">Esc</kbd> untuk menutup</span>
                        <template x-if="trackingTrackUrl">
                            <a :href="trackingTrackUrl" target="_blank" class="text-[#003366] hover:text-blue-700 font-bold underline flex items-center gap-1 transition">
                                <span>Buka Halaman Penuh</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </template>
                    </div>
                    <button type="button" @click="closeTracking()" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl transition text-xs cursor-pointer">
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeTinjauModal()" 
                 x-show="showTinjauModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-xl w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-800">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-white border-2 border-white/20 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            ⚖️
                        </div>
                        <div>
                            <h3 class="text-base font-cartoon font-bold text-white">Tinjauan & Keputusan Dokumen Surat</h3>
                            <p class="text-xs text-blue-100/90 font-cartoon">Pilih keputusan untuk meneruskan ke tahap selanjutnya atau mengembalikan untuk perbaikan.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeTinjauModal()" class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Keputusan -->
                <form :action="tinjauActionUrl" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 bg-[#F8FAFC] text-slate-800 font-cartoon">
                    @csrf
                    
                    <!-- Informasi Dokumen -->
                    <div class="mb-5 p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Judul Dokumen Surat</label>
                        <p class="text-sm font-extrabold text-[#003366]" x-text="tinjauJudul"></p>
                        <template x-if="tinjauDraftUrl">
                            <div class="mt-2.5 pt-2.5 border-t border-slate-200">
                                <button type="button" 
                                        @click="openPreview(tinjauDraftUrl, tinjauJudul, tinjauDraftType)"
                                        class="inline-flex items-center text-xs font-bold text-[#003366] hover:text-blue-700 hover:underline transition cursor-pointer">
                                    <span class="mr-1.5">👁️</span> Pratinjau File Draft Dokumen
                                </button>
                            </div>
                        </template>
                        <template x-if="tinjauCatatanRevisi">
                            <div class="mt-3 p-3.5 bg-rose-50 border-2 border-rose-300 rounded-xl text-xs text-rose-900 shadow-xs">
                                <span class="font-bold flex items-center gap-1.5 text-rose-800">
                                    <span>💬</span> Catatan / Instruksi Revisi Sebelumnya:
                                </span>
                                <p class="mt-1 font-semibold text-rose-950 text-xs whitespace-pre-line leading-relaxed" x-text="tinjauCatatanRevisi"></p>
                            </div>
                        </template>
                        <template x-if="tinjauLampiranRevisi">
                            <div class="mt-2.5 pt-2.5 border-t border-slate-200">
                                <a :href="tinjauLampiranRevisi" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border-2 border-rose-300 transition shadow-sm">
                                    <span>📎</span> Unduh File Koreksi / Lampiran Revisi Sebelumnya
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Pilihan Keputusan: Teruskan vs Kembalikan -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                            Pilih Keputusan Tindakan <span class="text-rose-500">*</span>
                        </label>
                        <div class="space-y-2.5">
                            <!-- Opsi 1: Teruskan / Setujui & Diarsipkan -->
                            <label class="flex items-start p-3.5 border-2 rounded-2xl cursor-pointer transition select-none"
                                   :class="tinjauKeputusan === 'teruskan' ? 'card-radio-teruskan-active' : 'border-slate-200 hover:border-slate-300 bg-white shadow-xs'">
                                <input type="radio" name="keputusan" value="teruskan" x-model="tinjauKeputusan" class="mt-1 text-emerald-600 focus:ring-0 border-2 border-slate-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-extrabold text-slate-900" 
                                          x-text="tinjauIsFinalSigner ? ('✅ Setujui & TTD ' + tinjauPenandatanganLabel) : '✅ Setujui & Teruskan ke Tingkat Berikutnya'"></span>
                                    <span class="block text-xs text-slate-600 mt-0.5"
                                          x-text="tinjauIsFinalSigner ? ('Dokumen disetujui & ditandatangani oleh ' + tinjauPenandatanganLabel + ' untuk kemudian diunggah berkas finalnya.') : 'Dokumen telah diverifikasi dan disetujui untuk dialirkan ke tahap proses berikutnya.'"></span>
                                </div>
                            </label>

                            <!-- Opsi 2: Kembalikan -->
                            <label class="flex items-start p-3.5 border-2 rounded-2xl cursor-pointer transition select-none"
                                   :class="tinjauKeputusan === 'kembalikan' ? 'card-radio-kembalikan-active' : 'border-slate-200 hover:border-slate-300 bg-white shadow-xs'">
                                <input type="radio" name="keputusan" value="kembalikan" x-model="tinjauKeputusan" class="mt-1 text-rose-600 focus:ring-0 border-2 border-slate-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-extrabold text-rose-700">↩️ Kembalikan / Perlu Revisi</span>
                                    <template x-if="!tinjauOpsiKembali || tinjauOpsiKembali.length <= 1">
                                        <span class="block text-xs text-rose-900 mt-0.5">
                                            Surat akan dikembalikan ke: <strong class="underline font-bold text-rose-950" x-text="(tinjauOpsiKembali && tinjauOpsiKembali.length === 1) ? tinjauOpsiKembali[0].label : tinjauKembaliTarget"></strong> untuk diperbaiki sesuai instruksi.
                                        </span>
                                    </template>
                                    <template x-if="tinjauOpsiKembali && tinjauOpsiKembali.length > 1">
                                        <span class="block text-xs text-rose-900 mt-0.5">
                                            Surat dapat dikembalikan ke tingkatan alur yang telah dilewati sebelumnya (dapat dipilih di bawah) untuk perbaikan.
                                        </span>
                                    </template>
                                </div>
                            </label>

                            <!-- Opsi 3: Ditolak -->
                            <label class="flex items-start p-3.5 border-2 rounded-2xl cursor-pointer transition select-none"
                                   :class="tinjauKeputusan === 'ditolak' ? 'card-radio-ditolak-active' : 'border-slate-200 hover:border-slate-300 bg-white shadow-xs'">
                                <input type="radio" name="keputusan" value="ditolak" x-model="tinjauKeputusan" class="mt-1 text-rose-700 focus:ring-0 border-2 border-slate-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-extrabold text-rose-800">❌ Ditolak / Berhenti Proses</span>
                                    <span class="block text-xs text-rose-900 mt-0.5">
                                        Dokumen surat ditolak dan seluruh alur proses dihentikan secara permanen.
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- KONTEN BILA KEPUTUSAN = TERUSKAN -->
                    <div x-show="tinjauKeputusan === 'teruskan'">
                        <!-- Khusus Surat Rahasia: Pilih Teruskan Bebas ke Pejabat Lain ATAU Tandatangani & Selesaikan -->
                        <div x-show="tinjauIsRahasia" class="mb-4 p-4 bg-rose-50 border-2 border-rose-300 rounded-2xl text-rose-950">
                            <label class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-2">
                                🔒 Tindakan Surat Rahasia <span class="text-rose-500">*</span>
                            </label>
                            <div class="space-y-2 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-cartoon font-bold text-slate-800">
                                    <input type="radio" name="is_final_sign" value="0" x-model="tinjauIsRahasiaFinal" class="text-rose-600 focus:ring-0">
                                    <span>Teruskan Rahasia ke Pejabat Lain (Paraf Lanjutan)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-cartoon font-bold text-slate-800">
                                    <input type="radio" name="is_final_sign" value="1" x-model="tinjauIsRahasiaFinal" class="text-emerald-600 focus:ring-0">
                                    <span>Tandatangani & Selesaikan Surat Rahasia (Siap Unggah Berkas Final)</span>
                                </label>
                            </div>
                            <div x-show="tinjauIsRahasiaFinal === '0' || !tinjauIsRahasiaFinal">
                                <label for="pilih_tujuan_rahasia_modal" class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">
                                    Pilih Pejabat Tujuan Berikutnya <span class="text-rose-500">*</span>
                                </label>
                                <select id="pilih_tujuan_rahasia_modal" 
                                        name="tujuan_rahasia_role" 
                                        x-model="tinjauTujuanRahasiaRole"
                                        :disabled="tinjauKeputusan !== 'teruskan' || !tinjauIsRahasia || tinjauIsRahasiaFinal === '1'"
                                        class="w-full text-xs font-cartoon font-bold border-2 border-rose-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5 focus:ring-0 focus:border-rose-500">
                                    <option value="" disabled selected class="text-slate-400">-- Pilih Pejabat Penerima Surat Rahasia --</option>
                                    @foreach($allGroupedRoles ?? \App\Models\User::getGroupedRoleLabels() as $groupName => $roles)
                                        <optgroup label="{{ $groupName }}" class="font-bold text-[#003366] bg-slate-100">
                                            @foreach($roles as $rKey => $rLabel)
                                                <option value="{{ $rKey }}" class="text-slate-800 font-normal bg-white">{{ $rLabel }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-rose-800 mt-1.5 font-medium">
                                    Surat rahasia bersifat tertutup dan hanya dapat dibuka oleh pejabat yang Anda pilih di atas.
                                </p>
                            </div>
                        </div>

                        <!-- Khusus Unit / Prodi / Lab untuk Surat SPT: Wajib Memilih PPK Tujuan -->
                        <div x-show="tinjauRole === 'unit' && tinjauIsSpt && !tinjauIsFinalSigner && !tinjauIsRahasia" class="mb-4 p-4 bg-amber-50 border-2 border-amber-400 rounded-2xl text-amber-950">
                            <label for="pilih_tujuan_ppk_modal" class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">
                                Pilih PPK (Pejabat Pembuat Komitmen) yang Dituju <span class="text-rose-500">*</span>
                            </label>
                            <select id="pilih_tujuan_ppk_modal" 
                                    name="tujuan_ppk" 
                                    x-model="tinjauTujuanPpk"
                                    :disabled="tinjauKeputusan !== 'teruskan' || tinjauRole !== 'unit' || !tinjauIsSpt || tinjauIsFinalSigner || tinjauIsRahasia"
                                    :required="tinjauKeputusan === 'teruskan' && tinjauRole === 'unit' && tinjauIsSpt && !tinjauIsFinalSigner && !tinjauIsRahasia" 
                                    class="w-full text-xs font-cartoon font-bold border-2 border-amber-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5 focus:ring-0 focus:border-amber-500">
                                <option value="" disabled selected class="text-slate-400">-- Pilih PPK Penerima SPT --</option>
                                @foreach($allPpkRoles ?? \App\Models\User::getPpkRoles() as $pKey => $pLabel)
                                    <option value="{{ $pKey }}">{{ $pLabel }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-amber-800 mt-1.5 font-medium">
                                💼 Sesuai SOP surat SPT: Setelah diperiksa unit, draf wajib diteruskan ke PPK sebelum ke Kasubbag.
                            </p>
                        </div>

                        <!-- Khusus Unit / Prodi / Lab untuk Surat NON-SPT: Wajib Memilih Kasubbag Tujuan -->
                        <div x-show="tinjauRole === 'unit' && !tinjauIsSpt && !tinjauIsFinalSigner && !tinjauIsRahasia" class="mb-4 p-4 bg-amber-50 border-2 border-amber-300 rounded-2xl text-amber-950">
                            <label for="pilih_tujuan_kasubbag_modal" class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">
                                Pilih Kepala Subbagian (Kasubbag) yang Dituju <span class="text-rose-500">*</span>
                            </label>
                            <select id="pilih_tujuan_kasubbag_modal" 
                                    name="tujuan_kasubbag" 
                                    x-model="tinjauTujuanKasubbag"
                                    :disabled="tinjauKeputusan !== 'teruskan' || tinjauRole !== 'unit' || tinjauIsSpt || tinjauIsFinalSigner || tinjauIsRahasia"
                                    :required="tinjauKeputusan === 'teruskan' && tinjauRole === 'unit' && !tinjauIsSpt && !tinjauIsFinalSigner && !tinjauIsRahasia" 
                                    class="w-full text-xs font-cartoon font-bold border-2 border-amber-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5 focus:ring-0 focus:border-amber-500">
                                <option value="" disabled selected class="text-slate-400">-- Pilih Kasubbag Pemeriksa Lanjutan --</option>
                                @foreach(\App\Models\User::getKasubbagRoles() as $kRoleKey => $kRoleName)
                                    <option value="{{ $kRoleKey }}">{{ $kRoleName }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-amber-800 mt-1.5">
                                Surat akan diparaf oleh pemeriksa unit dan dialirkan langsung ke antrean Kasubbag terpilih.
                            </p>
                        </div>

                        <!-- Khusus PPK (Kasus 3): Jika draf diawali dari Unit, PPK memilih Kasubbag tujuan -->
                        <div x-show="tinjauRole === 'ppk' && tinjauTujuanUnit && !tinjauIsRahasia" class="mb-4 p-4 bg-amber-50 border-2 border-amber-300 rounded-2xl text-amber-950">
                            <label for="pilih_tujuan_kasubbag_ppk_modal" class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">
                                Pilih Kepala Subbagian (Kasubbag) yang Dituju <span class="text-rose-500">*</span>
                            </label>
                            <select id="pilih_tujuan_kasubbag_ppk_modal" 
                                    name="tujuan_kasubbag" 
                                    x-model="tinjauTujuanKasubbag"
                                    :disabled="tinjauKeputusan !== 'teruskan' || tinjauRole !== 'ppk' || !tinjauTujuanUnit || tinjauIsRahasia"
                                    :required="tinjauKeputusan === 'teruskan' && tinjauRole === 'ppk' && tinjauTujuanUnit && !tinjauIsRahasia" 
                                    class="w-full text-xs font-cartoon font-bold border-2 border-amber-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5 focus:ring-0 focus:border-amber-500">
                                <option value="" disabled selected class="text-slate-400">-- Pilih Kasubbag Pemeriksa Lanjutan --</option>
                                @foreach(\App\Models\User::getKasubbagRoles() as $kRoleKey => $kRoleName)
                                    <option value="{{ $kRoleKey }}">{{ $kRoleName }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-amber-800 mt-1.5 font-medium">
                                💼 Dokumen SPT diawali dari Unit/Prodi/Lab. Silakan tentukan Kasubbag tujuan untuk pemeriksaan tata naskah lanjutan.
                            </p>
                        </div>

                        <!-- Khusus PPK (Kasus 2): Jika draf diawali dari Kasubbag, setelah dari PPK langsung melanjutkan ke Kabag -->
                        <div x-show="tinjauRole === 'ppk' && !tinjauTujuanUnit && !tinjauIsRahasia" class="mb-4 p-4 bg-sky-50 border-2 border-[#003366] rounded-2xl text-[#003366] shadow-[2px_2px_0px_#003366]">
                            <div class="flex items-start gap-2.5">
                                <span class="text-xl">💼</span>
                                <div>
                                    <h5 class="text-xs font-bold uppercase tracking-wider text-[#003366]">Alur Lanjutan SPT ke Kabag</h5>
                                    <p class="text-[11px] text-slate-700 mt-0.5 leading-relaxed font-medium">
                                        Draf SPT ini diawali dari pemeriksaan Kasubbag. Setelah diverifikasi & disetujui PPK, dokumen akan <strong>langsung melanjutkan ke Kepala Bagian (Kabag)</strong> dan seterusnya tanpa memilih Kasubbag kembali.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Khusus Kasubbag (Kasus 2): Jika Kasubbag menjadi pemeriksa pertama pada SPT, mengalir ke PPK -->
                        <div x-show="tinjauRole === 'kasubbag' && tinjauIsSpt && !tinjauTujuanUnit && !tinjauIsRahasia" class="mb-4 p-4 bg-amber-50 border-2 border-[#003366] rounded-2xl text-[#003366] shadow-[2px_2px_0px_#003366]">
                            <div class="flex items-start gap-2.5">
                                <span class="text-xl">💼</span>
                                <div>
                                    <h5 class="text-xs font-bold uppercase tracking-wider text-[#003366]">Verifikasi Awal Kasubbag (SPT)</h5>
                                    <p class="text-[11px] text-slate-700 mt-0.5 leading-relaxed font-medium">
                                        Sebagai pemeriksa pertama dokumen SPT, setelah Anda setujui draf ini akan <strong>diteruskan ke Pejabat Pembuat Komitmen (PPK)</strong> yang telah ditentukan oleh Penyusun.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Khusus Sekretaris Kabag (Jika bukan penandatangan final): Wajib Memilih Sekretaris Wadir yang Dituju -->
                        <div x-show="tinjauRole === 'sekretaris_kabag' && !tinjauIsFinalSigner && !tinjauIsRahasia" class="mb-4 p-4 bg-blue-50 border-2 border-blue-300 rounded-2xl text-blue-950">
                            <label for="pilih_tujuan_wadir_modal" class="block text-xs font-bold text-[#003366] uppercase tracking-wider mb-1.5">
                                Pilih Sekretaris Wakil Direktur yang Dituju <span class="text-rose-500">*</span>
                            </label>
                            <select id="pilih_tujuan_wadir_modal" 
                                    name="tujuan_wadir" 
                                    x-model="tinjauTujuanWadir"
                                    :disabled="tinjauKeputusan !== 'teruskan' || tinjauRole !== 'sekretaris_kabag' || tinjauIsFinalSigner || tinjauIsRahasia"
                                    :required="tinjauKeputusan === 'teruskan' && tinjauRole === 'sekretaris_kabag' && !tinjauIsFinalSigner && !tinjauIsRahasia" 
                                    class="w-full text-xs font-cartoon font-bold border-2 border-blue-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5 focus:ring-0 focus:border-[#003366]">
                                <option value="" disabled selected class="text-slate-400">-- Pilih Sekretaris Wakil Direktur --</option>
                                @foreach(\App\Models\User::getSekretarisWadirRoles() as $wRoleKey => $wRoleName)
                                    <option value="{{ $wRoleKey }}">{{ $wRoleName }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-blue-800 mt-1.5">
                                Surat akan diparaf atas nama Kabag dan dialirkan langsung ke antrean Wakil Direktur terpilih.
                            </p>
                        </div>

                        <!-- Catatan / Arahan Tambahan (Opsional) saat Diteruskan -->
                        <div class="mb-2">
                            <label for="komentar_teruskan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Catatan / Arahan Tambahan (Opsional)
                            </label>
                            <textarea id="komentar_teruskan" 
                                      name="komentar" 
                                      :disabled="tinjauKeputusan !== 'teruskan'"
                                      rows="2" 
                                      placeholder="Tuliskan catatan tambahan jika ada..." 
                                      class="w-full text-xs font-cartoon font-medium border-2 border-slate-300 rounded-xl shadow-xs bg-white text-slate-900 placeholder-slate-400 p-2.5 focus:border-[#003366] focus:ring-0"></textarea>
                            <p class="text-[11px] text-slate-500 mt-1">Catatan ini akan tersimpan pada riwayat tracking surat.</p>
                        </div>
                    </div>

                    <!-- KONTEN BILA KEPUTUSAN = KEMBALIKAN -->
                    <div x-show="tinjauKeputusan === 'kembalikan'">
                        <!-- Pilihan Tujuan Pengembalian Berdasarkan Alur yang Sudah Dilewati (DROPDOWN) -->
                        <div class="mb-4">
                            <label for="pilih_target_kembali_dropdown" class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">
                                Pilih Tingkat Tujuan Pengembalian <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-600 mb-2">
                                Pilih tingkatan dari alur yang sudah dilalui dokumen untuk menerima instruksi perbaikan revisi:
                            </p>
                            
                            <!-- Template dropdown bila ada opsi kembali -->
                            <template x-if="tinjauOpsiKembali && tinjauOpsiKembali.length > 0">
                                <select id="pilih_target_kembali_dropdown" 
                                        name="target_kembali" 
                                        x-model="tinjauTargetKembali"
                                        :disabled="tinjauKeputusan !== 'kembalikan'"
                                        class="w-full text-xs font-cartoon font-bold border-2 border-rose-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5 focus:ring-0 focus:border-rose-500">
                                    <template x-for="(opsi, idx) in tinjauOpsiKembali" :key="opsi.key">
                                        <option :value="opsi.key" x-text="opsi.label + ' (' + opsi.desc + ')'"></option>
                                    </template>
                                </select>
                            </template>

                            <!-- Fallback jika array opsi kosong -->
                            <template x-if="!tinjauOpsiKembali || tinjauOpsiKembali.length === 0">
                                <select name="target_kembali" 
                                        :disabled="tinjauKeputusan !== 'kembalikan'"
                                        class="w-full text-xs font-cartoon font-bold border-2 border-rose-300 rounded-xl shadow-xs bg-white text-slate-900 p-2.5">
                                    <option value="penyusun" selected x-text="tinjauKembaliTarget || 'Penyusun'"></option>
                                </select>
                            </template>
                        </div>

                        <!-- Textarea Komentar Revisi (Wajib Diisi) -->
                        <div class="mb-3.5">
                            <label for="komentar_kembalikan" class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">
                                Alasan Pengembalian / Catatan Revisi <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="komentar_kembalikan" 
                                      name="komentar" 
                                      x-model="tinjauKomentar" 
                                      :disabled="tinjauKeputusan !== 'kembalikan'"
                                      :required="tinjauKeputusan === 'kembalikan'" 
                                      rows="3" 
                                      placeholder="Contoh: Lampiran belum lengkap, format nomor surat mohon disesuaikan dengan pedoman tata naskah..." 
                                      class="w-full text-xs font-cartoon font-medium border-2 border-rose-300 rounded-xl shadow-xs bg-white text-slate-900 placeholder-slate-400 p-2.5 focus:ring-0 focus:border-rose-500"></textarea>
                            <p class="text-[11px] text-rose-800 mt-1 font-medium">Catatan ini akan ditampilkan pada kolom Status dan tercatat di Tracking Surat.</p>
                        </div>

                        <!-- Attach File Lampiran Koreksi (Opsional) -->
                        <div class="mb-2 p-3.5 bg-white border-2 border-slate-200 rounded-2xl shadow-xs">
                            <label for="lampiran_koreksi_kembalikan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                📎 Lampirkan File Koreksi / Catatan Revisi (Opsional)
                            </label>
                            <input type="file" 
                                   id="lampiran_koreksi_kembalikan" 
                                   name="file_lampiran" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   :disabled="tinjauKeputusan !== 'kembalikan'"
                                   class="w-full text-xs text-slate-600 border-2 border-slate-300 rounded-xl p-2 bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-2 file:border-rose-300 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-900 hover:file:bg-rose-200 transition cursor-pointer">
                            <p class="text-[11px] text-slate-500 mt-1.5">
                                Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Maksimal 10MB). Unggah berkas berisi coretan atau catatan perbaikan jika ada.
                            </p>
                        </div>
                    </div>

                    <!-- KONTEN BILA KEPUTUSAN = DITOLAK -->
                    <div x-show="tinjauKeputusan === 'ditolak'">
                        <!-- Textarea Alasan Penolakan (Wajib Diisi) -->
                        <div class="mb-3.5">
                            <label for="komentar_ditolak" class="block text-xs font-bold text-rose-900 uppercase tracking-wider mb-1.5">
                                Alasan Penolakan Dokumen <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="komentar_ditolak" 
                                      name="komentar" 
                                      x-model="tinjauKomentar" 
                                      :disabled="tinjauKeputusan !== 'ditolak'"
                                      :required="tinjauKeputusan === 'ditolak'" 
                                      rows="3" 
                                      placeholder="Tuliskan alasan lengkap penolakan dokumen surat ini..." 
                                      class="w-full text-xs font-cartoon font-medium border-2 border-rose-300 rounded-xl shadow-xs bg-white text-slate-900 placeholder-slate-400 p-2.5 focus:ring-0 focus:border-rose-500"></textarea>
                            <p class="text-[11px] text-rose-800 mt-1 font-medium">Alasan ini akan ditampilkan pada status dokumen dan tercatat permanen di riwayat tracking surat.</p>
                        </div>

                        <!-- Attach File Lampiran Catatan Penolakan (Opsional) -->
                        <div class="mb-2 p-3.5 bg-white border-2 border-slate-200 rounded-2xl shadow-xs">
                            <label for="lampiran_koreksi_ditolak" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                📎 Lampirkan File Catatan Penolakan (Opsional)
                            </label>
                            <input type="file" 
                                   id="lampiran_koreksi_ditolak" 
                                   name="file_lampiran" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   :disabled="tinjauKeputusan !== 'ditolak'"
                                   class="w-full text-xs text-slate-600 border-2 border-slate-300 rounded-xl p-2 bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-2 file:border-rose-300 file:text-xs file:font-bold file:bg-rose-100 file:text-rose-900 hover:file:bg-rose-200 transition cursor-pointer">
                            <p class="text-[11px] text-slate-500 mt-1.5">
                                Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Maksimal 10MB). Unggah berkas jika ada catatan penolakan.
                            </p>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 mt-5 border-t-2 border-slate-200">
                        <button type="button" @click="closeTinjauModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl text-xs transition cursor-pointer">
                            Batal
                        </button>
                        
                        <!-- Tombol Submit Teruskan / Setujui & Diarsipkan -->
                        <template x-if="tinjauKeputusan === 'teruskan'">
                            <button type="button" 
                                    @click="handleTinjauSetujui($event)"
                                    class="px-5 py-2.5 btn-modal-teruskan text-white font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span class="text-sm">✅</span>
                                <span x-text="tinjauIsFinalSigner ? ('Setujui & TTD ' + tinjauPenandatanganLabel) : 'Setujui & Teruskan Dokumen'"></span>
                            </button>
                        </template>

                        <!-- Tombol Submit Kembalikan -->
                        <template x-if="tinjauKeputusan === 'kembalikan'">
                            <button type="submit" class="px-5 py-2.5 btn-modal-kembalikan text-white font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span class="text-sm">↩️</span>
                                <span>Kembalikan Dokumen Surat</span>
                            </button>
                        </template>

                        <!-- Tombol Submit Ditolak -->
                        <template x-if="tinjauKeputusan === 'ditolak'">
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin MENOLAK dokumen surat ini? Seluruh alur proses akan dihentikan secara permanen.');"
                                    class="px-5 py-2.5 btn-modal-ditolak text-white font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span class="text-sm">❌</span>
                                <span>Tolak Dokumen Surat</span>
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             style="display: none;">
             
            <div @click.away="closeEditDraftModal()" 
                 x-show="showEditDraftModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-800">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-white border-2 border-white/20 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            ✏️
                        </div>
                        <div>
                            <h3 class="text-base font-cartoon font-bold text-white" x-text="editDraftIsRevisi ? 'Perbaiki & Ajukan Ulang Draft Surat' : 'Edit / Perbaiki Dokumen Draft Surat'">Perbaiki & Ajukan Ulang Draft Surat</h3>
                            <p class="text-xs text-blue-100/90 font-cartoon" x-text="editDraftIsRevisi ? 'Perbarui judul, jenis, tujuan, atau unggah file draft yang sudah direvisi.' : 'Perbaiki judul, jenis surat, atau berkas draft sebelum diperiksa oleh atasan.'">Perbarui judul, jenis, tujuan, atau unggah file draft yang sudah direvisi.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEditDraftModal()" class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Revisi Draft -->
                <form :action="editDraftActionUrl" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto flex-1 bg-[#F8FAFC] text-slate-800 font-cartoon">
                    @csrf
                    
                    <!-- Catatan Revisi dari Pemeriksa -->
                    <div class="mb-4 p-4 bg-rose-50 border-2 border-rose-300 rounded-2xl text-xs text-rose-900 shadow-xs" x-show="editDraftCatatan || editDraftLampiran">
                        <template x-if="editDraftCatatan">
                            <div>
                                <span class="font-bold flex items-center gap-1.5 text-rose-800 mb-1">
                                    <span>💬</span> Catatan Revisi dari Pemeriksa:
                                </span>
                                <p class="bg-white p-3 rounded-xl border border-rose-200 text-rose-950 font-medium leading-relaxed whitespace-pre-line shadow-xs" x-text="editDraftCatatan"></p>
                            </div>
                        </template>
                        <template x-if="editDraftLampiran">
                            <div class="mt-2.5" :class="editDraftCatatan ? 'pt-2.5 border-t border-rose-200' : ''">
                                <a :href="editDraftLampiran" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-bold rounded-xl border-2 border-rose-300 transition shadow-sm">
                                    <span>📎</span> Unduh File Koreksi / Lampiran Revisi dari Pemeriksa
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Input Judul Surat -->
                    <div class="mb-4">
                        <label for="edit_judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Judul Dokumen Surat <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="edit_judul" name="judul" x-model="editDraftJudul" required class="w-full text-xs font-cartoon font-bold border-2 border-slate-300 rounded-xl shadow-xs focus:ring-0 focus:border-[#003366] bg-white text-slate-900 placeholder-slate-400 p-2.5">
                    </div>

                    <!-- Input Jenis Surat -->
                    <div class="mb-4">
                        <label for="edit_jenis_surat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jenis Surat <span class="text-rose-500">*</span>
                        </label>
                        <select id="edit_jenis_surat" name="jenis_surat_id" x-model="editDraftJenisId" required class="w-full text-xs font-cartoon font-bold border-2 border-slate-300 rounded-xl shadow-xs focus:ring-0 focus:border-[#003366] bg-white text-slate-900 p-2.5">
                            @foreach($allJenisSurat ?? [] as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Kasubbag yang Dituju (Jika bukan dari Unit) -->
                    <div class="mb-4" x-show="editDraftHasKasubbag">
                        <label for="edit_tujuan_kasubbag" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kepala Subbagian (Kasubbag) yang Dituju <span class="text-rose-500">*</span>
                        </label>
                        <select id="edit_tujuan_kasubbag" name="tujuan_kasubbag" x-model="editDraftKasubbag" :required="editDraftHasKasubbag" class="w-full text-xs font-cartoon font-bold border-2 border-slate-300 rounded-xl shadow-xs focus:ring-0 focus:border-[#003366] bg-white text-slate-900 p-2.5">
                            @foreach($allKasubbagRoles ?? [] as $kKey => $kLabel)
                                <option value="{{ $kKey }}">{{ $kLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input File Draft Baru (Opsional) -->
                    <div class="mb-5">
                        <label for="edit_file_draft" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <span x-text="editDraftIsRevisi ? 'Unggah File Draft Hasil Revisi (PDF / Word)' : 'Ganti File Draft (Opsional, PDF / Word)'">Unggah File Draft Hasil Revisi (PDF / Word)</span>
                        </label>
                        <input type="file" id="edit_file_draft" name="file_draft" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-600 border-2 border-slate-300 rounded-xl p-2 bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-2 file:border-[#003366] file:text-xs file:font-bold file:bg-blue-100 file:text-[#003366] hover:file:bg-blue-200 transition cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">
                            Format yang didukung: PDF, DOC, DOCX (Maksimal 5MB). Biarkan kosong jika tidak mengubah file draft.
                        </p>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t-2 border-slate-200">
                        <button type="button" @click="closeEditDraftModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl text-xs transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 btn-pop-navy font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
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
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-800">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-white border-2 border-white/20 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            📤
                        </div>
                        <div>
                            <h3 class="text-base font-cartoon font-bold text-white">Upload File Final (Telah Di-TTD)</h3>
                            <p class="text-xs text-blue-100/90 font-cartoon">Unggah dokumen resmi ber-TTD <span x-text="uploadFinalPenandatanganLabel"></span> untuk menyelesaikan dan mengarsipkan surat.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeUploadFinalModal()" class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Upload Final -->
                <form :action="uploadFinalActionUrl" method="POST" enctype="multipart/form-data" @submit.prevent="submitUploadFinal($event)" class="p-6 overflow-y-auto flex-1 bg-[#F8FAFC] text-slate-800 font-cartoon">
                    @csrf
                    <input type="hidden" name="catatan" :value="uploadFinalCatatan">
                    
                    <!-- Info Surat -->
                    <div class="mb-4 p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Judul Dokumen Surat</label>
                        <p class="text-sm font-extrabold text-[#003366]" x-text="uploadFinalJudul"></p>
                    </div>

                    <!-- Instruksi -->
                    <div class="mb-4 p-4 bg-emerald-50 border-2 border-emerald-300 rounded-2xl text-xs text-emerald-950 shadow-xs">
                        <p class="font-extrabold flex items-center gap-1.5 text-emerald-900 mb-1">
                            <span>📌</span> Instruksi Pengarsipan:
                        </p>
                        <p class="text-emerald-900 leading-relaxed">
                            Pastikan file yang diunggah adalah dokumen yang telah <strong x-text="'ditandatangani oleh ' + uploadFinalPenandatanganLabel"></strong>, diberi stempel/cap resmi, dan berformat <strong>PDF (Maksimal 5MB)</strong>.
                        </p>
                    </div>

                    <!-- Input Nomor Surat Resmi (Wajib) -->
                    <div class="mb-4">
                        <label for="input_nomor_surat_modal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Surat Resmi <span class="text-rose-500">*</span>
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
                                   class="w-full text-xs text-slate-900 placeholder-slate-400 border-2 border-slate-300 rounded-xl pl-10 pr-3.5 py-2.5 bg-white shadow-xs focus:ring-0 focus:border-[#003366] transition font-mono font-bold">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Nomor surat resmi wajib dimasukkan sesuai dengan nomor yang tertera pada dokumen fisik/ber-TTD.</p>
                    </div>

                    <!-- Input File Final (.pdf) -->
                    <div class="mb-5">
                        <label for="input_file_final_modal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih File Final (.pdf) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" 
                               id="input_file_final_modal" 
                               name="file_final" 
                               accept=".pdf,application/pdf" 
                               required 
                               class="w-full text-xs text-slate-600 border-2 border-slate-300 rounded-xl p-2.5 bg-slate-50 shadow-xs file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-2 file:border-emerald-600 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-950 hover:file:bg-emerald-200 transition cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">Dokumen ini akan menjadi arsip digital resmi dan dapat diunduh oleh pihak terkait.</p>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t-2 border-slate-200">
                        <button type="button" 
                                @click="simpanTtdNanti()" 
                                class="text-xs text-[#003366] hover:text-blue-700 font-cartoon font-bold underline transition"
                                title="Setujui TTD sekarang dan unggah file PDF nanti">
                            ⏳ Unggah File Nanti
                        </button>
                        
                        <div class="flex items-center gap-2.5">
                            <button type="button" @click="closeUploadFinalModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl text-xs transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" 
                                    :disabled="isUploadingFinal" 
                                    class="px-5 py-2.5 btn-aksi-teal font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
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
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-lg w-full overflow-hidden flex flex-col text-slate-800 relative font-cartoon">
                
                <!-- Top Accent Bar (Kuning PU) -->
                <div class="h-2 w-full bg-[#F5B800] border-b-2 border-[#003366]"></div>

                <!-- Tombol Silang Kanan Atas -->
                <button type="button" 
                        @click="closeArsipModal(true)" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-800 hover:bg-slate-100 p-2 rounded-xl border border-transparent hover:border-slate-300 transition cursor-pointer z-10" 
                        title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="p-6 sm:p-8 flex flex-col items-center text-center">
                    
                    <!-- Badge Icon Arsip -->
                    <div class="w-20 h-20 rounded-3xl bg-emerald-100 border-2 border-emerald-600 flex items-center justify-center text-4xl mb-4 shadow-[4px_4px_0px_#065F46] text-emerald-900">
                        <span>🗄️</span>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 border-2 border-emerald-300 text-emerald-900 text-xs font-bold tracking-wider uppercase mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Dokumen Resmi Telah Diarsipkan</span>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-[#003366] mb-2">
                        Dokumen Berhasil Diarsipkan!
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6 font-cartoon">
                        Seluruh rangkaian tinjauan dan tanda tangan telah selesai. Berkas final dokumen resmi telah berhasil diarsipkan ke dalam sistem <span class="text-[#003366] font-extrabold">SURATKU</span>.
                    </p>

                    <!-- Card Rincian Dokumen yang Diarsipkan -->
                    <div class="w-full bg-[#F8FAFC] border-2 border-slate-200 rounded-2xl p-4 text-left mb-6 space-y-3 shadow-xs">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Judul Dokumen Surat</span>
                            <p class="text-sm font-extrabold text-[#003366] leading-snug" x-text="arsipData?.judul || '-'"></p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-slate-200">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Nomor Surat Resmi</span>
                                <div class="flex items-center gap-1.5 text-xs font-mono font-bold text-emerald-700">
                                    <span>📄</span>
                                    <span x-text="arsipData?.nomor_surat || '-'"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Status Dokumen</span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-900 border-2 border-emerald-300 text-xs font-bold">
                                    <span>✅</span> DIARSIPKAN
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-slate-200">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Ditandatangani Oleh</span>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    <span>✍️</span>
                                    <span x-text="arsipData?.penandatangan || 'Pejabat Penandatangan'"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Waktu Pengarsipan</span>
                                <div class="text-xs text-slate-700 font-medium" x-text="arsipData?.waktu || '-'"></div>
                            </div>
                        </div>

                        <div class="pt-2.5 border-t border-slate-200">
                            <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Diunggah / Diarsipkan Oleh</span>
                            <div class="text-xs text-slate-700 font-medium truncate" x-text="arsipData?.diarsipkan_oleh || '-'"></div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="w-full p-3.5 rounded-2xl bg-blue-50 border-2 border-blue-200 text-[11px] text-blue-950 text-left mb-6 flex items-start gap-2.5">
                        <span class="text-[#003366] text-base leading-none mt-0.5">💡</span>
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
                                    class="w-full sm:flex-1 py-3 px-4 btn-pop-navy font-cartoon font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>👁️</span>
                                <span>Pratinjau File Final</span>
                            </button>
                        </template>

                        <!-- Tombol Tutup -->
                        <button type="button" 
                                @click="closeArsipModal(true)" 
                                class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl text-xs transition cursor-pointer">
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
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
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-lg w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-800">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-white border-2 border-white/20 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            📝
                        </div>
                        <div>
                            <h3 class="text-base font-cartoon font-bold text-white">
                                <span x-show="editArsipCanChangeFile">Edit Berkas & Nomor Surat</span>
                                <span x-show="!editArsipCanChangeFile">Edit / Berikan Nomor Surat</span>
                            </h3>
                            <p class="text-xs text-blue-100/90 font-cartoon">Kelola dan perbarui data arsip dokumen resmi pada sistem.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEditArsipModal()" class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Edit Arsip -->
                <form :action="editArsipActionUrl" method="POST" enctype="multipart/form-data" @submit="isUpdatingArsip = true" class="p-6 overflow-y-auto flex-1 bg-[#F8FAFC] text-slate-800 font-cartoon">
                    @csrf
                    
                    <!-- Info Surat Ringkas -->
                    <div class="mb-4 p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs space-y-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Judul Dokumen Surat</label>
                            <p class="text-sm font-extrabold text-[#003366] leading-snug" x-text="editArsipJudul"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200 text-xs">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block">Penandatangan</span>
                                <span class="text-slate-800 font-bold" x-text="editArsipPenandatanganLabel"></span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block">Pengunggah Dokumen</span>
                                <span class="text-slate-800 font-bold" x-text="editArsipPengunggahName"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Input Nomor Surat Resmi (Wajib) -->
                    <div class="mb-4">
                        <label for="input_edit_nomor_surat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Surat Resmi <span class="text-rose-500">*</span>
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
                                   class="w-full text-xs text-slate-900 placeholder-slate-400 border-2 border-slate-300 rounded-xl pl-10 pr-3.5 py-2.5 bg-white shadow-xs focus:ring-0 focus:border-[#003366] transition font-mono font-bold">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Dapat diisi atau disunting oleh akun pengunggah, Sekretaris Direktur, maupun Super Admin.
                        </p>
                    </div>

                    <!-- Input Ganti Berkas Final PDF (Khusus Pengunggah / Super Admin) -->
                    <template x-if="editArsipCanChangeFile">
                        <div class="mb-4">
                            <label for="input_edit_file_final" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Ganti Berkas Final PDF <span class="text-slate-500 font-normal lowercase">(opsional)</span>
                            </label>
                            <input type="file" 
                                   id="input_edit_file_final" 
                                   name="file_final" 
                                   accept=".pdf,application/pdf" 
                                   class="w-full text-xs text-slate-600 border-2 border-slate-300 rounded-xl p-2.5 bg-slate-50 shadow-xs file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-2 file:border-amber-600 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-950 hover:file:bg-amber-200 transition cursor-pointer">
                            <p class="text-[11px] text-slate-500 mt-1">
                                Kosongkan jika hanya ingin mengubah Nomor Surat tanpa mengganti file PDF.
                            </p>
                        </div>
                    </template>

                    <!-- Pemberitahuan Hak Akses Berkas Final jika Bukan Pengunggah (misal Sekretaris Direktur) -->
                    <template x-if="!editArsipCanChangeFile">
                        <div class="mb-4 p-3.5 rounded-2xl bg-blue-50 border-2 border-blue-200 text-xs text-blue-950 shadow-xs flex items-start gap-2.5">
                            <span class="text-base text-[#003366] leading-none mt-0.5">ℹ️</span>
                            <div class="leading-relaxed">
                                <span class="font-bold text-[#003366]">Hak Akses Berkas Fisik:</span>
                                Anda dapat mengisi atau merevisi Nomor Surat. Penggantian berkas PDF dokumen hanya diizinkan bagi akun yang melakukan pengarsipan (<span class="font-bold text-slate-900" x-text="editArsipPengunggahName"></span>) atau Super Admin.
                            </div>
                        </div>
                    </template>

                    <!-- Link Pratinjau Berkas Saat Ini -->
                    <template x-if="editArsipFileFinalUrl">
                        <div class="mb-4 flex items-center justify-between p-3 rounded-xl bg-white border-2 border-slate-200 text-xs shadow-xs">
                            <div class="flex items-center gap-2 text-slate-700 truncate">
                                <span>📎</span>
                                <span class="truncate">Berkas PDF saat ini telah tersimpan</span>
                            </div>
                            <a :href="editArsipFileFinalUrl" target="_blank" class="px-2.5 py-1 rounded-lg bg-teal-100 text-teal-950 hover:bg-teal-200 border-2 border-teal-500 font-bold transition whitespace-nowrap inline-flex items-center gap-1">
                                <span>👁️</span> Pratinjau
                            </a>
                        </div>
                    </template>

                    <!-- Catatan Perubahan (Opsional) -->
                    <div class="mb-5">
                        <label for="input_catatan_edit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alasan / Catatan Perubahan <span class="text-slate-500 font-normal lowercase">(opsional)</span>
                        </label>
                        <input type="text" 
                               id="input_catatan_edit" 
                               name="catatan_edit" 
                               placeholder="Contoh: Penyesuaian nomor surat resmi dari TU..." 
                               class="w-full text-xs text-slate-900 placeholder-slate-400 border-2 border-slate-300 rounded-xl px-3.5 py-2.5 bg-white shadow-xs focus:ring-0 focus:border-[#003366] transition">
                        <p class="text-[11px] text-slate-500 mt-1">Catatan ini akan tercatat dalam log riwayat perjalanan surat.</p>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t-2 border-slate-200">
                        <button type="button" @click="closeEditArsipModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl text-xs transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                :disabled="isUpdatingArsip" 
                                class="px-5 py-2.5 btn-aksi-amber font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
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
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:px-6 lg:px-8"
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
                 class="cartoon-card bg-white rounded-3xl border-2 border-[#003366] shadow-[8px_8px_0px_#003366] max-w-2xl w-full overflow-hidden flex flex-col max-h-[92vh] text-slate-800">
                 
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[#002244] bg-[#003366] text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 text-white border-2 border-white/20 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            ℹ️
                        </div>
                        <div>
                            <h3 class="text-base font-cartoon font-bold text-white">Informasi & Rincian Surat</h3>
                            <p class="text-xs text-blue-100/90 font-cartoon">Rincian identitas, alur tujuan, penandatangan, dan riwayat arsip.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeInfoModal()" class="text-white/80 hover:text-white hover:bg-white/15 p-1.5 rounded-xl border border-transparent hover:border-white/30 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="p-6 overflow-y-auto flex-1 bg-[#F8FAFC] text-slate-800 font-cartoon space-y-4">
                    
                    <!-- 1. Identitas Surat -->
                    <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs space-y-3">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-1">Judul Dokumen Surat</span>
                            <h4 class="text-sm sm:text-base font-extrabold text-[#003366] leading-snug" x-text="infoJudul"></h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-slate-200">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Nomor Surat Resmi</span>
                                <div class="flex items-center gap-1.5 text-xs font-mono font-bold text-emerald-700">
                                    <span>📄</span>
                                    <span x-text="infoNomorSurat || 'Belum Ada (Draft/Proses)'"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Jenis Dokumen Surat</span>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-700">
                                    <span>📑</span>
                                    <span x-text="infoJenis"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-slate-200">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Pembuat / Penyusun</span>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    <span>👤</span>
                                    <span x-text="infoPembuat"></span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-0.5">Waktu Pengajuan</span>
                                <div class="flex items-center gap-1.5 text-xs text-slate-700 font-medium">
                                    <span>📅</span>
                                    <span x-text="infoTanggal"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Alur Disposisi & Pemeriksaan -->
                    <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-2.5">
                            🛤️ Alur Disposisi & Pemeriksaan:
                        </span>
                        <div class="space-y-2">
                            <!-- Khusus Surat Rahasia -->
                            <template x-if="infoIsRahasia">
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-rose-50 border-2 border-rose-300 text-xs">
                                    <span class="text-rose-900 font-bold flex items-center gap-1.5">
                                        <span>🔒</span> Dokumen Rahasia Tertutup
                                    </span>
                                    <span class="font-extrabold text-rose-950" x-text="'Ditujukan ke: ' + infoRahasiaTujuan"></span>
                                </div>
                            </template>

                            <template x-if="!infoIsRahasia">
                                <div class="space-y-2">
                                    <!-- Unit / Prodi / Lab -->
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                        <span class="text-slate-600 flex items-center gap-1.5">
                                            <span>🏢</span> Tingkat Unit / Prodi / Lab
                                        </span>
                                        <span class="font-bold" :class="infoUnit && infoUnit !== '-' ? 'text-emerald-700' : 'text-slate-400'" x-text="infoUnit && infoUnit !== '-' ? infoUnit : 'Langsung ke Kasubbag (Tanpa Unit)'"></span>
                                    </div>

                                    <!-- Khusus SPT: PPK -->
                                    <template x-if="infoIsSpt">
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50 border-2 border-amber-300 text-xs">
                                            <span class="text-amber-900 font-bold flex items-center gap-1.5">
                                                <span>💼</span> Verifikasi PPK (SPT)
                                            </span>
                                            <span class="font-bold text-amber-950" x-text="infoPpk && infoPpk !== '-' ? infoPpk : 'Menunggu pemilihan PPK oleh Unit'"></span>
                                        </div>
                                    </template>

                                    <!-- Kasubbag -->
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                        <span class="text-slate-600 flex items-center gap-1.5">
                                            <span>📌</span> Tingkat Kasubbag
                                        </span>
                                        <span class="font-bold text-amber-800" x-text="infoKasubbag"></span>
                                    </div>

                                    <!-- Wadir -->
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                        <span class="text-slate-600 flex items-center gap-1.5">
                                            <span>🎯</span> Tingkat Wakil Direktur
                                        </span>
                                        <span class="font-bold" :class="infoWadir && infoWadir !== '-' ? 'text-purple-700' : 'text-slate-400'" x-text="infoWadir && infoWadir !== '-' ? infoWadir : 'Bukan Alur Wadir'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 3. Tanda Tangan & Pengesahan -->
                    <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 shadow-xs space-y-2.5">
                        <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider block mb-1">
                            ✍️ Pengesahan & Tanda Tangan:
                        </span>
                        
                        <!-- Target Penandatangan (Titik Berhenti) -->
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                            <span class="text-slate-600 flex items-center gap-1.5">
                                <span>🎯</span> Titik Berhenti Penandatangan
                            </span>
                            <span class="font-bold text-[#003366]" x-text="infoPenandatangan"></span>
                        </div>

                        <!-- Status Penandatanganan Selesai -->
                        <template x-if="infoDitandatanganiOleh">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-teal-50 border-2 border-teal-200 text-xs">
                                <span class="text-teal-900 font-bold flex items-center gap-1.5">
                                    <span>✍️</span> Telah Ditandatangani Oleh
                                </span>
                                <span class="font-extrabold text-teal-950" x-text="infoDitandatanganiOleh"></span>
                            </div>
                        </template>

                        <!-- Akun Pengunggah Arsip Dokumen -->
                        <template x-if="infoPengunggahArsip">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50 border-2 border-emerald-200 text-xs">
                                <span class="text-emerald-900 font-bold flex items-center gap-1.5">
                                    <span>🗄️</span> Berkas Final Diarsipkan Oleh
                                </span>
                                <span class="font-extrabold text-emerald-950" x-text="infoPengunggahArsip"></span>
                            </div>
                        </template>
                    </div>

                    <!-- 4. Status Dokumen Terkini -->
                    <div class="p-3.5 rounded-2xl bg-white border-2 border-slate-200 shadow-xs flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status Dokumen Saat Ini:</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-xs" :class="infoStatusBadge" x-text="infoStatus"></span>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="px-6 py-4 bg-white border-t-2 border-slate-200 flex flex-wrap items-center justify-between gap-2.5 flex-shrink-0 font-cartoon">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Tombol Pratinjau Draft -->
                        <template x-if="infoDraftUrl">
                            <button type="button" 
                                    @click="openPreview(infoDraftUrl, infoJudul, infoDraftType)"
                                    class="px-3.5 py-2 btn-aksi-blue font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span>👁️</span> Pratinjau Draft
                            </button>
                        </template>

                        <!-- Tombol Pratinjau / Unduh Final -->
                        <template x-if="infoFinalUrl">
                            <button type="button" 
                                    @click="openPreview(infoFinalUrl, infoJudul + ' (Final)', 'pdf')"
                                    class="px-3.5 py-2 btn-aksi-teal font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                                <span>📥</span> Berkas Final
                            </button>
                        </template>

                        <!-- Tombol Buka Tracking Lengkap -->
                        <button type="button" 
                                @click="openTrackingFromInfo()" 
                                class="px-3.5 py-2 btn-aksi-indigo font-cartoon font-bold rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                            <span>🔍</span> Riwayat Tracking
                        </button>
                    </div>

                    <button type="button" 
                            @click="closeInfoModal()" 
                            class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border-2 border-slate-300 font-cartoon font-bold rounded-xl text-xs transition cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>