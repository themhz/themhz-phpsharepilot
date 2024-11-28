<!--Lists-->
<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Lists</h1>
    </div>
    <div class="w3-container">
        <p>Create a new List.</p>
        <div class="w3-bar w3-teal">
            <button class="w3-button" onclick="openNewListPopup()">New List</button>
            <div class="w3-dropdown-hover w3-teal">
                <div class="w3-container w3-teal">
                    <select class="w3-select w3-teal w3-dropdown-hover" name="channelsTopBar" id="channelsTopBar" onchange="filterChannels()"></select>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="w3-row">
    <div class="w3-twothird" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 24px;">
            <h1 class="w3-text-teal">Lists</h1>
            <button id="sortButton" onclick="sortList()" class="w3-button w3-teal w3-large"><i class="fas fa-sort"></i> Sort</button>
        </div>
        <div class="w3-container">
            <ul class="w3-ul w3-card-4" id="list">
                <!-- List items will be appended here -->
            </ul>
        </div>
    </div>
</div>
<!--Lists-->


<!--new List window-->
<div id="newListModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
        <span onclick="closeModal()" class="w3-button w3-display-topright">&times;</span>
            <h2>List Item</h2>
        </header>
        <div class="w3-container w3-margin">
            <input type="text" id="id" name="id" value="" style="display:none;">
            <label for="ListName">List Name:</label>
            <input type="text" id="ListName" name="ListName" class="w3-input w3-border" value="">
            <p id="error-message" style="color: red; display: none;">Please enter a List name!</p>

            <p>
                Channel:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="channels"></select>
            </p>
        </div>
        <footer class="w3-container w3-teal w3-padding">            
            <button class="w3-button w3-green" id="action_for_list" ></button>
        </footer>
    </div>
</div>
<!--new List window-->
<script>
    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete")
        {
            loadLists();
            loadChannels();
        }
    }, false);

    function loadLists(channel_id=null){
        let url ='lists/list?format=json';
        if(channel_id!=null){
            url+= "&channel_id="+channel_id;
        }
        fetch(url, {
            method: 'get',
            })
            .then(response => response.json())
            .then(data => {
                const ul = document.querySelector(".w3-ul");
                ul.innerHTML = '';
                ul.classList = "w3-ul w3-hoverable";
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = "w3-bar w3-hover-teal";
                    li.style.cursor = "pointer";
                    li.id = item.id;
                    li.innerHTML = `<span onclick="deleteList(${item.id})" class="w3-bar-item w3-button w3-white w3-xlarge w3-right">×</span>
                            <div class="w3-bar-item" id=${item.id}>
                                <span class="w3-large">${item.name}</span><br>
                                <span>${item.channelName}</span><br>
                                <button onclick="deleteList(${item.id})" class="w3-button w3-red w3-margin-top">Delete</button>
                            </div>`;
                    li.addEventListener('click', function () {
                        selectListItem(item)
                    });
                    ul.appendChild(li);
                });
            })
    }
    function loadChannels(){
        fetch('channels/loadchannels?format=json')
            .then(response => response.json())
            .then(data => {
                document.getElementById("channelsTopBar").innerHTML += `<option value="">All</option>`;
                data.forEach(item => {
                    document.getElementById("channels").innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    document.getElementById("channelsTopBar").innerHTML += `<option value="${item.id}">${item.name}</option>`;

                });
            });
    }
    function selectListItem(item){
        fetch(`lists/list?format=json&id=${item.id}`, {
            method: 'get',
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById("ListName").value =data.name;
                openUpdateListPopup(item);

            })
    }
    function deleteList(id){
        event.stopPropagation();
        if(confirm("are you sure you want to delete this List?")){

            fetch(`lists/delete?format=json&id=${id}`, {
                method: 'get',
            })
                .then(response => response.json())
                .then(data => {

                    if(data.result == true){
                        alert("deleted successfully");
                    }else{
                        alert("problem with deletion");
                    }
                loadLists();

            });
        }
    }

    // Define your sort state outside the sort function
    let isAscending = true;
    function sortList() {
        var list, i, switching, b, shouldSwitch;
        list = document.getElementById("list");
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
    function openModal(){
        document.getElementById('newListModal').style.display='block';
    }
    function closeModal(){
        document.getElementById('newListModal').style.display='none';
    }

    function AttachNewListEvent(){
        let element = document.getElementById("action_for_list");
        element.removeEventListener("click",CreateList);
        element.removeEventListener("click",updateList);
        element.addEventListener("click", CreateList);
    }

    function AttachUpdateListEvent() {
        let element = document.getElementById("action_for_list");
        element.removeEventListener("click",updateList);
        element.removeEventListener("click",CreateList);
        element.addEventListener("click",updateList);
    }

    function openNewListPopup(){
        document.getElementById("ListName").value="";
        document.getElementById("action_for_list").innerHTML = "Create List";
        AttachNewListEvent();
        openModal();
    }
    function openUpdateListPopup(item){
        document.getElementById("id").value=item.id;
        document.getElementById("ListName").value=item.name;
        document.getElementById("channels").value=item.channel_id;
        document.getElementById("action_for_list").innerHTML = "Update list";
        AttachUpdateListEvent();
        openModal();
    }
    function updateList(){
        //console.log("Updating list");
        let id = document.getElementById("id").value;
        let name = document.getElementById("ListName").value;
        let channel_id = document.getElementById("channels").value;

        fetch('lists/update?format=json', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id:id, name: name, channel_id: channel_id})
        })
            .then(response => response.json())
            .then(data => {
                if(data.result == true){
                    alert(`List "${name}" added`);
                    loadLists();
                }else{
                    alert(`List "${name}" was not added`);
                }

                closeModal();
            });
    }

    function CreateList(){
        //console.log("Creating new list");

        let name = document.getElementById("ListName").value;
        let channel_id = document.getElementById("channels").value;

        fetch('lists/add?format=json', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ channel_id: channel_id, name: name})
        })
            .then(response => response.json())
            .then(data => {
                if(data.result == true){
                    alert(`List "${name}" added`);
                    loadLists();
                }else{
                    alert(`List "${name}" was not added`);
                }
                document.getElementById('newListModal').style.display = 'none';
            });
    }
    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" || event.keyCode === 27) {
            // Fire the event you want to trigger here
            closeModal();
            // Replace the console.log statement with the desired event or function call
        }
    });

    function filterChannels(){
        let channel_id = document.getElementById("channelsTopBar").value;
        loadLists(channel_id);
    }

</script>
