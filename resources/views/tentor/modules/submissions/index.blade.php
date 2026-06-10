@extends('layouts.dashboard')

@section('title', 'Submissions - ' . $module->title . ' - Eduria')
@section('page-title', 'Submissions: ' . $module->title)

@section('content')
    <a href="{{ route('tentor.courses.show', $module->course_id) }}"
       class="back-link d-inline-flex align-items-center gap-2 text-decoration-none mb-3"
       style="color:#718096;font-size:0.88rem;font-weight:500;padding:8px 16px;border-radius:10px;">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Course
    </a>

    <div class="content-card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i data-lucide="upload" style="width:16px;height:16px;margin-right:8px;color:#4e73df;"></i>Student Submissions</span>
            <span class="badge bg-primary rounded-pill">{{ $submissions->count() }} submitted</span>
        </div>
        <div class="card-body p-0">
            @if ($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table-admin mb-0 animate-rows" data-sortable>
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th data-sort="student">Student</th>
                                <th data-sort="file">File</th>
                                <th data-sort="link">Link</th>
                                <th data-sort="notes">Notes</th>
                                <th data-sort="submitted">Submitted</th>
                                <th style="width: 60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $i => $sub)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $sub->siswa->name ?? '-' }}</td>
                                    <td>
                                        @if ($sub->file_path)
                                            <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank">
                                                <i data-lucide="file" style="width:12px;height:12px;margin-right:4px;"></i>{{ $sub->file_name ?? 'File' }}
                                            </a>
                                        @else
                                            <span class="text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sub->link_url)
                                            <a href="{{ $sub->link_url }}" target="_blank">
                                                <i data-lucide="external-link" style="width:12px;height:12px;margin-right:4px;"></i>Link
                                            </a>
                                        @else
                                            <span class="text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $sub->notes ? Str::limit($sub->notes, 40) : '&mdash;' }}
                                    </td>
                                    <td class="text-muted">{{ $sub->submitted_at ? $sub->submitted_at->format('d M Y H:i') : '&mdash;' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i data-lucide="more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px; border: none; padding: 6px; min-width: 160px;">
                                                <li>
                                                    <a href="{{ route('tentor.modules.submissions.show', [$module->id, $sub->id]) }}"
                                                       class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="eye" style="width:14px;height:14px;margin-right:8px;color:#4e73df;"></i>Detail
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
                    <div class="empty-state-icon-wrap"><i data-lucide="upload" style="width:32px;height:32px;"></i></div>
                    <h6>No submissions yet</h6>
                    <p>No student has submitted this assignment.</p>
                </div>
            @endif
        </div>
    </div>
@endsection


