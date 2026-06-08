@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - Eduria')
@section('page-title', 'Admin Dashboard')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Admin Dashboard</span>
@endsection

@section('content')
    {{-- Welcome Card --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4 animate-on-scroll" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff;">
                <div>
                    <i data-lucide="shield" style="width:40px;height:40px;opacity:0.3;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #fff;" id="adminGreeting">Welcome, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                        Monitor and manage all learning activities wisely.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-card-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i data-lucide="users"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end gap-2">
                    <div class="stat-card-number">
                        <span class="counter-animate" data-target="{{ $totalUsers }}">0</span>
                    </div>
                    <span class="stat-card-trend up">
                        <i data-lucide="trending-up" style="width:12px;height:12px;"></i>
                        12%
                    </span>
                </div>
                <div class="stat-card-label">Total Active Users</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-1">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-card-icon" style="background: linear-gradient(135deg, #f6c23e, #d4a217);">
                        <i data-lucide="book-open"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end gap-2">
                    <div class="stat-card-number">
                        <span class="counter-animate" data-target="{{ $totalCourses }}">0</span>
                    </div>
                    <span class="stat-card-trend up">
                        <i data-lucide="trending-up" style="width:12px;height:12px;"></i>
                        8%
                    </span>
                </div>
                <div class="stat-card-label">Total Available Courses</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm animate-on-scroll delay-2">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-card-icon" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                        <i data-lucide="user-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end gap-2">
                    <div class="stat-card-number">
                        <span class="counter-animate" data-target="{{ $totalEnrolled }}">0</span>
                    </div>
                    <span class="stat-card-trend up">
                        <i data-lucide="trending-up" style="width:12px;height:12px;"></i>
                        15%
                    </span>
                </div>
                <div class="stat-card-label">Total Enrolled Students</div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="content-card animate-on-scroll">
                <div class="content-card-header">
                    <span><i data-lucide="zap" style="width:18px;height:18px;margin-right:8px;"></i>Quick Actions</span>
                </div>
                <div class="content-card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-pill">
                            <i data-lucide="user-plus" style="width:16px;height:16px;margin-right:6px;"></i>Add User
                        </a>
                        <a href="{{ route('admin.enrollments.index', ['tab' => 'siswa']) }}" class="btn btn-outline-primary btn-pill">
                            <i data-lucide="user-check" style="width:16px;height:16px;margin-right:6px;"></i>Enroll Student
                        </a>
                        <a href="{{ route('admin.enrollments.index', ['tab' => 'tentor']) }}" class="btn btn-outline-primary btn-pill">
                            <i data-lucide="chalkboard" style="width:16px;height:16px;margin-right:6px;"></i>Assign Tentor
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-pill">
                            <i data-lucide="users" style="width:16px;height:16px;margin-right:6px;"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card animate-on-scroll delay-3">
                <div class="content-card-header">
                    <span><i data-lucide="users"></i>Latest Users</span>
                    <span class="badge bg-primary rounded-pill">{{ count($recentUsers) }}</span>
                </div>
                <div class="content-card-body p-0">
                    @if ($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin mb-0" data-sortable>
                                <thead>
                                    <tr>
                                        <th data-sort="name">User</th>
                                        <th data-sort="role">Role</th>
                                        <th data-sort="date">Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentUsers as $u)
                                        <tr>
                                            <td>
                                                <div class="avatar-cell">
                                                    <div class="avatar-inline" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    </div>
                                                    <div class="avatar-cell-text">
                                                        <div class="avatar-cell-name">{{ $u->name }}</div>
                                                        <div class="avatar-cell-sub">{{ $u->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-role {{ $u->role }}">{{ ucfirst($u->role) }}</span>
                                            </td>
                                            <td class="text-muted" style="font-size:0.85rem;">{{ $u->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon-wrap">
                                <i data-lucide="users"></i>
                            </div>
                            <h6>No users yet</h6>
                            <p>No users are registered in the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card animate-on-scroll delay-4">
                <div class="content-card-header">
                    <span><i data-lucide="book-open"></i>Latest Courses</span>
                    <span class="badge bg-primary rounded-pill">{{ count($recentCourses) }}</span>
                </div>
                <div class="content-card-body p-0">
                    @if ($recentCourses->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin mb-0" data-sortable>
                                <thead>
                                    <tr>
                                        <th data-sort="title">Title</th>
                                        <th data-sort="tentor">Tentor</th>
                                        <th data-sort="date">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentCourses as $c)
                                        <tr>
                                            <td class="fw-semibold" style="color:var(--text-primary);">{{ $c->title }}</td>
                                            <td>
                                                @if ($c->tentor)
                                                    <span class="badge-role tentor">
                                                        <i data-lucide="chalkboard" style="width:12px;height:12px;"></i>{{ $c->tentor->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" style="font-size:0.8rem;">—</span>
                                                @endif
                                            </td>
                                            <td class="text-muted" style="font-size:0.85rem;">{{ $c->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon-wrap">
                                <i data-lucide="book-open"></i>
                            </div>
                            <h6>No courses yet</h6>
                            <p>No courses have been created in the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mt-2">
        <div class="col-lg-4">
            <div class="content-card animate-on-scroll">
                <div class="content-card-header">
                    <span><i data-lucide="pie-chart" style="margin-right:8px;"></i>User Role Distribution</span>
                </div>
                <div class="content-card-body">
                    <canvas id="roleChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card animate-on-scroll delay-1">
                <div class="content-card-header">
                    <span><i data-lucide="trending-up" style="margin-right:8px;"></i>User Growth</span>
                </div>
                <div class="content-card-body">
                    <canvas id="growthChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card animate-on-scroll delay-2">
                <div class="content-card-header">
                    <span><i data-lucide="door-open" style="margin-right:8px;"></i>Enrollment Status</span>
                </div>
                <div class="content-card-body">
                    <canvas id="enrollmentChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hour = new Date().getHours();
        var el = document.getElementById('adminGreeting');
        if (el) {
            var name = el.textContent.split(', ').pop() || '';
            var greet = 'Good ';
            if (hour >= 3 && hour < 11) greet += 'Morning';
            else if (hour >= 11 && hour < 15) greet += 'Afternoon';
            else if (hour >= 15 && hour < 18) greet += 'Evening';
            else greet += 'Night';
            el.textContent = greet + ', ' + name + '!';
        }

        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var textColor = isDark ? '#cbd5e0' : '#4a5568';
        var gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';

        var palette = {
            blue: '#4e73df',
            teal: '#38b2ac',
            orange: '#ed8936',
            softRed: '#fc8181',
            navy: '#2a5298',
            lightBlue: '#7eb0ff',
        };

        Chart.defaults.color = textColor;

        // Role Distribution (Doughnut)
        var roleCtx = document.getElementById('roleChart');
        if (roleCtx) {
            var roleData = @json($roleDistribution);
            new Chart(roleCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(roleData).map(function(r) { return r.charAt(0).toUpperCase() + r.slice(1); }),
                    datasets: [{
                        data: Object.values(roleData),
                        backgroundColor: [palette.blue, palette.teal, palette.orange],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
                    }
                }
            });
        }

        // User Growth (Line)
        var growthCtx = document.getElementById('growthChart');
        if (growthCtx) {
            var growthData = @json($userGrowth);
            var growFill = isDark ? 'rgba(78,115,223,0.12)' : 'rgba(78,115,223,0.07)';
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(growthData),
                    datasets: [{
                        label: 'New Users',
                        data: Object.values(growthData),
                        borderColor: palette.blue,
                        backgroundColor: growFill,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: palette.blue,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { ticks: { font: { size: 10 } }, grid: { color: gridColor } },
                        y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: gridColor } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Enrollment Status (Doughnut)
        var enrollCtx = document.getElementById('enrollmentChart');
        if (enrollCtx) {
            var enrollData = @json($enrollmentStatus);
            var labels = Object.keys(enrollData).map(function(s) { return s.charAt(0).toUpperCase() + s.slice(1); });
            new Chart(enrollCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: Object.values(enrollData),
                        backgroundColor: [palette.teal, palette.orange, palette.softRed],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
                    }
                }
            });
        }
    });
</script>
@endpush
