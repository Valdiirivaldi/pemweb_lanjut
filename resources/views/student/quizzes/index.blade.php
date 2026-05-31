@extends('layouts.dashboard')

@section('title', 'Quiz History - Eduria')
@section('page-title', 'Quiz History')

@push('styles')
<style>
    .quiz-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
    }

    .quiz-stat-item {
        text-align: center;
        padding: 18px 12px;
        background: #f8faff;
        border-radius: 14px;
    }

    .quiz-stat-item .qstat-number {
        font-weight: 800;
        font-size: 1.5rem;
        color: #1e3c72;
    }

    .quiz-stat-item .qstat-label {
        font-size: 0.78rem;
        color: #a0aec0;
        margin-top: 2px;
    }

    .table-quiz {
        margin-bottom: 0;
    }

    .table-quiz thead th {
        border-top: none;
        font-weight: 700;
        font-size: 0.8rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 2px solid #e9edf4;
    }

    .table-quiz tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f4f8;
        font-size: 0.9rem;
        color: #4a5568;
    }

    .table-quiz tbody tr:hover {
        background: #f8faff;
    }

    .score-badge {
        padding: 4px 14px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
    }

    .score-pass {
        background: #c6f6d5;
        color: #276749;
    }

    .score-fail {
        background: #fed7d7;
        color: #9b2c2c;
    }
</style>
@endpush

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span><i class="fas fa-history me-2" style="color: #4e73df;"></i>Quiz History</span>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-weight: 600;">
                {{ $quizAttempts->count() }} quizzes
            </span>
        </div>
        <div class="card-body">
            @if ($quizAttempts->count() > 0)
                @php
                    $passed = $quizAttempts->filter(fn($a) => $a->certificate_path)->count();
                    $avgScore = $quizAttempts->avg('score');
                @endphp

                <div class="quiz-stats-grid mb-4">
                    <div class="quiz-stat-item">
                        <div class="qstat-number">{{ $quizAttempts->count() }}</div>
                        <div class="qstat-label">Total Taken</div>
                    </div>
                    <div class="quiz-stat-item">
                        <div class="qstat-number">{{ $passed }}</div>
                        <div class="qstat-label">Passed</div>
                    </div>
                    <div class="quiz-stat-item">
                        <div class="qstat-number">{{ number_format($avgScore, 1) }}</div>
                        <div class="qstat-label">Average Score</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-quiz">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Quiz Name</th>
                                <th>Course</th>
                                <th>Attempt</th>
                                <th>Score</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quizAttempts as $index => $attempt)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $attempt->quiz->title ?? '-' }}</td>
                                    <td>{{ $attempt->quiz->course->title ?? '-' }}</td>
                                    <td>#{{ $attempt->attempt_number ?? 1 }}</td>
                                    <td>
                                        <span class="score-badge {{ $attempt->certificate_path ? 'score-pass' : 'score-fail' }}">
                                            {{ $attempt->score }}
                                        </span>
                                    </td>
                                    <td style="color: #a0aec0; font-size: 0.85rem;">{{ $attempt->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-pencil-alt"></i>
                    <h6 style="color: #1e3c72; font-weight: 700;">No Quiz History</h6>
                    <p style="color: #a0aec0; font-size: 0.9rem;">You haven't taken any quizzes yet. Follow your courses to start taking quizzes.</p>
                    <a href="{{ route('siswa.courses.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 mt-2">
                        <i class="fas fa-book me-1"></i>View My Courses
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
