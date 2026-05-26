@extends('layouts.dashboard')

@section('title', 'Participants - Eduria')
@section('page-title', 'Participants')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}" class="nav-link">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('tentor.courses.index') }}" class="nav-link">
        <i class="fas fa-book"></i>My Courses
    </a>
    <a href="{{ route('tentor.modules.index') }}" class="nav-link">
        <i class="fas fa-layer-group"></i>Modules
    </a>
    <a href="{{ route('tentor.quizzes.index') }}" class="nav-link">
        <i class="fas fa-question-circle"></i>Quizzes
    </a>
    <a href="{{ route('tentor.students.index') }}" class="nav-link active">
        <i class="fas fa-users"></i>Participants
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Enrolled Students</span>
            <span class="badge bg-primary rounded-pill">{{ $students->count() }} Students</span>
        </div>
        <div class="card-body p-0">
            @if ($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Joined</th>
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
                                    <td class="text-muted">{{ $student->pivot->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h6>No participants yet</h6>
                    <p>No students are enrolled in your courses yet. Student enrollment can be managed by Admin.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
