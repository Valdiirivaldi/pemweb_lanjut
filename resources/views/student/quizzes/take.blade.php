@extends('layouts.dashboard')

@section('title', $quiz->title . ' - Eduria')
@section('page-title', $quiz->course->title)

@push('styles')
<style>
    .quiz-container {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
    }

    #quizOverlay {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.45);
        z-index: 999;
        border-radius: 14px;
        cursor: not-allowed;
    }

    .quiz-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .quiz-header h1 {
        font-weight: 800;
        color: #1e3c72;
        font-size: 1.3rem;
        margin: 0;
    }

    .quiz-timer {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border-radius: 12px;
        padding: 8px 18px;
        text-align: center;
        min-width: 100px;
        flex-shrink: 0;
    }

    .quiz-timer .timer-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.7;
        line-height: 1;
    }

    .quiz-timer .timer-value {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: monospace;
        line-height: 1.3;
    }

    .quiz-timer.caution {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .quiz-timer.warning {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        animation: timerPulse 1s ease-in-out infinite;
    }

    @keyframes timerPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .question-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        transition: box-shadow 0.3s ease;
    }

    .question-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    .question-card .question-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .question-card .question-text {
        font-weight: 600;
        color: #1e3c72;
        font-size: 0.95rem;
        margin-bottom: 14px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border: 1.5px solid #e9edf4;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 8px;
    }

    .option-item:hover {
        border-color: #4e73df;
        background: rgba(78, 115, 223, 0.04);
    }

    .option-item input[type="radio"] {
        accent-color: #4e73df;
        width: 18px;
        height: 18px;
        margin: 0;
        flex-shrink: 0;
        cursor: pointer;
    }

    .option-item input[type="checkbox"] {
        accent-color: #f59e0b;
        width: 18px;
        height: 18px;
        margin: 0;
        flex-shrink: 0;
        cursor: pointer;
    }

    .option-item .option-label {
        font-weight: 700;
        color: #4e73df;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .option-item .option-text {
        color: #4a5568;
        font-size: 0.9rem;
    }

    .btn-submit-quiz {
        border-radius: 12px;
        padding: 12px 36px;
        font-weight: 700;
        font-size: 1rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-submit-quiz:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
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

    .quiz-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
        padding: 16px 4px;
    }

    .quiz-footer .total-questions {
        font-size: 0.85rem;
        color: #a0aec0;
    }
</style>
@endpush

@section('content')
    <a href="{{ route('siswa.courses.learn', $quiz->course) }}" class="back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back to Class
    </a>

    @php
        $timeLimitSeconds = (int) $quiz->time_limit * 60;
        $elapsedSeconds = now()->diffInSeconds($attempt->created_at);
        $remainingSeconds = max(0, $timeLimitSeconds - $elapsedSeconds);
    @endphp

    <div class="quiz-container">
        <div class="quiz-header">
            <h1><i data-lucide="pencil" style="width:16px;height:16px;margin-right:8px;color:#f59e0b;"></i>{{ $quiz->title }}</h1>
            <div class="quiz-timer" id="timerContainer">
                <div class="timer-label">Timer</div>
                <div class="timer-value" id="timerDisplay">00:00</div>
            </div>
        </div>

        <form method="POST" action="{{ route('siswa.quizzes.submit', $quiz) }}" id="quizForm">
            @csrf

            @php $total = $quiz->questions->count(); @endphp

            @forelse ($quiz->questions as $i => $question)
                <div class="question-card">
                    <div class="question-text">
                        <span class="question-number">{{ $i + 1 }}</span>
                        <span>{{ $question->question_text }}</span>
                        @php
                            $typeBadge = match($question->type) {
                                'multiple' => ['label' => 'Multiple Answers', 'color' => '#f59e0b'],
                                'true_false' => ['label' => 'True / False', 'color' => '#10b981'],
                                default => ['label' => 'Single Choice', 'color' => '#4e73df'],
                            };
                        @endphp
                        <span class="badge rounded-pill ms-auto" style="background: {{ $typeBadge['color'] }}; font-size: 0.65rem; font-weight: 600; padding: 2px 10px; flex-shrink: 0;">
                            {{ $typeBadge['label'] }}
                        </span>
                    </div>

                    @php $isMulti = $question->isMultipleChoice(); @endphp

                    @foreach ($question->options ?? [] as $key => $text)
                        <label class="option-item">
                            @if ($isMulti)
                                <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $key }}">
                            @else
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}">
                            @endif
                            <span class="option-label">{{ $key }}.</span>
                            <span class="option-text">{{ $text }}</span>
                        </label>
                    @endforeach
                </div>
            @empty
                <div class="content-card shadow-sm" style="border-radius: 14px;">
                    <div class="card-body text-center py-5">
                        <div class="empty-state-icon-wrap"><i data-lucide="alert-triangle" style="width:32px;height:32px;color:#cbd5e0;"></i></div>
                        <h6 style="color: #1e3c72; font-weight: 700;">Questions not available</h6>
                        <p style="color: #a0aec0; font-size: 0.9rem;">The instructor has not added questions for this quiz yet.</p>
                    </div>
                </div>
            @endforelse

            @if ($total > 0)
                <div class="quiz-footer">
                    <div class="total-questions"><i data-lucide="list" style="width:14px;height:14px;margin-right:4px;"></i><span id="answeredCount">0</span> / {{ $total }} answered</div>
                    <button type="submit" class="btn-submit-quiz">
                        <i data-lucide="send" style="width:16px;height:16px;margin-right:8px;"></i>Submit Answers
                    </button>
                </div>
            @endif
        </form>

        <div id="quizOverlay"></div>
    </div>
@endsection

@push('scripts')
<script>
    (function() {
        let seconds = {{ $remainingSeconds }};

        const timerEl = document.getElementById('timerDisplay');
        const timerContainer = document.getElementById('timerContainer');
        const form = document.getElementById('quizForm');
        const submitBtn = document.querySelector('.btn-submit-quiz');
        let submitted = false;

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function render() {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            timerEl.textContent = pad(m) + ':' + pad(s);

            if (seconds <= 60) {
                timerContainer.classList.remove('caution');
                timerContainer.classList.add('warning');
            } else if (seconds <= 180) {
                timerContainer.classList.add('caution');
                timerContainer.classList.remove('warning');
            } else {
                timerContainer.classList.remove('caution', 'warning');
            }
        }

        function getAnsweredCount() {
            var checked = form.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
            return checked.length;
        }

        function lockForm() {
            document.getElementById('quizOverlay').style.display = 'block';
        }

        function submitIfNeeded() {
            if (submitted) return;

            var answered = getAnsweredCount();
            var total = {{ $total }};

            if (answered < total) {
                if (!confirm('Waktu habis! ' + (total - answered) + ' soal belum dijawab. Tetap kirim jawaban?')) {
                    return;
                }
            }

            submitted = true;

            clearInterval(t);

            timerEl.textContent = '00:00';

            lockForm();

            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Submitting...';
                submitBtn.disabled = true;
            }

            form.submit();
        }

        // Live progress counter
        function updateProgress() {
            var answered = form.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked').length;
            var el = document.getElementById('answeredCount');
            if (el) el.textContent = answered;
        }

        form.addEventListener('change', updateProgress);

        render();
        updateProgress();

        var t = setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                clearInterval(t);
                submitIfNeeded();
                return;
            }
            render();
        }, 1000);
    })();
</script>
@endpush
