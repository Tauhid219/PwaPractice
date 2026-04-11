@extends('frontend.layouts.master')

@section('title', 'লগিন | জিনিয়াস কিডস - কুইজ গাইডবুক')

@section('content')
<!-- Page Header End -->
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">Student Login</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Login</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Login Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">Welcome Back!</h1>
            <p>Please login to continue to your student profile and access your exams.</p>
        </div>
        <div class="bg-light rounded p-5 wow fadeInUp" data-wow-delay="0.5s" style="max-width: 600px; margin: 0 auto;">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="email" class="form-control border-0" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Your Email">
                            <label for="email">Email Address</label>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password" class="form-control border-0" id="password" name="password" required autocomplete="current-password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label" for="remember_me">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button class="btn btn-primary w-100 py-3" type="submit">Log in</button>
                    </div>
                    @if (Route::has('password.request'))
                    <div class="col-12 text-center mt-3">
                        <a class="text-primary text-decoration-none" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                    @endif
                    <div class="col-12 text-center mt-2">
                        <p>Don't have an account? <a class="text-primary fw-bold" href="{{ route('register') }}">Register Here</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Login End -->
@endsection
