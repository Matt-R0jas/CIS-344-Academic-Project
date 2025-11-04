<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$project_id = intval($_GET['id'] ?? 0);
if (!$project_id) {
    die("Project not specified.");
}

// Fetch project info, including owner details
$stmt = $mysqli->prepare(
    "SELECT p.project_name, p.description, u.first_name, u.last_name
     FROM projects p
     JOIN users u ON p.owner_id = u.user_id
     WHERE p.project_id = ?"
);
$stmt->bind_param('i', $project_id);
$stmt->execute();
$stmt->bind_result($project_name, $description, $owner_first, $owner_last);
$stmt->fetch();
$stmt->close();

if (!$project_name) {
    die("Project not found.");
}

echo "<h2>Project: " . htmlspecialchars($project_name) . "</h2>";
echo "<p><b>Description:</b> " . nl2br(htmlspecialchars($description)) . "</p>";
echo "<p><b>Created by:</b> " . htmlspecialchars($owner_first) . " " . htmlspecialchars($owner_last) . "</p>";

// Handles document upload and logs in database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $file = $_FILES['document'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);
        $filepath = 'uploads/' . uniqid() . '_' . $filename;
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $stmt = $mysqli->prepare("INSERT INTO documents (document_name, file_path, project_id, uploaded_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssii', $filename, $filepath, $project_id, $_SESSION['user_id']);
            if ($stmt->execute()) {
                echo "<p>File uploaded.</p>";
            } else {
                echo "<p>Error uploading file: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>File move failed.</p>";
        }
    } else {
        echo "<p>File upload error.</p>";
    }
}

// List documents for the project and showing who uploaded and when
$stmt = $mysqli->prepare(
    "SELECT d.document_id, d.document_name, d.file_path, d.uploaded_at, u.first_name, u.last_name
     FROM documents d
     JOIN users u ON d.uploaded_by = u.user_id
     WHERE d.project_id = ?
     ORDER BY d.uploaded_at DESC"
);
$stmt->bind_param('i', $project_id);
$stmt->execute();
$result = $stmt->get_result();
echo "<h3>Documents</h3>";

// Ensures file is stored and provides a download link
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($doc = $result->fetch_assoc()) {
        echo "<li>
            <a href='" . htmlspecialchars($doc['file_path']) . "' download>" . htmlspecialchars($doc['document_name']) . "</a>
            <br><small>
                Uploaded by " . htmlspecialchars($doc['first_name']) . " " . htmlspecialchars($doc['last_name']) .
                " on " . htmlspecialchars($doc['uploaded_at']) . "
            </small>
        </li>";
    }
    echo "</ul>";
} else {
    echo "<p>No documents uploaded yet.</p>";
}
$stmt->close();
?>

<!-- file upload and document list HTML -->
<form method="post" enctype="multipart/form-data">
  <label>Upload Document: <input type="file" name="document" required></label>
  <button type="submit">Upload</button>
</form>

<a href="dashboard.php">← Back to Dashboard</a>
