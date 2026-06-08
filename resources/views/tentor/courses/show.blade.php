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

    .course-hero .hero-desc {
        color: rgba(255,255,255,0.8);
        font-size: 0.88rem;
        max-width: 700px;
        line-height: 1.6;
        margin-top: 8px;
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

    .module-table-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    .module-table-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4f8;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .module-table-card .card-header .header-title {
        font-weight: 700;
        color: #1e3c72;
        font-size: 1rem;
    }

    .module-item {
        padding: 16px 22px;
        border-bottom: 1px solid #f5f7fa;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background 0.2s ease;
    }

    .module-item:last-child {
        border-bottom: none;
    }

    .module-item:hover {
        background: #fafbff;
    }

    .module-item .module-num {
        width: 34px;
        height: 34px;
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

    .module-item .module-info {
        flex: 1;
        min-width: 0;
    }

    .module-item .module-info .module-title {
        font-weight: 600;
        color: #1e3c72;
        font-size: 0.93rem;
    }

    .module-item .module-info .module-badges {
        display: flex;
        gap: 6px;
        margin-top: 4px;
        flex-wrap: wrap;
    }

    .module-item .module-info .module-badges .badge-tag {
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .module-item .module-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .module-item .module-actions .btn-sm-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.82rem;
    }

    .module-item .module-actions .btn-edit {
        background: rgba(78,115,223,0.08);
        color: #4e73df;
        border: 1px solid rgba(78,115,223,0.12);
    }

    .module-item .module-actions .btn-edit:hover {
        background: #4e73df;
        color: #fff;
    }

    .module-item .module-actions .btn-delete {
        background: rgba(231,76,60,0.08);
        color: #e74c3c;
        border: 1px solid rgba(231,76,60,0.12);
    }

    .module-item .module-actions .btn-delete:hover {
        background: #e74c3c;
        color: #fff;
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
</style>
@endpush

@section('content')
    <a href="{{ route('tentor.courses.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:14px;height:14px;margin-right:4px;"></i> Back to My Courses
    </a>

    <div class="course-hero shadow-sm mb-4">
        <div class="position-relative" style="z-index: 1;">
            <h3><i data-lucide="book" style="width:16px;height:16px;margin-right:8px;"></i>{{ $course->title }}</h3>
            @if ($course->description)
                <div class="hero-desc">{{ $course->description }}</div>
            @endif
            <div class="hero-stats">
                <div class="hero-stat">
                    <i data-lucide="layers" style="width:16px;height:16px;"></i>
                    <span>{{ $course->modules->count() }} Modules</span>
                </div>
                <div class="hero-stat">
                    <i data-lucide="help-circle" style="width:16px;height:16px;"></i>
                    <span>{{ $course->quizzes->count() }} Quizzes</span>
                </div>
                <div class="hero-stat">
                    <i data-lucide="users" style="width:16px;height:16px;"></i>
                    <span>{{ $course->students->count() }} Students</span>
                </div>
            </div>
        </div>
        <i data-lucide="graduation-cap" class="hero-icon" style="width:48px;height:48px;opacity:0.15;"></i>
    </div>

    <div class="module-table-card shadow-sm">
        <div class="card-header">
            <span class="header-title"><i data-lucide="layers" style="width:16px;height:16px;margin-right:8px;color:#4e73df;"></i>Course Materials</span>
            <a href="{{ route('tentor.modules.create', ['course_id' => $course->id]) }}" class="btn btn-primary rounded-pill px-3" style="height: 36px; font-size: 0.82rem; font-weight: 600;">
                <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Add Module
            </a>
        </div>
        <div class="card-body p-0">
            @forelse ($course->modules as $index => $module)
                <div class="module-item">
                    <div class="module-num">{{ $index + 1 }}</div>
                    <div class="module-info">
                        <div class="module-title">{{ $module->title }}</div>
                        <div class="module-badges">
                            @if ($module->content)
                                <span class="badge-tag" style="background: rgba(78,115,223,0.08); color: #4e73df;">
                                    <i data-lucide="align-left" style="width:12px;height:12px;margin-right:4px;"></i>Content
                                </span>
                            @endif
                            @if ($module->video_url)
                                <span class="badge-tag" style="background: rgba(231,76,60,0.08); color: #e74c3c;">
                                    <i data-lucide="video" style="width:12px;height:12px;margin-right:4px;"></i>Video
                                </span>
                            @endif
                            @if ($module->link_url)
                                <span class="badge-tag" style="background: rgba(22,160,133,0.08); color: #16a085;">
                                    <i data-lucide="link" style="width:12px;height:12px;margin-right:4px;"></i>Link
                                </span>
                            @endif
                            @php $fileCount = $module->files->count() + ($module->pdf_path ? 1 : 0); @endphp
                            @if ($fileCount > 0)
                                <span class="badge-tag" style="background: rgba(243,156,18,0.08); color: #e67e22;">
                                    <i data-lucide="file" style="width:12px;height:12px;margin-right:4px;"></i>{{ $fileCount }} File(s)
                                </span>
                            @endif
                            @if ($module->submissions_count > 0)
                                <span class="badge-tag" style="background: rgba(22,160,133,0.08); color: #16a085;">
                                    <i data-lucide="upload" style="width:12px;height:12px;margin-right:4px;"></i>{{ $module->submissions_count }} Submission(s)
                                </span>
                            @endif
                        </div>
                        @if ($fileCount > 0)
                            <div class="mt-2" style="font-size:0.8rem;">
                                @if ($module->pdf_path)
                                    <div class="d-inline-flex align-items-center gap-1 me-3">
                                        <i data-lucide="paperclip" class="text-muted" style="width:14px;height:14px;"></i>
                                        <a href="{{ asset('storage/' . $module->pdf_path) }}" target="_blank" class="text-decoration-none">
                                            {{ basename($module->pdf_path) }}
                                        </a>
                                    </div>
                                @endif
                                @foreach ($module->files as $file)
                                    <div class="d-inline-flex align-items-center gap-1 me-3">
                                        <i data-lucide="paperclip" class="text-muted" style="width:14px;height:14px;"></i>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-decoration-none">
                                            {{ $file->file_name }}
                                        </a>
                                        <form action="{{ route('tentor.modules.files.destroy', [$module->id, $file->id]) }}"
                                              method="POST" class="d-inline" onsubmit="return confirm('Delete this file?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0"
                                                    title="Delete file" style="line-height:1;">
                                                <i data-lucide="x" style="width:12px;height:12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="module-actions">
                        <a href="{{ route('tentor.modules.submissions.index', $module->id) }}"
                           class="btn btn-sm btn-outline-info rounded-pill px-3 me-1"
                           style="font-size:0.78rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                            <i data-lucide="upload" style="width:14px;height:14px;margin-right:4px;"></i>{{ $module->submissions_count }}
                        </a>
                        <a href="{{ route('tentor.modules.edit', $module->id) }}" class="btn-sm-icon btn-edit" title="Edit Module">
                            <i data-lucide="pen" style="width:16px;height:16px;"></i>
                        </a>
                        <form action="{{ route('tentor.modules.destroy', $module->id) }}" method="POST" class="d-inline delete-module-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm-icon btn-delete" title="Delete Module" onclick="return confirm('Delete this module? This action cannot be undone.');">
                                <i data-lucide="trash-2" style="width:16px;height:16px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding: 40px 20px;">
                    <div class="empty-state-icon-wrap"><i data-lucide="layers" style="width:32px;height:32px;"></i></div>
                    <h6>No materials yet</h6>
                    <p>You haven't added any modules to this course. Click "Add Module" to get started.</p>
                    <a href="{{ route('tentor.modules.create', ['course_id' => $course->id]) }}" class="btn btn-primary rounded-pill px-4 mt-2" style="font-size: 0.88rem;">
                        <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Add Module
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
    if(typeof lucide!=='undefined')lucide.createIcons();
</script>
@endpush
