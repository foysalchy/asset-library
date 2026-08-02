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

messaging.onBackgroundMessage(function (payload) {
    const { title, body, icon } = payload.notification || {};
    self.registration.showNotification(title || 'Notification', {
        body: body || '',
        icon: icon || '/images/logo.png',
        data: payload.data,
    });
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = event.notification.data?.url || '/';
    event.waitUntil(clients.openWindow(url));
});