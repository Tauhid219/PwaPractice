@extends('frontend.layouts.master')
@section('title', 'ফলাফল - ' . $exam->title)

@section('content')
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-3 text-white animated slideInDown mb-4">লিডারবোর্ড ও ফলাফল</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0 text-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('live-exams.index') }}">লাইভ এক্সাম</a></li>
                <li class="breadcrumb-item text-white-50 active" aria-current="page">ফলাফল</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <!-- Overview Header -->
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h2 class="mb-3 text-primary">{{ $exam->title }}</h2>
            <p class="fs-5 text-muted">মোট অংশগ্রহণকারী: <strong>{{ $attempts->total() }} জন</strong></p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center mb-5" role="alert">
                <strong><i class="fa fa-check-circle me-2"></i> {{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 justify-content-center">
            <div class="col-lg-10 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-light rounded p-4 p-sm-5 shadow-sm">
                    <h3 class="mb-4 text-center border-bottom pb-3"><i class="fa fa-trophy text-warning me-2"></i> লিডারবোর্ড (Top Results)</h3>

                    <!-- Display Current User's Result Highlighted if they attempted -->
                    @if($hasAttempted && auth()->check())
                        @php
                            $myAttempt = $attempts->firstWhere('user_id', auth()->id());
                        @endphp
                        @if($myAttempt)
                            <div class="alert alert-info border-primary mb-4 text-center shadow-sm">
                                <h4 class="mb-2">আপনার প্রাপ্ত নম্বর: <span class="text-primary display-6 fw-bold">{{ round($myAttempt->score) }}</span></h4>
                                <p class="mb-0 text-dark">আপনি লিডারবোর্ডে অবস্থান করছেন!</p>
                            </div>
                        @else
                            <div class="alert alert-info text-center border-primary mb-4 p-3 border-0 shadow-sm">
                                <h5><i class="fa fa-spinner fa-spin me-2"></i> আপনার প্রাপ্ত নম্বর প্রসেসিং হচ্ছে অথবা এই পাতায় নেই।</h5>
                            </div>
                        @endif
                    @endif

                    <div class="table-responsive mt-4">
                        <table class="table table-hover align-middle bg-white text-center rounded shadow-sm overflow-hidden">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col" class="py-3">র‍্যাংক</th>
                                    <th scope="col" class="py-3">নাম</th>
                                    <th scope="col" class="py-3">নম্বর</th>
                                    <th scope="col" class="py-3">সময়</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $index => $attempt)
                                    @php
                                        $isCurrentUser = auth()->check() && auth()->id() === $attempt->user_id;
                                    @endphp
                                    <tr class="{{ $isCurrentUser ? 'table-primary fw-bold' : '' }}">
                                        <td class="py-3 fs-5">
                                            @if($index == 0 && $attempts->currentPage() == 1)
                                                <i class="fa fa-trophy text-warning fs-3 px-2"></i>
                                            @elseif($index == 1 && $attempts->currentPage() == 1)
                                                <i class="fa fa-medal text-secondary fs-4 px-2"></i>
                                            @elseif($index == 2 && $attempts->currentPage() == 1)
                                                <i class="fa fa-medal fs-4 px-2" style="color: #cd7f32 !important;"></i>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3">{{ $loop->iteration + ($attempts->currentPage() - 1) * $attempts->perPage() }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 fs-5">
                                            {{ $attempt->user->name ?? 'অজ্ঞাত' }} 
                                            @if($isCurrentUser) <span class="badge bg-success ms-2 fs-6">আপনি</span> @endif
                                        </td>
                                        <td class="py-3 fs-5 text-primary fw-bolder">{{ round($attempt->score) }}</td>
                                        <td class="py-3 text-muted">
                                            {{ $attempt->created_at->format('d M y, h:i A') }}
                                            <br><small>{{ $attempt->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-5 text-center text-muted">
                                            <i class="fa fa-inbox fa-3x mb-3 text-light"></i>
                                            <h4>এখনো কোনো ফলাফল তৈরি হয়নি।</h4>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $attempts->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
