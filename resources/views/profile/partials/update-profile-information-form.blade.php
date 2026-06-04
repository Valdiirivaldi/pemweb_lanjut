<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label fw-semibold" style="color:#1e3c72;font-size:0.88rem;">Name</label>
        <input id="name" name="name" type="text"
               class="form-control"
               value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
               style="border-radius:10px;border:2px solid #e2e8f0;padding:10px 14px;">
        @error('name')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold" style="color:#1e3c72;font-size:0.88rem;">Email</label>
        <input id="email" name="email" type="email"
               class="form-control"
               value="{{ old('email', $user->email) }}" required autocomplete="username"
               style="border-radius:10px;border:2px solid #e2e8f0;padding:10px 14px;">
        @error('email')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="mb-1" style="font-size:0.85rem;color:#6b7280;">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="btn btn-link p-0 align-baseline"
                            style="font-size:0.85rem;text-decoration:underline;color:#4e73df;">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-1" style="font-size:0.85rem;color:#059669;">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary rounded-pill px-4"
                style="background:linear-gradient(135deg,#4e73df,#224abe);border:none;font-weight:600;">
            <i class="fas fa-check me-1"></i> Save
        </button>

        @if (session('status') === 'profile-updated')
            <small class="text-success fw-semibold">
                <i class="fas fa-check-circle me-1"></i>Saved.
            </small>
        @endif
    </div>
</form>
