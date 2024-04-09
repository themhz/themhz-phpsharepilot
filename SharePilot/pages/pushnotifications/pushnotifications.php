<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Push Notifications</h1>
    </div>
    <div class="w3-container">
        <div class="w3-panel w3-leftbar">
            <p><i class="fa fa-quote-right w3-xxlarge"></i><br>
                <i class="w3-serif w3-xlarge">Push notifications

                </i>
            </p>
        </div>
    </div>
</div>
<div class="w3-row">
    <div class="w3-twothird" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px;">
            <h1 class="w3-text-teal">Push Notifications</h1>
            <button id="sortButton" class="w3-button w3-teal w3-large"><i class="fas fa-sort"></i> Sort</button>
        </div>
        <div class="w3-container">
        <button onclick="sendNotification()" class="w3-button w3-teal w3-large">Send message</button>
        <button onclick="subscribeToNotification2()" class="w3-button w3-teal w3-large">Subscribe</button>        
            <ul class="w3-ul w3-card-4" id="sociallist">
                <!-- List items will be appended here -->
                
            </ul>
            
        </div>
                
    </div>
    
</div>
<script>
    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete")
        {
           


        }
    }, false);

    function subscribeToNotification2() {
        // First, check if the browser supports service workers and notifications
        if ('serviceWorker' in navigator && 'Notification' in window) {
            // Request notification permission from the user
            Notification.requestPermission().then(function(permission) {
            if (permission === "granted") {
                console.log("Notification permission granted.");
                // If permission is granted, register the service worker
                navigator.serviceWorker.register("/sw.js")
                .then(function(registration) {
                    console.log("Service Worker registered successfully.");
                    // TODO: You can now subscribe the user to push notifications
                })
                .catch(function(error) {
                    console.error("Service Worker registration failed:", error);
                });
            } else {
                console.log("Notification permission was not granted.");
            }
            });
        } else {
            console.log("Service Workers and/or Notifications are not supported in this browser.");
        }
        }

    function subscribeToNotification(){
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => navigator.serviceWorker.ready) // Ensure service worker is ready        
            .then(registration => {
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array('BEb-UDdo4nmNGdfHw5qPYqAkTbh0H6ahBr1nIamO0amKS0lUkYgsTu-KRMq8lct-6-rNL9RxLpaEYk2cgAEPW0I')
            });
            })
            .then(subscription => {
            // Convert subscription to JSON and send it to the server
            const subscriptionJson = subscription.toJSON();
            // Send subscriptionJson to your server to store it
            })
            .catch(error => {
                console.log(error);
            });
        }

        // Utility function to convert the VAPID key from a URL-safe base64 string to a Uint8Array
        function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
        }

        
    /*function sendNotification(){                
        fetch('pushnotifications/sendnotification?format=json', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                    somevariable: "asdasd"
                })
            })
            .then(response => response.json())
            .then(response => {
                console.log(response);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
       
    }*/

    function sendNotification() {
        // Check if the browser supports notifications
        if (!("Notification" in window)) {
            alert("This browser does not support desktop notification");
        }
        // Check whether notification permissions have already been granted
        else if (Notification.permission === "granted") {
            // If it's okay, create a notification
            const notification = new Notification("Hi there!", {
            body: "This is an example notification.",
            icon: "http://localhost/template/images/logo-no-background-mini.png"
            });
        }
        // Otherwise, we need to ask the user for permission
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
                const notification = new Notification("Hi there!", {
                body: "This is an example notification.",
                icon: "http://localhost/template/images/logo-no-background-mini.png"
                });
            }
            });
        }
        // At last, if the user has denied notifications, and you want to be respectful there is nothing more to do
    }

//sendNotification();  // Call this function when you want to send the notification


</script>