<h1>Videos from youtube</h1>
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
<button id="btnNewCourse">Νέος Χρήστης</button>
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
            var getAllUrl = "youtube/getvideo?format=json"; //OK
            var rows = ["id", {name:"thumbnailUrl", type:"image", width:"150px"},"title", {name:"videoUrl", type:"url", alt:"url"},"publishedAt"];
            var getItemUrl= null; //OK
            var deleteUrl = "youtube/addvideo?format=json"; //OK
            var updateUrl = null; //OK
            var insertUrl = "youtube/addvideo?format=json"; //OK
            var deleteconfirmmsg = "Είστε σίγουρος ότι θέλετε να διαγράψετε το video?";
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
