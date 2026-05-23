@php
    $sidebarMenus = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-chart-pie'],
        ['route' => 'admin.users.index', 'label' => 'Kelola Pengguna', 'icon' => 'fa-users'],
        ['route' => 'admin.enrollments.index', 'label' => 'Enrollment Kelas', 'icon' => 'fa-user-graduate', 'active' => true],
        ['route' => 'profile', 'label' => 'Profile', 'icon' => 'fa-user-cog'],
    ];

    $activeTab = request('tab', 'siswa');
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

@push('styles')
<style>
    .enrollment-tabs {
        display: flex;
        gap: 4px;
        border-bottom: 2px solid #e9edf4;
        margin-bottom: 24px;
    }
    .enrollment-tabs .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #718096;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border-radius: 0;
    }
    .enrollment-tabs .tab-btn:hover {
        color: #4e73df;
        background: rgba(78, 115, 223, 0.06);
    }
    .enrollment-tabs .tab-btn.active {
        color: #4e73df;
        border-bottom-color: #4e73df;
    }
</style>
@endpush

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

    {{-- Tab Navigation --}}
    <div class="enrollment-tabs">
        <a href="{{ route('admin.enrollments.index', ['tab' => 'siswa']) }}"
           class="tab-btn {{ $activeTab === 'siswa' ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i> Enrollment Siswa
        </a>
        <a href="{{ route('admin.enrollments.index', ['tab' => 'tentor']) }}"
           class="tab-btn {{ $activeTab === 'tentor' ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i> Enrollment Tentor
        </a>
    </div>

    @if ($activeTab === 'siswa')
        {{-- ═══════════════════════════════════════════ TAB SISWA ═══════════════════════════════════════════ --}}
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
                                        id="user_id" name="user_id" required
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
                                        id="course_id" name="course_id" required
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
    @else
        {{-- ═══════════════════════════════════════════ TAB TENTOR ═══════════════════════════════════════════ --}}
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-chalkboard-teacher me-2"></i>Tugaskan Tentor ke Kelas</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.enrollments.assign-tentor') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="tentor_id" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                    Pilih Tentor
                                </label>
                                <select class="form-select @error('tentor_id') is-invalid @enderror"
                                        id="tentor_id" name="tentor_id" required
                                        style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                    <option value="">-- Pilih Tentor --</option>
                                    @foreach ($tentors as $t)
                                        <option value="{{ $t->id }}" {{ old('tentor_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }} ({{ $t->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tentor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="course_id_tentor" class="form-label fw-semibold" style="color: #2d3748; font-size: 0.9rem;">
                                    Pilih Kelas
                                </label>
                                <select class="form-select @error('course_id') is-invalid @enderror"
                                        id="course_id_tentor" name="course_id" required
                                        style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($courses as $c)
                                        <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100"
                                    style="border-radius: 12px; height: 48px; font-weight: 700;">
                                <i class="fas fa-check-circle me-2"></i>Tugaskan Tentor
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-list me-2"></i>Daftar Kelas & Tentor</span>
                        <span class="badge bg-primary rounded-pill">{{ count($courses) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if (count($courses) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 0.88rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Tentor Pengampu</th>
                                            <th>Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courses as $index => $c)
                                            <tr>
                                                <td class="text-muted">{{ $index + 1 }}</td>
                                                <td class="fw-semibold">{{ $c->title }}</td>
                                                <td>
                                                    @if ($c->tentor)
                                                        <span class="badge-role tentor">
                                                            <i class="fas fa-chalkboard-teacher me-1"></i>{{ $c->tentor->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            <i class="fas fa-times me-1"></i>Belum ada tentor
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $c->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-book"></i>
                                <h6>Belum ada kelas</h6>
                                <p>Belum ada kelas yang tersedia di sistem.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
