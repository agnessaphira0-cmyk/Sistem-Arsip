<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Arsip PT Bank Sumut</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #004B87;     /* Biru Resmi Bank Sumut */
            --dark-blue: #002d54;        /* Biru Tua untuk kontras teks/header */
            --bank-orange: #FF7A00;      /* Orange Khas Bank Sumut */
            --orange-hover: #E06B00;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-blue);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Pembungkus Background khusus untuk efek blur agar kartu login TIDAK ikut blur */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle, rgba(0,0,0,0) 30%, rgba(0,0,0,0.6) 100%), 
                              url("{{ asset('img/gedung-pusat.jpg') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            /* FILTER BLUR: Menghaluskan pixel gambar yang pecah/burik */
            filter: blur(5px);
            -webkit-filter: blur(5px);
            /* TRANSFORM SCALE: Menaikkan sedikit skala gambar untuk menutup garis putih di pinggiran layar akibat blur */
            transform: scale(1.04); 
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            /* Memastikan kartu login berada di atas background blur dan tetap tajam */
            position: relative;
            z-index: 2; 
        }

        .login-header {
            background: linear-gradient(135deg, rgba(0, 45, 84, 0.95), rgba(0, 75, 135, 0.95));
            padding: 35px 32px;
            text-align: center;
            color: white;
            position: relative;
        }

        /* Aksen Garis Orange Khas Bank Sumut di bawah header */
        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--bank-orange);
        }

        .login-header img {
            height: 52px;
            width: auto;
            margin-bottom: 16px;
            object-fit: contain;
        }

        .login-header h4 {
            font-weight: 700;
            margin: 0;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        /* Identitas Badge Divisi Teknologi Informasi */
        .divisi-badge {
            display: inline-block;
            background: rgba(255, 122, 0, 0.25);
            color: #FF9E40;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 50px;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-body {
            padding: 35px 32px;
        }

        .form-label {
            font-size: 14px;
            margin-bottom: 6px;
            color: #1e293b;
        }

        .form-control {
            border-radius: 10px;
            padding: 11px 14px;
            border: 1px solid #b2c0cc;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 75, 135, 0.2);
            background: #fff;
        }

        /* Tombol Utama warna Orange Bank Sumut */
        .btn-login {
            background: linear-gradient(135deg, var(--bank-orange), #FF9124);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 600;
            width: 100%;
            font-size: 15px;
            box-shadow: 0 4px 14px rgba(255, 122, 0, 0.3);
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--orange-hover), var(--bank-orange));
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(255, 122, 0, 0.4);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .input-group-text {
            background: #f1f5f9;
            border: 1px solid #b2c0cc;
            border-right: none;
            color: #475569;
        }

        .input-group .form-control {
            border-left: none;
        }

        .login-footer {
            text-align: center;
            padding: 16px;
            background: rgba(248, 249, 250, 0.9);
            font-size: 12px;
            color: #475569;
            border-top: 1px solid rgba(0,0,0,0.05);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Bank Sumut">
            <h4>SISTEM INFORMASI ARSIP</h4>
            <div><span class="divisi-badge"><i class="fas fa-code-branch me-1"></i> Divisi Teknologi Informasi</span></div>
        </div>
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" 
                               placeholder="Masukkan username" 
                               value="{{ old('username') }}" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>
        </div>
        <div class="login-footer">
            &copy; {{ date('Y') }} PT Bank Sumut — Sistem Informasi Arsip
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>