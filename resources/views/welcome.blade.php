<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SI-PKL') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        :root {
            --bg-body: #f0f9ff;
            --bg-card: #ffffff;
            --border-color: #e0f2fe;
            --primary-blue: #0ea5e9;
            --primary-hover: #0284c7;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative Background Blob */
        .bg-blob {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(14,165,233,0.15) 0%, rgba(240,249,255,0) 70%);
            top: -200px;
            right: -200px;
            z-index: -1;
            border-radius: 50%;
        }
        
        .bg-blob-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(56,189,248,0.1) 0%, rgba(240,249,255,0) 70%);
            bottom: -100px;
            left: -100px;
            z-index: -1;
            border-radius: 50%;
        }

        /* Navbar */
        .navbar {
            padding: 20px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(240, 249, 255, 0.8);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(224, 242, 254, 0.5);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .logo-box {
            border-radius: 36px;
            width: 180px;
            height: 180px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 12px 40px rgba(2, 132, 199, 0.4);
            overflow: hidden;
            margin: 0 auto 32px auto; /* 32px spacing below the logo */
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .nav-actions {
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
        }

        .btn-outline:hover {
            background: rgba(14, 165, 233, 0.05);
        }

        .btn-primary {
            background: var(--primary-blue);
            color: white;
            border: 1px solid var(--primary-blue);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
        }

        /* Hero Section */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 80px 24px;
            max-width: 800px;
            margin: 0 auto;
        }

        .badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
            border: 1px solid #bae6fd;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--text-main);
            margin-bottom: 24px;
            letter-spacing: -1px;
        }

        .hero h1 span {
            color: var(--primary-blue);
        }

        .hero p {
            font-size: 1.125rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 600px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
        }
        
        .hero-actions .btn {
            padding: 14px 32px;
            font-size: 1rem;
        }



        /* Majors Section */
        .majors-section {
            padding: 60px 24px 80px 24px;
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
        }

        .majors-section h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .majors-section > p {
            color: var(--text-muted);
            margin-bottom: 48px;
            font-size: 1.125rem;
        }

        .majors-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
        }

        .major-card {
            background: var(--bg-card);
            border: 1px solid rgba(224, 242, 254, 0.8);
            border-radius: 20px;
            padding: 32px 24px;
            width: calc(33.333% - 16px);
            min-width: 200px;
            box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .major-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -5px rgba(14, 165, 233, 0.15);
            border-color: #bae6fd;
        }



        .major-card h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        footer {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
            font-size: 0.875rem;
            border-top: 1px solid rgba(224, 242, 254, 0.5);
        }

        @media (max-width: 768px) {
            .hero h1 .text-title { font-size: 2.5rem; }
            .hero h1 .text-subtitle { font-size: 1.75rem; }
            .hero-actions { flex-direction: column; }
            .navbar { padding: 16px 24px; }
            .major-card { width: calc(50% - 12px); }
        }
        @media (max-width: 480px) {
            .major-card { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="bg-blob"></div>
    <div class="bg-blob-2"></div>

    <nav class="navbar">
        <!-- Kosong untuk menyeimbangkan layout -->
        <div style="flex: 1;"></div>
        
        <div class="nav-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        Dasbor Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        Masuk
                    </a>
                @endauth
            @endif
        </div>
    </nav>

    <div class="hero">
        <div class="logo-box">
            <img src="{{ asset('images/logo-sipkl.jpg') }}" alt="Logo SI-PKL">
        </div>
        <div class="badge">SISTEM INFORMASI TERPADU</div>
        
        <h1 style="margin-bottom: 24px; line-height: 1.2;">
            <span style="font-size: 2.25rem; color: var(--text-main); font-weight: 700; display: inline-block; margin-bottom: 8px;">Manajemen Praktik Kerja Lapangan</span><br>
            <span style="font-size: 3.5rem; color: var(--primary-blue); font-weight: 800;">Lebih Mudah & Terstruktur</span>
        </h1>
        <p>Platform digital untuk mengelola, memantau, dan melaporkan kegiatan Praktik Kerja Lapangan siswa dengan pengalaman visual yang sejuk dan profesional.</p>
        
        <div class="hero-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        Buka Dasbor <i class="ph ph-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Mulai Sekarang <i class="ph ph-arrow-right"></i>
                    </a>
                @endauth
            @endif
        </div>
    </div>

    <div class="majors-section">
        <h2>5 Jurusan Unggulan 
            <br>SMK LABOR BINAAN FKIP UNRI PEKANBARU</h2>
        <p>Program Praktik Kerja Lapangan tersebar di berbagai bidang keahlian</p>
        <div class="majors-grid">
            <div class="major-card">
                <img src="{{ asset('images/rpl_major.jpg') }}" alt="Rekayasa Perangkat Lunak" style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">
                <h3>Rekayasa Perangkat Lunak</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/tkj_major.jpg') }}" alt="Teknik Komputer dan Jaringan" style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">
                <h3>Teknik Komputer dan Jaringan</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/akuntansi_major.jpg') }}" alt="Akuntansi" style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">
                <h3>Akuntansi</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/perkantoran_major.jpg') }}" alt="Manajemen Perkantoran" style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">
                <h3>Manajemen Perkantoran</h3>
            </div>
            <div class="major-card">
                <img src="{{ asset('images/retail_major.jpg') }}" alt="Bisnis Retail" style="width: 100%; height: 160px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">
                <h3>Bisnis Retail</h3>
            </div>
        </div>
    </div>

    <footer>
        &copy; {{ date('Y') }} SI-PKL. Dirancang untuk efisiensi dan kenyamanan mata.
    </footer>
</body>
</html>
