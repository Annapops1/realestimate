<?php
// dashboard.php
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: index.php");
    exit;
}

// Database connection
$host = '127.0.0.1'; // Database host
$db = 'miniproj'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user information
$user_id = $_SESSION["user_id"];
$sql = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Fetch properties information
$properties_sql = "SELECT property_id, address, city, state, size_sqft, bedrooms, bathrooms FROM properties WHERE user_id = ?";
$properties_stmt = $conn->prepare($properties_sql);
$properties_stmt->bind_param("i", $user_id);
$properties_stmt->execute();
$properties_result = $properties_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RealEstiMate</title>
    <link rel="stylesheet" href="common.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin-top: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .property-list {
            margin-top: 20px;
        }
        .property {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .logout-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            text-align: center;
            margin-top: 20px;
        }
        .logout-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Your email: <?php echo htmlspecialchars($email); ?></p>

        <h2>Your Properties</h2>
        <div class="property-list">
            <?php
            if ($properties_result->num_rows > 0) {
                while($property = $properties_result->fetch_assoc()) {
                    echo "<div class='property'>";
                    echo "<p>Address: " . htmlspecialchars($property['address']) . "</p>";
                    echo "<p>City: " . htmlspecialchars($property['city']) . "</p>";
                    echo "<p>State: " . htmlspecialchars($property['state']) . "</p>";
                    echo "<p>Size (sqft): " . htmlspecialchars($property['size_sqft']) . "</p>";
                    echo "<p>Bedrooms: " . htmlspecialchars($property['bedrooms']) . "</p>";
                    echo "<p>Bathrooms: " . htmlspecialchars($property['bathrooms']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No properties found.</p>";
            }
            $properties_stmt->close();
            ?>
        </div>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>
