<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Eduria</title>
    <script>if(localStorage.getItem('theme')==='dark')document.documentElement.setAttribute('data-theme','dark');</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
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
                        <i data-lucide="check"></i>
                    </div>
                    <div class="toast-login-text">
                        <h6><i data-lucide="key" style="width:14px;height:14px;color:#fbbf24;display:inline;vertical-align:middle;"></i> Login Successful!</h6>
                        <p>Welcome back, <strong>{{ $loginData['name'] }}</strong> &middot; <span>{{ $loginData['role'] }}</span></p>
                    </div>
                    <button class="toast-login-close" onclick="dismissLoginToast()" aria-label="Close">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <div class="toast-login-progress">
                    <div class="toast-login-progress-bar" id="toastProgress"></div>
                </div>
            </div>
        </div>
    @endif

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main Content --}}
    <div class="main-content">
        @include('layouts.partials.topbar')

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.3); z-index:999;"
         onclick="closeSidebar()"></div>

    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.js"></script>
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
        lucide.createIcons();

        /* ── Close Sidebar ── */
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

        /* ── Dark Mode Toggle ── */
        var themeToggle = document.getElementById('themeToggle');
        var htmlEl = document.documentElement;

        function applyTheme(theme) {
            if (theme === 'dark') {
                htmlEl.setAttribute('data-theme', 'dark');
                if (themeToggle) themeToggle.innerHTML = '<i data-lucide="sun" style="width:18px;height:18px;"></i>';
            } else {
                htmlEl.removeAttribute('data-theme');
                if (themeToggle) themeToggle.innerHTML = '<i data-lucide="moon" style="width:18px;height:18px;"></i>';
            }
            localStorage.setItem('theme', theme);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        if (themeToggle) {
            var current = localStorage.getItem('theme') || 'light';
            applyTheme(current);
            themeToggle.addEventListener('click', function() {
                var next = htmlEl.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                applyTheme(next);
            });
        }

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

    @include('layouts.partials._dynamic')

    @stack('scripts')
</body>
</html>
