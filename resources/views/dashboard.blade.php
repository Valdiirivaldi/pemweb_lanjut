@extends('layouts.dashboard')

@section('title', 'Dashboard - Eduria')
@section('page-title', 'Dashboard Siswa')

@section('sidebar-menu')
    <a href="{{ route('dashboard') }}" class="nav-link active">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="#" class="nav-link sidebar-tab-link" data-target="#tab-kelas">
        <i class="fas fa-book"></i>Kelas Saya
    </a>
    <a href="#" class="nav-link sidebar-tab-link" data-target="#tab-riwayat">
        <i class="fas fa-history"></i>Riwayat Kuis
    </a>
    <a href="#" class="nav-link sidebar-tab-link" data-target="#tab-sertifikat">
        <i class="fas fa-certificate"></i>Sertifikat
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
    /* ── Tab Navigation ── */
    .tab-nav-container {
        display: flex;
        gap: 4px;
        border-bottom: 2px solid #e9edf4;
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #718096;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        border-radius: 0;
        text-decoration: none;
    }

    .tab-btn i {
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .tab-btn:hover {
        color: #4e73df;
        background: rgba(78, 115, 223, 0.06);
    }

    .tab-btn.active {
        color: #4e73df;
        border-bottom-color: #4e73df;
        background: transparent;
    }

    /* ── Tab Panels ── */
    .tab-panel {
        display: none;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.35s ease, transform 0.35s ease;
    }

    .tab-panel.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Sidebar Tab Link Active ── */
    .sidebar-tab-link.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.08);
        border-left-color: #fbbf24;
    }

    /* ── Certificate Card ── */
    .cert-card {
        border: none;
        border-radius: 14px;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .cert-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    }

    .cert-card .cert-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        flex-shrink: 0;
    }

    .cert-card .cert-title {
        font-weight: 700;
        color: #1e3c72;
        font-size: 1rem;
    }

    .cert-card .cert-meta {
        color: #a0aec0;
        font-size: 0.8rem;
    }

    .btn-download {
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    /* ── Quiz Table ── */
    .table-quiz {
        margin-bottom: 0;
    }

    .table-quiz thead th {
        border-top: none;
        font-weight: 700;
        font-size: 0.8rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 2px solid #e9edf4;
    }

    .table-quiz tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f4f8;
        font-size: 0.9rem;
        color: #4a5568;
    }

    .table-quiz tbody tr:hover {
        background: #f8faff;
    }

    .score-badge {
        padding: 4px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
    }

    .score-pass {
        background: #c6f6d5;
        color: #276749;
    }

    .score-fail {
        background: #fed7d7;
        color: #9b2c2c;
    }

    /* ── Empty State Enhancement ── */
    .empty-state-img {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 16px;
        line-height: 1;
    }
</style>
@endpush

@section('content')
    {{-- Welcome + Stat --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4 animate-on-scroll" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff;">
                <div>
                    <i class="fas fa-graduation-cap" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #fff;" id="siswaGreeting">Selamat Datang, {{ $user->name }}!</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;" id="siswaGreetingMsg">
                        Terus semangat belajar dan raih prestasi terbaikmu!
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-1">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <div class="stat-number">
                            <span class="counter-animate" data-target="{{ $enrolledCourses->count() }}">0</span>
                        </div>
                        <div class="stat-label">Kelas Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="content-card shadow-sm">
        <div class="tab-nav-container px-3 pt-3">
            <button class="tab-btn active" data-target="#tab-kelas">
                <i class="fas fa-book"></i>
                Kelas Saya
            </button>
            <button class="tab-btn" data-target="#tab-riwayat">
                <i class="fas fa-history"></i>
                Riwayat Kuis
            </button>
            <button class="tab-btn" data-target="#tab-sertifikat">
                <i class="fas fa-certificate"></i>
                Sertifikat
            </button>
        </div>

        <div class="card-body">
            {{-- Panel: Kelas Saya --}}
            <div class="tab-panel active" id="tab-kelas">
                @if ($enrolledCourses->count() > 0)
                    <div class="row g-3">
                        @foreach ($enrolledCourses as $index => $course)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 h-100 animate-on-scroll delay-{{ min($index + 1, 5) }}" style="border-radius: 14px; background: #f8faff;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #4e73df, #224abe); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem;">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-truncate">{{ $course->title }}</h6>
                                                <small class="text-muted">Tentor: {{ $course->tentor->name ?? '-' }}</small>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $course->description ?? 'Tidak ada deskripsi.' }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>{{ $course->created_at->format('d M Y') }}
                                            </small>
                                            <a href="#" class="btn btn-sm btn-primary rounded-pill px-3">
                                                Masuk Kelas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-img">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h6>Kelas Anda masih kosong</h6>
                        <p>Silakan hubungi Admin untuk aktivasi akses kelas.</p>
                    </div>
                @endif
            </div>

            {{-- Panel: Riwayat Kuis --}}
            <div class="tab-panel" id="tab-riwayat">
                @if ($quizAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-quiz">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kuis</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Skor</th>
                                    <th>Tanggal Ujian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizAttempts as $index => $attempt)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-semibold">{{ $attempt->quiz->title ?? '-' }}</td>
                                        <td>{{ $attempt->quiz->course->title ?? '-' }}</td>
                                        <td>
                                            <span class="score-badge {{ $attempt->certificate_path ? 'score-pass' : 'score-fail' }}">
                                                {{ $attempt->score }}
                                            </span>
                                        </td>
                                        <td>{{ $attempt->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-img">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <h6>Tidak ada kuis</h6>
                        <p>Kamu belum mengerjakan kuis apapun. Ikuti pembelajaran di kelas untuk memulai kuis.</p>
                    </div>
                @endif
            </div>

            {{-- Panel: Sertifikat --}}
            <div class="tab-panel" id="tab-sertifikat">
                @forelse ($certificates as $cert)
                    <div class="cert-card p-4 mb-3">
                        <div class="d-flex align-items-center gap-4">
                            <div class="cert-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="cert-title text-truncate">
                                    {{ $cert->quiz->title ?? 'Sertifikat' }}
                                </div>
                                <div class="cert-meta mt-1">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $cert->created_at->format('d M Y') }}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-star me-1"></i>
                                    Skor: {{ $cert->score }}
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $cert->certificate_path) }}"
                               class="btn btn-primary btn-download"
                               target="_blank"
                               download>
                                <i class="fas fa-download me-1"></i> Unduh
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-img">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h6>Belum ada sertifikat</h6>
                        <p>Selesaikan kuis dengan nilai lulus untuk mendapatkan sertifikat kelulusan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ── Dynamic Greeting by Time ── */
        var hour = new Date().getHours();
        var greetingEl = document.getElementById('siswaGreeting');
        var msgEl = document.getElementById('siswaGreetingMsg');
        if (greetingEl) {
            var name = greetingEl.textContent.split(', ').pop() || '';
            var greet = 'Selamat ';
            if (hour >= 3 && hour < 11) greet += 'Pagi';
            else if (hour >= 11 && hour < 15) greet += 'Siang';
            else if (hour >= 15 && hour < 18) greet += 'Sore';
            else greet += 'Malam';
            greetingEl.textContent = greet + ', ' + name + '!';
        }
        if (msgEl) {
            var msgs = [
                'Terus semangat belajar dan raih prestasi terbaikmu!',
                'Setiap langkah kecil membawamu lebih dekat ke impian!',
                'Jangan pernah menyerah, kesuksesan menunggu di depan!',
                'Belajar hari ini, pemimpin di masa depan!',
                'Konsistensi adalah kunci menuju keberhasilan!'
            ];
            var dayOfYear = Math.floor((new Date() - new Date(new Date().getFullYear(), 0, 0)) / 86400000);
            msgEl.textContent = msgs[dayOfYear % msgs.length];
        }

        function switchTab(targetId) {
            var panels = document.querySelectorAll('.tab-panel');
            panels.forEach(function(p) {
                p.classList.remove('active');
            });

            var allTriggers = document.querySelectorAll('.tab-btn, .sidebar-tab-link');
            allTriggers.forEach(function(b) {
                b.classList.remove('active');
            });

            var targetPanel = document.querySelector(targetId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }

            var matchingTriggers = document.querySelectorAll('[data-target="' + targetId + '"]');
            matchingTriggers.forEach(function(b) {
                b.classList.add('active');
            });
        }

        var triggers = document.querySelectorAll('.tab-btn, .sidebar-tab-link');
        triggers.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var target = this.getAttribute('data-target');
                if (target) {
                    e.preventDefault();
                    switchTab(target);
                }
            });
        });

        var hash = window.location.hash;
        if (hash && document.querySelector(hash)) {
            switchTab(hash);
        }
    });
</script>
@endpush
