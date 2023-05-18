<!--Lists-->
<div class="w3-row w3-padding-64">
    <div class="w3-container w3-teal">
        <h1>Links in database</h1>
    </div>
    <div class="w3-container">
        <h1 class="w3-text-teal">Heading1</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum
            dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
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