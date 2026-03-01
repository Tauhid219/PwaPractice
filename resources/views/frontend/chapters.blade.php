@extends('frontend.layouts.master')
@section('title', $category->name . ' - অধ্যায়সমূহ')

@section('content')
    <!-- Page Header Start -->
    <div class="container-xxl py-5 page-header position-relative mb-5">
        <div class="container py-5">
            <h1 class="display-2 text-white animated slideInDown mb-4">{{ $category->name }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Chapters Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">অধ্যায় নির্বাচন করুন</h1>
                <p>{{ $category->name }} বিষয়ের অন্তর্ভুক্ত সকল অধ্যায়</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                @forelse($category->chapters as $chapter)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="classes-item">
                        <div class="bg-light rounded-circle w-75 mx-auto p-3 text-center mb-3 mt-4">
                            <i class="fa fa-book-open fa-3x text-primary mb-3"></i>
                            <div class="badge bg-primary text-white rounded-pill px-3 py-1">অধ্যায় {{ $chapter->order }}</div>
                        </div>
                        <div class="bg-light rounded p-4 pt-5 mt-n5 text-center">
                            <a class="d-block text-center h3 mt-3 mb-4" href="{{ route('chapter.questions', $chapter->slug) }}">{{ $chapter->name }}</a>
                            <div class="d-flex align-items-center justify-content-center mb-4">
                                <a href="{{ route('chapter.questions', $chapter->slug) }}" class="btn btn-primary px-4 py-2">শুরু করুন <i class="fa fa-arrow-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h3>দুঃখিত, এই বিষয়ে এখনো কোনো অধ্যায় যুক্ত করা হয়নি।</h3>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Chapters End -->
@endsection
