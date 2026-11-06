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
  swUrl: '/sw.js',
  registration: null,

  async init(vapidPublicKey, swUrl) {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
      return false;
    }

    this.vapidPublicKey = vapidPublicKey;
    if (swUrl) {
      this.swUrl = swUrl;
    }

    try {
      this.registration = await navigator.serviceWorker.register(this.swUrl, { scope: '/' });

      // Jika izin sudah diberikan sebelumnya, langsung aktifkan & sinkronkan
      if (Notification.permission === 'granted') {
        await this.subscribe();
        await this.updateBadge();
      } else if (Notification.permission === 'default') {
        // Minta izin secara native pada interaksi/ketukan pertama pengguna (tanpa banner/header tambahan)
        this.bindNativePermissionPrompt();
      }

      return true;
    } catch (err) {
      console.warn('[SuratkuPush] Service worker register failed:', err);
      return false;
    }
  },

  bindNativePermissionPrompt() {
    const askOnce = async () => {
      document.removeEventListener('click', askOnce);
      document.removeEventListener('touchend', askOnce);

      if (Notification.permission !== 'default') return;

      try {
        const perm = await Notification.requestPermission();
        if (perm === 'granted') {
          await this.subscribe();
          await this.updateBadge();
        }
      } catch (err) {
        // Abaikan bila pengguna membatalkan
      }
    };

    document.addEventListener('click', askOnce, { once: true });
    document.addEventListener('touchend', askOnce, { once: true });
  },

  async isSubscribed() {
    if (!this.registration) return false;
    const subscription = await this.registration.pushManager.getSubscription();
    return subscription !== null;
  },

  async subscribe() {
    if (!this.registration || !this.vapidPublicKey) return null;

    try {
      const applicationServerKey = urlBase64ToUint8Array(this.vapidPublicKey);
      let subscription = await this.registration.pushManager.getSubscription();

      if (!subscription) {
        subscription = await this.registration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: applicationServerKey,
        });
      }

      await this.sendSubscriptionToServer(subscription);
      return subscription;
    } catch (err) {
      console.warn('[SuratkuPush] Subscribe error:', err);
      return null;
    }
  },

  async sendSubscriptionToServer(subscription) {
    const raw = subscription.toJSON();
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
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
    } catch (e) {
      console.warn('[SuratkuPush] Failed to send subscription to server:', e);
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
      console.warn('[SuratkuPush] Unsubscribe error:', err);
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
      // offline
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

document.addEventListener('DOMContentLoaded', () => {
  const vapidMeta = document.querySelector('meta[name="vapid-public-key"]');
  const swMeta = document.querySelector('meta[name="service-worker-url"]');
  if (vapidMeta && vapidMeta.content) {
    SuratkuPush.init(vapidMeta.content, swMeta ? swMeta.content : '/sw.js');
  }
});

export default SuratkuPush;
