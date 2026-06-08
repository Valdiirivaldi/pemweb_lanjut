@extends('layouts.dashboard')

@section('title', 'Create Course - Eduria')
@section('page-title', 'Create New Course')

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
                    <span><i data-lucide="plus-circle" style="width:14px;height:14px;margin-right:8px;"></i>New Course Form</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.courses.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label-custom">
                                <i data-lucide="type" style="width:14px;height:14px;margin-right:4px;color:#4e73df;"></i>Course Title
                            </label>
                            <div class="input-wrap">
                                <i data-lucide="book" class="icon-input" style="width:16px;height:16px;"></i>
                                <input type="text"
                                       class="form-control form-input @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       placeholder="e.g., English for Beginners"
                                       required>
                            </div>
                            @error('title')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label-custom">
                                <i data-lucide="align-left" style="width:14px;height:14px;margin-right:4px;color:#4e73df;"></i>Course Description
                            </label>
                            <textarea class="form-control form-input @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      placeholder="Describe this course, the materials to be learned, and target participants..."
                                      rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('tentor.courses.index') }}"
                               class="btn btn-outline-secondary btn-secondary-custom px-4">
                                <i data-lucide="arrow-left" style="width:14px;height:14px;margin-right:4px;"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-primary-custom flex-grow-1">
                                <i data-lucide="check-circle" style="width:14px;height:14px;margin-right:8px;"></i>Save Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


