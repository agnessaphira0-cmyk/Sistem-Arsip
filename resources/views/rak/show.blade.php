<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rak - Sistem Arsip PT Bank Sumut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #004B87;
            --dark-blue: #002d54;
            --bank-orange: #FF7A00;
            --orange-hover: #E06B00;
            --bg-light: #f4f7fa;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg-light); color: #334155; }
        .sidebar { width: 260px; min-height: 100vh; background: linear-gradient(180deg, var(--dark-blue) 0%, var(--primary-blue) 100%); position: fixed; left: 0; top: 0; z-index: 100; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar-brand { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: center; position: relative; }
        .sidebar-brand::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: var(--bank-orange); }
        .sidebar-brand img { height: 35px; width: auto; margin-bottom: 10px; object-fit: contain; }
        .sidebar-brand h6 { color: white; font-weight: 700; font-size: 12px; margin: 0; line-height: 1.4; letter-spacing: 0.5px; }
        .sidebar-brand p { color: rgba(255,255,255,0.6); font-size: 11px; margin: 2px 0 0; font-weight: 500; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; font-size: 14px; font-weight: 500; border-left: 4px solid transparent; transition: all 0.2s ease; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.08); border-left-color: var(--bank-orange); }
        .sidebar .nav-link i { width: 20px; margin-right: 12px; font-size: 15px; }
        .sidebar-section { padding: 16px 24px 6px; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; }
        
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: white; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02); }
        
        .card-box { background: white; border-radius: 16px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 4px 12px rgba(0,0,0,0.02); transition: all 0.2s; cursor: pointer; overflow: hidden; }
        .card-box:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .progress { background-color: #f1f5f9; border-radius: 10px; }
        .btn-logout:hover { background-color: rgba(239, 68, 68, 0.15) !important; color: #ef4444 !important; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sumut">
        <h6>SISTEM INFORMASI ARSIP</h6>
        <p>PT Bank Sumut</p>
    </div>
    <div class="pt-2">
        <div class="sidebar-section">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('rak.index') }}" class="nav-link active">
            <i class="fas fa-archive"></i> Kelola Rak
        </a>
        <a href="{{ route('box.index') }}" class="nav-link">
            <i class="fas fa-box"></i> Kelola Box
        </a>
        <a href="{{ route('dokumen.index') }}" class="nav-link">
            <i class="fas fa-file-alt"></i> Kelola Dokumen
        </a>
        <div class="sidebar-section">Fitur</div>
        <a href="{{ route('dokumen.search') }}" class="nav-link">
            <i class="fas fa-search"></i> Cari Dokumen
        </a>
        <a href="{{ route('retensi.index') }}" class="nav-link">
            <i class="fas fa-clock"></i> Retensi Dokumen
        </a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link">
            <i class="fas fa-bell"></i> Notifikasi
        </a>
        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('user.index') }}" class="nav-link">
            <i class="fas fa-users"></i> Manajemen User
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color: var(--dark-blue)">Detail Rak — {{ $rak->kode_rak }}</h5>
            <small class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $rak->lokasi }}</small>
        </div>
        <div>
            <a href="{{ route('rak.index') }}" class="btn btn-light border px-3 rounded-3"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius:12px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        @forelse($boxes as $box)
        @php 
            $persen = $box->kapasitas > 0 ? ($box->dokumens_count / $box->kapasitas * 100) : 0; 
        @endphp
        <div class="col-md-4">
            <!-- Menghubungkan langsung link ke rute detail box Anda agar integrasi berjalan mulus -->
            <div class="card-box p-4" onclick="window.location='{{ route('box.show', $box->id) }}'">
                <div style="font-size:32px; margin-bottom:12px;">📦</div>
                <h5 class="fw-bold text-dark mb-1">{{ $box->kode_box }}</h5>
                <p class="text-muted mb-2" style="font-size:13px;">
                    <i class="fas fa-file-alt me-1 text-primary"></i><strong>{{ $box->dokumens_count }}</strong> / {{ $box->kapasitas }} Dokumen
                </p>
                
                <div class="progress mb-3" style="height:7px;">
                    <div class="progress-bar {{ $persen >= 90 ? 'bg-danger' : ($persen >= 70 ? 'bg-warning' : 'bg-success') }}" style="width:{{ min($persen, 100) }}%"></div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="badge px-2.5 py-1.5 {{ $persen >= 90 ? 'bg-danger-subtle text-danger' : ($persen >= 70 ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success') }}">
                        {{ round($persen) }}% Penuh
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('box.edit', $box->id) }}" class="btn btn-sm btn-outline-warning rounded-2" onclick="event.stopPropagation()">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('box.destroy', $box->id) }}" onsubmit="return confirm('Hapus box ini?')" onclick="event.stopPropagation()">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 text-center py-5 shadow-sm text-muted" style="border-radius:16px;">
                <div class="card-body">
                    <i class="fas fa-box fa-3x mb-3 text-secondary text-opacity-40"></i>
                    <p class="small mb-0">Belum ada penyimpanan box terdaftar di dalam struktur rak ini.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>