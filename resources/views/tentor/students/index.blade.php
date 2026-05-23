@extends('layouts.dashboard')

@section('title', 'Peserta - Eduria')
@section('page-title', 'Peserta')

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
    <a href="{{ route('tentor.quizzes.index') }}" class="nav-link">
        <i class="fas fa-question-circle"></i>Kuis & Bank Soal
    </a>
    <a href="{{ route('tentor.students.index') }}" class="nav-link active">
        <i class="fas fa-users"></i>Peserta
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Daftar Siswa Terdaftar</span>
            <span class="badge bg-primary rounded-pill">{{ $students->count() }} Siswa</span>
        </div>
        <div class="card-body p-0">
            @if ($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td class="fw-semibold">{{ $student->name }}</td>
                                    <td class="text-muted">{{ $student->email }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $student->enrolled_course }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $student->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h6>Belum ada peserta</h6>
                    <p>Belum ada siswa yang terdaftar di course anda. Enrollment siswa dapat diatur oleh Admin.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
