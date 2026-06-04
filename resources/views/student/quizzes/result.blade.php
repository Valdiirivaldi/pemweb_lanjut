@extends('layouts.dashboard')

@section('title', 'Hasil Kuis - Eduria')
@section('page-title', 'Hasil Kuis')

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
        <i class="fas fa-arrow-left"></i> Kembali ke Kelas
    </a>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm d-flex align-items-center gap-2 border-0 mb-4"
             style="background: #fee2e2; color: #991b1b; font-weight: 500; font-size: 0.9rem; max-width: 640px; margin-left: auto; margin-right: auto;">
            <i class="fas fa-exclamation-circle" style="font-size: 1.1rem;"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="hero-score-wrap" data-anim="hero">
        <div class="hero-score-card">
            <div class="hero-icon {{ $passed ? 'passed' : 'failed' }}">
                <i class="fas {{ $passed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            </div>

            <div class="hero-title">{{ $passed ? 'Selamat!' : 'Tetap Semangat!' }}</div>
            <div class="hero-subtitle">{{ $passed ? 'Kamu berhasil lulus kuis ini.' : 'Jangan menyerah, coba lagi!' }}</div>

            <div class="hero-score {{ $passed ? 'passed' : 'failed' }}">{{ $attempt->score }}%</div>

            <div class="hero-status {{ $passed ? 'passed' : 'failed' }}">
                <i class="fas {{ $passed ? 'fa-check' : 'fa-times' }}"></i>
                {{ $passed ? 'Lulus' : 'Gagal' }}
            </div>

            <div class="hero-meta">
                <div class="meta-row">
                    <span class="meta-label">Kelas</span>
                    <span class="meta-value">{{ $attempt->quiz->course->title }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Kuis</span>
                    <span class="meta-value">{{ $attempt->quiz->title }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Skor Minimal</span>
                    <span class="meta-value">{{ $passingScore }}%</span>
                </div>
            </div>

            <div class="hero-actions">
                <a href="{{ route('siswa.quizzes.show', $attempt->quiz) }}" class="btn-hero btn-hero-retry">
                    <i class="fas fa-redo"></i> Coba Lagi
                </a>
                <a href="{{ route('siswa.courses.learn', $attempt->quiz->course) }}" class="btn-hero btn-hero-outline">
                    <i class="fas fa-book"></i> Ke Kelas
                </a>
                @if ($passed && !empty($attempt->certificate_path))
                    <a href="{{ asset('storage/' . $attempt->certificate_path) }}" class="btn-hero btn-hero-cert" download>
                        <i class="fas fa-file-pdf"></i> Unduh Sertifikat
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($attempt->relationLoaded('answers') && $attempt->answers->isNotEmpty())
        <div class="review-wrap">
            <div class="review-head">
                <i class="fas fa-clipboard-check"></i>
                Review Jawaban
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
                        <span class="review-q-num">Soal {{ $i + 1 }}</span>
                        @if ($answer->is_correct)
                            <span class="review-badge review-badge-correct">
                                <i class="fas fa-check me-1"></i>Benar
                            </span>
                        @else
                            <span class="review-badge review-badge-wrong">
                                <i class="fas fa-times me-1"></i>Salah
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
                                <span class="review-essay-label">Jawaban kamu:</span>
                                <span class="review-essay-value text-{{ $answer->is_correct ? 'success' : 'danger' }}">
                                    {{ $studentAnswer ?: '(Tidak dijawab)' }}
                                </span>
                            </div>
                            <div class="review-essay-row">
                                <span class="review-essay-label">Kunci jawaban:</span>
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
</script>
@endpush
