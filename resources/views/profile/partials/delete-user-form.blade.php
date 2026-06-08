<button type="button" class="btn btn-danger btn-pill px-4"
        data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
    <i data-lucide="trash-2" style="width:14px;height:14px;margin-right:6px;"></i> Delete Account
</button>

<div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom:1px solid var(--border-light);">
                <h6 class="modal-title fw-bold" style="color:var(--text-primary);">
                    <i data-lucide="alert-triangle" style="width:18px;height:18px;color:#e74c3c;margin-right:8px;vertical-align:middle;"></i>
                    Are you sure you want to delete your account?
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:16px;">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                    <div class="form-floating-custom">
                        <input type="password" id="delete-password" name="password" placeholder=" ">
                        <label for="delete-password">Password</label>
                        @error('password', 'userDeletion')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border-light);">
                    <button type="button" class="btn btn-outline-secondary btn-pill px-3"
                            data-bs-dismiss="modal" style="font-weight:500;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger btn-pill px-3"
                            style="font-weight:600;">
                        <i data-lucide="trash-2" style="width:14px;height:14px;margin-right:6px;"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
