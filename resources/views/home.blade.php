<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eduria - Best Online Learning Platform</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ── Navbar ── */
        .navbar-eduria {
            background: transparent;
            transition: all 0.4s ease;
            padding: 16px 0;
            z-index: 9999;
        }
        .navbar-eduria.scrolled {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
            padding: 8px 0;
        }
        .navbar-eduria .nav-link {
            color: #fff !important;
            font-weight: 500;
            margin: 0 12px;
            position: relative;
            transition: color 0.3s;
        }
        .navbar-eduria.scrolled .nav-link {
            color: #2d3748 !important;
        }
        .navbar-eduria .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #fff;
            transition: width 0.3s;
        }
        .navbar-eduria.scrolled .nav-link::after { background: #4e73df; }
        .navbar-eduria .nav-link:hover::after { width: 100%; }
        .navbar-eduria .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: #fff !important;
            letter-spacing: -0.5px;
        }
        .navbar-eduria.scrolled .navbar-brand { color: #4e73df !important; }
        .btn-glow {
            border: 2px solid #fff;
            color: #fff;
            border-radius: 50px;
            padding: 8px 28px;
            font-weight: 600;
            transition: all 0.3s;
            background: transparent;
        }
        .btn-glow:hover {
            background: #fff;
            color: #4e73df;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
        }
        .navbar-eduria.scrolled .btn-glow {
            border-color: #4e73df;
            color: #4e73df;
        }
        .navbar-eduria.scrolled .btn-glow:hover {
            background: #4e73df;
            color: #fff;
            box-shadow: 0 0 30px rgba(78, 115, 223, 0.4);
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 30%, #4e73df 70%, #224abe 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            overflow: hidden;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        .hero .container { position: relative; z-index: 2; }
        .hero-title {
            font-size: 3.2rem;
            font-weight: 900;
            line-height: 1.2;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .hero-title .typed-text {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 520px;
            line-height: 1.8;
        }
        .btn-cta {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #1e3c72;
            font-weight: 700;
            padding: 16px 44px;
            border-radius: 50px;
            font-size: 1.1rem;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 8px 30px rgba(251, 191, 36, 0.35);
            animation: pulse 2.5s infinite;
        }
        .btn-cta:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 40px rgba(251, 191, 36, 0.5);
            color: #1e3c72;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 8px 30px rgba(251, 191, 36, 0.35); }
            50% { box-shadow: 0 8px 50px rgba(251, 191, 36, 0.6); }
        }
        .hero-illustration {
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            filter: drop-shadow(0 20px 60px rgba(0, 0, 0, 0.2));
        }
        .floating {
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-18px); }
        }

        /* ── Bouncing Icon ── */
        .bouncing-icon {
            animation: bounce 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* ── Section Titles ── */
        .section-title {
            font-weight: 800;
            font-size: 2.2rem;
            color: #1e3c72;
            position: relative;
            margin-bottom: 1rem;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            margin: 14px 0;
            border-radius: 4px;
        }
        .section-title-center::after { margin: 14px auto 0; }

        /* ── Keunggulan Cards ── */
        .feature-card {
            border: none;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            transition: all 0.4s ease;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }
        .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(78, 115, 223, 0.15);
        }
        .feature-icon-box {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            font-size: 2rem;
            color: #fff;
            margin: 0 auto 1.2rem;
        }

        /* ── About ── */
        .about-section { background: #f8faff; }
        .facility-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            font-weight: 500;
        }
        .facility-item i { color: #1cc88a; font-size: 1.2rem; }

        /* ── CTA Contact ── */
        .cta-section {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }
        .btn-wa {
            background: #25d366;
            color: #fff;
            font-weight: 700;
            padding: 18px 50px;
            border-radius: 50px;
            font-size: 1.2rem;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 8px 30px rgba(37, 211, 102, 0.35);
        }
        .btn-wa:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 16px 50px rgba(37, 211, 102, 0.5);
            color: #fff;
        }
        .btn-wa i { animation: waPulse 2s infinite; }
        @keyframes waPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        /* ── Footer ── */
        .footer { background: #0f1a2e; }
        .footer a { color: #a0aec0; text-decoration: none; transition: color 0.3s; }
        .footer a:hover { color: #4e73df; }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .hero-title { font-size: 2.4rem; }
            .navbar-eduria .nav-link { color: #2d3748 !important; }
            .navbar-eduria .nav-link::after { background: #4e73df !important; }
            .navbar-eduria .navbar-brand { color: #4e73df !important; }
            .navbar-eduria.scrolled .btn-glow { border-color: #fff; color: #fff; }
            .navbar-eduria.scrolled .btn-glow:hover { background: #fff; color: #4e73df; }
            .navbar-eduria .btn-glow { border-color: #4e73df; color: #4e73df; }
            .navbar-eduria .btn-glow:hover { background: #4e73df; color: #fff; }
        }
        @media (max-width: 767px) {
            .hero-title { font-size: 1.8rem; }
            .section-title { font-size: 1.6rem; }
            .hero-illustration { max-width: 320px; }
        }
    </style>
</head>
<body>

    <!-- ═══ NAVBAR ═══ -->
    <nav id="navbarEduria" class="navbar navbar-expand-lg navbar-eduria fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>Eduria
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keunggulan">Why Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Contact</a></li>
                </ul>
                    <a href="{{ route('login') }}" class="btn btn-glow">
                    <i class="fas fa-arrow-right-to-bracket me-2"></i>Login / Register
                </a>
            </div>
        </div>
    </nav>

    <!-- ═══ HERO ═══ -->
    <section id="hero" class="hero">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <p class="text-warning fw-semibold mb-2" style="letter-spacing: 2px; font-size: 0.9rem;">
                        <i class="fas fa-star me-1"></i>                         ONLINE LEARNING #1 PLATFORM
                    </p>
                    <h1 class="hero-title">
                        Ayo <span class="typed-text"></span>
                    </h1>
                    <p class="hero-sub mt-3">
                        Eduria adalah platform belajar bahasa Inggris with experienced tutors, complete materials, dan fun learning experience.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="#keunggulan" class="btn btn-cta">
                            <i class="fas fa-rocket me-2"></i>Start Learning Now
                        </a>
                        <a href="#about" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                            Learn More <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-4 pt-2">
                        <div><small class="text-white-50">Experienced Tutors</small><p class="text-white fw-bold mb-0">-</p></div>
                        <div><small class="text-white-50">Total Students</small><p class="text-white fw-bold mb-0">-</p></div>
                        <div><small class="text-white-50">Rating</small><p class="text-white fw-bold mb-0">⭐ 4.9</p></div>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000">
                    <img src="https://cdn-icons-png.flaticon.com/512/2885/2885417.png"
                         alt="Ilustrasi Belajar"
                         class="hero-illustration floating img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ KEUNGGULAN ═══ -->
    <section id="keunggulan" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                    <p class="text-primary fw-semibold mb-1">WHY CHOOSE US</p>
                    <h2 class="section-title section-title-center">Kenapa Harus Eduria?</h2>
                    <p class="text-muted mx-auto" style="max-width: 540px;">
                        We provide complete learning solutions untuk membantu kamu sukses.
                    </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card feature-card text-center h-100">
                        <div class="feature-icon-box bouncing-icon mx-auto" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Expert Tutors</h5>
                        <p class="text-muted mb-0">
                            Learn directly with experienced and professional tutors in their fields.
                        </p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card feature-card text-center h-100">
                        <div class="feature-icon-box bouncing-icon mx-auto" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                            <i class="fas fa-book"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Complete Materials</h5>
                        <p class="text-muted mb-0">
                            Access modules, learning videos, dan practice questions that are always updated.
                        </p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card feature-card text-center h-100">
                        <div class="feature-icon-box bouncing-icon mx-auto" style="background: linear-gradient(135deg, #f6c23e, #d4a217);">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Interactive Quizzes</h5>
                        <p class="text-muted mb-0">
                            Test your understanding with fun quizzes that make learning feel like playing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ ABOUT ═══ -->
    <section id="about" class="about-section py-5">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="zoom-in" data-aos-duration="1000">
                    <img src="https://cdn-icons-png.flaticon.com/512/4228/4228690.png"
                         alt="Tentang Eduria"
                         class="img-fluid floating"
                         style="max-width: 420px; filter: drop-shadow(0 10px 40px rgba(78, 115, 223, 0.15));">
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <p class="text-primary fw-semibold mb-1">ABOUT US</p>
                    <h2 class="section-title">Trusted Learning<br>Solution Since 2024</h2>
                    <p class="text-muted">
                        Eduria merupakan official supporting website from our offline tutoring center.
                        All students who register and join our offline classes will automatically
                        receive an account with full access to digital materials
                        and interactive quizzes on this platform.
                    </p>
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <div class="facility-item">
                                <i class="fas fa-circle-check"></i> Learning Videos
                            </div>
                            <div class="facility-item">
                                <i class="fas fa-circle-check"></i> PDF Modules & E-Books
                            </div>
                            <div class="facility-item">
                                <i class="fas fa-circle-check"></i> Quizzes & Try Outs
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="facility-item">
                                <i class="fas fa-circle-check"></i> Exclusive Materials
                            </div>
                            <div class="facility-item">
                                <i class="fas fa-circle-check"></i> Graduation Certificates
                            </div>
                            <div class="facility-item">
                                <i class="fas fa-circle-check"></i> Exclusive Discussion Groups
                            </div>
                        </div>
                    </div>
                    <a href="#kontak" class="btn btn-primary rounded-pill px-5 py-3 fw-bold mt-3"
                       style="background: linear-gradient(135deg, #4e73df, #224abe); border: none;">
                        <i class="fas fa-phone me-2"></i>Contact Us Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA CONTACT ═══ -->
    <section id="kontak" class="cta-section py-5">
        <div class="container py-5 text-center position-relative" style="z-index: 2;">
            <div data-aos="flip-up" data-aos-duration="1000">
                <p class="text-warning fw-semibold mb-1" style="letter-spacing: 2px;">READY TO SUCCEED?</p>
                <h2 class="text-white fw-bold" style="font-size: 2.4rem;">Join Eduria Sekarang!</h2>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 540px;">
                    Contact us via WhatsApp untuk info dan pendaftaran.
                    Tim kami siap membantu you!
                </p>
                <a href="https://wa.me/628972551888" class="btn btn-wa" target="_blank">
                    <i class="fab fa-whatsapp fa-lg me-2"></i>Contact WhatsApp
                </a>
                <div class="d-flex justify-content-center gap-5 mt-5 text-white">
                    <div><i class="fas fa-envelope text-warning me-2"></i>eduriainfo@gmail.com</div>
                    <div><i class="fas fa-map-marker-alt text-warning me-2"></i>Bandung, Indonesia</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ FOOTER ═══ -->
    <footer class="footer py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-graduation-cap me-1"></i>
                        &copy; {{ date('Y') }} Eduria. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-youtube fa-lg"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-tiktok fa-lg"></i></a>
                    <a href="#"><i class="fab fa-whatsapp fa-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ═══ SCRIPTS ═══ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <script>
        // ── Navbar scroll effect ──
        window.addEventListener('scroll', function () {
            const nav = document.getElementById('navbarEduria');
            if (window.scrollY > 60) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // ── AOS Init ──
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-cubic',
        });

        // ── Typed.js ──
        new Typed('.typed-text', {
            strings: [
                'Learning Made Fun.',
                'Reach Your Goals.',
                'Together with Eduria.',
            ],
            typeSpeed: 70,
            backSpeed: 40,
            backDelay: 1500,
            loop: true,
            showCursor: true,
            cursorChar: '|',
        });
    </script>
</body>
</html>
