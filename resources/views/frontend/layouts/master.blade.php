<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Kider - Preschool Website Template')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#FE5D37">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Kider">
    <link rel="apple-touch-icon" href="{{ asset('icons/app-icon.svg') }}">

    <!-- Favicon -->
    <link href="{{ asset('frontend/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('frontend/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        @include('frontend.layouts.navbar')

        @yield('content')

        @include('frontend.layouts.footer')

        <!-- Install Overlay Spinner -->
        <div id="install-overlay" class="d-none position-fixed w-100 vh-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center" style="background: rgba(0,0,0,0.85); z-index: 9999;">
            <div class="spinner-border text-primary mb-3" style="width: 4rem; height: 4rem;" role="status"></div>
            <h3 class="text-white mb-2">অ্যাপ ডেটা ডাউনলোড হচ্ছে...</h3>
            <p class="text-white-50">দয়া করে ব্রাউজার কিংবা পেজ বন্ধ করবেন না</p>
            <div class="progress w-50 mt-3" style="height: 10px; border-radius: 10px;">
                <div id="overlay-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
            </div>
            <h5 id="overlay-progress-text" class="text-white mt-3 fw-bold">0%</h5>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <!-- Service Worker Registration & PWA Install -->
    <script>
        // SW Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch((err) => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }

        // PWA Install Prompt Logic
        let deferredPrompt;
        const installBtn = document.getElementById('install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if(installBtn) {
                installBtn.classList.remove('d-none');
            }
        });

        if(installBtn) {
            installBtn.addEventListener('click', (e) => {
                installBtn.classList.add('d-none');
                if(deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                            // Start downloading offline data once accepted
                            document.getElementById('install-overlay').classList.remove('d-none');
                            if (navigator.serviceWorker.controller) {
                                navigator.serviceWorker.controller.postMessage({ type: 'START_OFFLINE_SYNC' });
                            }
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            });
        }
        
        window.addEventListener('appinstalled', (evt) => {
            console.log('App was successfully installed');
            if(installBtn) {
               installBtn.classList.add('d-none');
            }
        });

        // Listen for cache progress from Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', (event) => {
                const overlay = document.getElementById('install-overlay');
                const overlayBar = document.getElementById('overlay-progress-bar');
                const overlayText = document.getElementById('overlay-progress-text');

                if (event.data && event.data.type === 'INSTALL_PROGRESS') {
                    if (overlay && overlay.classList.contains('d-none')) {
                        overlay.classList.remove('d-none');
                    }
                    overlayBar.style.width = event.data.progress + '%';
                    overlayText.innerText = event.data.progress + '%';
                } else if (event.data && event.data.type === 'INSTALL_COMPLETE') {
                    overlayBar.style.width = '100%';
                    overlayText.innerText = '100%';
                    
                    setTimeout(() => {
                        overlay.classList.add('d-none');
                        alert('অ্যাপ ইন্সটল এবং ডেটা ডাউনলোড সফল হয়েছে! এখন এটি ইন্টারনেট ছাড়াই কাজ করবে।');
                    }, 500);
                }
            });
        }
    </script>
</body>

</html>
