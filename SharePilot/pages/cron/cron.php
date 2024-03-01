    <div class="w3-row custom-padding-45">
        <div class="w3-container w3-teal">
            <h1>Cron Page</h1>
        </div>
        <div class="w3-container">
            <div class="w3-panel w3-leftbar">
                <p><i class="fa fa-quote-right w3-xxlarge"></i><br>
                    <i class="w3-serif w3-xlarge">Cron Page</i>                   
                </p>
            </div>
        </div>
    </div>
    <div class="w3-row">        
            <div class="w3-container">                
                HELLO
                <input class="w3-btn w3-teal" value="Execute Cron" type="button" id="btnsearch" name="btnsearch" onclick="executeCron()">
            </div>        
    </div>

    <script>

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
        
    </script>