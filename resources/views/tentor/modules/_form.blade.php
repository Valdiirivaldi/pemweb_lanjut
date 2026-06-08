@props(['module' => null, 'courses', 'selectedCourseId' => null, 'backUrl' => null])

@php
    $isEdit = !is_null($module);
    $courseId = old('course_id', $isEdit ? ($module->course_id ?? '') : ($selectedCourseId ?? ''));
@endphp

<div class="mb-3 form-floating-custom">
    <select id="course_id" name="course_id" required
            class="@error('course_id') is-invalid @enderror{{ $courseId ? ' has-value' : '' }}">
        <option value=""></option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}"
                {{ ($courseId == $course->id) ? 'selected' : '' }}>
                {{ $course->title }}
            </option>
        @endforeach
    </select>
    <label for="course_id"><i data-lucide="book" style="width:14px;height:14px;margin-right:6px;"></i>Course</label>
    @error('course_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-floating-custom">
    <input type="text" id="title" name="title"
           value="{{ old('title', $isEdit ? $module->title : '') }}"
           placeholder=" "
           class="@error('title') is-invalid @enderror" required>
    <label for="title"><i data-lucide="type" style="width:14px;height:14px;margin-right:6px;"></i>Module Title</label>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-floating-custom">
    <textarea id="content" name="content" placeholder=" "
              class="@error('content') is-invalid @enderror"
              style="height:120px;">{{ old('content', $isEdit ? $module->content : '') }}</textarea>
    <label for="content"><i data-lucide="align-left" style="width:14px;height:14px;margin-right:6px;"></i>Content</label>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">Optional. Type the lesson content manually.</small>
</div>

<div class="mb-3 form-floating-custom">
    <input type="url" id="video_url" name="video_url"
           value="{{ old('video_url', $isEdit ? $module->video_url : '') }}"
           placeholder=" "
           class="@error('video_url') is-invalid @enderror">
    <label for="video_url"><i data-lucide="video" style="width:14px;height:14px;margin-right:6px;"></i>Video URL</label>
    @error('video_url')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">Optional. Paste a YouTube or Vimeo video link.</small>
</div>

<div class="mb-3 form-floating-custom">
    <input type="url" id="link_url" name="link_url"
           value="{{ old('link_url', $isEdit ? $module->link_url : '') }}"
           placeholder=" "
           class="@error('link_url') is-invalid @enderror">
    <label for="link_url"><i data-lucide="link" style="width:14px;height:14px;margin-right:6px;"></i>Reference Link</label>
    @error('link_url')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">Optional. Any external resource link.</small>
</div>

<div class="mb-3">
    <label class="form-label-custom">
        <i data-lucide="file" style="width:14px;height:14px;margin-right:6px;color:#4e73df;"></i>File Attachments
    </label>

    @if ($isEdit && $module->files->isNotEmpty())
        <div class="mb-2">
            <div style="font-size:0.82rem; font-weight:600; color:#4a5568; margin-bottom:6px;">Current files:</div>
            @foreach ($module->files as $file)
                <div class="current-file d-inline-flex align-items-center gap-2 me-2 mb-1">
                    <i data-lucide="paperclip" style="width:16px;height:16px;"></i>
                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                        {{ $file->file_name }}
                    </a>
                    <form action="{{ route('tentor.modules.files.destroy', [$module->id, $file->id]) }}"
                          method="POST" class="d-inline" onsubmit="return confirmDelete(this, 'Delete this file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0 ms-1"
                                title="Delete file" style="line-height:1;">
                            <i data-lucide="x" style="width:16px;height:16px;"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <div class="file-upload-area" id="fileUploadArea">
        <div class="upload-icon"><i data-lucide="cloud-upload" style="width:16px;height:16px;"></i></div>
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
        <i data-lucide="arrow-left" style="width:14px;height:14px;margin-right:4px;"></i>{{ $isEdit ? 'Cancel' : 'Cancel' }}
    </a>
    <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
        <i data-lucide="check-circle" style="width:16px;height:16px;margin-right:8px;"></i>{{ $isEdit ? 'Update Module' : 'Save Module' }}
    </button>
</div>


