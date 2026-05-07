const CACHE_NAME = "genius-kids-pwa-v12";
const urlsToCache = [
    "/offline",
    "/manifest.json",
    "/frontend/css/bootstrap.min.css",
    "/frontend/css/style.css",
    "/frontend/lib/animate/animate.min.css",
    "/frontend/lib/owlcarousel/assets/owl.carousel.min.css",
    "/frontend/js/main.js",
    "/icons/app-icon.svg",
    "/icons/icon-192x192.png",
    "/icons/icon-512x512.png",
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
    if (event.request.method !== "GET") {
        return;
    }

    // Navigation requests (page loads) - Network-only with offline fallback
    if (event.request.mode === "navigate") {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match("/offline").then((cachedOfflineResponse) => {
                    if (cachedOfflineResponse) {
                        return cachedOfflineResponse;
                    }

                    return new Response(
                        '<html><body><h1>ইন্টারনেট কানেকশন নেই</h1><p>অনুগ্রহ করে ইন্টারনেট সংযোগ চেক করুন।</p><button onclick="location.reload()">আবার চেষ্টা করুন</button></body></html>',
                        { headers: { "Content-Type": "text/html; charset=utf-8" } }
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

                return fetch(event.request)
                    .then((networkResponse) => {
                        return caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, networkResponse.clone());
                            return networkResponse;
                        });
                    })
                    .catch(() => {
                        return new Response("", { status: 408 });
                    });
            })
        );
    } else {
        // Stale-while-revalidate strategy for API calls and other resources
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(event.request).then((cachedResponse) => {
                    const fetchPromise = fetch(event.request)
                        .then((networkResponse) => {
                            if (networkResponse && networkResponse.status === 200) {
                                cache.put(event.request, networkResponse.clone());
                            }

                            return networkResponse;
                        })
                        .catch(() => null);

                    return cachedResponse || fetchPromise.then((res) => res || new Response("", { status: 408 }));
                });
            })
        );
    }
});
