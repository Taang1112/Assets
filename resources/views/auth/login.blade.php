<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Assets Collab</title>
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
            --bg-body: #0f172a;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at 50% 0%, #1e1b4b 0%, #0f172a 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
            color: #1e293b;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2.25rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-icon-wrapper {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.6rem;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
            margin: 0 auto 1.25rem;
        }

        .login-header h4 {
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            padding: 0.7rem 1rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-primary-gradient {
            background: var(--primary-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            box-shadow: 0 4px 14px var(--primary-glow);
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            box-shadow: 0 8px 22px var(--primary-glow);
            transform: translateY(-1px);
            color: #fff;
        }

        .alert {
            border-radius: 12px;
            font-size: 0.82rem;
            border: none;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center login-header">
        <div class="brand-icon-wrapper">
            <i class="bi bi-boxes"></i>
        </div>
        <h4>Assets Collab</h4>
        <p>Sistem Manajemen Toko</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3 mt-3" role="alert">
            <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3 mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="mt-4" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@email.com"
                       required
                       autofocus>
            </div>
            @error('email')
                <div class="text-danger mt-1" style="font-size:0.75rem; font-weight:600;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Masukkan password"
                       required>
            </div>
            @error('password')
                <div class="text-danger mt-1" style="font-size:0.75rem; font-weight:600;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-secondary" for="remember" style="font-size:0.8rem; font-weight:600;">
                    Ingat Saya
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary-gradient">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Ke Sistem
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
