@extends('frontend.layouts.master')
@section('title', $chapter->name . ' - প্রশ্ন ও উত্তর')

@section('content')
    <!-- Page Header Start -->
    <div class="container-xxl py-5 page-header position-relative mb-5">
        <div class="container py-5">
            <h1 class="display-2 text-white animated slideInDown mb-4">{{ $chapter->name }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('frontend.chapters', $chapter->category->slug) }}">{{ $chapter->category->name }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $chapter->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Questions Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">প্রশ্ন ও উত্তর</h1>
                <p>{{ $chapter->category->name }} > {{ $chapter->name }}</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="accordion" id="questionsAccordion">
                        @forelse($questions as $index => $question)
                        <div class="accordion-item shadow-sm mb-3 border-0 rounded">
                            <h2 class="accordion-header" id="heading-{{ $index }}">
                                <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} rounded fw-bold fs-5 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $index }}">
                                    <span class="text-primary me-2">প্রশ্ন {{ $index + 1 }}:</span> {{ $question->question_text }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $index }}" data-bs-parent="#questionsAccordion">
                                <div class="accordion-body bg-light rounded-bottom text-dark fs-5">
                                    <span class="fw-bold text-success me-2">উত্তর:</span> {{ $question->answer_text }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <h3>দুঃখিত, এই অধ্যায়ে এখনো কোনো প্রশ্ন যুক্ত করা হয়নি।</h3>
                        </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ route('frontend.chapters', $chapter->category->slug) }}" class="btn btn-outline-primary px-4 py-2"><i class="fa fa-arrow-left me-2"></i> অধ্যায় তালিকায় ফিরে যান</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Questions End -->
@endsection
