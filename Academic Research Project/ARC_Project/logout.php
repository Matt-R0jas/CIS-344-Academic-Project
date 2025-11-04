<?php
session_start();

// Unset all session variables
$_Session = [];

// Destroy the session
session_destroy();

//Redirect to homepage
header("Location: index.php");
exit;
?>