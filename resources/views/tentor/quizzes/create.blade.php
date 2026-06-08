@extends('layouts.dashboard')

@section('title', 'Create Quiz - Eduria')
@section('page-title', 'Create New Quiz')

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
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span><i data-lucide="plus-circle" style="width:16px;height:16px;margin-right:8px;"></i>New Quiz Form</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tentor.quizzes.store') }}">
                        @csrf
                        @include('tentor.quizzes._form', ['quiz' => null, 'courses' => $courses])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


