@extends('frontend.layouts.master')
@section('title', 'লাইভ এক্সাম')

@section('content')
<!-- Page Header Start -->
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">লাইভ এক্সাম সমূহ</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0 text-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">হোম</a></li>
                <li class="breadcrumb-item text-white-50 active" aria-current="page">লাইভ এক্সাম</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">আসন্ন এবং চলমান পরীক্ষাসমূহ</h1>
            <p>আপনার মেধা যাচাই করুন এবং বিশেষ পুরস্কার জিতে নিন!</p>
        </div>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4 justify-content-center">
            @forelse($activeExams as $exam)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded p-4 h-100 d-flex flex-column text-center shadow-sm">
                        <i class="fa fa-laptop-code text-primary display-4 mb-3"></i>
                        <h4 class="mb-3">{{ $exam->title }}</h4>
                        <p class="text-muted">{{ Str::limit($exam->description ?? '', 80) }}</p>
                        
                        <div class="mt-auto">
                            <ul class="list-unstyled text-start mb-4">
                                <li><i class="fa fa-calendar-alt text-primary me-2"></i> {{ $exam->start_time->format('d M, Y') }}</li>
                                <li><i class="fa fa-clock text-primary me-2"></i> {{ $exam->start_time->format('h:i A') }} - {{ $exam->end_time->format('h:i A') }}</li>
                                <li><i class="fa fa-hourglass-half text-primary me-2"></i> সময়: {{ $exam->duration_minutes }} মিনিট</li>
                            </ul>
                            
                            @php
                                $hasAttempted = \App\Models\LiveExamAttempt::where('user_id', auth()->id())->where('live_exam_id', $exam->id)->exists();
                                $now = now();
                            @endphp

                            @if($hasAttempted || $now->isAfter($exam->end_time))
                                <a href="{{ route('live-exams.results', $exam->id) }}" class="btn btn-success w-100">ফলাফল দেখুন <i class="fa fa-trophy ms-2"></i></a>
                            @elseif($now->isBefore($exam->start_time))
                                <a href="{{ route('live-exams.show', $exam->id) }}" class="btn btn-secondary w-100">শুরু হয়নি (বিস্তারিত)</a>
                            @else
                                <a href="{{ route('live-exams.show', $exam->id) }}" class="btn btn-primary w-100 pulse-animation">বিস্তারিত দেখুন</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3>বর্তমানে কোনো লাইভ এক্সাম চলছে না।</h3>
                    <p class="text-muted">নতুন পরীক্ষার আপডেটের জন্য চোখ রাখুন।</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
