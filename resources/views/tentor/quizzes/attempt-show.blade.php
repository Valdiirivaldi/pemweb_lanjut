@extends('layouts.dashboard')

@section('title', 'Review Jawaban - Eduria')
@section('page-title', 'Review Jawaban — ' . $attempt->siswa->name)

@push('styles')
<style>
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

    .review-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .attempt-info-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }

    .attempt-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
    }

    .attempt-info-row:not(:last-child) {
        border-bottom: 1px solid #e9edf4;
    }

    .attempt-info-label {
        color: #718096;
        font-size: 0.85rem;
    }

    .attempt-info-value {
        color: #1e3c72;
        font-weight: 600;
        font-size: 0.88rem;
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        font-size: 1.15rem;
        color: #1e3c72;
        margin-bottom: 16px;
    }

    .review-header i {
        color: #f59e0b;
        font-size: 1.3rem;
    }

    .review-q-num {
        font-weight: 600;
        color: #1e3c72;
    }

    .review-question-text {
        font-weight: 600;
        color: #1e3c72;
        font-size: 0.95rem;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e9edf4;
    }

    .review-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        margin-bottom: 6px;
        border: 1.5px solid #e9edf4;
        transition: all 0.2s;
    }

    .review-option.is-correct {
        background: #ecfdf5;
        border-color: #a7f3d0;
    }

    .review-option.is-wrong {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .review-option.is-missed {
        background: #f8faff;
        border-color: #e9edf4;
    }

    .review-option-key {
        font-weight: 700;
        color: #4e73df;
        font-size: 0.85rem;
        min-width: 24px;
    }

    .review-option-text {
        flex: 1;
        color: #4a5568;
        font-size: 0.9rem;
    }

    .review-option-icon {
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .review-option-icon .fa-check-circle {
        color: #10b981;
    }

    .review-option-icon .fa-times-circle {
        color: #ef4444;
    }

    .review-answer-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0;
    }

    .review-answer-label {
        font-weight: 600;
        color: #718096;
        font-size: 0.88rem;
        min-width: 110px;
    }

    .review-answer-value {
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
    <a href="{{ route('tentor.quizzes.attempts.index', $quiz->id) }}" class="back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Daftar Attempt
    </a>

    <div class="review-container">
        <div class="attempt-info-card">
            <div class="attempt-info-row">
                <span class="attempt-info-label">Nama Siswa</span>
                <span class="attempt-info-value">{{ $attempt->siswa->name }}</span>
            </div>
            <div class="attempt-info-row">
                <span class="attempt-info-label">Kuis</span>
                <span class="attempt-info-value">{{ $quiz->title }}</span>
            </div>
            <div class="attempt-info-row">
                <span class="attempt-info-label">Skor</span>
                <span class="attempt-info-value">{{ $attempt->score }}%</span>
            </div>
            <div class="attempt-info-row">
                <span class="attempt-info-label">Status</span>
                <span class="attempt-info-value">
                    @php $passed = $attempt->score >= (int) ($quiz->passing_score ?? 70); @endphp
                    @if ($passed)
                        <span class="badge bg-success">Lulus</span>
                    @else
                        <span class="badge bg-danger">Gagal</span>
                    @endif
                </span>
            </div>
            <div class="attempt-info-row">
                <span class="attempt-info-label">Tanggal</span>
                <span class="attempt-info-value">{{ $attempt->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        @if ($attempt->answers->isNotEmpty())
            <div class="review-header">
                <i data-lucide="clipboard-check" style="width:16px;height:16px;"></i>
                Review Jawaban
            </div>

            <div class="accordion" id="reviewAccordion">
                @foreach ($attempt->answers as $i => $answer)
                    @php
                        $question = $answer->question;
                        $accordionId = 'tentor-review-' . $answer->id;
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#{{ $accordionId }}" aria-expanded="false">
                                <span class="review-q-num">Soal {{ $i + 1 }}</span>
                                @if ($answer->is_correct)
                                    <span class="badge bg-success ms-2">Benar</span>
                                @else
                                    <span class="badge bg-danger ms-2">Salah</span>
                                @endif
                            </button>
                        </h2>
                        <div id="{{ $accordionId }}" class="accordion-collapse collapse"
                            data-bs-parent="#reviewAccordion">
                            <div class="accordion-body">
                                <div class="review-question-text">{{ $question->question_text }}</div>

                                @if ($question->isMultipleChoice())
                                    @php
                                        $rawGiven = $answer->given_answer;
                                        $isOldFormat = is_array($rawGiven) && count($rawGiven) > 0 && is_array($rawGiven[0]);
                                        $givenAnswers = $isOldFormat ? ($rawGiven[0] ?? []) : ($rawGiven ?? []);
                                    @endphp
                                    @foreach ($question->options ?? [] as $key => $text)
                                        @php
                                            $keyUpper = strtoupper($key);
                                            $selected = in_array($keyUpper, $givenAnswers);
                                            $isKeyCorrect = in_array($keyUpper, array_map('strtoupper', $question->correct_options ?? []));
                                        @endphp
                                        <div class="review-option {{ $selected ? ($isKeyCorrect ? 'is-correct' : 'is-wrong') : ($isKeyCorrect ? 'is-missed' : '') }}">
                                            <div class="review-option-key">{{ $key }}</div>
                                            <div class="review-option-text">{{ $text }}</div>
                                            <div class="review-option-icon">
                                                @if ($selected && $isKeyCorrect)
                                                    <i data-lucide="check-circle" style="width:18px;height:18px;color:#10b981;"></i>
                                                @elseif ($selected && !$isKeyCorrect)
                                                    <i data-lucide="x-circle" style="width:18px;height:18px;color:#ef4444;"></i>
                                                @elseif (!$selected && $isKeyCorrect)
                                                    <i data-lucide="check-circle" style="width:18px;height:18px;opacity:0.4;color:#10b981;"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @php
                                        $options = $question->options ?? [];
                                        $studentKeys = is_array($answer->given_answer) ? $answer->given_answer : [$answer->given_answer];
                                        $studentAnswer = collect($studentKeys)
                                            ->filter(fn($k) => $k !== '')
                                            ->map(fn($k) => isset($options[$k]) ? $k . '. ' . $options[$k] : $k)
                                            ->implode(', ');
                                        $correctAnswer = collect($question->correct_options ?? [])
                                            ->filter(fn($k) => $k !== '')
                                            ->map(fn($k) => isset($options[$k]) ? $k . '. ' . $options[$k] : $k)
                                            ->implode(', ');
                                    @endphp
                                    <div class="review-answer-row">
                                        <span class="review-answer-label">Jawaban siswa:</span>
                                        <span class="review-answer-value text-{{ $answer->is_correct ? 'success' : 'danger' }}">
                                            {{ $studentAnswer ?: '(Tidak dijawab)' }}
                                        </span>
                                    </div>
                                    <div class="review-answer-row">
                                        <span class="review-answer-label">Kunci jawaban:</span>
                                        <span class="review-answer-value text-success">
                                            {{ $correctAnswer ?: '(Tidak ada)' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="content-card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon-wrap"><i data-lucide="alert-triangle" style="width:32px;height:32px;color:#cbd5e0;"></i></div>
                    <h6 style="color: #1e3c72; font-weight: 700;">Data jawaban tidak tersedia</h6>
                    <p style="color: #a0aec0; font-size: 0.9rem;">Siswa ini mengerjakan sebelum fitur review diaktifkan.</p>
                </div>
            </div>
        @endif
    </div>

@endsection