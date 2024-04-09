// service-worker.js

// Listen for push events from the push service
self.addEventListener('push', function(event) {
    // Parse the incoming push message data
    let data = {};
    if (event.data) {
        data = event.data.json();
    }

    // Options for the notification
    const options = {
        body: data.body || 'You have a new message!',
        icon: 'icon.png',
        badge: 'badge.png',
        // Other options can go here
    };

    // Show the notification
    event.waitUntil(
        self.registration.showNotification(data.title || 'Notification', options)
    );
});

// Optional: listen for the notification click event
self.addEventListener('notificationclick', function(event) {
    event.notification.close(); // Android needs explicit close.

    // Do something with the event
    event.waitUntil(
        clients.openWindow('https://localhost') // Replace with your own URL
    );
});
