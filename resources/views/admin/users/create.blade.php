@extends('layouts.dashboard')

@section('title', 'Add Account - Eduria')
@section('page-title', 'Add New Account')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('admin.dashboard') }}">Admin</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('admin.users.index') }}">Manage Users</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Add Account</span>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <span><i data-lucide="user-plus" style="margin-right:8px;"></i>Add Account Form</span>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary btn-pill">
                        <i data-lucide="arrow-left" style="width:14px;height:14px;margin-right:4px;"></i>Back
                    </a>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3 form-floating-custom">
                            <input type="text" id="name" name="name"
                                   value="{{ old('name') }}" placeholder=" "
                                   class="@error('name') is-invalid @enderror" required>
                            <label for="name">Full Name</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-floating-custom">
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}" placeholder=" "
                                   class="@error('email') is-invalid @enderror" required>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-floating-custom">
                            <input type="password" id="password" name="password"
                                   placeholder=" "
                                   class="@error('password') is-invalid @enderror" required>
                            <label for="password">Password</label>
                            <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">Minimum 8 characters</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-floating-custom">
                            <select id="role" name="role" required class="@error('role') is-invalid @enderror">
                                <option value=""></option>
                                <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Student</option>
                                <option value="tentor" {{ old('role') === 'tentor' ? 'selected' : '' }}>Tentor</option>
                            </select>
                            <label for="role">Role</label>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-pill px-4" style="height: 48px;">
                                <i data-lucide="save" style="width:16px;height:16px;margin-right:6px;"></i>Save
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-pill px-4" style="height: 48px; border: 1px solid var(--border-default);">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
