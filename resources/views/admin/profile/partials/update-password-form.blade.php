<form method="post" action="{{ route('password.update') }}" class="row">
    @csrf
    @method('put')

    <div class="col-12 form-group">
        <label for="update_password_current_password" class="font-weight-bold">Current Password</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password" placeholder="Current password">
        </div>
        @if ($errors->updatePassword->has('current_password'))
            <span class="invalid-feedback d-block">{{ $errors->updatePassword->first('current_password') }}</span>
        @endif
    </div>

    <div class="col-12 form-group">
        <label for="update_password_password" class="font-weight-bold">New Password</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
            </div>
            <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password" placeholder="New password">
        </div>
        @if ($errors->updatePassword->has('password'))
            <span class="invalid-feedback d-block">{{ $errors->updatePassword->first('password') }}</span>
        @endif
    </div>

    <div class="col-12 form-group">
        <label for="update_password_password_confirmation" class="font-weight-bold">Confirm New Password</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-check-double"></i></span>
            </div>
            <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Confirm new password">
        </div>
        @if ($errors->updatePassword->has('password_confirmation'))
            <span class="invalid-feedback d-block">{{ $errors->updatePassword->first('password_confirmation') }}</span>
        @endif
    </div>

    <div class="col-12 mt-3 d-flex align-items-center">
        <button type="submit" class="btn btn-warning px-4 shadow-sm font-weight-bold">
            <i class="fas fa-sync-alt mr-1"></i> Update Password
        </button>
        @if (session('status') === 'password-updated')
            <span class="text-success ml-3 font-weight-bold"><i class="fas fa-check-circle"></i> Password updated successfully</span>
        @endif
    </div>
</form>
