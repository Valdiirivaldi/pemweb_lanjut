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
        /* ── Login Success Toast ── */
        .toast-login-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            opacity: 0;
            transform: translateX(120%) scale(0.9);
            transition: opacity 0.5s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
            pointer-events: none;
            max-width: 420px;
            width: 100%;
        }
        .toast-login-container.show {
            opacity: 1;
            transform: translateX(0) scale(1);
            pointer-events: auto;
        }
        .toast-login-container.hiding {
            opacity: 0;
            transform: translateX(60%) scale(0.9);
        }
        .toast-login {
            display: flex;
            align-items: stretch;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 16px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            position: relative;
        }
        .toast-login-accent {
            width: 5px;
            flex-shrink: 0;
            background: linear-gradient(180deg, #1e3c72, #2a5298);
        }
        .toast-login-body {
            flex: 1;
            padding: 18px 20px 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .toast-login-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
            flex-shrink: 0;
            position: relative;
        }
        .toast-login-icon .check-ring {
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 2px solid rgba(46, 82, 152, 0.25);
            animation: toastPing 1.5s ease-in-out infinite;
        }
        @keyframes toastPing {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 0; }
        }
        .toast-login-text h6 {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e3c72;
            margin: 0 0 2px;
            line-height: 1.3;
        }
        .toast-login-text p {
            font-size: 0.8rem;
            color: #718096;
            margin: 0;
            line-height: 1.4;
        }
        .toast-login-text p span {
            font-weight: 600;
            color: #4e73df;
            text-transform: capitalize;
        }
        .toast-login-close {
            position: absolute;
            top: 8px;
            right: 10px;
            background: none;
            border: none;
            color: #a0aec0;
            font-size: 1rem;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 8px;
            transition: all 0.2s;
            line-height: 1;
        }
        .toast-login-close:hover {
            background: #f7fafc;
            color: #4a5568;
        }
        .toast-login-progress {
            position: absolute;
            bottom: 0;
            left: 5px;
            right: 0;
            height: 3px;
            background: #e9edf4;
            border-radius: 0 0 16px 0;
            overflow: hidden;
        }
        .toast-login-progress-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #1e3c72, #4e73df);
            border-radius: 0 0 16px 0;
            transform-origin: left;
            animation: toastShrink 5s linear forwards;
        }
        @keyframes toastShrink {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

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

        .topbar .live-clock {
            font-size: 0.8rem;
            color: #a0aec0;
            font-weight: 500;
            letter-spacing: 0.3px;
            border-left: 1px solid #e2e8f0;
            padding-left: 12px;
        }

        /* ── Entrance Animations ── */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .animate-on-scroll.delay-1 { transition-delay: 0.1s; }
        .animate-on-scroll.delay-2 { transition-delay: 0.2s; }
        .animate-on-scroll.delay-3 { transition-delay: 0.3s; }
        .animate-on-scroll.delay-4 { transition-delay: 0.4s; }
        .animate-on-scroll.delay-5 { transition-delay: 0.5s; }

        /* ── Hover Lift Enhancement ── */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        }

        /* ── Pulse Dot ── */
        .pulse-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1cc88a;
            margin-right: 6px;
            animation: pulseDot 2s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
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

    {{-- Login Success Toast --}}
    @if (session('login-success'))
        @php $loginData = session('login-success'); @endphp
        <div class="toast-login-container show" id="loginToast">
            <div class="toast-login">
                <div class="toast-login-accent"></div>
                <div class="toast-login-body">
                    <div class="toast-login-icon">
                        <div class="check-ring"></div>
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="toast-login-text">
                        <h6><i class="fas fa-key me-1" style="color: #fbbf24;"></i> Login Successful!</h6>
                        <p>Welcome back, <strong>{{ $loginData['name'] }}</strong> &middot; <span>{{ $loginData['role'] }}</span></p>
                    </div>
                    <button class="toast-login-close" onclick="dismissLoginToast()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-login-progress">
                    <div class="toast-login-progress-bar" id="toastProgress"></div>
                </div>
            </div>
        </div>
    @endif

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            Eduria
        </a>

        <div class="nav-section">Menu</div>

        @switch(auth()->user()->role)
            @case('tentor')
                <a href="{{ route('tentor.dashboard') }}"
                   class="nav-link {{ request()->routeIs('tentor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>Dashboard
                </a>
                <a href="{{ route('tentor.courses.index') }}"
                   class="nav-link {{ request()->routeIs('tentor.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>My Courses
                </a>
                <a href="{{ route('tentor.modules.index') }}"
                   class="nav-link {{ request()->routeIs('tentor.modules.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>Modules
                </a>
                <a href="{{ route('tentor.quizzes.index') }}"
                   class="nav-link {{ request()->routeIs('tentor.quizzes.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>Quizzes
                </a>
                <a href="{{ route('tentor.students.index') }}"
                   class="nav-link {{ request()->routeIs('tentor.students.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>Participants
                </a>
                @break

            @case('admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>Manage Users
                </a>
                <a href="{{ route('admin.enrollments.index') }}"
                   class="nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i>Enrollments
                </a>
                @break

            @case('siswa')
                <a href="{{ route('siswa.dashboard') }}"
                   class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>Dashboard
                </a>
                <a href="{{ route('siswa.courses.index') }}"
                   class="nav-link {{ request()->routeIs('siswa.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>My Courses
                </a>
                <a href="{{ route('siswa.quizzes.index') }}"
                   class="nav-link {{ request()->routeIs('siswa.quizzes.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>Quiz History
                </a>
                <a href="{{ route('siswa.certificates.index') }}"
                   class="nav-link {{ request()->routeIs('siswa.certificates.*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i>Certificates
                </a>
                @break

            @default
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>Dashboard
                </a>
        @endswitch

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
               title="Logout">
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
                <span class="live-clock" id="liveClock"></span>
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
                            <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                <i class="fas fa-user me-2" style="color: #4e73df; width: 18px;"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-right-from-bracket me-2" style="width: 18px;"></i>Logout
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @php
        $__flash = [];
        if ($msg = session('success')) { $__flash['success'] = $msg; }
        if ($msg = session('error'))   { $__flash['error']   = $msg; }
        if ($msg = session('warning')) { $__flash['warning'] = $msg; }
        if ($msg = session('info'))    { $__flash['info']    = $msg; }
    @endphp
    @if ($__flash)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var flash = @json($__flash);
                var icons = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
                Object.entries(flash).forEach(function(_a) {
                    var type = _a[0], message = _a[1];
                    if (!message) return;
                    Swal.fire({
                        icon: icons[type] || 'info',
                        title: message,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                });
            });
        </script>
    @endif
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

        /* ── Live Clock ── */
        function updateClock() {
            var now = new Date();
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            var el = document.getElementById('liveClock');
            if (el) el.textContent = now.toLocaleDateString('en-US', options);
        }
        updateClock();
        setInterval(updateClock, 1000);

        /* ── Entrance Animation on Scroll ── */
        document.addEventListener('DOMContentLoaded', function() {
            var animated = document.querySelectorAll('.animate-on-scroll');
            if (animated.length) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });
                animated.forEach(function(el) { observer.observe(el); });
            }
        });

        /* ── Login Toast Dismiss ── */
        function dismissLoginToast() {
            var toast = document.getElementById('loginToast');
            if (!toast) return;
            toast.classList.remove('show');
            toast.classList.add('hiding');
            setTimeout(function() { toast.remove(); }, 500);
        }
        document.addEventListener('DOMContentLoaded', function() {
            var toast = document.getElementById('loginToast');
            if (toast) {
                setTimeout(dismissLoginToast, 5200);
            }
        });

        /* ── Counter Animation ── */
        document.addEventListener('DOMContentLoaded', function() {
            var counters = document.querySelectorAll('.counter-animate');
            if (counters.length) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var el = entry.target;
                            var target = parseInt(el.getAttribute('data-target'));
                            if (target === 0) { el.textContent = '0'; return; }
                            var current = 0;
                            var step = Math.max(1, Math.ceil(target / 30));
                            var interval = setInterval(function() {
                                current += step;
                                if (current >= target) { current = target; clearInterval(interval); }
                                el.textContent = current;
                            }, 30);
                            observer.unobserve(el);
                        }
                    });
                }, { threshold: 0.5 });
                counters.forEach(function(c) { observer.observe(c); });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
