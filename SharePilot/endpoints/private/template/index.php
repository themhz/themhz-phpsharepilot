<?php
namespace SharePilotV2\Components;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SharePilot</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


<link rel="stylesheet" href="endpoints/private/template/css/login.css">
<link rel="stylesheet" href="endpoints/private/template/css/style.css">


<script src="endpoints/private/template/js/main.js?v=<?php echo time(); ?>"></script>

</head>
<body>
<?php

if (isset($_SESSION["user"])){ ?>
<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-theme w3-top w3-left-align w3-large" style="display: flex; justify-content: flex-end;">
    <a class="w3-bar-item w3-button w3-right w3-hide-large w3-hover-white w3-large w3-theme-l1" href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
    
    <a class="w3-bar-item w3-button w3-hover-white custom-margin-top-45" href="help">Help</a>
    <span class="w3-bar-item w3-padding custom-margin-top-45">
        <span id="clockMain"></span>&nbsp;&nbsp;&nbsp;
        Welcome
        <?php
             echo $_SESSION["user"]["name"]. " " . $_SESSION["user"]["lastname"];
        ?>
    </span>
    <a class="w3-bar-item w3-button w3-hover-white custom-margin-top-45" href="login/logout?format=json">Exit</a>
  </div>
</div>

<!-- Sidebar -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-animate-left" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hover-teal w3-hide-large" title="Close Menu">
    <i class="fa fa-remove"></i>
  </a>
  <h4 class="w3-bar-item"><b>Menu</b></h4>
    <a class="w3-bar-item w3-button w3-hover-teal" href="services">Services</a>    
    <a class="w3-bar-item w3-button w3-hover-teal" href="channels">Channels</a>
    <a class="w3-bar-item w3-button w3-hover-teal" href="lists">Lists</a>
    <a class="w3-bar-item w3-button w3-hover-teal" href="links">Links</a>            
    <a class="w3-bar-item w3-button w3-hover-teal" href="schedule">Scheduled Posts</a>
    <a class="w3-bar-item w3-button w3-hover-teal" href="cron">Cron</a>
    <a class="w3-bar-item w3-button w3-hover-teal" href="pushnotifications">Push Notifications</a>
    <a class="w3-bar-item w3-button w3-hover-teal" href="settings">Settings</a>    
    <a class="w3-bar-item w3-button w3-hover-teal" href="updates">Updates</a>
    <a class="w3-bar-item w3-button w3-hover-teal" href="help">Help</a>

</nav>
<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
<!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
<div class="w3-main" style="margin-left:250px">
<?php } else { ?>
    <!-- Overlay effect when opening sidebar on small screens -->
    <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
    <!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
    <div class="w3-main">
    <?php }?>
    <?php
    if (file_exists($content)) {
        include $content;
    }
    ?>
    <!-- END MAIN -->
</div>
<script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");
    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");
    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }
    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
    document.addEventListener('DOMContentLoaded', (event) => {
        // Get all link elements
        var navLinks = document.querySelectorAll('.w3-bar-item.w3-button');
        // Loop over the link elements
        navLinks.forEach((link) => {
            // Check if the href of the link matches the current URL
            if (window.location.href.indexOf(link.href) > -1) {
                // If the link's href matches the current URL, add the active class to it
                link.classList.add('w3-teal');
            }
        });
    });
    <?php $timezone2 = new TimeZone();
        $dbTimeZone2 = $timezone2->GetTimeZoneFromDb();
        $timezone2->SetCurrentTimeZone($dbTimeZone2["timezone"]);
    ?>
    let serverTimezone2 = "<?php echo $timezone2->GetCurrentTimeZone() ?>";
    const displayServerTimeMain = () => {
        const now2 = new Date();
        const options2 = { timeZone: serverTimezone2, hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const formatter2 = new Intl.DateTimeFormat('en-US', options2);
        document.getElementById('clockMain').textContent = formatter2.format(now2);
        setTimeout(displayServerTimeMain, 1000);
    }

    displayServerTimeMain();
</script>
</body>
</html>
