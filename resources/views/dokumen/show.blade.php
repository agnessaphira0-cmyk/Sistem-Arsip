<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dokumen - Sistem Arsip PT Bank Sumut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #004B87;
            --dark-blue: #002d54;
            --bank-orange: #FF7A00;
            --bg-light: #f4f7fa;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-light); color: #334155; }
        .sidebar { width: 260px; min-height: 100vh; background: linear-gradient(180deg, var(--dark-blue) 0%, var(--primary-blue) 100%); position: fixed; left: 0; top: 0; z-index: 100; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: center; position: relative; }
        .sidebar-brand::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: var(--bank-orange); }
        .sidebar-brand img { height: 35px; width: auto; margin-bottom: 10px; object-fit: contain; }
        .sidebar-brand h6 { color: white; font-weight: 700; font-size: 12px; margin: 0; line-height: 1.4; letter-spacing: 0.5px; }
        .sidebar-brand p { color: rgba(255,255,255,0.6); font-size: 11px; margin: 2px 0 0; font-weight: 500; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; font-size: 14px; font-weight: 500; border-left: 4px solid transparent; }
        .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.08); border-left-color: var(--bank-orange); }
        .sidebar .nav-link i { width: 20px; margin-right: 12px; font-size: 15px; }
        .sidebar-section { padding: 16px 24px 6px; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: white; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); background: white; overflow: hidden; margin-bottom: 24px; }
        .detail-label { font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
        .detail-value { font-weight: 600; color: #1e293b; font-size: 15px; }
        .badge-digital { background-color: #e0f2fe; color: #0369a1; font-weight: 600; }
        .badge-fisik { background-color: #f1f5f9; color: #475569; font-weight: 600; }
        .qr-section { text-align: center; padding: 20px; background: #fafafa; border-radius: 12px; border: 1px dashed #e2e8f0; }
        @media print {
            body * { visibility: hidden; }
            #printableQrArea, #printableQrArea * { visibility: visible; }
            #printableQrArea { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 300px; text-align: center; border: none !important; box-shadow: none !important; }
            .btn, .sidebar, .topbar { display: none !important; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sumut">
        <h6>SISTEM INFORMASI ARSIP</h6>
        <p>PT Bank Sumut</p>
    </div>
    <div class="pt-2">
        <div class="sidebar-section">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('rak.index') }}" class="nav-link"><i class="fas fa-archive"></i> Kelola Rak</a>
        <a href="{{ route('box.index') }}" class="nav-link"><i class="fas fa-box"></i> Kelola Box</a>
        <a href="{{ route('dokumen.index') }}" class="nav-link active"><i class="fas fa-file-alt"></i> Kelola Dokumen</a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color: var(--dark-blue)">Rincian Berkas Dokumen</h5>
            <small class="text-muted">No Surat: <strong class="text-dark">{{ $dokumen->no_surat ?? '-' }}</strong></small>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-dark btn-sm px-3 rounded-3"><i class="fas fa-print me-1"></i> Cetak Label QR</button>
            <a href="{{ route('dokumen.index') }}" class="btn btn-light border btn-sm px-3 rounded-3">Kembali</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-label">Nomor Surat / Dokumen</div>
                            <div class="detail-value text-primary">{{ $dokumen->no_surat ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Nama Dokumen</div>
                            <div class="detail-value">{{ $dokumen->nama_dokumen ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Kategori</div>
                            <div class="detail-value">{{ $dokumen->kategori ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Wadah Box & Lokasi Rak</div>
                            <div class="detail-value">
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 fs-13">
                                    📦 {{ $dokumen->box->kode_box ?? 'Belum ada Box' }} — Rak: {{ $dokumen->box->rak->kode_rak ?? 'Belum ada Rak' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Jenis Dokumen</div>
                            <div>
                                <span class="badge px-3 py-2 rounded-2 {{ ($dokumen->jenis == 'digital') ? 'badge-digital' : 'badge-fisik' }}">
                                    {{ strtoupper($dokumen->jenis ?? 'FISIK') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Tanggal Masuk</div>
                            <div class="detail-value text-secondary">{{ $dokumen->tgl_masuk ? \Carbon\Carbon::parse($dokumen->tgl_masuk)->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Tanggal Kadaluarsa Retensi</div>
                            <div class="detail-value text-danger">{{ ($dokumen->retensi && $dokumen->retensi->tgl_kadaluarsa) ? \Carbon\Carbon::parse($dokumen->retensi->tgl_kadaluarsa)->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Keterangan Aturan Retensi</div>
                            <div class="detail-value fw-medium text-dark">{{ $dokumen->retensi->ket_retensi ?? 'Tidak ada aturan khusus.' }}</div>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <div class="detail-label">Catatan Keterangan Dokumen</div>
                            <div class="detail-value fw-normal text-muted" style="font-size:14px;">{{ $dokumen->keterangan ?? 'Tidak ada catatan keterangan tambahan.' }}</div>
                        </div>
                        @if($dokumen->file_path)
                        <div class="col-12 border-top pt-3">
                            <div class="detail-label mb-2">Lampiran Berkas Digital</div>
                            <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-3 px-3 fw-bold">
                                <i class="fas fa-file-pdf me-2"></i>Buka & Lihat Berkas PDF
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100" id="printableQrArea">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="qr-section mb-3">
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->gradient(0, 45, 84, 255, 122, 0, 'vertical')->margin(1)->generate(url('/dokumen/' . $dokumen->id)) !!}
                    </div>
                    <div class="text-center">
                        <small class="text-muted d-block font-monospace">ID: DOC-{{ $dokumen->id }}</small>
                        <span class="fw-bold text-dark d-block text-truncate px-2" style="font-size:13px; max-width:240px;">{{ $dokumen->nama_dokumen ?? '-' }}</span>
                        <span class="text-primary fw-medium small d-block mt-1">{{ $dokumen->no_surat ?? '-' }}</span>
                        <div class="badge bg-light text-secondary border mt-2 small">Box: {{ $dokumen->box->kode_box ?? '-' }} / Rak: {{ $dokumen->box->rak->kode_rak ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>