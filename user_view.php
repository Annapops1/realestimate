<?php
session_start();

$host = '127.0.0.1'; // Database host
$db = 'miniproj'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['user_id'])) {
    header("Location: admin_dash.php");
    exit();
}

$user_id = $_GET['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: admin_dash.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #35424a;
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #e8491d 3px solid;
        }
        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        .details {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }
        .details h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .details p {
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>User Details</h1>
        </div>
    </header>

    <div class="container">
        <div class="details">
            <h2><?= htmlspecialchars($user['username']) ?>'s Details</h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Password:</strong> <?= htmlspecialchars($user['password']) ?></p>
            <!-- Add more details like phone number, address, etc., if these columns exist -->
            <!-- Example:
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
            <p><strong>Photo:</strong> <img src="<?= htmlspecialchars($user['photo_path']) ?>" alt="User Photo"></p>
            -->
            <a href="admin_dash.php">Back to Users List</a>
        </div>
    </div>
</body>
</html>
