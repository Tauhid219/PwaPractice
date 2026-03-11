@extends('frontend.layouts.master')
@section('title', $category->name . ' - লেভেলসমূহ')

@section('content')
    <!-- Page Header Start -->
    <div class="container-xxl py-5 page-header position-relative mb-5">
        <div class="container py-5">
            <h1 class="display-2 text-white animated slideInDown mb-4">{{ $category->name }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0 text-white">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">হোম</a></li>
                    <li class="breadcrumb-item text-white-50 active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Levels Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                @include('frontend.layouts.sidebar')
                <div class="col-lg-9">
                    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="mb-3">লেভেল নির্বাচন করুন</h1>
                        <p>{{ $category->description }}</p>
                    </div>
                    
                    <div class="row g-4 justify-content-center">
                        @forelse($levels as $index => $level)
                        @php
                            $isLocked = true;
                            if ($level->is_free) {
                                $isLocked = false;
                            } elseif (auth()->check()) {
                                $progress = \App\Models\UserProgress::where('user_id', auth()->id())
                                                ->where('category_id', $category->id)
                                                ->where('level_id', $level->id)
                                                ->first();
                                if ($progress && $progress->status !== 'locked') {
                                    $isLocked = false;
                                }
                            }
                        @endphp
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="classes-item {{ $isLocked ? 'opacity-75' : '' }}">
                                <div class="bg-light rounded-circle w-75 mx-auto p-3 text-center mb-3 mt-4 position-relative">
                                    <i class="fa {{ $isLocked ? 'fa-lock' : 'fa-star' }} fa-3x {{ $isLocked ? 'text-secondary' : 'text-primary' }} mb-3 mt-2"></i>
                                </div>
                                <div class="bg-light rounded p-4 pt-5 mt-n5 text-center">
                                    <h3 class="mt-3 mb-4 {{ $isLocked ? 'text-muted' : '' }}">{{ $level->name }}</h3>
                                    <div class="d-flex align-items-center justify-content-center mb-4">
                                        @if($isLocked)
                                            <button class="btn btn-secondary px-4 py-2" disabled>লক করা <i class="fa fa-lock ms-2"></i></button>
                                        @else
                                            <a href="{{ route('level.questions', ['slug' => $category->slug, 'level' => $level->id]) }}" class="btn btn-primary px-4 py-2">শুরু করুন <i class="fa fa-arrow-right ms-2"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <h3>দুঃখিত, এই ক্যাটাগরিতে এখনো কোনো লেভেল যুক্ত করা হয়নি।</h3>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Levels End -->
@endsection
