@props(['course', 'index' => 0, 'routeName' => 'tentor.courses.show'])

<div class="col-12 col-md-6 col-lg-4 card-entry" data-title="{{ strtolower($course->title) }}">
    <div class="course-card shadow-sm">
        <div class="card-img-top" style="background: linear-gradient(135deg, {{ $index % 2 === 0 ? '#1e3c72, #2a5298' : '#2a5298, #1e3c72' }});">
            <i data-lucide="graduation-cap" class="course-icon" style="width:40px;height:40px;color:rgba(255,255,255,0.2);"></i>
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
                        <i data-lucide="layers" style="width:14px;height:14px;"></i>
                        <span>{{ $course->modules_count }} Modules</span>
                    </div>
                    <div class="meta-item">
                        <i data-lucide="help-circle" style="width:14px;height:14px;"></i>
                        <span>{{ $course->quizzes_count }} Quizzes</span>
                    </div>
                    <div class="meta-item">
                        <i data-lucide="users" style="width:14px;height:14px;"></i>
                        <span>{{ $course->students_count }} Students</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route($routeName, $course->id) }}" class="btn-action btn-primary">
                <i data-lucide="eye" style="width:14px;height:14px;margin-right:6px;"></i> Details
            </a>
        </div>
    </div>
</div>
