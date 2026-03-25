const CACHE_NAME = "genius-kids-pwa-v9";
const urlsToCache = [
    "/offline",
    "/manifest.json",
    "/frontend/css/bootstrap.min.css",
    "/frontend/css/style.css",
    "/frontend/lib/animate/animate.min.css",
    "/frontend/lib/owlcarousel/assets/owl.carousel.min.css",
    "/frontend/js/main.js",
    "/frontend/img/favicon.ico",
    "/icons/app-icon.svg",
    // External CDNs
    "https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css",
    "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css",
    "https://code.jquery.com/jquery-3.4.1.min.js",
    "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
];

// Install SW
self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log("Opened cache");
            return cache.addAll(urlsToCache);
        })
    );
});

// Activate SW
self.addEventListener("activate", (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Fetch SW
self.addEventListener("fetch", (event) => {
    // Exclude non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Navigation requests (page loads) — Network-only with offline fallback
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.match('/offline').then((cachedOfflineResponse) => {
                        if (cachedOfflineResponse) {
                            return cachedOfflineResponse;
                        }
                        // Last resort: return a simple offline response
                        return new Response(
                            '<html><body><h1>ইন্টারনেট কানেকশন নেই</h1><p>অনুগ্রহ করে ইন্টারনেট সংযোগ চেক করুন।</p><button onclick="location.reload()">আবার চেষ্টা করুন</button></body></html>',
                            { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                        );
                    });
                })
        );
        return;
    }

    const isStaticAsset = event.request.url.match(/\.(js|css|png|jpg|jpeg|svg|gif|woff2?|ttf|eot|ico)$/i);

    if (isStaticAsset) {
        // Cache-first for static assets
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(event.request).then((networkResponse) => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                }).catch(() => {
                    // Silently fail for uncached static assets when offline
                    return new Response('', { status: 408 });
                });
            })
        );
    } else {
        // Stale-While-Revalidate strategy for API calls and other resources
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(event.request).then((cachedResponse) => {
                    const fetchPromise = fetch(event.request).then((networkResponse) => {
                        // Only cache successful and valid responses
                        if (networkResponse && networkResponse.status === 200) {
                            cache.put(event.request, networkResponse.clone());
                        }
                        return networkResponse;
                    }).catch(() => null);

                    return cachedResponse || fetchPromise.then(res => res || new Response('', { status: 408 }));
                });
            })
        );
    }
});
