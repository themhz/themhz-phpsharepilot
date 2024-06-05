<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Installation</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-teal">

<div class="w3-container w3-padding w3-card-4 w3-round-large w3-margin w3-white">
    <h2 class="w3-center">Installation</h2>

    <?php        
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $db_host = $_POST["db_host"];
        $db_name = $_POST["db_name"];
        $db_user = $_POST["db_user"];
        $db_pass = $_POST["db_pass"];
        //$base_url = $_POST["base_url"];
        $admin_user = $_POST["admin_user"];
        $admin_pass = $_POST["admin_pass"];

        $env_data = "DB_HOST=$db_host\nDB_NAME=$db_name\nDB_USER=$db_user\nDB_PASS=$db_pass";


        // Write to .env file
        file_put_contents('../.env', $env_data);
        try{
            // Connect to MySQL
            $connection = new mysqli($db_host, $db_user, $db_pass);
        }catch(Exception $error){
            print_r($error);
            echo "<br>---------<br>";
            echo "$db_host, $db_user, $db_pass";
            die();
        }
        

        
       
        // Drop the database if it exists
        $connection->query("DROP DATABASE IF EXISTS $db_name");

       
        // Create the database
        $connection->query("CREATE DATABASE $db_name");
       
        // Select the database
        $connection->select_db($db_name);

        // Load and execute SQL dump
        $sql_dump = file_get_contents('db_php.sql');

        if ($connection->multi_query($sql_dump)) {
            do {
                // Store first result set
                if ($result = $connection->store_result()) {
                    $result->free();
                }
                // If there are more results, prepare next result set
            } while ($connection->next_result());
        } else {
            echo "Error when executing SQL: " . $connection->error;
        }

        $connection->multi_query($sql_dump);
       
        // Wait for all queries to finish
        //while($connection->more_results() && $connection->next_result()) {}
        do {
            if ($result = $connection->store_result()) {
                $result->free();
            }
        } while ($connection->more_results() && $connection->next_result());
        

        // Create the admin user
        $hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);
        
        //$connection->query("INSERT INTO users (name, lastname, email, password, role, regdate) VALUES ('admin', 'admin', '$admin_user', '$hashed_pass', 1, now())");

        ////////////////
            // Assign values to variables
            $admin_name = 'admin';
            $admin_lastname = 'admin';
            $admin_email = $admin_user;  // Assuming $admin_user contains the email address
            $role = 1;

            // Prepare the statement
            $stmt = $connection->prepare("INSERT INTO users (name, lastname, email, password, role, regdate) VALUES (?, ?, ?, ?, ?, NOW())");

            // Bind parameters to the prepared statement
            $stmt->bind_param("ssssi", $admin_name, $admin_lastname, $admin_email, $hashed_pass, $role);

            // Execute the prepared statement
            $stmt->execute();

            // Check for errors
            if ($stmt->error) {
                echo "Error: " . $stmt->error;
            } else {
                    // If installation is successful
                if ($connection->affected_rows > 0) {
                    // Set session variables
                    $_SESSION["installation_success"] = true;
                    $_SESSION["installation_message"] = "Installation completed successfully!";

                    // Redirect to the next page
                    header("Location: install_complete.php");
                    exit();
                }

                $connection->close();
            }

           

        ///////////////
        if ($stmt->error) {
            echo "Error: " . $stmt->error;
        }
         // Close the statement
         $stmt->close();            
    }

    ?>

    <form class="w3-container" method="post">
        <label class="w3-text-teal"><b>DB Host</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="db_host" value="db">

        <label class="w3-text-teal"><b>DB Name</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="db_name" value="sharepilot">

        <label class="w3-text-teal"><b>DB User</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="db_user" value="root">

        <label class="w3-text-teal"><b>DB Password</b></label>
        <input class="w3-input w3-border w3-light-grey" type="password" name="db_pass" value="526996">

        <label class="w3-text-teal"><b>Admin User</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="admin_user" value="admin">

        <label class="w3-text-teal"><b>Admin Password</b></label>
        <input class="w3-input w3-border w3-light-grey" type="password" name="admin_pass" value="526996">

        <button class="w3-btn w3-teal w3-margin-top">Install</button>
    </form>

</div>

</body>
</html>
