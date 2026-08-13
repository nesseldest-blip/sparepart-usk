// ============================================
// SERVICE WORKER - PWA Sparepart USK
// ============================================

const CACHE_NAME = 'sparepart-usk-v2';
const urlsToCache = [
    './',
    'index.html',
    'login.html',
    'register.html',
    'products.html',
    'product-detail.html',
    'cart.html',
    'checkout.html',
    'orders.html',
    'admin.html',
    'assets/css/style.css',
    'assets/js/app.js',
    'assets/js/script.js',
    'manifest.json'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) return response;
                return fetch(event.request);
            })
    );
});
