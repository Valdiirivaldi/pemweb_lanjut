@extends('layouts.dashboard')

@section('title', 'Hasil Kuis - Eduria')
@section('page-title', 'Hasil Kuis')

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
    .result-container {
        max-width: 640px;
        margin: 0 auto;
    }

    .result-card {
        background: #fff;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        text-align: center;
    }

    .result-icon {
        font-size: 4rem;
        margin-bottom: 16px;
    }

    .result-icon.passed {
        color: #10b981;
    }

    .result-icon.failed {
        color: #ef4444;
    }

    .result-title {
        font-weight: 800;
        font-size: 1.5rem;
        color: #1e3c72;
        margin-bottom: 4px;
    }

    .result-subtitle {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 24px;
    }

    .result-score {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
    }

    .result-score.passed {
        color: #10b981;
    }

    .result-score.failed {
        color: #ef4444;
    }

    .result-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 24px;
    }

    .result-status.passed {
        background: #d1fae5;
        color: #065f46;
    }

    .result-status.failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .result-info {
        background: #f8faff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        text-align: left;
    }

    .result-info .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
    }

    .result-info .info-row:not(:last-child) {
        border-bottom: 1px solid #e9edf4;
    }

    .result-info .info-label {
        color: #718096;
        font-size: 0.85rem;
    }

    .result-info .info-value {
        color: #1e3c72;
        font-weight: 600;
        font-size: 0.88rem;
    }

    .result-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-result {
        border-radius: 12px;
        padding: 10px 28px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-result-primary {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
    }

    .btn-result-primary:hover {
        background: linear-gradient(135deg, #224abe, #1a3491);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(78, 115, 223, 0.35);
    }

    .btn-result-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }

    .btn-result-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
    }

    .btn-result-outline {
        background: transparent;
        color: #4e73df;
        border: 1.5px solid #4e73df;
    }

    .btn-result-outline:hover {
        background: rgba(78, 115, 223, 0.06);
        color: #224abe;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #718096;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.25s ease;
        margin-bottom: 16px;
    }

    .back-link:hover {
        background: rgba(78,115,223,0.06);
        color: #4e73df;
    }
</style>
@endpush

@section('content')
    @php
        $passingScore = (int) ($attempt->quiz->passing_score ?? 70);
        $passed = $attempt->score >= $passingScore;
    @endphp

    <a href="{{ route('siswa.courses.learn', $attempt->quiz->course) }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Kelas
    </a>

    <div class="result-container">
        <div class="result-card">
            <div class="result-icon {{ $passed ? 'passed' : 'failed' }}">
                <i class="fas {{ $passed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            </div>

            <div class="result-title">{{ $passed ? 'Selamat!' : 'Tetap Semangat!' }}</div>
            <div class="result-subtitle">{{ $passed ? 'Kamu berhasil lulus kuis ini.' : 'Jangan menyerah, coba lagi!' }}</div>

            <div class="result-score {{ $passed ? 'passed' : 'failed' }}">{{ $attempt->score }}%</div>

            <div class="result-status {{ $passed ? 'passed' : 'failed' }}">
                <i class="fas {{ $passed ? 'fa-check' : 'fa-times' }}"></i>
                {{ $passed ? 'Lulus' : 'Gagal' }}
            </div>

            <div class="result-info">
                <div class="info-row">
                    <span class="info-label">Kelas</span>
                    <span class="info-value">{{ $attempt->quiz->course->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kuis</span>
                    <span class="info-value">{{ $attempt->quiz->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Skor</span>
                    <span class="info-value">{{ $attempt->score }}%</span>
                </div>
            </div>

            <div class="result-actions">
                <a href="{{ route('siswa.quizzes.show', $attempt->quiz) }}" class="btn-result btn-result-primary">
                    <i class="fas fa-redo"></i> Coba Lagi
                </a>
                <a href="{{ route('siswa.courses.learn', $attempt->quiz->course) }}" class="btn-result btn-result-outline">
                    <i class="fas fa-book"></i> Ke Kelas
                </a>
                @if ($passed && !empty($attempt->certificate_path))
                    <a href="{{ asset('storage/' . $attempt->certificate_path) }}" class="btn-result btn-result-success" download>
                        <i class="fas fa-file-pdf"></i> Unduh Sertifikat
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
