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
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet (FontAwesome 6) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <!-- Vite Assets (compiled Tailwind CSS and Javascript) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- NProgress -->
    <link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet">
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
</head>

<body class="min-h-screen pb-28 md:pb-0 md:pt-24 bg-[#FFFBEB]">

        <!-- iOS / Manual Install Guide Modal -->
        <div id="pwa-install-guide-modal" class="hidden fixed inset-0 flex items-end sm:items-center justify-content-center" style="z-index: 10500; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" role="dialog" aria-modal="true" aria-labelledby="pwa-modal-title">
            <div class="pwa-install-sheet">
                <div class="pwa-install-sheet-header">
                    <div class="flex items-center gap-2 mb-2">
                        <img src="{{ asset('icons/icon-72x72.png') }}" width="40" height="40" alt="" class="rounded-lg">
                        <div>
                            <div class="font-extrabold text-orange-500 text-sm font-sans">জিনিয়াস কিডস</div>
                            <div class="text-[10px] text-slate-400 font-extrabold font-sans">Genius Kids Quiz App</div>
                        </div>
                    </div>
                    <h2 id="pwa-modal-title" class="pwa-install-sheet-title">অ্যাপ ইন্সটল করুন</h2>
                </div>
                <ol id="pwa-modal-steps" class="pwa-install-sheet-steps"></ol>
                <button id="pwa-modal-close" class="pwa-install-sheet-close font-sans" aria-label="বন্ধ করুন">বুঝেছি, বন্ধ করুন</button>
            </div>
        </div>

        <!-- Online/Offline Banner -->
        <div id="offline-banner" class="offline-status-banner">
            <i class="fa fa-wifi me-2"></i> <span id="offline-banner-text">ইন্টারনেট সংযোগ নেই - অ্যাপটি অনলাইনে ব্যবহার করুন</span>
        </div>

        @include('frontend.layouts.navbar')

        <!-- Mobile Top PWA Banner -->
        <div id="mobile-pwa-banner" class="hidden md:hidden sticky top-0 z-40 bg-amber-100 border-b-4 border-slate-900 px-4 py-2.5 items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <button id="pwa-banner-close" class="text-slate-500 hover:text-slate-950 p-1 mr-1 text-lg leading-none focus:outline-none" aria-label="বন্ধ করুন">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img src="{{ asset('icons/icon-72x72.png') }}" width="42" height="42" alt="App Icon" class="rounded-xl border-2 border-slate-900 shadow-[1.5px_1.5px_0px_#000000]">
                <div>
                    <div class="font-extrabold text-slate-900 text-xs leading-tight">জিনিয়াস কিডস</div>
                    <div class="text-[10px] text-slate-600 font-bold leading-tight">কুইজ ও লাইভ এক্সাম</div>
                </div>
            </div>
            <button id="install-btn" class="px-3 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 border-2 border-slate-900 text-white font-extrabold text-xs transition-all shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none whitespace-nowrap">
                ইন্সটল করুন <i class="fa fa-download ms-1"></i>
            </button>
        </div>

        <main id="app" class="max-w-3xl mx-auto px-4 py-4 md:py-6">
            @yield('content')
        </main>

        @include('frontend.layouts.bottom_nav')

        <!-- Install Overlay Spinner (Tailwind Styled) -->
        <div id="install-overlay" class="hidden fixed inset-0 flex flex-col items-center justify-center bg-black/85 z-[9999]">
            <div class="w-16 h-16 border-4 border-slate-200 border-t-orange-500 rounded-full animate-spin mb-4"></div>
            <h3 class="text-white text-lg font-extrabold mb-2">অ্যাপ প্রস্তুত করা হচ্ছে...</h3>
            <p class="text-slate-400 text-sm font-semibold">দয়া করে ব্রাউজার কিংবা পেজ বন্ধ করবেন না</p>
            <div class="w-1/2 h-2 bg-slate-800 rounded-full overflow-hidden mt-4">
                <div id="overlay-progress-bar" class="h-full bg-orange-500 rounded-full transition-all duration-150" style="width: 0%;"></div>
            </div>
            <h5 id="overlay-progress-text" class="text-white mt-3 font-extrabold">0%</h5>
        </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

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
        const INSTALL_STATE_KEY = 'pwa-installed';
        const installButtons = document.querySelectorAll('#install-btn');
        const installOverlay = document.getElementById('install-overlay');
        const overlayBar = document.getElementById('overlay-progress-bar');
        const overlayText = document.getElementById('overlay-progress-text');

        const isStandalone = () => {
            return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        };

        const wasInstalled = () => {
            try {
                return localStorage.getItem(INSTALL_STATE_KEY) === '1';
            } catch (error) {
                return false;
            }
        };

        const markInstalled = () => {
            try {
                localStorage.setItem(INSTALL_STATE_KEY, '1');
            } catch (error) {
                console.warn('Unable to persist install state', error);
            }
        };

        const isEffectivelyInstalled = () => isStandalone() || wasInstalled();

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

        const updateInstallButtonState = () => {
            const nativePromptReady = hasNativeInstallPrompt();

            installButtons.forEach((button) => {
                if (isEffectivelyInstalled()) {
                    button.classList.add('hidden');
                    button.removeAttribute('data-install-pending');
                    button.removeAttribute('aria-disabled');
                    return;
                }

                if (isIosSafari()) {
                    button.innerHTML = 'অ্যাপ ইন্সটল করুন <i class="fa fa-download ms-2"></i>';
                    button.removeAttribute('data-install-pending');
                    button.removeAttribute('aria-disabled');
                    return;
                }

                if (isAndroidChrome() && !nativePromptReady) {
                    button.innerHTML = 'অ্যাপ ইন্সটল প্রস্তুত হচ্ছে <i class="fa fa-mobile-alt ms-2"></i>';
                    button.setAttribute('data-install-pending', 'true');
                    button.setAttribute('aria-disabled', 'true');
                    return;
                }

                button.innerHTML = 'অ্যাপ ইন্সটল করুন <i class="fa fa-download ms-2"></i>';
                button.removeAttribute('data-install-pending');
                button.removeAttribute('aria-disabled');
            });
        };

        const updateMobileBannerVisibility = () => {
            const banner = document.getElementById('mobile-pwa-banner');
            const profileCard = document.getElementById('pwa-profile-install-card');
            const installed = isEffectivelyInstalled();

            // 1. Update Profile Install Card (visible if not installed)
            if (profileCard) {
                if (installed) {
                    profileCard.classList.add('hidden');
                } else {
                    profileCard.classList.remove('hidden');
                }
            }

            // 2. Update Mobile Top Banner
            if (banner) {
                if (installed) {
                    banner.classList.add('hidden');
                    banner.classList.remove('flex');
                    return;
                }

                if (sessionStorage.getItem('pwa-banner-dismissed') === '1') {
                    banner.classList.add('hidden');
                    banner.classList.remove('flex');
                    return;
                }

                // Show banner on mobile screens (width < 768px) if the PWA can be installed
                const isMobile = window.innerWidth < 768 || /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
                const canInstall = hasNativeInstallPrompt() || isIosSafari() || isAndroidChrome();

                if (isMobile && canInstall) {
                    banner.classList.remove('hidden');
                    banner.classList.add('flex');
                } else {
                    banner.classList.add('hidden');
                    banner.classList.remove('flex');
                }
            }
        };

        const showInstallButtons = () => {
            if (isEffectivelyInstalled()) {
                hideInstallButtons();
                return;
            }

            const shouldShowButton = hasNativeInstallPrompt() || isIosSafari() || isAndroidChrome();

            installButtons.forEach((button) => {
                button.classList.toggle('hidden', !shouldShowButton);
            });

            updateInstallButtonState();
            updateMobileBannerVisibility();
        };

        const hideInstallButtons = () => {
            installButtons.forEach((button) => button.classList.add('hidden'));
            updateMobileBannerVisibility();
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
                installOverlay.classList.add('hidden');
            }
        };

        const showInstallOverlay = (maxProgress = 92) => {
            if (!installOverlay || !overlayBar || !overlayText) {
                return;
            }

            resetInstallOverlay();
            installOverlay.classList.remove('hidden');

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
                installOverlay.classList.add('hidden');
                alert(successMessage);
            }, 500);
        };

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();

            if (isEffectivelyInstalled()) {
                hideInstallButtons();
                return;
            }

            deferredPrompt = e;
            showInstallButtons();
        });

        window.addEventListener('load', () => {
            if (isStandalone()) {
                markInstalled();
                hideInstallButtons();
                return;
            }

            showInstallButtons();
        });

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
                    markInstalled();
                    installFinalizeTimeout = setTimeout(() => {
                        completeInstallOverlay('অ্যাপ ইন্সটল সফল হয়েছে!');
                    }, 3200);
                }
                return;
            }

            if (isIosSafari()) {
                showIosModal(
                    'iPhone/iPad-এ ইন্সটল করুন',
                    [
                        '① ব্রাউজারের নিচের টুলবারে <strong>শেয়ার বাটন (↑)</strong> ট্যাপ করুন।',
                        '② মেনু স্ক্রল করে নিচের দিকে যান এবং <strong>"Add to Home Screen"</strong> অপশনটি সিলেক্ট করুন।',
                        '③ ডানে উপরে থাকা <strong>"Add"</strong> বাটনে ট্যাপ করুন।'
                    ]
                );
                return;
            }

            // Android or any other browsers where native prompt is not yet ready/available
            showIosModal(
                'অ্যাপ ইন্সটল করার নিয়মাবলী',
                [
                    '① ব্রাউজারের উপরে ডানে থাকা <strong>থ্রি-ডট (⋮) মেনু</strong> বা ইন্সটল আইকন ট্যাপ করুন।',
                    '② অপশনগুলো থেকে <strong>"Install app"</strong> অথবা <strong>"Add to Home Screen"</strong> সিলেক্ট করুন।',
                    '③ ইন্সটল সম্পন্ন করতে কনফার্মেশন পপ-আপে <strong>"Install"</strong> বা <strong>"Add"</strong> বাটনে চাপুন।'
                ]
            );
        });

        window.addEventListener('appinstalled', () => {
            console.log('App was successfully installed');
            markInstalled();
            completeInstallOverlay('অ্যাপ ইন্সটল সফল হয়েছে!');
            hideInstallButtons();
        });

        function showIosModal(title, steps) {
            const modal = document.getElementById('pwa-install-guide-modal');
            if (!modal) return;

            document.getElementById('pwa-modal-title').textContent = title;
            const list = document.getElementById('pwa-modal-steps');
            list.innerHTML = steps.map((step) => `<li>${step}</li>`).join('');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const closeBtn = document.getElementById('pwa-modal-close');
            const modal = document.getElementById('pwa-install-guide-modal');

            if (closeBtn && modal) {
                closeBtn.addEventListener('click', () => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                });

                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.remove('flex');
                        modal.classList.add('hidden');
                    }
                });
            }

            // PWA Mobile Banner Close Handler
            const bannerCloseBtn = document.getElementById('pwa-banner-close');
            const banner = document.getElementById('mobile-pwa-banner');
            if (bannerCloseBtn && banner) {
                bannerCloseBtn.addEventListener('click', () => {
                    sessionStorage.setItem('pwa-banner-dismissed', '1');
                    if (banner) {
                        banner.classList.add('hidden');
                        banner.classList.remove('flex');
                    }
                });
            }
        });

        /* ============================== AUDIO (Web Audio API Synth & Remote MP3s) ============================== */
        let actx;
        window.tone = function(freq=600, dur=.12, type='sine', vol=.2) {
            try {
                actx = actx || new (window.AudioContext || window.webkitAudioContext)();
                const o = actx.createOscillator(), g = actx.createGain();
                o.type = type; o.frequency.value = freq;
                g.gain.value = vol;
                o.connect(g).connect(actx.destination);
                o.start();
                g.gain.exponentialRampToValueAtTime(.0001, actx.currentTime + dur);
                o.stop(actx.currentTime + dur);
            } catch(e){}
        };

        // Internet-hosted sounds (fallback to synthesized tones on network/CORS issues)
        const correctAudioUrl = 'https://raw.githubusercontent.com/techieshruti/Quiz-App-with-Timer/main/sounds/correct.mp3';
        const wrongAudioUrl = 'https://raw.githubusercontent.com/techieshruti/Quiz-App-with-Timer/main/sounds/wrong.mp3';
        let correctAudio = null;
        let wrongAudio = null;

        // Initialize audio objects lazily on user interaction to comply with browser autoplay policies
        function initAudio() {
            if (!correctAudio) correctAudio = new Audio(correctAudioUrl);
            if (!wrongAudio) wrongAudio = new Audio(wrongAudioUrl);
        }

        window.sfx = {
            click: () => window.tone(440, .06, 'square', .12),
            correct: () => { 
                initAudio();
                try {
                    correctAudio.currentTime = 0;
                    correctAudio.play().catch(() => {
                        // Fallback to synth if blocked or offline
                        window.tone(660, .1, 'sine', .2); 
                        setTimeout(() => window.tone(880, .14, 'sine', .2), 100); 
                        setTimeout(() => window.tone(1175, .18, 'sine', .2), 220);
                    });
                } catch (e) {
                    window.tone(660, .1, 'sine', .2); 
                    setTimeout(() => window.tone(880, .14, 'sine', .2), 100); 
                    setTimeout(() => window.tone(1175, .18, 'sine', .2), 220);
                }
            },
            wrong: () => { 
                initAudio();
                try {
                    wrongAudio.currentTime = 0;
                    wrongAudio.play().catch(() => {
                        // Fallback to synth if blocked or offline
                        window.tone(220, .18, 'sawtooth', .2); 
                        setTimeout(() => window.tone(160, .22, 'sawtooth', .2), 160);
                    });
                } catch (e) {
                    window.tone(220, .18, 'sawtooth', .2); 
                    setTimeout(() => window.tone(160, .22, 'sawtooth', .2), 160);
                }
            },
            win: () => { 
                [523, 659, 784, 1047].forEach((f, i) => setTimeout(() => window.tone(f, .18, 'triangle', .22), i * 120)); 
            },
        };

        // UI Audio Interaction Logic (Global Event Binder)
        document.addEventListener('mousedown', (e) => {
            initAudio(); // Initialize audio objects on first interaction
            const clickTarget = e.target.closest('a, button, .level-node, .av-pick, .speak, .nav-btn');
            if (clickTarget) {
                window.sfx.click();
            }
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
            to { transform: translateY(0); opacity: 1; }
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

        #install-btn[data-install-pending="true"] {
            opacity: 0.85;
            box-shadow: none;
        }
    </style>
    @include('partials._flash_messages')
</body>

</html>
