<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokumen - Sistem Arsip PT Bank Sumut</title>
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
        
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 24px; font-size: 14px; font-weight: 500; border-left: 4px solid transparent; transition: all 0.2s ease; text-decoration: none; display: block; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.08); border-left-color: var(--bank-orange); }
        .sidebar .nav-link i { width: 20px; margin-right: 12px; font-size: 15px; }
        .sidebar-section { padding: 16px 24px 6px; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; }
        
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: white; padding: 18px 24px; border-radius: 16px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02); }
        
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); background: white; }
        .form-control, .form-select { border-radius: 10px; padding: 11px 14px; border: 1px solid #b2c0cc; font-size: 14.5px; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(0, 75, 135, 0.15); }
        
        #upload-area { border: 2px dashed #004B87; border-radius: 12px; padding: 25px 20px; text-align: center; cursor: pointer; transition: all 0.2s; background: rgba(0, 75, 135, 0.01); }
        #upload-area:hover { background: #f0f5fa; border-color: var(--dark-blue); }
        
        .btn-update { background: var(--bank-orange); color: white; border-radius: 10px; padding: 10px 24px; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(255, 122, 0, 0.15); transition: all 0.2s; }
        .btn-update:hover { background: var(--orange-hover); color: white; }
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
        <div class="sidebar-section">Fitur</div>
        <a href="{{ route('dokumen.search') }}" class="nav-link"><i class="fas fa-search"></i> Cari Dokumen</a>
        <a href="{{ route('retensi.index') }}" class="nav-link"><i class="fas fa-clock"></i> Retensi Dokumen</a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link"><i class="fas fa-bell"></i> Notifikasi</a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <h5 class="mb-1 fw-bold" style="color: var(--dark-blue)">Ubah Data Dokumen</h5>
            <small class="text-muted">Memperbarui informasi berkas arsip secara berkala</small>
        </div>
        <a href="{{ route('dokumen.index') }}" class="btn btn-light border btn-sm px-3 rounded-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 small">
                        @foreach($errors->all() as $error)
                        <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('dokumen.update', ['dokumen' => $dokumen->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Nomor Surat / Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="no_surat" class="form-control" value="{{ old('no_surat', $dokumen->no_surat) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="nama_dokumen" class="form-control" value="{{ old('nama_dokumen', $dokumen->nama_dokumen) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $dokumen->kategori) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Pilih Wadah Box <span class="text-danger">*</span></label>
                                <select name="box_id" class="form-select" required>
                                    @foreach($boxes as $box)
                                    <option value="{{ $box->id }}" {{ old('box_id', $dokumen->box_id) == $box->id ? 'selected' : '' }}>
                                        {{ $box->kode_box }} (Rak: {{ $box->rak->kode_rak ?? '-' }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Jenis Dokumen <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select" required>
                                    <option value="fisik" {{ old('jenis', $dokumen->jenis) == 'fisik' ? 'selected' : '' }}>Fisik</option>
                                    <option value="digital" {{ old('jenis', $dokumen->jenis) == 'digital' ? 'selected' : '' }}>Digital</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_masuk" class="form-control" value="{{ old('tgl_masuk', $dokumen->tgl_masuk) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tanggal Kadaluarsa Retensi <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_kadaluarsa" class="form-control" value="{{ old('tgl_kadaluarsa', $dokumen->retensi->tgl_kadaluarsa ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Keterangan Aturan Retensi</label>
                                <input type="text" name="ket_retensi" class="form-control" value="{{ old('ket_retensi', $dokumen->retensi->ket_retensi ?? '') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Lampiran Dokumen Digital <span class="text-muted small">(Kosongkan jika tidak ingin mengubah berkas lama)</span></label>
                                <div id="upload-area" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-file-pdf fa-2x text-danger mb-2 d-block"></i>
                                    <div id="fileName" class="fw-medium text-secondary" style="font-size:14px;">
                                        @if($dokumen->file_path)
                                            <span class="text-primary"><i class="fas fa-paperclip me-1"></i> Berkas saat ini: {{ basename($dokumen->file_path) }}</span>
                                        @else
                                            Klik area ini untuk mengganti/melampirkan file berkas baru (PDF, Gambar)
                                        @endif
                                    </div>
                                </div>
                                <input type="file" name="file_path" id="fileInput" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Catatan Keterangan Tambahan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4 pt-2 border-top">
                            <button type="submit" class="btn btn-update">
                                <i class="fas fa-save me-1"></i> Perbarui Data
                            </button>
                            <a href="{{ route('dokumen.index') }}" class="btn btn-light border px-4 rounded-3">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('fileInput').addEventListener('change', function() {
        document.getElementById('fileName').innerHTML = this.files[0] ? `<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Berkas siap ganti: ${this.files[0].name}</span>` : 'Klik area ini untuk melampirkan file berkas';
    });
</script>
</body>
</html>