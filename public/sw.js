/**
 * Service Worker — reservation_ci PWA
 * Offline mode: dashboard accessible, create/reserve/live-data blocked.
 */

const CACHE_NAME  = 'reservation-ci-v3';
const OFFLINE_URL = '/offline.html';

// Pages that should be cached for offline shell access
const PRECACHE_ASSETS = [
  '/offline.html',
  '/manifest.json',
  '/assets/img/icon-192.png',
  '/assets/img/icon-512.png',
  'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
  'https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js',
];

// Routes that are READ-ONLY views — safe to serve from cache offline
const CACHEABLE_PAGES = [
  '/admin/dashboard',
  '/dashboard',
  '/admin/activity-logs',
  '/reservation-list',
  '/admin/reservations',
  '/admin/manage-users',
];

// Routes that REQUIRE internet — block offline with structured error
const REQUIRES_ONLINE = [
  '/reservation/create',
  '/create-reservation',
  '/admin/reservation/approve',
  '/admin/reservation/decline',
  '/check-availability',
  '/check-new-approvals',
  '/api/',
];

// Routes that load LIVE data — return empty offline payload
const LIVE_DATA_ROUTES = [
  '/reservation/list',
  '/admin/reservations/list',
  '/admin/logs/list',
  '/get-reservations',
  '/load-reservations',
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

  // Skip non-GET and chrome-extension
  if (request.method !== 'GET') return;
  if (url.protocol === 'chrome-extension:') return;

  // ── CDN / static assets → cache-first (works offline fine) ──
  if (
    url.hostname.includes('cdnjs.cloudflare.com') ||
    url.hostname.includes('cdn.jsdelivr.net') ||
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com')
  ) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // ── Live data routes → offline empty payload ──
  if (LIVE_DATA_ROUTES.some(r => url.pathname.includes(r))) {
    event.respondWith(handleLiveData(request));
    return;
  }

  // ── Action routes that need internet → block offline ──
  if (REQUIRES_ONLINE.some(r => url.pathname.includes(r))) {
    event.respondWith(handleRequiresOnline(request));
    return;
  }

  // ── HTML pages → network-first, fallback to cached shell ──
  if (request.headers.get('accept')?.includes('text/html')) {
    event.respondWith(networkFirstWithShellFallback(request));
    return;
  }

  // ── Everything else → stale-while-revalidate ──
  event.respondWith(staleWhileRevalidate(request));
});

// ── Handler: routes that need live internet (create, approve, etc.) ───────────
async function handleRequiresOnline(request) {
  if (!navigator.onLine) {
    return offlineJsonResponse({
      offline:  true,
      code:     'REQUIRES_ONLINE',
      message:  'This action requires an internet connection.',
    }, 503);
  }
  return fetch(request);
}

// ── Handler: live data routes (reservation lists, logs, etc.) ─────────────────
async function handleLiveData(request) {
  if (!navigator.onLine) {
    return offlineJsonResponse({
      offline:  true,
      code:     'NO_LIVE_DATA',
      message:  'Live data is not available offline.',
      data:     [],
    }, 503);
  }

  try {
    const response = await fetch(request);
    // Don't cache live data — always fresh
    return response;
  } catch {
    return offlineJsonResponse({
      offline:  true,
      code:     'NO_LIVE_DATA',
      message:  'Could not load data. Check your connection.',
      data:     [],
    }, 503);
  }
}

// ── Handler: HTML pages with shell fallback ───────────────────────────────────
async function networkFirstWithShellFallback(request) {
  const url = new URL(request.url);

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      // Only cache pages that are safe to view offline
      const isCacheable = CACHEABLE_PAGES.some(p => url.pathname.startsWith(p));
      if (isCacheable) cache.put(request, response.clone());
    }
    return response;
  } catch {
    // Try cached version of this exact page first
    const cached = await caches.match(request);
    if (cached) return cached;

    // Try the root dashboard as shell fallback
    const dashFallback = await caches.match('/dashboard') ||
                         await caches.match('/admin/dashboard');
    if (dashFallback) return dashFallback;

    // Last resort: offline page
    return caches.match(OFFLINE_URL);
  }
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

// ── Message passing ───────────────────────────────────────────────────────────
self.addEventListener('message', event => {
  if (!event.data) return;
  switch (event.data.type) {
    case 'SKIP_WAITING':
      self.skipWaiting();
      break;
    case 'RESERVATIONS_SYNCED':
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

async function staleWhileRevalidate(request) {
  const cache        = await caches.open(CACHE_NAME);
  const cached       = await cache.match(request);
  const fetchPromise = fetch(request).then(response => {
    if (response.ok) cache.put(request, response.clone());
    return response;
  }).catch(() => cached);
  return cached || fetchPromise;
}

function offlineJsonResponse(payload, status = 503) {
  return new Response(JSON.stringify(payload), {
    status,
    headers: { 'Content-Type': 'application/json' },
  });
}