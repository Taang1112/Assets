<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Pelanggan')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:   #4f46e5;
            --primary-d: #3730a3;
            --accent:    #06b6d4;
            --sidebar-w: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #4f46e5;
            --bg: #f1f5f9;
        }

        * { font-family: 'Inter', sans-serif; }
        body { background: var(--bg); min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; inset-y: 0; left: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 1000;
            transition: transform .3s;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand h5 {
            color: #fff; font-weight: 700; margin: 0;
            font-size: 1.1rem; letter-spacing: .3px;
        }
        .sidebar-brand small { color: var(--sidebar-text); font-size: .75rem; }
        .sidebar-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
        .nav-label {
            color: #475569; font-size: .65rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            padding: .75rem 1.25rem .25rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none; border-radius: 0;
            transition: background .2s, color .2s;
            font-size: .875rem; font-weight: 500;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.06); color: #fff; }
        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }
        .sidebar-link i { font-size: 1rem; width: 20px; text-align: center; }

        /* ── Main content ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .875rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
        }
        .topbar-title { font-weight: 600; font-size: 1rem; color: #0f172a; }
        .main-content { flex: 1; padding: 1.75rem 1.5rem; }

        /* ── Card ── */
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
        .card-header {
            background: #fff; border-bottom: 1px solid #f1f5f9;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
        }

        /* ── Badges / Status ── */
        .badge-aktif    { background:#dcfce7; color:#166534; }
        .badge-nonaktif { background:#fee2e2; color:#991b1b; }

        /* ── Table ── */
        .table thead th {
            font-size: .75rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .5px; color: #64748b;
            background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        }
        .table tbody tr:hover { background: #f8fafc; }
        .table td { vertical-align: middle; font-size: .875rem; color: #334155; }

        /* ── Buttons ── */
        .btn-primary   { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-d); border-color: var(--primary-d); }
        .btn-icon { width: 32px; height: 32px; padding: 0; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; }

        /* ── Form ── */
        .form-label { font-size: .8125rem; font-weight: 600; color: #475569; margin-bottom: .35rem; }
        .form-control, .form-select {
            border-radius: 8px; border-color: #e2e8f0;
            font-size: .875rem; padding: .5rem .85rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.15);
        }
        .is-invalid { border-color: #ef4444 !important; }

        /* ── Alert toast ── */
        .alert { border-radius: 10px; font-size: .875rem; border: none; }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 12px; padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
            background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.07);
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .stat-label { font-size: .75rem; color: #64748b; font-weight: 500; }
        .stat-value { font-size: 1.6rem; font-weight: 700; color: #0f172a; line-height: 1; }
    </style>

    @stack('styles')
</head>
<body>

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-boxes me-2 text-indigo-400"></i>Assets Collab</h5>
        <small>Sistem Manajemen Data</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('pelanggan.index') }}"
           class="sidebar-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Data Pelanggan
        </a>
    </nav>
    <div class="p-3 border-top" style="border-color:rgba(255,255,255,.07)!important;">
        <small class="text-secondary" style="font-size:.72rem;">
            &copy; {{ date('Y') }} Assets Collab
        </small>
    </div>
</aside>

<!-- ═══════════════ MAIN ═══════════════ -->
<div class="main-wrapper">
    <!-- Topbar -->
    <header class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border" style="font-size:.75rem;">
                <i class="bi bi-circle-fill text-success me-1" style="font-size:.5rem;"></i>Online
            </span>
        </div>
    </header>

    <!-- Content -->
    <main class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto-dismiss alerts after 4 seconds
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            let a = bootstrap.Alert.getOrCreateInstance(el);
            if (a) a.close();
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>
