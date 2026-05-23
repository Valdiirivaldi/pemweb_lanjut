@php
    $sidebarMenus = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-chart-pie', 'active' => true],
        ['route' => 'admin.users.index', 'label' => 'Kelola Pengguna', 'icon' => 'fa-users'],
        ['route' => 'admin.enrollments.index', 'label' => 'Enrollment Kelas', 'icon' => 'fa-user-graduate'],
        ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'fa-user-cog'],
    ];
@endphp

@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - Eduria')
@section('page-title', 'Admin Dashboard')

@section('sidebar-menu')
    @foreach ($sidebarMenus as $menu)
        <a href="{{ route($menu['route']) }}"
           class="nav-link {{ ($menu['active'] ?? false) || request()->routeIs($menu['route']) ? 'active' : '' }}">
            <i class="fas {{ $menu['icon'] }}"></i>{{ $menu['label'] }}
        </a>
    @endforeach
@endsection

@section('content')
    {{-- Welcome Card --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4 animate-on-scroll" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff;">
                <div>
                    <i class="fas fa-user-shield" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #fff;" id="adminGreeting">Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                        Pantau dan kelola seluruh aktivitas pembelajaran dengan bijak.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $totalUsers }}">0</span>
                </div>
                <div class="stat-label">Total Seluruh User Aktif</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-1">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f6c23e, #d4a217);">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $totalCourses }}">0</span>
                </div>
                <div class="stat-label">Total Kelas yang Tersedia</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-2">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $totalEnrolled }}">0</span>
                </div>
                <div class="stat-label">Total Siswa Ter-Enroll</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card shadow-sm animate-on-scroll delay-3">
                <div class="card-header">
                    <span>Pengguna Terbaru</span>
                    <span class="badge bg-primary rounded-pill">{{ count($recentUsers) }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Bergabung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentUsers as $u)
                                        <tr>
                                            <td class="fw-semibold">{{ $u->name }}</td>
                                            <td class="text-muted">{{ $u->email }}</td>
                                            <td>
                                                <span class="badge-role {{ $u->role }}">{{ ucfirst($u->role) }}</span>
                                            </td>
                                            <td class="text-muted">{{ $u->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h6>Belum ada pengguna</h6>
                            <p>Belum ada pengguna yang terdaftar di sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card shadow-sm animate-on-scroll delay-4">
                <div class="card-header">
                    <span>Course Terbaru</span>
                    <span class="badge bg-primary rounded-pill">{{ count($recentCourses) }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($recentCourses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tentor</th>
                                        <th>Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentCourses as $c)
                                        <tr>
                                            <td class="fw-semibold">{{ $c->title }}</td>
                                            <td class="text-muted">{{ $c->tentor->name ?? '-' }}</td>
                                            <td class="text-muted">{{ $c->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book"></i>
                            <h6>Belum ada course</h6>
                            <p>Belum ada course yang dibuat di sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hour = new Date().getHours();
        var el = document.getElementById('adminGreeting');
        if (el) {
            var name = el.textContent.split(', ').pop() || '';
            var greet = 'Selamat ';
            if (hour >= 3 && hour < 11) greet += 'Pagi';
            else if (hour >= 11 && hour < 15) greet += 'Siang';
            else if (hour >= 15 && hour < 18) greet += 'Sore';
            else greet += 'Malam';
            el.textContent = greet + ', ' + name + '!';
        }
    });
</script>
@endpush
