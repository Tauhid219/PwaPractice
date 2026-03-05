const CACHE_NAME = "genius-kids-pwa-v6";
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

// Listen for messages from the frontend
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'START_OFFLINE_SYNC') {
        event.waitUntil(
            (async () => {
                // Since it's online-only now, just simulate a very fast progress bar 
                // for the UI UX during installation.
                let progress = 0;
                const interval = setInterval(async () => {
                    progress += 20;
                    await sendMessageToClients({ type: 'INSTALL_PROGRESS', progress });
                    if (progress >= 100) {
                        clearInterval(interval);
                        await sendMessageToClients({ type: 'INSTALL_COMPLETE' });
                    }
                }, 400); // 2 second simulation
            })()
        );
    }
});

// Fetch SW
self.addEventListener("fetch", (event) => {
    // Exclude API requests or non-GET requests from caching
    if (event.request.method !== 'GET') {
        return;
    }

    const isHTMLRequest = event.request.headers.get("accept").includes("text/html") || event.request.mode === 'navigate';
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
                });
            })
        );
    } else if (isHTMLRequest) {
        // Handle /offline route properly to avoid loops
        if (event.request.url.includes('/offline')) {
            event.respondWith(
                caches.match('/offline').then(cached => {
                    return cached || fetch(event.request);
                })
            );
            return;
        }

        // Network-only for HTML logic with fallback to /offline
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    // Try to show cached offline page
                    return caches.match('/offline').then((cachedOfflineResponse) => {
                        if (cachedOfflineResponse) {
                            return cachedOfflineResponse;
                        }
                    });
                })
        );
    } else {
        // Network-only for everything else
        event.respondWith(fetch(event.request));
    }
});
