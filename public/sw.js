/**
 * Service Worker — reservation_ci PWA
 * Merged: smart caching strategies + localStorage sync + message passing
 */

const CACHE_NAME  = 'reservation-ci-v1';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
  '/offline.html',
  '/manifest.json',
  '/favicon.ico',
  'https://cdn.tailwindcss.com',
  'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
  'https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js',
];

// ── Install ───────────────────────────────────────────────────────────────────
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(PRECACHE_ASSETS))
      .then(() => self.skipWaiting())
  );
});

// ── Activate ──────────────────────────────────────────────────────────────────
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys()
      .then(keys => Promise.all(
        keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
      ))
      .then(() => self.clients.claim())
  );
});

// ── Fetch ─────────────────────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET
  if (request.method !== 'GET') return;

  // Form submissions / API → handle offline save
  if (
    url.pathname.includes('/api/') ||
    url.pathname.includes('/reservation/create') ||
    url.pathname.includes('/create-reservation') ||
    url.pathname.includes('/check-availability') ||
    url.pathname.includes('/check-new-approvals')
  ) {
    event.respondWith(handleFormSubmission(request));
    return;
  }

  // CDN assets → cache-first
  if (
    url.hostname.includes('cdn.tailwindcss.com') ||
    url.hostname.includes('cdnjs.cloudflare.com') ||
    url.hostname.includes('cdn.jsdelivr.net') ||
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com')
  ) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // HTML pages → network-first, fallback to cache then offline page
  if (request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(networkFirstWithOfflineFallback(request));
    return;
  }

  // Everything else → stale-while-revalidate
  event.respondWith(staleWhileRevalidate(request));
});

// ── Form submission handler ───────────────────────────────────────────────────
async function handleFormSubmission(request) {
  if (!navigator.onLine) {
    if (
      request.url.includes('/reservation/create') ||
      request.url.includes('/create-reservation')
    ) {
      try {
        const formData = await request.clone().formData();
        const data = {};
        formData.forEach((value, key) => { data[key] = value; });

        // SW can't access localStorage — message the client to save it
        const allClients = await self.clients.matchAll({ type: 'window' });
        allClients.forEach(client => {
          client.postMessage({
            type:    'SAVE_PENDING_RESERVATION',
            payload: { data, url: request.url, timestamp: Date.now() }
          });
        });
      } catch (e) {
        console.warn('[SW] Could not read formData offline:', e);
      }

      return new Response(JSON.stringify({
        status:  'offline',
        message: 'Reservation saved offline. Will sync when online.'
      }), {
        status:  202,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    return new Response(JSON.stringify({
      error:   true,
      message: 'You are offline. Please check your connection.'
    }), {
      status:  503,
      headers: { 'Content-Type': 'application/json' }
    });
  }

  return fetch(request);
}

// ── Background Sync ───────────────────────────────────────────────────────────
self.addEventListener('sync', event => {
  if (event.tag === 'sync-reservations') {
    event.waitUntil(syncPendingReservations());
  }
});

async function syncPendingReservations() {
  const allClients = await self.clients.matchAll({ type: 'window' });
  allClients.forEach(client => {
    client.postMessage({ type: 'FLUSH_PENDING_RESERVATIONS' });
  });
}

// ── Push Notifications ────────────────────────────────────────────────────────
self.addEventListener('push', event => {
  const data    = event.data?.json() ?? {};
  const title   = data.title || 'Reservation Update';
  const options = {
    body:     data.body  || 'You have a new notification.',
    icon:     data.icon  || '/assets/img/icon-192.png',
    badge:    data.badge || '/assets/img/icon-96.png',
    tag:      data.tag   || 'reservation',
    renotify: true,
    data:     data.data  || {},
    actions: [
      { action: 'view',    title: 'View Reservation' },
      { action: 'dismiss', title: 'Dismiss' },
    ],
    vibrate: [200, 100, 200],
  };
  event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', event => {
  event.notification.close();
  if (event.action === 'view' || !event.action) {
    const url = event.notification.data?.url || '/reservation-list';
    event.waitUntil(
      clients.matchAll({ type: 'window' }).then(windowClients => {
        for (const client of windowClients) {
          if (client.url.includes(url) && 'focus' in client) return client.focus();
        }
        if (clients.openWindow) return clients.openWindow(url);
      })
    );
  }
});

// ── Message passing (page → SW) ───────────────────────────────────────────────
self.addEventListener('message', event => {
  if (!event.data) return;
  switch (event.data.type) {
    case 'SKIP_WAITING':
      self.skipWaiting();
      break;
    case 'RESERVATIONS_SYNCED':
      // Client confirmed sync — nothing needed SW side
      break;
  }
});

// ── Strategies ────────────────────────────────────────────────────────────────
async function cacheFirst(request) {
  const cached = await caches.match(request);
  if (cached) return cached;
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return new Response('Offline', { status: 503 });
  }
}

async function networkFirstWithOfflineFallback(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    const cached = await caches.match(request);
    if (cached) return cached;
    return caches.match(OFFLINE_URL);
  }
}

async function staleWhileRevalidate(request) {
  const cache        = await caches.open(CACHE_NAME);
  const cached       = await cache.match(request);
  const fetchPromise = fetch(request).then(response => {
    if (response.ok) cache.put(request, response.clone());
    return response;
  }).catch(() => cached);
  return cached || fetchPromise;
}