<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - GURU PINTAR Deep Learning AI</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- Instant Theme Detection before paint -->
    <script>
        (function() {
            const saved = localStorage.getItem('guru_pintar_theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <!-- Dual Theme Design System (Light & Dark) -->
    <style>
        /* ========================================================= */
        /* LIGHT THEME (Biru Langit & Putih Bersih)                  */
        /* ========================================================= */
        :root, [data-theme="light"] {
            --bg-body: #f4f9fd;
            --bg-body-gradient: radial-gradient(at 0% 0%, rgba(186, 230, 253, 0.55) 0px, transparent 50%),
                                radial-gradient(at 100% 0%, rgba(224, 242, 254, 0.7) 0px, transparent 50%),
                                radial-gradient(at 50% 100%, rgba(186, 230, 253, 0.4) 0px, transparent 50%),
                                #f4f9fd;
            --bg-sidebar: rgba(255, 255, 255, 0.95);
            --bg-card: #ffffff;
            --bg-card-hover: #f8fbff;
            --bg-input: #ffffff;
            --bg-topbar: rgba(255, 255, 255, 0.88);
            
            --border-color: #e0eefb;
            --border-subtle: #f1f5f9;
            --border-focus: #0ea5e9;
            
            --text-main: #0f172a;       /* Deep Slate/Navy */
            --text-muted: #334155;      /* Slate-700 */
            --text-dim: #64748b;        /* Slate-500 */
            --title-color: #0369a1;     /* Biru Langit Elegan */
            
            --primary: #0284c7;         /* Sky 600 */
            --primary-hover: #0369a1;   /* Sky 700 */
            --primary-light: #e0f2fe;
            --primary-gradient: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            
            --accent-yellow: #f59e0b;
            --accent-purple: #8b5cf6;
            --accent-green: #10b981;
            --accent-rose: #f43f5e;
            --accent-cyan: #06b6d4;
            --accent-sky: #38bdf8;
            
            --shadow-card: 0 4px 20px -2px rgba(2, 132, 199, 0.07), 0 2px 6px -1px rgba(0, 0, 0, 0.04);
            --shadow-card-hover: 0 10px 25px -5px rgba(2, 132, 199, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            --shadow-glow-blue: 0 0 20px rgba(14, 165, 233, 0.25);
            
            --table-th-bg: #f0f7ff;
            --table-th-color: #0369a1;
            --table-border: #f1f5f9;
            --table-hover: #f8fbff;
            
            --badge-bg: #e0f2fe;
            --badge-color: #0284c7;
            --badge-border: #bae6fd;
            
            --modal-bg: #ffffff;
            --modal-border: rgba(186, 230, 253, 0.9);
            --modal-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.25);
        }

        /* ========================================================= */
        /* DARK THEME (Sleek Deep Navy & Cyber Blue)                 */
        /* ========================================================= */
        [data-theme="dark"] {
            --bg-body: #080e1e;
            --bg-body-gradient: radial-gradient(at 0% 0%, rgba(30, 58, 138, 0.35) 0px, transparent 50%),
                                radial-gradient(at 100% 0%, rgba(15, 23, 42, 0.85) 0px, transparent 50%),
                                radial-gradient(at 50% 100%, rgba(14, 116, 144, 0.2) 0px, transparent 50%),
                                #080e1e;
            --bg-sidebar: #0b1328;
            --bg-card: #121d38;
            --bg-card-hover: #182649;
            --bg-input: #0e172e;
            --bg-topbar: rgba(11, 19, 40, 0.88);
            
            --border-color: #1e2c4f;
            --border-subtle: #182544;
            --border-focus: #3a86ff;
            
            --text-main: #f8fafc;       /* Crisp White */
            --text-muted: #94a3b8;      /* Slate-400 */
            --text-dim: #64748b;        /* Slate-500 */
            --title-color: #38bdf8;     /* Sky Blue Neon yang Menyala & Kontras */
            
            --primary: #3a86ff;
            --primary-hover: #2563eb;
            --primary-light: rgba(58, 134, 255, 0.15);
            --primary-gradient: linear-gradient(135deg, #3a86ff 0%, #8b5cf6 100%);
            
            --accent-yellow: #f59e0b;
            --accent-purple: #8b5cf6;
            --accent-green: #10b981;
            --accent-rose: #f43f5e;
            --accent-cyan: #06b6d4;
            --accent-sky: #38bdf8;
            
            --shadow-card: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            --shadow-card-hover: 0 14px 28px -5px rgba(0, 0, 0, 0.5), 0 10px 12px -6px rgba(0, 0, 0, 0.4);
            --shadow-glow-blue: 0 0 20px rgba(58, 134, 255, 0.3);
            
            --table-th-bg: rgba(14, 23, 46, 0.85);
            --table-th-color: #38bdf8;
            --table-border: rgba(30, 44, 79, 0.6);
            --table-hover: rgba(255, 255, 255, 0.03);
            
            --badge-bg: rgba(58, 134, 255, 0.15);
            --badge-color: #60a5fa;
            --badge-border: rgba(58, 134, 255, 0.3);
            
            --modal-bg: #121d38;
            --modal-border: rgba(58, 134, 255, 0.35);
            --modal-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        :root {
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --sidebar-width: 260px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
        }

        body {
            font-family: var(--font-family);
            background: var(--bg-body-gradient);
            background-color: var(--bg-body);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Titles */
        h1, h2, h3, h4, h5, h6,
        .page-title, .card-title, .section-title, .modal-title,
        .brand-text h1, .content-area h1, .content-area h2, .content-area h3,
        .content-area h4, .content-area h5, .content-area h6 {
            color: var(--title-color) !important;
        }

        .content-area h1 + p, .content-area h2 + p, .content-area h3 + p {
            color: var(--text-muted) !important;
            font-weight: 500;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            backdrop-filter: blur(16px);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            overflow-y: auto;
            box-shadow: 2px 0 16px rgba(0, 0, 0, 0.04);
        }

        .sidebar-brand {
            padding: 22px 20px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-sidebar);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--primary-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: var(--shadow-glow-blue);
            flex-shrink: 0;
        }

        .brand-text h1 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .brand-text span {
            font-size: 10px;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .sidebar-menu {
            padding: 16px 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu-category {
            font-size: 10.5px;
            font-weight: 800;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 1.1px;
            padding: 14px 10px 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
        }

        .nav-link:hover {
            color: var(--primary);
            background-color: var(--primary-light);
            transform: translateX(3px);
        }

        .nav-link.active {
            background: var(--primary-gradient);
            color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        }

        .nav-link.active svg {
            color: #ffffff;
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-card-hover);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--primary-gradient);
            border: 2px solid var(--badge-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            overflow: hidden;
        }

        .user-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: var(--text-dim);
        }

        /* Main Wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: transparent;
        }

        .top-navbar {
            height: 70px;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--bg-topbar);
            backdrop-filter: blur(16px);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.04);
        }

        .search-box {
            position: relative;
            width: 320px;
        }

        .search-box input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 13px;
            outline: none;
        }

        .search-box input:focus {
            border-color: var(--border-focus);
            background-color: var(--bg-card);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.18);
        }

        .search-box svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-dim);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .theme-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            color: var(--text-main);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            outline: none;
        }

        .theme-toggle-btn:hover {
            border-color: var(--primary);
            background-color: var(--bg-card-hover);
            transform: translateY(-1px);
        }

        .badge-academic {
            background: var(--badge-bg);
            color: var(--badge-color);
            border: 1px solid var(--badge-border);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .content-area {
            padding: 28px;
            flex: 1;
        }

        /* Generic Cards */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 22px;
            box-shadow: var(--shadow-card);
        }

        .card:hover {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-card-hover);
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 22px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #10b981;
        }

        .alert-error {
            background-color: rgba(244, 63, 94, 0.15);
            border: 1px solid rgba(244, 63, 94, 0.35);
            color: #f43f5e;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.32);
        }

        .btn-primary:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn-yellow {
            background-color: #f59e0b;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
        }

        .btn-yellow:hover {
            background-color: #d97706;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* Form */
        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 7px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 10px 14px;
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 13.5px;
            outline: none;
            font-family: inherit;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--border-focus);
            background-color: var(--bg-card);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background: var(--bg-card);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        .custom-table th {
            background-color: var(--table-th-bg);
            color: var(--table-th-color);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.6px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--table-border);
            color: var(--text-main);
        }

        .custom-table tr:hover td {
            background-color: var(--table-hover);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-body);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Pagination */
        nav[role="navigation"] {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 6px !important;
        }

        nav[role="navigation"] .sm\:hidden { display: none !important; }
        nav[role="navigation"] .hidden { display: flex !important; align-items: center !important; gap: 10px !important; }
        nav[role="navigation"] .hidden > div:first-child { display: none !important; }

        nav[role="navigation"] a,
        nav[role="navigation"] span {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 34px !important;
            height: 34px !important;
            padding: 0 8px !important;
            border-radius: var(--radius-sm) !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: var(--text-muted) !important;
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            text-decoration: none !important;
        }

        nav[role="navigation"] a:hover {
            color: var(--primary) !important;
            border-color: var(--primary) !important;
            background: var(--primary-light) !important;
        }

        nav[role="navigation"] span[aria-current="page"] {
            background: var(--primary-gradient) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
        }

        /* Modals */
        #deleteAllModal > div, #importModal > div {
            background: var(--modal-bg) !important;
            border: 1px solid var(--modal-border) !important;
            box-shadow: var(--modal-shadow) !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                    <path d="M6 6h10"/>
                    <path d="M6 10h10"/>
                    <path d="m14 16 2 2 4-4"/>
                </svg>
            </div>
            <div class="brand-text">
                <h1>GURU PINTAR</h1>
                <span>Deep Learning AI</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-category">MENU UTAMA</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Kelola Siswa</span>
            </a>
            <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Kelola Kelas</span>
            </a>
            <a href="{{ route('cards.index') }}" class="nav-link {{ request()->routeIs('cards.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><rect width="5" height="5" x="7" y="7"/><rect width="5" height="5" x="7" y="13"/><rect width="5" height="5" x="13" y="7"/></svg>
                <span>Cetak Kartu QR</span>
            </a>
            <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span>Kelola Mapel</span>
            </a>

            <div class="menu-category">AKADEMIK</div>
            <a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Jadwal Mengajar</span>
            </a>
            <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <span>Input Absensi</span>
            </a>
            <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Input Penilaian</span>
            </a>
            <a href="{{ route('journals.index') }}" class="nav-link {{ request()->routeIs('journals.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <span>Agenda Mengajar</span>
            </a>
            <a href="{{ route('guidance.index') }}" class="nav-link {{ request()->routeIs('guidance.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                <span>Bimbingan Guru Wali</span>
            </a>
            <a href="{{ route('modules-ai.index') }}" class="nav-link {{ request()->routeIs('modules-ai.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                <span>Download Perangkat Ajar</span>
            </a>

            <div class="menu-category">FITUR AI & LAPORAN</div>
            <a href="{{ route('chatbot.index') }}" class="nav-link {{ request()->routeIs('chatbot.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                <span>Asisten Chatbot AI</span>
            </a>
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                <span>Pusat Laporan PDF</span>
            </a>
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span>Pengaturan & Profil</span>
            </a>
        </nav>

        @php
            $teacherName = $appSetting->teacher_name ?? 'Budi Santoso, S.Kom';
            $words = preg_split('/\s+/', trim($teacherName));
            $initials = '';
            foreach (array_slice($words, 0, 2) as $w) {
                $cleaned = preg_replace('/[^A-Za-z]/', '', $w);
                if (!empty($cleaned)) {
                    $initials .= strtoupper(substr($cleaned, 0, 1));
                }
            }
            $initials = $initials ?: 'BS';
        @endphp

        <div class="sidebar-footer">
            <a href="{{ route('settings.index') }}" class="user-card" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px; padding: 4px 6px; border-radius: var(--radius-md);" title="Buka Pengaturan Profil Guru">
                <div class="user-avatar">{{ $initials }}</div>
                <div class="user-info" style="flex: 1; min-width: 0;">
                    <div class="user-name" title="{{ $teacherName }}">{{ $teacherName }}</div>
                    <div class="user-role">Guru {{ $appSetting->teacher_subject ?? 'Informatika' }} & Wali</div>
                </div>
            </a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" placeholder="Cari siswa, kelas, jadwal, atau modul...">
            </div>

            <div class="nav-actions">
                <button type="button" id="themeToggleBtn" onclick="toggleGuruTheme()" class="theme-toggle-btn" title="Ganti Mode Gelap / Terang">
                    <span id="themeIconSun" style="display: none; align-items: center; justify-content: center;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    </span>
                    <span id="themeIconMoon" style="display: inline-flex; align-items: center; justify-content: center;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </span>
                    <span id="themeToggleText">Light Mode</span>
                </button>
                <div class="badge-academic">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    <span>{{ $appSetting->active_semester ?? 'Ganjil' }} {{ $appSetting->academic_year ?? '2025/2026' }}</span>
                </div>
                <a href="{{ route('attendance.scanner') }}" class="btn btn-sm btn-outline" style="gap: 6px;">Scan QR</a>
                <a href="{{ route('modules-ai.create') }}" class="btn btn-sm btn-yellow" style="gap: 6px;">Modul AI</a>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success"><span>{{ session('success') }}</span></div>
            @endif
            @if(session('error'))
                <div class="alert alert-error"><span>{{ session('error') }}</span></div>
            @endif
            @yield('content')
        </main>
    </div>

    <script>
        function updateThemeUI(theme) {
            const isDark = theme === 'dark';
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('guru_pintar_theme', theme);
            const sun = document.getElementById('themeIconSun');
            const moon = document.getElementById('themeIconMoon');
            const text = document.getElementById('themeToggleText');
            if (sun && moon && text) {
                if (isDark) {
                    sun.style.display = 'inline-flex';
                    moon.style.display = 'none';
                    text.innerText = 'Dark Mode';
                } else {
                    sun.style.display = 'none';
                    moon.style.display = 'inline-flex';
                    text.innerText = 'Light Mode';
                }
            }
        }
        function toggleGuruTheme() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeUI(current === 'dark' ? 'light' : 'dark');
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateThemeUI(localStorage.getItem('guru_pintar_theme') || 'light');
        });
    </script>
    @stack('scripts')
</body>
</html>
