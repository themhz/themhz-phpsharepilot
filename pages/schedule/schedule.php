<!--Lists-->
<div class="w3-row custom-padding-45">
    <div class="w3-container w3-teal">
        <h1>Scheduled Links</h1>
    </div>
    <br>
    <div class="w3-container">
        <div class="w3-border w3-padding-large w3-padding-64 w3-center">
            <div class="w3-container w3-card-4" >
                <h2 class="w3-text-teal">Schedule Controls</h2>
                <select class="w3-btn w3-teal w3-margin-bottom  w3-dropdown-hover" name="channels" id="channels" onchange="filterChannels()"></select>
                <button class="w3-btn w3-teal w3-margin-bottom" id="deleteautoscheduleposts">Delete {word} Schedule Posts</button>
                <button class="w3-btn w3-teal w3-margin-bottom" id="clearautoscheduleposts">Clear {word} Schedule Posts</button>
            </div>
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
                <h3 class="w3-text-teal">Interval hours between url posts</h3>
                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="hourInterval" id="hourInterval" value="">
                <h3 class="w3-text-teal">Avoid start hour</h3>
<!--                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="avoid_start_hour" id="avoid_start_hour" value="0">-->
                <select class="w3-input w3-border w3-margin-bottom" id="avoid_start_hour"></select>
                <h3 class="w3-text-teal">Avoid end hour</h3>
                <select class="w3-input w3-border w3-margin-bottom" id="avoid_end_hour"></select>
<!--                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="avoid_end_hour" id="avoid_end_hour" value="7">-->
                <button class="w3-btn w3-teal w3-margin-bottom" id="scheduleButton">Pull schedule posts</button>
                <button class="w3-btn w3-teal w3-margin-bottom" id="restatescheduleButton">Reset dates for schedule posts</button>
            </div>
        </div>
        <div class="w3-container w3-margin w3-margin w3-right">
            <div id="calendar-container">
                <!-- Calendar will be generated here -->
            </div>
            <ul id="tasks-list">
                <!-- Tasks will be added here -->
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
        <barter class="w3-container w3-teal w3-padding">
            <button id="closeModal" class="w3-button w3-white w3-border w3-round-large" onclick="closeModal()">Close</button>
            <input id="saveLink" class="w3-button w3-white w3-border w3-round-large" onclick="saveLink()" value="Save">
        </barter>
    </div>
</div>
<!--new url popup-->
<script>
    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete")
        {
            loadList();
            loadChannels();
            loadAvoidStartHoursAndEndHours();
        }
    }, false);
    function loadList(){
        channelId = document.getElementById("channels").value;
        let url = 'schedule/getscheduledlinks?format=raw';
        if (channelId !== null && channelId !== "") {
            url += '&channelid=' + encodeURIComponent(channelId);
        }
        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            }
            })
            .then(response => response.json())
            .then(data => {
                const ul = document.querySelector(".w3-ul");
                ul.innerHTML = '';
                ul.classList = "w3-ul w3-hoverable";
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = "w3-bar w3-hover-teal";
                    if(item.is_posted == 1){
                        li.className +=" w3-teal";
                    }
                    li.style.cursor = "pointer";
                    li.id = item.id;
                    li.innerHTML = `<span onclick="deletePost(${item.scheduled_id})" class="w3-bar-item w3-button w3-white w3-xlarge w3-right">×</span>
                                    <img src="${item.thumbnailUrl}" class="w3-bar-item w3-hide-small" style="width:150px">
                                    <div class="w3-bar-item" id=${item.scheduled_id}>
                                      <span class="w3-large">${item.title.substring(0, 80)}</span><br>
                                      <span>${item.regdate}</span>
                                      <!-- New elements: datetime text box and delete button -->
                                        <input id="post_time_${item.scheduled_id}" type="datetime-local" placeholder="Select date and time" class="w3-input w3-border" onclick="event.stopPropagation()" value="${item.post_time}">
                                        <button onclick="deletePost(${item.scheduled_id})" class="w3-button w3-red w3-margin-top">Delete</button>
                                        <button onclick="updateSchedulePost(${item.scheduled_id})" class="w3-button w3-blue w3-margin-top">Update Schedule</button>
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
        loadCalendar();
    }

    function deletePost(id){
        event.stopPropagation();
        if(confirm("are you sure you want to delete this item?")){
            const scheduled_id = id;

            fetch('schedule/delete?format=raw', {
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
                        alert("deleted successfully");
                    }else{
                        alert("problem with deletion");
                    }
                    loadList();
                });


            // $.ajax({
            //     type: "POST",
            //     url: "schedule/delete?format=raw",
            //     data: {
            //         id: scheduled_id,
            //     },
            //     success: (response) => {
            //         if(response.result == true){
            //             alert("deleted successfully");
            //         }else{
            //             alert("problem with deletion");
            //         }
            //         loadList();
            //     },
            //     error: () => {
            //         alert("An error occurred while scheduling the video.");
            //     },
            // });
        }
    }
    function updateSchedulePost(id){
        event.stopPropagation();
        var post_time = document.getElementById('post_time_'+id).value;

        fetch('schedule/updateschedulepost?format=raw', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id,
                post_time: post_time
            })
        })
            .then(response => response.json())
            .then(response => {
                if(response.result == true){
                    alert("updated successfully");
                }else{
                    alert("problem with update");
                }
                loadList();
            });
    }
    function loadChannels(){
        fetch('channels/loadchannels?format=raw')
            .then(response => response.json())
            .then(data => {
                createChannellist(data);
            })
    }
    function createChannellist(data){
        document.getElementById("channels").innerHTML =`<option value="">All</option>`;
        data.forEach(item => {
            document.getElementById("channels").innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });

        fixTexts();
    }
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
    document.getElementById("scheduleButton").addEventListener("click", async () => {
        let initial_schedule_post_date = document.getElementById(`initial_schedule_post_date`).value;
        let initial_schedule_post_time = document.getElementById(`initial_schedule_post_time`).value;
        let hourInterval = document.getElementById(`hourInterval`).value;
        let avoid_start_hour = document.getElementById(`avoid_start_hour`).value;
        let avoid_end_hour = document.getElementById(`avoid_end_hour`).value;
        if (initial_schedule_post_date.trim().length > 0 &&
            initial_schedule_post_time.trim().length > 0 &&
            hourInterval.trim().length > 0 &&
            avoid_start_hour.trim().length>0 &&
            avoid_end_hour.trim().length>0
            ) {
            try {
                var channelId = document.getElementById("channels").value;
                if(channelId == "") {
                    channelId = null;
                }
                let url = 'schedule/autoscheduleposts?format=raw';
                if (channelId !== null) {
                    url += '&channelid=' + encodeURIComponent(channelId);
                }
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ start_datetime: initial_schedule_post_date + ' ' +initial_schedule_post_time , hourInterval:hourInterval, channelId:channelId, avoid_start_hour:avoid_start_hour,avoid_end_hour:avoid_end_hour })
                });
                const data = await response.json();
                if (data.result == true) {
                    loadList();
                    alert("Urls has been scheduled successfully");
                } else {
                    alert(`Error scheduling urls`);
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
    document.getElementById("deleteautoscheduleposts").addEventListener("click", async () => {
        if(confirm("Are you sure you want to clear all schedules?")){
            try {
                var channelId = document.getElementById("channels").value;
                let url = 'schedule/deleteautoscheduleposts?format=raw';
                if (channelId !== null) {
                    url += '&channelid=' + encodeURIComponent(channelId);
                }
                const response = await fetch(url, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    },
                });
                const data = await response.json();
                //console.log(data.result);
                 if (data.result==true) {
                     alert("deleted successfully");
                     loadList();
                 } else {
                     alert("nothing to delete");
                 }
            } catch (error) {
                alert(`Error: ${error}`);
            }
        }
    });
    document.getElementById("clearautoscheduleposts").addEventListener("click", async () => {
        if(confirm("Are you sure you want to clear all schedules?")){
            try {
                var channelId = document.getElementById("channels").value;
                let url = 'schedule/clearautoscheduleposts?format=raw';
                if (channelId !== null) {
                    url += '&channelid=' + encodeURIComponent(channelId);
                }
                const response = await fetch(url, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    },
                });
                const data = await response.json();
                if (data.result) {
                    loadList();
                    alert("cleared");
                }
            } catch (error) {
                alert(`Error: ${error}`);
            }
        }
    });
    document.getElementById("restatescheduleButton").addEventListener("click", async () => {
        let initial_schedule_post_date = document.getElementById(`initial_schedule_post_date`).value;
        let initial_schedule_post_time = document.getElementById(`initial_schedule_post_time`).value;
        let hourInterval = document.getElementById(`hourInterval`).value;
        let avoid_start_hour = document.getElementById(`avoid_start_hour`).value;
        let avoid_end_hour = document.getElementById(`avoid_end_hour`).value;
        if (initial_schedule_post_date.trim().length > 0 &&
            initial_schedule_post_time.trim().length > 0 &&
            hourInterval.trim().length > 0 &&
            avoid_start_hour.trim().length>0 &&
            avoid_end_hour.trim().length>0) {
            try {
                const response = await fetch("schedule/restateschedule?format=raw", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ start_datetime: initial_schedule_post_date + ' ' +initial_schedule_post_time , hourInterval:hourInterval, avoid_start_hour:avoid_start_hour,avoid_end_hour:avoid_end_hour})
                });
                const data = await response.json();
                console.log(data);
                if (data.result==true) {
                    loadList();
                    alert("Urls have been scheduled");
                } else {
                    alert(`Error rescheduling urls`);
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
function filterChannels(){
    loadList();
    fixTexts();

}
function fixTexts(){

    document.getElementById("clearautoscheduleposts").innerText = "Clear {word} Schedule Posts";
    document.getElementById("deleteautoscheduleposts").innerText = "Delete {word} Schedule Posts";
    let channelName = document.getElementById("channels").options[document.getElementById("channels").selectedIndex].innerText;
    document.getElementById("clearautoscheduleposts").innerText = document.getElementById("clearautoscheduleposts").innerText.replace("{word}",channelName);
    document.getElementById("deleteautoscheduleposts").innerText = document.getElementById("deleteautoscheduleposts").innerText.replace("{word}",channelName);
}

function loadAvoidStartHoursAndEndHours(){
    for(let i=0;i<24;i++){
        document.getElementById("avoid_start_hour").innerHTML += `<option value="${i}">${i}</option>`;
        document.getElementById("avoid_end_hour").innerHTML += `<option value="${i}">${i}</option>`;
    }

    document.getElementById("avoid_start_hour").value = "0";
    document.getElementById("avoid_end_hour").value = "7";

}
</script>
<script src="template/js/calendar.js?v="<?php echo time(); ?>"></script>
