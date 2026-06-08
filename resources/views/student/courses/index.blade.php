@extends('layouts.dashboard')

@section('title', 'My Courses - Eduria')
@section('page-title', 'My Courses')

@push('styles')
<style>
    :root {
        --card-radius: 16px;
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 1rem;
        pointer-events: none;
        transition: color 0.3s ease;
        z-index: 2;
    }

    .search-wrapper .search-input {
        height: 52px;
        border-radius: 14px;
        border: 2px solid #e9edf4;
        padding-left: 48px;
        padding-right: 120px;
        font-size: 0.95rem;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .search-wrapper .search-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78,115,223,0.12);
        outline: none;
    }

    .search-wrapper .search-actions {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        gap: 6px;
        z-index: 2;
    }

    .search-wrapper .search-actions .btn {
        height: 38px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }

    .search-wrapper .search-actions .btn-clear {
        background: #f1f4f9;
        color: #718096;
        border: none;
        width: 38px;
        justify-content: center;
        padding: 0;
        opacity: 0;
        visibility: hidden;
        transform: scale(0.8);
        transition: all 0.25s ease;
    }

    .search-wrapper .search-actions .btn-clear.show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    .search-wrapper .search-actions .btn-clear:hover {
        background: #e2e8f0;
        color: #4a5568;
    }

    .search-wrapper .search-actions .btn-search {
        background: #4e73df;
        color: #fff;
        border: none;
    }

    .search-wrapper .search-actions .btn-search:hover {
        background: #224abe;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(78,115,223,0.3);
    }

    .result-badge {
        font-size: 0.85rem;
        color: #718096;
        padding: 6px 16px;
        background: #f8fafc;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .result-badge .count-num {
        font-weight: 700;
        color: #4e73df;
        min-width: 20px;
        text-align: center;
    }

    .course-card {
        border: none;
        border-radius: var(--card-radius);
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
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
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.06) 0%, transparent 50%);
        pointer-events: none;
    }

    .course-card .card-img-top .course-icon {
        font-size: 2.8rem;
        color: rgba(255,255,255,0.85);
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        transition: all 0.4s ease;
        z-index: 1;
    }

    .course-card:hover .card-img-top .course-icon {
        transform: scale(1.12) rotate(-4deg);
        color: #fff;
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
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .course-card .card-body .course-description {
        font-size: 0.85rem;
        color: #718096;
        line-height: 1.6;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .course-card .card-body .course-meta {
        margin-top: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #a0aec0;
        padding-top: 12px;
        border-top: 1px solid #f0f4f8;
    }

    .course-card .card-body .course-meta i {
        color: #4e73df;
        font-size: 0.75rem;
        width: 16px;
        text-align: center;
    }

    .course-card .card-body .course-meta .tentor-name {
        color: #4a5568;
        font-weight: 500;
    }

    .course-card .card-footer {
        padding: 12px 20px 16px;
        background: transparent;
        border-top: 1px solid #f0f4f8;
    }

    .course-card .card-footer .btn-action {
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .course-card .card-footer .btn-action.btn-primary {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        box-shadow: 0 4px 12px rgba(78,115,223,0.25);
    }

    .course-card .card-footer .btn-action.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78,115,223,0.35);
    }

    .course-card .card-footer .btn-action.btn-primary.loading {
        pointer-events: none;
        opacity: 0.8;
    }

    .course-card .card-footer .btn-action.btn-outline-success {
        border: 1.5px solid #10b981;
        color: #059669;
        background: rgba(16,185,129,0.06);
    }

    .course-card .card-footer .btn-action.btn-outline-success:hover {
        background: #10b981;
        color: #fff;
    }

    .spinner-enroll {
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: none;
    }

    .btn-action.loading .spinner-enroll {
        display: inline-block;
    }

    .btn-action.loading .btn-text {
        display: none;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .toggle-catalog {
        background: #f8faff;
        border: 2px dashed #d0d9e8;
        border-radius: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 24px;
    }

    .toggle-catalog:hover {
        border-color: #4e73df;
        background: rgba(78,115,223,0.04);
    }

    .toggle-catalog .toggle-header {
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        user-select: none;
    }

    .toggle-catalog .toggle-header .toggle-label {
        font-weight: 700;
        color: #1e3c72;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .toggle-catalog .toggle-header .toggle-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #4e73df;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.35s ease;
    }

    .toggle-catalog .toggle-header .toggle-arrow {
        color: #a0aec0;
        font-size: 0.9rem;
        transition: transform 0.35s ease;
    }

    .toggle-catalog.open .toggle-header .toggle-arrow {
        transform: rotate(180deg);
    }

    .toggle-catalog .catalog-body {
        display: none;
        padding: 0 24px 24px;
        border-top: 1px solid #e9edf4;
    }

    .toggle-catalog.open .catalog-body {
        display: block;
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

    .hidden-card {
        display: none !important;
    }

    .empty-search-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-search-state .empty-icon {
        font-size: 3.5rem;
        color: #cbd5e0;
        margin-bottom: 12px;
        display: block;
    }

    .empty-search-state h6 {
        color: #1e3c72;
        font-weight: 700;
    }

    .empty-search-state p {
        color: #a0aec0;
        font-size: 0.88rem;
        max-width: 360px;
        margin: 0 auto;
    }

    .alert-flash {
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-16px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .section-divider {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 20px 0 16px;
        color: #a0aec0;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9edf4;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        {{-- Header --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h4 style="color: #1e3c72; font-weight: 800; margin: 0; font-size: 1.3rem;">
                    <i data-lucide="book" style="width:18px;height:18px;color:#4e73df;margin-right:8px;"></i>My Courses
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.88rem; margin-top: 4px;">
                    Manage your enrolled courses or discover new ones
                </p>
            </div>
            <div class="result-badge">
                <i data-lucide="book-open" style="width:16px;height:16px;"></i>
                <span>Showing</span>
                <span class="count-num" id="myCount">{{ $myCourses->count() }}</span>
                <span>courses</span>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="content-card shadow-sm" style="border-radius: var(--card-radius);">
            <div class="card-body p-4">
                <form action="{{ route('siswa.courses.index') }}" method="GET" id="searchForm">
                    <div class="search-wrapper">
                        <i data-lucide="search" class="search-icon" style="width:16px;height:16px;"></i>
                        <input type="text"
                               name="search"
                               class="search-input form-control"
                               id="searchInput"
                               placeholder="Search course name..."
                               value="{{ $search ?? '' }}"
                               autocomplete="off">
                        <div class="search-actions">
                            <button type="button" class="btn btn-clear" id="clearSearch" title="Hapus">
                                <i data-lucide="x" style="width:16px;height:16px;"></i>
                            </button>
                            <button type="submit" class="btn btn-search">
                                <i data-lucide="search" style="width:14px;height:14px;margin-right:4px;"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Toggle: Gabung Kelas Baru --}}
<div class="toggle-catalog shadow-sm" id="toggleCatalog">
    <div class="toggle-header" id="toggleTrigger">
        <div class="toggle-label">
            <div class="toggle-icon"><i data-lucide="plus-circle" style="width:18px;height:18px;"></i></div>
Join New Class
            @php
                $availableCount = $allCourses->count() - $myCourses->count();
            @endphp
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-size: 0.75rem; font-weight: 600;">
                {{ $availableCount }} available
            </span>
        </div>
        <i data-lucide="chevron-down" class="toggle-arrow" style="width:16px;height:16px;"></i>
    </div>
    <div class="catalog-body">
        @if ($allCourses->count() > 0)
            <div class="pt-3 pb-1">
                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                    <i data-lucide="info" style="width:14px;height:14px;margin-right:4px;color:#4e73df;"></i>
                    Browse all available courses and join the ones you're interested in.
                </p>
            </div>
            <div class="row g-3 mt-2" id="catalogGrid">
                @foreach ($allCourses as $course)
                    <div class="col-12 col-md-6 col-lg-4 catalog-card-wrap card-entry"
                         data-title="{{ strtolower($course->title) }}"
                         data-enrolled="{{ in_array($course->id, $enrolledIds) ? '1' : '0' }}">
                        <div class="course-card shadow-sm">
                            <div class="card-img-top" style="background: linear-gradient(135deg, {{ $loop->index % 2 === 0 ? '#1e3c72, #2a5298' : '#2a5298, #1e3c72' }});">
                                <i data-lucide="graduation-cap" class="course-icon" style="width:40px;height:40px;"></i>
                            </div>
                            <div class="card-body">
                                <h6 class="course-title">{{ $course->title }}</h6>
                                @if ($course->description)
                                    <p class="course-description">{{ $course->description }}</p>
                                @else
                                    <p class="course-description" style="font-style: italic; opacity: 0.6;">No description</p>
                                @endif
                                <div class="course-meta">
                                    <i data-lucide="presentation" style="width:14px;height:14px;"></i>
                                    <span class="tentor-name">{{ $course->tentor->name ?? 'Tentor' }}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if (in_array($course->id, $enrolledIds))
                                    <span class="btn-action" style="border: 1.5px solid #10b981; color: #059669; background: rgba(16,185,129,0.06); cursor: default;">
                                        <i data-lucide="check-circle" style="width:14px;height:14px;margin-right:4px;"></i> Enrolled
                                    </span>
                                @else
                                    <form action="{{ route('siswa.courses.enroll') }}" method="POST" class="enroll-form">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" class="btn-action btn-primary">
                                            <span class="spinner-enroll"></span>
                                            <span class="btn-text">
                                                <i data-lucide="plus-circle" style="width:14px;height:14px;margin-right:4px;"></i> Join Class
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="empty-search-state" id="catalogEmpty" style="display: none;">
                <i data-lucide="search" class="empty-icon" style="width:48px;height:48px;"></i>
                <h6>Course Not Found</h6>
                <p>No courses match your search.</p>
            </div>
        @else
            <div class="empty-state" style="padding: 20px 0;">
                <div class="empty-state-icon-wrap"><i data-lucide="book-open" style="width:32px;height:32px;"></i></div>
                <h6 style="font-size: 0.95rem;">No Courses Yet</h6>
                <p style="font-size: 0.85rem;">No courses are available at this time.</p>
            </div>
        @endif
    </div>
</div>

{{-- My Enrolled Courses --}}
<div class="section-divider">
    <i data-lucide="bookmark" style="width:16px;height:16px;color:#4e73df;"></i> My Enrolled Courses
</div>

<div class="row g-4" id="myCourseGrid">
    @forelse ($myCourses as $course)
        <div class="col-12 col-md-6 col-lg-4 my-card-wrap card-entry" data-title="{{ strtolower($course->title) }}">
            <div class="course-card shadow-sm">
                <div class="card-img-top" style="background: linear-gradient(135deg, {{ $loop->index % 2 === 0 ? '#1e3c72, #2a5298' : '#2a5298, #1e3c72' }});">
                    <i data-lucide="graduation-cap" class="course-icon" style="width:40px;height:40px;"></i>
                </div>
                <div class="card-body">
                    <h6 class="course-title">{{ $course->title }}</h6>
                    @if ($course->description)
                        <p class="course-description">{{ $course->description }}</p>
                    @else
                        <p class="course-description" style="font-style: italic; opacity: 0.6;">No description</p>
                    @endif
                    <div class="course-meta">
                        <i data-lucide="presentation" style="width:14px;height:14px;"></i>
                        <span class="tentor-name">{{ $course->tentor->name ?? 'Tentor' }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('siswa.courses.learn', $course->id) }}" class="btn-action btn-primary">
                        <i data-lucide="arrow-right" style="width:14px;height:14px;"></i> Enter Class
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state" style="padding: 40px 20px;">
                <div class="empty-state-icon-wrap"><i data-lucide="book-open" style="width:44px;height:44px;color:#cbd5e0;"></i></div>
                <h6 style="color: #1e3c72; font-weight: 700;">No Courses Yet</h6>
                <p style="color: #a0aec0; font-size: 0.9rem;">You haven't joined any courses yet. Click "Join New Class" above to get started!</p>
            </div>
        </div>
    @endforelse
</div>

<div class="empty-search-state" id="myEmpty" style="display: none;">
    <i data-lucide="search" class="empty-icon" style="width:48px;height:48px;"></i>
    <h6>Course Not Found</h6>
    <p>No courses match your search "<span id="mySearchQuery" style="font-weight: 600; color: #4e73df;"></span>".</p>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const toggleTrigger = document.getElementById('toggleTrigger');
        const toggleCatalog = document.getElementById('toggleCatalog');
        const myCards = document.querySelectorAll('.my-card-wrap');
        const catalogCards = document.querySelectorAll('.catalog-card-wrap');
        const myCount = document.getElementById('myCount');
        const myEmpty = document.getElementById('myEmpty');
        const catalogEmpty = document.getElementById('catalogEmpty');
        const mySearchQuery = document.getElementById('mySearchQuery');

        // Toggle catalog
        toggleTrigger.addEventListener('click', function() {
            toggleCatalog.classList.toggle('open');

            if (toggleCatalog.classList.contains('open')) {
                setTimeout(() => {
                    toggleCatalog.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        });

        // Search clear button
        function toggleClearBtn(value) {
            if (value.trim().length > 0) {
                clearBtn.classList.add('show');
            } else {
                clearBtn.classList.remove('show');
            }
        }

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            toggleClearBtn('');
            filterAll('');
            searchInput.focus();
        });

        // Filter function
        function filterAll(query) {
            const q = query.trim().toLowerCase();

            // Filter enrolled courses
            let visibleMy = 0;
            myCards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                if (title.includes(q)) {
                    card.classList.remove('hidden-card');
                    visibleMy++;
                } else {
                    card.classList.add('hidden-card');
                }
            });

            myCount.textContent = visibleMy;

            if (q.length > 0 && visibleMy === 0 && myCards.length > 0) {
                myEmpty.style.display = 'block';
                mySearchQuery.textContent = q;
            } else {
                myEmpty.style.display = 'none';
            }

            // Filter catalog cards
            if (catalogCards.length > 0) {
                let visibleCat = 0;
                catalogCards.forEach(card => {
                    const title = card.getAttribute('data-title') || '';
                    if (title.includes(q)) {
                        card.classList.remove('hidden-card');
                        visibleCat++;
                    } else {
                        card.classList.add('hidden-card');
                    }
                });

                if (q.length > 0 && visibleCat === 0) {
                    catalogEmpty.style.display = 'block';
                } else {
                    catalogEmpty.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('input', function() {
            toggleClearBtn(this.value);
            filterAll(this.value);
        });

        // Initial state
        toggleClearBtn(searchInput.value);
        if (searchInput.value.trim().length > 0) {
            filterAll(searchInput.value);
        }

        // Enroll button loading
        document.querySelectorAll('.enroll-form').forEach(form => {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('.btn-action');
                if (btn) {
                    btn.classList.add('loading');
                    btn.disabled = true;
                }
            });
        });

        // Auto-dismiss alerts
        document.querySelectorAll('.alert-flash').forEach(alert => {
            setTimeout(() => {
                const bs = bootstrap.Alert.getOrCreateInstance(alert);
                bs?.close();
            }, 5000);
        });
    })();
</script>
@endpush
