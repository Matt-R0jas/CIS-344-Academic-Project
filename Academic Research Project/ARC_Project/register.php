<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    // Get and trim user input from the form
    $username = trim($_POST['username']);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $institution = trim($_POST['institution']);
    $department = trim($_POST['department']);

    // Validate that required fields are not empty
    if ($username && $email && $password && $first_name && $last_name){
        // Securely hash passwords
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        // Prepare quary to prevent SQL injection
        $stmt = $mysqli -> prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, institution, department) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt -> bind_param("sssssss", $username, $email, $password_hash, $first_name, $last_name, $institution, $department);

        // Run query and check for errors
        if ($stmt -> execute()) {
            echo "Registration successful! <a href=\"login.php\">Login here</a>";
            exit;
        }
        else {
            echo "Registration failed: " . htmlspecialchars($stmt -> error);
        }
        $stmt -> close();
    }
    else {
        echo "Please fill all required fields.";
    }
}
?>
<!-- Register form HTML -->
<form method="post">
    Username: <input name="username" required><br>
    Email: <input name="email" type="email" required><br>
    Password: <input name="password" type="password" required><br>
    First Name: <input name="first_name" required><br>
    Last Name: <input name="last_name" required><br>
    Institution: <input name="institution"><br>
    Department: <input name="department"><br>
    <button type="submit">Register</button>
</form>