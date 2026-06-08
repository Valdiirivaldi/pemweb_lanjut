@extends('layouts.dashboard')

@section('title', 'Edit Module - Eduria')
@section('page-title', 'Edit Module')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    .form-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4f8;
        padding: 20px 24px;
        font-weight: 700;
        color: #1e3c72;
    }

    .form-card .card-body {
        padding: 24px;
    }

    .form-input {
        height: 48px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding-left: 16px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 4px rgba(78,115,223,0.12);
        outline: none;
    }

    textarea.form-input {
        height: auto;
        resize: vertical;
        min-height: 140px;
        padding-top: 12px;
    }


    .form-label-custom {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.85rem;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label-custom .label-icon {
        color: #4e73df;
    }

    .form-hint {
        font-size: 0.78rem;
        color: #a0aec0;
        margin-top: 4px;
    }

    .btn-primary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .btn-secondary-custom {
        border-radius: 12px;
        height: 48px;
        font-weight: 600;
    }

</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <a href="{{ route('tentor.courses.show', $module->course_id) }}"
               class="back-link d-inline-flex align-items-center gap-2 text-decoration-none mb-3"
               style="color: #718096; font-size: 0.88rem; font-weight: 500; padding: 8px 16px; border-radius: 10px; transition: all 0.25s ease;">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back to Course
            </a>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
                    <i data-lucide="alert-circle" style="width:14px;height:14px;margin-right:4px;"></i> Please fix the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-card shadow-sm">
                <div class="card-header">
                    <i data-lucide="pencil" style="width:16px;height:16px;margin-right:8px;color: #4e73df;"></i>Edit Module: {{ $module->title }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.modules.update', $module->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('tentor.modules._form', [
                            'module' => $module,
                            'courses' => $courses,
                            'selectedCourseId' => $module->course_id,
                            'backUrl' => route('tentor.courses.show', $module->course_id),
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('fileUploadArea').addEventListener('click', function() {
        document.getElementById('files').click();
    });

    document.getElementById('files').addEventListener('change', function() {
        var area = document.getElementById('fileUploadArea');
        var text = document.getElementById('uploadText');
        if (this.files && this.files.length > 0) {
            area.classList.add('has-file');
            text.textContent = this.files.length + ' file(s) selected';
        } else {
            area.classList.remove('has-file');
            text.textContent = 'Click to choose files';
        }
    });
</script>
@endpush
