const CACHE_NAME = "genius-kids-pwa-v15";
const OFFLINE_FALLBACK_URL = "/offline";
const PRECACHE_URLS = [
    OFFLINE_FALLBACK_URL,
    "/manifest.json",
    "/favicon.png",
    "/frontend/css/bootstrap.min.css",
    "/frontend/css/style.css",
    "/frontend/lib/animate/animate.min.css",
    "/frontend/lib/owlcarousel/assets/owl.carousel.min.css",
    "/frontend/js/main.js",
    "/icons/icon-180x180.png",
    "/icons/icon-192x192.png",
    "/icons/icon-512x512.png",
];

function isSameOrigin(url) {
    return url.origin === self.location.origin;
}

function isPrecachedPath(pathname) {
    return PRECACHE_URLS.includes(pathname);
}

function isCacheableStaticRequest(request, url) {
    if (!isSameOrigin(url)) {
        return false;
    }

    if (isPrecachedPath(url.pathname)) {
        return true;
    }

    return /^(style|script|image|font)$/.test(request.destination);
}

async function cacheFirst(request) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
        return cachedResponse;
    }

    const networkResponse = await fetch(request);

    if (
        networkResponse &&
        networkResponse.ok &&
        networkResponse.type === "basic"
    ) {
        const cache = await caches.open(CACHE_NAME);
        cache.put(request, networkResponse.clone());
    }

    return networkResponse;
}

// Install SW
self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            // Cache each URL individually so one failure doesn't block the whole install
            const cachePromises = PRECACHE_URLS.map((url) =>
                cache.add(url).catch((err) => {
                    console.warn(`[SW] Failed to precache: ${url}`, err);
                })
            );
            return Promise.all(cachePromises);
        })
    );
});

// Activate SW
self.addEventListener("activate", (event) => {
    event.waitUntil(
        Promise.all([
            // Clean up old caches
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== CACHE_NAME) {
                            console.log(`[SW] Deleting old cache: ${cacheName}`);
                            return caches.delete(cacheName);
                        }
                        return Promise.resolve();
                    })
                );
            }),
            // Take control of all open clients immediately
            self.clients.claim(),
        ])
    );
});

// Fetch SW
self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET") {
        return;
    }

    const requestUrl = new URL(event.request.url);

    // Keep navigations online-first and fall back to the dedicated offline page.
    if (event.request.mode === "navigate") {
        event.respondWith(
            fetch(event.request).catch(async () => {
                const cachedOfflineResponse = await caches.match(OFFLINE_FALLBACK_URL);

                if (cachedOfflineResponse) {
                    return cachedOfflineResponse;
                }

                return new Response(
                    '<html lang="bn"><body><h1>ইন্টারনেট সংযোগ নেই</h1><p>সংযোগ ফিরে এলে আবার চেষ্টা করুন।</p></body></html>',
                    { headers: { "Content-Type": "text/html; charset=utf-8" } }
                );
            })
        );
        return;
    }

    // Never cache cross-origin requests or dynamic app/document requests.
    if (!isCacheableStaticRequest(event.request, requestUrl)) {
        return;
    }

    event.respondWith(
        cacheFirst(event.request).catch(() => {
            return new Response("", { status: 408 });
        })
    );
});
