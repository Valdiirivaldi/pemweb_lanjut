<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label fw-semibold" style="color:#1e3c72;font-size:0.88rem;">Current Password</label>
        <input id="update_password_current_password" name="current_password" type="password"
               class="form-control"
               autocomplete="current-password"
               style="border-radius:10px;border:2px solid #e2e8f0;padding:10px 14px;">
        @error('current_password', 'updatePassword')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label fw-semibold" style="color:#1e3c72;font-size:0.88rem;">New Password</label>
        <input id="update_password_password" name="password" type="password"
               class="form-control"
               autocomplete="new-password"
               style="border-radius:10px;border:2px solid #e2e8f0;padding:10px 14px;">
        @error('password', 'updatePassword')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label fw-semibold" style="color:#1e3c72;font-size:0.88rem;">Confirm Password</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
               class="form-control"
               autocomplete="new-password"
               style="border-radius:10px;border:2px solid #e2e8f0;padding:10px 14px;">
        @error('password_confirmation', 'updatePassword')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary rounded-pill px-4"
                style="background:linear-gradient(135deg,#4e73df,#224abe);border:none;font-weight:600;">
            <i class="fas fa-check me-1"></i> Save
        </button>

        @if (session('status') === 'password-updated')
            <small class="text-success fw-semibold">
                <i class="fas fa-check-circle me-1"></i>Saved.
            </small>
        @endif
    </div>
</form>
