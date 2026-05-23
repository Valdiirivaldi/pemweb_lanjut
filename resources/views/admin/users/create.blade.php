@php
    $sidebarMenus = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-chart-pie'],
        ['route' => 'admin.users.index', 'label' => 'Kelola Pengguna', 'icon' => 'fa-users', 'active' => true],
        ['route' => 'admin.enrollments.index', 'label' => 'Enrollment Kelas', 'icon' => 'fa-user-graduate'],
        ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'fa-user-cog'],
    ];
@endphp

@extends('layouts.dashboard')

@section('title', 'Tambah Akun - Eduria')
@section('page-title', 'Tambah Akun Baru')

@section('sidebar-menu')
    @foreach ($sidebarMenus as $menu)
        <a href="{{ route($menu['route']) }}"
           class="nav-link {{ ($menu['active'] ?? false) || request()->routeIs($menu['route']) ? 'active' : '' }}">
            <i class="fas {{ $menu['icon'] }}"></i>{{ $menu['label'] }}
        </a>
    @endforeach
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span><i class="fas fa-user-plus me-2"></i>Form Tambah Akun</span>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary" style="border-radius: 10px;">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap"
                                   required
                                   style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                Alamat Email
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   required
                                   style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                Password
                            </label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Minimal 8 karakter"
                                   required
                                   style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                Role / Peran
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role"
                                    name="role"
                                    required
                                    style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                <option value="">-- Pilih Role --</option>
                                <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="tentor" {{ old('role') === 'tentor' ? 'selected' : '' }}>Tentor</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"
                                    style="border-radius: 12px; height: 48px; font-weight: 700;">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4"
                               style="border-radius: 12px; height: 48px; font-weight: 600; border: 1px solid #e2e8f0;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
