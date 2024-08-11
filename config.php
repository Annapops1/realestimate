<?php
// Database connection settings
$servername = "localhost"; // Your server name (usually "localhost" for local development)
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (usually empty for XAMPP)
$dbname = "miniproj"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
