<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Arsip PT Bank Sumut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-blue:#004B87; --dark-blue:#002d54; --bank-orange:#FF7A00; --orange-hover:#E06B00; --bg-light:#f4f7fa; }
        body { font-family:'Inter',sans-serif; background:var(--bg-light); color:#334155; }

        /* SIDEBAR */
        .sidebar {
            width:260px;
            height:100vh;
            background:linear-gradient(180deg,var(--dark-blue) 0%,var(--primary-blue) 100%);
            position:fixed;
            left:0;
            top:0;
            z-index:100;
            box-shadow:4px 0 10px rgba(0,0,0,0.1);
            transition:width 0.3s ease;
            overflow-x:hidden;
            overflow-y:auto;
            display:flex;
            flex-direction:column;
        }
        .sidebar.collapsed { width:70px; }

        /* Custom Scrollbar Sidebar */
        .sidebar::-webkit-scrollbar { width:4px; }
        .sidebar::-webkit-scrollbar-track { background:transparent; }
        .sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.25); border-radius:4px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background:rgba(255,255,255,0.4); }

        .sidebar-brand { padding:28px 20px; border-bottom:1px solid rgba(255,255,255,0.08); text-align:center; position:relative; transition:padding 0.3s; flex-shrink:0; }
        .sidebar.collapsed .sidebar-brand { padding:20px 10px; }
        .sidebar-brand::after { content:''; position:absolute; bottom:0; left:0; width:100%; height:3px; background:var(--bank-orange); }
        .sidebar-brand img { height:56px; object-fit:contain; display:block; margin:0 auto 8px; transition:height 0.3s; }
        .sidebar.collapsed .sidebar-brand img { height:32px; margin-bottom:0; }
        .sidebar-brand h6 { color:white; font-weight:700; font-size:13px; margin:0; line-height:1.4; letter-spacing:0.5px; transition:opacity 0.2s; }
        .sidebar-brand p { color:rgba(255,255,255,0.6); font-size:11px; margin:4px 0 0; font-weight:500; transition:opacity 0.2s; }
        .sidebar.collapsed .sidebar-brand h6, .sidebar.collapsed .sidebar-brand p { opacity:0; max-height:0; overflow:hidden; margin:0; }

        .sidebar-nav { flex:1; }

        .sidebar .nav-link { color:rgba(255,255,255,0.7); padding:12px 24px; font-size:14px; font-weight:500; border-left:4px solid transparent; transition:all 0.2s; white-space:nowrap; display:flex; align-items:center; }
        .sidebar.collapsed .nav-link { padding:14px 0; justify-content:center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color:white; background:rgba(255,255,255,0.08); border-left-color:var(--bank-orange); }
        .sidebar .nav-link i { width:20px; margin-right:12px; font-size:15px; flex-shrink:0; }
        .sidebar.collapsed .nav-link i { margin-right:0; width:auto; }
        .nav-text { transition:opacity 0.2s,width 0.3s; overflow:hidden; }
        .sidebar.collapsed .nav-text { opacity:0; width:0; }
        .sidebar-section { padding:16px 24px 6px; font-size:11px; font-weight:700; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:1px; white-space:nowrap; transition:opacity 0.2s,padding 0.3s; }
        .sidebar.collapsed .sidebar-section { opacity:0; padding:8px 0; pointer-events:none; }

        .sidebar-logout { flex-shrink:0; border-top:1px solid rgba(255,255,255,0.08); padding:12px 16px 20px; }
        .btn-logout-sidebar { display:flex; align-items:center; gap:12px; width:100%; padding:11px 16px; background:rgba(239,68,68,0.12); color:#fca5a5; border:1px solid rgba(239,68,68,0.25); border-radius:10px; font-size:14px; font-weight:500; cursor:pointer; transition:all 0.2s; white-space:nowrap; overflow:hidden; }
        .btn-logout-sidebar:hover { background:rgba(239,68,68,0.25); color:#fff; border-color:rgba(239,68,68,0.5); }
        .btn-logout-sidebar i { font-size:15px; width:20px; flex-shrink:0; }
        .sidebar.collapsed .sidebar-logout { padding:12px 14px 20px; }
        .sidebar.collapsed .btn-logout-sidebar { width:42px; padding:11px; justify-content:center; }
        .sidebar.collapsed .btn-logout-text { display:none; }

        /* TOGGLE BTN */
        .toggle-btn { position:fixed; top:22px; left:246px; z-index:200; width:28px; height:28px; border-radius:50%; background:white; border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.1); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:left 0.3s; color:#64748b; font-size:12px; }
        .toggle-btn.collapsed { left:56px; }
        .toggle-btn:hover { background:#f8fafc; color:#334155; }

        /* MAIN */
        .main-content { margin-left:260px; padding:30px; transition:margin-left 0.3s; }
        .main-content.expanded { margin-left:70px; }
        .topbar { background:white; padding:18px 24px; border-radius:16px; margin-bottom:30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid rgba(0,0,0,0.02); }
        .stat-card { background:white; border-radius:16px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid rgba(0,0,0,0.02); border-top:4px solid; transition:transform 0.2s,box-shadow 0.2s; }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,0.06); }
        .stat-card.blue { border-color:var(--primary-blue); }
        .stat-card.green { border-color:#10b981; }
        .stat-card.orange { border-color:var(--bank-orange); }
        .stat-card.red { border-color:#ef4444; }
        .stat-card .icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; }
        .stat-card.blue .icon { background:#e0effa; color:var(--primary-blue); }
        .stat-card.green .icon { background:#e6f7f0; color:#10b981; }
        .stat-card.orange .icon { background:#fff2e6; color:var(--bank-orange); }
        .stat-card.red .icon { background:#fee2e2; color:#ef4444; }
        .card { border:1px solid rgba(0,0,0,0.03); border-radius:16px; box-shadow:0 4px 12px rgba(0,0,0,0.03); background:white; overflow:hidden; }
        .card-header { border-bottom:1px solid #f1f5f9 !important; }
        .table th { font-weight:600; font-size:13px; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; padding:12px 16px; }
        .table td { font-size:14px; padding:14px 16px; vertical-align:middle; color:#334155; }
        .badge-digital { background-color:#e0f2fe; color:#0369a1; font-weight:600; }
        .badge-fisik { background-color:#f1f5f9; color:#475569; font-weight:600; }

        /* SKELETON */
        .skeleton { background:linear-gradient(90deg,#f0f4f8 25%,#e2e8f0 50%,#f0f4f8 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:8px; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .skeleton-card { background:white; border-radius:16px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.03); border:1px solid rgba(0,0,0,0.02); border-top:4px solid #e2e8f0; }
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
        <a href="{{ route('dashboard') }}" class="nav-link active"><i class="fas fa-tachometer-alt"></i><span class="nav-text">Dashboard</span></a>
        <a href="{{ route('rak.index') }}" class="nav-link"><i class="fas fa-archive"></i><span class="nav-text">Kelola Rak</span></a>
        <a href="{{ route('box.index') }}" class="nav-link"><i class="fas fa-box"></i><span class="nav-text">Kelola Box</span></a>
        <a href="{{ route('dokumen.index') }}" class="nav-link"><i class="fas fa-file-alt"></i><span class="nav-text">Kelola Dokumen</span></a>
        <div class="sidebar-section">Fitur</div>
        <a href="{{ route('dokumen.search') }}" class="nav-link"><i class="fas fa-search"></i><span class="nav-text">Cari Dokumen</span></a>
        <a href="{{ route('retensi.index') }}" class="nav-link"><i class="fas fa-clock"></i><span class="nav-text">Retensi Dokumen</span></a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link"><i class="fas fa-bell"></i><span class="nav-text">Notifikasi</span></a>
        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-users"></i><span class="nav-text">Manajemen User</span></a>
    </div>

    <!-- LOGOUT SELALU KELIHATAN DI BAWAH -->
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout-sidebar">
                <i class="fas fa-sign-out-alt"></i>
                <span class="btn-logout-text">Logout</span>
            </button>
        </form>
    </div>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color:var(--dark-blue)">Dashboard</h5>
            <small class="text-muted">Selamat datang kembali, <span class="fw-semibold text-dark">{{ session('admin_nama') }}</span>!</small>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge px-3 py-2" style="background-color:var(--primary-blue);font-weight:600;font-size:12px;border-radius:30px;">{{ ucfirst(session('admin_role')) }}</span>
            <span class="text-muted fw-medium" style="font-size:13px;"><i class="fas fa-calendar me-1 text-primary"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    <!-- Skeleton Stats -->
    <div class="row g-3 mb-4" id="skeletonStats">
        @for($i=0;$i<4;$i++)
        <div class="col-md-3">
            <div class="skeleton-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="flex:1"><div class="skeleton mb-2" style="height:12px;width:80px;"></div><div class="skeleton" style="height:36px;width:60px;"></div></div>
                    <div class="skeleton" style="width:48px;height:48px;border-radius:12px;"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Real Stats -->
    <div class="row g-3 mb-4" id="realStats" style="display:none;">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="d-flex justify-content-between align-items-center">
                    <div><div class="text-muted fw-medium small mb-1">Total Rak</div><div class="fw-bold fs-2" style="color:var(--dark-blue);">{{ \App\Models\Rak::count() }}</div></div>
                    <div class="icon"><i class="fas fa-archive"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="d-flex justify-content-between align-items-center">
                    <div><div class="text-muted fw-medium small mb-1">Total Box</div><div class="fw-bold fs-2" style="color:#065f46;">{{ \App\Models\Box::count() }}</div></div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="d-flex justify-content-between align-items-center">
                    <div><div class="text-muted fw-medium small mb-1">Total Dokumen</div><div class="fw-bold fs-2" style="color:#c2410c;">{{ \App\Models\Dokumen::count() }}</div></div>
                    <div class="icon"><i class="fas fa-file-alt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card red">
                <div class="d-flex justify-content-between align-items-center">
                    <div><div class="text-muted fw-medium small mb-1">Perlu Dimusnahkan</div><div class="fw-bold fs-2" style="color:#991b1b;">{{ \App\Models\Retensi::where('status','kadaluarsa')->count() }}</div></div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skeleton Content -->
    <div class="row g-4" id="skeletonContent">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-3 pb-3"><div class="skeleton" style="height:16px;width:160px;"></div></div>
                <div class="card-body p-3">
                    @for($i=0;$i<5;$i++)
                    <div class="d-flex gap-3 mb-3 align-items-center">
                        <div class="skeleton" style="height:14px;flex:2;"></div>
                        <div class="skeleton" style="height:14px;flex:1;"></div>
                        <div class="skeleton" style="height:22px;width:60px;border-radius:20px;"></div>
                        <div class="skeleton" style="height:14px;width:70px;"></div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-3 pb-3"><div class="skeleton" style="height:16px;width:140px;"></div></div>
                <div class="card-body p-3">
                    @for($i=0;$i<4;$i++)
                    <div class="mb-3"><div class="skeleton mb-2" style="height:13px;width:100%;"></div><div class="skeleton" style="height:11px;width:80px;"></div></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Real Content -->
    <div class="row g-4" id="realContent" style="display:none;">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-3 pb-3">
                    <h6 class="fw-bold mb-0" style="color:var(--dark-blue);"><i class="fas fa-file-alt me-2 text-primary"></i>Dokumen Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>Nama Dokumen</th><th>Kategori</th><th>Jenis</th><th>Tgl Masuk</th></tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Dokumen::latest()->take(5)->get() as $dok)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $dok->nama_dokumen }}</td>
                                    <td><span class="text-secondary">{{ $dok->kategori }}</span></td>
                                    <td><span class="badge px-2 py-1 rounded-2 {{ $dok->jenis=='digital'?'badge-digital':'badge-fisik' }}">{{ ucfirst($dok->jenis) }}</span></td>
                                    <td class="text-muted">{{ \Carbon\Carbon::parse($dok->tgl_masuk)->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-4"><i class="fas fa-folder-open d-block fs-3 mb-2 text-secondary opacity-25"></i>Belum ada dokumen baru</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-3 pb-3">
                    <h6 class="fw-bold mb-0" style="color:var(--dark-blue);"><i class="fas fa-bell me-2 text-danger"></i>Notifikasi Retensi</h6>
                </div>
                <div class="card-body p-0">
                    @forelse(\App\Models\Notifikasi::where('status_baca',false)->latest()->take(5)->get() as $notif)
                    <div class="px-3 py-3 border-bottom">
                        <div class="fw-medium text-dark mb-1" style="font-size:13.5px;line-height:1.4;">{{ $notif->pesan }}</div>
                        <small class="text-muted d-flex align-items-center gap-1" style="font-size:11px;"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($notif->tgl_notif)->diffForHumans() }}</small>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5 px-3">
                        <i class="fas fa-check-circle text-success fs-2 d-block mb-3"></i>
                        <span class="d-block fw-semibold text-dark small">Semua Beres!</span>
                        <span class="text-muted small" style="font-size:12px;">Tidak ada notifikasi retensi baru</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
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
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            toggleBtn.classList.add('collapsed');
            toggleIcon.classList.replace('fa-chevron-left','fa-chevron-right');
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
            toggleBtn.classList.remove('collapsed');
            toggleIcon.classList.replace('fa-chevron-right','fa-chevron-left');
        }
    }

    toggleBtn.addEventListener('click', () => {
        isCollapsed = !isCollapsed;
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        applySidebarState();
    });

    applySidebarState();

    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('skeletonStats').style.display = 'none';
            document.getElementById('skeletonContent').style.display = 'none';
            document.getElementById('realStats').style.display = '';
            document.getElementById('realContent').style.display = '';
        }, 800);
    });
</script>
</body>
</html>