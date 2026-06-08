@extends('layouts.dashboard')

@section('title', 'My Profile - Eduria')
@section('page-title', 'My Profile')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">My Profile</span>
@endsection

@push('styles')
<style>
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
        color: var(--text-primary);
        font-size: 1.25rem;
    }

    .profile-email {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .profile-meta {
        color: var(--text-subtle);
        font-size: 0.8rem;
    }

    .profile-meta svg {
        width: 14px;
        height: 14px;
        vertical-align: middle;
        margin-right: 4px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label svg {
        width: 16px;
        height: 16px;
        color: var(--text-subtle);
        flex-shrink: 0;
    }

    .info-value {
        color: var(--text-primary);
        font-size: 0.9rem;
        font-weight: 600;
    }

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
        border-top: 1px solid var(--border-light);
        margin-top: 16px;
    }

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
            <div class="content-card">
                <div class="content-card-body text-center">
                    <div class="profile-avatar mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h5 class="profile-name mb-1">{{ $user->name }}</h5>
                    <p class="profile-email mb-2">
                        <i data-lucide="mail" style="width:14px;height:14px;vertical-align:middle;margin-right:4px;"></i>{{ $user->email }}
                    </p>

                    <div class="mb-3">
                        <span class="badge-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </div>

                    <p class="profile-meta mb-3">
                        <i data-lucide="calendar"></i>
                        Member since {{ $user->created_at->format('d M Y') }}
                    </p>

                    <button class="btn btn-primary btn-edit-toggle" id="editToggle" type="button">
                        <i data-lucide="pencil" style="width:14px;height:14px;margin-right:6px;"></i> Edit Profile
                    </button>

                    {{-- Slide-down Edit Form --}}
                    <div class="edit-form-wrap" id="editForm">
                        <div class="edit-form-inner">
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')

                                <div class="form-floating-custom">
                                    <input type="text" id="name" name="name"
                                           value="{{ old('name', $user->name) }}" placeholder=" " required>
                                    <label for="name">Full Name</label>
                                    @error('name')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-floating-custom">
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email', $user->email) }}" placeholder=" " required>
                                    <label for="email">Email</label>
                                    @error('email')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary flex-grow-1 btn-pill">
                                        <i data-lucide="check" style="width:14px;height:14px;margin-right:6px;"></i> Save
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-pill px-4" id="editCancel">
                                        Cancel
                                    </button>
                                </div>

                                @if (session('status') === 'profile-updated')
                                    <div class="alert alert-success mt-3 mb-0 py-2 text-center rounded-pill" style="font-size: 0.85rem;">
                                        <i data-lucide="check-circle" style="width:14px;height:14px;margin-right:6px;"></i> Profile updated successfully!
                                    </div>
                                @endif
                            </form>
                        </div>
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
                        <div class="stat-card text-center">
                            <div class="stat-card-icon mx-auto mb-3" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                <i data-lucide="book-open"></i>
                            </div>
                            <div class="stat-card-number">
                                <span class="counter" data-target="{{ $totalClasses }}">0</span>
                            </div>
                            <div class="stat-card-label">Enrolled Courses</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <div class="stat-card-icon mx-auto mb-3" style="background: linear-gradient(135deg, #38a169, #276749);">
                                <i data-lucide="award"></i>
                            </div>
                            <div class="stat-card-number">
                                <span class="counter" data-target="{{ $totalCertificates }}">0</span>
                            </div>
                            <div class="stat-card-label">Certificates</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <div class="stat-card-icon mx-auto mb-3" style="background: linear-gradient(135deg, #d69e2e, #b7791f);">
                                <i data-lucide="pencil"></i>
                            </div>
                            <div class="stat-card-number">
                                <span class="counter" data-target="{{ $totalQuizzes }}">0</span>
                            </div>
                            <div class="stat-card-label">Quizzes Taken</div>
                        </div>
                    </div>
                </div>

                {{-- Account Info Card for Siswa --}}
                <div class="content-card">
                    <div class="content-card-header">
                        <span><i data-lucide="info" style="margin-right:8px;"></i>Account Information</span>
                    </div>
                    <div class="content-card-body">
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="id-card"></i>User ID</span>
                            <span class="info-value">#{{ $user->id }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="qrcode"></i>Unique ID</span>
                            <span class="info-value">
                                @if ($user->unique_id)
                                    <span class="badge-role" style="background:#e8ecf4;color:var(--text-primary);font-family:monospace;">
                                        {{ $user->unique_id }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:0.85rem;">—</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="mail"></i>Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="check-circle"></i>Email Verification</span>
                            <span class="info-value">
                                @if ($user->email_verified_at)
                                    <span class="badge-role" style="background:#c6f6d5;color:#276749;">
                                        <i data-lucide="check-circle" style="width:12px;height:12px;"></i>Verified
                                    </span>
                                @else
                                    <span class="badge-role" style="background:#fed7d7;color:#9b2c2c;">
                                        <i data-lucide="x-circle" style="width:12px;height:12px;"></i>Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="user-tag"></i>Role</span>
                            <span class="info-value">
                                <span class="badge-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="calendar-plus"></i>Member Since</span>
                            <span class="info-value">{{ $user->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="clock"></i>Last Updated</span>
                            <span class="info-value">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Combined card: Info Summary + Account Details for Admin/Tentor --}}
                <div class="content-card">
                    <div class="content-card-body">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid var(--border-light);">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #4e73df, #224abe); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; flex-shrink: 0;">
                                <i data-lucide="{{ Auth::user()->role == 'admin' ? 'shield' : 'chalkboard' }}" style="width:24px;height:24px;"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--text-primary);">{{ ucfirst(Auth::user()->role) }} Account</h5>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    {{ Auth::user()->role == 'admin' ? 'You are the owner and manager of the learning system.' : 'You are an instructor responsible for courses and students.' }}
                                </p>
                            </div>
                        </div>

                        <div class="info-item">
                            <span class="info-label"><i data-lucide="id-card"></i>User ID</span>
                            <span class="info-value">#{{ $user->id }}</span>
                        </div>
                        @if ($user->role !== 'admin')
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="qrcode"></i>Unique ID</span>
                            <span class="info-value">
                                @if ($user->unique_id)
                                    <span class="badge-role" style="background:#e8ecf4;color:var(--text-primary);font-family:monospace;">
                                        {{ $user->unique_id }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:0.85rem;">—</span>
                                @endif
                            </span>
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="mail"></i>Email</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="check-circle"></i>Email Verification</span>
                            <span class="info-value">
                                @if ($user->email_verified_at)
                                    <span class="badge-role" style="background:#c6f6d5;color:#276749;">
                                        <i data-lucide="check-circle" style="width:12px;height:12px;"></i>Verified
                                    </span>
                                @else
                                    <span class="badge-role" style="background:#fed7d7;color:#9b2c2c;">
                                        <i data-lucide="x-circle" style="width:12px;height:12px;"></i>Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="user-tag"></i>Role</span>
                            <span class="info-value">
                                <span class="badge-role {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="calendar-plus"></i>Member Since</span>
                            <span class="info-value">{{ $user->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i data-lucide="clock"></i>Last Updated</span>
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

        @if ($errors->any())
            editForm.classList.add('show');
        @endif
    });
</script>
@endpush
