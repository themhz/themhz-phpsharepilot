<!--row click popup-->
<div id="myModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('myModal').style.display='none'" class="w3-button w3-display-topright w3-large">&times;</span>
            <h2>Edit Item</h2>
        </header>
        <div class="w3-container">
            <input type="text" id="editId" style="display: none">
            <p>Title: <input id="editTitle" class="w3-input w3-border w3-margin-top" type="text"></p>
            <p>URL: <input id="editURL" class="w3-input w3-border w3-margin-top" type="text"></p>
            <p>Thumbnail URL: <input id="editThumbURL" class="w3-input w3-border w3-margin-top" type="text"></p>
            <p>
                Channel:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="editmodal_channels" onchange="loadListsModalBySelectedChannel()"></select>
            </p>
            <p>
                List:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="editmodal_lists"></select>
            </p>
        </div>
        <barter class="w3-container w3-teal w3-padding">
            <button onclick="submitChanges()" class="w3-button w3-white w3-border w3-round">Update</button>
        </barter>
    </div>
</div>
<!--popup-->
