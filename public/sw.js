/**
 * Suratku Service Worker
 * Progressive Web App (PWA) + Web Push Notification + App Badging
 * Politeknik Pekerjaan Umum
 */

const CACHE_NAME = 'suratku-v1';

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

// Wajib untuk kriteria PWA Chrome & pembuatan paket WebAPK mandiri (tanpa topbar CCT/browser)
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }
  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request))
  );
});

// 1. Terima sinyal Push Notification dari server Laravel
self.addEventListener('push', (event) => {
  let data = {};
  if (event.data) {
    try {
      data = event.data.json();
    } catch (e) {
      data = { title: 'Suratku', body: event.data.text() };
    }
  }

  const title = data.title || 'Suratku - Notifikasi Surat';
  const options = {
    body: data.body || 'Ada dokumen surat yang memerlukan tindakan Anda.',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/badge-72x72.png',
    tag: data.tag || 'suratku-notif',
    renotify: true,
    data: {
      url: data.url || '/dashboard',
    },
    vibrate: [200, 100, 200],
    actions: [
      { action: 'open', title: 'Buka Surat' }
    ]
  };

  // Update App Badge (angka merah di atas shortcut icon HP) jika didukung browser
  if (typeof data.badge_count === 'number' && 'setAppBadge' in navigator) {
    if (data.badge_count > 0) {
      navigator.setAppBadge(data.badge_count).catch(() => {});
    } else {
      navigator.clearAppBadge().catch(() => {});
    }
  }

  event.waitUntil(self.registration.showNotification(title, options));
});

// 2. Tangani klik pada notifikasi di layar HP
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  const targetUrl = (event.notification.data && event.notification.data.url)
    ? event.notification.data.url
    : '/dashboard';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      // Jika tab/aplikasi sudah terbuka, fokuskan dan arahkan
      for (const client of clientList) {
        if ('focus' in client) {
          if (client.url.includes(self.location.origin)) {
            client.navigate(targetUrl);
            return client.focus();
          }
        }
      }
      // Jika belum terbuka, buka window baru
      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});

// 3. Listener pesan dari aplikasi utama (misal update badge count secara lokal)
self.addEventListener('message', (event) => {
  if (!event.data) return;

  if (event.data.type === 'SET_BADGE' && 'setAppBadge' in navigator) {
    const count = parseInt(event.data.count, 10);
    if (count > 0) {
      navigator.setAppBadge(count).catch(() => {});
    } else {
      navigator.clearAppBadge().catch(() => {});
    }
  } else if (event.data.type === 'CLEAR_BADGE' && 'clearAppBadge' in navigator) {
    navigator.clearAppBadge().catch(() => {});
  }
});

