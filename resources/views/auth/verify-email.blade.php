@extends('frontend.layouts.master')
@section('title', 'ইমেইল ভেরিফিকেশন | জিনিয়াস কিডস')

@section('content')
<div class="max-w-md mx-auto py-6">
    <!-- Form Card -->
    <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-6 mb-12">
        <div class="text-center mb-6">
            <div class="text-5xl mb-2">✉️</div>
            <h1 class="text-xl font-extrabold text-slate-900 font-sans">ইমেইল ভেরিফিকেশন</h1>
            <p class="text-xs text-slate-500 font-extrabold">নিবন্ধনের জন্য ধন্যবাদ! শুরু করার আগে, অনুগ্রহ করে ইমেইলে পাঠানো লিংকে ক্লিক করে অ্যাকাউন্ট ভেরিফাই করো। ইমেইল না পেয়ে থাকলে আবার পাঠাতে পারো।</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 bg-emerald-100 border-2 border-slate-900 text-emerald-800 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-center">
                রেজিস্ট্রেশনের সময় দেওয়া ইমেইলে ভেরিফিকেশন লিংক পাঠানো হয়েছে।
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-3 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[3px_3px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer flex items-center justify-center gap-1.5">
                    ভেরিফিকেশন ইমেইল পুনরায় পাঠাও <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-xs font-extrabold text-rose-500 hover:underline decoration-none cursor-pointer">
                    লগআউট করো ↩️
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
