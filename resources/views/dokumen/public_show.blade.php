<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Berkas Resmi | PT Bank Sumut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue:#004B87; --dark-blue:#002d54; --bank-orange:#FF7A00; --bg-light:#f4f7fa; }
        body { font-family:'Inter',sans-serif; background:var(--bg-light); color:#334155; }
        .navbar-brand-pub { border-bottom: 4px solid var(--bank-orange); }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 75, 135, 0.08); background: white; overflow: hidden; }
        .info-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 14px; font-weight: 600; color: #1e293b; }
        .btn-view-pdf { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; font-weight: 600; padding: 12px 24px; border-radius: 10px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); transition: all 0.2s; text-decoration: none; }
        .btn-view-pdf:hover { background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3); }
        .status-verif { background-color: #dcfce7; color: #15803d; font-weight: 700; padding: 6px 16px; border-radius: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 justify-content-between">

    <nav class="navbar navbar-expand-lg bg-white shadow-sm navbar-brand-pub py-3">
        <div class="container justify-content-center justify-content-md-between">
            <div class="d-flex align-items-center">
                <img src="https://www.banksumut.co.id/wp-content/uploads/2020/04/Logo-Bank-Sumut-1.png" alt="Logo Bank Sumut" style="height: 35px; object-fit: contain;" class="me-3">
                <div class="border-start border-2 ps-3 d-none d-md-block">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color:var(--dark-blue); font-size:12px;">Sistem Informasi Validasi Arsip</h6>
                    <small class="text-muted" style="font-size:10px;">Divisi Teknologi Informasi PT Bank Sumut</small>
                </div>
            </div>
            <div class="mt-2 mt-md-0">
                <span class="status-verif"><i class="fas fa-check-circle me-1"></i> Terverifikasi Asli</span>
            </div>
        </div>
    </nav>

    <main class="container my-5 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card card-custom">
                    <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);">
                        <h5 class="fw-bold mb-1">Rincian Berkas Dokumen</h5>
                        <small class="opacity-75">ID Sistem: DOC-{{ $dokumen->id }}</small>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-4 border-bottom pb-3">
                            <span class="info-label">Nomor Surat / Dokumen</span>
                            <p class="info-value mb-0" style="color: var(--primary-blue) !important;">{{ $dokumen->no_surat ?? '-' }}</p>
                        </div>

                        <div class="mb-4 border-bottom pb-3">
                            <span class="info-label">Nama Dokumen / Perihal</span>
                            <p class="info-value text-dark mb-0">{{ $dokumen->nama_dokumen ?? '-' }}</p>
                        </div>

                        <div class="row mb-4 border-bottom pb-3">
                            <div class="col-6">
                                <span class="info-label">Kategori</span>
                                <p class="info-value mb-0">{{ $dokumen->kategori ?? '-' }}</p>
                            </div>
                            <div class="col-6">
                                <span class="info-label">Tanggal Masuk</span>
                                <p class="info-value mb-0">{{ $dokumen->tgl_masuk ? \Carbon\Carbon::parse($dokumen->tgl_masuk)->format('d/m/Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-4 border-bottom pb-3">
                            <div class="col-6">
                                <span class="info-label">Wadah Box & Lokasi Rak</span>
                                <p class="info-value mb-0" style="color:var(--bank-orange) !important;">
                                    <i class="fas fa-box me-1"></i>{{ $dokumen->box->kode_box ?? '-' }} — <i class="fas fa-archive ms-1 me-1"></i>{{ $dokumen->box->rak->kode_rak ?? '-' }}
                                </p>
                            </div>
                            <div class="col-6">
                                <span class="info-label">Jenis Dokumen</span>
                                <p class="info-value mb-0">
                                    <span class="badge {{ $dokumen->jenis=='digital' ? 'bg-info text-white' : 'bg-secondary text-white' }} px-2 py-1 text-uppercase" style="font-size:10px;">{{ $dokumen->jenis }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="mb-4 border-bottom pb-3">
                            <span class="info-label">Tanggal Kadaluarsa Retensi</span>
                            <p class="info-value text-danger mb-0">
                                {{ ($dokumen->retensi && $dokumen->retensi->tgl_kadaluarsa) ? \Carbon\Carbon::parse($dokumen->retensi->tgl_kadaluarsa)->format('d/m/Y') : '-' }}
                            </p>
                        </div>

                        <div class="text-center pt-3">
                            @if($dokumen->file)
                                <a href="{{ asset('storage/' . $dokumen->file) }}" target="_blank" class="btn btn-view-pdf d-inline-flex align-items-center gap-2 w-100 justify-content-center">
                                    <i class="fas fa-file-pdf"></i> Buka & Lihat Berkas PDF
                                </a>
                            @else
                                <div class="alert alert-warning py-2 mb-0 border-0 shadow-sm rounded-3" style="font-size:12px;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Berkas fisik aman di gudang, namun file salinan digital PDF belum diunggah.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white py-3 text-center border-top text-muted" style="font-size:10px; font-weight:600; letter-spacing:0.5px;">
        © {{ date('Y') }} PT Bank Sumut • Membangun Sumatera Utara
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>