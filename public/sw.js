const CACHE_NAME = "genius-kids-pwa-v1";
const urlsToCache = [
    "/",
    "/offline.html",
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
            // Use cache.addAll cautiously with external resources. 
            // If any single URL fails to fetch, the entire cache operation fails.
            // So we map them individually to allow partial caching success.
            return Promise.all(
                urlsToCache.map(url => {
                    return cache.add(url).catch(error => {
                        console.error('Failed to cache:', url, error);
                    });
                })
            );
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
    // 1. network-first for backend html/data requests (category, chapter, question routes)
    // 2. cache-first for static assets (images, css, js)

    const isHTMLRequest = event.request.headers.get("accept").includes("text/html");
    const isStaticAsset = event.request.url.match(/\.(js|css|png|jpg|jpeg|svg|gif|woff2?|ttf|eot)$/i);

    if (isStaticAsset) {
        // Cache-first for static assets
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                if (cachedResponse) return cachedResponse;
                return fetch(event.request).then((networkResponse) => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                });
            })
        );
    } else if (isHTMLRequest || event.request.mode === 'navigate') {
        // Network-first for HTML pages (dynamic logic) width fallback to cache
        event.respondWith(
            fetch(event.request)
                .then((networkResponse) => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                })
                .catch(() => {
                    // If network fails, try cache
                    return caches.match(event.request).then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        // If no cache, return offline page
                        return caches.match('/offline.html');
                    });
                })
        );
    } else {
        // Default Stale-While-Revalidate for other requests
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                const fetchPromise = fetch(event.request).then((networkResponse) => {
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                    });
                    return networkResponse;
                }).catch(() => {
                    // Ignore fetch err for background sync
                });
                return cachedResponse || fetchPromise;
            })
        );
    }
});
