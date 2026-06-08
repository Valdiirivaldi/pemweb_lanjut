<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3 form-floating-custom">
        <input type="password" id="update_password_current_password" name="current_password"
               placeholder=" " autocomplete="current-password">
        <label for="update_password_current_password">Current Password</label>
        @error('current_password', 'updatePassword')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3 form-floating-custom">
        <input type="password" id="update_password_password" name="password"
               placeholder=" " autocomplete="new-password">
        <label for="update_password_password">New Password</label>
        @error('password', 'updatePassword')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3 form-floating-custom">
        <input type="password" id="update_password_password_confirmation" name="password_confirmation"
               placeholder=" " autocomplete="new-password">
        <label for="update_password_password_confirmation">Confirm Password</label>
        @error('password_confirmation', 'updatePassword')
            <small class="text-danger mt-1 d-block">{{ $message }}</small>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary btn-pill px-4">
            <i data-lucide="check" style="width:14px;height:14px;margin-right:6px;"></i> Save
        </button>

        @if (session('status') === 'password-updated')
            <small class="text-success fw-semibold">
                <i data-lucide="check-circle" style="width:14px;height:14px;margin-right:4px;vertical-align:middle;"></i>Saved.
            </small>
        @endif
    </div>
</form>
