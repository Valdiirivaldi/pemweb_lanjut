@extends('layouts.dashboard')

@section('title', 'Submission Detail - Eduria')
@section('page-title', 'Submission Detail')

@section('content')
    <a href="{{ route('tentor.modules.submissions.index', $module->id) }}"
       class="back-link d-inline-flex align-items-center gap-2 text-decoration-none mb-3"
       style="color:#718096;font-size:0.88rem;font-weight:500;padding:8px 16px;border-radius:10px;">
        <i class="fas fa-arrow-left"></i> Back to Submissions
    </a>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span class="fw-bold">
                        <i class="fas fa-user me-2" style="color:#4e73df;"></i>{{ $submission->siswa->name }}
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless" style="font-size:0.9rem;">
                        <tr>
                            <th class="text-muted" style="width:140px;">Module</th>
                            <td>{{ $module->title }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Student</th>
                            <td>{{ $submission->siswa->name }} ({{ $submission->siswa->email }})</td>
                        </tr>
                        <tr>
                            <th class="text-muted">File</th>
                            <td>
                                @if ($submission->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank">
                                        <i class="fas fa-download me-1"></i>{{ $submission->file_name ?? 'Download' }}
                                    </a>
                                @else
                                    <span class="text-muted">No file uploaded</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Link</th>
                            <td>
                                @if ($submission->link_url)
                                    <a href="{{ $submission->link_url }}" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>{{ $submission->link_url }}
                                    </a>
                                @else
                                    <span class="text-muted">No link provided</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Notes</th>
                            <td>{{ $submission->notes ?? '&mdash;' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Submitted</th>
                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i:s') : '&mdash;' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
