@extends('layouts.dashboard')

@section('title', 'Edit Module - Eduria')
@section('page-title', 'Edit Module')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}"
       class="nav-link {{ request()->routeIs('tentor.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('tentor.courses.index') }}"
       class="nav-link {{ request()->routeIs('tentor.courses.*') ? 'active' : '' }}">
        <i class="fas fa-book"></i>My Courses
    </a>
    <a href="{{ route('tentor.modules.index') }}"
       class="nav-link {{ request()->routeIs('tentor.modules.*') ? 'active' : '' }}">
        <i class="fas fa-layer-group"></i>Modules
    </a>
    <a href="{{ route('tentor.quizzes.index') }}"
       class="nav-link {{ request()->routeIs('tentor.quizzes.*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i>Quizzes
    </a>
    <a href="{{ route('tentor.students.index') }}"
       class="nav-link {{ request()->routeIs('tentor.students.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>Participants
    </a>
    <a href="{{ route('profile') }}"
       class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    .form-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4f8;
        padding: 20px 24px;
        font-weight: 700;
        color: #1e3c72;
    }

    .form-card .card-body {
        padding: 24px;
    }

    .form-input {
        height: 48px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding-left: 16px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78,115,223,0.12);
        outline: none;
    }

    textarea.form-input {
        height: auto;
        resize: vertical;
        min-height: 140px;
        padding-top: 12px;
    }

    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23a0aec0' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
    }

    .form-label-custom {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.85rem;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label-custom .label-icon {
        color: #4e73df;
    }

    .form-hint {
        font-size: 0.78rem;
        color: #a0aec0;
        margin-top: 4px;
    }

    .btn-primary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .btn-secondary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 600;
    }

    .file-upload-area {
        border: 2px dashed #d0d9e8;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #fafbff;
    }

    .file-upload-area:hover {
        border-color: #4e73df;
        background: #f0f4ff;
    }

    .file-upload-area .upload-icon {
        font-size: 2rem;
        color: #4e73df;
        margin-bottom: 8px;
    }

    .file-upload-area .upload-text {
        color: #4a5568;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .file-upload-area .upload-hint {
        color: #a0aec0;
        font-size: 0.8rem;
        margin-top: 4px;
    }

    .file-upload-area.has-file {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .current-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 10px;
        background: #f0f4ff;
        color: #4e73df;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 12px;
    }

    .current-file a {
        color: #4e73df;
        text-decoration: none;
    }

    .current-file a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <a href="{{ route('tentor.courses.show', $module->course_id) }}"
               class="back-link d-inline-flex align-items-center gap-2 text-decoration-none mb-3"
               style="color: #718096; font-size: 0.88rem; font-weight: 500; padding: 8px 16px; border-radius: 10px; transition: all 0.25s ease;">
                <i class="fas fa-arrow-left"></i> Back to Course
            </a>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle me-1"></i> Please fix the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-edit me-2" style="color: #4e73df;"></i>Edit Module: {{ $module->title }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.modules.update', $module->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="course_id" class="form-label-custom">
                                <i class="fas fa-book label-icon"></i>Course
                            </label>
                            <select class="form-control form-input @error('course_id') is-invalid @enderror"
                                    id="course_id" name="course_id" required>
                                <option value="">-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ (old('course_id', $module->course_id) == $course->id) ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label-custom">
                                <i class="fas fa-heading label-icon"></i>Module Title
                            </label>
                            <input type="text" class="form-control form-input @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $module->title) }}"
                                   placeholder="e.g., Introduction to Variables" required>
                            @error('title')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label-custom">
                                <i class="fas fa-align-left label-icon"></i>Content
                            </label>
                            <textarea class="form-control form-input @error('content') is-invalid @enderror"
                                      id="content" name="content"
                                      placeholder="Write the module content here...">{{ old('content', $module->content) }}</textarea>
                            @error('content')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <div class="form-hint">Optional. Type the lesson content manually.</div>
                        </div>

                        <div class="mb-4">
                            <label for="video_url" class="form-label-custom">
                                <i class="fas fa-video label-icon"></i>Video URL
                            </label>
                            <input type="url" class="form-control form-input @error('video_url') is-invalid @enderror"
                                   id="video_url" name="video_url" value="{{ old('video_url', $module->video_url) }}"
                                   placeholder="e.g., https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <div class="form-hint">Optional. Paste a YouTube or Vimeo video link.</div>
                        </div>

                        <div class="mb-4">
                            <label for="link_url" class="form-label-custom">
                                <i class="fas fa-link label-icon"></i>Reference Link
                            </label>
                            <input type="url" class="form-control form-input @error('link_url') is-invalid @enderror"
                                   id="link_url" name="link_url" value="{{ old('link_url', $module->link_url) }}"
                                   placeholder="e.g., https://example.com/reference">
                            @error('link_url')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <div class="form-hint">Optional. Any external resource link.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">
                                <i class="fas fa-file label-icon"></i>File Attachment
                            </label>

                            @if ($module->pdf_path)
                                <div class="current-file">
                                    <i class="fas fa-paperclip"></i>
                                    Current file:
                                    <a href="{{ asset('storage/' . $module->pdf_path) }}" target="_blank">
                                        {{ basename($module->pdf_path) }}
                                    </a>
                                </div>
                            @endif

                            <div class="file-upload-area" id="fileUploadArea">
                                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <div class="upload-text">
                                    {{ $module->pdf_path ? 'Click to replace file' : 'Click to upload or drag a file here' }}
                                </div>
                                <div class="upload-hint">Supported: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR (max 100MB)</div>
                            </div>
                            <input type="file" class="d-none" id="file" name="file"
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar">
                            @error('file')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <div class="form-hint">Leave empty to keep the current file.</div>
                        </div>

                        <div class="d-flex gap-3 mt-4 pt-3 border-top">
                            <a href="{{ route('tentor.courses.show', $module->course_id) }}"
                               class="btn btn-outline-secondary btn-secondary-custom px-4">
                                <i class="fas fa-arrow-left me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
                                <i class="fas fa-check-circle me-2"></i>Update Module
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('fileUploadArea').addEventListener('click', function() {
        document.getElementById('file').click();
    });

    document.getElementById('file').addEventListener('change', function() {
        var area = document.getElementById('fileUploadArea');
        if (this.files && this.files.length > 0) {
            area.classList.add('has-file');
            area.querySelector('.upload-text').textContent = this.files[0].name;
            area.querySelector('.upload-hint').textContent = (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB';
        } else {
            area.classList.remove('has-file');
            area.querySelector('.upload-text').textContent = 'Click to upload or drag a file here';
            area.querySelector('.upload-hint').textContent = 'Supported: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR (max 100MB)';
        }
    });
</script>
@endpush