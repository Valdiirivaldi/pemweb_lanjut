@extends('layouts.dashboard')

@section('title', 'Edit Profile - Eduria')
@section('page-title', 'Edit Profile')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('profile') }}">Profile</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Edit Profile</span>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Profile Information --}}
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <span><i data-lucide="user" style="margin-right:8px;"></i>Profile Information</span>
                </div>
                <div class="content-card-body">
                    <p class="text-muted" style="font-size:0.88rem;margin-bottom:20px;">
                        Update your account's profile information and email address.
                    </p>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="content-card mb-4">
                <div class="content-card-header">
                    <span><i data-lucide="lock" style="margin-right:8px;"></i>Update Password</span>
                </div>
                <div class="content-card-body">
                    <p class="text-muted" style="font-size:0.88rem;margin-bottom:20px;">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="content-card mb-4" style="border-left: 4px solid #dc3545;">
                <div class="content-card-header" style="background:#fff5f5;">
                    <span><i data-lucide="trash-2" style="margin-right:8px;color:#e74c3c;"></i>Delete Account</span>
                </div>
                <div class="content-card-body">
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
