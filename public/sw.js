const CACHE_NAME = "genius-kids-pwa-v4";
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

// Helper function to send messages to active clients
async function sendMessageToClients(message) {
    const clients = await self.clients.matchAll({ includeUncontrolled: true, type: 'window' });
    for (const client of clients) {
        client.postMessage(message);
    }
}

// Install SW
self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            console.log("Opened cache");

            // Just cache static URLs first. Don't notify progress since this is quiet.
            await Promise.all(
                urlsToCache.map(async (url) => {
                    try {
                        await cache.add(url);
                    } catch (error) {
                        console.error('Failed to cache static:', url, error);
                    }
                })
            );
        })()
    );
});

// Listen for messages from the frontend (like manual trigger for offline DB sync)
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'START_OFFLINE_SYNC') {
        event.waitUntil(
            (async () => {
                const cache = await caches.open(CACHE_NAME);
                let totalUrls = 0;
                let cachedCount = 0;

                try {
                    const response = await fetch('/offline-urls');
                    if (response.ok) {
                        const dynamicUrls = await response.json();
                        totalUrls = dynamicUrls.length;

                        for (const url of dynamicUrls) {
                            try {
                                await cache.add(url);
                                cachedCount++;
                                await sendMessageToClients({ type: 'INSTALL_PROGRESS', progress: Math.min(100, Math.round((cachedCount / totalUrls) * 100)) });
                            } catch (e) {
                                console.error('Failed to cache dynamic url:', url, e);
                            }
                        }
                    }
                } catch (apiError) {
                    console.warn('Could not fetch dynamic URLs during manual download.', apiError);
                }

                // Tell the UI that caching is complete
                await sendMessageToClients({ type: 'INSTALL_COMPLETE' });
            })()
        );
    }
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
                    return caches.match(event.request, { ignoreSearch: true }).then((cachedResponse) => {
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
