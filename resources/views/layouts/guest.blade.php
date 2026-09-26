<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SI-PKL') }} - Login</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons: Lucide & Phosphor -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- PWA Manifest & Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0284c7">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sipkl.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-sipkl.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-sipkl.jpg') }}">

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
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: -0.01em;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 24px;
        }

        .login-card {
            background: var(--bg-card);
            border-radius: 20px;
            box-shadow: 0 10px 40px -10px rgba(14, 165, 233, 0.15);
            border: 1px solid rgba(224, 242, 254, 0.5);
            padding: 40px 32px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .logo-box {
            border-radius: 50%;
            width: 135px;
            height: 135px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 12px 36px rgba(2, 132, 199, 0.32);
            overflow: hidden;
            border: 3.5px solid #e0f2fe;
            background: #ffffff;
            padding: 3px;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-main);
            margin-bottom: 8px;
            margin-top: 0;
        }

        .login-subtitle {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 32px;
            margin-top: 0;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            font-size: 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            outline: none;
            transition: all 0.2s;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-control::placeholder {
            color: #cbd5e1;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            accent-color: var(--primary-blue);
            cursor: pointer;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            user-select: none;
        }

        .btn-primary {
            width: 100%;
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .forgot-link {
            font-size: 0.85rem;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        
        .text-danger {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 6px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-container">
                <div class="logo-box">
                    <img src="{{ asset('images/logo-sipkl.jpg') }}" alt="Logo SI-PKL">
                </div>
            </div>
            
            <h1 class="login-title">Masuk ke SI-PKL</h1>
            <p class="login-subtitle">Silakan masukkan kredensial Anda</p>

            {{ $slot }}
        </div>
        
        <div style="text-align: center; margin-top: 24px; font-size: 0.75rem; color: var(--text-muted);">
            &copy; {{ date('Y') }} Sistem Informasi Praktik Kerja Lapangan.<br>Hak Cipta Dilindungi.
        </div>
    </div>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker registered:', reg.scope))
                    .catch(err => console.log('PWA Service Worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
