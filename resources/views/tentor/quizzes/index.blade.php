@extends('layouts.dashboard')

@section('title', 'Kuis & Bank Soal - Eduria')
@section('page-title', 'Kuis & Bank Soal')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}" class="nav-link">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('tentor.courses.index') }}" class="nav-link">
        <i class="fas fa-book"></i>Course Saya
    </a>
    <a href="{{ route('tentor.modules.index') }}" class="nav-link">
        <i class="fas fa-layer-group"></i>Modul
    </a>
    <a href="{{ route('tentor.quizzes.index') }}" class="nav-link active">
        <i class="fas fa-question-circle"></i>Kuis & Bank Soal
    </a>
    <a href="{{ route('tentor.students.index') }}" class="nav-link">
        <i class="fas fa-users"></i>Peserta
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Daftar Kuis</span>
            <a href="{{ route('tentor.quizzes.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="fas fa-plus me-1"></i>Buat Kuis
            </a>
        </div>
        <div class="card-body p-0">
            @if ($quizzes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Kuis</th>
                                <th>Course</th>
                                <th>Soal</th>
                                <th>Peserta</th>
                                <th>Batas Waktu</th>
                                <th>Dibuat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quizzes as $quiz)
                                <tr>
                                    <td class="fw-semibold">{{ $quiz->title }}</td>
                                    <td class="text-muted">{{ $quiz->course->title ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $quiz->questions_count }} Soal
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ $quiz->attempts_count }} Peserta
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $quiz->time_limit }} menit</td>
                                    <td class="text-muted">{{ $quiz->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h6>Belum ada kuis</h6>
                    <p>Anda belum membuat kuis apapun. Klik "Buat Kuis" untuk memulai.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
