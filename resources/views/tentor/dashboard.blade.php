@extends('layouts.dashboard')

@section('title', 'Tentor Dashboard - Eduria')
@section('page-title', 'Tentor Dashboard')

@push('styles')
<style>
    :root {
        --card-radius: 16px;
    }
</style>
@endpush

@section('content')
    {{-- Welcome Card --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4 animate-on-scroll"
                 style="background: linear-gradient(135deg, #2a5298, #1e3c72); color: #fff;">
                <div>
                    <i data-lucide="presentation" style="width:40px;height:40px;opacity:0.3;"></i>
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
                        <i data-lucide="book-open" style="width:16px;height:16px;"></i>
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
                        <i data-lucide="graduation-cap" style="width:16px;height:16px;"></i>
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
                        <i data-lucide="help-circle" style="width:16px;height:16px;"></i>
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
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h5 style="color: #1e3c72; font-weight: 700; margin: 0; font-size: 1.1rem;">
                <i data-lucide="book" style="width:16px;height:16px;margin-right:8px;color:#4e73df;"></i>My Courses
            </h5>
        </div>
        <a href="{{ route('tentor.courses.create') }}" class="btn btn-primary rounded-pill px-3" style="height: 38px; font-weight: 600; font-size: 0.85rem;">
            <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Create Course
        </a>
    </div>

    <div class="content-card shadow-sm animate-on-scroll delay-3">
        <div class="card-body p-4">
            <div class="search-wrapper mb-4">
                <i data-lucide="search" class="search-icon" style="width:16px;height:16px;"></i>
                <input type="text" class="search-input form-control" id="searchInput"
                       placeholder="Search course name..." autocomplete="off">
            </div>

            @if ($courses->count() > 0)
                <div class="row g-4" id="courseGrid">
                    @foreach ($courses as $course)
                        <x-course-card :course="$course" :index="$loop->index" />
                    @endforeach
                </div>

                <div class="empty-state" id="searchEmpty" style="display: none; padding: 20px;">
                    <div class="empty-state-icon-wrap"><i data-lucide="search" style="width:32px;height:32px;"></i></div>
                    <h6 style="color: #1e3c72; font-weight: 700;">No courses found</h6>
                    <p style="color: #a0aec0; font-size: 0.9rem;">No courses match your search. Try a different keyword.</p>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon-wrap"><i data-lucide="book-open" style="width:32px;height:32px;"></i></div>
                    <h6>No courses yet</h6>
                    <p>You haven't created any courses yet. Click "Create Course" to get started.</p>
                    <a href="{{ route('tentor.courses.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                        <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Create Course
                    </a>
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

    var searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    searchInput.addEventListener('input', function() {
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
