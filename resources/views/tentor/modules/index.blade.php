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
                    <table class="table-admin mb-0" data-sortable>
                        <thead>
                            <tr>
                                <th data-sort="title">Module Title</th>
                                <th data-sort="course">Course</th>
                                <th data-sort="video">Video</th>
                                <th data-sort="files">Files</th>
                                <th data-sort="created">Created</th>
                                <th style="width: 60px;">Actions</th>
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
                                                <i data-lucide="check" style="width:12px;height:12px;margin-right:4px;"></i>Yes
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i data-lucide="x" style="width:12px;height:12px;margin-right:4px;"></i>No
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($module->files_count > 0 || $module->pdf_path)
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i data-lucide="check" style="width:12px;height:12px;margin-right:4px;"></i>{{ $module->files_count + ($module->pdf_path ? 1 : 0) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i data-lucide="x" style="width:12px;height:12px;margin-right:4px;"></i>0
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $module->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i data-lucide="more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px; border: none; padding: 6px; min-width: 160px;">
                                                <li>
                                                    <a href="{{ route('tentor.courses.show', $module->course_id) }}" class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="eye" style="width:14px;height:14px;margin-right:8px;color:#4e73df;"></i>Details
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon-wrap"><i data-lucide="layers"></i></div>
                    <h6>No modules yet</h6>
                    <p>Modules will appear after you create a course and add modules to it.</p>
                </div>
            @endif
        </div>
    </div>

@endsection