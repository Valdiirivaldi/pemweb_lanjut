@extends('layouts.dashboard')

@section('title', 'Quiz Attempts - Eduria')
@section('page-title', 'Attempts — ' . $quiz->title)

@section('breadcrumb')
    <a href="{{ route('tentor.quizzes.index') }}">Quizzes</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Attempts</span>
@endsection

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span>Daftar Siswa yang Mengerjakan</span>
        </div>
        <div class="card-body p-0">
            @if ($attempts->count() > 0)
                <div class="table-responsive">
                    <table class="table-admin mb-0" data-sortable>
                        <thead>
                            <tr>
                                <th data-sort="name">Nama Siswa</th>
                                <th data-sort="score">Skor</th>
                                <th data-sort="status">Status</th>
                                <th data-sort="date">Tanggal</th>
                                <th style="width: 60px;">Aksi</th>
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
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i data-lucide="more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px; border: none; padding: 6px; min-width: 160px;">
                                                <li>
                                                    <a href="{{ route('tentor.quizzes.attempts.show', [$quiz->id, $attempt->id]) }}"
                                                       class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="search" style="width:14px;height:14px;margin-right:8px;color:#4e73df;"></i>Lihat Detail Jawaban
                                                    </a>
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
                    <div class="empty-state-icon-wrap"><i data-lucide="user-x"></i></div>
                    <h6>Belum ada siswa yang mengerjakan</h6>
                    <p>Quiz ini belum dikerjakan oleh siswa manapun.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endpush
