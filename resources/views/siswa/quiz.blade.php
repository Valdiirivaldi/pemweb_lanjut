@extends('layouts.dashboard')

@section('title', $quiz->title . ' - Eduria')
@section('page-title', $quiz->course->title)

@section('sidebar-menu')
    <a href="{{ route('siswa.dashboard') }}"
       class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('siswa.courses.index') }}"
       class="nav-link {{ request()->routeIs('siswa.courses.*') ? 'active' : '' }}">
        <i class="fas fa-book"></i>My Courses
    </a>
    <a href="{{ route('siswa.quizzes.index') }}"
       class="nav-link {{ request()->routeIs('siswa.quizzes.*') ? 'active' : '' }}">
        <i class="fas fa-history"></i>Quiz History
    </a>
    <a href="{{ route('siswa.certificates.index') }}"
       class="nav-link {{ request()->routeIs('siswa.certificates.*') ? 'active' : '' }}">
        <i class="fas fa-certificate"></i>Certificates
    </a>
    <a href="{{ route('profile') }}"
       class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
    .quiz-container {
        max-width: 800px;
        margin: 0 auto;
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
        <i class="fas fa-arrow-left"></i> Kembali ke Kelas
    </a>

    <div class="quiz-container">
        <div class="quiz-header">
            <h1><i class="fas fa-pencil-alt me-2" style="color: #f59e0b;"></i>{{ $quiz->title }}</h1>
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
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required>
                            @endif
                            <span class="option-label">{{ $key }}.</span>
                            <span class="option-text">{{ $text }}</span>
                        </label>
                    @endforeach
                </div>
            @empty
                <div class="content-card shadow-sm" style="border-radius: 14px;">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #cbd5e0; margin-bottom: 16px;"></i>
                        <h6 style="color: #1e3c72; font-weight: 700;">Soal belum tersedia</h6>
                        <p style="color: #a0aec0; font-size: 0.9rem;">Tentor belum menambahkan soal untuk kuis ini.</p>
                    </div>
                </div>
            @endforelse

            @if ($total > 0)
                <div class="quiz-footer">
                    <div class="total-questions"><i class="fas fa-list me-1"></i>Total soal: {{ $total }}</div>
                    <button type="submit" class="btn-submit-quiz">
                        <i class="fas fa-paper-plane me-2"></i>Submit Jawaban
                    </button>
                </div>
            @endif
        </form>
    </div>
@endsection

@push('scripts')
<script>
    (function() {
        const minutes = {{ (int) $quiz->time_limit }};
        let seconds = minutes * 60;

        const timerEl = document.getElementById('timerDisplay');
        const timerContainer = document.getElementById('timerContainer');
        const form = document.getElementById('quizForm');
        let submitted = false;

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function render() {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            timerEl.textContent = pad(m) + ':' + pad(s);

            if (seconds <= 60) {
                timerContainer.classList.add('warning');
            }
        }

        function submitIfNeeded() {
            if (submitted) return;
            submitted = true;
            form.submit();
        }

        render();

        const t = setInterval(function() {
            seconds--;
            if (seconds <= 0) {
                clearInterval(t);
                timerEl.textContent = '00:00';
                submitIfNeeded();
                return;
            }
            render();
        }, 1000);
    })();
</script>
@endpush
