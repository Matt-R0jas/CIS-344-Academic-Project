<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// This reads project name and description 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projects_name = trim($_POST['project_name']);
    $description = trim($_POST['description']);
    // Inserts the project for the user
    if ($projects_name) {
        $stmt = $mysqli -> prepare("INSERT INTO projects (project_name, description, owner_id) VALUES (?, ?, ?)");
        $stmt ->bind_param('ssi', $projects_name, $description, $_SESSION['user_id']);
        if ($stmt -> execute()) {
            $project_id = $stmt -> insert_id;
            header("Location: dashboard.php");
            exit;
        }
        else {
            echo "Error creating project: " . htmlspecialchars($stmt -> error);
        }
        $stmt -> close();
    }
    else {
        echo "Project name is required.";
    }
}
?>

<!-- Create project HTML -->
<html>
    <head>
        <title>Create Project</title>
    </head>
    <body>
        <h2>Create New Project</h2>
        <form method="post">
            Project Name: <input name="project_name" required><br>
            Description: <textarea name="description" rows="4" cols="40" required></textarea><br>
            <button type="submit">Create</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </body>
</html>