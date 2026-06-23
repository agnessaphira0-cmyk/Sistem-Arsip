<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Sistem Arsip PT Bank Sumut</title>
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

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
            position: fixed; left: 0; top: 0; z-index: 100;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center;
            position: relative;
        }
        .sidebar-brand::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 100%; height: 3px; background: var(--bank-orange);
        }
        .sidebar-brand img { height: 35px; width: auto; margin-bottom: 10px; object-fit: contain; }
        .sidebar-brand h6 { color: white; font-weight: 700; font-size: 12px; margin: 0; line-height: 1.4; letter-spacing: 0.5px; }
        .sidebar-brand p { color: rgba(255,255,255,0.6); font-size: 11px; margin: 2px 0 0; font-weight: 500; }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7); padding: 12px 24px; font-size: 14px; font-weight: 500;
            border-left: 4px solid transparent; transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white; background: rgba(255,255,255,0.08); border-left-color: var(--bank-orange);
        }
        .sidebar .nav-link i { width: 20px; margin-right: 12px; font-size: 15px; }
        .sidebar-section {
            padding: 16px 24px 6px; font-size: 11px; font-weight: 700;
            color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;
        }

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

        .btn-logout-sidebar {
            display: flex; align-items: center; gap: 12px;
            width: calc(100% - 32px); margin: 12px 16px;
            padding: 11px 16px; background: rgba(239,68,68,0.12); color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.25); border-radius: 10px;
            font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s;
        }
        .btn-logout-sidebar:hover { background: rgba(239,68,68,0.25); color: #fff; border-color: rgba(239,68,68,0.5); }

        .main-content { margin-left: 260px; padding: 30px; }

        .topbar {
            background: white; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02);
        }

        .card {
            border: 1px solid rgba(0,0,0,0.03); border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03); background: white; overflow: hidden;
        }

        .notif-item { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0f7ff; border-left: 4px solid var(--primary-blue); }
        .notif-item.unread:hover { background: #e1effe; }

        .notif-icon-box {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 16px;
        }
        .notif-item.unread .notif-icon-box { background-color: #e0f2fe; color: var(--primary-blue); }
        .notif-item:not(.unread) .notif-icon-box { background-color: #f1f5f9; color: #64748b; }

        .btn-read-all {
            border-radius: 10px; font-weight: 600; padding: 8px 16px;
            border: 1px solid var(--primary-blue); color: var(--primary-blue); transition: all 0.2s;
        }
        .btn-read-all:hover { background-color: var(--primary-blue); color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sumut">
        <h6>SISTEM INFORMASI ARSIP</h6>
        <p>PT Bank Sumut</p>
    </div>

    <div class="sidebar-nav pt-2">
        <div class="sidebar-section">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('rak.index') }}" class="nav-link"><i class="fas fa-archive"></i> Kelola Rak</a>
        <a href="{{ route('box.index') }}" class="nav-link"><i class="fas fa-box"></i> Kelola Box</a>
        <a href="{{ route('dokumen.index') }}" class="nav-link"><i class="fas fa-file-alt"></i> Kelola Dokumen</a>
        <div class="sidebar-section">Fitur</div>
        <a href="{{ route('dokumen.search') }}" class="nav-link"><i class="fas fa-search"></i> Cari Dokumen</a>
        <a href="{{ route('retensi.index') }}" class="nav-link"><i class="fas fa-clock"></i> Retensi Dokumen</a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link active"><i class="fas fa-bell"></i> Notifikasi</a>
        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-users"></i> Manajemen User</a>

        <div class="logout-wrapper">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color: var(--dark-blue)">Notifikasi</h5>
            <small class="text-muted fw-medium">{{ $notifikasis->where('status_baca', false)->count() }} pemberitahuan baru belum dibaca</small>
        </div>
        @if($notifikasis->where('status_baca', false)->count() > 0)
        <form method="POST" action="{{ route('notifikasi.baca.semua') }}">
            @csrf
            <button type="submit" class="btn btn-read-all btn-sm">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius:12px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            @forelse($notifikasis as $notif)
            <div class="notif-item {{ !$notif->status_baca ? 'unread' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="notif-icon-box flex-shrink-0">
                            <i class="{{ !$notif->status_baca ? 'fas fa-bell' : 'fas fa-bell-slash' }}"></i>
                        </div>
                        <div>
                            <div style="font-size:14.5px; line-height: 1.4;" class="{{ !$notif->status_baca ? 'fw-semibold text-dark' : 'text-secondary' }}">
                                {{ $notif->pesan }}
                            </div>
                            <small class="text-muted d-flex align-items-center gap-2 mt-1" style="font-size:12px;">
                                <i class="far fa-clock"></i>
                                <span>{{ \Carbon\Carbon::parse($notif->tgl_notif)->format('d/m/Y H:i') }}</span>
                                <span>&bull;</span>
                                <span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($notif->tgl_notif)->diffForHumans() }}</span>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex gap-1 ms-3 flex-shrink-0">
                        @if(!$notif->status_baca)
                        <form method="POST" action="{{ route('notifikasi.baca', $notif->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-2" title="Tandai dibaca">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('notifikasi.destroy', $notif->id) }}"
                              onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3 d-block text-secondary text-opacity-35"></i>
                <span class="d-block fw-semibold text-dark small">Kotak Masuk Kosong</span>
                <span class="text-muted small" style="font-size:12px;">Tidak ada pemberitahuan notifikasi saat ini</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>