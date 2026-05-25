@extends('layouts.dashboard')

@section('title', 'Tentor Dashboard - Eduria')
@section('page-title', 'Tentor Dashboard')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}" class="nav-link active">
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
    <a href="{{ route('tentor.students.index') }}" class="nav-link">
        <i class="fas fa-users"></i>Participants
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@section('content')
    {{-- Welcome Card --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4 animate-on-scroll" style="background: linear-gradient(135deg, #2a5298, #1e3c72); color: #fff;">
                <div>
                    <i class="fas fa-chalkboard-teacher" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #fff;" id="tentorGreeting">Welcome, {{ $user->name }}!</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                        Keep inspiring and educating the next generation.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $courses->count() }}">0</span>
                </div>
                <div class="stat-label">My Courses</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-1">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $totalStudents }}">0</span>
                </div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-2">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f6c23e, #d4a217);">
                        <i class="fas fa-question-circle"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $totalQuizzes }}">0</span>
                </div>
                <div class="stat-label">Total Quizzes</div>
            </div>
        </div>
    </div>

    {{-- Course List --}}
    <div class="content-card shadow-sm animate-on-scroll delay-3">
        <div class="card-header">
            <span>My Courses</span>
            <a href="{{ route('tentor.courses.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="fas fa-plus me-1"></i>Create Course
            </a>
        </div>
        <div class="card-body p-0">
            @if ($courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Course Title</th>
                                <th>Modules</th>
                                <th>Quizzes</th>
                                <th>Students</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td class="fw-semibold">{{ $course->title }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $course->modules_count }} Modules
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            {{ $course->quizzes_count }} Quizzes
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ $course->students_count }} Students
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $course->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('tentor.courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h6>No courses yet</h6>
                    <p>You haven't created any courses yet. Click "Create Course" to get started.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hour = new Date().getHours();
        var el = document.getElementById('tentorGreeting');
        if (el) {
            var name = el.textContent.split(', ').pop() || '';
            var greet = 'Good ';
            if (hour >= 3 && hour < 11) greet += 'Morning';
            else if (hour >= 11 && hour < 15) greet += 'Afternoon';
            else if (hour >= 15 && hour < 18) greet += 'Evening';
            else greet += 'Night';
            el.textContent = greet + ', ' + name + '!';
        }
    });
</script>
@endpush
