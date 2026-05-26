@extends('layouts.dashboard')

@section('title', 'My Profile - Eduria')
@section('page-title', 'My Profile')

@section('sidebar-menu')
    @if(Auth::user()->role == 'admin')
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i>Dashboard
        </a>
    @elseif(Auth::user()->role == 'tentor')
        <a href="{{ route('tentor.dashboard') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i>Dashboard
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i>Dashboard
        </a>
    @endif
    <a href="{{ route('profile') }}" class="nav-link active">
        <i class="fas fa-user-cog"></i>Profile
    </a>
@endsection

@push('styles')
<style>
    /* ── Profile Avatar ── */
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 2.5rem;
        color: #fff;
        margin: 0 auto;
        background: linear-gradient(135deg, #4e73df, #224abe);
        flex-shrink: 0;
    }

    .profile-name {
        font-weight: 800;
        color: #1e3c72;
        font-size: 1.25rem;
    }

    .profile-email {
        color: #718096;
        font-size: 0.9rem;
    }

    .profile-meta {
        color: #a0aec0;
        font-size: 0.8rem;
    }

    .profile-meta i {
        width: 16px;
        text-align: center;
        margin-right: 4px;
    }

    .profile-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        height: 100%;
    }

    .profile-card:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    }

    /* ── Stat Cards ── */
    .profile-stat-card {
        border: none;
        border-radius: 16px;
        padding: 24px;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        height: 100%;
        text-align: center;
    }

    .profile-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    }

    .profile-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
        margin: 0 auto 12px;
    }

    .profile-stat-number {
        font-weight: 800;
        font-size: 2rem;
        color: #1e3c72;
        line-height: 1.2;
    }

    .profile-stat-label {
        color: #a0aec0;
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 4px;
    }

    /* ── Info Card ── */
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f4f8;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .info-value {
        color: #1e3c72;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* ── Edit Form Slide ── */
    .edit-form-wrap {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .edit-form-wrap.show {
        max-height: 500px;
    }

    .edit-form-wrap .edit-form-inner {
        padding-top: 16px;
        border-top: 1px solid #f0f4f8;
        margin-top: 16px;
    }

    .form-floating-custom {
        position: relative;
        margin-bottom: 16px;
    }

    .form-floating-custom .form-control {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 16px 14px 8px;
        height: auto;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
    }

    .form-floating-custom .form-control:focus {
        border-color: #4e73df;
        box-shadow: none;
    }

    .form-floating-custom label {
        position: absolute;
        top: 10px;
        left: 14px;
        font-size: 0.75rem;
        color: #a0aec0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .form-floating-custom .form-control:focus ~ label,
    .form-floating-custom .form-control:not(:placeholder-shown) ~ label {
        color: #4e73df;
    }

    /* ── Button ── */
    .btn-edit-toggle {
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-edit-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.25);
    }
</style>
@endpush

@section('content')
    <div class="row g-4">
        {{-- Left Column: Profile Card --}}
        <div class="col-lg-4">
            <div class="profile-card p-4 text-center">
                <div class="profile-avatar mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h5 class="profile-name mb-1">{{ $user->name }}</h5>
                <p class="profile-email mb-2">
                    <i class="far fa-envelope me-1"></i>{{ $user->email }}
                </p>

                <div class="mb-3">
                    <span class="badge-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </div>

                <p class="profile-meta mb-3">
                    <i class="far fa-calendar-alt"></i>
                    Member since {{ $user->created_at->format('d M Y') }}
                </p>

                <button class="btn btn-primary btn-edit-toggle" id="editToggle" type="button">
                    <i class="fas fa-pen me-1"></i> Edit Profile
                </button>

                {{-- Slide-down Edit Form --}}
                <div class="edit-form-wrap" id="editForm">
                    <div class="edit-form-inner">
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="form-floating-custom">
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $user->name) }}" placeholder=" " required>
                                <label for="name">Full Name</label>
                                @error('name')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-floating-custom">
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $user->email) }}" placeholder=" " required>
                                <label for="email">Email</label>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill">
                                    <i class="fas fa-check me-1"></i> Save
                                </button>
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="editCancel">
                                    Cancel
                                </button>
                            </div>

                            @if (session('status') === 'profile-updated')
                                <div class="alert alert-success mt-3 mb-0 py-2 text-center rounded-pill" style="font-size: 0.85rem;">
                                    <i class="fas fa-check-circle me-1"></i> Profile updated successfully!
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Stats + Info --}}
        <div class="col-lg-8">
            @if(Auth::user()->role == 'siswa')
                {{-- Stat Cards Row (Siswa only) --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="profile-stat-card">
                            <div class="profile-stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="profile-stat-number">
                                <span class="counter" data-target="{{ $totalClasses }}">0</span>
                            </div>
                            <div class="profile-stat-label">Enrolled Courses</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-stat-card">
                            <div class="profile-stat-icon" style="background: linear-gradient(135deg, #38a169, #276749);">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="profile-stat-number">
                                <span class="counter" data-target="{{ $totalCertificates }}">0</span>
                            </div>
                            <div class="profile-stat-label">Certificates</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-stat-card">
                            <div class="profile-stat-icon" style="background: linear-gradient(135deg, #d69e2e, #b7791f);">
                                <i class="fas fa-pencil-alt"></i>
                            </div>
                            <div class="profile-stat-number">
                                <span class="counter" data-target="{{ $totalQuizzes }}">0</span>
                            </div>
                            <div class="profile-stat-label">Quizzes Taken</div>
                        </div>
                    </div>
                </div>

                {{-- Account Info Card for Siswa --}}
                <div class="content-card shadow-sm">
                    <div class="card-header">
                        <span><i class="fas fa-info-circle me-2"></i>Account Information</span>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-id-card me-2"></i>User ID</span>
                            <span class="info-value">#{{ $user->id }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-qrcode me-2"></i>Unique ID</span>
                            <span class="info-value">
                                @if ($user->unique_id)
                                    <span class="badge-role" style="background:#e8ecf4;color:#1e3c72;font-family:monospace;">
                                        {{ $user->unique_id }}
                                    </span>
                                    @if(Auth::user()->isAdmin())
                                    <button class="btn btn-sm btn-link p-0 ms-1" type="button"
                                            onclick="toggleUniqueIdEdit()"
                                            style="color:#4e73df;text-decoration:none;font-size:0.75rem;">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    @endif
                                @else
                                    <span class="text-muted" style="font-size:0.85rem;">—</span>
                                @endif
                            </span>
                        </div>
                        @if(Auth::user()->isAdmin())
                        {{-- Inline edit form for Unique ID --}}
                        <div id="uniqueIdEditForm" style="display:none; padding: 8px 0 4px;">
                            <form method="post" action="{{ route('profile.unique-id') }}">
                                @csrf
                                @method('patch')
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="unique_id"
                                           value="{{ $user->unique_id }}" maxlength="20"
                                           style="border-radius:8px 0 0 8px;font-family:monospace;"
                                           placeholder="S-2026-XXXX" required>
                                    <button class="btn btn-success btn-sm" type="submit"
                                            style="border-radius:0 8px 8px 0;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                            onclick="toggleUniqueIdEdit()"
                                            style="border-radius:8px;margin-left:4px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @error('unique_id')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                                @if (session('status') === 'unique-id-updated')
                                    <small class="text-success mt-1 d-block">
                                        <i class="fas fa-check-circle me-1"></i>Unique ID updated!
                                    </small>
                                @endif
                            </form>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-envelope me-2"></i>Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-check-circle me-2"></i>Email Verification</span>
                            <span class="info-value">
                                @if ($user->email_verified_at)
                                    <span class="badge-role" style="background:#c6f6d5;color:#276749;">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge-role" style="background:#fed7d7;color:#9b2c2c;">
                                        <i class="fas fa-times-circle me-1"></i>Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-user-tag me-2"></i>Role</span>
                            <span class="info-value">
                                <span class="badge-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-calendar-plus me-2"></i>Member Since</span>
                            <span class="info-value">{{ $user->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-clock me-2"></i>Last Updated</span>
                            <span class="info-value">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Combined card: Info Summary + Account Details for Admin/Tentor --}}
                <div class="content-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid #f0f4f8;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #4e73df, #224abe); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; flex-shrink: 0;">
                                <i class="fas {{ Auth::user()->role == 'admin' ? 'fa-user-shield' : 'fa-chalkboard-teacher' }}"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1" style="color: #1e3c72;">{{ ucfirst(Auth::user()->role) }} Account</h5>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    {{ Auth::user()->role == 'admin' ? 'You are the owner and manager of the learning system.' : 'You are an instructor responsible for courses and students.' }}
                                </p>
                            </div>
                        </div>

                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-id-card me-2"></i>User ID</span>
                            <span class="info-value">#{{ $user->id }}</span>
                        </div>
                        @if ($user->role !== 'admin')
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-qrcode me-2"></i>Unique ID</span>
                            <span class="info-value">
                                @if ($user->unique_id)
                                    <span class="badge-role" style="background:#e8ecf4;color:#1e3c72;font-family:monospace;">
                                        {{ $user->unique_id }}
                                    </span>
                                    @if(Auth::user()->isAdmin())
                                    <button class="btn btn-sm btn-link p-0 ms-1" type="button"
                                            onclick="toggleUniqueIdEdit()"
                                            style="color:#4e73df;text-decoration:none;font-size:0.75rem;">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    @endif
                                @else
                                    <span class="text-muted" style="font-size:0.85rem;">—</span>
                                @endif
                            </span>
                        </div>
                        @if(Auth::user()->isAdmin())
                        {{-- Inline edit form for Unique ID --}}
                        <div id="uniqueIdEditForm" style="display:none; padding: 8px 0 4px;">
                            <form method="post" action="{{ route('profile.unique-id') }}">
                                @csrf
                                @method('patch')
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="unique_id"
                                           value="{{ $user->unique_id }}" maxlength="20"
                                           style="border-radius:8px 0 0 8px;font-family:monospace;"
                                           placeholder="S-2026-XXXX" required>
                                    <button class="btn btn-success btn-sm" type="submit"
                                            style="border-radius:0 8px 8px 0;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                            onclick="toggleUniqueIdEdit()"
                                            style="border-radius:8px;margin-left:4px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @error('unique_id')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                                @if (session('status') === 'unique-id-updated')
                                    <small class="text-success mt-1 d-block">
                                        <i class="fas fa-check-circle me-1"></i>Unique ID updated!
                                    </small>
                                @endif
                            </form>
                        </div>
                        @endif
                        @endif
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-envelope me-2"></i>Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-check-circle me-2"></i>Email Verification</span>
                            <span class="info-value">
                                @if ($user->email_verified_at)
                                    <span class="badge-role" style="background:#c6f6d5;color:#276749;">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge-role" style="background:#fed7d7;color:#9b2c2c;">
                                        <i class="fas fa-times-circle me-1"></i>Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-user-tag me-2"></i>Role</span>
                            <span class="info-value">
                                <span class="badge-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-calendar-plus me-2"></i>Member Since</span>
                            <span class="info-value">{{ $user->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-clock me-2"></i>Last Updated</span>
                            <span class="info-value">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ── Counter Animation ── */
        var counters = document.querySelectorAll('.counter');
        if (counters.length) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        var target = parseInt(el.getAttribute('data-target'));
                        if (target === 0) {
                            el.textContent = '0';
                            return;
                        }
                        var current = 0;
                        var step = Math.max(1, Math.ceil(target / 30));
                        var duration = 400;
                        var intervalTime = Math.floor(duration / (target / step));
                        var timer = setInterval(function() {
                            current += step;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            el.textContent = current;
                        }, intervalTime);
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            counters.forEach(function(c) { observer.observe(c); });
        }

        /* ── Edit Form Slide Toggle ── */
        var editToggle = document.getElementById('editToggle');
        var editForm = document.getElementById('editForm');
        var editCancel = document.getElementById('editCancel');

        if (editToggle && editForm) {
            editToggle.addEventListener('click', function() {
                editForm.classList.toggle('show');
            });
        }

        if (editCancel && editForm) {
            editCancel.addEventListener('click', function() {
                editForm.classList.remove('show');
            });
        }

        /* ── Auto-open edit form if validation errors exist ── */
        @if ($errors->any())
            editForm.classList.add('show');
        @endif
    });

    /* ── Toggle Unique ID Edit ── */
    function toggleUniqueIdEdit() {
        var el = document.getElementById('uniqueIdEditForm');
        if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endpush
