@php
    $sidebarMenus = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-chart-pie'],
        ['route' => 'admin.users.index', 'label' => 'Kelola Pengguna', 'icon' => 'fa-users'],
        ['route' => 'admin.enrollments.index', 'label' => 'Enrollment Kelas', 'icon' => 'fa-user-graduate', 'active' => true],
        ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'fa-user-cog'],
    ];
@endphp

@extends('layouts.dashboard')

@section('title', 'Enrollment Kelas - Eduria')
@section('page-title', 'Enrollment Kelas')

@section('sidebar-menu')
    @foreach ($sidebarMenus as $menu)
        <a href="{{ route($menu['route']) }}"
           class="nav-link {{ ($menu['active'] ?? false) || request()->routeIs($menu['route']) ? 'active' : '' }}">
            <i class="fas {{ $menu['icon'] }}"></i>{{ $menu['label'] }}
        </a>
    @endforeach
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2"
             style="border-radius: 14px; border: none; font-size: 0.9rem; font-weight: 500;" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2"
             style="border-radius: 14px; border: none; font-size: 0.9rem; font-weight: 500;" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span><i class="fas fa-plus-circle me-2"></i>Berikan Akses Kelas</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.enrollments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                Nama Siswa
                            </label>
                            <select class="form-select @error('user_id') is-invalid @enderror"
                                    id="user_id"
                                    name="user_id"
                                    required
                                    style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($siswa as $s)
                                    <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }} ({{ $s->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="course_id" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                Nama Kelas
                            </label>
                            <select class="form-select @error('course_id') is-invalid @enderror"
                                    id="course_id"
                                    name="course_id"
                                    required
                                    style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->title }} (Tentor: {{ $c->tentor->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100"
                                style="border-radius: 12px; height: 48px; font-weight: 700;">
                            <i class="fas fa-check-circle me-2"></i>Berikan Akses
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="content-card shadow-sm">
                <div class="card-header">
                    <span><i class="fas fa-list me-2"></i>Daftar Enrollment</span>
                    <span class="badge bg-primary rounded-pill">{{ count($enrollments) }}</span>
                </div>
                <div class="card-body p-0">
                    @if (count($enrollments) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.88rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tanggal Enrollment</th>
                                        <th style="width: 60px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollments as $index => $en)
                                        <tr>
                                            <td class="text-muted">{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $en->user_name }}</td>
                                            <td>{{ $en->course_title }}</td>
                                            <td class="text-muted">{{ \Carbon\Carbon::parse($en->created_at)->format('d M Y') }}</td>
                                            <td>
                                                <form action="{{ route('admin.enrollments.destroy', $en->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus enrollment ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            style="border-radius: 8px;"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-graduate"></i>
                            <h6>Belum ada enrollment</h6>
                            <p>Belum ada siswa yang terdaftar di kelas manapun. Gunakan form di samping untuk memberikan akses.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
