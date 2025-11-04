<?php
    //database info
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = " "; // enter password if needed
    $db_name = "arc_database";

    // connecting to database; if it fails it will display a message for the user
    $mysqli = new mysqli($db_host,
                         $db_user,
                         $db_pass, 
                         $db_name);
    
    if($mysqli -> connect_error) {
        die("Connection failed: " . $mysqli -> connect_error);
    }

    if(!$mysqli -> set_charset("utf8mb4")) {
        die("Error loading character set utf8mb4: " . $mysqli -> error);
    }


?>