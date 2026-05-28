<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MulyadiHB Finance ERP</title>
    <meta name="description" content="Login ke sistem ERP keuangan MulyadiHB Finance">

    <!-- Limitless CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_limitless.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors.min.css') }}">

    <style>
        :root {
            --brand-dark: #1a3c5e;
            --brand-gold: #c9a227;
            --brand-light: #f0f4f8;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0d2137 0%, #1a3c5e 50%, #0d2137 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow: hidden;
        }

        /* Animated background orbs */
        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,162,39,0.12) 0%, transparent 70%);
            top: -100px; right: -100px;
            animation: floatOrb 8s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: fixed;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26,60,94,0.4) 0%, transparent 70%);
            bottom: -80px; left: -80px;
            animation: floatOrb 10s ease-in-out infinite reverse;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(201,162,39,0.2);
            border-radius: 20px;
            padding: 44px 40px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
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
            width: 64px; height: 64px;
            background: linear-gradient(135deg, var(--brand-gold), #e8b84b);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(201,162,39,0.4);
        }

        .brand-logo h1 {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 4px;
            letter-spacing: -0.3px;
        }

        .brand-logo p {
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            margin: 0;
        }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .form-control-auth {
            width: 100%;
            padding: 13px 16px;
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

        .form-control-auth.is-invalid {
            border-color: #e74c3c;
        }

        .invalid-feedback {
            color: #ff7675;
            font-size: 12px;
            margin-top: 6px;
        }

        .btn-login {
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
            letter-spacing: 0.3px;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(201,162,39,0.45);
            filter: brightness(1.08);
        }

        .btn-login:active { transform: translateY(0); }

        .auth-link {
            text-align: center;
            margin-top: 24px;
            color: rgba(255,255,255,0.45);
            font-size: 13px;
        }

        .auth-link a {
            color: var(--brand-gold);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .auth-link a:hover { opacity: 0.8; }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            gap: 12px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        .divider span {
            color: rgba(255,255,255,0.3);
            font-size: 12px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--brand-gold);
        }

        .remember-row label {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            cursor: pointer;
            margin: 0;
        }

        .alert-danger-custom {
            background: rgba(231,76,60,0.15);
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ff7675;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <!-- Brand -->
        <div class="brand-logo">
            <div class="logo-icon">💰</div>
            <h1>MulyadiHB Finance</h1>
            <p>Enterprise Resource Planning System</p>
        </div>

        <!-- Alert errors -->
        @if ($errors->any())
            <div class="alert-danger-custom">
                @foreach ($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control-auth @error('email') is-invalid @enderror"
                    placeholder="nama@perusahaan.com"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control-auth @error('password') is-invalid @enderror"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                Masuk ke Sistem
            </button>
        </form>

        <div class="auth-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function() {
    var btn = document.getElementById('btnLogin');
    btn.textContent = 'Memproses...';
    btn.style.opacity = '0.7';
    btn.disabled = true;
});
</script>
</body>
</html>
