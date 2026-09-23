<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | SI-PKL SMK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 48px;
            max-width: 520px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 12px;
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
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
            transition: all 0.2s;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.6);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 72px; height: 72px; background: rgba(239, 68, 68, 0.15); color: #f87171;">
            <i class="ph-bold ph-shield-warning" style="font-size: 36px;"></i>
        </div>
        <div class="error-code">403</div>
        <h2 class="h4 fw-bold mb-2">Akses Tidak Diizinkan</h2>
        <p class="text-secondary mb-4" style="color: #94a3b8 !important; font-size: 0.95rem;">
            Maaf, akun Anda tidak memiliki hak akses atau izin untuk melihat halaman ini.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ url('/') }}" class="btn-home">
                <i class="ph-bold ph-house"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
