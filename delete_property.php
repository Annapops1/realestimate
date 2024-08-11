<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Check if property ID is provided
if (isset($_GET['property_id'])) {
    $property_id = $_GET['property_id'];

    // Prepare the SQL statement
    $sql = "DELETE FROM properties WHERE property_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ii", $property_id, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to a confirmation or listing page
        header("Location: my_properties.php?message=Property deleted successfully.");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("No property ID specified.");
}

$conn->close();
