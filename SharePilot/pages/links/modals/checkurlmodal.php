<!-- check url Modal -->
<div id="checkUrlModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="closeModal('#checkUrlModal')" class="w3-button w3-display-topright">&times;</span>
            <h2 id="checkUrlModalTitle"></h2>
        </header>
        <div class="w3-container">
            <br>
            <img id="checkUrlModalImage" class="w3-image" alt="Image" style="width:50%;"/>
            <p id="checkUrlModalDescription"></p>
            <p id="checkUrlModalPostTime"></p>
        </div>
        <footer class="w3-container w3-teal w3-padding">            
            <input id="saveLink" class="w3-button w3-white w3-border w3-round-large" onclick="saveLink()" value="Save">
        </footer>
    </div>
</div>
<script>
    function saveLink(){
        fetch("links/addurl?format=raw", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(urlData),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.message) {
                alert(data.message);
                loadChannels();
                loadUrls();
                closeModal("#checkUrlModal");
                document.getElementById("txtUrl").value="";
            } else {
                console.error("Unexpected response data:", data);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>