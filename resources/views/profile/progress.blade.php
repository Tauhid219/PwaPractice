@extends('frontend.layouts.master')
@section('title', 'আমার প্রোগ্রেস')

@section('content')
<!-- Page Header Start -->
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5 text-center">
        <h1 class="display-2 text-white animated slideInDown mb-4">আমার পড়াশোনার অগ্রগতি</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.edit') }}">প্রোফাইল</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">প্রোগ্রেস রিপোর্ট</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container-xxl py-5">
    <div class="container">
        <!-- Summary Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-lg-2 col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="p-4 rounded shadow-sm border-start border-danger border-5 bg-light h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger-soft rounded-circle p-3 me-3">
                            <i class="fa fa-fire text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">ধারা (Streak)</h6>
                            <h3 class="mb-0">{{ $user->current_streak }}</h3>
                            <small class="text-danger-emphasis">দিন</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 wow fadeIn" data-wow-delay="0.2s">
                <div class="p-4 rounded shadow-sm border-start border-primary border-5 bg-light h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-soft rounded-circle p-3 me-2">
                            <i class="fa fa-book-open text-primary fa-xl"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">পড়া হয়েছে</h6>
                            <h3 class="mb-0">{{ $totalRead }}</h3>
                            <small class="text-primary-emphasis text-nowrap">টি প্রশ্ন</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                <div class="p-4 rounded shadow-sm border-start border-success border-5 bg-light h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-soft rounded-circle p-3 me-2">
                            <i class="fa fa-check-double text-success fa-xl"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">শেষ লেভেল</h6>
                            <h3 class="mb-0">{{ $progressStats['total_completed_levels'] }}</h3>
                            <small class="text-success-emphasis text-nowrap">টি</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="p-4 rounded shadow-sm border-start border-warning border-5 bg-light h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning-soft rounded-circle p-3 me-3">
                            <i class="fa fa-star text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">চলমান লেভেল</h6>
                            <h3 class="mb-0">{{ $progressStats['total_active_levels'] }}</h3>
                            <small class="text-warning-emphasis">টি লেভেল</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                <div class="p-4 rounded shadow-sm border-start border-info border-5 bg-light h-100">
                    <div class="d-flex align-items-center">
                        <div class="bg-info-soft rounded-circle p-3 me-3">
                            <i class="fa fa-history text-info fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">কুইজ দিয়েছেন</h6>
                            <h3 class="mb-0">{{ $quizAttempts->total() }}</h3>
                            <small class="text-info-emphasis">বার</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievement Badges Section -->
        <div class="row g-4 mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="col-12">
                <div class="bg-light rounded p-4 shadow-sm border-top border-primary border-5">
                    <h3 class="mb-4"><i class="fa fa-award text-primary me-2"></i> অর্জিত ব্যাজসমূহ</h3>
                    <div class="d-flex flex-wrap gap-4">
                        <!-- 7 Day Streak Badge -->
                        <div class="badge-item text-center {{ $user->current_streak >= config('quiz.milestones.streak_bronze') ? '' : 'grayscale opacity-50' }}" style="width: 100px;">
                            <div class="badge-icon mb-2">
                                <img src="https://cdn-icons-png.flaticon.com/512/616/616412.png" alt="7 Days" width="60">
                            </div>
                            <small class="fw-bold d-block">{{ config('quiz.milestones.streak_bronze') }} দিনের ধারা</small>
                            @if($user->current_streak >= config('quiz.milestones.streak_bronze'))
                                <span class="badge bg-success rounded-pill small">অর্জিত</span>
                            @else
                                <span class="badge bg-secondary rounded-pill small">বাকি</span>
                            @endif
                        </div>

                        <!-- 30 Day Streak Badge -->
                        <div class="badge-item text-center {{ $user->current_streak >= config('quiz.milestones.streak_silver') ? '' : 'grayscale opacity-50' }}" style="width: 100px;">
                            <div class="badge-icon mb-2">
                                <img src="https://cdn-icons-png.flaticon.com/512/10432/10432486.png" alt="30 Days" width="60">
                            </div>
                            <small class="fw-bold d-block">{{ config('quiz.milestones.streak_silver') }} দিনের ধারা</small>
                            @if($user->current_streak >= config('quiz.milestones.streak_silver'))
                                <span class="badge bg-success rounded-pill small">অর্জিত</span>
                            @else
                                <span class="badge bg-secondary rounded-pill small">বাকি</span>
                            @endif
                        </div>

                        <!-- 100 Quiz Attempts Badge -->
                        <div class="badge-item text-center {{ $quizAttempts->total() >= config('quiz.milestones.quiz_century') ? '' : 'grayscale opacity-50' }}" style="width: 100px;">
                            <div class="badge-icon mb-2">
                                <img src="https://cdn-icons-png.flaticon.com/512/4345/4345094.png" alt="100 Quizzes" width="60">
                            </div>
                            <small class="fw-bold d-block">শতক কুইজ</small>
                            @if($quizAttempts->total() >= config('quiz.milestones.quiz_century'))
                                <span class="badge bg-success rounded-pill small">অর্জিত</span>
                            @else
                                <span class="badge bg-secondary rounded-pill small">বাকি</span>
                            @endif
                        </div>

                        <!-- Level Master (10+ levels completed) -->
                        <div class="badge-item text-center {{ $progressStats['total_completed_levels'] >= config('quiz.milestones.level_master') ? '' : 'grayscale opacity-50' }}" style="width: 100px;">
                            <div class="badge-icon mb-2">
                                <img src="https://cdn-icons-png.flaticon.com/512/2583/2583344.png" alt="Level Master" width="60">
                            </div>
                            <small class="fw-bold d-block">লেভেল মাস্টার</small>
                            @if($progressStats['total_completed_levels'] >= config('quiz.milestones.level_master'))
                                <span class="badge bg-success rounded-pill small">অর্জিত</span>
                            @else
                                <span class="badge bg-secondary rounded-pill small">বাকি</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <!-- Quiz History Table -->
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <h3 class="mb-4 border-bottom pb-2"><i class="fa fa-history text-primary me-2"></i> কুইজ হিস্ট্রি</h3>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>তারিখ</th>
                                    <th>বিষয় ও লেভেল</th>
                                    <th>স্কোর</th>
                                    <th class="text-center">ফলাফল</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizAttempts as $attempt)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $attempt->created_at->format('d M, Y') }}</div>
                                        <small class="text-muted">{{ $attempt->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $attempt->category->name ?? 'N/A' }}</div>
                                        <span class="badge bg-primary-soft text-primary">{{ $attempt->level->name ?? 'Level' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $attempt->score }} / {{ $attempt->total_questions }}</div>
                                        <div class="progress" style="height: 4px; width: 100px;">
                                            <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}" 
                                                 style="width: {{ $attempt->total_questions > 0 ? ($attempt->score / $attempt->total_questions) * 100 : 0 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($attempt->passed)
                                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="fa fa-check-circle me-1"></i> উত্তীর্ণ</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fa fa-times-circle me-1"></i> অনুত্তীর্ণ</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <img src="{{ asset('frontend/img/empty.png') }}" alt="" style="width: 100px; opacity: 0.3;">
                                        <p class="text-muted mt-3">আপনি এখনো কোনো কুইজ দেননি।</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $quizAttempts->links() }}
                    </div>
                </div>
            </div>

            <!-- Subject-wise Progress -->
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-light rounded p-4 h-100 shadow-sm">
                    <h3 class="mb-4 border-bottom pb-2"><i class="fa fa-chart-pie text-primary me-2"></i> বিষয়ভিত্তিক অগ্রগতি</h3>
                    <div class="progress-list" style="max-height: 600px; overflow-y: auto;">
                        @forelse($userProgress->groupBy('category_id') as $categoryId => $progresses)
                            <div class="category-progress-block mb-4">
                                <h5 class="text-primary fw-bold mb-3"><i class="fa fa-folder-open me-2"></i>{{ $progresses->first()->category->name ?? 'Category' }}</h5>
                                <div class="list-group list-group-flush rounded border ring-light">
                                    @foreach($progresses as $progress)
                                        <div class="list-group-item d-flex justify-content-between align-items-center bg-white">
                                            <span class="fw-medium">{{ $progress->level->name ?? 'Level' }}</span>
                                            @if($progress->status == 'completed')
                                                <span class="badge bg-success-soft text-success rounded-pill small"><i class="fa fa-check me-1"></i> শেষ</span>
                                            @elseif($progress->status == 'active')
                                                <span class="badge bg-warning-soft text-warning rounded-pill small"><i class="fa fa-spinner fa-spin me-1"></i> চলমান</span>
                                            @else
                                                <span class="badge bg-light text-muted rounded-pill small"><i class="fa fa-lock me-1"></i> লক</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="text-muted">কোনো প্রোগ্রেস হিস্ট্রি নেই।</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft { background-color: rgba(0, 123, 255, 0.1) !important; }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1) !important; }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-info-soft { background-color: rgba(23, 162, 184, 0.1) !important; }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; }
    .text-primary-emphasis { color: #0056b3 !important; font-weight: 500; }
    .text-success-emphasis { color: #1e7e34 !important; font-weight: 500; }
    .text-warning-emphasis { color: #856404 !important; font-weight: 500; }
    .text-info-emphasis { color: #117a8b !important; font-weight: 500; }
    .text-danger-emphasis { color: #a72834 !important; font-weight: 500; }
    
    .grayscale { filter: grayscale(100%); }
    .badge-item { transition: transform 0.3s ease; }
    .badge-item:hover { transform: translateY(-5px); }
    
    .category-progress-block h5 {
        font-size: 1.1rem;
    }
    .wow {
        visibility: hidden;
    }
</style>
@endsection
