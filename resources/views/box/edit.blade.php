<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Box - Sistem Arsip PT Bank Sumut</title>
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
        .topbar { background: white; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02); }
        
        .card-form { background: white; border-radius: 16px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .form-control, .form-select { border-radius: 10px; padding: 11px 14px; border: 1px solid #b2c0cc; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(0, 75, 135, 0.15); }
        
        .btn-update { background: var(--bank-orange); color: white; border-radius: 10px; padding: 10px 20px; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(255, 122, 0, 0.2); }
        .btn-update:hover { background: var(--orange-hover); color: white; }
        .btn-logout:hover { background-color: rgba(239, 68, 68, 0.15) !important; color: #ef4444 !important; }
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
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('rak.index') }}" class="nav-link">
            <i class="fas fa-archive"></i> Kelola Rak
        </a>
        <a href="{{ route('box.index') }}" class="nav-link active">
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

<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0 fw-bold" style="color: var(--dark-blue)"><i class="fas fa-edit me-2 text-warning"></i>Ubah Informasi Box</h5>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-form p-4">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 small">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('box.update', $box->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Kode Box</label>
                            <input type="text" name="kode_box" class="form-control" value="{{ old('kode_box', $box->kode_box) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Nama Box</label>
                            <input type="text" name="nama_box" class="form-control" value="{{ old('nama_box', $box->nama_box) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Pindahkan Lokasi Rak</label>
                            <select name="rak_id" class="form-select" required>
                                <option value="">-- Pilih Rak --</option>
                                @foreach($raks as $rak)
                                <option value="{{ $rak->id }}" {{ old('rak_id', $box->rak_id) == $rak->id ? 'selected' : '' }}>
                                    {{ $rak->kode_rak }} — {{ $rak->lokasi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Kapasitas Penyimpanan</label>
                            <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', $box->kapasitas) }}" min=\"1\" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-update">
                                <i class="fas fa-save me-1"></i> Update Data
                            </button>
                            <a href="{{ route('box.index') }}" class="btn btn-light border rounded-3 px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>