@extends('layouts.dashboard')

@section('title', 'My Courses - Eduria')
@section('page-title', 'My Courses')

@push('styles')
<style>
    :root {
        --card-radius: 16px;
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
                    <i data-lucide="book" style="width:16px;height:16px;margin-right:8px;color:#4e73df;"></i>My Courses
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.88rem; margin-top: 4px;">
                    Manage your courses, modules, and students
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="result-badge">
                    <i data-lucide="book-open" style="width:16px;height:16px;"></i>
                    <span>Showing</span>
                    <span class="count-num">{{ $courses->count() }}</span>
                    <span>courses</span>
                </div>
                <a href="{{ route('tentor.courses.create') }}" class="btn btn-primary rounded-pill px-3" style="height: 40px; font-weight: 600; font-size: 0.88rem;">
                    <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Create Course
                </a>
            </div>
        </div>

        <div class="content-card shadow-sm" style="border-radius: var(--card-radius);">
            <div class="card-body p-4">
                <div class="search-wrapper">
                    <i data-lucide="search" class="search-icon" style="width:16px;height:16px;"></i>
                    <input type="text" class="search-input form-control" id="searchInput"
                           placeholder="Search course name..." autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4" id="courseGrid">
    @forelse ($courses as $course)
        <x-course-card :course="$course" :index="$loop->index" />
    @empty
        <div class="col-12">
            <div class="empty-state" style="padding: 40px 20px;">
                <div class="empty-state-icon">
                    <div class="empty-state-icon-wrap"><i data-lucide="book-open" style="width:16px;height:16px;"></i></div>
                </div>
                <h6>No courses yet</h6>
                <p>You haven't created any courses yet. Click "Create Course" to get started.</p>
                <a href="{{ route('tentor.courses.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                    <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Create Course
                </a>
            </div>
        </div>
    @endforelse
</div>

<div class="empty-state" id="searchEmpty" style="display: none; padding: 40px 20px;">
    <div class="empty-state-icon">
        <div class="empty-state-icon-wrap"><i data-lucide="search" style="width:16px;height:16px;"></i></div>
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
