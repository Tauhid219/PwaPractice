<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unlock Premium Access | {{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-slate-50">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Logo or Branding could go here -->
        <div class="w-full max-w-md">
            <!-- Shadcn-style Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 flex flex-col space-y-1.5">
                    <h3 class="font-semibold tracking-tight text-2xl text-slate-900">App Access Required</h3>
                    <p class="text-sm text-slate-500">
                        To download our app and get full access to all questions and live exams, 
                        a small payment is required.
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <div class="flex items-center justify-center py-6 bg-slate-50 rounded-lg border border-slate-100 mb-6">
                        <div class="text-4xl font-bold text-slate-900 tracking-tighter">
                            ৳50<span class="text-lg font-normal text-slate-500 tracking-normal"> BDT</span>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md text-sm mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('payment.pay') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center w-full whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-950 disabled:pointer-events-none disabled:opacity-50 bg-slate-900 text-slate-50 hover:bg-slate-900/90 h-11 px-8 py-2">
                            Pay Now with SSLCommerz
                        </button>
                    </form>
                </div>
            </div>
            
            <p class="text-center text-xs text-slate-500 mt-4">
                Secure payment powered by SSLCommerz
            </p>
            
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-slate-900 underline underline-offset-4">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
