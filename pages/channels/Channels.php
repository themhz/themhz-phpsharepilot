<!--Lists-->
<div class="w3-row w3-padding-64">
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
<div id="myModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('myModal').style.display='none'" class="w3-button w3-display-topright w3-large">&times;</span>
            <h2>New Channel A</h2>
        </header>
        <div class="w3-container">
            <p>Channel Name: <input id="selectedChannelName" class="w3-input w3-border w3-margin-top" type="text"></p>
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button onclick="submitChanges()" class="w3-button w3-white w3-border w3-round">Submit</button>
        </footer>
    </div>
</div>

<!--popup-->

<!--new url popup-->
<!-- check url Modal -->
<div id="modal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('modal').style.display='none'"
                  class="w3-button w3-display-topright">&times;</span>
            <h2 id="modalTitle"></h2>
        </header>
        <div class="w3-container">
            <br>
            <img id="modalImage" class="w3-image" alt="Image" style="width:50%;"/>
            <p id="modalDescription"></p>
            <p id="modalPostTime"></p>
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button id="closeModal" class="w3-button w3-white w3-border w3-round-large" onclick="closeModal()">Close</button>
            <input id="saveLink" class="w3-button w3-white w3-border w3-round-large" onclick="saveLink()" value="Save">
        </footer>
    </div>
</div>

<!--new url popup-->

<!--new Channel window-->
<div id="newChannelModal" class="w3-modal">
    <div class="w3-modal-content">
        <header class="w3-container w3-teal">
        <span onclick="document.getElementById('newChannelModal').style.display='none'"
              class="w3-button w3-display-topright">&times;</span>
            <h2>New Channel B</h2>
        </header>
        <div class="w3-container w3-margin">
            <label for="channelName">Channel Name:</label>
            <input type="text" id="channelName" name="channelName" class="w3-input w3-border" value="">
            <p id="error-message" style="color: red; display: none;">Please enter a channel name!</p>
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button class="w3-button w3-red" onclick="document.getElementById('newChannelModal').style.display='none'">Cancel</button>
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
        }
    }, false);


    function loadChannels(){
        fetch('channels?format=raw', {
            method: 'get',
            })
            .then(response => response.json())
            .then(data => {
                createlist(data);
            })
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
                document.getElementById('editTitle').value = item.name;
                document.getElementById('myModal').style.display = 'block';
            });


            ul.appendChild(li);
        });


    }


    function submitChanges() {
        document.getElementById('myModal').style.display = 'none';
    }

    function deleteChannel(id){
        event.stopPropagation();
        if(confirm("are you sure you want to delete this channel?")){
            $.ajax({
                type: "DELETE",
                url: `channels?format=raw&id=${id}`,
                success: (response) => {
                    if(response.result == true){
                        alert("deleted successfully");
                    }else{
                        alert("problem with deletion");
                    }

                    loadChannels();
                },
                error: () => {
                    alert("An error occurred while scheduling the video.");
                },
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
        alert(document.getElementById('channelName').value);
        var channelName = document.getElementById('channelName').value;
        if(channelName === ""){
            document.getElementById('error-message').style.display = 'block';
        } else {
            document.getElementById('error-message').style.display = 'none';

            fetch('database/addchannel?format=raw', {
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

</script>