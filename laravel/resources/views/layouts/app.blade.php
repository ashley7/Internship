<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MedIntern') — Medical Internship Portal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:ital@0;1&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary:   #1a5276;
            --brand-accent:    #2e86c1;
            --brand-light:     #d6eaf8;
            --brand-success:   #1e8449;
            --brand-danger:    #c0392b;
            --brand-warning:   #d68910;
            --sidebar-width:   260px;
            --sidebar-bg:      #0d2137;
            --sidebar-text:    #a9cce3;
            --sidebar-active:  #2e86c1;
            --topbar-height:   64px;
            --body-bg:         #f0f4f8;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--body-bg);
            color: #1c2833;
            min-height: 100vh;
        }

        /* ── Sidebar ───────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-brand h5 {
            font-family: 'Lora', serif;
            font-size: 1.15rem;
            color: #fff;
            margin: 0;
            letter-spacing: .3px;
        }

        .sidebar-brand small {
            color: var(--sidebar-text);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-user {
            padding: 16px 22px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--brand-accent);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff; font-size: .9rem;
            flex-shrink: 0;
        }

        .sidebar-user-info strong {
            display: block;
            color: #fff;
            font-size: .85rem;
            line-height: 1.3;
        }

        .sidebar-user-info span {
            color: var(--sidebar-text);
            font-size: .72rem;
            text-transform: capitalize;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 0;
            overflow-y: auto;
        }

        .sidebar-section {
            padding: 6px 22px 4px;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(169,204,227,.45);
            margin-top: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 22px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }

        .sidebar-link i { font-size: 1.05rem; flex-shrink: 0; }

        .sidebar-link:hover {
            color: #fff;
            background: rgba(255,255,255,.05);
        }

        .sidebar-link.active {
            color: #fff;
            background: rgba(46,134,193,.18);
            border-left-color: var(--sidebar-active);
        }

        .sidebar-footer {
            padding: 14px 22px;
            border-top: 1px solid rgba(255,255,255,.07);
        }

        /* ── Main Layout ───────────────────────────────── */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #dde3ea;
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 900;
            gap: 16px;
        }

        .topbar-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--brand-primary);
            flex: 1;
        }

        #content {
            flex: 1;
            padding: 28px;
        }

        /* ── Cards ─────────────────────────────────────── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 4px 12px rgba(0,0,0,.05);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eaf0f6;
            border-radius: 12px 12px 0 0 !important;
            padding: 16px 20px;
            font-weight: 600;
            font-size: .92rem;
            color: var(--brand-primary);
        }

        /* ── Stat Cards ────────────────────────────────── */
        .stat-card {
            border-radius: 12px;
            padding: 22px 20px;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 14px;
        }

        .stat-card .stat-value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            opacity: .75;
        }

        /* ── Status Badges ──────────────────────────────── */
        .badge-pending  { background: #fef3cd; color: #856404; }
        .badge-approved { background: #d1e7dd; color: #0a3622; }
        .badge-declined { background: #f8d7da; color: #58151c; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .3px;
            display: inline-block;
        }

        /* ── Tables ────────────────────────────────────── */
        .table thead th {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: #6b7c93;
            background: #f8fafc;
            border-bottom: 2px solid #e8edf3;
            white-space: nowrap;
        }

        .table td { vertical-align: middle; font-size: .875rem; }
        .table tbody tr:hover { background: #f8fafc; }

        /* ── Forms ──────────────────────────────────────── */
        .form-label { font-weight: 600; font-size: .82rem; color: #4a5568; margin-bottom: 6px; }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #dde3ea;
            font-size: .875rem;
            padding: 9px 14px;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-accent);
            box-shadow: 0 0 0 3px rgba(46,134,193,.15);
        }
        textarea.form-control { min-height: 130px; resize: vertical; }

        /* ── Buttons ────────────────────────────────────── */
        .btn { border-radius: 8px; font-weight: 600; font-size: .85rem; }
        .btn-primary { background: var(--brand-primary); border-color: var(--brand-primary); }
        .btn-primary:hover { background: var(--brand-accent); border-color: var(--brand-accent); }

        /* ── Notes ──────────────────────────────────────── */
        .note-bubble {
            background: #f0f4f8;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-left: 3px solid #cbd5e0;
        }
        .note-bubble.supervisor { border-left-color: var(--brand-accent); background: #eaf4fb; }
        .note-bubble.student    { border-left-color: #27ae60; background: #eafaf1; }

        /* ── Report Text ────────────────────────────────── */
        .report-body {
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: 10px;
            padding: 20px;
            font-size: .9rem;
            line-height: 1.8;
            white-space: pre-wrap;
        }

        /* ── Alerts ─────────────────────────────────────── */
        .alert { border-radius: 10px; border: none; font-size: .875rem; }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-wrapper { margin-left: 0; }
        }

        /* ── Print ──────────────────────────────────────── */
        @media print {
            #sidebar, #topbar, .no-print { display: none !important; }
            #main-wrapper { margin-left: 0; }
            .card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-hospital text-info fs-5"></i>
            <h5>MedIntern</h5>
        </div>
        <small>Medical Internship Portal</small>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div class="sidebar-user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ str_replace('_', ' ', auth()->user()->role == 'student'?'Intern Doctor':auth()->user()->role) }}</span>
        </div>
    </div>

    <div class="sidebar-nav">
        @yield('sidebar-links')
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>
</nav>

<!-- Main -->
<div id="main-wrapper">
    <div id="topbar">
        <button class="btn btn-sm btn-outline-secondary d-md-none me-2" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">{{ now()->format('D, d M Y') }}</span>
        </div>
    </div>

    <div id="content">
        {{-- Flash Messages --}}
        @foreach(['success' => 'success', 'info' => 'info', 'error' => 'danger'] as $key => $type)
            @if(session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'danger' ? 'x-circle' : 'info-circle') }} me-2"></i>
                    {{ session($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
