
<div class="w3-row w3-padding-64">
    <div class="w3-container w3-teal">
        <h1>Settings</h1>
    </div>

    <div class="w3-bar w3-black">
        <button class="w3-bar-item w3-button" onclick="openCity('Users')">Users</button>
        <button class="w3-bar-item w3-button" onclick="openCity('TimeZone')">TimeZone</button>
    </div>

    <div id="Users" class="w3-container city" style="display:none">
        <h2>Manage Users</h2>

        <?php include_once "users.php"?>
    </div>

    <div id="TimeZone" class="w3-container city" >
        <?php include_once "timezone.php" ?>
    </div>

</div>


<script>
    function openCity(cityName) {
        var i;
        var x = document.getElementsByClassName("city");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        document.getElementById(cityName).style.display = "block";
    }

</script>
