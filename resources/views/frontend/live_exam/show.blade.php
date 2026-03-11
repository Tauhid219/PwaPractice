@extends('frontend.layouts.master')
@section('title', $exam->title)

@section('content')
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">{{ $exam->title }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0 text-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('live-exams.index') }}">লাইভ এক্সাম</a></li>
                <li class="breadcrumb-item text-white-50 active" aria-current="page">{{ Str::limit($exam->title, 20) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light p-5 rounded">
                    <h2 class="mb-4">পরীক্ষার বিস্তারিত তথ্য</h2>
                    <p class="fs-5">{{ $exam->description }}</p>

                    <div class="alert alert-info mt-4">
                        <i class="fa fa-info-circle me-2"></i> <strong>নিয়মাবলী:</strong>
                        <ul class="mt-2 mb-0">
                            <li>পরীক্ষা শুরু করার পর নির্দিষ্ট সময়ের মধ্যে শেষ করতে হবে।</li>
                            <li>একবার সাবমিট করলে আর পরিবর্তন করা যাবে না।</li>
                            <li>প্রতিটি প্রশ্নের সঠিক উত্তর দিতে হবে।</li>
                        </ul>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-primary text-white p-5 rounded text-center">
                    <i class="fa fa-clock display-4 mb-3"></i>
                    <h4 class="text-white mb-3">সময়সূচী</h4>
                    
                    <ul class="list-unstyled text-start mb-4 fs-5">
                        <li class="mb-2"><i class="fa fa-calendar-check me-2"></i> <strong>শুরু:</strong> <br> {{ $exam->start_time->format('d M Y, h:i A') }}</li>
                        <li class="mb-2"><i class="fa fa-calendar-times me-2"></i> <strong>শেষ:</strong> <br> {{ $exam->end_time->format('d M Y, h:i A') }}</li>
                        <li class="mb-2"><i class="fa fa-hourglass-half me-2"></i> <strong>সময়:</strong> {{ $exam->duration_minutes }} মিনিট</li>
                    </ul>

                    @php
                        $now = now();
                        $hasAttempted = \App\Models\LiveExamAttempt::where('user_id', auth()->id())->where('live_exam_id', $exam->id)->exists();
                    @endphp

                    @if($hasAttempted)
                        <div class="alert alert-warning text-dark border-0">
                            <strong>আপনি ইতিমধ্যে পরীক্ষায় অংশ নিয়েছেন।</strong>
                        </div>
                    @elseif($now->isBefore($exam->start_time))
                         <button class="btn btn-light text-primary w-100 py-3 fw-bold disabled">পরীক্ষা শুরু হয়নি</button>
                    @elseif($now->isAfter($exam->end_time))
                         <button class="btn btn-danger w-100 py-3 fw-bold disabled">সময় শেষ</button>
                    @else
                        <a href="{{ route('live-exams.join', $exam->id) }}" class="btn btn-warning text-dark w-100 py-3 fw-bold shadow">
                            পরীক্ষা শুরু করুন <i class="fa fa-play ms-2"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
