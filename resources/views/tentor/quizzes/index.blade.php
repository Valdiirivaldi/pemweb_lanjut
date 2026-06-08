@extends('layouts.dashboard')

@section('title', 'Quizzes - Eduria')
@section('page-title', 'Quizzes')

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Quiz List</span>
            <a href="{{ route('tentor.quizzes.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Create Quiz
            </a>
        </div>
        <div class="card-body p-0">
            @if ($quizzes->count() > 0)
                <div class="table-responsive">
                    <table class="table-admin mb-0" data-sortable>
                        <thead>
                            <tr>
                                <th data-sort="title">Quiz Title</th>
                                <th data-sort="course">Course</th>
                                <th data-sort="questions">Questions</th>
                                <th data-sort="participants">Participants</th>
                                <th data-sort="time">Time Limit</th>
                                <th data-sort="created">Created</th>
                                <th style="width: 60px;">Actions</th>
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
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i data-lucide="more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px; border: none; padding: 6px; min-width: 160px;">
                                                <li>
                                                    <a href="{{ route('tentor.quizzes.attempts.index', $quiz->id) }}" class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="users" style="width:14px;height:14px;margin-right:8px;color:#27ae60;"></i>Attempts
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('tentor.quizzes.questions.index', $quiz->id) }}" class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="help-circle" style="width:14px;height:14px;margin-right:8px;color:#4e73df;"></i>Questions
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('tentor.quizzes.edit', $quiz->id) }}" class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="pencil" style="width:14px;height:14px;margin-right:8px;color:#f6c23e;"></i>Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('tentor.quizzes.destroy', $quiz->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item py-2 rounded-2 text-danger"
                                                                data-ajax-action="delete"
                                                                data-confirm="Yakin ingin menghapus quiz ini? Semua data soal dan attempt akan ikut terhapus.">
                                                            <i data-lucide="trash-2" style="width:14px;height:14px;margin-right:8px;"></i>Hapus
                                                        </button>
                                                    </form>
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
                    <div class="empty-state-icon-wrap"><i data-lucide="help-circle"></i></div>
                    <h6>No quizzes yet</h6>
                    <p>You haven't created any quizzes yet. Click "Create Quiz" to get started.</p>
                </div>
            @endif
        </div>
    </div>
@endsection


