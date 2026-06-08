@extends('layouts.dashboard')

@section('title', 'Dashboard - Eduria')
@section('page-title', 'Student Dashboard')

@push('styles')
<style>
    .stat-card-flat {
        border: none;
        border-radius: 16px;
        padding: 22px 24px;
        background: #fff;
        transition: all 0.3s ease;
    }

    .stat-card-flat:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    }

    .stat-card-flat .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
    }

    .stat-card-flat .stat-number {
        font-weight: 800;
        font-size: 1.8rem;
        color: #1e3c72;
        line-height: 1.2;
    }

    .stat-card-flat .stat-label {
        color: #a0aec0;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .greeting-card {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        padding: 28px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .greeting-card::after {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    .greeting-card .greeting-icon {
        font-size: 2.8rem;
        opacity: 0.2;
        position: absolute;
        bottom: 16px;
        right: 24px;
    }

    .greeting-card h3 {
        font-weight: 800;
        margin-bottom: 4px;
    }

    .greeting-card p {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .section-header h5 {
        font-weight: 700;
        color: #1e3c72;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-header .btn-link-all {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4e73df;
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 8px;
        transition: all 0.25s ease;
    }

    .section-header .btn-link-all:hover {
        background: rgba(78,115,223,0.08);
    }

    .course-card-sm {
        border: none;
        border-radius: 14px;
        background: #f8faff;
        transition: all 0.3s ease;
        height: 100%;
    }

    .course-card-sm:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }

    .course-card-sm .card-body {
        padding: 18px;
    }

    .course-card-sm .course-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .course-card-sm .course-title-sm {
        font-weight: 700;
        font-size: 0.92rem;
        color: #1e3c72;
        margin-bottom: 2px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-card-sm .course-tentor-sm {
        font-size: 0.78rem;
        color: #a0aec0;
    }

    .section-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .section-card .section-card-body {
        padding: 20px 24px 24px;
    }

    /* ── Dashboard Entry Animations ── */
    .dash-anim {
        opacity: 0;
        transform: translateY(24px);
        animation: dashFadeIn 0.5s ease forwards;
    }

    .dash-anim:nth-child(1) { animation-delay: 0.05s; }
    .dash-anim:nth-child(2) { animation-delay: 0.10s; }
    .dash-anim:nth-child(3) { animation-delay: 0.15s; }
    .dash-anim:nth-child(4) { animation-delay: 0.20s; }
    .dash-anim:nth-child(5) { animation-delay: 0.25s; }

    @keyframes dashFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    .cert-list-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f0f4f8;
    }

    .cert-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .cert-list-item .cert-icon-sm {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .cert-list-item .cert-info {
        flex: 1;
        min-width: 0;
    }

    .cert-list-item .cert-title-sm {
        font-weight: 600;
        font-size: 0.88rem;
        color: #1e3c72;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cert-list-item .cert-meta-sm {
        font-size: 0.78rem;
        color: #a0aec0;
    }

    .cert-list-item .btn-download-sm {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #4e73df;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    .cert-list-item .btn-download-sm:hover {
        background: #4e73df;
        border-color: #4e73df;
        color: #fff;
    }
</style>
@endpush

@section('content')
    {{-- Row 1: Greeting + Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="greeting-card shadow-sm dash-anim">
                <div class="position-relative" style="z-index: 1;">
                    <h3 id="siswaGreeting">Welcome, {{ $user->name }}!</h3>
                    <p id="siswaGreetingMsg">Keep learning and reach your best achievements!</p>
                </div>
                <i data-lucide="graduation-cap" class="greeting-icon" style="width:40px;height:40px;"></i>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="stat-card-flat shadow-sm dash-anim">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                <i data-lucide="book-open" style="width:20px;height:20px;"></i>
                            </div>
                            <div>
                                <div class="stat-number">
                                    <span class="counter-animate" data-target="{{ $totalEnrolled }}">0</span>
                                </div>
                                <div class="stat-label">Enrolled Courses</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: My Courses --}}
    <div class="dash-anim">
        <div class="section-header">
            <h5><i data-lucide="book" style="width:18px;height:18px;color:#4e73df;margin-right:8px;"></i>My Courses</h5>
            @if ($enrolledCourses->count() > 0)
                <a href="{{ route('siswa.courses.index') }}" class="btn-link-all">
                    View All <i data-lucide="arrow-right" style="width:12px;height:12px;margin-left:4px;"></i>
                </a>
            @endif
        </div>
        <div class="section-card shadow-sm">
            <div class="section-card-body">
                @if ($enrolledCourses->count() > 0)
                    <div class="row g-3">
                        @foreach ($enrolledCourses as $course)
                            <div class="col-md-6 col-lg-4">
                                <div class="course-card-sm shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-3 mb-2">
                                            <div class="course-icon-box">
                                                <i data-lucide="book" style="width:18px;height:18px;"></i>
                                            </div>
                                            <div class="flex-grow-1 min-width-0">
                                                <div class="course-title-sm">{{ $course->title }}</div>
                                                <div class="course-tentor-sm">
                                                    <i data-lucide="presentation" style="width:12px;height:12px;margin-right:4px;"></i>{{ $course->tentor->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        @if ($course->description)
                                            <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.8rem;">
                                                {{ $course->description }}
                                            </p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                <i data-lucide="calendar" style="width:12px;height:12px;margin-right:4px;"></i>{{ $course->created_at->format('d M Y') }}
                                            </small>
                                            <a href="{{ route('siswa.courses.learn', $course->id) }}" class="btn btn-sm btn-primary rounded-pill px-3" style="font-size: 0.78rem; font-weight: 600;">
                                                <i data-lucide="arrow-right" style="width:14px;height:14px;margin-right:4px;"></i>Enter
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="padding: 24px 12px;">
                        <div class="empty-state-icon-wrap"><i data-lucide="book-open" style="width:32px;height:32px;color:#cbd5e0;"></i></div>
                        <h6 style="font-size: 0.95rem; color: #1e3c72;">You have no courses yet</h6>
                        <p style="font-size: 0.85rem;">Browse and join courses on the My Courses page.</p>
                        <a href="{{ route('siswa.courses.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 mt-2">
                            <i data-lucide="search" style="width:14px;height:14px;margin-right:4px;"></i>Find Courses
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Section: Quiz History --}}
    <div class="dash-anim">
        <div class="section-header">
            <h5><i data-lucide="pencil" style="width:18px;height:18px;color:#4e73df;margin-right:8px;"></i>Quiz History</h5>
            @if ($quizAttempts->count() > 0)
                <a href="{{ route('siswa.quizzes.index') }}" class="btn-link-all">
                    View All <i data-lucide="arrow-right" style="width:12px;height:12px;margin-left:4px;"></i>
                </a>
            @endif
        </div>
        <div class="section-card shadow-sm">
            <div class="section-card-body">
                @if ($quizAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table-admin mb-0" data-sortable>
                            <thead>
                                <tr>
                                    <th data-sort="quiz">Quiz</th>
                                    <th data-sort="course">Course</th>
                                    <th data-sort="score">Score</th>
                                    <th data-sort="date">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizAttempts as $attempt)
                                    <tr>
                                        <td class="fw-semibold" style="color: #1e3c72;">{{ $attempt->quiz->title ?? '-' }}</td>
                                        <td style="color: #718096;">{{ $attempt->quiz->course->title ?? '-' }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $attempt->certificate_path ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}"
                                                  style="font-weight: 600; font-size: 0.78rem; padding: 4px 12px;">
                                                {{ $attempt->score }}
                                            </span>
                                        </td>
                                        <td style="color: #a0aec0; font-size: 0.8rem;">{{ $attempt->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state" style="padding: 24px 12px;">
                        <div class="empty-state-icon-wrap"><i data-lucide="pencil" style="width:32px;height:32px;color:#cbd5e0;"></i></div>
                        <h6 style="font-size: 0.95rem; color: #1e3c72;">No quizzes yet</h6>
                        <p style="font-size: 0.85rem;">You haven't taken any quizzes yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Section: Sertifikat --}}
    <div class="dash-anim">
        <div class="section-header">
            <h5><i data-lucide="award" style="width:18px;height:18px;color:#4e73df;margin-right:8px;"></i>Certificates</h5>
            @if ($certificates->count() > 0)
                <a href="{{ route('siswa.certificates.index') }}" class="btn-link-all">
                    View All <i data-lucide="arrow-right" style="width:12px;height:12px;margin-left:4px;"></i>
                </a>
            @endif
        </div>
        <div class="section-card shadow-sm">
            <div class="section-card-body">
                @forelse ($certificates as $cert)
                    <div class="cert-list-item">
                        <div class="cert-icon-sm">
                            <i data-lucide="file-text" style="width:18px;height:18px;"></i>
                        </div>
                        <div class="cert-info">
                            <div class="cert-title-sm">{{ $cert->quiz->title ?? 'Certificate' }}</div>
                            <div class="cert-meta-sm">
                                <i data-lucide="calendar" style="width:12px;height:12px;margin-right:4px;"></i>{{ $cert->created_at->format('d M Y') }}
                                <span class="mx-2">|</span>
                                <i data-lucide="star" style="width:12px;height:12px;margin-right:4px;"></i>Score: {{ $cert->score }}
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $cert->certificate_path) }}"
                           class="btn-download-sm" target="_blank" download title="Download">
                            <i data-lucide="download" style="width:16px;height:16px;"></i>
                        </a>
                    </div>
                @empty
                    <div class="empty-state" style="padding: 24px 12px;">
                        <div class="empty-state-icon-wrap"><i data-lucide="award" style="width:32px;height:32px;color:#cbd5e0;"></i></div>
                        <h6 style="font-size: 0.95rem; color: #1e3c72;">No certificates yet</h6>
                        <p style="font-size: 0.85rem;">Complete quizzes with a passing score to earn certificates.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hour = new Date().getHours();
        var greetingEl = document.getElementById('siswaGreeting');
        var msgEl = document.getElementById('siswaGreetingMsg');

        if (greetingEl) {
            var name = '{{ $user->name }}';
            var greet = 'Good ';
            if (hour >= 3 && hour < 11) greet += 'Morning';
            else if (hour >= 11 && hour < 15) greet += 'Afternoon';
            else if (hour >= 15 && hour < 18) greet += 'Evening';
            else greet += 'Night';
            greetingEl.textContent = greet + ', ' + name + '!';
        }

        if (msgEl) {
            var msgs = [
                'Keep learning and reach your best achievements!',
                'Every small step brings you closer to your dreams!',
                'Never give up, success is waiting ahead!',
                'Learn today, lead tomorrow!',
                'Consistency is the key to success!'
            ];
            var dayOfYear = Math.floor((new Date() - new Date(new Date().getFullYear(), 0, 0)) / 86400000);
            msgEl.textContent = msgs[dayOfYear % msgs.length];
        }
    });
</script>
@endpush
