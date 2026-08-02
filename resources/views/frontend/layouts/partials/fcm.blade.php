{{-- resources/views/layouts/partials/fcm.blade.php --}}
<script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js"></script>

<script>
(function() {
    window.initPushNotification = async function() {
        console.warn('Firebase not ready yet.');
    };

    if (window.__fcmInitialized) return;
    window.__fcmInitialized = true;

    try {
        const FIREBASE_CONFIG = {
            apiKey:            @json(config('services.firebase.api_key')),
            authDomain:        @json(config('services.firebase.auth_domain')),
            projectId:         @json(config('services.firebase.project_id')),
            storageBucket:     @json(config('services.firebase.storage_bucket')),
            messagingSenderId: @json(config('services.firebase.sender_id')),
            appId:             @json(config('services.firebase.app_id')),
        };
        const VAPID_KEY  = @json(config('services.firebase.vapid_key'));
        const FCM_URL    = @json(route('fcm.token'));
        const CSRF_TOKEN = document.querySelector('meta[name=csrf-token]')?.content;

        if (!('serviceWorker' in navigator)) {
            throw new Error('Service workers not supported in this browser');
        }

        firebase.initializeApp(FIREBASE_CONFIG);
        const messaging = firebase.messaging();

        // Service worker register koro
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then((registration) => {
                window.initPushNotification = async function() {
                    try {
                        const permission = await Notification.requestPermission();
                        if (permission !== 'granted') return;

                        const token = await messaging.getToken({
                            vapidKey: VAPID_KEY,
                            serviceWorkerRegistration: registration,
                        });

                        if (!token) return;

                        await fetch(FCM_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                            },
                            body: JSON.stringify({ token }),
                        });

                        localStorage.setItem('fcm_token', token);
                        updateBellUI(true);
                    } catch (e) {
                        console.error('FCM token error:', e);
                    }
                };

                // Foreground message listener
                messaging.onMessage(function(payload) {
                    const { title, body, icon } = payload.notification || {};
                    const url = payload.data?.url || '/';
                    showToastNotification(title, body, url, icon);
                });

                // Page load e auto-init jodi permission age theke deওয়া thake
                if (Notification.permission === 'granted') {
                    window.initPushNotification();
                    updateBellUI(true);
                } else {
                    updateBellUI(false);
                }
            })
            .catch((err) => console.error('Service worker registration failed:', err));

    } catch (e) {
        console.error('Firebase init failed:', e);
    }

    function showToastNotification(title, body, url, icon) {
        const toast = document.createElement('div');
        toast.innerHTML = `
            <div class="fixed top-4 right-4 z-[9999] max-w-sm w-full bg-white rounded-xl shadow-2xl border border-gray-100 p-4 flex items-start gap-3 animate-slide-in cursor-pointer"
                 onclick="window.location.href='${url}'"
                 id="fcm-toast">
                <img src="${icon || '/images/logo.png'}" class="w-10 h-10 rounded-lg object-cover shrink-0" onerror="this.src='/images/logo.png'">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800">${title || ''}</p>
                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">${body || ''}</p>
                </div>
                <button onclick="event.stopPropagation(); this.closest('#fcm-toast').remove()"
                        class="text-gray-400 hover:text-gray-600 shrink-0">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                    </svg>
                </button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.querySelector('#fcm-toast')?.remove(), 5000);
    }

    function updateBellUI(subscribed) {
        const bell = document.getElementById('push-bell-btn');
        if (!bell) return;
        bell.classList.toggle('text-[#0071c5]', subscribed);
        bell.classList.toggle('text-gray-400', !subscribed);
        bell.title = subscribed ? 'Notifications ON' : 'Enable Notifications';
    }
    window.updateBellUI = updateBellUI;
})();
</script>