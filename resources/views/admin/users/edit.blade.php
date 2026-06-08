@extends('layouts.dashboard')

@section('title', 'Edit Account - Eduria')
@section('page-title', 'Edit Account')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('admin.dashboard') }}">Admin</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('admin.users.index') }}">Manage Users</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Edit Account</span>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <span><i data-lucide="user-edit" style="margin-right:8px;"></i>Edit Account Form</span>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary btn-pill">
                        <i data-lucide="arrow-left" style="width:14px;height:14px;margin-right:4px;"></i>Back
                    </a>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 form-floating-custom">
                            <input type="text" id="name" name="name"
                                   value="{{ old('name', $user->name) }}" placeholder=" "
                                   class="@error('name') is-invalid @enderror" required>
                            <label for="name">Full Name</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-floating-custom">
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', $user->email) }}" placeholder=" "
                                   class="@error('email') is-invalid @enderror" required>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-floating-custom">
                            <input type="password" id="password" name="password"
                                   placeholder=" ">
                            <label for="password">Password</label>
                            <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">Leave blank if unchanged</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-floating-custom">
                            <select id="role" name="role" required class="@error('role') is-invalid @enderror">
                                <option value=""></option>
                                <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>Student</option>
                                <option value="tentor" {{ old('role', $user->role) === 'tentor' ? 'selected' : '' }}>Tentor</option>
                            </select>
                            <label for="role">Role</label>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-floating-custom">
                            <input type="text" id="unique_id" name="unique_id"
                                   value="{{ old('unique_id', $user->unique_id) }}" placeholder=" "
                                   style="font-family:monospace;">
                            <label for="unique_id">Unique ID</label>
                            <small class="text-muted" style="font-size:0.75rem;margin-top:2px;display:block;">Auto-generated, can be edited</small>
                            @error('unique_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-pill px-4" style="height: 48px;">
                                <i data-lucide="save" style="width:16px;height:16px;margin-right:6px;"></i>Update
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
