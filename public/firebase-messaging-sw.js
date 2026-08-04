// public/firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyBvwP08S_h_lEE8YoV6yDnW8QMha4iEnsY",
    authDomain: "asset-library-e4b60.firebaseapp.com",
    projectId: "asset-library-e4b60",
    storageBucket: "asset-library-e4b60.firebasestorage.app",
    messagingSenderId: "621079292689",
    appId: "1:621079292689:web:8804bc3895507c16c73ab0",
    measurementId: "G-PMLXFLT9VT"
});

const messaging = firebase.messaging();

// public/firebase-messaging-sw.js
messaging.onBackgroundMessage(function(payload) {


    const data = payload.data || {};
    const title = data.title || 'New Notification';
    const body  = data.body  || '';
    const url   = data.url   || '/';
    const icon  = data.icon  || '/logo.png';

    self.registration.showNotification(title, {
        body: body,
        icon: icon,
        badge: icon,
        data: { url: url },
    });
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = event.notification.data?.url || '/';
    event.waitUntil(clients.openWindow(url));
});