<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — {{ Auth::user()->nama_perusahaan ?? 'MulyadiHB Finance' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Limitless Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_limitless.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors.min.css') }}">

    <style>
        :root {
            --brand-dark: #1a3c5e;
            --brand-darker: #0d2137;
            --brand-gold: #c9a227;
            --brand-gold-light: #e8b84b;
            --sidebar-w: 260px;
            --navbar-h: 56px;
            --text-muted-custom: rgba(255,255,255,0.5);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f4f6f9;
            color: #2d3748;
        }

        /* ─── SIDEBAR ─────────────────────────────────── */
        .erp-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--brand-darker) 0%, var(--brand-dark) 100%);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.25);
        }

        .sidebar-brand {
            padding: 20px 22px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .company-name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-brand .app-label {
            font-size: 11px;
            color: var(--brand-gold);
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-brand .logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .sidebar-brand .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--brand-gold), var(--brand-gold-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(201,162,39,0.35);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        .nav-section-label {
            padding: 10px 22px 4px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .nav-item-erp {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 22px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            position: relative;
        }

        .nav-item-erp:hover {
            color: #fff;
            background: rgba(255,255,255,0.07);
            border-left-color: rgba(201,162,39,0.5);
            text-decoration: none;
        }

        .nav-item-erp.active {
            color: #fff;
            background: rgba(201,162,39,0.15);
            border-left-color: var(--brand-gold);
        }

        .nav-item-erp .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .nav-item-erp .nav-badge {
            margin-left: auto;
            background: var(--brand-gold);
            color: #1a1a2e;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 22px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-user .avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--brand-gold), var(--brand-gold-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #1a1a2e;
            flex-shrink: 0;
        }

        .sidebar-user .user-info .name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            line-height: 1;
            margin-bottom: 2px;
        }

        .sidebar-user .user-info .role {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
        }

        /* ─── NAVBAR ─────────────────────────────────── */
        .erp-navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--navbar-h);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 900;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border-bottom: 1px solid #e8edf2;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-left .page-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--brand-dark);
        }

        .navbar-left .breadcrumb-erp {
            font-size: 12px;
            color: #94a3b8;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid #e8edf2;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
            color: #64748b;
        }

        .navbar-btn:hover {
            background: #f0f4f8;
            color: var(--brand-dark);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border: 1px solid #e8edf2;
            border-radius: 8px;
            background: #fff;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #fff5f5;
            border-color: #fca5a5;
            color: #dc2626;
            text-decoration: none;
        }

        /* ─── CONTENT ─────────────────────────────────── */
        .erp-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            min-height: calc(100vh - var(--navbar-h));
            padding: 28px 28px;
        }

        /* ─── CARD UTILITY ─────────────────────────────── */
        .card-erp {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e8edf2;
        }

        .card-erp-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-erp-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--brand-dark);
            margin: 0;
        }

        .card-erp-body {
            padding: 22px;
        }

        /* ─── BUTTONS ─────────────────────────────────── */
        .btn-primary-erp {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            background: linear-gradient(135deg, var(--brand-dark), #2563a8);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary-erp:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,60,94,0.35);
            color: #fff;
            text-decoration: none;
        }

        .btn-gold-erp {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            background: linear-gradient(135deg, var(--brand-gold), var(--brand-gold-light));
            color: #1a1a2e;
            border: none;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-gold-erp:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201,162,39,0.4);
            color: #1a1a2e;
            text-decoration: none;
        }

        .btn-danger-erp {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 16px;
            background: #fff;
            color: #dc2626;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-danger-erp:hover {
            background: #fff5f5;
            color: #dc2626;
            text-decoration: none;
        }

        .btn-sm-erp {
            padding: 6px 14px !important;
            font-size: 12px !important;
        }

        /* ─── TABLE ─────────────────────────────────── */
        .table-erp {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .table-erp thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 11px 16px;
            border-bottom: 2px solid #e8edf2;
            white-space: nowrap;
        }

        .table-erp tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid #f0f4f8;
            color: #374151;
            vertical-align: middle;
        }

        .table-erp tbody tr:hover { background: #f8fafc; }
        .table-erp tbody tr:last-child td { border-bottom: none; }

        /* ─── BADGES ─────────────────────────────────── */
        .badge-erp {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-warning { background: #fef9c3; color: #a16207; }
        .badge-danger  { background: #fee2e2; color: #dc2626; }
        .badge-info    { background: #dbeafe; color: #1d4ed8; }

        /* ─── ALERTS ─────────────────────────────────── */
        .alert-erp {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }
        .alert-danger  { background: #fff1f2; border: 1px solid #fca5a5; color: #dc2626; }
        .alert-warning { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }

        /* ─── FORM ─────────────────────────────────── */
        .form-group-erp { margin-bottom: 20px; }

        .form-label-erp {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
        }

        .form-control-erp {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: 13.5px;
            color: #374151;
            background: #fff;
            transition: all 0.2s;
            outline: none;
        }

        .form-control-erp:focus {
            border-color: var(--brand-dark);
            box-shadow: 0 0 0 3px rgba(26,60,94,0.08);
        }

        .form-control-erp.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        /* ─── STAT CARDS ─────────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e8edf2;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon.blue  { background: #dbeafe; }
        .stat-icon.green { background: #dcfce7; }
        .stat-icon.gold  { background: #fef9c3; }
        .stat-icon.red   { background: #fee2e2; }

        .stat-body .label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-body .value {
            font-size: 22px;
            font-weight: 800;
            color: var(--brand-dark);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-body .sub {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ─── MONEY FORMAT ─────────────────────────────── */
        .money { font-family: 'Courier New', monospace; font-weight: 700; }

        /* ─── RESPONSIVE ─────────────────────────────── */
        @media (max-width: 768px) {
            .erp-sidebar { transform: translateX(-100%); }
            .erp-sidebar.open { transform: translateX(0); }
            .erp-navbar { left: 0; }
            .erp-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="erp-sidebar" id="erpSidebar">
    <div class="sidebar-brand">
        <div class="logo-row">
            <div class="logo-icon">💰</div>
            <div>
                <div class="company-name">{{ Auth::user()->nama_perusahaan ?? 'Perusahaan' }}</div>
                <div class="app-label">Finance ERP</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item-erp {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <div class="nav-section-label">Master Data</div>

        <a href="{{ route('stok.index') }}"
           class="nav-item-erp {{ request()->routeIs('stok.*') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Manajemen Stok
        </a>

        <a href="{{ route('suplier.index') }}"
           class="nav-item-erp {{ request()->routeIs('suplier.*') ? 'active' : '' }}">
            <span class="nav-icon">🏭</span> Suplier
        </a>

        <a href="{{ route('pelanggan.index') }}"
           class="nav-item-erp {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Customer
        </a>

        <div class="nav-section-label">Transaksi</div>

        <a href="{{ route('transaksi.index') }}"
           class="nav-item-erp {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <span class="nav-icon">🧾</span> Penjualan
        </a>

        <a href="{{ route('pembelian.index') }}"
           class="nav-item-erp {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span> Pembelian
        </a>

        <a href="{{ route('pembayaran.index') }}"
           class="nav-item-erp {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
            <span class="nav-icon">💳</span> Hutang & Piutang
        </a>

        <div class="nav-section-label">Akuntansi</div>

        <a href="{{ route('jurnal.index') }}"
           class="nav-item-erp {{ request()->routeIs('jurnal.*') ? 'active' : '' }}">
            <span class="nav-icon">📔</span> Jurnal Umum
        </a>

        <div class="nav-section-label">Laporan</div>

        <a href="{{ route('laporan.buku-besar') }}"
           class="nav-item-erp {{ request()->routeIs('laporan.buku-besar*') ? 'active' : '' }}">
            <span class="nav-icon">📒</span> Buku Besar
        </a>

        <a href="{{ route('laporan.laba-rugi') }}"
           class="nav-item-erp {{ request()->routeIs('laporan.laba-rugi*') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Laba Rugi
        </a>

        <a href="{{ route('laporan.neraca') }}"
           class="nav-item-erp {{ request()->routeIs('laporan.neraca*') ? 'active' : '' }}">
            <span class="nav-icon">⚖️</span> Neraca
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}</div>
            <div class="user-info">
                <div class="name">{{ Auth::user()->nama ?? 'User' }}</div>
                <div class="role">Administrator</div>
            </div>
        </div>
    </div>
</aside>

{{-- NAVBAR --}}
<header class="erp-navbar">
    <div class="navbar-left">
        <button class="navbar-btn" id="sidebarToggle" title="Toggle Sidebar">☰</button>
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            @hasSection('breadcrumb')
            <div class="breadcrumb-erp">@yield('breadcrumb')</div>
            @endif
        </div>
    </div>

    <div class="navbar-right">
        <div style="font-size:12px;color:#94a3b8;margin-right:8px;">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="btn-logout">
                🚪 Keluar
            </button>
        </form>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="erp-content">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert-erp alert-success">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-erp alert-danger">❌ {{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert-erp alert-warning">⚠️ {{ session('warning') }}</div>
    @endif

    @yield('content')
</main>

{{-- JS Core --}}
<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    // Sidebar toggle
    const sidebar = document.getElementById('erpSidebar');
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });

    // Setup AJAX CSRF
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Format uang rupiah
    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka || 0).toLocaleString('id-ID');
    }

    // Konfirmasi hapus
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(el.dataset.confirm || 'Yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
