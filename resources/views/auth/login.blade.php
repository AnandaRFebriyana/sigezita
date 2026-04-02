<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | Sigezita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --green-deep: #0d5c3a;
            --green-mid: #1a8a54;
            --green-light: #2ec278;
            --green-pale: #d4f5e5;
            --orange: #f97316;
            --orange-light: #fed7aa;
            --sky: #38bdf8;
            --off-white: #f8fdf9;
            --dark: #0a1f14;
            --text-muted: #4b7a62;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--off-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, var(--green-light), transparent 70%);
            top: -120px; right: -120px;
            animation: float1 8s ease-in-out infinite;
        }
        .blob-2 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, var(--orange-light), transparent 70%);
            bottom: -80px; left: -100px;
            animation: float2 10s ease-in-out infinite;
        }
        .blob-3 {
            width: 260px; height: 260px;
            background: radial-gradient(circle, var(--sky), transparent 70%);
            top: 45%; left: 38%;
            opacity: 0.2;
            animation: float1 12s ease-in-out infinite reverse;
        }
        @keyframes float1 {
            0%, 100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(28px,-28px) scale(1.05); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(-20px,20px) scale(1.08); }
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* ── WRAPPER ── */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 900px;
            animation: fadeUp 0.7s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── CARD: true 50/50 grid ── */
        .login-card {
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(13,92,58,0.18), 0 0 0 1px rgba(46,194,120,0.1);
            display: grid;
            grid-template-columns: 1fr 1fr;   /* <── equal halves */
            min-height: 520px;
        }

        /* ── SIDE PANEL ── */
        .login-side {
            background: linear-gradient(160deg, var(--green-deep) 0%, var(--green-mid) 70%, var(--green-light) 100%);
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-side::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            top: -70px; right: -90px;
        }
        .login-side::after {
            content: '';
            position: absolute;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            bottom: -50px; left: -50px;
        }

        .side-top { position: relative; z-index: 1; }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 2.25rem;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .brand-logo .logo-badge {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.2);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            border: 1.5px solid rgba(255,255,255,0.3);
        }

        .side-headline {
            font-family: 'Sora', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }

        .side-sub {
            font-size: 0.82rem;
            opacity: 0.8;
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        .side-features {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .side-feature {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.8rem;
            opacity: 0.88;
        }

        .feat-dot {
            width: 20px; height: 20px;
            background: rgba(255,255,255,0.18);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,0.25);
        }

        /* stat row at bottom of side */
        .side-bottom { position: relative; z-index: 1; }

        .side-stat-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.55rem;
        }

        .side-stat {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.65rem 0.4rem;
            border: 1px solid rgba(255,255,255,0.15);
            text-align: center;
        }

        .s-num {
            font-family: 'Sora', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--green-pale);
            line-height: 1;
        }

        .s-lbl {
            font-size: 0.58rem;
            opacity: 0.7;
            margin-top: 0.2rem;
        }

        /* ── FORM AREA ── */
        .login-form-area {
            padding: 3rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-headline {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }

        .form-sub {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .form-label-custom {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.77rem;
            color: var(--dark);
            margin-bottom: 0.4rem;
            letter-spacing: 0.02em;
            display: block;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 1.15rem;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.8rem;
            z-index: 5;
        }

        .input-wrap input {
            width: 100%;
            height: 2.9rem;
            padding: 0 1rem 0 2.6rem;
            border: 2px solid rgba(46,194,120,0.2);
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            color: var(--dark);
            background: var(--off-white);
            transition: all 0.25s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: var(--green-mid);
            background: white;
            box-shadow: 0 0 0 4px rgba(46,194,120,0.12);
        }

        .input-wrap input::placeholder { color: #a0c4b0; }

        .eye-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.8rem;
            z-index: 5;
            padding: 0;
        }
        .eye-toggle:focus { outline: none; }

        .remember-row {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .check-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .check-label input[type="checkbox"] {
            accent-color: var(--green-mid);
            width: 14px; height: 14px;
        }

        .btn-login-custom {
            width: 100%;
            height: 3rem;
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            border: none;
            border-radius: 100px;
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 28px rgba(13,92,58,0.38);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 38px rgba(13,92,58,0.48);
        }
        .btn-login-custom:active { transform: translateY(0); }

        /* alerts */
        .alert-custom {
            border-radius: 12px;
            font-size: 0.83rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-danger-custom  { background: #fff1f0; color: #c0392b; border: 1px solid #ffc5c0; }
        .alert-success-custom { background: var(--green-pale); color: var(--green-deep); border: 1px solid rgba(46,194,120,0.3); }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .divider hr { flex: 1; border: none; border-top: 1px solid rgba(46,194,120,0.15); }
        .divider span { font-size: 0.7rem; color: var(--text-muted); white-space: nowrap; }

        .form-footer-note {
            margin-top: 1rem;
            font-size: 0.74rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 680px) {
            .login-card { grid-template-columns: 1fr; }
            .login-side { display: none; }
            .login-form-area { padding: 2.5rem 1.75rem; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- ── SIDE PANEL (50%) ── -->
            <div class="login-side">
                <div class="side-top">
                    <div class="brand-logo">
                        <span class="logo-badge">⚖️</span>
                        Sigezita
                    </div>

                    <div class="side-headline">Monitor Gizi<br>Balita Digital</div>
                    <div class="side-sub">
                        Platform terpadu untuk petugas Posyandu memantau dan menganalisis status gizi balita secara akurat.
                    </div>

                    <div class="side-features">
                        <div class="side-feature">
                            <span class="feat-dot">✓</span>
                            Klasifikasi BB/U, TB/U, BB/TB
                        </div>
                        <div class="side-feature">
                            <span class="feat-dot">✓</span>
                            Deteksi dini risiko stunting
                        </div>
                        <div class="side-feature">
                            <span class="feat-dot">✓</span>
                            Laporan &amp; export otomatis
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── FORM AREA (50%) ── -->
            <div class="login-form-area">

                <div class="form-headline">Selamat Datang 👋</div>
                <div class="form-sub">Masuk ke sistem untuk melanjutkan</div>

                {{-- @if($errors->any()) --}}
                {{-- <div class="alert-custom alert-danger-custom"> --}}
                {{--     <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }} --}}
                {{-- </div> --}}
                {{-- @endif --}}
                {{-- @if(session('success')) --}}
                {{-- <div class="alert-custom alert-success-custom"> --}}
                {{--     <i class="fas fa-check-circle"></i> {{ session('success') }} --}}
                {{-- </div> --}}
                {{-- @endif --}}

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <label class="form-label-custom">Alamat Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" placeholder="contoh@gmail.com" required autofocus>
                    </div>

                    <label class="form-label-custom">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="passwordInput" placeholder="••••••••" required>
                        <button type="button" class="eye-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                    <div class="remember-row">
                        <label class="check-label">
                            <input type="checkbox" name="remember" id="rememberMe">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn-login-custom">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk ke Sistem
                    </button>
                </form>

                <div class="divider">
                    <hr><span>Sistem Informasi Posyandu</span><hr>
                </div>

                <div class="form-footer-note">
                    Hubungi administrator jika mengalami masalah saat login atau lupa kata sandi.
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>