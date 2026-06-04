@extends('layouts.dashboard')

@section('title', 'Submissions - ' . $module->title . ' - Eduria')
@section('page-title', 'Submissions: ' . $module->title)

@section('content')
    <a href="{{ route('tentor.courses.show', $module->course_id) }}"
       class="back-link d-inline-flex align-items-center gap-2 text-decoration-none mb-3"
       style="color:#718096;font-size:0.88rem;font-weight:500;padding:8px 16px;border-radius:10px;">
        <i class="fas fa-arrow-left"></i> Back to Course
    </a>

    <div class="content-card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fas fa-upload me-2" style="color:#4e73df;"></i>Student Submissions</span>
            <span class="badge bg-primary rounded-pill">{{ $submissions->count() }} submitted</span>
        </div>
        <div class="card-body p-0">
            @if ($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>File</th>
                                <th>Link</th>
                                <th>Notes</th>
                                <th>Submitted</th>
                                <th class="text-end">Action</th>
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
                                                <i class="fas fa-file me-1"></i>{{ $sub->file_name ?? 'File' }}
                                            </a>
                                        @else
                                            <span class="text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sub->link_url)
                                            <a href="{{ $sub->link_url }}" target="_blank">
                                                <i class="fas fa-external-link-alt me-1"></i>Link
                                            </a>
                                        @else
                                            <span class="text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $sub->notes ? Str::limit($sub->notes, 40) : '&mdash;' }}
                                    </td>
                                    <td class="text-muted">{{ $sub->submitted_at ? $sub->submitted_at->format('d M Y H:i') : '&mdash;' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('tentor.modules.submissions.show', [$module->id, $sub->id]) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-upload" style="font-size:3rem;"></i>
                    <h6>No submissions yet</h6>
                    <p>No student has submitted this assignment.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
