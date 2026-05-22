@extends('layouts.dashboard')

@section('title', 'Dashboard - Eduria')
@section('page-title', 'Dashboard Siswa')

@section('sidebar-menu')
    <a href="{{ route('dashboard') }}" class="nav-link active">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-book"></i>Kelas Saya
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-history"></i>Riwayat Kuis
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-certificate"></i>Sertifikat
    </a>
    <a href="{{ route('profile.edit') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@section('content')
    {{-- Welcome + Stat --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff;">
                <div>
                    <i class="fas fa-graduation-cap" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #fff;">Selamat Datang, {{ $user->name }}!</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                        Terus semangat belajar dan raih prestasi terbaikmu!
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $enrolledCourses->count() }}</div>
                        <div class="stat-label">Kelas Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enrolled Courses --}}
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Kelas Saya</span>
            <span class="badge bg-primary rounded-pill">{{ $enrolledCourses->count() }}</span>
        </div>
        <div class="card-body">
            @if ($enrolledCourses->count() > 0)
                <div class="row g-3">
                    @foreach ($enrolledCourses as $course)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 h-100" style="border-radius: 14px; background: #f8faff;">
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
                    <i class="fas fa-book-open"></i>
                    <h6>Kelas masih kosong</h6>
                    <p>
                        Kamu belum terdaftar di kelas manapun. Hubungi admin atau tentor untuk
                        mendapatkan akses ke kelas.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
