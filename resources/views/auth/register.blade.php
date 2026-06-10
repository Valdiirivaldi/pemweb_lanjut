<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Eduria</title>
    <script>if(localStorage.getItem('theme')==='dark')document.documentElement.setAttribute('data-theme','dark');</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4e73df 100%);
            padding: 20px;
        }

        .register-wrapper {
            width: 100%;
            max-width: 460px;
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand a {
            text-decoration: none;
            color: #fff;
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .brand a i { margin-right: 10px; }

        .brand p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            margin-top: 6px;
        }

        .card-register {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 36px 36px 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }

        .card-register h3 {
            font-weight: 800;
            color: #1e3c72;
            margin-bottom: 4px;
        }

        .card-register .subtitle {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }

        .floating-group {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-group .form-control {
            height: 56px;
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            padding: 22px 16px 8px 16px;
            font-size: 0.95rem;
            background: #fff;
            color: #2d3748;
        }

        .floating-group .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.12);
            outline: none;
        }

        .floating-group .form-control.is-invalid {
            border-color: #e53e3e;
            box-shadow: 0 0 0 4px rgba(229, 62, 62, 0.1);
        }

        .floating-group label {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            font-size: 0.95rem;
            color: #a0aec0;
            font-weight: 500;
            pointer-events: none;
            background: transparent;
            padding: 0 4px;
        }

        .floating-group .form-control:focus ~ label,
        .floating-group .form-control:not(:placeholder-shown) ~ label {
            top: 12px;
            transform: translateY(0);
            font-size: 0.75rem;
            color: #4e73df;
            font-weight: 600;
        }

        .floating-group .form-control::placeholder {
            color: transparent;
        }

        .floating-group .form-control:focus::placeholder {
            color: #cbd5e0;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            padding: 8px;
            font-size: 1.1rem;
            z-index: 5;
        }

        .password-toggle:hover { color: #4e73df; }

        @keyframes segPop {
            0% { transform: scaleY(1); }
            50% { transform: scaleY(1.6); }
            100% { transform: scaleY(1.3); }
        }

        .password-strength {
            margin-top: -8px;
            margin-bottom: 4px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .password-strength.visible { opacity: 1; }

        .password-strength .strength-bar {
            display: flex;
            gap: 4px;
            height: 6px;
        }

        .password-strength .strength-bar .seg {
            flex: 1;
            background: #e2e8f0;
            border-radius: 4px;
            transition: background 0.4s ease, transform 0.3s ease, box-shadow 0.4s ease;
        }

        .password-strength .strength-bar .seg.active {
            transform: scaleY(1.4);
            animation: segPop 0.4s ease;
        }

        .password-strength .strength-bar .seg:nth-child(1).active { background: #e53e3e; box-shadow: 0 0 6px rgba(229,62,62,0.4); }
        .password-strength .strength-bar .seg:nth-child(2).active { background: #dd6b20; box-shadow: 0 0 6px rgba(221,107,32,0.4); }
        .password-strength .strength-bar .seg:nth-child(3).active { background: #d69e2e; box-shadow: 0 0 6px rgba(214,158,46,0.4); }
        .password-strength .strength-bar .seg:nth-child(4).active { background: #38a169; box-shadow: 0 0 6px rgba(56,161,105,0.4); }
        .password-strength .strength-bar .seg:nth-child(5).active { background: #2f855a; box-shadow: 0 0 10px rgba(47,133,90,0.5); }

        .password-strength .strength-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-align: center;
            margin-top: 4px;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .password-strength .strength-label i {
            font-size: 0.75rem;
        }

        .password-strength .criteria-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 6px;
        }

        .password-strength .criteria-list .criteria-item {
            font-size: 0.62rem;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 3px;
            transition: all 0.35s ease;
        }

        .password-strength .criteria-list .criteria-item.met {
            background: #f0fff4;
            border-color: #38a169;
            color: #2f855a;
        }

        .password-strength .criteria-list .criteria-item i {
            font-size: 0.55rem;
        }

        .btn-register {
            width: 100%;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-register:hover:not(:disabled) {
            box-shadow: 0 8px 30px rgba(78, 115, 223, 0.4);
        }

        .btn-register:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        .alert-error {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            border-radius: 14px;
            color: #c53030;
            padding: 14px 18px;
            font-size: 0.9rem;
            font-weight: 500;
            display: none;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 22px;
        }

        .alert-error.show {
            display: flex;
        }

        .alert-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .auth-link {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #224abe;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            color: #a0aec0;
            font-size: 0.85rem;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider::before { margin-right: 16px; }
        .divider::after { margin-left: 16px; }

        .back-link {
            text-align: center;
            margin-top: 18px;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link a:hover {
            color: #fff;
        }

        .back-link a i { margin-right: 6px; }

        /* ── Dark Mode ── */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #0a1628 0%, #132347 50%, #1a3a7a 100%);
        }
        [data-theme="dark"] .card-register {
            background: rgba(30, 41, 59, 0.95);
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-theme="dark"] .card-register h3 {
            color: #f1f5f9;
        }
        [data-theme="dark"] .card-register .subtitle {
            color: #94a3b8;
        }
        [data-theme="dark"] .floating-group .form-control {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }
        [data-theme="dark"] .floating-group .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.2);
        }
        [data-theme="dark"] .floating-group label {
            color: #64748b;
        }
        [data-theme="dark"] .floating-group .form-control:focus ~ label,
        [data-theme="dark"] .floating-group .form-control:not(:placeholder-shown) ~ label {
            color: #60a5fa;
        }
        [data-theme="dark"] .floating-group .form-control::placeholder {
            color: transparent;
        }
        [data-theme="dark"] .floating-group .form-control:focus::placeholder {
            color: #475569;
        }
        [data-theme="dark"] .password-toggle {
            color: #64748b;
        }
        [data-theme="dark"] .password-toggle:hover {
            color: #60a5fa;
        }
        [data-theme="dark"] .password-strength .criteria-list .criteria-item {
            background: #1e293b;
            border-color: #334155;
            color: #64748b;
        }
        [data-theme="dark"] .password-strength .criteria-list .criteria-item.met {
            background: #064e3b;
            border-color: #38a169;
            color: #6ee7b7;
        }
        [data-theme="dark"] .divider {
            color: #64748b;
        }
        [data-theme="dark"] .divider::before,
        [data-theme="dark"] .divider::after {
            background: #334155;
        }
        [data-theme="dark"] .text-center {
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .alert-error {
            background: #7f1d1d;
            border-color: #991b1b;
            color: #fecaca;
        }
        [data-theme="dark"] .password-strength .strength-bar .seg {
            background: #334155;
        }

        @media (max-width: 480px) {
            .card-register {
                padding: 28px 20px 24px;
            }

            .brand a { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

    <button class="theme-toggle" id="themeToggle" type="button" title="Toggle theme"
        style="position:fixed;top:20px;right:20px;z-index:9999;width:42px;height:42px;border-radius:12px;border:1px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.15);backdrop-filter:blur(8px);color:#fff;font-size:1.1rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s ease;">
        <i class="fas fa-moon"></i>
    </button>

    <div class="register-wrapper">
        <div class="brand">
            <a href="{{ route('home') }}">
                <i class="fas fa-graduation-cap"></i>Eduria
            </a>
            <p>Online English Learning Platform</p>
        </div>

        <div class="card-register">
            <h3>Create New Account</h3>
            <p class="subtitle">Register to start learning at Eduria</p>

            @if ($errors->any())
                <div class="alert-error show" role="alert">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf

                <div class="floating-group">
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Full Name"
                           autocomplete="name"
                           autofocus
                           required>
                    <label for="name">
                        <i class="far fa-user me-1"></i>Full Name
                    </label>
                    @error('name')
                        <div style="font-size:0.78rem;color:#e53e3e;margin-top:4px">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="floating-group">
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           autocomplete="email"
                           required>
                    <label for="email">
                        <i class="far fa-envelope me-1"></i>Email Address
                    </label>
                    @error('email')
                        <div style="font-size:0.78rem;color:#e53e3e;margin-top:4px">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="floating-group">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Create password"
                           autocomplete="new-password"
                           minlength="8"
                           required>
                    <label for="password">
                        <i class="fas fa-lock me-1"></i>Password
                    </label>
                    <button type="button"
                            class="password-toggle"
                            id="togglePassword"
                            tabindex="-1"
                            aria-label="Toggle password visibility">
                        <i class="far fa-eye-slash" id="toggleIcon"></i>
                    </button>
                    @error('password')
                        <div style="font-size:0.78rem;color:#e53e3e;margin-top:4px">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div id="passwordStrength" class="password-strength">
                    <div class="strength-bar" id="strengthBar">
                        <div class="seg" data-idx="0"></div>
                        <div class="seg" data-idx="1"></div>
                        <div class="seg" data-idx="2"></div>
                        <div class="seg" data-idx="3"></div>
                        <div class="seg" data-idx="4"></div>
                    </div>
                    <div class="strength-label">
                        <i id="strengthIcon" class="fas fa-lock"></i>
                        <span id="strengthLabel"></span>
                    </div>
                    <div class="criteria-list" id="criteriaList">
                        <span class="criteria-item" data-criterion="length8">
                            <i class="far fa-circle"></i> 8+ characters
                        </span>
                        <span class="criteria-item" data-criterion="length12">
                            <i class="far fa-circle"></i> 12+ characters
                        </span>
                        <span class="criteria-item" data-criterion="case">
                            <i class="far fa-circle"></i> Uppercase & lowercase
                        </span>
                        <span class="criteria-item" data-criterion="digit">
                            <i class="far fa-circle"></i> Number
                        </span>
                        <span class="criteria-item" data-criterion="special">
                            <i class="far fa-circle"></i> Symbol
                        </span>
                    </div>
                </div>

                <div class="floating-group">
                    <input type="password"
                           class="form-control"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Confirm password"
                           autocomplete="new-password"
                           required>
                    <label for="password_confirmation">
                        <i class="fas fa-lock me-1"></i>Confirm Password
                    </label>
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i>Register
                </button>
            </form>

            <div class="divider">or</div>

            <p class="text-center mb-0" style="font-size: 0.9rem; color: #718096;">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link">Login here</a>
            </p>
        </div>

        <div class="back-link">
            <a href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i>Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            'use strict';

            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            const passInput = document.getElementById('password');
            const strengthLabel = document.getElementById('strengthLabel');
            const strengthWrap = document.getElementById('passwordStrength');
            const strengthIcon = document.getElementById('strengthIcon');
            const segments = document.querySelectorAll('.strength-bar .seg');
            const criteriaItems = document.querySelectorAll('.criteria-item');

            function calculateStrength(pass) {
                const checks = {
                    length8: pass.length >= 8,
                    length12: pass.length >= 12,
                    case: /[a-z]/.test(pass) && /[A-Z]/.test(pass),
                    digit: /\d/.test(pass),
                    special: /[^a-zA-Z0-9]/.test(pass)
                };
                const score = Object.values(checks).filter(Boolean).length;
                return { score, checks };
            }

            function updateStrength() {
                const pass = passInput.value;
                if (pass.length === 0) {
                    strengthWrap.classList.remove('visible');
                    return;
                }
                strengthWrap.classList.add('visible');

                const { score, checks } = calculateStrength(pass);

                segments.forEach(function(seg, i) {
                    if (i < score) {
                        if (!seg.classList.contains('active')) {
                            setTimeout(function() {
                                seg.classList.add('active');
                            }, i * 50);
                        }
                    } else {
                        seg.classList.remove('active');
                    }
                });

                criteriaItems.forEach(function(item) {
                    var criterion = item.getAttribute('data-criterion');
                    var met = checks[criterion];
                    item.classList.toggle('met', met);
                    item.querySelector('i').className = met ? 'fas fa-check-circle' : 'far fa-circle';
                });

                var label, iconClass;
                if (score <= 2) { label = 'Weak'; iconClass = 'fas fa-exclamation-triangle'; }
                else if (score <= 3) { label = 'Medium'; iconClass = 'fas fa-minus-circle'; }
                else { label = 'Strong'; iconClass = 'fas fa-shield-halved'; }

                strengthLabel.textContent = label;
                strengthIcon.className = iconClass;
            }

            passInput.addEventListener('input', updateStrength);

            toggleBtn.addEventListener('click', function() {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                toggleIcon.className = isPassword ? 'far fa-eye' : 'far fa-eye-slash';
                toggleBtn.setAttribute('aria-label',
                    isPassword ? 'Hide password' : 'Show password'
                );
            });

        })();

        var themeToggle = document.getElementById('themeToggle');
        var htmlEl = document.documentElement;

        function applyTheme(theme) {
            if (theme === 'dark') {
                htmlEl.setAttribute('data-theme', 'dark');
                if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                htmlEl.removeAttribute('data-theme');
                if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
            localStorage.setItem('theme', theme);
        }

        if (themeToggle) {
            var current = localStorage.getItem('theme') || 'light';
            applyTheme(current);
            themeToggle.addEventListener('click', function() {
                var next = htmlEl.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                applyTheme(next);
            });
        }
    </script>

</body>
</html>
