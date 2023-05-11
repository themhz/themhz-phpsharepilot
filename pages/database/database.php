<h1>Videos from youtube</h1>
<form>
    <label for="url" class="url-label">Url:</label><input class="url-text" type="text" value="" name="url" id="url">
    <input type="button" value="Add" class="url-button">
</form>

<div id="modal" style="display:none; border:1px solid black;position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:9999; background:white; padding:20px;width: 60%; height: 70%; overflow: auto;">
    <h2 id="modalTitle"></h2>
    <p id="modalDescription"></p>
    <img id="modalImage" alt="Image" width="50%"/>
    <p id="modalPostTime"></p>
    <button id="closeModal" onclick="closeModal()">Close</button>
    <button id="closeModal" onclick="saveLink()">Save</button>
</div>




<table id="table">
    <tbody id="tbody"></tbody>
</table>

<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span id="closepopup" class="close">X</span>
        <div class="modal-frame">
            <table>
                <tr>
                    <td>Όνομα μαθήματος</td>
                    <td><input type="text" value="" id="title"></td>
                </tr>
                <tr>
                    <td>Περιγραφή</td>
                    <td><textarea id="description" cols="15" rows="5"></textarea></td>
                </tr>
                <tr>
                    <td>Είδος</td>
                    <td><select id="courses_type"></select></td>
                </tr>
                <tr>
                    <td>Εξάμηνο</td>
                    <td><select id="semester">
                            <option value="1">Πρώτο</option>
                            <option value="2">Δεύτερο</option>
                            <option value="3">Τρίτο</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Καθηγητής</td>
                    <td><select id="users"></select></td>
                </tr>
                <tr>
                    <td>Ects</td>
                    <td><input type="text" value="" id="ects"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="save" onclick="tablehandler.save()"></td>
                </tr>
            </table>
        </div>
    </div>

</div>
<button id="btnNewCourse">Νέος Link</button>
<script>


    <!-- Add this to your existing <script> tag in your index.php file -->
    document.querySelectorAll('.add-video').forEach((button) => {
        button.addEventListener('click', () => {
            const video_id = button.dataset.videoId;
            const title = button.dataset.title;
            const video_url = button.dataset.videoUrl;
            const thumbnail_url = button.dataset.thumbnailUrl;
            const published_at = button.dataset.publishedAt;

            $.ajax({
                type: "POST",
                url: "actions/add_video.php",
                data: { video_id, title, video_url, thumbnail_url, published_at },
                success: (response) => {
                    alert(response);
                    location.reload();
                },
                error: () => {
                    alert("An error occurred while adding the video.");
                },
            });
        });
    });

    document.querySelectorAll('.delete-video').forEach((button) => {
        button.addEventListener('click', () => {
            const url = button.getAttribute('data-video-url');
            $.ajax({
                type: "POST",
                url: "actions/delete_video.php",
                data: {
                    url: url,
                },
                success: (response) => {
                    alert(response);
                    location.reload();
                },
                error: () => {
                    alert("An error occurred while deleting the video.");
                },
            });
        });
    });



    //Το load της σελίδας για να φορτώσει το ajax
    var actionType;
    var gid;
    var tablehandler;

    document.addEventListener('readystatechange', function(evt) {
        if(evt.target.readyState == "complete"){
            var tableid = "table";
            var getAllUrl = "database/getvideo?format=raw"; //OK
            var rows = ["id", {name:"thumbnailUrl", type:"image", width:"150px"},"title", {name:"url", type:"url", alt:"url"},"dateInserted","datePosted"];
            var getItemUrl= null; //OK
            var deleteUrl = null; //OK
            var updateUrl = null; //OK
            var insertUrl = null; //OK
            var insertUrl = null; //OK
            var deleteconfirmmsg = "Είστε σίγουρος ότι θέλετε να διαγράψετε το Link από την βάση?"; //OK
            var insertconfirmmsg = "Είστε σίγουρος ότι θέλετε να εισάγετε το video?";
            var updateconfirmmsg = "Είστε σίγουρος ότι θέλετε να ενημερώσετε το video?";
            var popupwindow = "myModal"; //OK
            var newbutton = "btnNewCourse"; //OK
            var closepopupbutton = "closepopup";//ΟΚ

            var clickrowForPopup = function(){
                var fields = [{id: "title", type: "textbox", required : true}
                    ,{id: "description", type: "textbox", required : true}
                    ,{id: "courses_type", type: "select", required : true}
                    ,{id: "semester", type: "select", required : true}
                    ,{id: "ects", type: "textbox", required : true}
                    ,{id: "users", type: "select", required : false}
                ];
                formValidator =  new FrormValidator(fields);

                return formValidator.validate();
            };
            // tablehandler = new TableHandler(tableid, getAllUrl, rows, getItemUrl, deleteUrl, updateUrl, insertUrl, deleteconfirmmsg, popupwindow, newbutton, closepopupbutton, clickrowForPopup);
            tablehandler = new TableHandler();
            tablehandler.tableid=tableid; //Το id του πίνακα που θα τοποθετηθούν τα δεδομένα
            tablehandler.getAllUrl=getAllUrl; //Το url που θα χρησιμοποιηθεί για να φορτοθούν τα δεδομένα από το endpoint της php
            tablehandler.rows = rows; //Τα δεδομένα
            tablehandler.getItemUrl = getItemUrl; //Όταν γίνει κλικ σε ένα δεδομένο πάνω στον πίνακα τότε καλείτε αλλη μια φορά ένα url για να φορτώσει τα δεδομένα για το συγκεκριμένο item. Στο μέλλον μπορεί να αντικατασταθεί αυτό με ένα μονομιάς φόρτομα. Δηλαδή να προφορτόνονται όλα τα πιθανά δεδομένα σε μια μνήμη cache.
            tablehandler.deleteUrl = deleteUrl; //To url της διαγραφής
            tablehandler.updateUrl = updateUrl; //Το url της ενημέρωσης
            tablehandler.insertUrl = insertUrl; //To url της εισαγωγής
            tablehandler.deleteconfirmmsg = deleteconfirmmsg; //Το μήνυμα προϊδοποίησης διαγραφής
            tablehandler.insertconfirmmsg = insertconfirmmsg; //Το μήνυμα προϊδοποίησης εισαγωγής
            tablehandler.updateconfirmmsg = updateconfirmmsg; //Το μήνυμα προϊδοποίησης ενημέρωσης
            tablehandler.popupwindow = popupwindow;//το id του popup
            tablehandler.newbutton = newbutton;//το id του new κουμπιού
            tablehandler.closepopupbutton = closepopupbutton;
            tablehandler.clicksaveForPopup = null;
            tablehandler.onOpenPopup = clickrowForPopup;
            tablehandler.loadtable();

        }
    }, false);
</script>


<script>
    document.querySelector('.url-button').addEventListener('click', () => {
        const url = document.querySelector('.url-text').value;

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
        alert("Saving link needs to be done");
    }


</script>


