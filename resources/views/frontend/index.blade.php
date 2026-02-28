@extends('frontend.layouts.master')
@section('title', 'জিনিয়াস কিডস - কুইজ গাইডবুক')

@section('content')
    <!-- Page Header End -->
    <div class="container-xxl py-5 page-header position-relative mb-5">
        <div class="container py-5">
            <h1 class="display-2 text-white animated slideInDown mb-4">জিনিয়াস কিডস কুইজ গাইডবুক</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Categories Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">বিষয় নির্বাচন করুন</h1>
                <p>যেকোনো বিষয়ে ক্লিক করে অধ্যায় অনুযায়ী প্রশ্ন ও উত্তর পড়ুন</p>
            </div>
            
            <div class="row g-4">
                @foreach($categories as $category)
                <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="{{ route('frontend.chapters', $category->slug) }}" class="text-decoration-none">
                        <div class="facility-item h-100">
                            <div class="facility-icon bg-primary">
                                <span class="bg-primary"></span>
                                <i class="fa {{ $category->icon }} fa-3x text-primary"></i>
                                <span class="bg-primary"></span>
                            </div>
                            <div class="facility-text bg-primary">
                                <h3 class="text-primary mb-3">{{ $category->name }}</h3>
                                <p class="mb-0 text-dark">{{ $category->description }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Categories End -->
@endsection
