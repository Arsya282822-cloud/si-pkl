<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SI-PKL') }} - SMK Labor Binaan FKIP UNRI</title>

    <!-- Favicon & PWA -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sipkl.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-sipkl.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-sipkl.jpg') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons: Phosphor Icons & Lucide -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border-subtle: #e2e8f0;
            --border-accent: #bae6fd;
            --primary-blue: #0284c7;
            --primary-dark: #0369a1;
            --primary-gradient: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            --text-main: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient Glow Background */
        .bg-glow-top {
            position: absolute;
            width: 750px;
            height: 750px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.12) 0%, rgba(248, 250, 252, 0) 70%);
            top: -250px;
            left: 50%;
            transform: translateX(-50%);
            z-index: -1;
            pointer-events: none;
        }

        .bg-glow-bottom {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.08) 0%, rgba(248, 250, 252, 0) 70%);
            bottom: 50px;
            right: -100px;
            z-index: -1;
            pointer-events: none;
        }

        /* Top Navbar */
        .navbar {
            padding: 16px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
        }

        .brand-title {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-main);
            line-height: 1.15;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 9px 20px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-outline {
            background: #ffffff;
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-outline:hover {
            background: #f1f5f9;
            color: var(--text-main);
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
            filter: brightness(1.05);
        }

        /* Hero Container */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 56px 24px 48px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Centered Logo Box */
        .hero-logo-wrapper {
            margin-bottom: 24px;
            position: relative;
        }

        .hero-logo-box {
            width: 120px;
            height: 120px;
            border-radius: 28px;
            background: #ffffff;
            padding: 8px;
            box-shadow: 0 12px 32px -4px rgba(2, 132, 199, 0.25), 0 0 0 1px rgba(224, 242, 254, 0.9);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .hero-logo-box:hover {
            transform: scale(1.03);
        }

        .hero-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }

        /* Hero Pill Badge */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.775rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            margin-bottom: 20px;
            border: 1px solid #bae6fd;
            box-shadow: 0 2px 6px rgba(14, 165, 233, 0.08);
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.85); }
        }

        /* Hero Typography */
        .hero-title {
            margin-bottom: 20px;
            line-height: 1.2;
            letter-spacing: -0.03em;
        }

        .hero-title-top {
            display: block;
            font-size: clamp(1.5rem, 3.5vw, 2.2rem);
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .hero-title-main {
            display: block;
            font-size: clamp(2rem, 5vw, 3.25rem);
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.035em;
        }

        .hero-desc {
            font-size: 1.05rem;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 660px;
            font-weight: 400;
        }

        /* Hero Action Buttons */
        .hero-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            margin-bottom: 44px;
            flex-wrap: wrap;
        }

        .hero-actions .btn {
            padding: 12px 28px;
            font-size: 0.95rem;
        }

        /* Feature Chips / Quick Highlights */
        .hero-highlights {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            max-width: 760px;
        }

        .highlight-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .highlight-item:hover {
            border-color: #bae6fd;
            background: #f0f9ff;
            color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .highlight-item i {
            color: var(--primary-blue);
            font-size: 1rem;
        }

        /* Majors Section */
        .majors-section {
            padding: 50px 24px 70px 24px;
            text-align: center;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        .majors-section-header {
            margin-bottom: 40px;
        }

        .majors-section h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .majors-section-header p {
            color: var(--text-muted);
            font-size: 0.975rem;
        }

        .majors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        .major-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            padding: 18px 16px 20px;
            box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .major-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 28px -6px rgba(2, 132, 199, 0.15);
            border-color: #bae6fd;
        }

        .major-card-img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 14px;
            border: 1px solid #f1f5f9;
        }

        .major-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.35;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
            font-size: 0.825rem;
            border-top: 1px solid var(--border-subtle);
            background: #ffffff;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .navbar { padding: 14px 20px; }
            .hero { padding: 40px 18px 36px; }
            .hero-logo-box { width: 100px; height: 100px; border-radius: 22px; }
            .hero-actions { width: 100%; flex-direction: column; }
            .hero-actions .btn { width: 100%; justify-content: center; }
            .majors-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .majors-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-glow-top"></div>
    <div class="bg-glow-bottom"></div>

    <!-- Top Navigation Bar -->
    <nav class="navbar">
        <a href="{{ url('/') }}" class="brand-logo">
            <div class="brand-icon">
                <i class="ph-bold ph-graduation-cap"></i>
            </div>
            <div>
                <div class="brand-title">SI-PKL</div>
                <div class="brand-subtitle">SMK LABOR PEKANBARU</div>
            </div>
        </a>
        
        <div class="nav-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        <i class="ph-bold ph-squares-four"></i>
                        <span>Dasbor Saya</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="ph-bold ph-sign-in"></i>
                        <span>Masuk Sistem</span>
                    </a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="hero">
        <div class="hero-logo-wrapper">
            <div class="hero-logo-box">
                <img src="{{ asset('images/logo-sipkl.jpg') }}" alt="Logo SI-PKL SMK Labor">
            </div>
        </div>

        <div class="hero-badge">
            <span class="badge-dot"></span>
            <span>SISTEM INFORMASI PRAKTIK KERJA LAPANGAN</span>
        </div>
        
        <h1 class="hero-title">
            <span class="hero-title-top">Transformasi Digital Praktik Kerja Lapangan</span>
            <span class="hero-title-main">Lebih Cerdas, Akurat & Terintegrasi</span>
        </h1>
        
        <p class="hero-desc">
            Digitalisasi program PKL kini lebih transparan dan terukur. Aplikasi ini menyatukan absensi GPS akurat, pembukuan jurnal harian, kolaborasi evaluasi guru bersama industri, serta pencetakan dokumen akhir seperti rapor dan sertifikat resmi tanpa proses manual.
        </p>
        
        <div class="hero-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        <span>Buka Dasbor Saya</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <span>Mulai Sekarang</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                @endauth
            @endif
            <a href="#jurusan" class="btn btn-outline">
                <i class="ph-bold ph-buildings"></i>
                <span>Lihat Program Jurusan</span>
            </a>
        </div>

        <!-- Highlight Badges -->
        <div class="hero-highlights">
            <div class="highlight-item">
                <i class="ph-fill ph-map-pin-line"></i>
                <span>Presensi GPS Akurat</span>
            </div>
            <div class="highlight-item">
                <i class="ph-fill ph-notebook"></i>
                <span>Jurnal Digital Harian</span>
            </div>
            <div class="highlight-item">
                <i class="ph-fill ph-handshake"></i>
                <span>50+ Mitra DUDI</span>
            </div>
            <div class="highlight-item">
                <i class="ph-fill ph-certificate"></i>
                <span>Rapor & Sertifikat Otomatis</span>
            </div>
        </div>
    </main>

    <!-- Majors Section -->
    <section class="majors-section" id="jurusan">
        <div class="majors-section-header">
            <h2>5 Jurusan Unggulan</h2>
            <p>SMK Labor Binaan FKIP UNRI Pekanbaru</p>
        </div>
        
        <div class="majors-grid">
            <div class="major-card">
                <img src="{{ asset('images/rpl_major.jpg') }}" alt="Rekayasa Perangkat Lunak" class="major-card-img">
                <h3>Rekayasa Perangkat Lunak</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/tkj_major.jpg') }}" alt="Teknik Komputer dan Jaringan" class="major-card-img">
                <h3>Teknik Komputer dan Jaringan</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/akuntansi_major.jpg') }}" alt="Akuntansi" class="major-card-img">
                <h3>Akuntansi</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/perkantoran_major.jpg') }}" alt="Manajemen Perkantoran" class="major-card-img">
                <h3>Manajemen Perkantoran</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/retail_major.jpg') }}" alt="Bisnis Retail" class="major-card-img">
                <h3>Bisnis Retail</h3>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} SI-PKL &bull; SMK Labor Binaan FKIP UNRI Pekanbaru. All rights reserved.
    </footer>
</body>
</html>
