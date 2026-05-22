@php
    $sidebarMenus = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-chart-pie'],
        ['route' => 'admin.users.index', 'label' => 'Kelola Pengguna', 'icon' => 'fa-users', 'active' => true],
        ['route' => 'admin.enrollments.index', 'label' => 'Enrollment Kelas', 'icon' => 'fa-user-graduate'],
        ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'fa-user-cog'],
    ];
@endphp

@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna - Eduria')
@section('page-title', 'Kelola Pengguna')

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

    <div class="content-card shadow-sm">
        <div class="card-header">
            <span><i class="fas fa-users me-2"></i>Daftar Akun Siswa & Tentor</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary" style="border-radius: 10px; font-weight: 600;">
                <i class="fas fa-plus me-1"></i>Tambah Akun
            </a>
        </div>
        <div class="card-body p-0">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                                <th style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $u)
                                <tr>
                                    <td class="text-muted">{{ $users->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $u->name }}</td>
                                    <td class="text-muted">{{ $u->email }}</td>
                                    <td>
                                        <span class="badge-role {{ $u->role }}">{{ ucfirst($u->role) }}</span>
                                    </td>
                                    <td class="text-muted">{{ $u->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $u->id) }}"
                                           class="btn btn-sm btn-warning me-1"
                                           style="border-radius: 8px; font-weight: 600; color: #fff;"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $u->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus akun {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    style="border-radius: 8px; font-weight: 600;"
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
                <div class="d-flex justify-content-center py-3">
                    {{ $users->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h6>Belum ada pengguna</h6>
                    <p>Belum ada siswa atau tentor yang terdaftar. Klik "Tambah Akun" untuk menambahkan.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2" style="border-radius: 10px;">
                        <i class="fas fa-plus me-1"></i>Tambah Akun
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
