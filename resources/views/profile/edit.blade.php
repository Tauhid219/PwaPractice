@extends('frontend.layouts.master')
@section('title', 'My Profile')

@section('content')
<!-- Page Header End -->
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">আমার প্রোফাইল</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-light rounded p-5 mb-4 shadow-sm">
                    <h3 class="mb-4"><i class="fa fa-user text-primary me-2"></i> প্রোফাইল আপডেট করুন</h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
                
                <div class="bg-light rounded p-5 mb-4 shadow-sm">
                    <h3 class="mb-4"><i class="fa fa-lock text-primary me-2"></i> পাসওয়ার্ড পরিবর্তন</h3>
                    @include('profile.partials.update-password-form')
                </div>
                
                <div class="text-center mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                            <i class="fa fa-sign-out-alt me-2"></i> লগআউট করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
