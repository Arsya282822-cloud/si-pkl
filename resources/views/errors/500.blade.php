<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Kesalahan Server | SI-PKL SMK</title>
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
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), 0 0 40px rgba(244, 63, 94, 0.15);
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
        .pulse-glow-rose {
            animation: pulseGlowRose 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }
        @keyframes pulseGlowRose {
            0%, 100% { filter: drop-shadow(0 0 15px rgba(244, 63, 94, 0.4)); opacity: 0.95; }
            50% { filter: drop-shadow(0 0 30px rgba(225, 29, 72, 0.7)); opacity: 1; }
        }
        .error-code {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f43f5e 0%, #fb7185 50%, #fbcfe8 100%);
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
        <!-- 500 Illustration: Futuristic Server & Diagnostic Repair -->
        <div class="illustration-wrapper">
            <svg class="floating-anim pulse-glow-rose" width="160" height="140" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Server Racks Base -->
                <rect x="42" y="34" width="76" height="24" rx="6" fill="url(#server-grad-500)" stroke="rgba(244, 63, 94, 0.4)" stroke-width="1.5"/>
                <rect x="42" y="64" width="76" height="24" rx="6" fill="url(#server-grad-500)" stroke="rgba(244, 63, 94, 0.4)" stroke-width="1.5"/>
                <rect x="42" y="94" width="76" height="24" rx="6" fill="url(#server-grad-500)" stroke="rgba(244, 63, 94, 0.4)" stroke-width="1.5"/>
                
                <!-- Server LED status dots -->
                <circle cx="54" cy="46" r="3" fill="#22c55e" filter="drop-shadow(0 0 4px #22c55e)"/>
                <circle cx="64" cy="46" r="2.5" fill="#e2e8f0"/>
                <rect x="74" y="44" width="34" height="4" rx="2" fill="rgba(255, 255, 255, 0.2)"/>

                <circle cx="54" cy="76" r="3" fill="#f43f5e" filter="drop-shadow(0 0 6px #f43f5e)"/>
                <circle cx="64" cy="76" r="2.5" fill="#f43f5e"/>
                <rect x="74" y="74" width="24" height="4" rx="2" fill="rgba(244, 63, 94, 0.5)"/>

                <circle cx="54" cy="106" r="3" fill="#38bdf8" filter="drop-shadow(0 0 4px #38bdf8)"/>
                <circle cx="64" cy="106" r="2.5" fill="#e2e8f0"/>
                <rect x="74" y="104" width="34" height="4" rx="2" fill="rgba(255, 255, 255, 0.2)"/>

                <!-- Glowing Hazard Triangle / Alert Floating Sign -->
                <polygon points="122,22 138,50 106,50" fill="#f43f5e" stroke="#ffe4e6" stroke-width="1.5" filter="drop-shadow(0 0 10px rgba(244, 63, 94, 0.8))"/>
                <line x1="122" y1="31" x2="122" y2="41" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="122" cy="46" r="1.3" fill="#ffffff"/>

                <!-- Sparks & Diagnostics Signals -->
                <circle cx="28" cy="74" r="3" fill="#f43f5e" filter="drop-shadow(0 0 5px #f43f5e)"/>
                <circle cx="138" cy="98" r="4" fill="#fb7185" filter="drop-shadow(0 0 6px #fb7185)"/>
                <circle cx="34" cy="38" r="2" fill="#ffffff"/>

                <defs>
                    <linearGradient id="server-grad-500" x1="42" y1="34" x2="118" y2="58" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#334155"/>
                        <stop offset="100%" stop-color="#0f172a"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="error-code">500</div>
        <h1 class="h4 fw-bold mb-2">Terjadi Kesalahan Server</h1>
        <p class="text-secondary mb-4" style="color: #94a3b8 !important; font-size: 0.95rem; line-height: 1.55;">
            Sistem kami sedang mengalami kendala teknis sementara. Tim administrator akan segera melakukan penanganan.
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
