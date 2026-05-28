<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — MulyadiHB Finance ERP</title>
    <meta name="description" content="Daftarkan akun perusahaan Anda di sistem ERP MulyadiHB Finance">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_limitless.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors.min.css') }}">

    <style>
        :root {
            --brand-dark: #1a3c5e;
            --brand-gold: #c9a227;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0d2137 0%, #1a3c5e 50%, #0d2137 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 24px 20px;
        }

        body::before {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,162,39,0.1) 0%, transparent 70%);
            top: -80px; left: -80px;
            animation: floatOrb 9s ease-in-out infinite;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
        }

        .register-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 520px;
        }

        .register-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(201,162,39,0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo .logo-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--brand-gold), #e8b84b);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(201,162,39,0.4);
        }

        .brand-logo h1 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 4px;
        }

        .brand-logo p {
            color: rgba(255,255,255,0.45);
            font-size: 13px;
            margin: 0;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 7px;
        }

        .form-control-auth {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            transition: all 0.25s ease;
            outline: none;
        }

        .form-control-auth:focus {
            border-color: var(--brand-gold);
            background: rgba(255,255,255,0.1);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.15);
        }

        .form-control-auth::placeholder { color: rgba(255,255,255,0.3); }

        .form-control-auth.is-invalid { border-color: #e74c3c; }

        .invalid-feedback {
            color: #ff7675;
            font-size: 12px;
            margin-top: 5px;
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--brand-gold), #e8b84b);
            border: none;
            border-radius: 10px;
            color: #1a1a2e;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 8px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(201,162,39,0.45);
        }

        .auth-link {
            text-align: center;
            margin-top: 22px;
            color: rgba(255,255,255,0.45);
            font-size: 13px;
        }

        .auth-link a {
            color: var(--brand-gold);
            text-decoration: none;
            font-weight: 600;
        }

        .section-divider {
            color: rgba(255,255,255,0.3);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 8px 0 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .alert-danger-custom {
            background: rgba(231,76,60,0.15);
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ff7675;
            font-size: 13px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
<div class="register-wrapper">
    <div class="register-card">
        <div class="brand-logo">
            <div class="logo-icon">💰</div>
            <h1>Buat Akun Perusahaan</h1>
            <p>MulyadiHB Finance ERP System</p>
        </div>

        @if ($errors->any())
            <div class="alert-danger-custom">
                @foreach ($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf

            <div class="section-divider">Informasi Akun</div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama"
                        class="form-control-auth @error('nama') is-invalid @enderror"
                        placeholder="Nama Anda" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="nama_perusahaan">Nama Perusahaan *</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan"
                        class="form-control-auth @error('nama_perusahaan') is-invalid @enderror"
                        placeholder="PT. Nama Perusahaan" value="{{ old('nama_perusahaan') }}" required>
                    @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email *</label>
                <input type="email" id="email" name="email"
                    class="form-control-auth @error('email') is-invalid @enderror"
                    placeholder="email@perusahaan.com" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password"
                        class="form-control-auth @error('password') is-invalid @enderror"
                        placeholder="Min. 6 karakter" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control-auth"
                        placeholder="Ulangi password" required>
                </div>
            </div>

            <div class="section-divider">Informasi Kontak</div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="no_telp">No. Telepon</label>
                    <input type="text" id="no_telp" name="no_telp"
                        class="form-control-auth @error('no_telp') is-invalid @enderror"
                        placeholder="08xx-xxxx-xxxx" value="{{ old('no_telp') }}">
                    @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat"
                        class="form-control-auth @error('alamat') is-invalid @enderror"
                        placeholder="Kota, Provinsi" value="{{ old('alamat') }}">
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn-register" id="btnRegister">
                Buat Akun Sekarang
            </button>
        </form>

        <div class="auth-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>
<script>
document.getElementById('registerForm').addEventListener('submit', function() {
    var btn = document.getElementById('btnRegister');
    btn.textContent = 'Memproses...';
    btn.style.opacity = '0.7';
    btn.disabled = true;
});
</script>
</body>
</html>
