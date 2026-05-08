<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'জিনিয়াস কিডস - কুইজ গাইডবুক')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="জিনিয়াস কিডস কুইজ প্রতিযোগিতার স্মার্ট গাইডবুক। বিভিন্ন ক্যাটাগরির কুইজ, প্রগ্রেস ট্র্যাকিং এবং অনলাইন প্রস্তুতির জন্য একটি Bangla-first learning app।" name="description">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#FE5D37">
    <meta name="application-name" content="Genius Kids">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="জিনিয়াস কিডস">
    <meta name="format-detection" content="telephone=no">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-180x180.png') }}">

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

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    
    <!-- NProgress -->
    <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet">
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
        
        <!-- iOS / Manual Install Guide Modal -->
        <div id="pwa-install-guide-modal" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-end align-items-sm-center justify-content-center" style="z-index: 10500; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" role="dialog" aria-modal="true" aria-labelledby="pwa-modal-title">
            <div class="pwa-install-sheet">
                <div class="pwa-install-sheet-header">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="{{ asset('icons/icon-72x72.png') }}" width="40" height="40" alt="" class="rounded-2">
                        <div>
                            <div class="fw-bold" style="color:#FE5D37; font-size:1rem;">জিনিয়াস কিডস</div>
                            <div style="font-size:0.8rem; color:#888;">Genius Kids Quiz App</div>
                        </div>
                    </div>
                    <h2 id="pwa-modal-title" class="pwa-install-sheet-title">অ্যাপ ইন্সটল করুন</h2>
                </div>
                <ol id="pwa-modal-steps" class="pwa-install-sheet-steps"></ol>
                <button id="pwa-modal-close" class="pwa-install-sheet-close" aria-label="বন্ধ করুন">বুঝেছি, বন্ধ করুন</button>
            </div>
        </div>
    </div>
        </div>
        <!-- Spinner End -->

        <!-- Online/Offline Banner -->
        <div id="offline-banner" class="offline-status-banner">
            <i class="fa fa-wifi me-2"></i> <span id="offline-banner-text">ইন্টারনেট সংযোগ নেই - অ্যাপটি অনলাইনে ব্যবহার করুন</span>
        </div>

        @include('frontend.layouts.navbar')

        @yield('content')

        @include('frontend.layouts.footer')
        
        @include('frontend.layouts.bottom_nav')

        <!-- Install Overlay Spinner -->
        <div id="install-overlay" class="d-none position-fixed w-100 vh-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center" style="background: rgba(0,0,0,0.85); z-index: 9999;">
            <div class="spinner-border text-primary mb-3" style="width: 4rem; height: 4rem;" role="status"></div>
            <h3 class="text-white mb-2">অ্যাপ প্রস্তুত করা হচ্ছে...</h3>
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
                navigator.serviceWorker.register("{{ asset('sw.js') }}")
                    .then((registration) => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch((err) => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }

        // PWA Install Prompt Logic
        let deferredPrompt = null;
        let installProgressInterval = null;
        let installFinalizeTimeout = null;
        const installButtons = document.querySelectorAll('#install-btn');
        const installOverlay = document.getElementById('install-overlay');
        const overlayBar = document.getElementById('overlay-progress-bar');
        const overlayText = document.getElementById('overlay-progress-text');
        const isStandalone = () => window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        const isAndroidChrome = () => {
            const ua = navigator.userAgent;

            return /Android/i.test(ua) && /Chrome/i.test(ua) && !/EdgA|OPR|SamsungBrowser/i.test(ua);
        };
        const isIosSafari = () => {
            const ua = navigator.userAgent;
            const isIosDevice = /iPhone|iPad|iPod/i.test(ua);
            const isWebkit = /WebKit/i.test(ua);
            const isCriOS = /CriOS/i.test(ua);
            const isFxiOS = /FxiOS/i.test(ua);

            return isIosDevice && isWebkit && !isCriOS && !isFxiOS;
        };
        const hasNativeInstallPrompt = () => deferredPrompt !== null;
        const showInstallButtons = () => {
            if (isStandalone()) {
                hideInstallButtons();
                return;
            }

            // Always show on mobile (Android/iOS) or if native prompt is ready
            const shouldShowButton = isAndroidChrome() || isIosSafari() || hasNativeInstallPrompt();

            installButtons.forEach((button) => {
                button.classList.toggle('d-none', !shouldShowButton);
            });
        };
        const hideInstallButtons = () => {
            installButtons.forEach((button) => button.classList.add('d-none'));
        };
        const resetInstallOverlay = () => {
            if (installProgressInterval) {
                clearInterval(installProgressInterval);
                installProgressInterval = null;
            }

            if (installFinalizeTimeout) {
                clearTimeout(installFinalizeTimeout);
                installFinalizeTimeout = null;
            }

            if (overlayBar) {
                overlayBar.style.width = '0%';
            }

            if (overlayText) {
                overlayText.innerText = '0%';
            }

            if (installOverlay) {
                installOverlay.classList.add('d-none');
            }
        };
        const showInstallOverlay = (maxProgress = 92) => {
            if (!installOverlay || !overlayBar || !overlayText) {
                return;
            }

            resetInstallOverlay();
            installOverlay.classList.remove('d-none');

            let progress = 0;
            installProgressInterval = setInterval(() => {
                progress += 4;
                if (progress > maxProgress) {
                    progress = maxProgress;
                }

                overlayBar.style.width = progress + '%';
                overlayText.innerText = progress + '%';
            }, 120);
        };
        const completeInstallOverlay = (successMessage) => {
            if (!installOverlay || !overlayBar || !overlayText) {
                return;
            }

            if (installProgressInterval) {
                clearInterval(installProgressInterval);
                installProgressInterval = null;
            }

            overlayBar.style.width = '100%';
            overlayText.innerText = '100%';

            installFinalizeTimeout = setTimeout(() => {
                installOverlay.classList.add('d-none');
                alert(successMessage);
            }, 500);
        };

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showInstallButtons();
        });

        window.addEventListener('load', () => {
            if (isStandalone()) {
                hideInstallButtons();
                return;
            }

            showInstallButtons();
        });

        // Event Delegation for Install Button (handles dynamic elements)
        document.addEventListener('click', async (e) => {
            const installBtn = e.target.closest('#install-btn');
            if (!installBtn) return;

            e.preventDefault();
            console.log('Install button clicked, deferredPrompt status:', !!deferredPrompt);

            if (deferredPrompt) {
                hideInstallButtons();
                showInstallOverlay();

                deferredPrompt.prompt();
                const choiceResult = await deferredPrompt.userChoice;

                if (choiceResult.outcome !== 'accepted') {
                    console.log('User dismissed the install prompt');
                    deferredPrompt = null;
                    resetInstallOverlay();
                    showInstallButtons();
                } else {
                    console.log('User accepted the install prompt');
                    deferredPrompt = null;
                    installFinalizeTimeout = setTimeout(() => {
                        completeInstallOverlay('অ্যাপ ইন্সটল সফল হয়েছে!');
                    }, 3200);
                }
                return;
            }

            // Fallback for Manual Guides
            if (isAndroidChrome()) {
                showIosModal(
                    'Chrome-এ ইন্সটল করুন',
                    [
                        '① উপরের <strong>⋮ তিন-ডট মেনু</strong> খুলুন',
                        '② <strong>"Install app"</strong> বা <strong>"Add to Home screen"</strong> ট্যাপ করুন',
                        '③ নিশ্চিত করতে <strong>"Install"</strong> বোতামে ট্যাপ করুন',
                    ]
                );
            } else if (isIosSafari()) {
                showIosModal(
                    'iPhone/iPad-এ ইন্সটল করুন',
                    [
                        '① নিচের টুলবারে <strong>শেয়ার বাটন (↑)</strong> ট্যাপ করুন',
                        '② স্ক্রল করে <strong>"Add to Home Screen"</strong> খুঁজুন',
                        '③ ডানে উপরে <strong>"Add"</strong> ট্যাপ করুন',
                    ]
                );
            } else {
                alert('আপনার ব্রাউজারের মেনু থেকে "Add to Home screen" অপশনটি খুঁজে নিন।');
            }
        });

        window.addEventListener('appinstalled', () => {
            console.log('App was successfully installed');
            completeInstallOverlay('অ্যাপ ইন্সটল সফল হয়েছে!');
            hideInstallButtons();
        });

        // --- iOS/Manual install guide modal ---
        function showIosModal(title, steps) {
            const modal = document.getElementById('pwa-install-guide-modal');
            if (!modal) return;
            document.getElementById('pwa-modal-title').textContent = title;
            const list = document.getElementById('pwa-modal-steps');
            list.innerHTML = steps.map(s => `<li>${s}</li>`).join('');
            modal.classList.remove('d-none');
            modal.classList.add('pwa-modal-show');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.getElementById('pwa-modal-close');
            const modal    = document.getElementById('pwa-install-guide-modal');
            if (closeBtn && modal) {
                closeBtn.addEventListener('click', () => {
                    modal.classList.remove('pwa-modal-show');
                    modal.classList.add('d-none');
                });
                // Close on backdrop click
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.remove('pwa-modal-show');
                        modal.classList.add('d-none');
                    }
                });
            }
        });

        // UI Audio Interaction Logic
        document.addEventListener('DOMContentLoaded', () => {
            const clickAudio = new Audio('{{ asset("frontend/sounds/click.mp3") }}');
            const popAudio = new Audio('{{ asset("frontend/sounds/pop.mp3") }}');
            
            clickAudio.volume = 0.5;
            popAudio.volume = 0.6;

            // Buttons & Links get 'click' sound
            document.querySelectorAll('a, .btn').forEach(el => {
                el.addEventListener('mousedown', () => {
                    if(!el.classList.contains('accordion-button')) {
                        clickAudio.currentTime = 0;
                        clickAudio.play().catch(e => {}); 
                    }
                });
            });

            // Accordion gets 'pop' sound
            document.querySelectorAll('.accordion-button').forEach(el => {
                el.addEventListener('mousedown', () => {
                    popAudio.currentTime = 0;
                    popAudio.play().catch(e => {});
                });
            });
        });
        
        // Online/Offline Status Logic
        const offlineBanner = document.getElementById('offline-banner');
        const offlineText = document.getElementById('offline-banner-text');

        window.addEventListener('online', () => {
            offlineBanner.classList.remove('is-offline');
            offlineBanner.classList.add('is-online');
            offlineText.innerText = 'ইন্টারনেট সংযোগ পুনরায় স্থাপিত হয়েছে';
            
            setTimeout(() => {
                offlineBanner.classList.remove('is-online');
            }, 3000);
        });

        window.addEventListener('offline', () => {
            offlineBanner.classList.remove('is-online');
            offlineBanner.classList.add('is-offline');
            offlineText.innerText = 'ইন্টারনেট সংযোগ নেই - অ্যাপটি অনলাইনে ব্যবহার করুন';
        });

        // Initial check
        if (!navigator.onLine) {
            offlineBanner.classList.add('is-offline');
        }
    </script>
    <style>
        /* Online/Offline Banner */
        .offline-status-banner {
            position: fixed;
            top: -60px;
            left: 0;
            width: 100%;
            z-index: 11000;
            text-align: center;
            padding: 12px;
            font-weight: bold;
            color: white;
            transition: top 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .offline-status-banner.is-offline {
            top: 0;
            background-color: #dc3545;
        }
        .offline-status-banner.is-online {
            top: 0;
            background-color: #28a745;
        }

        /* Mobile adjustment for bottom nav */
        @media (max-width: 991.98px) {
            body {
                padding-bottom: 60px;
            }
        }

        /* PWA Install Guide Modal (iOS / Manual) */
        .pwa-install-guide-modal.pwa-modal-show,
        #pwa-install-guide-modal.pwa-modal-show {
            display: flex !important;
        }
        .pwa-install-sheet {
            background: #fff;
            border-radius: 20px 20px 0 0;
            padding: 24px 20px 32px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 -8px 40px rgba(0,0,0,0.18);
            animation: slideUp 0.32s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @media (min-width: 576px) {
            .pwa-install-sheet {
                border-radius: 20px;
                margin: 16px;
            }
        }
        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to   { transform: translateY(0);   opacity: 1; }
        }
        .pwa-install-sheet-header {
            margin-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 14px;
        }
        .pwa-install-sheet-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }
        .pwa-install-sheet-steps {
            padding: 0 0 0 4px;
            list-style: none;
            margin-bottom: 20px;
        }
        .pwa-install-sheet-steps li {
            padding: 10px 12px;
            margin-bottom: 8px;
            background: #fff8f5;
            border-left: 3px solid #FE5D37;
            border-radius: 0 8px 8px 0;
            font-size: 0.95rem;
            color: #333;
            line-height: 1.5;
        }
        .pwa-install-sheet-close {
            display: block;
            width: 100%;
            padding: 13px;
            background: #FE5D37;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .pwa-install-sheet-close:hover {
            background: #e04d2c;
        }
    </style>
    @include('partials._flash_messages')
</body>

</html>
