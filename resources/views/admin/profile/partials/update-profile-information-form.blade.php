<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="row">
    @csrf
    @method('patch')

    <div class="col-12 form-group">
        <label for="name" class="font-weight-bold">Name</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        </div>
        @error('name')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-12 form-group">
        <label for="email" class="font-weight-bold">Email Address</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        </div>
        @error('email')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 text-warning small">
                Your email address is unverified.
                <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline btn-xs">Click here to re-send the verification email.</button>
                @if (session('status') === 'verification-link-sent')
                    <div class="text-success mt-1">
                        A new verification link has been sent to your email address.
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="col-12 mt-3 d-flex align-items-center">
        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
            <i class="fas fa-save mr-1"></i> Save Changes
        </button>
        @if (session('status') === 'profile-updated')
            <span class="text-success ml-3 font-weight-bold"><i class="fas fa-check-circle"></i> Profile updated successfully</span>
        @endif
    </div>
</form>
