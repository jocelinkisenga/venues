const CACHE = 'salle-v1';
const PRECACHE = ['/entree-hors-ligne.html', '/manifest.webmanifest'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.addAll(PRECACHE)));
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    const url = new URL(req.url);

    if (req.method !== 'GET' || url.pathname.startsWith('/api/') || url.pathname.startsWith('/livewire')) {
        return;
    }

    // Fichiers compilés (CSS, JS) : cache d'abord, ils ne contiennent aucune donnée
    if (url.pathname.startsWith('/build/')) {
        event.respondWith(
            caches.match(req).then((hit) => hit || fetch(req).then((res) => {
                const copy = res.clone();
                caches.open(CACHE).then((c) => c.put(req, copy));
                return res;
            }))
        );
        return;
    }

    // Pages : réseau d'abord, sinon écran d'entrée hors ligne
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).catch(() => caches.match('/entree-hors-ligne.html'))
        );
    }
});