<!--Lists-->
<div class="w3-row w3-padding-64">
    <div class="w3-container w3-teal">
        <h1>Links in database</h1>
    </div>
    <br>
    <div class="w3-container">
        <div class="w3-container w3-card-4" action="/action_page.php">
            <h2 class="w3-text-teal">Add Url Form</h2>
            <p>Add a new url using this form</p>
            <p>
                <label for="txtUrl" class="w3-text-teal"><b>Url</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="txtUrl" id="txtUrl">
                <input class="w3-btn w3-teal" value="Check" type="button" id="checkUrl" name="checkUrl">
        </div>

    </div>
</div>

<div class="w3-row">
    <div class="w3-twothird w3-container">
        <h1 class="w3-text-teal">Links</h1>
        <ul class="w3-ul w3-card-4">

        </ul>
    </div>
    <div class="w3-third w3-container">
        <div class="w3-border w3-padding-large w3-padding-64 w3-center">
            <div class="w3-container w3-card-4" >
                <h2 class="w3-text-teal">Schedule Posts</h2>
                    <input class="w3-input w3-border w3-margin-bottom" type="date" name="initial_schedule_post_date" id="initial_schedule_post_date" value="">
                    <input class="w3-input w3-border w3-margin-bottom" type="time" name="initial_schedule_post_time" id="initial_schedule_post_time" value="">
                <h3 class="w3-text-teal">Interval</h3>
                    <input class="w3-input w3-border w3-margin-bottom"  type="text" name="hourInterval" id="hourInterval" value="">
                    <button class="w3-btn w3-teal w3-margin-bottom" id="scheduleButton">Schedule Posts</button>
            </div>
        </div>
        <div class="w3-border w3-padding-large w3-padding-32 w3-center">AD</div>
    </div>
</div>
<!--Lists-->

<!--row click popup-->
<div id="myModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="document.getElementById('myModal').style.display='none'" class="w3-button w3-display-topright w3-large">&times;</span>
            <h2>Edit Item</h2>
        </header>
        <div class="w3-container">
            <p>Title: <input id="editTitle" class="w3-input w3-border w3-margin-top" type="text"></p>
            <p>URL: <input id="editURL" class="w3-input w3-border w3-margin-top" type="text"></p>
            <p>Thumbnail URL: <input id="editThumbURL" class="w3-input w3-border w3-margin-top" type="text"></p>
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

<script>
    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete")
        {
            loadList();
        }
    }, false);

    function loadList(){
        fetch('database/getvideo?format=raw')
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

                    li.innerHTML = `<span onclick="deleteItem(${item.id})" class="w3-bar-item w3-button w3-white w3-xlarge w3-right">×</span>
                                    <img src="${item.thumbnailUrl}" class="w3-bar-item w3-hide-small" style="width:150px">
                                    <div class="w3-bar-item" id=${item.id}>
                                      <span class="w3-large">${item.title.substring(0, 80)}</span><br>
                                      <span>${item.regdate}</span>
                                      <!-- New elements: datetime text box and delete button -->
                                        <input type="datetime-local" placeholder="Select date and time" class="w3-input w3-border" onclick="event.stopPropagation()" value="${item.post_time}">
                                        <button onclick="deletePost(${item.id})" class="w3-button w3-red w3-margin-top">Delete</button>
                                        <button onclick="schedulePost(${item.id})" class="w3-button w3-blue w3-margin-top">Schedule</button>
                                    </div>
                                    `;
                    li.addEventListener('click', function() {
                        document.getElementById('editTitle').value = item.title;
                        document.getElementById('editURL').value = item.url;
                        document.getElementById('editThumbURL').value = item.thumbnailUrl;
                        document.getElementById('myModal').style.display = 'block';
                    });


                    ul.appendChild(li);
                });
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    function submitChanges() {
        // Access the input values using:
        // document.getElementById('editTitle').value
        // document.getElementById('editURL').value
        // document.getElementById('editThumbURL').value

        // Update the item in your data and on the page

        // Hide the modal
        document.getElementById('myModal').style.display = 'none';

        alert(1);
    }

    function deletePost(id){
        event.stopPropagation();
        const scheduled_id = id;
        $.ajax({
            type: "POST",
            url: "database/delete?format=raw",
            data: {
                id: scheduled_id,
            },
            success: (response) => {
                if(response.result == true){
                    alert("deleted successfully");
                }else{
                    alert("problem with deletion");
                }

                loadList();
            },
            error: () => {
                alert("An error occurred while scheduling the video.");
            },
        });

    }

    function schedulePost(id){
        event.stopPropagation();
        const scheduled_id = id;
        $.ajax({
            type: "POST",
            url: "database/schedulepost?format=raw",
            data: {
                id: scheduled_id,
            },
            success: (response) => {
                if(response.result == true){
                    alert("deleted successfully");
                }else{
                    alert("problem with deletion");
                }

                loadList();
            },
            error: () => {
                alert("An error occurred while scheduling the video.");
            },
        });
    }

</script>


<script>
    let urlData = null;
    document.querySelector('#checkUrl').addEventListener('click', () => {
        const url = document.querySelector('#txtUrl').value;

        if(!validateUrl(url)) {
            alert("Invalid URL");
            return;
        }

        const formData = new FormData();
        formData.append('url', url);

        fetch('database/fetchurl?format=raw', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                document.querySelector('#modalTitle').innerText = data.title;
                document.querySelector('#modalDescription').innerText = data.description;
                document.querySelector('#modalImage').src = data.image;
                document.querySelector('#modalPostTime').innerText = data.postedtime ? 'Posted on: ' + data.postedtime : '';
                document.querySelector('#modal').style.display = "block";
                data.url =  url;
                urlData = data;
            });
    });

    function closeModal() {
        document.querySelector('#modal').style.display = "none";
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

    function saveLink(){

        fetch("database/addurl?format=raw", {
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
                    loadList();
                    closeModal();
                } else {
                    console.error("Unexpected response data:", data);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });


    }

    document.getElementById("scheduleButton").addEventListener("click", async () => {
        let initial_schedule_post_date = document.getElementById(`initial_schedule_post_date`).value;
        let initial_schedule_post_time = document.getElementById(`initial_schedule_post_time`).value;
        let hourInterval = document.getElementById(`hourInterval`).value;
        if (initial_schedule_post_date.trim().length > 0 &&
            initial_schedule_post_time.trim().length > 0 &&
            hourInterval.trim().length > 0) {
            try {


                const response = await fetch("database/autoscheduleposts?format=raw", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ start_datetime: initial_schedule_post_date + ' ' +initial_schedule_post_time , hourInterval:hourInterval})
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message);
                } else {
                    alert(`Error: ${data.error}`);
                }
            } catch (error) {
                alert(`Error: ${error}`);
            }
        } else {
            // Variables do not have a non-empty value
            //console.log("One or more variables do not have a non-empty value.");
            alert("please fill the date time and the interval");
        }



    });


</script>