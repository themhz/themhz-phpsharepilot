<?php
session_start();

// Redirect if installation wasn't successful
if (!isset($_SESSION["installation_success"]) || !$_SESSION["installation_success"]) {
    header("Location: install/install.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    // Delete installation files and folder only after successful installation
    array_map('unlink', glob("../install/*"));
    rmdir('../install');
   
    
    // Read .env file
    $env_lines = file('../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // Build the base URL dynamically
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];  // Get the host from the server variables
    $base_url = $protocol . '://' . $host;  // Concatenate to form the base URL

    // Redirect to the base URL
    header("Location: " . $base_url);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Installation Successful</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-teal">

<div class="w3-container w3-padding w3-card-4 w3-round-large w3-margin w3-white">
    <h2 class="w3-center">Installation Successful</h2>
    <p class="w3-center"><?php echo $_SESSION["installation_message"]; ?></p>

    <form class="w3-container w3-center" method="post">
        <button class="w3-btn w3-teal w3-margin-top">Delete Installation Folder and Go to Site</button>
    </form>
</div>

</body>
</html>
