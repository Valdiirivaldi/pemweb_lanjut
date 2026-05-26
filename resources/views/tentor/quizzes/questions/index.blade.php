@extends('layouts.dashboard')

@section('title', 'Questions - ' . $quiz->title . ' - Eduria')
@section('page-title', 'Questions: ' . $quiz->title)

@push('styles')
<style>
    .question-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    .question-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4f8;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .question-card .card-header .header-title {
        font-weight: 700;
        color: #1e3c72;
        font-size: 1rem;
    }
    .question-item {
        padding: 18px 22px;
        border-bottom: 1px solid #f5f7fa;
        transition: background 0.2s ease;
    }
    .question-item:last-child {
        border-bottom: none;
    }
    .question-item:hover {
        background: #fafbff;
    }
    .question-item .q-num {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .question-item .q-text {
        font-weight: 600;
        color: #1e3c72;
        font-size: 0.93rem;
    }
    .question-item .q-options {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }
    .question-item .q-options .opt {
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 8px;
        background: #f7fafc;
        color: #4a5568;
    }
    .question-item .q-options .opt.correct {
        background: rgba(16,185,129,0.12);
        color: #10b981;
        font-weight: 600;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #718096;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.25s ease;
        margin-bottom: 16px;
    }
    .back-link:hover {
        background: rgba(78,115,223,0.06);
        color: #4e73df;
    }
</style>
@endpush

@section('content')
    <a href="{{ route('tentor.quizzes.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Quizzes
    </a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-1"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="question-card shadow-sm">
        <div class="card-header">
            <span class="header-title">
                <i class="fas fa-question-circle me-2" style="color: #4e73df;"></i>
                {{ $questions->count() }} Questions
            </span>
            <a href="{{ route('tentor.quizzes.questions.create', $quiz->id) }}" class="btn btn-primary rounded-pill px-3" style="height: 36px; font-size: 0.82rem; font-weight: 600;">
                <i class="fas fa-plus me-1"></i>Add Question
            </a>
        </div>
        <div class="card-body p-0">
            @forelse ($questions as $index => $question)
                    <div class="question-item d-flex align-items-start gap-3">
                        @php $typeLabel = match($question->type) { 'single' => 'Single', 'multiple' => 'Multiple', 'true_false' => 'T/F' }; @endphp
                        @php $typeColor = match($question->type) { 'single' => '#4e73df', 'multiple' => '#f59e0b', 'true_false' => '#10b981' }; @endphp
                        <div class="q-num">{{ $index + 1 }}</div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="q-text">{{ $question->question_text }}</div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge rounded-pill" style="background: {{ $typeColor }}; font-size: 0.68rem; font-weight: 600; padding: 3px 10px;">{{ $typeLabel }}</span>
                            </div>
                            <div class="q-options">
                                @foreach ($question->options ?? [] as $key => $text)
                                    <span class="opt {{ in_array($key, $question->correct_options ?? []) ? 'correct' : '' }}">
                                        {{ $key }}. {{ $text }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    <div class="d-flex gap-2 flex-shrink-0">
                        <a href="{{ route('tentor.quizzes.questions.edit', [$quiz->id, $question->id]) }}"
                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fas fa-pen me-1"></i>Edit
                        </a>
                        <form action="{{ route('tentor.quizzes.questions.destroy', [$quiz->id, $question->id]) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus soal ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding: 40px 20px;">
                    <i class="fas fa-question-circle" style="font-size: 3rem; color: #cbd5e0;"></i>
                    <h6>No questions yet</h6>
                    <p>This quiz has no questions. Click "Add Question" to get started.</p>
                    <a href="{{ route('tentor.quizzes.questions.create', $quiz->id) }}" class="btn btn-primary rounded-pill px-4 mt-2" style="font-size: 0.88rem;">
                        <i class="fas fa-plus me-1"></i>Add Question
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
