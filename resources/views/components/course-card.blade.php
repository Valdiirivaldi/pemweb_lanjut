@props(['course', 'index' => 0])

<div class="col-12 col-md-6 col-lg-4 card-entry" data-title="{{ strtolower($course->title) }}">
    <div class="course-card shadow-sm">
        <div class="card-img-top" style="background: linear-gradient(135deg, {{ $index % 2 === 0 ? '#1e3c72, #2a5298' : '#2a5298, #1e3c72' }});">
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
