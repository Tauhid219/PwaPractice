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
                <!-- Study Progress Card -->
                <div class="bg-primary rounded p-4 mb-5 text-white d-flex flex-column flex-md-row justify-content-between align-items-center shadow-sm">
                    <div class="mb-3 mb-md-0 text-center text-md-start">
                        <h4 class="mb-1 text-white"><i class="fa fa-chart-line me-2"></i> পড়াশোনার অগ্রগতি</h4>
                        <p class="mb-0 small text-white-50">আপনার পড়া প্রশ্নের সংখ্যা এবং লেভেল শেষের রিপোর্ট এখানে দেখুন</p>
                    </div>
                    <a href="{{ route('profile.progress') }}" class="btn btn-light rounded-pill px-4 fw-bold">প্রোগ্রেস দেখুন <i class="fa fa-arrow-right ms-2"></i></a>
                </div>

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
