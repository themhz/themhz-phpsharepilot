<?php
namespace SharePilotV2\Components;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SharePilot</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="template/css/login.css">
<link rel="stylesheet" href="template/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="template/js/main.js?v=<?php echo time(); ?>"></script>
</head>
<body>
    <!-- Overlay effect when opening sidebar on small screens -->
    <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
    <!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
    <div class="w3-main">        
        <div class="w3-container w3-teal">
            <h1>SharePilot</h1>
        </div>
        <div class="login-container">
            <div class="form">
                <label for="username">Username:</label><br>
                <input type="text" id="email" name="email"><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br>                                
                <input type="button" value="Login" name="Login" id="Login" onclick="login()">                
            </div>
            <div class="w3-panel w3-red" style="display:none;" id="error">
                <p>Wrong username or password</p>
            </div> 
        </div>
    </div>
    <script>
    function login(){        
        document.querySelector("#Login").style.backgroundColor  = "grey";
        document.querySelector("#Login").style.disabled = true;
        fetch('login/authentication?format=json', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    email: document.querySelector("#email").value,
                    password:document.querySelector("#password").value,                    
                })
            })
            .then(response => response.json())
            .then(data => {
                document.querySelector("#Login").style.backgroundColor  = "#4CAF50";
                document.querySelector("#Login").style.disabled = false;
                document.querySelector("#error").style.display="none";
                if(data.userAuth==false){
                    document.querySelector("#error").style.display="block";
                }else{
                    window.location.href="default";
                }                
            });
    }
</script>

</body>
</html>
