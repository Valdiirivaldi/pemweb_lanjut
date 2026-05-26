@extends('layouts.dashboard')

@section('title', 'Edit Profile - Eduria')
@section('page-title', 'Edit Profile')

@section('sidebar-menu')
    <a href="{{ route('profile') }}"
       class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i>Profile
    </a>
    @if(Auth::user()->role == 'tentor')
        <a href="{{ route('tentor.dashboard') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i>Dashboard
        </a>
    @elseif(Auth::user()->role == 'admin')
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i>Dashboard
        </a>
    @else
        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="fas fa-chart-pie"></i>Dashboard
        </a>
    @endif
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Profile Information --}}
            <div class="content-card shadow-sm mb-4">
                <div class="card-header">
                    <span><i class="fas fa-user me-2"></i>Profile Information</span>
                </div>
                <div class="card-body">
                    <p class="text-muted" style="font-size:0.88rem;margin-bottom:20px;">
                        Update your account's profile information and email address.
                    </p>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="content-card shadow-sm mb-4">
                <div class="card-header">
                    <span><i class="fas fa-lock me-2"></i>Update Password</span>
                </div>
                <div class="card-body">
                    <p class="text-muted" style="font-size:0.88rem;margin-bottom:20px;">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="content-card shadow-sm mb-4" style="border-left: 4px solid #dc3545;">
                <div class="card-header" style="background:#fff5f5;">
                    <span><i class="fas fa-trash-alt me-2 text-danger"></i>Delete Account</span>
                </div>
                <div class="card-body">
                    <p class="text-muted" style="font-size:0.88rem;margin-bottom:20px;">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Before deleting your account, please download any data or information that you wish to retain.
                    </p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
