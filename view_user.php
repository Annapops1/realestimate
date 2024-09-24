<?php
// Start session
session_start();

// Include database connection file
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$user_id = "";

// Get user ID from URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    die("User ID not specified.");
}

// Prepare a select statement
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows == 1) {
    // Fetch user details
    $row = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0056b3;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>User Details</h1>
        <table>
            <tr><th>Username</th><td><?php echo htmlspecialchars($row["username"]); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($row["email"]); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($row["phone"]); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($row["address"]); ?></td></tr>
            
        </table>
        <a class="back-link" href="admin_dash.php">Back to Manage Users</a>
    </div>
</body>
</html>
