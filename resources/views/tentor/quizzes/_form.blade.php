@props(['quiz' => null, 'courses'])

@php
    $isEdit = !is_null($quiz);
@endphp

<div class="mb-4">
    <label for="course_id" class="form-label-custom">
        <i class="fas fa-book me-1" style="color: #4e73df;"></i>Select Course
    </label>
    <div class="input-wrap">
        <i class="fas fa-folder-open icon-input"></i>
        <select class="form-control form-input @error('course_id') is-invalid @enderror"
                id="course_id"
                name="course_id"
                required>
            <option value="">-- Select Course --</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" {{ (old('course_id', $isEdit ? $quiz->course_id : '') == $course->id) ? 'selected' : '' }}>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
    </div>
    @error('course_id')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
</div>

<div class="mb-4">
    <label for="title" class="form-label-custom">
        <i class="fas fa-heading me-1" style="color: #4e73df;"></i>Quiz Title
    </label>
    <div class="input-wrap">
        <i class="fas fa-question-circle icon-input"></i>
        <input type="text"
               class="form-control form-input @error('title') is-invalid @enderror"
               id="title"
               name="title"
               value="{{ old('title', $isEdit ? $quiz->title : '') }}"
               placeholder="e.g., Midterm Exam"
               required>
    </div>
    @error('title')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
</div>

<div class="mb-4">
    <label for="time_limit" class="form-label-custom">
        <i class="fas fa-clock me-1" style="color: #4e73df;"></i>Time Limit (minutes)
    </label>
    <div class="input-wrap">
        <i class="fas fa-hourglass-half icon-input"></i>
        <input type="number"
               class="form-control form-input @error('time_limit') is-invalid @enderror"
               id="time_limit"
               name="time_limit"
               value="{{ old('time_limit', $isEdit ? $quiz->time_limit : 60) }}"
               min="1"
               max="999"
               required>
    </div>
    @error('time_limit')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
    <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">
        <i class="fas fa-info-circle me-1"></i>Quiz duration in minutes. Example: 60 = 1 hour.
    </small>
</div>

<div class="mb-4">
    <label for="passing_score" class="form-label-custom">
        <i class="fas fa-check-circle me-1" style="color: #4e73df;"></i>Passing Score (0-100)
    </label>
    <div class="input-wrap">
        <i class="fas fa-percent icon-input"></i>
        <input type="number"
               class="form-control form-input @error('passing_score') is-invalid @enderror"
               id="passing_score"
               name="passing_score"
               value="{{ old('passing_score', $isEdit ? $quiz->passing_score : 70) }}"
               min="0"
               max="100"
               required>
    </div>
    @error('passing_score')
        <small class="text-danger mt-1 d-block">{{ $message }}</small>
    @enderror
    <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">
        <i class="fas fa-info-circle me-1"></i>Minimum score required to pass. Default: 70.
    </small>
</div>

<div class="d-flex gap-3 mt-4">
    <a href="{{ route('tentor.quizzes.index') }}"
       class="btn btn-outline-secondary btn-secondary-custom px-4">
        <i class="fas fa-arrow-left me-1"></i>{{ $isEdit ? 'Cancel' : 'Back' }}
    </a>
    <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
        <i class="fas {{ $isEdit ? 'fa-save' : 'fa-check-circle' }} me-2"></i>{{ $isEdit ? 'Update Quiz' : 'Save Quiz' }}
    </button>
</div>
