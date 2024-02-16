<style>
    .delete-button {
        background-color: teal;
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 2px 2px;
    }

    .user-row {
        cursor: pointer;
    }
    .user-row:hover {
        background-color: #f5f5f5;
    }
    #userModal form input, #userModal form select {
        margin: 10px 0;
    }

</style>
<div class="w3-container">
    <button onclick="addUser()" class="w3-button w3-teal w3-large">New User</button>


    <table class="w3-table-all w3-centered" id="userTable">
        <thead>
        <tr class="w3-teal">
            <th>Name</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>MobilePhone</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div id="userModal" class="w3-modal">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom">
            <header class="w3-container w3-teal">
                    <span onclick="hideModal()"
                          class="w3-button w3-teal w3-xlarge w3-display-topright">&times;</span>
                <h2>User Form</h2>
            </header>

            <div class="w3-container">
                <form id="userForm">
                    <input name="id" id="id" style="display:none ;">
                    <input class="w3-input w3-border" type="text" placeholder="name" required name="name" id="name">
                    <input class="w3-input w3-border" type="text" placeholder="lastname" required name="lastname" id="lastname">
                    <input class="w3-input w3-border" type="email" placeholder="email" required name="email" id="email">
                    <input class="w3-input w3-border" type="password" placeholder="password" required name="password" id="password">
                    <input class="w3-input w3-border" type="tel" placeholder="mobilephone" required name="mobilephone" id="mobilephone">
                    <button onclick="handleRegister(event)" class="w3-button w3-block w3-teal w3-section w3-padding" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        loadTableData();
    };

    // Get the modal
    var modal = document.getElementById('userModal');
    var isEdit = 0;
    // Function to show modal
    function showModal(isedit) {
        modal.style.display = "block";
        isEdit = isedit;

        if(isedit==1){
            document.getElementById('password').removeAttribute('required');
        }else{
            document.getElementById('password').setAttribute('required', '');

        }
    }

    // Function to hide modal
    function hideModal() {
        modal.style.display = "none";
    }

    function addUser(){
        document.getElementById("id").value="";
        document.getElementById("name").value="";
        document.getElementById("lastname").value="";
        document.getElementById("email").value="";
        document.getElementById("password").value="";
        document.getElementById("mobilephone").value="";
        showModal(0);
    }
    function loadTableData() {
        // Fetch data from your endpoint
        fetch('settings?method=getusers&format=json')
            .then(response => response.json())
            .then(data => {
                // If the request was successful, populate the table with the data
                var tableBody = document.getElementById('userTable').querySelector('tbody');
                tableBody.innerHTML = ''; // Clear the table body

                // Loop through the data and create a new row for each item
                for (var i = 0; i < data.length; i++) {
                    var row = `
                <tr class="user-row" onclick="handleEdit('${data[i].id}')">
                    <td>${data[i].name}</td>
                    <td>${data[i].lastname}</td>
                    <td>${data[i].email}</td>
                    <td>${data[i].mobilephone}</td>
                    <td><button onclick="handleDelete('${data[i].id}')" class="delete-button w3-button w3-teal">Delete</button></td>
                </tr>
            `;
                    tableBody.innerHTML += row;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
    // Handle form submission
    function handleRegister(event) {
        event.preventDefault(); // Prevent page reload

        // Get the form
        var form = document.getElementById('userModal').querySelector('form');

        // Check if form is valid
        if (form.checkValidity()) {
            // Fetch form data
            var formData = new FormData(form);

            // Create a JSON object of the form data
            var jsonObject = {};
            for (const [key, value] of formData.entries()) {
                jsonObject[key] = value.trim();
            }

            // AJAX call for create/update user
            var method = isEdit==0? "registeruser" : "updateuser";
            fetch(`settings?method=${method}&format=json`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(jsonObject),
            })
                .then(response => response.json())
                .then(data => {
                    if(data.result==false){
                        alert("The user with that email, already exists, please select anotherone");
                    }else{
                        if(isEdit==0){
                            alert("User was inserted successfully");
                        }else{
                            alert("User was updated successfully");
                        }
                    }
                    console.log('Success:', data);
                    hideModal(); // Hide the modal after successful operation
                    loadTableData();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }else {
            // If the form is invalid, display an error message
            form.reportValidity();
            //alert('Please fix the errors in the form');

        }
    }
    // Handle delete action
    function handleDelete(userId) {
        event.stopPropagation(); // Prevent event bubbling
        event.preventDefault(); // Prevent page reload
        if(confirm("are you sure you want to delete the user?")){
            // AJAX call for delete user
            fetch(`settings?method=deleteuser&format=json&id=${userId}`, {
                method: 'get',
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    loadTableData();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }

    }
    // Handle edit action
    function handleEdit(userId) {
        // Fetch user data for the user with the specified ID
        fetch(`settings?method=getuserbyid&id=${userId}&format=json`)
            .then(response => response.json())
            .then(data => {
                // If the request was successful, populate the form with the user data
                var form = document.getElementById('userModal').querySelector('form');
                for (var key in data) {
                    if (form[key]) {
                        form[key].value = data[key];
                    }
                }
                document.getElementById("password").value="";
                // Show the modal
                showModal(1);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideModal();
        }
    });

</script>
