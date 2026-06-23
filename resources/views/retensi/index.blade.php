<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retensi Dokumen - Sistem Arsip PT Bank Sumut</title>
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
            width: 260px; min-height: 100vh;
            background: linear-gradient(180deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
            position: fixed; left: 0; top: 0; z-index: 100;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-brand {
            padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center; position: relative;
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
            text-decoration: none; display: block;
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

        .btn-check-retensi {
            background: var(--primary-blue); color: white; border-radius: 10px;
            padding: 10px 20px; border: none; font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,75,135,0.15); transition: all 0.2s; text-decoration: none;
        }
        .btn-check-retensi:hover { background: var(--dark-blue); color: white; }

        .btn-log-pemusnahan {
            background: #e2e8f0; color: #334155; border-radius: 10px;
            padding: 10px 20px; border: 1px solid #cbd5e1; font-weight: 600;
            transition: all 0.2s; text-decoration: none;
        }
        .btn-log-pemusnahan:hover { background: #cbd5e1; color: #1e293b; }

        .card {
            border: 1px solid rgba(0,0,0,0.03); border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03); background: white; overflow: hidden;
        }

        .table th {
            font-weight: 600; font-size: 13px; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 16px;
        }
        .table td { font-size: 14px; padding: 14px 16px; vertical-align: middle; color: #334155; }

        .badge-aktif { background-color: #e6f7f0; color: #065f46; font-weight: 600; }
        .badge-akan { background-color: #fff2e6; color: #c2410c; font-weight: 600; }
        .badge-kadaluarsa { background-color: #fee2e2; color: #991b1b; font-weight: 600; }
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
        <a href="{{ route('retensi.index') }}" class="nav-link active"><i class="fas fa-clock"></i> Retensi Dokumen</a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link"><i class="fas fa-bell"></i> Notifikasi</a>
        <div class="sidebar-section">Pengaturan</div>
        <a href="{{ route('user.index') }}" class="nav-link"><i class="fas fa-users"></i> Manajemen User</a>

        <div class="logout-wrapper">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
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
            <h5 class="mb-1 fw-bold" style="color: var(--dark-blue)">Retensi Dokumen</h5>
            <small class="text-muted">Monitoring berkala jangka waktu pemeliharaan berkas arsip</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('retensi.riwayat') }}" class="btn btn-log-pemusnahan btn-sm d-flex align-items-center">
                <i class="fas fa-history me-2 text-danger"></i> Lihat Log Pemusnahan
            </a>
            <a href="{{ route('retensi.cek') }}" class="btn btn-check-retensi btn-sm d-flex align-items-center">
                <i class="fas fa-sync me-2"></i> Cek Retensi Sekarang
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius:12px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Box</th>
                            <th>Rak</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Kadaluarsa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retensis as $i => $r)
                        <tr>
                            <td class="fw-bold text-muted">{{ $i + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $r->dokumen->nama_dokumen ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $r->dokumen->box->kode_box ?? '-' }}</span></td>
                            <td><span class="text-muted small">{{ $r->dokumen->box->rak->kode_rak ?? '-' }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($r->tgl_mulai)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tgl_kadaluarsa)->format('d/m/Y') }}</td>
                            <td>
                                @if($r->status == 'aktif')
                                    <span class="badge px-2 py-1 rounded-2 badge-aktif">Aktif</span>
                                @elseif($r->status == 'akan_kadaluarsa')
                                    <span class="badge px-2 py-1 rounded-2 badge-akan">Akan Kadaluarsa</span>
                                @else
                                    <span class="badge px-2 py-1 rounded-2 badge-kadaluarsa">Kadaluarsa</span>
                                @endif
                            </td>
                            <td>
                                @if($r->status == 'kadaluarsa' && $r->dokumen_id)
                                <form method="POST" action="{{ route('retensi.musnahkan', $r->dokumen_id) }}" onsubmit="return confirm('Yakin ingin memusnahkan dokumen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-2">
                                        <i class="fas fa-fire me-1"></i> Musnahkan
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted small ps-3">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">Tidak ada data retensi.</td>
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