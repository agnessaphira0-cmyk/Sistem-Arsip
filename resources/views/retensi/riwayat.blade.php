<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemusnahan - Sistem Arsip PT Bank Sumut</title>
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
        .sidebar-brand h6 { color: white; font-weight: 700; font-size: 12px; margin: 0; }
        .sidebar-brand p { color: rgba(255,255,255,0.6); font-size: 11px; margin: 2px 0 0; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; font-size: 14px; text-decoration: none; display: block; border-left: 4px solid transparent; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.08); border-left-color: var(--bank-orange); }
        .sidebar .nav-link i { width: 20px; margin-right: 12px; }
        .sidebar-section { padding: 16px 24px 6px; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: white; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .card { border: 1px solid rgba(0,0,0,0.03); border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); background: white; overflow: hidden; }
        .table th { font-weight: 600; font-size: 12px; color: #64748b; text-transform: uppercase; padding: 14px 16px; }
        .table td { font-size: 13.5px; padding: 14px 16px; color: #334155; }
        
        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #ef4444 !important;
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
        <a href="{{ route('dokumen.index') }}" class="nav-link"><i class="fas fa-file-alt"></i> Kelola Dokumen</a>
        <div class="sidebar-section">Fitur</div>
        <a href="{{ route('dokumen.search') }}" class="nav-link"><i class="fas fa-search"></i> Cari Dokumen</a>
        <a href="{{ route('retensi.index') }}" class="nav-link active"><i class="fas fa-clock"></i> Retensi Dokumen</a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link"><i class="fas fa-bell"></i> Notifikasi</a>
        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-users"></i> Manajemen User</a>
        
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent btn-logout text-danger fw-semibold">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color: var(--dark-blue)">Log Riwayat Pemusnahan</h5>
            <small class="text-muted">Bukti rekaman berkas perbankan yang telah dihancurkan</small>
        </div>
        <div>
            <a href="{{ route('retensi.index') }}" class="btn btn-sm btn-light border px-3 rounded-3 fw-semibold">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>No Surat</th>
                            <th>Nama Dokumen</th>
                            <th>Kategori</th>
                            <th>Ex Box/Rak</th>
                            <th>Waktu Musnah</th>
                            <th>Eksekutor</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $i => $r)
                        <tr>
                            <td class="fw-bold text-muted">{{ $i + 1 }}</td>
                            <td class="text-danger font-monospace fw-semibold small">#{{ $r->no_surat }}</td>
                            <td class="fw-semibold text-dark">{{ $r->nama_dokumen }}</td>
                            <td>{{ $r->kategori }}</td>
                            <td><span class="badge bg-light text-secondary border">{{ $r->kode_box_lama ?? $r->kode_box }} / {{ $r->kode_rak_lama ?? $r->kode_rak }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal_pemusnahan ?? $r->created_at)->format('d/m/Y H:i') }} WIB</td>
                            <td><span class="badge bg-danger-subtle text-danger px-2 py-1.5"><i class="fas fa-user-shield me-1"></i>{{ $r->eksekutor ?? $r->dimusnahkan_oleh }}</span></td>
                            <td class="text-muted small">{{ $r->alasan ?? $r->alasan_pemusnahan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">Belum ada riwayat dokumen yang dimusnahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>