<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3 form-floating-custom">
        <input type="text" id="name" name="name"
               value="{{ old('name', $user->name) }}" placeholder=" " required
               autocomplete="name">
        <label for="name">Name</label>
        @error('name')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3 form-floating-custom">
        <input type="email" id="email" name="email"
               value="{{ old('email', $user->email) }}" placeholder=" " required
               autocomplete="username">
        <label for="email">Email</label>
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
        <button type="submit" class="btn btn-primary btn-pill px-4">
            <i data-lucide="check" style="width:14px;height:14px;margin-right:6px;"></i> Save
        </button>

        @if (session('status') === 'profile-updated')
            <small class="text-success fw-semibold">
                <i data-lucide="check-circle" style="width:14px;height:14px;margin-right:4px;vertical-align:middle;"></i>Saved.
            </small>
        @endif
    </div>
</form>
