<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Loads user info for display
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$institution = $_SESSION["institution"];
$department = $_SESSION["department"];

// Get user's projects within the database
$stmt = $mysqli -> prepare("SELECT project_id, project_name FROM projects WHERE owner_id = ?");
$stmt -> bind_param("i", $_SESSION["user_id"]);
$stmt -> execute();
$result = $stmt -> get_result();
$projects = $result -> fetch_all(MYSQLI_ASSOC);
$stmt -> close();

// Displays user info and projects list
echo "<h2>Welcome, " . htmlspecialchars($first_name) . " " . htmlspecialchars($last_name) . "</h2>";
echo "Institution: " . htmlspecialchars($institution) . "<br>";
echo "Department: " . htmlspecialchars($department) . "<br>";
echo "<h3>Your Projects</h3><ul>";
foreach ($projects as $project) {
    echo "<li><a href=\"project.php?id=" . $project["project_id"] . "\">" . htmlspecialchars($project["project_name"]) . "</a></li>";
}
echo "</ul>";
?>

<!-- HTML code to display user info and project links -->
<a href="create_project.php">Create Project</a> | <a href="logout.php">logout</a>