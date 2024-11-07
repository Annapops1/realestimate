<?php
// fetch_locations.php
header('Content-Type: application/json');
session_start();

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch locations
$sql = "SELECT state, district, place FROM locations";
$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[$row['state']][$row['district']][] = $row['place'];
    }
}

$conn->close();
echo json_encode($locations);
?>
