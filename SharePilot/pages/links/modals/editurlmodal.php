<div id="editUrlModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="closeModal('#editUrlModal')" class="w3-button w3-display-topright w3-large">&times;</span>
            <h2>Edit Link</h2>
        </header>
        <div class="w3-container">            
            <input type="text" id="editId" style="display: none">
            <p>URL: <input id="editURL" class="w3-input w3-border w3-margin-top" type="text"></p>
            <p>Title: <input id="editTitle" class="w3-input w3-border w3-margin-top" type="text"></p>            
            <p> 
                Image preview:   
                <img id="editThumbURLImage" class="w3-image" alt="Image" style="width:50%;"/>
            </p>
            <p>Image URL: <input id="editThumbURL" class="w3-input w3-border w3-margin-top" type="text" onchange= "changeUrlImage()"></p>
            <p>
                Description:
                <textarea class="w3-input w3-border w3-round-large" value="" id="editDescription"></textarea>            
            </p>
            <p>
                Channel:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="editmodal_channels" onchange="loadListsModalBySelectedChannel()"></select>
            </p>
            <p>
                List:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="editmodal_lists"></select>
            </p>
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button onclick="submitChanges()" class="w3-button w3-white w3-border w3-round">Update</button>
        </footer>
    </div>
</div>

<script>
    function loadListsModalBySelectedChannel(){
        let selectedChannel = document.getElementById('editmodal_channels').value;
        if(selectedChannel!="0"){
            fetch(`links/loadlists?format=json&channel_id=${selectedChannel}`)
                .then(response => response.json())
                .then(data => {
                    createListListsModal(data);
                });
        }

    }

    function createListListsModal(data){
        let c=false;
        document.getElementById("editmodal_lists").innerHTML = "";
        data.forEach(item => {
            if(c==false){
                document.getElementById("editmodal_lists").innerHTML =`<option value="0" selected>Select List</option>`;
                c=true;
            }
            document.getElementById("editmodal_lists").innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    }

    function submitChanges() {
        fetch('links/update?format=json', {
            method: "POST", 
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id : document.getElementById('editId').value,
                title: document.getElementById('editTitle').value,
                description: document.getElementById('editDescription').value,
                url: document.getElementById('editURL').value,
                thumbnailUrl: document.getElementById('editThumbURL').value,
                channel_id: document.getElementById('editmodal_channels').value,
                list_id: document.getElementById('editmodal_lists').value,
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('editUrlModal').style.display = 'none';
            filterChannels();
        });
    }

    function changeUrlImage(){
        document.getElementById("editThumbURLImage").src=document.getElementById("editThumbURL").value;
    }
</script>