<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Box - Sistem Arsip PT Bank Sumut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue:#004B87; --dark-blue:#002d54; --bank-orange:#FF7A00; --orange-hover:#E06B00; --bg-light:#f4f7fa; }
        body { font-family:'Inter',sans-serif; background:var(--bg-light); color:#334155; }

        .sidebar { width:260px; min-height:100vh; background:linear-gradient(180deg,var(--dark-blue) 0%,var(--primary-blue) 100%); position:fixed; left:0; top:0; z-index:100; box-shadow:4px 0 10px rgba(0,0,0,0.1); transition:width 0.3s; overflow:hidden; }
        .sidebar.collapsed { width:70px; }
        .sidebar-brand { padding:24px 20px; border-bottom:1px solid rgba(255,255,255,0.08); text-align:center; position:relative; transition:padding 0.3s; }
        .sidebar.collapsed .sidebar-brand { padding:20px 10px; }
        .sidebar-brand::after { content:''; position:absolute; bottom:0; left:0; width:100%; height:3px; background:var(--bank-orange); }
        .sidebar-brand img { height:35px; width:auto; margin-bottom:10px; object-fit:contain; display:block; margin-left:auto; margin-right:auto; transition:height 0.3s; }
        .sidebar.collapsed .sidebar-brand img { height:28px; margin-bottom:0; }
        .sidebar-brand h6 { color:white; font-weight:700; font-size:12px; margin:0; line-height:1.4; letter-spacing:0.5px; }
        .sidebar-brand p { color:rgba(255,255,255,0.6); font-size:11px; margin:2px 0 0; font-weight:500; }
        .sidebar.collapsed .sidebar-brand h6, .sidebar.collapsed .sidebar-brand p { opacity:0; max-height:0; overflow:hidden; margin:0; }
        .sidebar .nav-link { color:rgba(255,255,255,0.7); padding:12px 24px; font-size:14px; font-weight:500; border-left:4px solid transparent; transition:all 0.2s; white-space:nowrap; display:flex; align-items:center; }
        .sidebar.collapsed .nav-link { padding:14px 0; justify-content:center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color:white; background:rgba(255,255,255,0.08); border-left-color:var(--bank-orange); }
        .sidebar .nav-link i { width:20px; margin-right:12px; font-size:15px; flex-shrink:0; }
        .sidebar.collapsed .nav-link i { margin-right:0; width:auto; }
        .nav-text { transition:opacity 0.2s,width 0.3s; overflow:hidden; }
        .sidebar.collapsed .nav-text { opacity:0; width:0; }
        .sidebar-section { padding:16px 24px 6px; font-size:11px; font-weight:700; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:1px; white-space:nowrap; transition:opacity 0.2s,padding 0.3s; }
        .sidebar.collapsed .sidebar-section { opacity:0; padding:8px 0; pointer-events:none; }

        /* ===== STICKY LOGOUT FIX ===== */
        .sidebar .sidebar-nav {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 105px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar .sidebar-nav::-webkit-scrollbar { width: 0; }
        .sidebar .sidebar-nav .logout-wrapper { margin-top: auto; padding-bottom: 8px; }
        /* ============================= */

        .btn-logout-sidebar { display:flex; align-items:center; gap:12px; width:calc(100% - 32px); margin:12px 16px; padding:11px 16px; background:rgba(239,68,68,0.12); color:#fca5a5; border:1px solid rgba(239,68,68,0.25); border-radius:10px; font-size:14px; font-weight:500; cursor:pointer; transition:all 0.2s; white-space:nowrap; overflow:hidden; }
        .btn-logout-sidebar:hover { background:rgba(239,68,68,0.25); color:#fff; border-color:rgba(239,68,68,0.5); }
        .btn-logout-sidebar i { font-size:15px; width:20px; flex-shrink:0; }
        .sidebar.collapsed .btn-logout-sidebar { width:42px; margin:12px auto; padding:11px; justify-content:center; }
        .sidebar.collapsed .btn-logout-text { display:none; }

        .toggle-btn { position:fixed; top:22px; left:246px; z-index:200; width:28px; height:28px; border-radius:50%; background:white; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.1); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:left 0.3s; color:#64748b; font-size:12px; }
        .toggle-btn.collapsed { left:56px; }
        .toggle-btn:hover { background:#f8fafc; color:#334155; }

        .main-content { margin-left:260px; padding:30px; transition:margin-left 0.3s; }
        .main-content.expanded { margin-left:70px; }
        .topbar { background:white; padding:18px 24px; border-radius:16px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid rgba(0,0,0,0.02); }

        .card-box { background:white; border-radius:16px; border:1px solid rgba(0,0,0,0.04); box-shadow:0 4px 12px rgba(0,0,0,0.02); transition:all 0.2s; cursor:pointer; overflow:hidden; }
        .card-box:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,0.08); border-color:rgba(0,75,135,0.2); }
        .box-icon-wrapper { width:50px; height:50px; background:#fff2e6; color:var(--bank-orange); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; }
        .progress { background-color:#f1f5f9; border-radius:10px; }
        .btn-add-box { background:linear-gradient(135deg,var(--bank-orange),#FF9124); color:white; border:none; border-radius:10px; padding:10px 18px; font-weight:600; box-shadow:0 4px 12px rgba(255,122,0,0.2); transition:all 0.2s; }
        .btn-add-box:hover { background:linear-gradient(135deg,var(--orange-hover),var(--bank-orange)); color:white; box-shadow:0 6px 16px rgba(255,122,0,0.3); }

        .skeleton { background:linear-gradient(90deg,#f0f4f8 25%,#e2e8f0 50%,#f0f4f8 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:8px; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .skeleton-box-card { background:white; border-radius:16px; padding:24px; border:1px solid rgba(0,0,0,0.04); box-shadow:0 4px 12px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

<button class="toggle-btn" id="toggleBtn" title="Lipat/Buka Sidebar">
    <i class="fas fa-chevron-left" id="toggleIcon"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sumut">
        <h6>SISTEM INFORMASI ARSIP</h6>
        <p>PT Bank Sumut</p>
    </div>

    <div class="sidebar-nav pt-2">
        <div class="sidebar-section">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span class="nav-text">Dashboard</span></a>
        <a href="{{ route('rak.index') }}" class="nav-link"><i class="fas fa-archive"></i><span class="nav-text">Kelola Rak</span></a>
        <a href="{{ route('box.index') }}" class="nav-link active"><i class="fas fa-box"></i><span class="nav-text">Kelola Box</span></a>
        <a href="{{ route('dokumen.index') }}" class="nav-link"><i class="fas fa-file-alt"></i><span class="nav-text">Kelola Dokumen</span></a>
        <div class="sidebar-section">Fitur</div>
        <a href="{{ route('dokumen.search') }}" class="nav-link"><i class="fas fa-search"></i><span class="nav-text">Cari Dokumen</span></a>
        <a href="{{ route('retensi.index') }}" class="nav-link"><i class="fas fa-clock"></i><span class="nav-text">Retensi Dokumen</span></a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link"><i class="fas fa-bell"></i><span class="nav-text">Notifikasi</span></a>
        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-users"></i><span class="nav-text">Manajemen User</span></a>

        <div class="logout-wrapper">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="fas fa-sign-out-alt"></i><span class="btn-logout-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color:var(--dark-blue)">Kelola Box</h5>
            <small class="text-muted">Daftar penyimpanan box arsip berkas</small>
        </div>
        <div>
            <a href="{{ route('box.create') }}" class="btn btn-add-box"><i class="fas fa-plus me-2"></i>Tambah Box</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Skeleton -->
    <div class="row g-4" id="skeletonContent">
        @for($i=0;$i<6;$i++)
        <div class="col-md-4">
            <div class="skeleton-box-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="skeleton" style="width:50px;height:50px;border-radius:12px;"></div>
                    <div class="skeleton" style="height:24px;width:90px;border-radius:20px;"></div>
                </div>
                <div class="skeleton mb-2" style="height:20px;width:130px;"></div>
                <div class="skeleton mb-3" style="height:13px;width:100px;"></div>
                <div class="d-flex justify-content-between mb-1"><div class="skeleton" style="height:12px;width:80px;"></div><div class="skeleton" style="height:12px;width:100px;"></div></div>
                <div class="skeleton mb-3" style="height:7px;width:100%;border-radius:10px;"></div>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <div class="skeleton" style="height:22px;width:60px;border-radius:20px;"></div>
                    <div class="d-flex gap-2"><div class="skeleton" style="width:32px;height:32px;border-radius:6px;"></div><div class="skeleton" style="width:32px;height:32px;border-radius:6px;"></div></div>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Real Content -->
    <div class="row g-4" id="realContent" style="display:none;">
        @forelse($boxes as $box)
        @php
            $jumlah_dokumen = $box->dokumens_count ?? $box->dokumens()->count();
            $persen = ($jumlah_dokumen / $box->kapasitas) * 100;
        @endphp
        <div class="col-md-4">
            <div class="card-box p-4" onclick="window.location='{{ route('box.show', $box->id) }}'">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="box-icon-wrapper"><i class="fas fa-box"></i></div>
                    <span class="badge rounded-pill px-3 py-1" style="background-color:rgba(0,75,135,0.1);color:var(--primary-blue);font-weight:600;">Rak: {{ $box->rak->kode_rak ?? 'N/A' }}</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $box->nama_box }}</h5>
                <p class="text-muted small mb-3"><i class="fas fa-barcode me-1"></i> Kode: {{ $box->kode_box }}</p>
                <div class="mb-3">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Kapasitas Isi</span>
                        <span class="fw-semibold text-dark">{{ $jumlah_dokumen }} / {{ $box->kapasitas }} Dokumen</span>
                    </div>
                    <div class="progress" style="height:7px;">
                        <div class="progress-bar {{ $persen>=90?'bg-danger':($persen>=70?'bg-warning':'bg-success') }}" role="progressbar" style="width:{{ $persen }}%"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="badge px-2 py-1 {{ $persen>=90?'bg-danger-subtle text-danger':($persen>=70?'bg-warning-subtle text-warning':'bg-success-subtle text-success') }}">{{ round($persen) }}% Penuh</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('box.edit', $box->id) }}" class="btn btn-sm btn-outline-warning rounded-2" onclick="event.stopPropagation()"><i class="fas fa-edit"></i></a>
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
                    <i class="fas fa-box fa-3x mb-3 text-secondary opacity-50"></i>
                    <p class="mb-3">Belum ada box terdaftar dalam sistem.</p>
                    <a href="{{ route('box.create') }}" class="btn btn-sm btn-primary">Tambah box pertama</a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleBtn');
    const toggleIcon = document.getElementById('toggleIcon');
    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    function applySidebarState() {
        if (isCollapsed) {
            sidebar.classList.add('collapsed'); mainContent.classList.add('expanded');
            toggleBtn.classList.add('collapsed'); toggleIcon.classList.replace('fa-chevron-left','fa-chevron-right');
        } else {
            sidebar.classList.remove('collapsed'); mainContent.classList.remove('expanded');
            toggleBtn.classList.remove('collapsed'); toggleIcon.classList.replace('fa-chevron-right','fa-chevron-left');
        }
    }
    toggleBtn.addEventListener('click', () => { isCollapsed=!isCollapsed; localStorage.setItem('sidebarCollapsed',isCollapsed); applySidebarState(); });
    applySidebarState();

    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('skeletonContent').style.display = 'none';
            document.getElementById('realContent').style.display = '';
        }, 800);
    });
</script>
</body>
</html>