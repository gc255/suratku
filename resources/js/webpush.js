/**
 * Suratku Web Push & App Badging Client
 * Progressive Web App (PWA) Push Notification Support
 */

function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

const SuratkuPush = {
  vapidPublicKey: null,
  registration: null,

  async init(vapidPublicKey) {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
      return false;
    }

    this.vapidPublicKey = vapidPublicKey;

    try {
      this.registration = await navigator.serviceWorker.register('/sw.js', { scope: '/' });

      // Jika izin sudah diberikan sebelumnya, sinkronkan langganan & perbarui badge
      if (Notification.permission === 'granted') {
        await this.syncSubscription();
        await this.updateBadge();
      }

      return true;
    } catch (err) {
      console.warn('[SuratkuPush] Service worker register failed:', err);
      return false;
    }
  },

  async isSubscribed() {
    if (!this.registration) return false;
    const subscription = await this.registration.pushManager.getSubscription();
    return subscription !== null;
  },

  async requestPermissionAndSubscribe() {
    if (!('Notification' in window) || !('PushManager' in window)) {
      alert('Browser Anda tidak mendukung Web Push Notification.');
      return false;
    }

    if (!this.vapidPublicKey) {
      console.warn('[SuratkuPush] VAPID Public Key belum disetel.');
      return false;
    }

    try {
      const permission = await Notification.requestPermission();
      if (permission !== 'granted') {
        return false;
      }

      await this.subscribe();
      await this.updateBadge();
      return true;
    } catch (err) {
      console.error('[SuratkuPush] Gagal meminta izin notifikasi:', err);
      return false;
    }
  },

  async subscribe() {
    if (!this.registration || !this.vapidPublicKey) return null;

    try {
      const applicationServerKey = urlBase64ToUint8Array(this.vapidPublicKey);
      let subscription = await this.registration.pushManager.getSubscription();

      // Jika belum ada, daftarkan subscription baru
      if (!subscription) {
        subscription = await this.registration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: applicationServerKey,
        });
      }

      // Kirim data subscription ke server Laravel
      await this.sendSubscriptionToServer(subscription);
      return subscription;
    } catch (err) {
      console.error('[SuratkuPush] Gagal berlangganan push:', err);
      return null;
    }
  },

  async syncSubscription() {
    try {
      if (!this.registration) return;
      const subscription = await this.registration.pushManager.getSubscription();
      if (subscription) {
        await this.sendSubscriptionToServer(subscription);
      }
    } catch (err) {
      console.warn('[SuratkuPush] Gagal sinkronisasi subscription:', err);
    }
  },

  async sendSubscriptionToServer(subscription) {
    const raw = subscription.toJSON();
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const res = await fetch('/push/subscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token || '',
      },
      body: JSON.stringify({
        endpoint: subscription.endpoint,
        keys: raw.keys,
        contentEncoding: (PushManager.supportedContentEncodings || ['aes128gcm'])[0],
      }),
    });

    if (res.ok) {
      const data = await res.json();
      if (typeof data.badge_count === 'number') {
        this.setBadge(data.badge_count);
      }
    }
  },

  async unsubscribe() {
    try {
      if (!this.registration) return;
      const subscription = await this.registration.pushManager.getSubscription();
      if (subscription) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        await fetch('/push/unsubscribe', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token || '',
          },
          body: JSON.stringify({ endpoint: subscription.endpoint }),
        });
        await subscription.unsubscribe();
        this.clearBadge();
      }
    } catch (err) {
      console.warn('[SuratkuPush] Gagal unsubscribe:', err);
    }
  },

  async updateBadge() {
    try {
      const res = await fetch('/push/badge', {
        headers: { 'Accept': 'application/json' },
      });
      if (res.ok) {
        const data = await res.json();
        if (typeof data.badge_count === 'number') {
          this.setBadge(data.badge_count);
        }
      }
    } catch (e) {
      // diamkan bila offline
    }
  },

  setBadge(count) {
    if ('setAppBadge' in navigator) {
      if (count > 0) {
        navigator.setAppBadge(count).catch(() => {});
      } else {
        navigator.clearAppBadge().catch(() => {});
      }
    }
  },

  clearBadge() {
    if ('clearAppBadge' in navigator) {
      navigator.clearAppBadge().catch(() => {});
    }
  }
};

window.SuratkuPush = SuratkuPush;

// Otomatis jalankan inisialisasi jika ada meta vapid-public-key
document.addEventListener('DOMContentLoaded', () => {
  const vapidMeta = document.querySelector('meta[name="vapid-public-key"]');
  if (vapidMeta && vapidMeta.content) {
    SuratkuPush.init(vapidMeta.content);
  }
});

export default SuratkuPush;
