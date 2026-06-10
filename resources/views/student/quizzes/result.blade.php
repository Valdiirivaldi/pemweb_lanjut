@extends('layouts.dashboard')

@section('title', 'Quiz Result - Eduria')
@section('page-title', 'Quiz Result')

@push('styles')
<style>
    :root {
        --navy-900: #0f172a;
        --navy-800: #1e293b;
        --navy-700: #334155;
        --navy-600: #1e3c72;
        --navy-500: #4e73df;
        --navy-50: #f0f4ff;
        --bg-page: #f8fafc;
        --shadow-float: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.02);
        --shadow-card: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        --radius-card: 16px;
        --radius-btn: 12px;
    }

    /* ── Hero Score Section ── */
    .hero-score-wrap {
        max-width: 640px;
        margin: 0 auto;
    }

    .hero-score-card {
        background: #ffffff;
        border: none;
        border-radius: var(--radius-card);
        padding: 40px 32px 32px;
        box-shadow: var(--shadow-float);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-score-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--navy-500), #6366f1, var(--navy-500));
    }

    .hero-icon {
        font-size: 4.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .hero-icon.passed { color: #10b981; }
    .hero-icon.failed { color: #ef4444; }

    .hero-title {
        font-weight: 800;
        font-size: 1.6rem;
        color: var(--navy-600);
        margin-top: 8px;
        margin-bottom: 2px;
    }

    .hero-subtitle {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .hero-score {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -2px;
        margin-bottom: 12px;
    }

    .hero-score.passed { color: #10b981; }
    .hero-score.failed { color: #ef4444; }

    .hero-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 22px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 24px;
    }

    .hero-status.passed {
        background: #d1fae5;
        color: #065f46;
    }

    .hero-status.failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .hero-meta {
        background: var(--navy-50);
        border-radius: var(--radius-btn);
        padding: 16px 20px;
        margin-bottom: 24px;
        text-align: left;
        border: 1px solid #e9edf4;
    }

    .hero-meta .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
    }

    .hero-meta .meta-row:not(:last-child) {
        border-bottom: 1px solid #e9edf4;
    }

    .hero-meta .meta-label {
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .hero-meta .meta-value {
        color: var(--navy-600);
        font-weight: 700;
        font-size: 0.88rem;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-hero {
        border-radius: var(--radius-btn);
        padding: 11px 30px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-hero:hover {
        transform: translateY(-3px);
    }

    .btn-hero-retry {
        background: linear-gradient(135deg, var(--navy-500), var(--navy-600));
        color: #fff;
        box-shadow: 0 4px 14px rgba(78,115,223,0.35);
    }

    .btn-hero-retry:hover {
        box-shadow: 0 8px 28px rgba(78,115,223,0.45);
        color: #fff;
    }

    .btn-hero-outline {
        background: transparent;
        color: var(--navy-500);
        border: 1.5px solid var(--navy-500);
    }

    .btn-hero-outline:hover {
        background: var(--navy-50);
        color: var(--navy-600);
        border-color: var(--navy-600);
    }

    .btn-hero-cert {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 4px 14px rgba(16,185,129,0.35);
    }

    .btn-hero-cert:hover {
        box-shadow: 0 8px 28px rgba(16,185,129,0.45);
        color: #fff;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.25s ease;
        margin-bottom: 16px;
    }

    .back-link:hover {
        background: var(--navy-50);
        color: var(--navy-500);
    }

    /* ── Review Section ── */
    .review-wrap {
        max-width: 720px;
        margin: 40px auto 0;
    }

    .review-head {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 1.2rem;
        color: var(--navy-600);
        margin-bottom: 6px;
        padding-bottom: 16px;
        border-bottom: 2px solid #eef2f7;
    }

    .review-head-suffix {
        font-weight: 400;
        font-size: 0.9rem;
        color: #94a3b8;
        margin-left: auto;
    }

    .review-head i {
        color: #f59e0b;
        font-size: 1.3rem;
    }

    .review-float {
        background: #ffffff;
        border: none;
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        margin-bottom: 20px;
        overflow: hidden;
        opacity: 0;
        animation: fadeSlideUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .review-float-head {
        background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
        padding: 14px 22px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #f0f3f8;
    }

    .review-q-num {
        font-weight: 700;
        color: var(--navy-600);
        font-size: 0.92rem;
    }

    .review-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 50px;
    }

    .review-badge-correct {
        background: #d1fae5;
        color: #065f46;
    }

    .review-badge-wrong {
        background: #fee2e2;
        color: #991b1b;
    }

    .review-float-body {
        padding: 22px;
    }

    .review-q-text {
        font-weight: 600;
        color: var(--navy-800);
        font-size: 0.95rem;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #eef2f7;
        line-height: 1.65;
    }

    /* ── Option Items ── */
    .review-opts {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .review-opt {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border-radius: var(--radius-btn);
        border: 1.5px solid #e9edf4;
        background: #ffffff;
        transition: all 0.2s ease;
        cursor: default;
    }

    .review-opt:not(.is-correct-key):not(.is-wrong-pick):hover {
        border-color: #cbd5e1;
        background: #fafcff;
    }

    .review-opt.is-correct-key {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .review-opt.is-wrong-pick {
        background: #fff1f2;
        border-color: #fecdd3;
    }

    .review-opt-key {
        font-weight: 700;
        color: var(--navy-500);
        font-size: 0.8rem;
        min-width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--navy-50);
    }

    .review-opt.is-correct-key .review-opt-key {
        background: #bbf7d0;
        color: #065f46;
    }

    .review-opt.is-wrong-pick .review-opt-key {
        background: #fecdd3;
        color: #9f1239;
    }

    .review-opt-text {
        flex: 1;
        color: var(--navy-700);
        font-size: 0.9rem;
    }

    .review-opt-icon {
        flex-shrink: 0;
        width: 26px;
        text-align: center;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .review-opt-icon .ico-check { color: #059669; }
    .review-opt-icon .ico-cross { color: #e11d48; }

    /* ── Essay Answer ── */
    .review-essay-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
        padding: 9px 0;
    }

    .review-essay-row:not(:last-child) {
        border-bottom: 1px dashed #eef2f7;
    }

    .review-essay-label {
        font-weight: 600;
        color: #94a3b8;
        font-size: 0.83rem;
        min-width: 120px;
    }

    .review-essay-value {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--navy-800);
    }

    /* ── Keyframes ── */
    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.5);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
@endpush

@section('content')
    @php
        $passingScore = (int) ($attempt->quiz->passing_score ?? 70);
        $passed = $attempt->score >= $passingScore;
    @endphp

    <a href="{{ route('siswa.courses.learn', $attempt->quiz->course) }}" class="back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back to Class
    </a>

    <div class="hero-score-wrap" data-anim="hero">
        <div class="hero-score-card">
            <div class="hero-icon {{ $passed ? 'passed' : 'failed' }}">
                <i data-lucide="{{ $passed ? 'check-circle' : 'x-circle' }}" style="width:16px;height:16px;"></i>
            </div>

            <div class="hero-title">{{ $passed ? 'Congratulations!' : 'Keep Going!' }}</div>
            <div class="hero-subtitle">{{ $passed ? 'You passed this quiz.' : 'Don\'t give up, try again!' }}</div>

            <div class="hero-score {{ $passed ? 'passed' : 'failed' }}">{{ $attempt->score }}%</div>

            <div class="hero-status {{ $passed ? 'passed' : 'failed' }}">
                <i data-lucide="{{ $passed ? 'check' : 'x' }}" style="width:16px;height:16px;"></i>
                {{ $passed ? 'Passed' : 'Failed' }}
            </div>

            <div class="hero-meta">
                <div class="meta-row">
                    <span class="meta-label">Class</span>
                    <span class="meta-value">{{ $attempt->quiz->course->title }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Quiz</span>
                    <span class="meta-value">{{ $attempt->quiz->title }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Minimum Score</span>
                    <span class="meta-value">{{ $passingScore }}%</span>
                </div>
            </div>

            <div class="hero-actions">
                <a href="{{ route('siswa.quizzes.show', $attempt->quiz) }}" class="btn-hero btn-hero-retry">
                    <i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i> Try Again
                </a>
                <a href="{{ route('siswa.courses.learn', $attempt->quiz->course) }}" class="btn-hero btn-hero-outline">
                    <i data-lucide="book" style="width:16px;height:16px;"></i> To Class
                </a>
                @if ($passed && !empty($attempt->certificate_path))
                    <a href="{{ asset('storage/' . $attempt->certificate_path) }}" class="btn-hero btn-hero-cert" download>
                        <i data-lucide="file-text" style="width:16px;height:16px;"></i> Download Certificate
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($allAttempts->count() > 1)
        <div class="review-wrap" style="margin-top: 24px;">
            <div class="review-head">
                <i data-lucide="history" style="width:18px;height:18px;color:#f59e0b;margin-right:8px;"></i>
                Attempt History
                <span class="review-head-suffix">{{ $allAttempts->count() }} attempts</span>
            </div>
            <div class="content-card shadow-sm" style="border-radius: var(--radius-card);">
                <div class="card-body p-3">
                    <table class="table table-quiz mb-0">
                        <thead>
                            <tr>
                                <th>Attempt</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allAttempts as $i => $a)
                                @php
                                    $aPassed = $a->score >= (int) ($attempt->quiz->passing_score ?? 70);
                                @endphp
                                <tr class="{{ $a->id === $attempt->id ? 'table-primary' : '' }}">
                                    <td class="fw-semibold">Attempt #{{ $i + 1 }}</td>
                                    <td>
                                        <span class="score-badge {{ $aPassed ? 'score-pass' : 'score-fail' }}">
                                            {{ $a->score }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if ($aPassed)
                                            <span class="text-success fw-semibold"><i data-lucide="check" style="width:16px;height:16px;"></i> Passed</span>
                                        @else
                                            <span class="text-danger fw-semibold"><i data-lucide="x" style="width:16px;height:16px;"></i> Failed</span>
                                        @endif
                                    </td>
                                    <td style="color: #a0aec0; font-size: 0.85rem;">
                                        {{ $a->finished_at ? $a->finished_at->format('d M Y, H:i') : $a->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($attempt->relationLoaded('answers') && $attempt->answers->isNotEmpty())
        <div class="review-wrap">
            <div class="review-head">
                <i data-lucide="clipboard-check" style="width:18px;height:18px;color:#f59e0b;margin-right:8px;"></i>
                Review Answers
                <span class="review-head-suffix">{{ $attempt->answers->count() }} soal</span>
            </div>

            @foreach ($attempt->answers as $i => $answer)
                @php
                    $question = $answer->question;
                    $rawGiven = $answer->given_answer;
                    $isOldFormat = is_array($rawGiven) && count($rawGiven) > 0 && is_array($rawGiven[0]);
                    $selectedOptions = $isOldFormat ? ($rawGiven[0] ?? []) : ($rawGiven ?? []);
                @endphp
                <div class="review-float" data-anim="review">
                    <div class="review-float-head">
                        <span class="review-q-num">Question {{ $i + 1 }}</span>
                        @if ($answer->is_correct)
                            <span class="review-badge review-badge-correct">
                                <i data-lucide="check" style="width:14px;height:14px;margin-right:4px;"></i>Correct
                            </span>
                        @else
                            <span class="review-badge review-badge-wrong">
                                <i data-lucide="x" style="width:14px;height:14px;margin-right:4px;"></i>Incorrect
                            </span>
                        @endif
                    </div>
                    <div class="review-float-body">
                        <div class="review-q-text">{{ $question->question_text }}</div>

                        @if ($question->isMultipleChoice())
                            <div class="review-opts">
                                @foreach ($question->options ?? [] as $key => $text)
                                    @php
                                        $keyUpper = strtoupper($key);
                                        $selected = in_array($keyUpper, array_map('strtoupper', $selectedOptions));
                                        $isKeyCorrect = in_array($keyUpper, array_map('strtoupper', $question->correct_options ?? []));
                                    @endphp
                                    <div class="review-opt {{ $isKeyCorrect ? 'is-correct-key' : ($selected ? 'is-wrong-pick' : '') }}">
                                        <div class="review-opt-key">{{ $keyUpper }}</div>
                                        <div class="review-opt-text">{{ $text }}</div>
                                        <div class="review-opt-icon">
                                            @if ($isKeyCorrect)
                                                <span class="ico-check">&#10003;</span>
                                            @elseif ($selected)
                                                <span class="ico-cross">&#10007;</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @php
                                $options = $question->options ?? [];
                                $selectedKeys = is_array($rawGiven) ? $rawGiven : [$rawGiven];
                                $studentAnswer = collect($selectedKeys)
                                    ->filter(fn($k) => $k !== '')
                                    ->map(fn($k) => isset($options[$k]) ? $k . '. ' . $options[$k] : $k)
                                    ->implode(', ');
                                $correctAnswer = collect($question->correct_options ?? [])
                                    ->filter(fn($k) => $k !== '')
                                    ->map(fn($k) => isset($options[$k]) ? $k . '. ' . $options[$k] : $k)
                                    ->implode(', ');
                            @endphp
                            <div class="review-essay-row">
                                <span class="review-essay-label">Your answer:</span>
                                <span class="review-essay-value text-{{ $answer->is_correct ? 'success' : 'danger' }}">
                                    {{ $studentAnswer ?: '(Not answered)' }}
                                </span>
                            </div>
                            <div class="review-essay-row">
                                <span class="review-essay-label">Correct answer:</span>
                                <span class="review-essay-value" style="color:#059669;">
                                    {{ $correctAnswer ?: '(Tidak ada)' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var heroEl = document.querySelector('[data-anim="hero"]');
        var reviewCards = document.querySelectorAll('[data-anim="review"]');
        var delay = 0;

        if (heroEl) {
            heroEl.style.opacity = '0';
            heroEl.style.animation = 'fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards';
            heroEl.style.animationDelay = '100ms';
            delay = 350;
        }

        reviewCards.forEach(function (card, index) {
            card.style.animationDelay = (delay + index * 150) + 'ms';
        });
    });

    /* ── Confetti on pass ── */
    var passed = {{ $passed ? 'true' : 'false' }};
    if (passed && typeof confetti !== 'undefined') {
        setTimeout(function() {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc']
            });
        }, 400);
        setTimeout(function() {
            confetti({
                particleCount: 50,
                spread: 100,
                origin: { y: 0.4, x: 0.3 },
                colors: ['#4e73df', '#1cc88a']
            });
        }, 700);
        setTimeout(function() {
            confetti({
                particleCount: 50,
                spread: 100,
                origin: { y: 0.4, x: 0.7 },
                colors: ['#f6c23e', '#e74a3b']
            });
        }, 1000);
    }
</script>

@if ($passed)
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
@endif
@endpush
