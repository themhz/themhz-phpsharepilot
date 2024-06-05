<!-- check url Modal -->
<div id="checkUrlModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="closeModal('#checkUrlModal')" class="w3-button w3-display-topright">&times;</span>
            <h2 id="checkUrlModalTitle"></h2>
        </header>
        <div class="w3-container">
            <br>
            <p>
                Title:
                <input type="textbox" class="w3-input w3-border w3-round-large" id="checkUrlModalTitleText" value="" onkeyup="onChangeTitle()">
            </p>
            <p> 
                Image preview:   
                <img id="checkUrlModalImage" class="w3-image" alt="Image" style="width:50%;"/>
            </p>
            <p>    
                Image URL:
                <input type="textbox" class="w3-input w3-border w3-round-large" id="checkUrlModalImageText" value="" onchange="onChangeImage()">
            </p>
                <p>
                    Description:
                    <textarea class="w3-input w3-border w3-round-large" value="" id="checkUrlModalDescription"></textarea>            
                </p>
            <p id="checkUrlModalPostTime"></p>
            <p>
                Channel:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="checkUrl_channels" onchange="loadCheckUrlModalBySelectedChannel()"></select>
            </p>
            <p>
                List:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="checkUrl_lists"></select>
            </p>
        </div>
        <footer class="w3-container w3-teal w3-padding">            
            <input id="saveLink" class="w3-button w3-white w3-border w3-round-large" onclick="saveLink()" value="Save">
        </footer>
    </div>
</div>
<script>
    function saveLink(){        
        urlData.channel_id = document.querySelector("#checkUrl_channels").value;
        urlData.list_id = document.querySelector("#checkUrl_lists").value;
        urlData.description = document.querySelector("#checkUrlModalDescription").value;
        urlData.image = document.querySelector("#checkUrlModalImageText").value;
        urlData.title = document.querySelector("#checkUrlModalTitleText").value;

        fetch("links/addurl?format=json", {
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


    function loadCheckUrlModalBySelectedChannel(){
        let selectedChannel = document.getElementById('checkUrl_channels').value;
        if(selectedChannel!="0"){
            fetch(`links/loadlists?format=json&channel_id=${selectedChannel}`)
                .then(response => response.json())
                .then(data => {
                    createCheckUrlListsModal(data);
                });
        }

    }

    function createCheckUrlListsModal(data){
        let c=false;
        document.getElementById("checkUrl_lists").innerHTML = "";
        data.forEach(item => {
            if(c==false){
                document.getElementById("checkUrl_lists").innerHTML =`<option value="0" selected>Select List</option>`;
                c=true;
            }
            document.getElementById("checkUrl_lists").innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    }

    function onChangeImage(){
        document.querySelector('#checkUrlModalImage').src = document.querySelector('#checkUrlModalImageText').value;
    }

    function onChangeTitle(){
        document.querySelector('#checkUrlModalTitle').innerText = document.querySelector('#checkUrlModalTitleText').value;
    }
</script>