@extends('frontend.layouts.master')

@section('content')
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">অফলাইন!</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">ইন্টারনেট নেই</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-wifi-off display-1 text-primary"></i>
                <h1 class="display-1">Ops!</h1>
                <h1 class="mb-4">ইন্টারনেট কানেকশন নেই</h1>
                <p class="mb-4">আপনি বর্তমানে অফলাইনে আছেন। লাইভ কুইজ ও উত্তর দেখতে অ্যাপটির ইন্টারনেট সংযোগ প্রয়োজন। অনুগ্রহ করে আপনার ডাটা অথবা ওয়াই-ফাই কানেকশন চেক করুন।</p>
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <button onclick="window.location.reload()" class="btn btn-primary rounded-pill py-3 px-5">আবার চেষ্টা করুন</button>
                    <a class="btn btn-outline-primary rounded-pill py-3 px-5" href="{{ route('home') }}">হোম পেজ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
