@extends('layouts.dashboard')

@section('title', 'My Courses - Eduria')
@section('page-title', 'My Courses')

@push('styles')
<style>
    :root {
        --card-radius: 16px;
    }

    .course-card {
        border: none;
        border-radius: var(--card-radius);
        background: #fff;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }

    .course-card .card-img-top {
        height: 120px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .course-card .card-img-top::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .course-card .card-img-top .course-icon {
        font-size: 2.8rem;
        color: rgba(255,255,255,0.2);
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        transition: transform 0.4s ease;
    }

    .course-card:hover .card-img-top .course-icon {
        transform: scale(1.12) rotate(-4deg);
        color: rgba(255,255,255,0.4);
    }

    .course-card .card-body {
        padding: 20px 20px 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .course-card .card-body .course-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-card .card-body .course-description {
        font-size: 0.85rem;
        color: #718096;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
        flex: 1;
    }

    .course-card .card-body .course-meta {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #f0f4f8;
    }

    .course-card .card-body .course-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #718096;
    }

    .course-card .card-body .course-meta .meta-item i {
        color: #4e73df;
        width: 16px;
        text-align: center;
    }

    .course-card .card-footer {
        padding: 12px 20px 16px;
        background: transparent;
        border-top: none;
    }

    .course-card .card-footer .btn-action {
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .course-card .card-footer .btn-action.btn-primary {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        border: none;
    }

    .course-card .card-footer .btn-action.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(78,115,223,0.35);
    }

    .card-entry {
        opacity: 0;
        transform: translateY(24px);
        animation: cardIn 0.45s ease forwards;
    }

    .card-entry:nth-child(1) { animation-delay: 0.02s; }
    .card-entry:nth-child(2) { animation-delay: 0.06s; }
    .card-entry:nth-child(3) { animation-delay: 0.10s; }
    .card-entry:nth-child(4) { animation-delay: 0.14s; }
    .card-entry:nth-child(5) { animation-delay: 0.18s; }
    .card-entry:nth-child(6) { animation-delay: 0.22s; }
    .card-entry:nth-child(7) { animation-delay: 0.26s; }
    .card-entry:nth-child(8) { animation-delay: 0.30s; }
    .card-entry:nth-child(9) { animation-delay: 0.34s; }
    .card-entry:nth-child(10) { animation-delay: 0.38s; }

    @keyframes cardIn {
        to { opacity: 1; transform: translateY(0); }
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-input {
        height: 52px;
        border-radius: 14px;
        border: 2px solid #e2e8f0;
        padding-left: 48px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fff;
    }

    .search-wrapper .search-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78,115,223,0.12);
        outline: none;
    }

    .search-wrapper .search-icon {
        position: absolute;
        top: 50%;
        left: 18px;
        transform: translateY(-50%);
        color: #a0aec0;
        z-index: 2;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        color: #cbd5e0;
        margin-bottom: 16px;
    }

    .empty-state h6 {
        color: #1e3c72;
        font-weight: 700;
    }

    .empty-state p {
        color: #a0aec0;
        font-size: 0.9rem;
    }

    .result-badge {
        background: #f0f4ff;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
        color: #4a5568;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .result-badge .count-num {
        font-weight: 700;
        color: #4e73df;
        font-size: 1rem;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h4 style="color: #1e3c72; font-weight: 800; margin: 0; font-size: 1.3rem;">
                    <i class="fas fa-book me-2" style="color: #4e73df;"></i>My Courses
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.88rem; margin-top: 4px;">
                    Manage your courses, modules, and students
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="result-badge">
                    <i class="fas fa-book-open"></i>
                    <span>Showing</span>
                    <span class="count-num">{{ $courses->count() }}</span>
                    <span>courses</span>
                </div>
                <a href="{{ route('tentor.courses.create') }}" class="btn btn-primary rounded-pill px-3" style="height: 40px; font-weight: 600; font-size: 0.88rem;">
                    <i class="fas fa-plus me-1"></i>Create Course
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-1"></i> {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="content-card shadow-sm" style="border-radius: var(--card-radius);">
            <div class="card-body p-4">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input form-control" id="searchInput"
                           placeholder="Search course name..." autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4" id="courseGrid">
    @forelse ($courses as $course)
        <div class="col-12 col-md-6 col-lg-4 card-entry" data-title="{{ strtolower($course->title) }}">
            <div class="course-card shadow-sm">
                <div class="card-img-top" style="background: linear-gradient(135deg, {{ $loop->index % 2 === 0 ? '#1e3c72, #2a5298' : '#2a5298, #1e3c72' }});">
                    <i class="fas fa-graduation-cap course-icon"></i>
                </div>
                <div class="card-body">
                    <h6 class="course-title">{{ $course->title }}</h6>
                    @if ($course->description)
                        <p class="course-description">{{ $course->description }}</p>
                    @else
                        <p class="course-description" style="font-style: italic; opacity: 0.6;">No description</p>
                    @endif
                    <div class="course-meta">
                        <div class="d-flex gap-3">
                            <div class="meta-item">
                                <i class="fas fa-layer-group"></i>
                                <span>{{ $course->modules_count }} Modules</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-question-circle"></i>
                                <span>{{ $course->quizzes_count }} Quizzes</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $course->students_count }} Students</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('tentor.courses.show', $course->id) }}" class="btn-action btn-primary">
                        <i class="fas fa-eye"></i> Details
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state" style="padding: 40px 20px;">
                <div class="empty-state-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h6>No courses yet</h6>
                <p>You haven't created any courses yet. Click "Create Course" to get started.</p>
                <a href="{{ route('tentor.courses.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                    <i class="fas fa-plus me-1"></i>Create Course
                </a>
            </div>
        </div>
    @endforelse
</div>

<div class="empty-state" id="searchEmpty" style="display: none; padding: 40px 20px;">
    <div class="empty-state-icon">
        <i class="fas fa-search"></i>
    </div>
    <h6>No courses found</h6>
    <p>No courses match your search. Try a different keyword.</p>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        var q = this.value.toLowerCase().trim();
        var cards = document.querySelectorAll('#courseGrid .card-entry');
        var visibleCount = 0;

        cards.forEach(function(card) {
            var title = card.getAttribute('data-title');
            if (!q || title.indexOf(q) !== -1) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        var grid = document.getElementById('courseGrid');
        var empty = document.getElementById('searchEmpty');
        if (q && visibleCount === 0) {
            grid.style.display = 'none';
            empty.style.display = '';
        } else {
            grid.style.display = '';
            empty.style.display = 'none';
        }
    });
</script>
@endpush