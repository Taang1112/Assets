<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Assets Collab') — Sistem Manajemen Toko</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --primary-glow: rgba(79, 70, 229, 0.25);
            --sidebar-w: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --bg-body: #f8fafc;
            --card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            --card-shadow-hover: 0 12px 28px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: var(--bg-body);
            color: #1e293b;
            overflow-x: hidden;
            width: 100%;
        }

        /* Fixed Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1050;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: auto;
        }

        .sidebar-brand {
            flex-shrink: 0;
            padding: 1.2rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.15rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
            flex-shrink: 0;
        }

        .brand-text h5 {
            color: #fff;
            font-weight: 800;
            margin: 0;
            font-size: 1rem;
            letter-spacing: -0.3px;
        }

        .brand-text small {
            color: #64748b;
            font-size: 0.68rem;
            font-weight: 500;
            display: block;
        }

        .sidebar-nav {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 0.85rem 0.65rem;
            position: relative;
            z-index: 1055;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .nav-section-title {
            color: #475569;
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 0.75rem 0.75rem 0.25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.6rem 0.75rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 9px;
            margin-bottom: 2px;
            position: relative;
            z-index: 1060;
            cursor: pointer;
            pointer-events: auto !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #f8fafc;
            transform: translateX(3px);
        }

        .sidebar-link.active {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        }

        .sidebar-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 1rem 1.15rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.25);
        }

        /* Sidebar Backdrop Overlay on Mobile */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Desktop specific sidebar rules */
        @media (min-width: 992px) {
            .sidebar {
                transform: none !important;
                z-index: 1050 !important;
            }
            .sidebar-backdrop {
                display: none !important;
                pointer-events: none !important;
            }
        }

        /* Main Content Layout */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-w));
            max-width: 100%;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
            width: 100%;
        }

        .topbar-title {
            font-weight: 800;
            font-size: 1.1rem;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin: 0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.3rem 0.75rem;
            border-radius: 30px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
        }

        .main-content {
            flex: 1;
            padding: 1.5rem;
            width: 100%;
            max-width: 100%;
        }

        /* Glass / Elevated Cards */
        .card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease;
            width: 100%;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.1rem 1.35rem;
        }

        /* Stat Cards */
        .stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #f1f5f9;
            box-shadow: var(--card-shadow);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            width: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 0.15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            letter-spacing: -0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Modern Table Responsive Wrapper */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0 0 16px 16px;
        }

        .table {
            margin-bottom: 0;
            width: 100%;
        }

        .table thead th {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: background 0.15s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.82rem;
            color: #334155;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }

        /* Modern Badges */
        .badge-pill-custom {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.3rem 0.75rem;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-aktif    { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-nonaktif { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-baik     { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-rringan  { background: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
        .badge-rberat   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-tersedia { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-dipakai  { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-dipinjam { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
        .badge-dihapus  { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .badge-pending  { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-selesai  { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-batal    { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Modern Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 0.5rem 1.1rem;
            transition: all 0.2s ease;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            color: #ffffff;
            box-shadow: 0 6px 18px var(--primary-glow);
            transform: translateY(-1px);
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            flex-shrink: 0;
        }

        /* Form styling */
        .form-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.3rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 0.82rem;
            padding: 0.5rem 0.85rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        /* Alert animations */
        .alert {
            border-radius: 12px;
            font-size: 0.82rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .empty-state {
            padding: 3rem 1.25rem;
            text-align: center;
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            background: #f1f5f9;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 1.5rem;
            margin-bottom: 0.85rem;
        }

        /* Mobile Breakpoints & Responsive Overrides */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.3);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            .topbar {
                padding: 0.75rem 0.85rem;
            }

            .topbar-title {
                font-size: 0.95rem;
            }

            .main-content {
                padding: 0.85rem 0.75rem !important;
                width: 100% !important;
                max-width: 100vw !important;
            }

            .card {
                border-radius: 12px !important;
            }

            .card-header {
                padding: 0.85rem 1rem;
            }

            .stat-card {
                padding: 0.75rem 0.85rem;
                gap: 0.65rem;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 1.05rem;
                border-radius: 9px;
            }

            .stat-label {
                font-size: 0.65rem;
                margin-bottom: 0.05rem;
            }

            .stat-value {
                font-size: 1.1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Backdrop Overlay for Mobile Sidebar Drawer -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2.5">
            <div class="brand-icon">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="brand-text">
                <h5>Assets Collab</h5>
                <small>Sistem Manajemen Toko</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white d-lg-none" id="sidebarClose" aria-label="Close"></button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">UTAMA</div>
        <a href="{{ route('transaksi.index') }}"
           class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> Data Transaksi
        </a>

        <div class="nav-section-title">MASTER DATA</div>
        <a href="{{ route('produk.index') }}"
           class="sidebar-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Produk
        </a>
        <a href="{{ route('pelanggan.index') }}"
           class="sidebar-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Pelanggan
        </a>
        <a href="{{ route('karyawan.index') }}"
           class="sidebar-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> Karyawan
        </a>
        <a href="{{ route('inventaris.index') }}"
           class="sidebar-link {{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
            <i class="bi bi-archive-fill"></i> Inventaris
        </a>

        <div class="nav-section-title">KATEGORI</div>
        <a href="{{ route('kategori-produk.index') }}"
           class="sidebar-link {{ request()->routeIs('kategori-produk.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> Kat. Produk
        </a>
        <a href="{{ route('kategori-inventaris.index') }}"
           class="sidebar-link {{ request()->routeIs('kategori-inventaris.*') ? 'active' : '' }}">
            <i class="bi bi-tag-fill"></i> Kat. Inventaris
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:0.78rem;">
                AC
            </div>
            <div>
                <div class="text-white fw-bold" style="font-size:0.78rem;">Administrator</div>
                <small class="text-secondary" style="font-size:0.65rem;">Assets Collab v1.0</small>
            </div>
        </div>
    </div>
</aside>

<div class="main-wrapper">
    <header class="topbar">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <button class="btn btn-icon btn-light border d-lg-none" id="sidebarToggle" type="button" aria-label="Menu Mobile">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="topbar-title text-truncate">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="status-pill flex-shrink-0">
            <span class="status-dot"></span> <span class="d-none d-sm-inline">System </span>Active
        </div>
    </header>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto dismiss alerts
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => { try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e){} }, 4500);
    });

    // Mobile Sidebar Drawer Toggle Logic
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        if (sidebar && backdrop) {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        if (sidebar && backdrop) {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    // Close sidebar when clicking any link inside sidebar on mobile screens
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>
