<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    // This collects credentials and grabs the user record
    $stmt = $mysqli -> prepare("SELECT user_id, username, first_name, last_name, institution, department, password_hash FROM users WHERE email = ?");
    $stmt -> bind_param("s", $email);
    $stmt -> execute();
    $stmt -> bind_result($user_id, $username, $first_name, $last_name, $institution, $department, $password_hash);
    $stmt -> fetch();
    $stmt -> close();

    if (isset($password_hash) && password_verify($password, $password_hash)) {
        // Store login info in session for later access
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;
        $_SESSION["first_name"] = $first_name;
        $_SESSION["last_name"] = $last_name;
        $_SESSION["institution"] = $institution;
        $_SESSION["department"] = $department;
        header("Location: dashboard.php");
        exit;
    }
    else {
        echo "Login failed. Invalid email or password";
    }
}
?>

<!-- Login form HTML -->
<form method="post">
    Email: <input name="email" type="email" required><br>
    Password: <input name="password" type="password" required><br>
    <button type="submit">Login</button>
</form>