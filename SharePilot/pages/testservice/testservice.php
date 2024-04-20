<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscription Page</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }
    .container {
        display: flex;
        justify-content: space-around;
        padding: 20px;
    }
    .column {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        margin: 10px;
        text-align: center;
    }
    button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }
</style>
</head>
<body>
<div class="container">
    <div class="column">
        <h2>Single User Notification</h2>
        <p>Subscribe to receive updates for individual notifications.</p>
        <button id="singleSubscribeBtn">Single Subscribe</button>
    </div>
    <div class="column">
        <h2>Subscribe to a Group</h2>
        <p>Join a group to receive collective notifications.</p>
        <button id="groupSubscribeBtn">Group Subscribe</button>
    </div>
</div>
>
<script type="module">         
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";
    
    document.addEventListener('DOMContentLoaded', () => {
        const singleSubscribeBtn = document.getElementById('singleSubscribeBtn');
        const groupSubscribeBtn = document.getElementById('groupSubscribeBtn');

        singleSubscribeBtn.addEventListener('click', singleSubscribe);
        groupSubscribeBtn.addEventListener('click', groupSubscribe);
    });
    

    function singleSubscribe(){        
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
        var messaging = getMessaging(app)                       
        
        navigator.serviceWorker.register("sw.js").then(registration => {
            
            //Get the vapid key first of all. This is the Key pair
            //Here the vapid key is BEb-UDdo4nmNGdfHw5qPYqAkTbh0H6ahBr1nIamO0amKS0lUkYgsTu-KRMq8lct-6-rNL9RxLpaEYk2cgAEPW0I
            //Take the key and save it to your database
            getToken(messaging, {
                serviceWorkerRegistration: registration,
                vapidKey: 'BEb-UDdo4nmNGdfHw5qPYqAkTbh0H6ahBr1nIamO0amKS0lUkYgsTu-KRMq8lct-6-rNL9RxLpaEYk2cgAEPW0I' }).then((currentToken) => {
                if (currentToken) {
                    console.log("Token is: "+currentToken);
                    saveToken(currentToken);
                    //document.querySelector("#token").innerText = currentToken;
                    //console.log(currentToken);
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
    }

    function saveToken(token){
        fetch('testservice/savetoken?format=json', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    ftoken: token,                    
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                
            });
            
    }

    function groupSubscribe(){
        alert(2);
    }

</script>
</body>
</html>
