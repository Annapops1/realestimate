<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['property_id'])) {
    die(json_encode(['error' => 'Property ID not provided']));
}

$property_id = $_GET['property_id'];

// Database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch property and owner details
$sql = "SELECT p.*, u.username as owner_name, u.email as owner_email, u.phone as owner_phone 
        FROM properties p 
        JOIN users u ON p.user_id = u.user_id 
        WHERE p.property_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die(json_encode(['error' => 'Property not found']));
}

echo json_encode([
    'success' => true,
    'data' => [
        'place' => $data['place'],
        'district' => $data['district'],
        'state' => $data['state'],
        'size' => $data['size'],
        'price' => $data['price'],
        'property_type' => $data['property_type'],
        'owner_name' => $data['owner_name'],
        'owner_email' => $data['owner_email'],
        'owner_phone' => $data['owner_phone']
    ]
]);

$stmt->close();
$conn->close();
?>
