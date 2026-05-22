@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - Eduria')
@section('page-title', 'Admin Dashboard')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
        <i class="fas fa-chart-pie"></i>Dashboard
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-users"></i>Kelola Pengguna
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-book"></i>Kelola Course
    </a>
    <a href="#" class="nav-link">
        <i class="fas fa-file-alt"></i>Laporan
    </a>
    <a href="{{ route('profile.edit') }}" class="nav-link">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@section('content')
    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $totalAdmins }}</div>
                <div class="stat-label">Admin</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f6c23e, #d4a217);">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $totalTentors }}</div>
                <div class="stat-label">Tentor</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $totalStudents }}</div>
                <div class="stat-label">Siswa</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #e74a3b, #c0392b);">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $totalCourses }}</div>
                <div class="stat-label">Course</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Recent Users --}}
        <div class="col-lg-6">
            <div class="content-card shadow-sm">
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

        {{-- Recent Courses --}}
        <div class="col-lg-6">
            <div class="content-card shadow-sm">
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
