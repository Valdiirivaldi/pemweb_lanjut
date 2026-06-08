@extends('layouts.dashboard')



@section('title', 'Admin Dashboard - Eduria')
@section('page-title', 'Admin Dashboard')

@section('content')
    {{-- Welcome Card --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="stat-card shadow-sm d-flex align-items-center gap-4 animate-on-scroll" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff;">
                <div>
                    <i class="fas fa-user-shield" style="font-size: 2.5rem; opacity: 0.3;"></i>
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
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-number">
                    <span class="counter-animate" data-target="{{ $totalUsers }}">0</span>
                </div>
                <div class="stat-label">Total Active Users</div>
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
                <div class="stat-label">Total Available Courses</div>
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
                <div class="stat-label">Total Enrolled Students</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="content-card shadow-sm animate-on-scroll delay-3">
                <div class="card-header">
                    <span>Latest Users</span>
                    <span class="badge bg-primary rounded-pill">{{ count($recentUsers) }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
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
                            <h6>No users yet</h6>
                            <p>No users are registered in the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card shadow-sm animate-on-scroll delay-4">
                <div class="card-header">
                    <span>Latest Courses</span>
                    <span class="badge bg-primary rounded-pill">{{ count($recentCourses) }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($recentCourses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
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
            <div class="content-card shadow-sm animate-on-scroll">
                <div class="card-header">
                    <span><i class="fas fa-chart-pie me-2"></i>User Role Distribution</span>
                </div>
                <div class="card-body">
                    <canvas id="roleChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card shadow-sm animate-on-scroll delay-1">
                <div class="card-header">
                    <span><i class="fas fa-chart-line me-2"></i>User Growth</span>
                </div>
                <div class="card-body">
                    <canvas id="growthChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card shadow-sm animate-on-scroll delay-2">
                <div class="card-header">
                    <span><i class="fas fa-door-open me-2"></i>Enrollment Status</span>
                </div>
                <div class="card-body">
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
                        backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(growthData),
                    datasets: [{
                        label: 'New Users',
                        data: Object.values(growthData),
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: '#4e73df',
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
                        backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
                    }
                }
            });
        }
    });
</script>
@endpush
