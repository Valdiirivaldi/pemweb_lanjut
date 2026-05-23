@extends('layouts.dashboard')

@section('title', 'Buat Course - Eduria')
@section('page-title', 'Buat Course Baru')

@section('sidebar-menu')
    <a href="{{ route('tentor.dashboard') }}" class="nav-link">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="{{ route('tentor.courses.index') }}" class="nav-link">
        <i class="fas fa-book"></i>Course Saya
    </a>
    <a href="{{ route('tentor.modules.index') }}" class="nav-link">
        <i class="fas fa-layer-group"></i>Modul
    </a>
    <a href="{{ route('tentor.quizzes.index') }}" class="nav-link">
        <i class="fas fa-question-circle"></i>Kuis & Bank Soal
    </a>
    <a href="{{ route('tentor.students.index') }}" class="nav-link">
        <i class="fas fa-users"></i>Peserta
    </a>
    <a href="{{ route('profile') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
    .form-input {
        height: 48px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
        padding: 12px 16px;
    }
    .form-input:focus {
        border-color: #4e73df;
        box-shadow: none;
    }
    textarea.form-input {
        height: auto;
        resize: vertical;
        min-height: 120px;
    }
    .form-label-custom {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.85rem;
        margin-bottom: 6px;
    }
    .btn-primary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.25);
    }
    .btn-secondary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 600;
    }
    .icon-input {
        position: absolute;
        top: 50%;
        left: 16px;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 1rem;
        pointer-events: none;
    }
    .form-input ~ .icon-input + .form-input {
        padding-left: 44px;
    }
    .input-wrap {
        position: relative;
    }
    .input-wrap .form-input {
        padding-left: 44px;
    }
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span><i class="fas fa-plus-circle me-2"></i>Form Course Baru</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.courses.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label-custom">
                                <i class="fas fa-heading me-1" style="color: #4e73df;"></i>Judul Course
                            </label>
                            <div class="input-wrap">
                                <i class="fas fa-book icon-input"></i>
                                <input type="text"
                                       class="form-control form-input @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       placeholder="cth: Laravel untuk Pemula"
                                       required>
                            </div>
                            @error('title')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label-custom">
                                <i class="fas fa-align-left me-1" style="color: #4e73df;"></i>Deskripsi Course
                            </label>
                            <textarea class="form-control form-input @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      placeholder="Jelaskan tentang course ini, materi yang akan dipelajari, dan target peserta..."
                                      rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('tentor.courses.index') }}"
                               class="btn btn-outline-secondary btn-secondary-custom px-4">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
                                <i class="fas fa-check-circle me-2"></i>Simpan Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
