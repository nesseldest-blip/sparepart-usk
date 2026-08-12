// ============================================
// SERVICE WORKER - PWA Sparepart USK
// Memungkinkan website di-install sebagai APK
// ============================================

const CACHE_NAME = 'sparepart-usk-v1';
const urlsToCache = [
    '/sparepart-usk/',
    '/sparepart-usk/index.php',
    '/sparepart-usk/assets/css/style.css',
    '/sparepart-usk/assets/js/script.js',
    '/sparepart-usk/manifest.json'
];

// Install service worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Aktifkan service worker
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
});

// Fetch - gunakan cache dulu, lalu network
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Jika ada di cache, kembalikan
                if (response) {
                    return response;
                }
                // Jika tidak, fetch dari network
                return fetch(event.request);
            })
    );
});