<!-- check url Modal -->
<div id="addUrlModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="closeModal()" class="w3-button w3-display-topright">&times;</span>
            <h2 id="addUrlModalTitle">Add Url Form</h2>
            <p>Add a new url using this form</p>
        </header>
        <div class="w3-container">
            <br>
            <label for="txtUrl" class="w3-text-teal"><b>Url</b></label>
            <input class="w3-input w3-border w3-margin-bottom" type="text" name="txtUrl" id="txtUrl">
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button class="w3-button w3-white w3-border w3-round-large" type="button" id="checkUrl" name="checkUrl">Check </button>
            <button id="closeModal" class="w3-button w3-white w3-border w3-round-large" onclick="closeModal()">Close</button>            
        </footer>
    </div>
</div>

<script>
    document.querySelector('#checkUrl').addEventListener('click', () => {
        const url = document.querySelector('#txtUrl').value;
        if(!validateUrl(url)) {
            alert("Invalid URL");
            return;
        }
        const formData = new FormData();
        formData.append('url', url);
        fetch('links/fetchurl?format=raw', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                //Hide the current modal 
                document.querySelector('#addUrlModal').style.display='none'

                //Show the checkurl modal
                document.querySelector('#checkUrlModal').style.display = "block";

                //Set the values for checkurl modal
                document.querySelector('#checkUrlModalTitle').innerText = data.title;
                document.querySelector('#checkUrlModalDescription').innerText = data.description;
                document.querySelector('#checkUrlModalImage').src = data.image;
                document.querySelector('#checkUrlModalPostTime').innerText = data.postedtime ? 'Posted on: ' + data.postedtime : '';
                
                
                data.url =  url;
                urlData = data;
            });
    });

    function closeModal() {
        document.querySelector('#addUrlModal').style.display = "none";
    }
</script>
