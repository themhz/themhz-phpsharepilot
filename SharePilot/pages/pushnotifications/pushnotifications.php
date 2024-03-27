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
        <button onclick="subscribeToNotification()" class="w3-button w3-teal w3-large">Subscribe</button>        
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


    function subscribeToNotification(){
        navigator.serviceWorker.register('/service-worker.js')
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
            console.error('Service worker registration or push subscription failed', error);
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

    function sendNotification(){                
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
       
    }

</script>