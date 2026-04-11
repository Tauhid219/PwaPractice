@extends('frontend.layouts.master')

@section('title', 'রেজিস্ট্রেশন | জিনিয়াস কিডস - কুইজ গাইডবুক')

@section('content')
<!-- Page Header End -->
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">Register</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Register</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Register Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">Create an Account!</h1>
            <p>Join us today to access your exams and track your study progress.</p>
        </div>
        <div class="bg-light rounded p-5 wow fadeInUp" data-wow-delay="0.5s" style="max-width: 600px; margin: 0 auto;">
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control border-0" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Full Name">
                            <label for="name">Full Name</label>
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="email" class="form-control border-0" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Your Email">
                            <label for="email">Email Address</label>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password" class="form-control border-0" id="password" name="password" required autocomplete="new-password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password" class="form-control border-0" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            <label for="password_confirmation">Confirm Password</label>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-primary w-100 py-3" type="submit">Register</button>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <p>Already have an account? <a class="text-primary fw-bold" href="{{ route('login') }}">Log In</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Register End -->
@endsection
