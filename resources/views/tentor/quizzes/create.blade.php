@extends('layouts.dashboard')

@section('title', 'Buat Kuis - Eduria')
@section('page-title', 'Buat Kuis Baru')

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
    <a href="{{ route('tentor.quizzes.index') }}" class="nav-link active">
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
    .input-wrap {
        position: relative;
    }
    .input-wrap .form-input {
        padding-left: 44px;
    }
    .input-wrap .icon-input {
        position: absolute;
        top: 50%;
        left: 16px;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 1rem;
        pointer-events: none;
    }
    select.form-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23a0aec0' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
    }
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span><i class="fas fa-plus-circle me-2"></i>Form Kuis Baru</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.quizzes.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="course_id" class="form-label-custom">
                                <i class="fas fa-book me-1" style="color: #4e73df;"></i>Pilih Course
                            </label>
                            <div class="input-wrap">
                                <i class="fas fa-folder-open icon-input"></i>
                                <select class="form-control form-input @error('course_id') is-invalid @enderror"
                                        id="course_id"
                                        name="course_id"
                                        required>
                                    <option value="">-- Pilih Course --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('course_id')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label-custom">
                                <i class="fas fa-heading me-1" style="color: #4e73df;"></i>Judul Kuis
                            </label>
                            <div class="input-wrap">
                                <i class="fas fa-question-circle icon-input"></i>
                                <input type="text"
                                       class="form-control form-input @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       placeholder="cth: Ujian Tengah Semester"
                                       required>
                            </div>
                            @error('title')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="time_limit" class="form-label-custom">
                                <i class="fas fa-clock me-1" style="color: #4e73df;"></i>Batas Waktu (menit)
                            </label>
                            <div class="input-wrap">
                                <i class="fas fa-hourglass-half icon-input"></i>
                                <input type="number"
                                       class="form-control form-input @error('time_limit') is-invalid @enderror"
                                       id="time_limit"
                                       name="time_limit"
                                       value="{{ old('time_limit', 60) }}"
                                       min="1"
                                       max="999"
                                       required>
                            </div>
                            @error('time_limit')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">
                                <i class="fas fa-info-circle me-1"></i>Waktu pengerjaan kuis dalam menit. Contoh: 60 = 1 jam.
                            </small>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('tentor.quizzes.index') }}"
                               class="btn btn-outline-secondary btn-secondary-custom px-4">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
                                <i class="fas fa-check-circle me-2"></i>Simpan Kuis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
