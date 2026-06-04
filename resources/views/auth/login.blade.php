<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Eduria</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 30%, #4e73df 70%, #224abe 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
            pointer-events: none;
        }

        canvas#particlesCanvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 2;
            perspective: 1000px;
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand a {
            text-decoration: none;
            color: #fff;
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -0.5px;
            transition: opacity 0.3s;
        }

        .brand a:hover {
            opacity: 0.85;
        }

        .brand a i {
            margin-right: 10px;
        }

        .brand p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            margin-top: 6px;
        }

        .card-login {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 40px 36px 36px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            transform-style: preserve-3d;
            will-change: transform;
        }

        .card-login h3 {
            font-weight: 800;
            color: #1e3c72;
            margin-bottom: 4px;
            transform: translateZ(30px);
        }

        .card-login .subtitle {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 28px;
            transform: translateZ(20px);
        }

        .floating-group {
            position: relative;
            margin-bottom: 22px;
        }

        .floating-group .form-control {
            height: 56px;
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            padding: 22px 16px 8px 16px;
            font-size: 0.95rem;
            background: #fff;
            transition: all 0.3s ease;
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

        .floating-group .form-control.is-valid {
            border-color: #38a169;
            box-shadow: 0 0 0 4px rgba(56, 161, 105, 0.1);
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
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            padding: 0 4px;
        }

        .floating-group .form-control:focus~label,
        .floating-group .form-control:not(:placeholder-shown)~label {
            top: 12px;
            transform: translateY(0);
            font-size: 0.75rem;
            color: #4e73df;
            font-weight: 600;
        }

        .floating-group .form-control.is-invalid~label {
            color: #e53e3e;
        }

        .floating-group .form-control.is-valid~label {
            color: #38a169;
        }

        .floating-group .form-control::placeholder {
            color: transparent;
        }

        .floating-group .form-control:focus::placeholder {
            color: #cbd5e0;
        }

        .validation-feedback {
            font-size: 0.78rem;
            font-weight: 500;
            margin-top: 6px;
            margin-left: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
            opacity: 0;
            transform: translateY(-6px);
            transition: all 0.3s ease;
            height: 0;
            overflow: hidden;
        }

        .validation-feedback.show {
            opacity: 1;
            transform: translateY(0);
            height: 20px;
        }

        .validation-feedback.invalid {
            color: #e53e3e;
        }

        .validation-feedback.valid {
            color: #38a169;
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
            transition: color 0.3s;
            z-index: 5;
        }

        .password-toggle:hover {
            color: #4e73df;
        }

        .password-toggle:focus {
            outline: none;
        }

        .password-toggle i {
            transition: transform 0.3s ease, opacity 0.2s ease;
            display: inline-block;
        }

        .password-toggle.animating i {
            animation: iconPop 0.35s ease;
        }

        @keyframes iconPop {
            0% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }

            50% {
                transform: scale(0.7) rotate(180deg);
                opacity: 0.4;
            }

            100% {
                transform: scale(1) rotate(360deg);
                opacity: 1;
            }
        }

        .password-strength {
            margin-top: -10px;
            margin-bottom: 18px;
            height: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .password-strength.visible {
            opacity: 1;
        }

        .password-strength .strength-bar {
            flex: 1;
            height: 5px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .password-strength .strength-bar-fill {
            height: 100%;
            width: 0%;
            border-radius: 10px;
            transition: width 0.35s ease, background 0.35s ease;
        }

        .password-strength .strength-label {
            font-size: 0.72rem;
            font-weight: 600;
            min-width: 55px;
            text-align: right;
            transition: color 0.35s ease;
        }

        .btn-login {
            width: 100%;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            transform: translateZ(40px);
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px) translateZ(40px);
            box-shadow: 0 8px 30px rgba(78, 115, 223, 0.4);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(0) translateZ(40px);
        }

        .btn-login:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        .btn-login .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        .btn-login.loading .btn-text-loading {
            display: inline;
        }

        .btn-text-loading {
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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
            opacity: 0;
            transform: translateY(-8px);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .alert-error.show {
            display: flex;
            opacity: 1;
            transform: translateY(0);
        }

        .alert-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .form-check-input:checked {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .form-check-input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
        }

        .auth-link {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
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

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .back-link {
            text-align: center;
            margin-top: 18px;
        }

        .back-link a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #fff;
        }

        .back-link a i {
            margin-right: 6px;
        }

        .toast-notification {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            background: #fff;
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-left: 5px solid #4e73df;
            display: none;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #2d3748;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 380px;
        }

        .toast-notification.show {
            display: flex;
            transform: translateX(0);
        }

        .toast-notification i {
            font-size: 1.3rem;
            color: #4e73df;
        }

        .toast-notification.error {
            border-left-color: #e53e3e;
        }

        .toast-notification.error i {
            color: #e53e3e;
        }

        @media (max-width: 480px) {
            .card-login {
                padding: 28px 20px 24px;
            }

            .brand a {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

    <canvas id="particlesCanvas"></canvas>

    <div class="login-wrapper">
        <div class="brand">
            <a href="{{ route('home') }}">
                <i class="fas fa-graduation-cap"></i>Eduria
            </a>
            <p>Platform Belajar Bahasa Inggris Online</p>
        </div>

        <div class="card-login">
            <h3>Selamat Datang</h3>
            <p class="subtitle">Masuk ke akun Eduria Anda</p>

            @error('email')
                <div class="alert-error show" role="alert">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            @if ($errors->has('email'))
                <div id="errorContainer" class="alert-error" role="alert" style="display:none;">
            @else
                <div id="errorContainer" class="alert-error" role="alert">
            @endif
                <i class="fas fa-circle-exclamation"></i>
                <span id="errorMessage"></span>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
                @csrf


                <div class="floating-group">
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email"
                        placeholder="nama@email.com" value="{{ old('email') }}" autocomplete="email" autofocus required>
                    <label for="email">
                        <i class="far fa-envelope me-1"></i>Alamat Email
                    </label>
                    <div id="emailFeedback" class="validation-feedback">
                        <i class="fas fa-circle-exclamation"></i>
                        <span id="emailFeedbackText"></span>
                    </div>
                </div>

                <div class="floating-group">
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan password" autocomplete="current-password" minlength="8" required>
                    <label for="password">
                        <i class="fas fa-lock me-1"></i>Kata Sandi
                    </label>
                    <button type="button" class="password-toggle" id="togglePassword" tabindex="-1"
                        aria-label="Tampilkan kata sandi">
                        <i class="far fa-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>

                <div id="passwordStrength" class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-bar-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-label" id="strengthLabel"></span>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember" style="font-size: 0.9rem; color: #4a5568;">
                            Ingat saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link" style="font-size: 0.85rem;">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        <i class="fas fa-arrow-right-to-bracket me-2"></i>Masuk
                    </span>
                    <span class="btn-text-loading">Memproses...</span>
                </button>
            </form>

            <div class="divider">atau</div>

            <p class="text-center mb-0" style="font-size: 0.9rem; color: #718096;">
                Belum punya akun?
                <a href="{{ route('register') }}" class="auth-link">Daftar Sekarang</a>
            </p>
        </div>

        <div class="back-link">
            <a href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <div id="toastNotification" class="toast-notification">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            'use strict';

            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            const loginBtn = document.getElementById('loginBtn');
            const errorBox = document.getElementById('errorContainer');
            const errorMsg = document.getElementById('errorMessage');
            const toast = document.getElementById('toastNotification');
            const toastMsg = document.getElementById('toastMessage');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const emailFeedback = document.getElementById('emailFeedback');
            const emailFeedbackText = document.getElementById('emailFeedbackText');
            const strengthFill = document.getElementById('strengthFill');
            const strengthLabel = document.getElementById('strengthLabel');
            const strengthWrap = document.getElementById('passwordStrength');

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // ── 1. Real-time Email Validation ──
            function validateEmail() {
                const val = emailInput.value.trim();

                emailInput.classList.remove('is-valid', 'is-invalid');
                emailFeedback.classList.remove('show', 'invalid', 'valid');

                if (val.length === 0) {
                    return;
                }

                const hasAt = val.includes('@');
                const hasDot = val.includes('.');
                const isValid = emailRegex.test(val);

                if (isValid) {
                    emailInput.classList.add('is-valid');
                    emailFeedback.className = 'validation-feedback show valid';
                    emailFeedback.querySelector('i').className = 'fas fa-check-circle';
                    emailFeedbackText.textContent = 'Format email valid';
                } else if (!hasAt || !hasDot) {
                    emailInput.classList.add('is-invalid');
                    emailFeedback.className = 'validation-feedback show invalid';
                    emailFeedback.querySelector('i').className = 'fas fa-circle-exclamation';
                    emailFeedbackText.textContent = 'Format email tidak valid (harus mengandung @ dan .com)';
                }
            }

            emailInput.addEventListener('input', validateEmail);
            emailInput.addEventListener('blur', function() {
                if (this.value.trim().length > 0) {
                    validateEmail();
                }
            });

            // ── 2. Password Strength Meter ──
            function calculateStrength(pass) {
                let score = 0;

                if (pass.length >= 8) score += 1;
                if (pass.length >= 12) score += 1;
                if (/[a-z]/.test(pass) && /[A-Z]/.test(pass)) score += 1;
                if (/\d/.test(pass)) score += 1;
                if (/[^a-zA-Z0-9]/.test(pass)) score += 1;

                return score;
            }

            function updateStrength() {
                const pass = passInput.value;

                if (pass.length === 0) {
                    strengthWrap.classList.remove('visible');
                    return;
                }

                strengthWrap.classList.add('visible');
                const score = calculateStrength(pass);

                let pct, color, label;

                if (score <= 1) {
                    pct = 20;
                    color = '#e53e3e';
                    label = 'Lemah';
                } else if (score === 2) {
                    pct = 40;
                    color = '#dd6b20';
                    label = 'Kurang';
                } else if (score === 3) {
                    pct = 60;
                    color = '#d69e2e';
                    label = 'Sedang';
                } else if (score === 4) {
                    pct = 80;
                    color = '#38a169';
                    label = 'Kuat';
                } else {
                    pct = 100;
                    color = '#2f855a';
                    label = 'Sangat Kuat';
                }

                strengthFill.style.width = pct + '%';
                strengthFill.style.background = color;
                strengthLabel.textContent = label;
                strengthLabel.style.color = color;
            }

            passInput.addEventListener('input', updateStrength);

            // ── 3. 3D Tilt Effect on Card ──
            const card = document.querySelector('.card-login');
            const wrapper = document.querySelector('.login-wrapper');

            wrapper.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = ((y - centerY) / centerY) * -8;
                const rotateY = ((x - centerX) / centerX) * 8;

                card.style.transform =
                    'rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-2px)';
                card.style.boxShadow =
                    (rotateY > 0 ? '' : '-') + Math.abs(rotateY) * 1.5 + 'px ' +
                    (rotateX > 0 ? '' : '-') + Math.abs(rotateX) * 1.5 + 'px 60px rgba(0,0,0,0.2), ' +
                    '0 8px 20px rgba(0,0,0,0.08)';
            });

            wrapper.addEventListener('mouseleave', function() {
                card.style.transform = 'rotateX(0deg) rotateY(0deg) translateY(0)';
                card.style.boxShadow = '0 25px 60px rgba(0,0,0,0.2), 0 8px 20px rgba(0,0,0,0.08)';
            });

            // ── 4. Particle Background ──
            const canvas = document.getElementById('particlesCanvas');
            const ctx = canvas.getContext('2d');
            let particles = [];
            let animId;

            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }

            function createParticles(count) {
                particles = [];
                for (let i = 0; i < count; i++) {
                    particles.push({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        r: Math.random() * 2.5 + 0.5,
                        dx: (Math.random() - 0.5) * 0.4,
                        dy: (Math.random() - 0.5) * 0.4,
                        alpha: Math.random() * 0.5 + 0.2,
                    });
                }
            }

            function drawParticles() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];

                    p.x += p.dx;
                    p.y += p.dy;

                    if (p.x < 0) p.x = canvas.width;
                    if (p.x > canvas.width) p.x = 0;
                    if (p.y < 0) p.y = canvas.height;
                    if (p.y > canvas.height) p.y = 0;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(255, 255, 255, ' + p.alpha + ')';
                    ctx.fill();

                    for (let j = i + 1; j < particles.length; j++) {
                        const q = particles[j];
                        const dist = Math.hypot(p.x - q.x, p.y - q.y);
                        if (dist < 150) {
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(q.x, q.y);
                            ctx.strokeStyle = 'rgba(255, 255, 255, ' + (0.08 * (1 - dist / 150)) + ')';
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }

                animId = requestAnimationFrame(drawParticles);
            }

            function initParticles() {
                resizeCanvas();
                const count = Math.min(Math.floor(window.innerWidth * 0.05), 60);
                createParticles(count);
                drawParticles();
            }

            window.addEventListener('resize', function() {
                resizeCanvas();
                createParticles(Math.min(Math.floor(window.innerWidth * 0.05), 60));
            });

            initParticles();

            // ── 5. Animated Eye Toggle ──
            toggleBtn.addEventListener('click', function() {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                toggleIcon.className = isPassword ? 'far fa-eye' : 'far fa-eye-slash';
                toggleBtn.setAttribute('aria-label',
                    isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
                );

                toggleBtn.classList.remove('animating');
                void toggleBtn.offsetWidth;
                toggleBtn.classList.add('animating');

                passInput.focus({
                    preventScroll: true
                });
            });

            // ── Clear error on input focus ──
            [emailInput, passInput].forEach(function(input) {
                input.addEventListener('focus', function() {
                    hideError();
                    if (this.id === 'email') {
                        this.classList.remove('is-invalid');
                        emailFeedback.classList.remove('show', 'invalid');
                    }
                });
            });

            function showError(message) {
                errorMsg.textContent = message;
                errorBox.classList.add('show');
                emailInput.classList.add('is-invalid');
                passInput.classList.add('is-invalid');
            }

            function hideError() {
                errorBox.classList.remove('show');
            }

            function showToast(message, isError) {
                toastMsg.textContent = message;
                toast.className = 'toast-notification';
                if (isError) {
                    toast.classList.add('error');
                    toast.querySelector('i').className = 'fas fa-exclamation-circle';
                } else {
                    toast.querySelector('i').className = 'fas fa-check-circle';
                }
                toast.classList.add('show');
                clearTimeout(toast._timeout);
                toast._timeout = setTimeout(function() {
                    toast.classList.remove('show');
                }, 4000);
            }

            function setLoading(loading) {
                if (loading) {
                    loginBtn.classList.add('loading');
                    loginBtn.disabled = true;
                    emailInput.disabled = true;
                    passInput.disabled = true;
                } else {
                    loginBtn.classList.remove('loading');
                    loginBtn.disabled = false;
                    emailInput.disabled = false;
                    passInput.disabled = false;
                }
            }

            // ── Handle Form Submit (normal, tanpa AJAX) ──
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const pass = passInput.value;

                if (!email) {
                    showError('Masukkan alamat email Anda.');
                    emailInput.focus();
                    e.preventDefault();
                    return;
                }

                if (!emailRegex.test(email)) {
                    showError('Format email tidak valid.');
                    emailInput.focus();
                    e.preventDefault();
                    return;
                }

                if (!pass || pass.length < 8) {
                    showError('Kata sandi minimal 8 karakter.');
                    passInput.focus();
                    e.preventDefault();
                    return;
                }

                // Tidak ada preventDefault jika valid -> biarkan Laravel menangani redirect.
            });


            [emailInput, passInput].forEach(function(input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && this.disabled) {
                        e.preventDefault();
                    }
                });
            });

        })();
    </script>

</body>

</html>
