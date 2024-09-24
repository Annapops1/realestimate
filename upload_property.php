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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_type = $_POST['transaction_type'];
    $property_type = $_POST['property_type'];

    // Redirect based on the selected options
    if ($transaction_type === 'rent' && $property_type === 'house') {
        header("Location: upload_house_rent.php"); // Redirect to house rent upload page
    } elseif ($transaction_type === 'rent' && $property_type === 'plot') {
        header("Location: upload_plot_rent.php"); // Redirect to plot rent upload page
    } elseif ($transaction_type === 'buy' && $property_type === 'house') {
        header("Location: upload_house_buy.php"); // Redirect to house buy upload page
    } elseif ($transaction_type === 'buy' && $property_type === 'plot') {
        header("Location: upload_plot_buy.php"); // Redirect to plot buy upload page
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Property</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.6;
        }
        .main-header-area {
            width: 100%;
            z-index: 100;
            background-color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .classy-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
        }
        .classynav {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .classynav li {
            margin-left: 20px;
            display: inline-block;
        }
        .classynav li a {
            text-transform: uppercase;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            transition: color 0.3s, background-color 0.3s;
            border-radius: 5px;
        }
        .classynav li a:hover {
            background-color: #ff4a17;
            color: #fff;
        }
        .header-area {
            background-color: #2c3e50;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        .upload-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <!-- Main Header Area -->
    <div class="main-header-area" id="stickyHeader">
        <div class="classy-nav-container breakpoint-off">
            <nav class="classy-navbar justify-content-between" id="southNav">
                <div class="classy-menu">
                    <div class="classynav">
                        <ul>
                            <li><a href="index1.php">Home</a></li>
                            <li><a href="my_properties.php">My Properties</a></li>
                            <li><a href="upload_property.php">Upload Property</a></li>
                            <li><a href="search_properties.php">Search Properties</a></li>
                            <li><a href="view_property.php">View Property</a></li>
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <header class="header-area">
        <h1>Upload Property</h1>
    </header>

    <div class="upload-container">
        <form action="upload_property.php" method="post">
            <div class="form-group">
                <label for="transaction_type">Transaction Type:</label>
                <select id="transaction_type" name="transaction_type" required>
                    <option value="">Select Transaction Type</option>
                    <option value="rent">Rent</option>
                    <option value="buy">Buy</option>
                </select>
            </div>

            <div class="form-group">
                <label for="property_type">Property Type:</label>
                <select id="property_type" name="property_type" required>
                    <option value="">Select Property Type</option>
                    <option value="house">House</option>
                    <option value="plot">Plot</option>
                </select>
            </div>

            <input type="submit" value="Continue">
        </form>
    </div>
</body>
</html>

