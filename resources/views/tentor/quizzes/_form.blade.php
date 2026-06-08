@props(['quiz' => null, 'courses'])

@php
    $isEdit = !is_null($quiz);
    $quizCourseId = old('course_id', $isEdit ? ($quiz->course_id ?? '') : '');
    $quizTitle = old('title', $isEdit ? ($quiz->title ?? '') : '');
    $quizTimeLimit = old('time_limit', $isEdit ? ($quiz->time_limit ?? 60) : 60);
    $quizPassingScore = old('passing_score', $isEdit ? ($quiz->passing_score ?? 70) : 70);
@endphp

<div class="mb-3 form-floating-custom">
    <select id="course_id" name="course_id" required
            class="@error('course_id') is-invalid @enderror{{ $quizCourseId ? ' has-value' : '' }}">
        <option value=""></option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" {{ $quizCourseId == $course->id ? 'selected' : '' }}>
                {{ $course->title }}
            </option>
        @endforeach
    </select>
    <label for="course_id"><i data-lucide="book" style="width:14px;height:14px;margin-right:6px;color:#4e73df;"></i>Select Course</label>
    @error('course_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-floating-custom">
    <input type="text" id="title" name="title"
           value="{{ $quizTitle }}"
           placeholder=" "
           class="@error('title') is-invalid @enderror" required>
    <label for="title"><i data-lucide="help-circle" style="width:14px;height:14px;margin-right:6px;color:#4e73df;"></i>Quiz Title</label>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-floating-custom">
    <input type="number" id="time_limit" name="time_limit"
           value="{{ $quizTimeLimit }}"
           placeholder=" "
           min="1" max="999"
           class="@error('time_limit') is-invalid @enderror" required>
    <label for="time_limit"><i data-lucide="clock" style="width:14px;height:14px;margin-right:6px;color:#4e73df;"></i>Time Limit (minutes)</label>
    @error('time_limit')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">
        <i data-lucide="info" style="width:12px;height:12px;margin-right:4px;"></i>Quiz duration in minutes. Example: 60 = 1 hour.
    </small>
</div>

<div class="mb-3 form-floating-custom">
    <input type="number" id="passing_score" name="passing_score"
           value="{{ $quizPassingScore }}"
           placeholder=" "
           min="0" max="100"
           class="@error('passing_score') is-invalid @enderror" required>
    <label for="passing_score"><i data-lucide="check-circle" style="width:14px;height:14px;margin-right:6px;color:#4e73df;"></i>Passing Score (0-100)</label>
    @error('passing_score')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">
        <i data-lucide="info" style="width:12px;height:12px;margin-right:4px;"></i>Minimum score required to pass. Default: 70.
    </small>
</div>

<div class="d-flex gap-3 mt-4">
    <a href="{{ route('tentor.quizzes.index') }}"
       class="btn btn-outline-secondary btn-secondary-custom px-4">
        <i data-lucide="arrow-left" style="width:14px;height:14px;margin-right:4px;"></i>{{ $isEdit ? 'Cancel' : 'Back' }}
    </a>
    <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
        <i data-lucide="{{ $isEdit ? 'save' : 'check-circle' }}" style="width:16px;height:16px;margin-right:8px;"></i>{{ $isEdit ? 'Update Quiz' : 'Save Quiz' }}
    </button>
</div>

@push('scripts')
<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endpush
