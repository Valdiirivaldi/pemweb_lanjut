@extends('layouts.dashboard')

@section('title', 'Participants - Eduria')
@section('page-title', 'Participants')

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Enrolled Students</span>
            <span class="badge bg-primary rounded-pill">{{ $students->count() }} Students</span>
        </div>
        <div class="card-body p-0">
            @if ($students->count() > 0)
                <div class="table-responsive">
                    <table class="table-admin mb-0" data-sortable>
                        <thead>
                            <tr>
                                <th data-sort="name">Name</th>
                                <th data-sort="email">Email</th>
                                <th data-sort="course">Course</th>
                                <th data-sort="joined">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td class="fw-semibold">{{ $student->name }}</td>
                                    <td class="text-muted">{{ $student->email }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $student->enrolled_course }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $student->pivot->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon-wrap"><i data-lucide="users"></i></div>
                    <h6>No participants yet</h6>
                    <p>No students are enrolled in your courses yet. Student enrollment can be managed by Admin.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endpush
