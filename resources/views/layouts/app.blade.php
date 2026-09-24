<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SI-PKL') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 for Grid -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons & Phosphor Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- PWA Manifest & Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0284c7">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SI-PKL">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-sipkl.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Premium Vibrant Dark Navy & Neon Cyan Theme */
        :root {
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            
            --sidebar-bg: #151d2a;
            --sidebar-gradient: linear-gradient(180deg, #182234 0%, #111827 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --sidebar-active-bg: linear-gradient(90deg, rgba(0, 242, 254, 0.14) 0%, rgba(14, 165, 233, 0.03) 100%);
            --sidebar-text: #94a3b8;
            --sidebar-active-text: #ffffff;
            --sidebar-sub-bg: #0d1420;
            --sidebar-cyan: #00f2fe;
            --sidebar-cyan-glow: rgba(0, 242, 254, 0.4);
            
            --primary-blue: #0284c7;
            --primary-hover: #0369a1;
            --primary-light: #f0f9ff;
            
            --text-main: #0f172a;
            --text-muted: #64748b;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.08);
            
            --sidebar-width: 255px;
            --sidebar-mini-width: 72px;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-main);
            background-color: var(--bg-body);
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
        }

        /* Clean Card Class */
        .pro-card {
            background: var(--bg-card);
            border-radius: 14px;
            box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Standardize Buttons */
        .btn {
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-sm {
            padding: 4px 12px;
            font-size: 0.8125rem;
            border-radius: 6px;
        }

        .pro-card:hover {
            box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.1);
        }

        #app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Dapodik Vibrant Sidebar Navigation */
        .pro-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-gradient);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.07);
            z-index: 100;
            transition: width 0.3s ease;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
        }

        .pro-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .pro-sidebar::-webkit-scrollbar-thumb {
            background: #2c384d;
            border-radius: 4px;
        }

        .sidebar-brand {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, rgba(24, 34, 52, 0.95) 0%, rgba(17, 24, 39, 0.95) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-brand img {
            border: 1px solid rgba(56, 189, 248, 0.3);
            box-shadow: 0 4px 14px rgba(0, 242, 254, 0.2);
        }

        .sidebar-nav {
            padding: 6px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            padding: 14px 20px 6px 20px;
            margin: 0;
        }

        .pro-nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            user-select: none;
            width: 100%;
            position: relative;
        }

        .pro-nav-link i.nav-icon {
            font-size: 20px;
            min-width: 28px;
            color: #94a3b8;
            transition: all 0.18s ease;
        }

        .pro-nav-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            padding-left: 22px;
        }
        .pro-nav-link:hover i.nav-icon {
            color: #38bdf8;
            transform: scale(1.08);
        }

        .pro-nav-link.active {
            background: var(--sidebar-active-bg);
            color: #ffffff;
            border-left-color: var(--sidebar-cyan);
            font-weight: 600;
            box-shadow: inset 4px 0 14px -3px var(--sidebar-cyan-glow);
        }
        .pro-nav-link.active i.nav-icon {
            color: var(--sidebar-cyan);
            filter: drop-shadow(0 0 6px var(--sidebar-cyan-glow));
        }

        .pro-nav-link[aria-expanded="true"] {
            background: rgba(255, 255, 255, 0.03);
            color: #ffffff;
        }
        .pro-nav-link[aria-expanded="true"] i.nav-icon {
            color: #38bdf8;
        }

        .nav-arrow {
            margin-left: auto;
            font-size: 13px;
            color: #64748b;
            transition: transform 0.22s ease, color 0.18s ease;
        }

        .pro-nav-link:hover .nav-arrow {
            color: #cbd5e1;
        }

        .pro-nav-link[aria-expanded="true"] .nav-arrow {
            transform: rotate(90deg);
            color: var(--sidebar-cyan);
        }

        .badge-new {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            color: #ffffff;
            font-size: 0.625rem;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 4px;
            margin-left: 6px;
            letter-spacing: 0.6px;
            box-shadow: 0 2px 8px rgba(244, 63, 94, 0.45);
        }

        .sub-nav {
            list-style: none;
            padding: 4px 0;
            margin: 0;
            background-color: var(--sidebar-sub-bg);
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .sub-nav-link {
            display: flex;
            align-items: center;
            padding: 9px 20px 9px 48px;
            color: #8192a8;
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 400;
            transition: all 0.18s ease;
            position: relative;
        }

        .sub-nav-link::before {
            content: "";
            position: absolute;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #475569;
            transition: all 0.18s ease;
        }

        .sub-nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.04);
            padding-left: 51px;
        }
        .sub-nav-link:hover::before {
            background-color: var(--sidebar-cyan);
            box-shadow: 0 0 6px var(--sidebar-cyan);
        }

        .sub-nav-link.active {
            color: #38bdf8;
            font-weight: 600;
            background: linear-gradient(90deg, rgba(0, 242, 254, 0.1) 0%, transparent 100%);
        }
        .sub-nav-link.active::before {
            background-color: var(--sidebar-cyan);
            box-shadow: 0 0 8px var(--sidebar-cyan);
        }

        .nav-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            font-size: 0.68rem;
            padding: 2px 7px;
            border-radius: 10px;
            margin-left: auto;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.4);
        }

        /* Main Content Area */
        .pro-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Top Header */
        .pro-header {
            height: 70px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px); /* Adding back subtle glass for coolness */
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-main);
        }

        /* User Menu */
        .user-dropdown-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 16px 6px 6px;
            border-radius: 30px;
            transition: all 0.2s;
        }
        
        .user-dropdown-btn:hover {
            background: #f1f5f9;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-main);
        }

        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            padding: 8px;
            margin-top: 10px !important;
        }
        
        .dropdown-item {
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-blue);
        }

        /* Content Wrapper */
        .pro-content {
            padding: 32px;
            flex: 1;
        }

        /* Toggle Button */
        .menu-toggle-btn {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .menu-toggle-btn:hover {
            background: #f1f5f9;
        }

        /* Mobile Backdrop Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 95;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media (min-width: 993px) {
            .pro-sidebar {
                transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .pro-sidebar.collapsed {
                width: var(--sidebar-mini-width) !important;
            }
            .pro-sidebar.collapsed .sidebar-brand {
                padding: 16px 0;
                justify-content: center;
                gap: 0;
            }
            .pro-sidebar.collapsed .sidebar-brand-text {
                display: none !important;
            }
            .pro-sidebar.collapsed .nav-label {
                display: none !important;
            }
            .pro-sidebar.collapsed .pro-nav-link {
                justify-content: center;
                padding: 12px 0;
                border-left: none;
                position: relative;
            }
            .pro-sidebar.collapsed .pro-nav-link span,
            .pro-sidebar.collapsed .pro-nav-link .nav-arrow,
            .pro-sidebar.collapsed .pro-nav-link .nav-badge,
            .pro-sidebar.collapsed .pro-nav-link .badge-new {
                display: none !important;
            }
            .pro-sidebar.collapsed .pro-nav-link i.nav-icon {
                min-width: unset;
                margin: 0;
                font-size: 22px;
            }
            .pro-sidebar.collapsed .sub-nav,
            .pro-sidebar.collapsed .collapse {
                display: none !important;
            }
            .pro-sidebar.collapsed .pro-nav-link.active {
                background: var(--sidebar-active-bg);
                border-left: none;
            }
            .pro-sidebar.collapsed .pro-nav-link.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 8px;
                bottom: 8px;
                width: 4px;
                background: var(--sidebar-cyan);
                border-radius: 0 4px 4px 0;
                box-shadow: 0 0 10px var(--sidebar-cyan);
            }
        }

        @media (max-width: 992px) {
            .pro-sidebar {
                position: fixed;
                left: -100%;
                width: var(--sidebar-width) !important;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 100;
            }
            .pro-sidebar.collapsed {
                width: var(--sidebar-width) !important;
            }
            .pro-sidebar.active {
                left: 0;
            }
            .pro-header {
                padding: 0 16px;
            }
            .pro-content {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <div id="app-wrapper">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Professional Sidebar -->
        <aside class="pro-sidebar" id="sidebar">
            <a href="{{ url('/') }}" class="sidebar-brand" title="SI-PKL SMK Labor Binaan FKIP UNRI">
                <img src="{{ asset('images/logo-sipkl.jpg') }}" alt="Logo SI-PKL" style="width: 38px; height: 38px; min-width: 38px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                <div class="sidebar-brand-text" style="line-height: 1.2; white-space: nowrap; overflow: hidden;">
                    <span style="font-weight: 700; font-size: 0.95rem; letter-spacing: 0.5px; color: #ffffff; display: block;">SI-PKL</span>
                    <small style="font-size: 0.68rem; color: #8c98b2; display: block;">SMK Labor Binaan FKIP UNRI</small>
                </div>
            </a>
            
            <div class="sidebar-nav">
                @include('layouts.navigation')
            </div>
        </aside>

        <!-- Main Content -->
        <main class="pro-main">
            <!-- Header -->
            <header class="pro-header">
                <div class="d-flex align-items-center gap-3">
                    <button class="menu-toggle-btn" id="sidebarToggle">
                        <i class="ph ph-list" style="font-size: 24px;"></i>
                    </button>
                    <h1 class="header-title">@yield('title', strip_tags($header ?? 'Dashboard'))</h1>
                </div>
                
                <div>
                    <div class="dropdown">
                        <button class="user-dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="user-name d-none d-md-flex align-items-center gap-1">
                                {{ Auth::user()->name ?? 'User' }}
                                <i class="ph ph-caret-down" style="font-size: 14px;"></i>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header text-muted" style="font-size: 0.75rem;">Masuk sebagai {{ ucfirst(Auth::user()->role?->nama_role ?? 'User') }}</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="ph ph-user-circle" style="font-size: 18px;"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('bantuan.index') }}">
                                    <i class="ph ph-question" style="font-size: 18px;"></i> Pusat Bantuan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" style="background: none; border: none; width: 100%; text-align: left;">
                                        <i class="ph ph-sign-out" style="font-size: 18px;"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="pro-content animate-fade-in">
                <!-- Floating Toast Notification System -->
                <div class="position-fixed top-0 end-0 p-4" style="z-index: 9999; max-width: 420px; width: 100%; pointer-events: none;">
                    @if (session('success'))
                        <div class="alert glass-card border-0 shadow-lg d-flex align-items-center gap-3 p-3 mb-3 text-dark animate-slide-in" role="alert" style="pointer-events: auto; border-radius: 12px; border-left: 5px solid #10b981 !important;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white flex-shrink-0" style="width: 38px; height: 38px; box-shadow: 0 4px 10px rgba(16,185,129,0.3);">
                                <i class="ph-bold ph-check" style="font-size: 20px;"></i>
                            </div>
                            <div class="flex-grow-1" style="font-size: 0.875rem;">
                                <div class="fw-bold text-success">Berhasil!</div>
                                <div class="text-secondary">{{ session('success') }}</div>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem;"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert glass-card border-0 shadow-lg d-flex align-items-center gap-3 p-3 mb-3 text-dark animate-slide-in" role="alert" style="pointer-events: auto; border-radius: 12px; border-left: 5px solid #ef4444 !important;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-danger text-white flex-shrink-0" style="width: 38px; height: 38px; box-shadow: 0 4px 10px rgba(239,68,68,0.3);">
                                <i class="ph-bold ph-warning-circle" style="font-size: 20px;"></i>
                            </div>
                            <div class="flex-grow-1" style="font-size: 0.875rem;">
                                <div class="fw-bold text-danger">Terjadi Kesalahan!</div>
                                <div class="text-secondary">{{ session('error') }}</div>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem;"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert glass-card border-0 shadow-lg d-flex align-items-center gap-3 p-3 mb-3 text-dark animate-slide-in" role="alert" style="pointer-events: auto; border-radius: 12px; border-left: 5px solid #0ea5e9 !important;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-info text-white flex-shrink-0" style="width: 38px; height: 38px; box-shadow: 0 4px 10px rgba(14,165,233,0.3);">
                                <i class="ph-bold ph-info" style="font-size: 20px;"></i>
                            </div>
                            <div class="flex-grow-1" style="font-size: 0.875rem;">
                                <div class="fw-bold text-primary">Informasi</div>
                                <div class="text-secondary">{{ session('info') }}</div>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem;"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert glass-card border-0 shadow-lg d-flex align-items-start gap-3 p-3 mb-3 text-dark animate-slide-in" role="alert" style="pointer-events: auto; border-radius: 12px; border-left: 5px solid #f59e0b !important;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-white flex-shrink-0" style="width: 38px; height: 38px; box-shadow: 0 4px 10px rgba(245,158,11,0.3);">
                                <i class="ph-bold ph-warning" style="font-size: 20px;"></i>
                            </div>
                            <div class="flex-grow-1" style="font-size: 0.85rem;">
                                <div class="fw-bold text-warning mb-1">Perhatian (Validasi Gagal)</div>
                                <ul class="mb-0 ps-3 text-secondary">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem;"></button>
                        </div>
                    @endif
                </div>

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Handler
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Cek state dari localStorage (Desktop)
        if (localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 992) {
            if (sidebar) sidebar.classList.add('collapsed');
        }

        function toggleMobileSidebar(show) {
            if (!sidebar) return;
            if (show) {
                sidebar.classList.add('active');
                if (sidebarOverlay) sidebarOverlay.classList.add('active');
            } else {
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            }
        }

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (window.innerWidth <= 992) {
                    const isOpen = sidebar.classList.contains('active');
                    toggleMobileSidebar(!isOpen);
                } else {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
                }
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                toggleMobileSidebar(false);
            });
        }
        
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && sidebar && sidebar.classList.contains('active')) {
                if (!sidebar.contains(e.target) && sidebarToggle && !sidebarToggle.contains(e.target)) {
                    toggleMobileSidebar(false);
                }
            }
        });

        // Populate titles on nav links for tooltips in mini-sidebar mode
        document.querySelectorAll('.pro-nav-link').forEach(link => {
            if (!link.getAttribute('title')) {
                const textSpan = link.querySelector('span');
                if (textSpan) {
                    link.setAttribute('title', textSpan.textContent.trim());
                }
            }
        });

        // Sub-menu Collapse Behavior Enhancement
        document.querySelectorAll('.pro-nav-link[data-bs-toggle="collapse"]').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', function(e) {
                // Prevent page scroll jumps on hash href
                if (this.getAttribute('href')?.startsWith('#')) {
                    e.preventDefault();
                }
            });
        });

        // Auto dismiss toast alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) bsAlert.close();
                }
            });
        }, 5000);

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker registered:', reg.scope))
                    .catch(err => console.log('PWA Service Worker registration failed:', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
