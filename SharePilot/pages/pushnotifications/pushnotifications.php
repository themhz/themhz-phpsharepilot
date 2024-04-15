<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Push Notificationers</h1>
    </div>
    <div class="w3-container">
        <div class="w3-panel w3-leftbar">
            <p><i class="fa fa-quote-right w3-xxlarge"></i><br>
                <i class="w3-serif w3-xlarge">Push Notificationers

                </i>
            </p>
        </div>
    </div>
</div>
<div class="w3-row">
    <div class="w3-twothird" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px;">
            <h1 class="w3-text-teal">Push Notificationers</h1>
            <button id="sortButton" class="w3-button w3-teal w3-large"><i class="fas fa-sort"></i> Sort</button>
            
        </div>
        <div class="w3-container">
        <button onclick="sendNotification()" class="w3-button w3-teal w3-large">Send message</button>
        <button onclick="subscribeToNotification2()" class="w3-button w3-teal w3-large">Subscribe</button>        
            <ul class="w3-ul w3-card-4" id="sociallist">
                <!-- List items will be appended here -->
                
            </ul>
            
        </div>
        
        <span id="token"></span>
    </div>
    
</div>
<script>
    document.addEventListener('readystatechange', function(evt) {        
        if(evt.target.readyState == "complete")
        {           
            getKeys();            
        }
    }, false);
    


    function getKeys() {
        fetch('pushnotifications?method=getKeys&format=json', {
            method: "POST"
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.log('There was a problem with the fetch operation:', error);
        });
    }

</script>

<script type="module">
     
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";
        
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyAbpxWmMCN-LXX6nipW5F26Z3S_V4p8HVs",
            authDomain: "sharepilot-939ee.firebaseapp.com",
            projectId: "sharepilot-939ee",
            storageBucket: "sharepilot-939ee.appspot.com",
            messagingSenderId: "904029506864",
            appId: "1:904029506864:web:94fe33895e5f168fa58cf5",
            measurementId: "G-EZE78SEWVK"
        };
        
        // Initialize Firebase
        const app = initializeApp(firebaseConfig);        
        
        var messaging;
        
        try{
                
            messaging = getMessaging(app);
        }catch(error){
            alert(error);
        }
        
        
       
        navigator.serviceWorker.register("sw.js").then(registration => {
            
            getToken(messaging, {
                serviceWorkerRegistration: registration,
                vapidKey: 'BEb-UDdo4nmNGdfHw5qPYqAkTbh0H6ahBr1nIamO0amKS0lUkYgsTu-KRMq8lct-6-rNL9RxLpaEYk2cgAEPW0I' }).then((currentToken) => {
                if (currentToken) {
                    console.log("Token is: "+currentToken);
                    document.querySelector("#token").innerText = currentToken;
                    alert(currentToken);
                    // Send the token to your server and update the UI if necessary
                    // ...
                } else {
                    // Show permission request UI
                    console.log('No registration token available. Request permission to generate one.');
                    // ...
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // ...
            });
        });



    </script>