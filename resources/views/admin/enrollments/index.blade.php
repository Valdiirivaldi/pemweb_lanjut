@extends('layouts.dashboard')

@php
    $activeTab = request('tab', 'siswa');
@endphp

@section('title', 'Class Enrollment - Eduria')
@section('page-title', 'Class Enrollment')

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
            <i class="fas fa-user-graduate"></i> Student Enrollment
        </a>
        <a href="{{ route('admin.enrollments.index', ['tab' => 'tentor']) }}"
            class="tab-btn {{ $activeTab === 'tentor' ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i> Tentor Assignment
        </a>
    </div>

    @if ($activeTab === 'siswa')
        {{-- ═══════════════════════════════════════════ TAB SISWA ═══════════════════════════════════════════ --}}
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            {{-- Search --}}
            <form method="GET" action="{{ route('admin.enrollments.index') }}" class="d-flex gap-2 flex-grow-1">
                <input type="hidden" name="tab" value="siswa">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="input-group" style="max-width: 320px; border-radius: 10px; overflow: hidden;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Search student or course..."
                           value="{{ request('search') }}" style="height: 36px; font-size: 0.85rem;">
                </div>
                <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 10px;">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.enrollments.index', ['tab' => 'siswa', 'status' => request('status')]) }}"
                       class="btn btn-sm btn-outline-secondary" style="border-radius: 10px;">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>

            {{-- Status Filter --}}
            <div class="d-flex gap-2">
                <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['tab' => 'siswa', 'status' => 'pending'])) }}"
                    class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                    Pending
                </a>
                <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['tab' => 'siswa', 'status' => 'active'])) }}"
                    class="btn btn-sm {{ request('status') === 'active' ? 'btn-success' : 'btn-outline-success' }}">
                    Active
                </a>
                <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['tab' => 'siswa', 'status' => null, 'search' => request('search')])) }}"
                    class="btn btn-sm btn-outline-secondary">
                    All
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-plus-circle me-2"></i>Grant Class Access</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.enrollments.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="user_id" class="form-label fw-semibold"
                                    style="color: #2d3748; font-size: 0.9rem;">
                                    Student Name
                                </label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                                    name="user_id" required style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                    <option value="">-- Select Student --</option>
                                    @foreach ($siswa as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('user_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }} ({{ $s->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="course_id" class="form-label fw-semibold"
                                    style="color: #2d3748; font-size: 0.9rem;">
                                    Course Name
                                </label>
                                <select class="form-select @error('course_id') is-invalid @enderror" id="course_id"
                                    name="course_id" required style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                    <option value="">-- Select Course --</option>
                                    @foreach ($courses as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('course_id') == $c->id ? 'selected' : '' }}>
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
                                <i class="fas fa-check-circle me-2"></i>Grant Access
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-list me-2"></i>Enrollment List</span>
                        <span class="badge bg-primary rounded-pill">{{ count($enrollments) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if (count($enrollments) > 0)
                            {{-- Bulk Actions --}}
                            <form id="bulkForm" method="POST" action="{{ route('admin.enrollments.bulk-toggle') }}">
                                @csrf
                                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom bg-light">
                                    <input type="checkbox" id="selectAll" style="width: 16px; height: 16px;">
                                    <span class="text-muted" style="font-size: 0.8rem;">Select All</span>
                                    <div class="ms-auto d-flex gap-2">
                                        <button type="submit" name="action" value="unlock"
                                                class="btn btn-sm btn-success" style="border-radius: 8px;"
                                                onclick="return confirm('Buka akses untuk enrollment terpilih?')">
                                            <i class="fas fa-lock-open me-1"></i>Bulk Unlock
                                        </button>
                                        <button type="submit" name="action" value="lock"
                                                class="btn btn-sm btn-danger" style="border-radius: 8px;"
                                                onclick="return confirm('Kunci akses untuk enrollment terpilih?')">
                                            <i class="fas fa-lock me-1"></i>Bulk Lock
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" style="font-size: 0.88rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 32px;"></th>
                                                <th>#</th>
                                                <th>Student</th>
                                                <th>Course</th>
                                                <th>Status</th>
                                                <th>Enrollment Date</th>
                                                <th>Unlocked By</th>
                                                <th style="width: 60px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($enrollments as $index => $en)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="ids[]" value="{{ $en->id }}"
                                                               class="enroll-checkbox"
                                                               style="width: 16px; height: 16px;">
                                                    </td>
                                                    <td class="text-muted">{{ $index + 1 }}</td>
                                                    <td class="fw-semibold">{{ $en->user_name }}</td>
                                                    <td>{{ $en->course_title }}</td>
                                                    <td>
                                                        @php
                                                            $isActive = (int) ($en->is_unlocked ?? 0) === 1;
                                                        @endphp
                                                        <span class="badge rounded-pill {{ $isActive ? 'bg-success' : 'bg-warning text-dark' }}"
                                                              style="font-size: 0.75rem;">
                                                            {{ $isActive ? 'Active' : 'Pending' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-muted" style="font-size: 0.8rem;">
                                                        {{ \Carbon\Carbon::parse($en->created_at)->format('d M Y') }}</td>
                                                    <td class="text-muted" style="font-size: 0.8rem;">
                                                        @if ($en->unlocked_by)
                                                            {{ \App\Models\User::find($en->unlocked_by)?->name ?? 'User #'.$en->unlocked_by }}
                                                            <br><small>{{ $en->unlocked_at ? \Carbon\Carbon::parse($en->unlocked_at)->format('d M Y H:i') : '' }}</small>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            {{-- Toggle Access --}}
                                                            <form
                                                                action="{{ route('admin.enrollments.toggle-access', $en->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm {{ $isActive ? 'btn-success' : 'btn-danger' }}"
                                                                    style="border-radius: 8px; min-width: 36px;"
                                                                    title="Toggle Access">
                                                                    <i
                                                                        class="fas {{ $isActive ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                                                </button>
                                                            </form>

                                                            {{-- Delete Enrollment --}}
                                                            <form action="{{ route('admin.enrollments.destroy', $en->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Delete this enrollment?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    style="border-radius: 8px;" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-user-graduate"></i>
                                <h6>No enrollments found</h6>
                                <p>@if(request('search') || request('status')) No enrollments match your filter criteria. Try a different search. @else No students are enrolled in any classes yet. Use the form to grant access. @endif</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Select All Script --}}
        @push('scripts')
        <script>
            document.getElementById('selectAll')?.addEventListener('change', function() {
                document.querySelectorAll('.enroll-checkbox').forEach(function(cb) {
                    cb.checked = this.checked;
                }, this);
            });
        </script>
        @endpush
    @else
        {{-- ═══════════════════════════════════════════ TAB TENTOR ═══════════════════════════════════════════ --}}
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-chalkboard-teacher me-2"></i>Assign Tentor to Course</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.enrollments.assign-tentor') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="tentor_id" class="form-label fw-semibold"
                                    style="color: #2d3748; font-size: 0.9rem;">
                                    Select Tentor
                                </label>
                                <select class="form-select @error('tentor_id') is-invalid @enderror" id="tentor_id"
                                    name="tentor_id" required
                                    style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                    <option value="">-- Select Tentor --</option>
                                    @foreach ($tentors as $t)
                                        <option value="{{ $t->id }}"
                                            {{ old('tentor_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }} ({{ $t->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tentor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="course_id_tentor" class="form-label fw-semibold"
                                    style="color: #2d3748; font-size: 0.9rem;">
                                    Select Course
                                </label>
                                <select class="form-select @error('course_id') is-invalid @enderror" id="course_id_tentor"
                                    name="course_id" required
                                    style="height: 48px; border-radius: 12px; font-size: 0.9rem;">
                                    <option value="">-- Select Course --</option>
                                    @foreach ($courses as $c)
                                        <option value="{{ $c->id }}"
                                            {{ old('course_id') == $c->id ? 'selected' : '' }}>
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
                                <i class="fas fa-check-circle me-2"></i>Assign Tentor
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-list me-2"></i>Courses & Tentors</span>
                        <span class="badge bg-primary rounded-pill">{{ count($courses) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if (count($courses) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 0.88rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Course Name</th>
                                            <th>Assigned Tentor</th>
                                            <th>Created</th>
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
                                                            <i
                                                                class="fas fa-chalkboard-teacher me-1"></i>{{ $c->tentor->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            <i class="fas fa-times me-1"></i>No tentor
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
                                <h6>No courses yet</h6>
                                <p>No courses available in the system.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
