const CACHE_NAME = 'noteshub-cache-v1';
const OFFLINE_URL = 'offline.php';

const PRECACHE_ASSETS = [
    'assets/css/style.css',
    'assets/js/script.js',
    'assets/icons/icon-192.png',
    'assets/icons/icon-512.png',
    OFFLINE_URL
];

self.addEventListener('install', function (event) {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys.filter(function (key) { return key !== CACHE_NAME; })
                    .map(function (key) { return caches.delete(key); })
            );
        })
    );
    self.clients.claim();
});

// Network-first for pages (so notes stay fresh), cache-first for static assets
self.addEventListener('fetch', function (event) {
    const req = event.request;
    if (req.method !== 'GET') return;

    const isStatic = req.url.includes('/assets/');

    if (isStatic) {
        event.respondWith(
            caches.match(req).then(function (cached) {
                return cached || fetch(req);
            })
        );
    } else {
        event.respondWith(
            fetch(req).catch(function () {
                return caches.match(OFFLINE_URL);
            })
        );
    }
});
