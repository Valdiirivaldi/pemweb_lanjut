@extends('layouts.dashboard')

@section('title', $course->title . ' - Eduria')
@section('page-title', $course->title)

@push('styles')
<style>
    .course-hero {
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        padding: 28px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .course-hero::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    .course-hero .hero-icon {
        font-size: 3rem;
        opacity: 0.15;
        position: absolute;
        bottom: 16px;
        right: 24px;
    }

    .course-hero h3 {
        font-weight: 800;
        margin-bottom: 6px;
    }

    .course-hero .hero-tentor {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .course-hero .hero-desc {
        color: rgba(255,255,255,0.8);
        font-size: 0.88rem;
        max-width: 700px;
        line-height: 1.6;
    }

    .course-hero .hero-stats {
        display: flex;
        gap: 24px;
        margin-top: 16px;
    }

    .course-hero .hero-stats .hero-stat {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.75);
    }

    .course-hero .hero-stats .hero-stat i {
        font-size: 1rem;
        color: rgba(255,255,255,0.5);
    }

    .module-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    .module-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .module-card .module-header {
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #f0f4f8;
        cursor: pointer;
        user-select: none;
        transition: background 0.25s ease;
    }

    .module-card .module-header:hover {
        background: #f8faff;
    }

    .module-card .module-header .module-num {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .module-card .module-header .module-title {
        font-weight: 700;
        color: #1e3c72;
        font-size: 0.95rem;
        flex: 1;
    }

    .module-card .module-header .module-toggle-icon {
        color: #a0aec0;
        transition: transform 0.35s ease;
        font-size: 0.85rem;
    }

    .module-card .module-header .module-toggle-icon.open {
        transform: rotate(180deg);
    }

    .module-card .module-body {
        display: none;
        padding: 20px 22px;
    }

    .module-card .module-body.open {
        display: block;
    }

    .module-card .module-body .video-wrapper {
        border-radius: 12px;
        overflow: hidden;
        background: #000;
        margin-bottom: 16px;
    }

    .module-card .module-body .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        background: rgba(78,115,223,0.08);
        color: #4e73df;
        border: 1.5px solid rgba(78,115,223,0.15);
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .module-card .module-body .btn-pdf:hover {
        background: #4e73df;
        color: #fff;
        border-color: #4e73df;
    }

    .module-entry {
        opacity: 0;
        transform: translateY(20px);
        animation: moduleIn 0.45s ease forwards;
    }

    .module-entry:nth-child(1) { animation-delay: 0.05s; }
    .module-entry:nth-child(2) { animation-delay: 0.10s; }
    .module-entry:nth-child(3) { animation-delay: 0.15s; }
    .module-entry:nth-child(4) { animation-delay: 0.20s; }
    .module-entry:nth-child(5) { animation-delay: 0.25s; }
    .module-entry:nth-child(6) { animation-delay: 0.30s; }

    @keyframes moduleIn {
        to { opacity: 1; transform: translateY(0); }
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

    .quiz-card {
        border: none;
        border-radius: 14px;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .quiz-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .quiz-card .quiz-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .quiz-card .quiz-body {
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .quiz-card .quiz-info h6 {
        font-weight: 700;
        color: #1e3c72;
        font-size: 0.92rem;
        margin-bottom: 3px;
    }

    .quiz-card .quiz-info .quiz-meta {
        font-size: 0.78rem;
        color: #a0aec0;
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .quiz-card .quiz-info .quiz-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .quiz-card .quiz-info .quiz-meta i {
        font-size: 0.7rem;
        color: #cbd5e0;
    }

    .btn-start-quiz {
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.82rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-start-quiz:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
    }
</style>
@endpush

@section('content')
    <a href="{{ route('siswa.courses.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to My Courses
    </a>

    {{-- Course Hero --}}
    <div class="course-hero shadow-sm mb-4">
        <div class="position-relative" style="z-index: 1;">
            <h3><i class="fas fa-book me-2"></i>{{ $course->title }}</h3>
            <div class="hero-tentor">
                <i class="fas fa-chalkboard-teacher me-1"></i>{{ $course->tentor->name ?? 'Tentor' }}
            </div>
            @if ($course->description)
                <div class="hero-desc">{{ $course->description }}</div>
            @endif
            <div class="hero-stats">
                <div class="hero-stat">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ $course->modules->count() }} Modules</span>
                </div>
                <div class="hero-stat">
                    <i class="fas fa-question-circle"></i>
                    <span>{{ $course->quizzes->count() }} Quizzes</span>
                </div>
            </div>
        </div>
        <i class="fas fa-graduation-cap hero-icon"></i>
    </div>

    {{-- Module List --}}
    <div class="row g-3" id="moduleList">
        @forelse ($course->modules as $index => $module)
            <div class="col-12 module-entry">
                <div class="module-card shadow-sm">
                    <div class="module-header" data-target="module-body-{{ $module->id }}">
                        <div class="module-num">{{ $index + 1 }}</div>
                        <div class="module-title">{{ $module->title }}</div>
                        <i class="fas fa-chevron-down module-toggle-icon"></i>
                    </div>
                    <div class="module-body" id="module-body-{{ $module->id }}">
                        @if ($module->video_url)
                            <div class="video-wrapper">
                                <div class="ratio ratio-16x9">
                                    @php
                                        $videoUrl = $module->video_url;
                                        $embedUrl = $videoUrl;
                                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                        } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches)) {
                                            $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                                        }
                                    @endphp
                                    <iframe src="{{ $embedUrl }}"
                                            title="{{ $module->title }}"
                                            allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            style="border: none; border-radius: 12px;">
                                    </iframe>
                                </div>
                            </div>
                        @else
                            <div class="text-muted mb-3" style="font-size: 0.88rem; font-style: italic;">
                                <i class="fas fa-video-slash me-1"></i>No video available for this module.
                            </div>
                        @endif

                        @if ($module->pdf_path)
                            <a href="{{ asset('storage/' . $module->pdf_path) }}"
                               class="btn-pdf"
                               target="_blank"
                               download>
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="content-card shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-layer-group" style="font-size: 3rem;"></i>
                            <h6 style="color: #1e3c72; font-weight: 700;">No Materials Yet</h6>
                            <p style="color: #a0aec0; font-size: 0.9rem;">The tentor hasn't added any modules to this course yet. Please check back later.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Quiz List --}}
    <div class="mt-5 mb-2">
        <h5 class="fw-bold text-dark mb-3">
            <i class="fas fa-question-circle me-2" style="color: #f59e0b;"></i>Course Quizzes
        </h5>

        @forelse ($course->quizzes as $quiz)
            <div class="quiz-card shadow-sm mb-3">
                <div class="quiz-body">
                    <div class="quiz-icon">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div class="quiz-info flex-grow-1">
                        <h6>{{ $quiz->title }}</h6>
                        <div class="quiz-meta">
                            <span><i class="fas fa-list"></i>{{ $quiz->questions->count() }} Questions</span>
                            <span><i class="fas fa-clock"></i>{{ $quiz->time_limit }} min</span>
                            @php
                                $attempted = $quiz->attempts()->where('siswa_id', $user->id)->first();
                            @endphp
                            @if ($attempted)
                                <span class="text-{{ $attempted->score >= $quiz->passing_score ? 'success' : 'danger' }}">
                                    <i class="fas {{ $attempted->score >= $quiz->passing_score ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    Score: {{ $attempted->score }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($attempted)
                        <a href="{{ route('siswa.quiz-attempts.show', $attempted) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" style="font-size: 0.78rem;">
                            <i class="fas fa-history me-1"></i>Review
                        </a>
                    @endif
                    <a href="{{ route('siswa.quizzes.show', $quiz) }}" class="btn-start-quiz">
                        <i class="fas fa-play me-1"></i>{{ $attempted ? 'Retry' : 'Start' }}
                    </a>
                </div>
            </div>
        @empty
            <div class="content-card shadow-sm" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-question-circle" style="font-size: 3rem;"></i>
                        <h6 style="color: #1e3c72; font-weight: 700;">No Quizzes Yet</h6>
                        <p style="color: #a0aec0; font-size: 0.9rem;">The tentor hasn't added any quizzes for this course yet.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.module-header').forEach(function(header) {
        header.addEventListener('click', function() {
            var targetId = this.getAttribute('data-target');
            var body = document.getElementById(targetId);
            var icon = this.querySelector('.module-toggle-icon');

            if (body) {
                var isOpen = body.classList.contains('open');
                body.classList.toggle('open');
                if (icon) icon.classList.toggle('open');

                if (!isOpen) {
                    // Scroll to the module header smoothly
                    setTimeout(function() {
                        header.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                }
            }
        });
    });

    // Auto-open first module
    var firstBody = document.querySelector('.module-body');
    var firstIcon = document.querySelector('.module-toggle-icon');
    if (firstBody) {
        firstBody.classList.add('open');
        if (firstIcon) firstIcon.classList.add('open');
    }
</script>
@endpush