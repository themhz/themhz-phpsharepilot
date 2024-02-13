<!-- check url Modal -->
<div id="checkUrlModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('checkUrlModal').style.display='none'"
                  class="w3-button w3-display-topright">&times;</span>
            <h2 id="checkUrlModalTitle"></h2>
        </header>
        <div class="w3-container">
            <br>
            <img id="checkUrlModalImage" class="w3-image" alt="Image" style="width:50%;"/>
            <p id="checkUrlModalDescription"></p>
            <p id="checkUrlModalPostTime"></p>
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button id="closeModal" class="w3-button w3-white w3-border w3-round-large" onclick="closeModal()">Close</button>
            <input id="saveLink" class="w3-button w3-white w3-border w3-round-large" onclick="saveLink()" value="Save">
        </footer>
    </div>
</div>
