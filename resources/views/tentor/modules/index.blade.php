@extends('layouts.dashboard')

@section('title', 'Modul - Eduria')
@section('page-title', 'Modul')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}" class="nav-link">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('tentor.courses.index') }}" class="nav-link">
        <i class="fas fa-book"></i>Course Saya
    </a>
    <a href="{{ route('tentor.modules.index') }}" class="nav-link active">
        <i class="fas fa-layer-group"></i>Modul
    </a>
    <a href="{{ route('tentor.quizzes.index') }}" class="nav-link">
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
            <span>Daftar Modul</span>
        </div>
        <div class="card-body p-0">
            @if ($modules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Modul</th>
                                <th>Course</th>
                                <th>Video</th>
                                <th>PDF</th>
                                <th>Dibuat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td class="fw-semibold">{{ $module->title }}</td>
                                    <td class="text-muted">{{ $module->course->title ?? '-' }}</td>
                                    <td>
                                        @if ($module->video_url)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check me-1"></i>Ada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-times me-1"></i>Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($module->pdf_path)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check me-1"></i>Ada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-times me-1"></i>Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $module->created_at->format('d M Y') }}</td>
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
                    <i class="fas fa-layer-group"></i>
                    <h6>Belum ada modul</h6>
                    <p>Modul akan muncul setelah Anda membuat course dan menambahkan modul di dalamnya.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
