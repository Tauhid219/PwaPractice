<x-guest-layout>
    <p class="login-box-msg">Sign in to start your session</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3 text-success text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus autocomplete="username">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row items-center">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Log in') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <div class="social-auth-links text-center mt-2 mb-3">
        <p>- OR -</p>
    </div>

    @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
        </p>
    @endif
    
    <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">{{ __('Register a new account') }}</a>
    </p>
</x-guest-layout>
