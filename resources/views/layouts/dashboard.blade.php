<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Eduria</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fc;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 24px;
            color: #fff;
            font-weight: 800;
            font-size: 1.4rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand:hover { color: #fff; }

        .sidebar-brand i { font-size: 1.6rem; }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.65);
            padding: 12px 24px;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.25s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-left-color: #fbbf24;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
        }

        .sidebar .nav-section {
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 20px 24px 8px;
        }

        .sidebar-user {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
        }

        .sidebar-user .avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user .user-name {
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .sidebar-user .user-role {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar .page-title {
            font-weight: 700;
            color: #1e3c72;
            font-size: 1.2rem;
        }

        .topbar .page-title span {
            color: #a0aec0;
            font-weight: 400;
        }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .topbar .btn-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #718096;
            transition: all 0.3s;
            text-decoration: none;
        }

        .topbar .btn-icon:hover {
            background: #f7fafc;
            color: #4e73df;
        }

        .topbar .user-dropdown .dropdown-toggle {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 8px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .topbar .user-dropdown .dropdown-toggle:hover {
            background: #f7fafc;
        }

        .topbar .user-dropdown .dropdown-toggle::after { display: none; }

        .topbar .avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
        }

        .content-wrapper {
            padding: 28px;
        }

        /* ── Sidebar Toggle (mobile) ── */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #1e3c72;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px;
        }

        /* ── Card ── */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 22px 24px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
        }

        .stat-card .stat-number {
            font-weight: 800;
            font-size: 1.8rem;
            color: #1e3c72;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            color: #a0aec0;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .content-card {
            border: none;
            border-radius: 16px;
            background: #fff;
            overflow: hidden;
        }

        .content-card .card-header {
            background: transparent;
            border-bottom: 1px solid #f0f4f8;
            padding: 18px 24px;
            font-weight: 700;
            color: #1e3c72;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-card .card-body { padding: 24px; }

        /* ── Badge Role ── */
        .badge-role {
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-role.admin { background: #ebf4ff; color: #4e73df; }
        .badge-role.tentor { background: #fefcbf; color: #975a16; }
        .badge-role.siswa { background: #c6f6d5; color: #276749; }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 16px;
        }

        .empty-state h6 {
            font-weight: 700;
            color: #4a5568;
        }

        .empty-state p {
            color: #a0aec0;
            font-size: 0.9rem;
            max-width: 360px;
            margin: 0 auto;
        }

        /* ── Responsive ── */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .content-wrapper {
                padding: 20px 16px;
            }

            .topbar {
                padding: 12px 16px;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            Eduria
        </a>

        <div class="nav-section">Menu</div>

        @section('sidebar-menu')
            {{-- Override this section in each dashboard --}}
        @show

        <div class="sidebar-user d-flex align-items-center gap-3">
            <div class="avatar" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-grow-1 min-width-0">
                <div class="user-name text-truncate">{{ Auth::user()->name }}</div>
                <div class="user-role text-capitalize">{{ Auth::user()->role }}</div>
            </div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               style="color: rgba(255,255,255,0.4); font-size: 1.1rem;"
               title="Keluar">
                <i class="fas fa-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="page-title mb-0">
                    @yield('page-title', 'Dashboard')
                </h5>
            </div>

            <div class="topbar-right">
                <div class="dropdown user-dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 14px; border: none; padding: 8px;">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2" style="color: #4e73df; width: 18px;"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-right-from-bracket me-2" style="width: 18px;"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.3); z-index:999;"
         onclick="closeSidebar()"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').style.display = 'none';
        }

        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
            document.getElementById('sidebarOverlay').style.display = sidebar.classList.contains('open') ? 'block' : 'none';
        });
    </script>

    @stack('scripts')
</body>
</html>
