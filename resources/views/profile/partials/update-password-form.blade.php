<form method="post" action="{{ route('password.update') }}" class="row g-3">
    @csrf
    @method('put')

    <div class="col-12">
        <label for="update_password_current_password" class="form-label text-dark fw-bold">বর্তমান পাসওয়ার্ড</label>
        <input type="password" class="form-control" id="update_password_current_password" name="current_password" autocomplete="current-password">
        @if ($errors->updatePassword->has('current_password'))
            <div class="text-danger mt-1"><small>{{ $errors->updatePassword->first('current_password') }}</small></div>
        @endif
    </div>

    <div class="col-12">
        <label for="update_password_password" class="form-label text-dark fw-bold">নতুন পাসওয়ার্ড</label>
        <input type="password" class="form-control" id="update_password_password" name="password" autocomplete="new-password">
        @if ($errors->updatePassword->has('password'))
            <div class="text-danger mt-1"><small>{{ $errors->updatePassword->first('password') }}</small></div>
        @endif
    </div>

    <div class="col-12">
        <label for="update_password_password_confirmation" class="form-label text-dark fw-bold">পাসওয়ার্ড নিশ্চিত করুন</label>
        <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
        @if ($errors->updatePassword->has('password_confirmation'))
            <div class="text-danger mt-1"><small>{{ $errors->updatePassword->first('password_confirmation') }}</small></div>
        @endif
    </div>

    <div class="col-12 mt-4 d-flex align-items-center">
        <button type="submit" class="btn btn-primary py-2 px-4 rounded-pill">পাসওয়ার্ড পরিবর্তন করুন</button>
        @if (session('status') === 'password-updated')
            <span class="text-success ms-3"><i class="fa fa-check"></i> <b>সফলভাবে সংরক্ষিত</b></span>
        @endif
    </div>
</form>
