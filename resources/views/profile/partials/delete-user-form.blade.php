<button type="button" class="btn btn-danger rounded-pill px-4"
        style="font-weight:600;"
        data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
    <i class="fas fa-trash-alt me-1"></i> Delete Account
</button>

<div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom:1px solid #f0f4f8;">
                <h6 class="modal-title fw-bold" style="color:#1e3c72;">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Are you sure you want to delete your account?
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p style="color:#6b7280;font-size:0.9rem;margin-bottom:16px;">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                    <div class="mb-2">
                        <input id="delete-password" name="password" type="password"
                               class="form-control"
                               placeholder="Password"
                               style="border-radius:10px;border:2px solid #e2e8f0;padding:10px 14px;">
                        @error('password', 'userDeletion')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f4f8;">
                    <button type="button" class="btn btn-secondary rounded-pill px-3"
                            data-bs-dismiss="modal" style="font-weight:500;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-3"
                            style="font-weight:600;">
                        <i class="fas fa-trash-alt me-1"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
