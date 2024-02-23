<div class="w3-container w3-teal">
    <h1>SharePilot</h1>
</div>
<div class="login-container">
    <div>
        <label for="username">Username:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <input type="checkbox" id="remember" name="remember">
        <label for="remember">Remember me</label><br>
        <input type="button" value="Login" name="Login">
        <!-- Placeholder for the message -->        
        <?php
            /*if (isset($_GET["result"]) && $_GET["result"] === "false") {
                echo '<div id="message" class="w3-red" style="padding:10px;">';
                echo "wrong username or password";
                echo "<div>";
            }*/
        ?>        
    </div>
</div>

<script>
    function login(){
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
                console.log(data);
                
            });
    }
</script>
