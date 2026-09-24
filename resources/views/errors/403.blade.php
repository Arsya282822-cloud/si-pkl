<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | SI-PKL SMK</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sipkl.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-sipkl.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: radial-gradient(circle at 50% 20%, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            margin: 0;
            overflow-x: hidden;
        }
        .error-card {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), 0 0 40px rgba(239, 68, 68, 0.15);
            position: relative;
            z-index: 1;
        }
        .illustration-wrapper {
            position: relative;
            width: 180px;
            height: 140px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .floating-anim {
            animation: float 4s ease-in-out infinite;
        }
        .pulse-glow-amber {
            animation: pulseGlowAmber 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(-2deg); }
        }
        @keyframes pulseGlowAmber {
            0%, 100% { filter: drop-shadow(0 0 15px rgba(245, 158, 11, 0.4)); opacity: 0.95; }
            50% { filter: drop-shadow(0 0 30px rgba(239, 68, 68, 0.7)); opacity: 1; }
        }
        .error-code {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 50%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -0.04em;
        }
        .btn-home {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 28px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
            transition: all 0.25s ease;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(14, 165, 233, 0.65);
            color: #ffffff;
        }
        .btn-back {
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.25s ease;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <!-- 403 Illustration: Cyber Security Shield Lock -->
        <div class="illustration-wrapper">
            <svg class="floating-anim pulse-glow-amber" width="160" height="140" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Glow Barrier -->
                <circle cx="80" cy="70" r="56" stroke="rgba(245, 158, 11, 0.2)" stroke-width="2" stroke-dasharray="8 6"/>
                
                <!-- Shield Base Outer -->
                <path d="M80 26 L120 42 C120 82 80 114 80 114 C80 114 40 82 40 42 L80 26 Z" fill="url(#shield-grad-403)" stroke="rgba(245, 158, 11, 0.6)" stroke-width="2"/>
                
                <!-- Inner Shield Inset -->
                <path d="M80 34 L112 47 C112 79 80 104 80 104 C80 104 48 79 48 47 L80 34 Z" fill="rgba(15, 23, 42, 0.65)" stroke="rgba(239, 68, 68, 0.4)" stroke-width="1.5"/>

                <!-- Padlock Center Hologram -->
                <rect x="67" y="64" width="26" height="22" rx="5" fill="#f59e0b" filter="drop-shadow(0 0 8px #f59e0b)"/>
                <path d="M72 64 V56 C72 51.5 75.5 48 80 48 C84.5 48 88 51.5 88 56 V64" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/>
                
                <!-- Keyhole -->
                <circle cx="80" cy="73" r="2.5" fill="#0f172a"/>
                <path d="M78.5 73 L81.5 73 L82 80 L78 80 Z" fill="#0f172a"/>

                <!-- Laser Sparks -->
                <circle cx="34" cy="46" r="3" fill="#ef4444" filter="drop-shadow(0 0 4px #ef4444)"/>
                <circle cx="126" cy="94" r="4" fill="#f59e0b" filter="drop-shadow(0 0 6px #f59e0b)"/>
                <circle cx="128" cy="40" r="2" fill="#ffffff"/>

                <defs>
                    <linearGradient id="shield-grad-403" x1="40" y1="26" x2="120" y2="114" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#b45309"/>
                        <stop offset="50%" stop-color="#7f1d1d"/>
                        <stop offset="100%" stop-color="#1e1b4b"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="error-code">403</div>
        <h1 class="h4 fw-bold mb-2">Akses Tidak Diizinkan</h1>
        <p class="text-secondary mb-4" style="color: #94a3b8 !important; font-size: 0.95rem; line-height: 1.55;">
            Maaf, akun atau hak akses Anda tidak mencukupi untuk membuka halaman atau fitur ini.
        </p>
        
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <button onclick="window.history.back()" class="btn-back">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
            <a href="{{ url('/') }}" class="btn-home">
                <i class="ph-bold ph-house"></i> Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>
