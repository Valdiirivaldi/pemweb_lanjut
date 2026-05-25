@extends('layouts.dashboard')

@section('title', 'Certificates - Eduria')
@section('page-title', 'Certificates')

@section('sidebar-menu')
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
    <a href="{{ route('profile') }}"
       class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
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
</style>
@endpush

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span><i class="fas fa-certificate me-2" style="color: #4e73df;"></i>Graduation Certificates</span>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-weight: 600;">
                {{ $certificates->count() }} certificates
            </span>
        </div>
        <div class="card-body">
            @forelse ($certificates as $cert)
                <div class="cert-card p-4 mb-3">
                    <div class="d-flex align-items-center gap-4">
                        <div class="cert-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="cert-title text-truncate">
                                {{ $cert->quiz->title ?? 'Certificate' }}
                            </div>
                            <div class="cert-meta mt-1">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $cert->created_at->format('d M Y') }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-star me-1"></i>
                                Score: {{ $cert->score }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-book me-1"></i>
                                {{ $cert->quiz->course->title ?? '-' }}
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $cert->certificate_path) }}"
                           class="btn btn-primary btn-download"
                           target="_blank"
                           download>
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div style="font-size: 4rem; color: #cbd5e0; margin-bottom: 16px; line-height: 1;">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h6 style="color: #1e3c72; font-weight: 700;">No Certificates Yet</h6>
                    <p style="color: #a0aec0; font-size: 0.9rem;">Complete quizzes with a passing score to earn your certificate.</p>
                    <a href="{{ route('siswa.quizzes.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 mt-2">
                        <i class="fas fa-pencil-alt me-1"></i>View Quizzes
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
