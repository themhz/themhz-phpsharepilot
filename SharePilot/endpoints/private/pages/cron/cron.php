    <div class="w3-row custom-padding-45">
        <div class="w3-container w3-teal">
            <h1>Cron Page</h1>
        </div>
        <div class="w3-container">
            <div class="w3-panel w3-leftbar">
                <p><i class="fa fa-quote-right w3-xxlarge"></i><br>
                    <i class="w3-serif w3-xlarge">Managing and configuring your cron job</i>        
                    <i class="w3-serif w3-large" id="cronstatus"><b></b></i>    
                </p>                                
            </div>            
        </div>
        <div class="w3-row">        
            <div class="w3-container">
                <input class="w3-btn w3-teal" value="Execute Cron" type="button" id="btnsearch" name="btnsearch" onclick="executeCron()">
                <input class="w3-btn w3-teal" value="Check if cron is running" type="button" id="btnsearch" name="btnsearch" onclick="checkCronTab()">
                <input class="w3-btn w3-teal" value="Create Cron Token" type="button" id="btnsearch" name="btnsearch" onclick="createCronToken()">
            </div>        
        </div>
        <div class="w3-container w3-margin">            
            <p>
                <ul>
                    <li>
                        <strong>Checking the Status of the Cron Service:</strong>
                        <p>To verify if the cron service is running, use:</p>
                        <ul>
                            <li>For systems using systemd:</li>
                            <code>systemctl status cron</code>
                            <li>For other init systems:</li>
                            <code>service cron status</code>
                        </ul>
                    </li>
                    <li>
                        <strong>Starting or Stopping the Cron Service:</strong>
                        <p>To start or stop the cron service, use:</p>
                        <ul>
                            <li>To start the service with systemd:</li>
                            <code>systemctl start cron</code>
                            <li>To stop the service with systemd:</li>
                            <code>systemctl stop cron</code>
                            <li>To start the service with another init system:</li>
                            <code>service cron start</code>
                            <li>To stop the service with another init system:</li>
                            <code>service cron stop</code>
                        </ul>
                    </li>
                    <li>
                        <strong>Adding Your Specific Cron Job:</strong>
                        <p>To add your cron job, ensuring it executes in the correct directory:</p>
                        <code>* * * * * cd /var/www/html &amp;&amp; /usr/local/bin/php index.php page=cron method=post format=json token=<span id="token1">{Cron Token}</span> &gt; /var/www/html/logfile.log 2&gt;&amp;1</code>
                        <p>Replace <code id="token2">{Cron Token}</code> with your actual token. This configuration changes the working directory before executing the script and redirects output and errors to a log file.</p>
                    </li>
                </ul>
            </p>
        </div>        
    </div>    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkCronTab();
            getToken();
        });

        function executeCron(){
            fetch('cron?format=json', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    test:1
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                
            });
        }

        function checkCronTab(){
            fetch('cron/checkcrontab?format=json', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    test:1
                })
            })
            .then(response => response.json())
            .then(data => {
                //console.log(data);
                var cronStatusElement = document.querySelector("#cronstatus");
                cronStatusElement.querySelector("b").innerText = data.cronstatus;
                cronStatusElement.classList.remove("w3-text-green", "w3-text-red");
                var isSuccess = true;
                if(data.cronstatus.includes("failed")){
                    isSuccess=false;
                }

                if (isSuccess) {
                    cronStatusElement.classList.add("w3-text-green");
                } else {
                    cronStatusElement.classList.add("w3-text-red");
                }

            });
        }

        function createCronToken(){
            fetch('cron/createcrontoken?format=json', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    test:1
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.token);
                document.querySelector("#token1").innerText = data.token;
                document.querySelector("#token2").innerText = data.token;
            });
        }

        function getToken(){
            fetch('cron?format=json', {
                method: "GET",
                headers: {
                    "Content-Type": "application/json"
                }                
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.token);
                document.querySelector("#token1").innerText = data.token;
                document.querySelector("#token2").innerText = data.token;
                
            });
        }
        
    </script>

    <style>
        code {
            color: blue;
            background-color: #f0f0f0; /* Light grey background */
            padding: 2px 4px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>