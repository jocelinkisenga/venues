// service-worker.js (PRODUCTION - Ilands Solutions)

importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

const CACHE_VERSION = 'ilands-v1';
const OFFLINE_URL = '/offline.html';

// ===============================
// 1. SKIP WAITING (auto update)
// ===============================
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

workbox.core.skipWaiting();
workbox.core.clientsClaim();

// ===============================
// 2. PRECACHE (offline page + manifest build)
// ===============================
workbox.precaching.precacheAndRoute([
  { url: OFFLINE_URL, revision: CACHE_VERSION },
]);

// ===============================
// 3. CACHE ASSETS BUILD (VITE / MIX)
// ===============================
workbox.routing.registerRoute(
  ({ request }) =>
    request.destination === 'script' ||
    request.destination === 'style' ||
    request.destination === 'image' ||
    request.destination === 'font',

  new workbox.strategies.StaleWhileRevalidate({
    cacheName: `${CACHE_VERSION}-assets`,
    plugins: [
      new workbox.expiration.ExpirationPlugin({
        maxEntries: 200,
        maxAgeSeconds: 30 * 24 * 60 * 60, // 30 jours
      }),
    ],
  })
);

// ===============================
// 4. LARAVEL PAGES (Blade routes)
// ===============================
workbox.routing.registerRoute(
  ({ request }) => request.mode === 'navigate',

  new workbox.strategies.NetworkFirst({
    cacheName: `${CACHE_VERSION}-pages`,
    networkTimeoutSeconds: 3,
    plugins: [
      {
        handlerDidError: async () => {
          return caches.match(OFFLINE_URL);
        },
      },
    ],
  })
);

// ===============================
// 5. API LARAVEL (SAFE CACHE)
// ===============================
workbox.routing.registerRoute(
  ({ url }) => url.pathname.startsWith('/api'),

  new workbox.strategies.NetworkFirst({
    cacheName: `${CACHE_VERSION}-api`,
    networkTimeoutSeconds: 5,
    plugins: [
      new workbox.expiration.ExpirationPlugin({
        maxEntries: 100,
        maxAgeSeconds: 5 * 60, // 5 min (API toujours fresh)
      }),
    ],
  })
);

// ===============================
// 6. OFFLINE FALLBACK GLOBAL
// ===============================
self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(async () => {
        return caches.match(OFFLINE_URL);
      })
    );
  }
});