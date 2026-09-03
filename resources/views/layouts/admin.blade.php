<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ShortLink Admin Panel">
    <title>@yield('title', 'Admin - ShortLink')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ─────────────────────────────────
           BASE
        ───────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
            margin: 0;
            color: #334155;
        }

        /* ─────────────────────────────────
           LAYOUT SHELL
        ───────────────────────────────── */
        .admin-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* ─────────────────────────────────
           SIDEBAR
        ───────────────────────────────── */
        .admin-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        /* Brand */
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding-right: 0.75rem;
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1.25rem 1.25rem;
            text-decoration: none;
            flex-grow: 1;
            min-width: 0;
        }
        .sidebar-close-btn {
            background: none;
            border: none;
            color: rgba(255,255,255,0.6);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.18s;
        }
        .sidebar-close-btn:hover {
            color: #fff;
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(220,38,38,0.4);
            flex-shrink: 0;
        }
        .sidebar-brand-text {
            flex: 1;
            min-width: 0;
        }
        .sidebar-brand-name {
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.3px;
        }
        .sidebar-brand-role {
            font-size: 0.65rem;
            font-weight: 600;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: rgba(220,38,38,0.15);
            padding: 0.1rem 0.45rem;
            border-radius: 4px;
            display: inline-block;
            margin-top: 2px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0.75rem;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .sidebar-nav-label {
            font-size: 0.62rem;
            font-weight: 700;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 0.75rem;
            margin: 0.75rem 0 0.4rem;
        }
        .sidebar-nav-label:first-child { margin-top: 0; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 11px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.18s;
            margin-bottom: 2px;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(220,38,38,0.35);
        }
        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .sidebar-link .nav-badge {
            margin-left: auto;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 20px;
        }
        .sidebar-link.active .nav-badge {
            background: rgba(255,255,255,0.25);
        }

        /* User footer */
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.18s;
            text-decoration: none;
        }
        .sidebar-user:hover { background: rgba(255,255,255,0.08); }
        .sidebar-user-avatar {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .sidebar-user-role {
            font-size: 0.68rem;
            color: rgba(255,255,255,0.45);
        }
        .sidebar-user-icon {
            margin-left: auto;
            color: rgba(255,255,255,0.3);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* ─────────────────────────────────
           MAIN CONTENT
        ───────────────────────────────── */
        .admin-main {
            flex: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            width: 100%;
        }

        /* Top bar */
        .admin-topbar {
            height: 62px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            width: 100%;
            min-width: 0;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }
        .topbar-page-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .topbar-breadcrumb {
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }
        .topbar-user-chip {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.4rem 0.85rem 0.4rem 0.5rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s;
            color: #334155;
        }
        .topbar-user-chip:hover { background: #f0f0ff; border-color: #c7d2fe; color: #6366f1; }
        .topbar-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .topbar-name { font-size: 0.82rem; font-weight: 600; }

        .topbar-view-site {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.42rem 0.9rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s;
        }
        .topbar-view-site:hover { border-color: #6366f1; color: #6366f1; background: #f0f0ff; }

        .topbar-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.42rem 0.9rem;
            border-radius: 8px;
            border: 1.5px solid #fecaca;
            background: #fff5f5;
            color: #dc2626;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
        }
        .topbar-logout-btn:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

        /* Sidebar toggle (mobile) */
        .sidebar-toggle-btn {
            display: none;
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #334155;
            font-size: 1rem;
            align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
            flex-shrink: 0;
        }

        /* Content area */
        .admin-content {
            flex: 1;
            padding: 1.75rem;
            min-width: 0;
            width: 100%;
        }

        /* Alerts */
        .admin-alert {
            border-radius: 12px;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
        }
        .admin-alert-success { background: #ecfdf5; color: #065f46; }
        .admin-alert-error   { background: #fef2f2; color: #991b1b; }

        /* ─────────────────────────────────
           SHARED ADMIN COMPONENTS & CARDS
        ───────────────────────────────── */
        .admin-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .admin-header-icon {
            width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; box-shadow: 0 6px 16px rgba(220,38,38,0.35);
        }
        .admin-back-btn {
            width: 42px; height: 42px; border-radius: 12px;
            background: #fff; border: 1.5px solid #e2e8f0;
            color: #334155; display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.15rem; text-decoration: none; transition: all 0.18s; flex-shrink: 0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .admin-back-btn:hover {
            background: #dc2626; color: #fff; border-color: #dc2626; transform: translateX(-2px);
        }
        .admin-page-title {
            font-size: 1.65rem; font-weight: 800; color: #1e293b; letter-spacing: -0.4px; margin: 0 0 0.2rem;
        }
        .admin-page-subtitle {
            font-size: 0.85rem; color: #64748b; margin: 0;
        }

        /* Card */
        .admin-card {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .admin-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(to right, #fff5f5, #fff);
            flex-wrap: wrap;
            gap: 0.65rem;
        }
        .admin-card-icon, .admin-section-icon {
            width: 38px; height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1rem;
            box-shadow: 0 3px 10px rgba(220,38,38,0.3);
            flex-shrink: 0;
        }
        .admin-card-title {
            font-size: 0.96rem; font-weight: 700; color: #1e293b; margin: 0;
        }
        .admin-card-sub {
            font-size: 0.74rem; color: #94a3b8; margin: 0;
        }
        .admin-card-body {
            padding: 1.5rem;
        }
        .admin-card-body-full, .admin-card-body-zero {
            padding: 0;
        }

        /* Badges */
        .admin-role-badge {
            display: inline-flex; align-items: center;
            padding: 0.22rem 0.7rem; border-radius: 20px;
            font-size: 0.7rem; font-weight: 700; letter-spacing: 0.3px;
        }
        .role-admin { background: #fee2e2; color: #991b1b; }
        .role-user  { background: #eff6ff; color: #1d4ed8; }

        .admin-status-badge {
            display: inline-flex; align-items: center;
            padding: 0.25rem 0.75rem; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px;
        }
        .status-active   { background: #d1fae5; color: #065f46; }
        .status-expired  { background: #fee2e2; color: #991b1b; }
        .status-inactive { background: #fef3c7; color: #92400e; }

        .admin-cat-badge {
            display: inline-flex; align-items: center;
            padding: 0.22rem 0.65rem; border-radius: 20px;
            font-size: 0.72rem; font-weight: 600; color: #fff;
        }
        .admin-click-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.65rem; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #64748b;
        }

        .admin-user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff; font-size: 0.8rem; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .admin-user-name  { font-size: 0.875rem; font-weight: 600; color: #1e293b; }
        .admin-user-email { font-size: 0.75rem; color: #94a3b8; }

        /* Action Buttons */
        .admin-action-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 9px;
            font-size: 0.85rem; border: 1.5px solid; cursor: pointer;
            transition: all 0.18s; text-decoration: none;
        }
        .btn-admin-info    { border-color: #bfdbfe; color: #2563eb; background: #eff6ff; }
        .btn-admin-info:hover { background: #2563eb; color: #fff; border-color: #2563eb; }
        .btn-admin-warn    { border-color: #fde68a; color: #d97706; background: #fffbeb; }
        .btn-admin-warn:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
        .btn-admin-success { border-color: #a7f3d0; color: #059669; background: #ecfdf5; }
        .btn-admin-success:hover { background: #10b981; color: #fff; border-color: #10b981; }
        .btn-admin-danger  { border-color: #fecaca; color: #dc2626; background: #fff1f2; }
        .btn-admin-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

        /* Mobile Card */
        .admin-mobile-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            transition: transform 0.15s, box-shadow 0.15s;
        }

        /* Empty state */
        .admin-empty { text-align: center; padding: 3rem 1rem; }
        .admin-empty-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: #fff5f5; color: #fca5a5; font-size: 2rem;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
        }
        .admin-empty-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.4rem; }
        .admin-empty-msg   { font-size: 0.83rem; color: #94a3b8; }

        /* Table */
        .admin-table thead th {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: #94a3b8;
            border-bottom: 1.5px solid #f1f5f9;
            background: #fafbfc; padding: 0.85rem 1rem;
        }
        .admin-table thead th:first-child { padding-left: 1.5rem; }
        .admin-table thead th:last-child  { padding-right: 1.5rem; }
        .admin-table tbody td {
            font-size: 0.875rem; color: #334155;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }
        .admin-table tbody td:first-child { padding-left: 1.5rem; }
        .admin-table tbody td:last-child  { padding-right: 1.5rem; }
        .admin-table tbody tr:hover td { background: #fafbff; }
        .admin-table tbody tr:last-child td { border-bottom: none; }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        /* ─────────────────────────────────
           RESPONSIVE
        ───────────────────────────────── */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.show {
                display: block;
            }
            .admin-main {
                margin-left: 0;
                width: 100%;
                min-width: 0;
            }
            .sidebar-toggle-btn {
                display: inline-flex;
            }
            .admin-content {
                padding: 1.25rem;
            }
        }
        @media (max-width: 575.98px) {
            .admin-content { padding: 1rem 0.75rem; }
            .admin-topbar { padding: 0 0.85rem; }
            .topbar-view-site { display: none; }
            .topbar-breadcrumb { display: none; }
            .topbar-page-title { font-size: 0.82rem; }
            .topbar-name { display: none; }
            .topbar-user-chip { padding: 0.25rem; border-radius: 50%; }
            .topbar-user-chip i { display: none; }
            .topbar-logout-btn span { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="admin-shell">

    {{-- ── Sidebar ── --}}
    <aside class="admin-sidebar" id="adminSidebar">
        {{-- Brand --}}
        <div class="sidebar-header">
            <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-link-45deg"></i>
                </div>
                <div class="sidebar-brand-text">
                    <div class="sidebar-brand-name">ShortLink</div>
                    <span class="sidebar-brand-role">Admin Panel</span>
                </div>
            </a>
            <button class="sidebar-close-btn d-lg-none" id="sidebarClose" title="Tutup Menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.short-links.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.short-links.*') ? 'active' : '' }}">
                <i class="bi bi-link-45deg"></i>
                <span>Manajemen Short Link</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Manajemen User</span>
            </a>

            <div class="sidebar-nav-label" style="margin-top:1.25rem;">Akun</div>

            <a href="{{ route('admin.profile') }}"
               class="sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Profil Admin</span>
            </a>

            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <i class="bi bi-arrow-left-circle"></i>
                <span>Kembali ke Situs</span>
            </a>
        </nav>

        {{-- User Footer --}}
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="flex:1;min-width:0;">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
                <i class="bi bi-three-dots-vertical sidebar-user-icon"></i>
            </div>
        </div>
    </aside>

    {{-- Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ── Main ── --}}
    <div class="admin-main">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="topbar-page-title">@yield('page_title', 'Admin Dashboard')</div>
                    <div class="topbar-breadcrumb">ShortLink &rsaquo; Admin &rsaquo; @yield('page_title', 'Dashboard')</div>
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('dashboard') }}" class="topbar-view-site">
                    <i class="bi bi-box-arrow-up-right"></i> Lihat Situs
                </a>
                <a href="{{ route('admin.profile') }}" class="topbar-user-chip">
                    <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="topbar-name">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down" style="font-size:0.65rem;opacity:0.6;"></i>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="topbar-logout-btn" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-sm-inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <main class="admin-content">
            @if(session('success'))
                <div class="admin-alert admin-alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:0.75rem;"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="admin-alert admin-alert-error alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:0.75rem;"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle (mobile)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose  = document.getElementById('sidebarClose');
    const adminSidebar  = document.getElementById('adminSidebar');
    const sidebarOverlay= document.getElementById('sidebarOverlay');

    function openSidebar() {
        adminSidebar.classList.add('open');
        sidebarOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        adminSidebar.classList.remove('open');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
</script>
@stack('scripts')
</body>
</html>
