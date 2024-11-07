<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update user status to inactive
$sql = "UPDATE users SET is_active = 0 WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Optionally, you can log the user out after deletion
    session_destroy();
    header("Location: index.php"); // Redirect to a goodbye page or login page
} else {
    echo "Error updating record: " . $conn->error;
}

$stmt->close();
$conn->close();
?> 