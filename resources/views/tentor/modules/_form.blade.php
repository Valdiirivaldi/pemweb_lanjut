@props(['module' => null, 'courses', 'selectedCourseId' => null, 'backUrl' => null])

@php
    $isEdit = !is_null($module);
@endphp

<div class="mb-4">
    <label for="course_id" class="form-label-custom">
        <i class="fas fa-book label-icon"></i>Course
    </label>
    <select class="form-control form-input @error('course_id') is-invalid @enderror"
            id="course_id" name="course_id" required>
        <option value="">-- Select Course --</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}"
                {{ (old('course_id', $isEdit ? $module->course_id : $selectedCourseId) == $course->id) ? 'selected' : '' }}>
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
           id="title" name="title" value="{{ old('title', $isEdit ? $module->title : '') }}"
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
              placeholder="Write the module content here... You can use plain text or HTML.">{{ old('content', $isEdit ? $module->content : '') }}</textarea>
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
           id="video_url" name="video_url" value="{{ old('video_url', $isEdit ? $module->video_url : '') }}"
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
           id="link_url" name="link_url" value="{{ old('link_url', $isEdit ? $module->link_url : '') }}"
           placeholder="e.g., https://example.com/reference">
    @error('link_url')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
    <div class="form-hint">Optional. Any external resource link.</div>
</div>

<div class="mb-4">
    <label class="form-label-custom">
        <i class="fas fa-file label-icon"></i>File Attachments
    </label>

    @if ($isEdit && $module->files->isNotEmpty())
        <div class="mb-2">
            <div style="font-size:0.82rem; font-weight:600; color:#4a5568; margin-bottom:6px;">Current files:</div>
            @foreach ($module->files as $file)
                <div class="current-file d-inline-flex align-items-center gap-2 me-2 mb-1">
                    <i class="fas fa-paperclip"></i>
                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                        {{ $file->file_name }}
                    </a>
                    <form action="{{ route('tentor.modules.files.destroy', [$module->id, $file->id]) }}"
                          method="POST" class="d-inline" onsubmit="return confirm('Delete this file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0 ms-1"
                                title="Delete file" style="line-height:1;">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <div class="file-upload-area" id="fileUploadArea">
        <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
        <div class="upload-text" id="uploadText">
            Click to choose files
        </div>
        <div class="upload-hint">Supported: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, JPG, PNG, GIF, MP4 (max 100MB)</div>
    </div>
    <input type="file" class="d-none" id="files" name="files[]" multiple
           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.jpg,.jpeg,.png,.gif,.mp4,.webm">
    @error('files')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
    @error('files.*')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
    <div class="form-hint">You can select multiple files at once. Existing files are kept unless deleted individually.</div>
</div>

<div class="d-flex gap-3 mt-4 pt-3 border-top">
    <a href="{{ $backUrl ?? '#' }}"
       class="btn btn-outline-secondary btn-secondary-custom px-4">
        <i class="fas fa-arrow-left me-1"></i>{{ $isEdit ? 'Cancel' : 'Cancel' }}
    </a>
    <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
        <i class="fas fa-check-circle me-2"></i>{{ $isEdit ? 'Update Module' : 'Save Module' }}
    </button>
</div>
