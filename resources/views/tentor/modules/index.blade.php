@extends('layouts.dashboard')

@section('title', 'Modules - Eduria')
@section('page-title', 'Modules')

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Module List</span>
        </div>
        <div class="card-body p-0">
            @if ($modules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Module Title</th>
                                <th>Course</th>
                                <th>Video</th>
                                <th>PDF</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td class="fw-semibold">{{ $module->title }}</td>
                                    <td class="text-muted">{{ $module->course->title ?? '-' }}</td>
                                    <td>
                                        @if ($module->video_url)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check me-1"></i>Yes
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-times me-1"></i>No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($module->pdf_path)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check me-1"></i>Yes
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-times me-1"></i>No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $module->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('tentor.courses.show', $module->course_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-layer-group"></i>
                    <h6>No modules yet</h6>
                    <p>Modules will appear after you create a course and add modules to it.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
