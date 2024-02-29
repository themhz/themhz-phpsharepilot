<!--Lists-->
<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Channels</h1>
    </div>
    <div class="w3-container">
        <p>Create a new Channel.</p>
        <div class="w3-bar w3-teal">
            <button class="w3-button" onclick="document.getElementById('newChannelModal').style.display='block'">New Channel</button>
        </div>
    </div>
</div>
<div class="w3-row">
    <div class="w3-twothird" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px;">
            <h1 class="w3-text-teal">Channels</h1>
            <button id="sortButton" onclick="sortList()" class="w3-button w3-teal w3-large"><i class="fas fa-sort"></i> Sort</button>
        </div>
        <div class="w3-container">
            <ul class="w3-ul w3-card-4" id="channellist">
                <!-- List items will be appended here -->
            </ul>
        </div>
    </div>
</div>
<!--Lists-->
<!--row click popup-->
<div id="rowClickModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('rowClickModal').style.display='none'" class="w3-button w3-display-topright w3-large">&times;</span>
            <h2>Channel</h2>
        </header>
        <div class="w3-container">
            <p>Channel Name: <input id="txtChannelName" class="w3-input w3-border w3-margin-top" type="text"></p>
            <input type="text" id="txtChannelId" name="txtChannelId" value="" style="display: none">
        </div>
        <div class="w3-container">
            <div class="w3-bar">
                <span class="w3-bar-item">Social:</span>
                <select class="w3-select w3-bar-item w3-margin-bottom w3-dropdown-hover" type="text" id="selectedSocialId" name="selectedSocialId" value="" onchange="onChangeSelectedSocial()"></select>
                <input class="w3-bar-item w3-button w3-white w3-border w3-round" type="button" value="Add Key" onclick="addKey()">
            </div>
        </div>
        <div class="w3-container" id="keylist">
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button onclick="update()" class="w3-button w3-white w3-border w3-round">Update</button>
        </footer>
    </div>
</div>
<!--popup-->

<!--new Channel window-->
<div id="newChannelModal" class="w3-modal">
    <div class="w3-modal-content">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('newChannelModal').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h2>New Channel</h2>
        </header>
        <div class="w3-container w3-margin">
            <label for="channelName">Channel Name:</label>
            <input type="text" id="channelName" name="channelName" class="w3-input w3-border" value="">
            <p id="error-message" style="color: red; display: none;">Please enter a channel name!</p>
        </div>
        <footer class="w3-container w3-teal w3-padding">            
            <button class="w3-button w3-green" id="create-channel">Create Channel</button>            
        </footer>
    </div>
</div>
<!--new channel window-->
<script>
    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete")
        {
            loadChannels();
            loadSocials();
        }
    }, false);
    function loadChannels(){
        fetch('channels?format=json', {
            method: 'get',
            })
            .then(response => response.json())
            .then(data => {
                createlist(data);
            })
    }
    function loadSocials(){
        //txtSocialId
        fetch('channels/getsocials?format=json', {
            method: 'get',
        })
            .then(response => response.json())
            .then(data => {
                //createlist(data);
                createSosials(data);
            })
    }
    function createSosials(data){
        //console.log(data);
        for(var i=0;i<data.length;i++){
            document.getElementById("selectedSocialId").innerHTML +="<option value="+data[i].id+">"+ data[i].name+"</option>";
        }
    }
    function onChangeSelectedSocial(){
        fetch('channels/loadkeys?format=json',{
            method: 'post',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                channelId:document.getElementById("txtChannelId").value,
                socialId:document.getElementById("selectedSocialId").value,
            })
        })
        .then(response => response.json())
        .then(data => {
            loadkeys(data);
        });
    }
    function loadkeys(data){
        let keylist = document.getElementById("keylist");
        keylist.innerHTML ="";
        for(let i=0;i<data.length;i++){
            let element = `
                <div id="${data[i].id}" class="w3-bar">
                    <label class="w3-bar-item" for="textbox1">Name:</label>
                    <input class="w3-bar-item w3-input w3-border" type="text" id="textbox1" name="textbox1" value="${data[i].name}">
                    <label class="w3-bar-item" for="textbox2">Value:</label>
                    <input class="w3-bar-item w3-input w3-border" type="text" id="textbox2" name="textbox2" value="${data[i].value}">
                    <button class="w3-bar-item w3-button w3-white w3-border w3-round" onclick="deleteKey('${data[i].id}')">Delete</button>
                </div>`;
            keylist.insertAdjacentHTML('beforeend', element);
        }
    }
    function createlist(data){
        const ul = document.querySelector(".w3-ul");
        ul.innerHTML = '';
        ul.classList = "w3-ul w3-hoverable";
        data.forEach(item => {
            const li = document.createElement('li');
            li.className = "w3-bar w3-hover-teal";
            li.style.cursor = "pointer";
            li.id = item.id;
            li.innerHTML = `<span onclick="deleteChannel(${item.id})" class="w3-bar-item w3-button w3-white w3-xlarge w3-right">×</span>
                            <div class="w3-bar-item" id=${item.id}>
                                <span class="w3-large">${item.name}</span><br>
                                <span>${item.regdate}</span><br>
                                <button onclick="deleteChannel(${item.id})" class="w3-button w3-red w3-margin-top">Delete</button>
                            </div>
                                    `;
            li.addEventListener('click', function() {
                editChannelKeys(item)
            });
            ul.appendChild(li);
        });
    }
    function editChannelKeys(item){
        document.getElementById('txtChannelName').value = item.name;
        document.getElementById('txtChannelId').value = item.id;
        document.getElementById('rowClickModal').style.display = 'block';
        onChangeSelectedSocial();
    }
    function update() {
        fetch('channels?format=json', {
            method: 'put',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                    id:document.getElementById("txtChannelId").value,
                    name: document.getElementById("txtChannelName").value,
                    social_id:document.getElementById("selectedSocialId").value,
                    keylist : getKeyListFromPopUp()
                })
            })
            .then(response => response.json())
            .then(data => {
                loadChannels();
                //createlist(data);
            })

        document.getElementById('rowClickModal').style.display = 'none';
    }
    function deleteChannel(id){
        event.stopPropagation();
        if(confirm("are you sure you want to delete this channel?")){
            fetch('channels/delete?format=json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                        id: id
                    })
                })
                .then(response => response.json())
                .then(response => {
                    if(response.result == true){
                        alert("deleted successfully");
                    }else{
                        alert("problem with deletion");
                    }
                    // Hide modal after successful operation
                    document.getElementById('newChannelModal').style.display='none';
                    loadChannels();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });

        }
    }
    function closeModal() {
        document.querySelector('#modal').style.display = "none";
    }
    // Define your sort state outside the sort function
    let isAscending = true;
    function sortList() {
        var list, i, switching, b, shouldSwitch;
        list = document.getElementById("channellist");
        switching = true;
        while (switching) {
            switching = false;
            b = list.getElementsByClassName("w3-bar");
            for (i = 0; i < (b.length - 1); i++) {
                shouldSwitch = false;
                if (isAscending) {
                    if (b[i].innerText.toLowerCase() > b[i + 1].innerText.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else {
                    if (b[i].innerText.toLowerCase() < b[i + 1].innerText.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                b[i].parentNode.insertBefore(b[i + 1], b[i]);
                switching = true;
            }
        }
        // Toggle the sort state after sorting
        isAscending = !isAscending;
        // Change the sort icon after sorting
        let sortButton = document.getElementById("sortButton");
        if (isAscending) {
            sortButton.innerHTML = '<i class="fas fa-sort-up"></i> Sort';
        } else {
            sortButton.innerHTML = '<i class="fas fa-sort-down"></i> Sort';
        }
    }
    document.getElementById('create-channel').addEventListener('click', function(){
        document.getElementById('channelName').value = document.getElementById('channelName').value.trim();
        var channelName = document.getElementById('channelName').value;
        if(channelName === ""){
            document.getElementById('error-message').style.display = 'block';
        } else {
            document.getElementById('error-message').style.display = 'none';
            fetch('channels/addchannel?format=json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    channelName: channelName
                })
            })
                .then(response => response.json())
                .then(data => {
                    //console.log(data);
                    alert(data.message);
                    // Hide modal after successful operation
                    document.getElementById('newChannelModal').style.display='none';
                    loadChannels();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });
    let idCounter = 1;  // This will be used to give each pair a unique ID.
    function addKey() {
        let keylist = document.getElementById("keylist");
        let id = "pair_" + idCounter;  // Create a unique ID for this pair.
        idCounter++;  // Increment the counter for next time.
        let element = `
        <div id="${id}" class="w3-bar">
            <label class="w3-bar-item" for="textbox1">Name:</label>
            <input class="w3-bar-item w3-input w3-border" type="text" id="textbox1" name="textbox1">
            <label class="w3-bar-item" for="textbox2">Value:</label>
            <input class="w3-bar-item w3-input w3-border" type="text" id="textbox2" name="textbox2">
            <button class="w3-bar-item w3-button w3-white w3-border w3-round" onclick="deleteKey('${id}')">Delete</button>
        </div>`;
        keylist.insertAdjacentHTML('beforeend', element);
    }
    function deleteKey(id) {
        let element = document.getElementById(id);
        element.parentNode.removeChild(element);
    }
    function getKeyListFromPopUp(){
        let pairs = document.querySelectorAll('#keylist .w3-bar');
        // This array will hold all of the pairs' data.
        let data = [];
        // Iterate over each pair and get the values of the inputs.
        for (let i = 0; i < pairs.length; i++) {
            let inputs = pairs[i].getElementsByTagName('input');
            // Get the values of the inputs.
            let name = inputs[0].value.trim();
            let value = inputs[1].value.trim();
            if(name!="" && value!=""){
                data.push({ name: name, value: value });
            }
        }
        return data;
    }

    document.addEventListener("keydown", function(event) {
        // Check if the pressed key is the Escape key (keyCode 27)
        if (event.keyCode === 27) {
            // Close the popup by setting its display property to "none"            
            document.getElementById('newChannelModal').style.display = 'none';
            document.getElementById('rowClickModal').style.display = 'none';
        }
    });
</script>
