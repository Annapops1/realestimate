<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
$properties = [];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $location = $_GET['location'] ?? '';
    $property_type = $_GET['property_type'] ?? '';
    $max_price = $_GET['max_price'] ?? '';
    $bedrooms = $_GET['bedrooms'] ?? '';

    // Prepare the SQL query
    $sql = "SELECT * FROM properties WHERE transaction_type = 'rent'";
    if ($location) {
        $sql .= " AND (place LIKE '%$location%' OR district LIKE '%$location%')";
    }
    if ($property_type) {
        $sql .= " AND property_type = '$property_type'";
    }
    if ($max_price) {
        $sql .= " AND price <= '$max_price'";
    }
    if ($bedrooms) {
        $sql .= " AND bedrooms >= '$bedrooms'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent a Property - RealEstimate</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="rent_properties.css">
    <style>
        .rent-properties-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .search-form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .property-listings {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .property-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .property-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .property-info {
            padding: 15px;
        }

        .property-info h3 {
            margin-bottom: 10px;
        }

        .price {
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
        }

        .view-details {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .view-details:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .property-listings {
                grid-template-columns: 1fr;
            }
        }

        /* Add these new styles */

        /* Smooth transitions for interactive elements */
        .search-button,
        .view-details {
            transition: background-color 0.3s ease;
        }

        /* Improve form layout on larger screens */
        @media (min-width: 768px) {
            .search-form form {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        /* Add some depth to property cards */
        .property-card {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }

        .property-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Style for when no properties are found */
        .no-properties {
            text-align: center;
            padding: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            margin-top: 20px;
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #007bff;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        /* New header styles */
        .main-header {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
        }

        .site-title {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #007bff;
            text-decoration: none;
        }

        .main-nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .main-nav li {
            margin-left: 1rem;
        }

        .main-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .main-nav a:hover {
            background-color: #007bff;
            color: #ffffff;
        }

        .main-nav a.active {
            background-color: #007bff;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .main-nav {
                margin-top: 1rem;
                width: 100%;
            }

            .main-nav ul {
                flex-direction: column;
            }

            .main-nav li {
                margin-left: 0;
                margin-bottom: 0.5rem;
            }

            .main-nav a {
                display: block;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <a href="index1.php" class="site-title">RealEstiMate</a>
            <nav class="main-nav">
                <ul>
                    <li><a href="index1.php" <?php echo ($_SERVER['PHP_SELF'] == '/index1.php') ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="rent_properties.php" <?php echo ($_SERVER['PHP_SELF'] == '/rent_properties.php') ? 'class="active"' : ''; ?>>Rent</a></li>
                    <li><a href="buy_properties.php" <?php echo ($_SERVER['PHP_SELF'] == '/buy_properties.php') ? 'class="active"' : ''; ?>>Buy</a></li>
                    <?php if(isset($_SESSION['username'])): ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php" <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'class="active"' : ''; ?>>Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="rent-properties-container">
        <h1>Rent a Property</h1>
        <div class="property-listings">
            <?php if (count($properties) > 0): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="property-card">
                        <?php 
                        // Assuming 'photo' contains the image filenames separated by commas
                        $imageArray = explode(',', $property['photo']); // Split the filenames into an array
                        $image = !empty($imageArray[0]) ? htmlspecialchars(urlencode($imageArray[0])) : 'default-property.png'; 
                        
                        // Check if the image file exists
                        $imagePath = 'uploads/' . $image;
                        if (!file_exists($imagePath)) {
                            $image = 'default-property.png'; // Fallback to default image if not found
                        }
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="Property Image" style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="property-info">
                            <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p><?php echo htmlspecialchars($property['description']); ?></p>
                            <p class="price">₹<?php echo htmlspecialchars($property['price']); ?></p>
                            <a href="property_details.php?property_id=<?php echo htmlspecialchars($property['property_id']); ?>" class="view-details">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-properties">No properties found.</div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Include your footer here -->

    <!-- Include your JavaScript files here -->
</body>
</html>