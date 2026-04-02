<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sigezita — Sistem Identifikasi Status Gizi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-deep: #0d5c3a;
            --green-mid: #1a8a54;
            --green-light: #2ec278;
            --green-pale: #d4f5e5;
            --orange: #f97316;
            --orange-light: #fed7aa;
            --yellow: #fbbf24;
            --sky: #38bdf8;
            --purple: #a78bfa;
            --white: #fefefe;
            --off-white: #f8fdf9;
            --dark: #0a1f14;
            --text-muted: #4b7a62;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--off-white);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* ── NOISE TEXTURE OVERLAY ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 3rem;
            backdrop-filter: blur(16px);
            background: rgba(248, 253, 249, 0.85);
            border-bottom: 1px solid rgba(46, 194, 120, 0.15);
        }

        .nav-logo {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--green-deep);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-logo .logo-badge {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--green-mid), var(--green-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .nav-login {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.4rem;
            background: var(--green-deep);
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 100px;
            text-decoration: none;
            transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(13, 92, 58, 0.35);
        }

        .nav-login:hover {
            background: var(--green-mid);
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(13, 92, 58, 0.45);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 7rem 3rem 5rem;
            overflow: hidden;
        }

        /* Blob decorations */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.55;
            pointer-events: none;
        }

        .blob-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, var(--green-light), transparent 70%);
            top: -100px; right: -100px;
            animation: float1 8s ease-in-out infinite;
        }

        .blob-2 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, var(--orange-light), transparent 70%);
            bottom: 0; left: -80px;
            animation: float2 10s ease-in-out infinite;
        }

        .blob-3 {
            width: 280px; height: 280px;
            background: radial-gradient(circle, var(--sky), transparent 70%);
            top: 40%; left: 40%;
            opacity: 0.25;
            animation: float1 12s ease-in-out infinite reverse;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.05); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-20px, 20px) scale(1.08); }
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--green-pale);
            color: var(--green-deep);
            font-family: 'Sora', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.35rem 0.85rem;
            border-radius: 100px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(46, 194, 120, 0.3);
            animation: fadeUp 0.6s ease both;
        }

        .hero-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(2.8rem, 5vw, 4.2rem);
            font-weight: 800;
            line-height: 1.08;
            color: var(--dark);
            margin-bottom: 1.25rem;
            animation: fadeUp 0.7s ease 0.1s both;
        }

        .hero-title .highlight {
            color: var(--green-mid);
            position: relative;
            display: inline-block;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 0; right: 0;
            height: 6px;
            background: var(--green-light);
            border-radius: 3px;
            opacity: 0.4;
            transform: scaleX(0);
            transform-origin: left;
            animation: underlineGrow 0.8s ease 0.8s forwards;
        }

        @keyframes underlineGrow {
            to { transform: scaleX(1); }
        }

        .hero-desc {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 500px;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.7s ease 0.2s both;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeUp 0.7s ease 0.3s both;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2rem;
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 100px;
            text-decoration: none;
            box-shadow: 0 8px 32px rgba(13, 92, 58, 0.4);
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(13, 92, 58, 0.5);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 2rem;
            background: transparent;
            color: var(--green-deep);
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 100px;
            text-decoration: none;
            border: 2px solid var(--green-light);
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: var(--green-pale);
            transform: translateY(-3px);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── HERO VISUAL ── */
        .hero-visual {
            position: relative;
            animation: fadeUp 0.8s ease 0.3s both;
        }

        .dashboard-card {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 32px 80px rgba(13, 92, 58, 0.15), 0 0 0 1px rgba(46, 194, 120, 0.1);
            position: relative;
            z-index: 2;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .card-title-sm {
            font-family: 'Sora', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .status-dot {
            width: 8px; height: 8px;
            background: var(--green-light);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(46, 194, 120, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(46, 194, 120, 0); }
        }

        .stat-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .stat-box {
            background: var(--off-white);
            border-radius: 14px;
            padding: 1rem 0.75rem;
            text-align: center;
            border: 1px solid rgba(46, 194, 120, 0.12);
        }

        .stat-num {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .stat-label {
            font-size: 0.68rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .stat-box.green .stat-num { color: var(--green-mid); }
        .stat-box.orange .stat-num { color: var(--orange); }
        .stat-box.yellow .stat-num { color: #b45309; }

        /* Mini bar chart */
        .chart-area {
            background: var(--off-white);
            border-radius: 14px;
            padding: 1rem;
            border: 1px solid rgba(46, 194, 120, 0.12);
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            height: 70px;
        }

        .bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .bar::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.3), transparent);
        }

        .bar.normal { background: var(--green-mid); height: 85%; }
        .bar.kurus { background: var(--orange); height: 35%; }
        .bar.stunting { background: var(--yellow); height: 20%; }
        .bar.obesitas { background: var(--sky); height: 10%; }
        .bar.normal2 { background: var(--green-mid); height: 90%; }
        .bar.kurus2 { background: var(--orange); height: 30%; }
        .bar.stunting2 { background: var(--yellow); height: 25%; }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.68rem;
            color: var(--text-muted);
        }

        .legend-dot {
            width: 8px; height: 8px;
            border-radius: 2px;
        }

        /* Floating badges */
        .float-badge {
            position: absolute;
            background: white;
            border-radius: 16px;
            padding: 0.65rem 1rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            z-index: 3;
            animation: floatBadge 4s ease-in-out infinite;
        }

        .float-badge.badge-1 {
            top: -20px; left: -30px;
            color: var(--green-deep);
            border: 1px solid var(--green-pale);
            animation-delay: 0s;
        }

        .float-badge.badge-2 {
            bottom: 20px; right: -25px;
            color: var(--orange);
            border: 1px solid var(--orange-light);
            animation-delay: 1.5s;
        }

        @keyframes floatBadge {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* ── SECTION SHARED ── */
        section {
            position: relative;
            z-index: 1;
        }

        .section-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 5rem 3rem;
        }

        .section-tag {
            font-family: 'Sora', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--green-mid);
            margin-bottom: 0.75rem;
        }

        .section-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .section-desc {
            font-size: 1rem;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 560px;
        }

        /* ── FEATURES ── */
        .features-section {
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 3.5rem;
        }

        .feature-card {
            background: var(--off-white);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(46, 194, 120, 0.12);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(46, 194, 120, 0.04));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(13, 92, 58, 0.12);
            border-color: rgba(46, 194, 120, 0.25);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.25rem;
        }

        .feature-icon.g1 { background: linear-gradient(135deg, #d4f5e5, #a7f0c8); }
        .feature-icon.g2 { background: linear-gradient(135deg, #fed7aa, #fde68a); }
        .feature-icon.g3 { background: linear-gradient(135deg, #bae6fd, #c7d2fe); }
        .feature-icon.g4 { background: linear-gradient(135deg, #fce7f3, #ddd6fe); }
        .feature-icon.g5 { background: linear-gradient(135deg, #d4f5e5, #bae6fd); }
        .feature-icon.g6 { background: linear-gradient(135deg, #fde68a, #fed7aa); }

        .feature-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* ── STATS BANNER ── */
        .stats-banner {
            background: linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 60%, var(--green-light) 100%);
            position: relative;
            overflow: hidden;
        }

        .stats-banner::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 70%);
            top: -200px; right: -100px;
            border-radius: 50%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
        }

        .stat-big {
            font-family: 'Sora', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: white;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-big span {
            color: var(--green-pale);
        }

        .stat-small {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.75);
            font-weight: 400;
        }

        /* ── STEPS ── */
        .steps-section { background: var(--off-white); }

        .steps-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
            margin-top: 3.5rem;
        }

        .step-list { display: flex; flex-direction: column; gap: 1.5rem; }

        .step-item {
            display: flex;
            gap: 1.25rem;
            align-items: flex-start;
            padding: 1.25rem;
            border-radius: 16px;
            transition: all 0.3s;
            cursor: default;
        }

        .step-item:hover {
            background: white;
            box-shadow: 0 8px 32px rgba(13, 92, 58, 0.1);
        }

        .step-num {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--green-mid), var(--green-light));
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-content h4 {
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }

        .step-content p {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Gizi chart visual */
        .gizi-visual {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 24px 64px rgba(13, 92, 58, 0.12);
            border: 1px solid rgba(46, 194, 120, 0.1);
        }

        .gizi-title {
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.25rem;
        }

        .gizi-bar-row {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .gizi-bar-item { display: flex; flex-direction: column; gap: 0.25rem; }

        .gizi-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .gizi-bar-track {
            height: 10px;
            background: var(--off-white);
            border-radius: 100px;
            overflow: hidden;
        }

        .gizi-bar-fill {
            height: 100%;
            border-radius: 100px;
            transition: width 1s ease;
        }

        .fill-green { background: linear-gradient(90deg, var(--green-mid), var(--green-light)); }
        .fill-orange { background: linear-gradient(90deg, #ea580c, var(--orange)); }
        .fill-yellow { background: linear-gradient(90deg, #d97706, var(--yellow)); }
        .fill-sky { background: linear-gradient(90deg, #0284c7, var(--sky)); }

        /* ── CTA SECTION ── */
        .cta-section {
            background: white;
        }

        .cta-box {
            background: linear-gradient(135deg, var(--green-deep), var(--green-mid));
            border-radius: 32px;
            padding: 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 70%);
            top: -150px; right: -100px;
            border-radius: 50%;
        }

        .cta-box::after {
            content: '🌱';
            position: absolute;
            font-size: 8rem;
            opacity: 0.08;
            bottom: -1rem;
            left: 2rem;
        }

        .cta-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            position: relative;
        }

        .cta-desc {
            color: rgba(255,255,255,0.8);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .btn-white {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2.25rem;
            background: white;
            color: var(--green-deep);
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 100px;
            text-decoration: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            transition: all 0.3s;
            position: relative;
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 48px rgba(0,0,0,0.3);
        }

        /* ── FOOTER ── */
        footer {
            background: var(--dark);
            padding: 2.5rem 3rem;
            position: relative;
            z-index: 1;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-logo {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            color: white;
        }

        .footer-text {
            font-size: 0.825rem;
            color: rgba(255,255,255,0.45);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            nav { padding: 1rem 1.5rem; }
            .hero { padding: 6rem 1.5rem 3rem; }
            .hero-inner { grid-template-columns: 1fr; gap: 3rem; }
            .features-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .steps-grid { grid-template-columns: 1fr; gap: 2rem; }
            .section-inner { padding: 4rem 1.5rem; }
            .cta-box { padding: 2.5rem 1.5rem; }
        }

        @media (max-width: 540px) {
            .features-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .hero-title { font-size: 2.2rem; }
        }
    </style>
</head>
<body>

    <!-- NAV -->
    <nav>
        <a href="#" class="nav-logo">
            <span class="logo-badge">⚖️</span>
            Sigezita
        </a>
        <a href="{{ route('login') }}" class="nav-login">
            Masuk &rarr;
        </a>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-tag">🌿 Sistem Informasi Posyandu</div>
                <h1 class="hero-title">
                    Identifikasi <span class="highlight">Status Gizi</span> Balita Lebih Akurat
                </h1>
                <p class="hero-desc">
                    Sigezita membantu petugas Posyandu memantau, menganalisis, dan melaporkan status gizi balita secara digital — cepat, tepat, dan terpercaya.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('login') }}" class="btn-primary">
                        Masuk Sekarang &rarr;
                    </a>
                    <a href="#fitur" class="btn-secondary">
                        Lihat Fitur ↓
                    </a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="float-badge badge-1">
                    ✅ 98 Balita Normal
                </div>
                <div class="dashboard-card">
                    <div class="card-header">
                        <span class="card-title-sm">Rekap Bulan Ini</span>
                        <span class="status-dot"></span>
                    </div>
                    <div class="stat-row">
                        <div class="stat-box green">
                            <div class="stat-num">142</div>
                            <div class="stat-label">Total Balita</div>
                        </div>
                        <div class="stat-box orange">
                            <div class="stat-num">8</div>
                            <div class="stat-label">Perlu Perhatian</div>
                        </div>
                        <div class="stat-box yellow">
                            <div class="stat-num">3</div>
                            <div class="stat-label">Stunting</div>
                        </div>
                    </div>
                    <div class="chart-area">
                        <div class="chart-bars">
                            <div class="bar normal"></div>
                            <div class="bar kurus"></div>
                            <div class="bar stunting"></div>
                            <div class="bar obesitas"></div>
                            <div class="bar normal2"></div>
                            <div class="bar kurus2"></div>
                            <div class="bar stunting2"></div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <div class="legend-dot" style="background:var(--green-mid)"></div>
                                Normal
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background:var(--orange)"></div>
                                Kurus
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background:var(--yellow)"></div>
                                Stunting
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background:var(--sky)"></div>
                                Obesitas
                            </div>
                        </div>
                    </div>
                </div>
                <div class="float-badge badge-2">
                    📊 Laporan Siap Export
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features-section" id="fitur">
        <div class="section-inner">
            <div class="section-tag">✦ Fitur Unggulan</div>
            <h2 class="section-title">Semua yang Dibutuhkan<br>Petugas Posyandu</h2>
            <p class="section-desc">Dirancang khusus untuk mempermudah kerja petugas di lapangan dengan antarmuka yang intuitif dan fitur yang lengkap.</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon g1">👶</div>
                    <div class="feature-title">Data Balita Terpusat</div>
                    <div class="feature-desc">Kelola data balita secara lengkap — identitas, riwayat tumbuh kembang, dan status gizi dalam satu tempat.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon g2">📏</div>
                    <div class="feature-title">Input Pengukuran</div>
                    <div class="feature-desc">Catat berat badan, tinggi badan, dan lingkar kepala dengan mudah.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon g3">📊</div>
                    <div class="feature-title">Analisis Status Gizi</div>
                    <div class="feature-desc">Identifikasi otomatis status gizi: normal, kurus, gemuk, stunting, atau wasting berdasarkan z-score.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon g4">📋</div>
                    <div class="feature-title">Laporan & Export</div>
                    <div class="feature-desc">Generate laporan bulanan dalam hitungan detik. Export ke Excel atau cetak langsung dari browser.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon g5">🏥</div>
                    <div class="feature-title">Manajemen Posyandu</div>
                    <div class="feature-desc">Admin dapat mengelola data posyandu, menambah petugas, dan mengatur akses dengan mudah.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon g6">🔐</div>
                    <div class="feature-title">Akses Berbasis Peran</div>
                    <div class="feature-desc">Sistem role Admin & Petugas memastikan data aman. Setiap petugas hanya melihat data posyandunya.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS BANNER -->
    <section class="stats-banner">
        <div class="section-inner" style="padding: 3.5rem 3rem;">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-big">4<span>+</span></div>
                    <div class="stat-small">Kategori Status Gizi Terdeteksi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-big">0<span>s</span></div>
                    <div class="stat-small">Kalkulasi Otomatis</div>
                </div>
                <div class="stat-item">
                    <div class="stat-big">∞</div>
                    <div class="stat-small">Data Balita Tersimpan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="steps-section">
        <div class="section-inner">
            <div class="section-tag">✦ Cara Kerja</div>
            <h2 class="section-title">Mudah Digunakan,<br>Hasil yang Akurat</h2>

            <div class="steps-grid">
                <div class="step-list">
                    <div class="step-item">
                        <div class="step-num">1</div>
                        <div class="step-content">
                            <h4>Daftarkan Data Balita</h4>
                            <p>Masukkan data identitas balita seperti nama, tanggal lahir, nama orang tua, dan posyandu tempat terdaftar.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">2</div>
                        <div class="step-content">
                            <h4>Input Hasil Pengukuran</h4>
                            <p>Catat berat badan dan tinggi badan balita setiap bulan. Sistem langsung menghitung z-score secara otomatis.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">3</div>
                        <div class="step-content">
                            <h4>Lihat Status Gizi</h4>
                            <p>Sistem mengidentifikasi status gizi balita secara instan — normal, kurus, stunting, atau obesitas.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-num">4</div>
                        <div class="step-content">
                            <h4>Buat & Export Laporan</h4>
                            <p>Generate laporan rekap bulanan untuk diserahkan ke Puskesmas atau Dinas Kesehatan dengan sekali klik.</p>
                        </div>
                    </div>
                </div>

                <div class="gizi-visual">
                    <div class="gizi-title">📈 Distribusi Status Gizi — Maret 2025</div>
                    <div class="gizi-bar-row">
                        <div class="gizi-bar-item">
                            <div class="gizi-bar-label">
                                <span>Normal</span>
                                <span style="color: var(--green-mid); font-weight: 700;">78%</span>
                            </div>
                            <div class="gizi-bar-track">
                                <div class="gizi-bar-fill fill-green" style="width: 78%"></div>
                            </div>
                        </div>
                        <div class="gizi-bar-item">
                            <div class="gizi-bar-label">
                                <span>Kurus</span>
                                <span style="color: var(--orange); font-weight: 700;">10%</span>
                            </div>
                            <div class="gizi-bar-track">
                                <div class="gizi-bar-fill fill-orange" style="width: 10%"></div>
                            </div>
                        </div>
                        <div class="gizi-bar-item">
                            <div class="gizi-bar-label">
                                <span>Stunting</span>
                                <span style="color: #b45309; font-weight: 700;">7%</span>
                            </div>
                            <div class="gizi-bar-track">
                                <div class="gizi-bar-fill fill-yellow" style="width: 7%"></div>
                            </div>
                        </div>
                        <div class="gizi-bar-item">
                            <div class="gizi-bar-label">
                                <span>Obesitas</span>
                                <span style="color: #0284c7; font-weight: 700;">5%</span>
                            </div>
                            <div class="gizi-bar-track">
                                <div class="gizi-bar-fill fill-sky" style="width: 5%"></div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--green-pale);">
                        <div style="font-family: 'Sora', sans-serif; font-size: 0.78rem; color: var(--text-muted); font-weight: 500; margin-bottom: 0.75rem;">Riwayat Berat Badan — Ani (12 bln)</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.72rem; color: var(--text-muted);">5 kg</span>
                            <div style="flex: 1; height: 2px; background: var(--green-pale); border-radius: 2px; position: relative;">
                                <div style="position: absolute; inset: 0; background: linear-gradient(90deg, var(--green-light), var(--green-mid)); border-radius: 2px; width: 75%;"></div>
                                <div style="position: absolute; top: 50%; right: 25%; transform: translate(50%, -50%); width: 10px; height: 10px; background: var(--green-mid); border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.15);"></div>
                            </div>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">10 kg</span>
                        </div>
                        <div style="text-align: center; margin-top: 0.5rem;">
                            <span style="font-family: 'Sora', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--green-mid);">7.8 kg</span>
                            <span style="font-size: 0.72rem; color: var(--text-muted); margin-left: 0.35rem;">↑ +0.3 dari bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="section-inner">
            <div class="cta-box">
                <h2 class="cta-title">Mulai Gunakan Sigezita Sekarang</h2>
                <p class="cta-desc">Bergabung bersama petugas Posyandu yang sudah memanfaatkan teknologi untuk tumbuh kembang balita yang lebih baik.</p>
                <a href="{{ route('login') }}" class="btn-white">
                    Masuk ke Dashboard &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-inner">
            <div class="footer-logo">⚖️ Sigezita</div>
            <div class="footer-text">Sistem Identifikasi Status Gizi · Posyandu Digital</div>
            <div class="footer-text">© {{ date('Y') }} Sigezita. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>