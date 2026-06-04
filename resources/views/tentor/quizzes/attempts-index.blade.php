@extends('layouts.dashboard')

@section('title', 'Quiz Attempts - Eduria')
@section('page-title', 'Attempts — ' . $quiz->title)

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
</style>
@endpush

@section('content')
    <a href="{{ route('tentor.quizzes.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Quizzes
    </a>

    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Daftar Siswa yang Mengerjakan</span>
        </div>
        <div class="card-body p-0">
            @if ($attempts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Skor</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attempts as $attempt)
                                @php
                                    $passingScore = (int) ($quiz->passing_score ?? 70);
                                    $passed = $attempt->score >= $passingScore;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $attempt->siswa->name ?? 'Unknown' }}</td>
                                    <td>
                                        <span class="fw-bold {{ $passed ? 'text-success' : 'text-danger' }}">
                                            {{ $attempt->score }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if ($passed)
                                            <span class="badge bg-success">Lulus</span>
                                        @else
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $attempt->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('tentor.quizzes.attempts.show', [$quiz->id, $attempt->id]) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-search me-1"></i>Lihat Detail Jawaban
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h6>Belum ada siswa yang mengerjakan</h6>
                    <p>Quiz ini belum dikerjakan oleh siswa manapun.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
