<!--Lists-->
<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Links</h1>
    </div>
    <div class="w3-container w3-margin-top">        
        <div class="w3-bar w3-teal">
            <div class="w3-dropdown-hover w3-teal">
                <div class="w3-container w3-teal">
                    <button class="w3-button" onclick="openNewLinkPopup()">Add Url</button>
                </div>
            </div>
            <div class="w3-dropdown-hover w3-teal">
                <div class="w3-container w3-teal">
                    <select class="w3-select w3-teal w3-dropdown-hover" name="channels" id="channels" onchange="filterChannels()"></select>
                </div>
            </div>
            <div class="w3-dropdown-hover w3-teal">
                <div class="w3-container w3-teal">
                    <select class="w3-select w3-teal w3-dropdown-hover" name="lists" id="lists" onchange="filterLists()"></select>
                </div>
            </div>
        </div>
    </div>
    <div class="w3-container">
        <p>Hover over the "Select Channel" to select the links of a Channel. Click on "New Channel" to create a new one.</p>
        <div class="w3-container w3-card-4" >
            <h2 class="w3-text-teal">Search links</h2>
            <p>Search links from database</p>
            <p>
                <label for="txtUrl" class="w3-text-teal"><b>search</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="txtsearch" id="txtsearch">
                <input class="w3-btn w3-teal" value="search" type="button" id="btnsearch" name="btnsearch">
            </p>
        </div>
    </div>
    
</div>
<div class="w3-row">
    <div class="w3-twothird" style="padding: 0;">    
    <?php include "urls.php" //List of the urls?>
    </div>    
</div>
<?php include "modals/addurlmodal.php" //when I click to add the url from the menu?>
<?php include "modals/checkurlmodal.php" //when I click to check if the url exists from the add url modal?>
<?php include "modals/editurlmodal.php" //when you click on a url to edit it this will popup?>
<?php //include "modals/newlistmodal.php" //To be used?>


<script>
    //Loads the urls on the main page
    function loadUrls() {
        let channelId = document.getElementById("channels").value;
        let listId = document.getElementById("lists").value;

        let url = 'links/getlinks?format=json';
        if (channelId != "0" && channelId!="") {
            url += '&channelid=' + encodeURIComponent(channelId);
        }

        if (listId != "0" && listId!="") {
            url += '&listid=' + encodeURIComponent(listId);
        }

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
        }).then(response => response.json())
        .then(data => {
            createUrlDivs(data);
        });
    }
    //Loads the channels on the main page
    function loadChannels(){
      
        fetch('channels/loadchannels?format=json')
            .then(response => response.json())
            .then(data => {
                createChannellist(data);
            });
    }
    //Loads the lists on the main page if channels are selected
    function loadLists(){        
        let channel_id = document.getElementById("channels").value;
        if(!(channel_id == "0" || channel_id == "")){
            fetch(`links/loadlists?format=json&channel_id=${channel_id}`)
                .then(response => response.json())
                .then(data => {
                    createList(data);
                });
        }
    }

   
    function filterChannels(){
        loadUrls();
        loadLists();
    }

    function filterLists(){
        loadUrls();
    }

    //This function creates the div items of the links in the list
    function createUrlDivs(data){        
        const ul = document.querySelector(".w3-ul");
        ul.innerHTML = '';
        ul.classList = "w3-ul w3-hoverable";
        data.forEach(item => {
            const li = document.createElement('li');
            li.className = "w3-bar w3-hover-teal";
            li.style.cursor = "pointer";
            li.id = item.id;
            li.innerHTML = `<span onclick="deletePost(${item.id})" class="w3-bar-item w3-button w3-white w3-xlarge w3-right">×</span>
                            <img src="${item.thumbnailUrl}" class="w3-bar-item w3-hide-small" style="width:150px">
                            <div class="w3-bar-item" id=${item.id}>
                                <span class="w3-large">${item.title.substring(0, 80)}</span><br>
                                <span>${item.regdate}</span><br>
                                <span style="color:blue">Channel: ${item.channel_name == null ? "no channel" : item.channel_name }</span><br>
                                <span style="color:blue">List: ${item.list_name == null ? "no list" : item.list_name }</span><br>
                                <button onclick="deletePost(${item.id})" class="w3-button w3-red w3-margin-top">Delete</button>
                                <button onclick="schedulePost(${item.id})" class="w3-button w3-blue w3-margin-top">Add to Schedule</button>
                            </div>
                                    `;
            li.addEventListener('click', function() {
                document.getElementById('editId').value = item.id;
                document.getElementById('editTitle').value = item.title;
                document.getElementById('editDescription').value = item.description;
                
                document.getElementById('editURL').value = item.url;
                document.getElementById('editThumbURL').value = item.thumbnailUrl;                
                document.getElementById('editThumbURLImage').src = item.thumbnailUrl;
                document.getElementById('editUrlModal').style.display = 'block';
                document.getElementById('editmodal_channels').value = item.channel_id;
                if(item.channel_id!=null){
                    loadListsModal(item);
                }else{
                    document.getElementById("editmodal_lists").innerHTML ="";
                }
            });
            ul.appendChild(li);
        });
    }

    
    //Load the logical lists of links to the url modal
    function loadListsModal(item){

        if(!(item.channel_id == "0" || item.channel_id == "" || item.channel_id == null)){
            fetch(`links/loadlists?format=json&channel_id=${item.channel_id}`)
                .then(response => response.json())
                .then(data => {
                    createListListsModal(data);
                    document.getElementById('editmodal_lists').value = item.list_id;
            });
        }else{
            document.getElementById("editmodal_lists").innerHTML ="";
        }
    }

    
    //Creates the list in the menu
    function createList(data){
        document.getElementById("lists").innerHTML =`<option value="0">Select List</option>`;
        document.getElementById("editmodal_lists").innerHTML ="";

        data.forEach(item => {
            document.getElementById("lists").innerHTML += `<option value="${item.id}">${item.name}</option>`;
            document.getElementById("editmodal_lists").innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    }
    
    //Creates the channels in the menu
    function createChannellist(data){

        document.getElementById("channels").innerHTML =`<option value="0">Select Channel</option>`;
        document.getElementById("editmodal_channels").innerHTML =`<option value="0">Select Channel</option>`;
        document.getElementById("checkUrl_channels").innerHTML =`<option value="0">Select Channel</option>`;
                
        data.forEach(item => {
            document.getElementById("channels").innerHTML += `<option value="${item.id}">${item.name}</option>`;
            document.getElementById("editmodal_channels").innerHTML += `<option value="${item.id}">${item.name}</option>`;            
            document.getElementById("checkUrl_channels").innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    }
    
    function deletePost(id){
        event.stopPropagation();
        if(confirm("are you sure you want to delete this post?")){
            let scheduled_id = id;
            fetch('links/delete?format=json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: scheduled_id
                })
            }).then(response => response.json())
                .then(data => {
                    if(data.result == true){
                        alert("deleted successfully");
                    }else{
                        alert("problem with deletion");
                    }
                    loadUrls();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    }
    function schedulePost(id){
        event.stopPropagation();
        let scheduled_id = id;
        fetch('links/schedulepost?format=json', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: scheduled_id
                })
             })
            .then(response => response.json())
            .then(response => {
                if(response.result == true){
                    alert("link added to scheduler successfully");
                }else{
                    alert("problem with added link to scheduler");
                }
                loadUrls();
            });

    }
    function search(){
        const search = document.querySelector('#txtsearch').value;
        if(search.trim()=="") {
            loadUrls();
            return;
        }
        fetch('links/search?format=json', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ search: search})
             })
            .then(response => response.json())
            .then(data => {
                //console.log(data);
                createUrlDivs(data);
            });
    }
    
    function validateUrl(value) {
        var url;
        try {
            url = new URL(value);
        } catch (_) {
            return false;
        }
        return url.protocol === "http:" || url.protocol === "https:";
    }    
    let isAscending = true;
    function sortUrls() {
        var list, i, switching, b, shouldSwitch;
        list = document.getElementById("urls");
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

    function openNewLinkPopup(){        
        document.getElementById('addUrlModal').style.display = 'block';        
    }

    function closeModal(modalName) {
        document.querySelector(modalName).style.display = "none";
    }

    

    //Events
    let urlData = null;
    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete")
        {
            loadChannels();
            loadLists();
            loadUrls();                                    
        }
    }, false);
    
    document.querySelector('#txtsearch').addEventListener('keydown', function(event) {
        // The keyCode for the Enter key is 13
        if (event.keyCode === 13) {
            event.preventDefault();
            search();
        }
    });
    document.querySelector('#btnsearch').addEventListener('click', () => {
        search();
    });
    
    // Add an event listener to the document object
    document.addEventListener("keydown", function(event) {
        // Check if the pressed key is the Escape key (keyCode 27)        
        if (event.keyCode === 27) {
            // Close the popup by setting its display property to "none"            
            closeModal('#addUrlModal');
            closeModal('#checkUrlModal');
            closeModal('#editUrlModal');
            closeModal('#newListModal');            
        }
    });
</script>
