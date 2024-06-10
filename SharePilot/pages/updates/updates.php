<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Updates</h1>
    </div>
    <div class="w3-container">
        <div class="w3-panel w3-leftbar">
            <p><i class="fa fa-quote-right w3-xxlarge"></i><br>
                <i class="w3-serif w3-xlarge">Updates

                </i>
            </p>
        </div>
    </div>
</div>
<div class="w3-row">
    <div class="w3-twothird" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px;">
            <h1 class="w3-text-teal">Updates</h1>
            <button id="sortButton" class="w3-button w3-teal w3-large"><i class="fas fa-sort"></i> Sort</button>
            
        </div>
        <div class="w3-container">
            <button onclick="CheckUpdate()" class="w3-button w3-teal w3-large">Check Update</button>
            <button onclick="DownloadUpdate()" class="w3-button w3-teal w3-large">Download Update</button>
            <button onclick="Update()" class="w3-button w3-teal w3-large">Update</button>
        </div>               
    </div>    
</div>
<script>
    document.addEventListener('readystatechange', function(evt) {        
        // if(evt.target.readyState == "complete")
        // {           
        //     getKeys();            
        // }
    }, false);
    
    function DownloadUpdate() {
        fetch('updates?method=downloadupdate&format=json', {
            method: "POST"
        })
        .then(response => response.json())
        .then(data => {            
            if (data.result === false) {
                console.log(data.message); // This will log "no update available"
            }else{
                Update();
            }
        })       
        .catch(error => {
            console.log('There was a problem with the fetch operation:', error);
        });
    }



    function CheckUpdate() {
        fetch('updates?method=checkupdate&format=json', {
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

    function Update() {
      
        fetch('updates?method=update&format=json', {
            method: "POST"
        })
        .then(response => response.json())        
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.log('There was a problem with the fetch operation:', error);
        });
    }

</script>
