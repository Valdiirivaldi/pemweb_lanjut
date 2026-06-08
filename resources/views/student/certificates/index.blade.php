@extends('layouts.dashboard')

@section('title', 'Certificates - Eduria')
@section('page-title', 'Certificates')

@push('styles')
<style>
    .cert-card {
        border: none;
        border-radius: 14px;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .cert-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    }

    .cert-card .cert-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        flex-shrink: 0;
    }

    .cert-card .cert-title {
        font-weight: 700;
        color: #1e3c72;
        font-size: 1rem;
    }

    .cert-card .cert-meta {
        color: #a0aec0;
        font-size: 0.8rem;
    }

    .btn-download {
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span><i data-lucide="award" style="width:16px;height:16px;margin-right:8px;color:#4e73df;"></i>Graduation Certificates</span>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-weight: 600;">
                {{ $certificates->count() }} certificates
            </span>
        </div>
        <div class="card-body">
            @forelse ($certificates as $cert)
                <div class="cert-card p-4 mb-3">
                    <div class="d-flex align-items-center gap-4">
                        <div class="cert-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                            <i data-lucide="file-text" style="width:18px;height:18px;"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="cert-title text-truncate">
                                {{ $cert->quiz->title ?? 'Certificate' }}
                            </div>
                            <div class="cert-meta mt-1">
                                <i data-lucide="calendar" style="width:14px;height:14px;margin-right:4px;"></i>
                                {{ $cert->created_at->format('d M Y') }}
                                <span class="mx-2">|</span>
                                <i data-lucide="star" style="width:14px;height:14px;margin-right:4px;"></i>
                                Score: {{ $cert->score }}
                                <span class="mx-2">|</span>
                                <i data-lucide="book" style="width:14px;height:14px;margin-right:4px;"></i>
                                {{ $cert->quiz->course->title ?? '-' }}
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $cert->certificate_path) }}"
                           class="btn btn-primary btn-download"
                           target="_blank"
                           download>
                            <i data-lucide="download" style="width:14px;height:14px;margin-right:4px;"></i> Download
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon-wrap"><i data-lucide="award" style="width:32px;height:32px;color:#cbd5e0;"></i></div>
                    <h6 style="color: #1e3c72; font-weight: 700;">No Certificates Yet</h6>
                    <p style="color: #a0aec0; font-size: 0.9rem;">Complete quizzes with a passing score to earn your certificate.</p>
                    <a href="{{ route('siswa.quizzes.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 mt-2">
                        <i data-lucide="pencil" style="width:14px;height:14px;margin-right:4px;"></i>View Quizzes
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endpush
