@extends('layouts.dashboard')

@section('title', 'Quizzes - Eduria')
@section('page-title', 'Quizzes')

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Quiz List</span>
            <a href="{{ route('tentor.quizzes.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="fas fa-plus me-1"></i>Create Quiz
            </a>
        </div>
        <div class="card-body p-0">
            @if ($quizzes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Quiz Title</th>
                                <th>Course</th>
                                <th>Questions</th>
                                <th>Participants</th>
                                <th>Time Limit</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quizzes as $quiz)
                                <tr>
                                    <td class="fw-semibold">{{ $quiz->title }}</td>
                                    <td class="text-muted">{{ $quiz->course->title ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $quiz->questions_count }} Questions
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ $quiz->attempts_count }} Participants
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $quiz->time_limit }} minutes</td>
                                    <td class="text-muted">{{ $quiz->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('tentor.quizzes.attempts.index', $quiz->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                <i class="fas fa-users me-1"></i>Attempts
                                            </a>
                                            <a href="{{ route('tentor.quizzes.questions.index', $quiz->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fas fa-eye me-1"></i>Questions
                                            </a>
                                            <a href="{{ route('tentor.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <form action="{{ route('tentor.quizzes.destroy', $quiz->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus quiz ini? Semua data soal dan attempt akan ikut terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="fas fa-trash me-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h6>No quizzes yet</h6>
                    <p>You haven't created any quizzes yet. Click "Create Quiz" to get started.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
