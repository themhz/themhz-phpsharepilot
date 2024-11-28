<style>
    .custom-padding-45 {
        padding: 45px;
    }
</style>

<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Help</h1>
    </div>
    <div class="w3-container">
        <div class="w3-panel w3-leftbar w3-light-grey">
            <p><i class="fa fa-quote-right w3-xxlarge"></i><br>
                <i class="w3-serif w3-xlarge">Need assistance?</i>
            </p>
        </div>

        <div class="w3-panel w3-light-grey w3-padding-16">
            <h2>Introduction</h2>
            <p>Welcome to our Help page. Here, you will find answers to commonly asked questions and tips on how to use our service effectively.</p>
        </div>

        <div class="w3-panel w3-light-grey w3-padding-16">
            <h2>Frequently Asked Questions</h2>
            <div class="w3-panel w3-pale-blue w3-leftbar">
                <p><strong>Q: How do I reset my password?</strong></p>
                <p>A: You can reset the admin password by going to the settings page, click on the admin and enter the new password. Then click save.</p>
            </div>
            <div class="w3-panel w3-pale-green w3-leftbar">
                <p><strong>Q: Where is the tutorial?</strong></p>
                <p>You can read the tutorial on the tutorial page https://sharepilot.gr/tutorial.</p>
            </div>            
        </div>

        <div class="w3-panel w3-light-grey w3-padding-16">
            <h2>Contact Us</h2>
            <p>If you need further assistance, please feel free to reach out to us through the following channels:</p>
            <ul class="w3-ul w3-border">
                <li>Email: themhz@gmail.com</li>                
                <li>Url: <a href="https://CodeCraft.gr"  target="_blank" >CodeCraft.gr</a></li>
                <li>
                    <a href="https://www.facebook.com" target="_blank" class="w3-button w3-blue w3-margin-right">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <a href="https://www.instagram.com" target="_blank" class="w3-button w3-pink w3-margin-right">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                    <a href="https://www.twitter.com" target="_blank" class="w3-button w3-light-blue w3-margin-right">
                        <i class="fab fa-twitter"></i> Twitter (X)
                    </a>
                    <a href="https://www.reddit.com" target="_blank" class="w3-button w3-orange w3-margin-right">
                        <i class="fab fa-reddit"></i> Reddit
                    </a>
                </li>
            </ul>
        </div>

        <div class="w3-panel w3-light-grey w3-padding-16">
            <a id="version" href="updates">Version: </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        fetch('help?method=getversion&format=json', {
            method: "POST"
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            var navLinks = document.querySelector('#version');
            navLinks.innerHTML = "Version: " + data.message;
        })
        .catch(error => {
            console.log('There was a problem with the fetch operation:', error);
        });
    });
</script>
