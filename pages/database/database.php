<!--Lists-->
<div class="w3-row w3-padding-64">
    <div class="w3-container w3-teal">
        <h1>Links in database</h1>
    </div>
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
        <p class="w3-border w3-padding-large w3-padding-32 w3-center">AD</p>
        <p class="w3-border w3-padding-large w3-padding-64 w3-center">AD</p>
    </div>
</div>
<!--Lists-->

<!--popup-->
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
<!-- W3.CSS Modal -->
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
            <button id="saveLink" class="w3-button w3-white w3-border w3-round-large" onclick="saveLink()">Save</button>
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
                ul.classList = "w3-ul w3-hoverable";
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = "w3-bar w3-hover-blue";
                    li.style.cursor = "pointer";


                    li.innerHTML = `
          <span onclick="this.parentElement.style.display='none'" class="w3-bar-item w3-button w3-white w3-xlarge w3-right">×</span>
          <img src="${item.thumbnailUrl}" class="w3-bar-item w3-hide-small" style="width:150px">
          <div class="w3-bar-item">
              <span class="w3-large">${item.title.substring(0, 80)}</span><br>
              <span>${item.regdate}</span>
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

</script>


<script>
    var urlData = null;
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
                data.url =  document.querySelector('.url-text').value;
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
                alert(1);
                if (!response.ok) {
                    throw new Error("HTTP error " + response.status);
                }
                return response.json();
            })
            .then(data => {
                alert(2);
                if (data && data.message) {
                    alert(data.message);
                    loadTable();
                    closeModal();
                } else {
                    console.error("Unexpected response data:", data);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });


    }


</script>