<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Sesi Kedaluwarsa | SI-PKL SMK</title>
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
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), 0 0 40px rgba(168, 85, 247, 0.15);
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
        .pulse-glow-purple {
            animation: pulseGlowPurple 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(-2deg); }
        }
        @keyframes pulseGlowPurple {
            0%, 100% { filter: drop-shadow(0 0 15px rgba(168, 85, 247, 0.4)); opacity: 0.95; }
            50% { filter: drop-shadow(0 0 30px rgba(192, 132, 252, 0.7)); opacity: 1; }
        }
        .error-code {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #c084fc 0%, #a855f7 50%, #7e22ce 100%);
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
        <!-- 419 Illustration: Digital Hourglass / Session Expired -->
        <div class="illustration-wrapper">
            <svg class="floating-anim pulse-glow-purple" width="160" height="140" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Clock / Hourglass Hologram Ring -->
                <circle cx="80" cy="70" r="54" stroke="rgba(192, 132, 252, 0.25)" stroke-width="2" stroke-dasharray="6 6"/>
                
                <!-- Hourglass Frame Top & Bottom -->
                <path d="M56 34 H104 L92 64 H68 Z" fill="url(#hg-top-grad)" stroke="#c084fc" stroke-width="2"/>
                <path d="M68 76 H92 L104 106 H56 Z" fill="url(#hg-bot-grad)" stroke="#c084fc" stroke-width="2"/>
                <line x1="50" y1="34" x2="110" y2="34" stroke="#e9d5ff" stroke-width="3" stroke-linecap="round"/>
                <line x1="50" y1="106" x2="110" y2="106" stroke="#e9d5ff" stroke-width="3" stroke-linecap="round"/>

                <!-- Falling Sand Stream -->
                <line x1="80" y1="64" x2="80" y2="88" stroke="#f0abfc" stroke-width="2" stroke-dasharray="3 3"/>
                <circle cx="80" cy="98" r="4" fill="#f0abfc" filter="drop-shadow(0 0 5px #f0abfc)"/>

                <!-- Sparks -->
                <circle cx="34" cy="50" r="3" fill="#c084fc" filter="drop-shadow(0 0 4px #c084fc)"/>
                <circle cx="128" cy="86" r="4" fill="#a855f7" filter="drop-shadow(0 0 6px #a855f7)"/>

                <defs>
                    <linearGradient id="hg-top-grad" x1="56" y1="34" x2="104" y2="64" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#9333ea"/>
                        <stop offset="100%" stop-color="#3b0764"/>
                    </linearGradient>
                    <linearGradient id="hg-bot-grad" x1="56" y1="76" x2="104" y2="106" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#3b0764"/>
                        <stop offset="100%" stop-color="#a855f7"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="error-code">419</div>
        <h1 class="h4 fw-bold mb-2">Sesi Telah Berakhir</h1>
        <p class="text-secondary mb-4" style="color: #94a3b8 !important; font-size: 0.95rem; line-height: 1.55;">
            Halaman ini telah kedaluwarsa karena tidak ada aktivitas dalam beberapa saat. Silakan muat ulang halaman atau login kembali.
        </p>
        
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <button onclick="location.reload()" class="btn-back">
                <i class="ph-bold ph-arrows-clockwise"></i> Muat Ulang
            </button>
            <a href="{{ url('/') }}" class="btn-home">
                <i class="ph-bold ph-house"></i> Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>
