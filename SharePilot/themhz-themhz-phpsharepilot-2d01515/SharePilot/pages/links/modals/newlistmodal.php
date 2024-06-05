<div id="newListModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
        <header class="w3-container w3-teal">
            <span onclick="closeModal('#newListModal')" class="w3-button w3-display-topright w3-large">&times;</span>
            <h2>New List</h2>
        </header>
        <div class="w3-container">
            <p>
                Channel:
                <select class="w3-select w3-dropdown-hover w3-white w3-border" name="option" id="dropDownChannelForNewList"></select>
            </p>
            <p>
                List:
                <input id="txtlist" class="w3-input w3-border w3-margin-top" type="text">
            </p>
        </div>
        <footer class="w3-container w3-teal w3-padding">
            <button onclick="addNewList()" class="w3-button w3-white w3-border w3-round">Add</button>
        </footer>
    </div>
</div>


<script>
    function addNewList(){        
        let channel_id = document.getElementById("dropDownChannelForNewList").value;
        let name = document.getElementById("txtlist").value;

        if(channel_id!=0){
            fetch('links/addnewlist?format=json', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ channel_id: channel_id, name: name})
            })
                .then(response => response.json())
                .then(data => {
                    if(data.result == true){
                        //alert(`List "${name}" added`);
                        loadLists();
                    }else{
                        alert(`List "${name}" was not added`);
                    }

                    document.getElementById('newListModal').style.display = 'none';
                });
        }else{
            alert("Please select a channel");
        }

    }

    function newList(){
        document.getElementById('newListModal').style.display = 'block';
    }

</script>
