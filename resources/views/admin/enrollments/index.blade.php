@extends('layouts.dashboard')

@php
    $activeTab = request('tab', 'siswa');
@endphp

@section('title', 'Class Enrollment - Eduria')
@section('page-title', 'Class Enrollment')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('admin.dashboard') }}">Admin</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Enrollments</span>
@endsection

@section('content')
    {{-- Tab Navigation --}}
    <div class="enrollment-tabs">
        <a href="{{ route('admin.enrollments.index', ['tab' => 'siswa']) }}"
            class="tab-btn {{ $activeTab === 'siswa' ? 'active' : '' }}">
            <i data-lucide="user-check"></i> Student Enrollment
        </a>
        <a href="{{ route('admin.enrollments.index', ['tab' => 'tentor']) }}"
            class="tab-btn {{ $activeTab === 'tentor' ? 'active' : '' }}">
            <i data-lucide="chalkboard"></i> Tentor Assignment
        </a>
    </div>

    @if ($activeTab === 'siswa')
        {{-- ═══════════════════════════════════════════ TAB SISWA ═══════════════════════════════════════════ --}}
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            {{-- Search --}}
            <form method="GET" action="{{ route('admin.enrollments.index') }}" class="d-flex gap-2 flex-grow-1"
                  data-live-search="true" data-live-target="enrollments-table-wrap">
                <input type="hidden" name="tab" value="siswa">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="input-group" style="max-width: 320px; border-radius: 10px; overflow: hidden;">
                    <span class="input-group-text bg-white border-end-0">
                        <i data-lucide="search" style="width:16px;height:16px;color:var(--text-muted);"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Search student or course..."
                           value="{{ request('search') }}" style="height: 36px; font-size: 0.85rem;">
                </div>
                <button type="submit" class="btn btn-sm btn-primary btn-pill">
                    <i data-lucide="search" style="width:14px;height:14px;"></i>
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.enrollments.index', ['tab' => 'siswa', 'status' => request('status')]) }}"
                       class="btn btn-sm btn-outline-secondary btn-pill">
                        <i data-lucide="x" style="width:14px;height:14px;"></i>
                    </a>
                @endif
            </form>

            {{-- Status Filter --}}
            <div class="d-flex gap-2">
                @php $curStatus = request('status'); @endphp
                <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['tab' => 'siswa', 'status' => 'pending'])) }}"
                   class="btn btn-sm rounded-pill px-3 {{ $curStatus === 'pending' ? 'badge-status pending' : 'btn-outline-secondary' }}">
                    <i data-lucide="clock" style="width:12px;height:12px;"></i>Pending
                </a>
                <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['tab' => 'siswa', 'status' => 'active'])) }}"
                   class="btn btn-sm rounded-pill px-3 {{ $curStatus === 'active' ? 'badge-status active' : 'btn-outline-secondary' }}">
                    <i data-lucide="check-circle" style="width:12px;height:12px;"></i>Active
                </a>
                <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['tab' => 'siswa', 'status' => null, 'search' => request('search')])) }}"
                   class="btn btn-sm rounded-pill px-3 {{ !$curStatus ? 'btn-outline-primary' : 'btn-outline-secondary' }}">
                    All
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="content-card">
                    <div class="content-card-header">
                        <span><i data-lucide="plus-circle" style="margin-right:8px;"></i>Grant Class Access</span>
                    </div>
                    <div class="content-card-body">
                        <form method="POST" action="{{ route('admin.enrollments.store') }}">
                            @csrf

                            <div class="mb-3 form-floating-custom">
                                <select id="user_id" name="user_id" required class="@error('user_id') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach ($siswa as $s)
                                        <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->email }})</option>
                                    @endforeach
                                </select>
                                <label for="user_id">Student Name</label>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-floating-custom">
                                <select id="course_id" name="course_id" required class="@error('course_id') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach ($courses as $c)
                                        <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }} (Tentor: {{ $c->tentor->name ?? '-' }})</option>
                                    @endforeach
                                </select>
                                <label for="course_id">Course Name</label>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-pill" style="height: 48px;">
                                <i data-lucide="check-circle" style="width:16px;height:16px;margin-right:6px;"></i>Grant Access
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-card">
                    <div class="content-card-header">
                        <span><i data-lucide="list" style="margin-right:8px;"></i>Enrollment List</span>
                        <span class="badge bg-primary rounded-pill">{{ count($enrollments) }}</span>
                    </div>
                    <div class="content-card-body p-0" id="enrollments-table-wrap">
                        @if (count($enrollments) > 0)
                            {{-- Bulk Actions --}}
                            <form id="bulkForm" method="POST" action="{{ route('admin.enrollments.bulk-toggle') }}">
                                @csrf
                                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom"
                                     style="background: var(--bg-body) !important;">
                                    <input type="checkbox" id="selectAll"
                                           style="width: 16px; height: 16px; accent-color: #4e73df;">
                                    <span class="text-muted" style="font-size: 0.8rem;">Select All</span>
                                    <div class="ms-auto d-flex gap-2">
                                        <button type="submit" name="action" value="unlock"
                                                class="btn btn-sm btn-outline-success rounded-pill px-3"
                                                style="font-size: 0.8rem; font-weight: 600;"
                                                onclick="return confirm('Buka akses untuk enrollment terpilih?')">
                                            <i data-lucide="unlock" style="width:14px;height:14px;margin-right:4px;"></i>Bulk Unlock
                                        </button>
                                        <button type="submit" name="action" value="lock"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                style="font-size: 0.8rem; font-weight: 600;"
                                                onclick="return confirm('Kunci akses untuk enrollment terpilih?')">
                                            <i data-lucide="lock" style="width:14px;height:14px;margin-right:4px;"></i>Bulk Lock
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table-admin mb-0" data-sortable>
                                        <thead>
                                            <tr>
                                                <th style="width: 32px;"></th>
                                                <th data-sort="student">Student</th>
                                                <th data-sort="course">Course</th>
                                                <th data-sort="status">Status</th>
                                                <th data-sort="date">Enrolled</th>
                                                <th>Unlocked By</th>
                                                <th style="width: 60px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($enrollments as $index => $en)
                                                @php
                                                    $isActive = (int) ($en->is_unlocked ?? 0) === 1;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="ids[]" value="{{ $en->id }}"
                                                               class="enroll-checkbox"
                                                               style="width: 16px; height: 16px; accent-color: #4e73df;">
                                                    </td>
                                                    <td>
                                                        <div class="avatar-cell">
                                                            <div class="avatar-inline" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                                                {{ strtoupper(substr($en->user_name, 0, 1)) }}
                                                            </div>
                                                            <div class="avatar-cell-text">
                                                                <div class="avatar-cell-name">{{ $en->user_name }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="color:var(--text-primary);font-weight:500;">{{ $en->course_title }}</td>
                                                    <td>
                                                        <span class="badge-status {{ $isActive ? 'active' : 'pending' }}">
                                                            <i data-lucide="circle" style="width:6px;height:6px;"></i>
                                                            {{ $isActive ? 'Active' : 'Pending' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-muted" style="font-size:0.85rem;">
                                                        {{ \Carbon\Carbon::parse($en->created_at)->format('d M Y') }}</td>
                                                    <td class="text-muted" style="font-size:0.85rem;">
                                                        @if ($en->unlocked_by)
                                                            {{ \App\Models\User::find($en->unlocked_by)?->name ?? 'User #'.$en->unlocked_by }}
                                                            <br><small style="font-size:0.7rem;">{{ $en->unlocked_at ? \Carbon\Carbon::parse($en->unlocked_at)->format('d M Y H:i') : '' }}</small>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                                <i data-lucide="more-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px; border: none; padding: 6px; min-width: 160px;">
                                                                <li>
                                                                    <form action="{{ route('admin.enrollments.toggle-access', $en->id) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item py-2 rounded-2"
                                                                                data-ajax-action="toggle-access"
                                                                                data-confirm="{{ $isActive ? 'Lock access for this enrollment?' : 'Unlock access for this enrollment?' }}">
                                                                            <i data-lucide="{{ $isActive ? 'lock' : 'unlock' }}" style="width:14px;height:14px;margin-right:8px;color:{{ $isActive ? '#e74c3c' : '#27ae60' }};"></i>
                                                                            {{ $isActive ? 'Lock Access' : 'Unlock Access' }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('admin.enrollments.destroy', $en->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item py-2 rounded-2 text-danger"
                                                                                data-ajax-action="delete"
                                                                                data-confirm="Delete this enrollment?">
                                                                            <i data-lucide="trash-2" style="width:14px;height:14px;margin-right:8px;"></i>Delete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
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
                                <div class="empty-state-icon-wrap">
                                    <i data-lucide="user-check"></i>
                                </div>
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
                <div class="content-card">
                    <div class="content-card-header">
                        <span><i data-lucide="chalkboard" style="margin-right:8px;"></i>Assign Tentor to Course</span>
                    </div>
                    <div class="content-card-body">
                        <form method="POST" action="{{ route('admin.enrollments.assign-tentor') }}">
                            @csrf

                            <div class="mb-3 form-floating-custom">
                                <select id="tentor_id" name="tentor_id" required class="@error('tentor_id') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach ($tentors as $t)
                                        <option value="{{ $t->id }}" {{ old('tentor_id') == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->email }})</option>
                                    @endforeach
                                </select>
                                <label for="tentor_id">Select Tentor</label>
                                @error('tentor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-floating-custom">
                                <select id="course_id_tentor" name="course_id" required class="@error('course_id') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach ($courses as $c)
                                        <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                                    @endforeach
                                </select>
                                <label for="course_id_tentor">Select Course</label>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-pill" style="height: 48px;">
                                <i data-lucide="check-circle" style="width:16px;height:16px;margin-right:6px;"></i>Assign Tentor
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="content-card">
                    <div class="content-card-header">
                        <span><i data-lucide="list" style="margin-right:8px;"></i>Courses & Tentors</span>
                        <span class="badge bg-primary rounded-pill">{{ count($courses) }}</span>
                    </div>
                    <div class="content-card-body p-0">
                        @if (count($courses) > 0)
                            <div class="table-responsive">
                                <table class="table-admin mb-0">
                                    <thead>
                                        <tr>
                                            <th>Course Name</th>
                                            <th>Assigned Tentor</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courses as $index => $c)
                                            <tr>
                                                <td style="color:var(--text-primary);font-weight:500;">{{ $c->title }}</td>
                                                <td>
                                                    @if ($c->tentor)
                                                        <span class="badge-role tentor">
                                                            <i data-lucide="chalkboard" style="width:12px;height:12px;"></i>{{ $c->tentor->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:0.8rem;">
                                                            <i data-lucide="x" style="width:12px;height:12px;margin-right:4px;"></i>No tentor
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-muted" style="font-size:0.85rem;">{{ $c->created_at->format('d M Y') }}</td>
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
                                <p>No courses available in the system.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
