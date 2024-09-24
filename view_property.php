<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all properties
$sql = "SELECT property_id, title,place, district, state, size, photo FROM properties";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties - RealEstiMate</title>
    <link rel="stylesheet" href="common.css">
    <style>
        /* Reset some basic elements for better consistency across browsers */
        body, h1, h2, p, a {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Set the background color and font style for the body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation Bar */
        .navbar {
            overflow: hidden;
            background-color: #333;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
            margin: 0 10px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #04AA6D;
            color: white;
        }

        /* Header area styles */
        .header-area {
            background-color: #35424a;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .header-area h1 {
            margin: 0;
            font-size: 36px;
            font-weight: normal;
        }

        /* Properties Container */
        .properties-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .properties-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        .property-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }

        .property-item img {
            width: 150px;
            height: 100px;
            border-radius: 10px;
            margin-right: 20px;
            object-fit: cover;
        }

        .property-item h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #2980b9;
        }

        .property-item p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }

        .property-item a {
            display: inline-block;
            text-decoration: none;
            background-color: #2980b9;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 14px;
            margin-top: 10px;
        }

        .property-item a:hover {
            background-color: #3498db;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .property-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .property-item img {
                width: 100%;
                height: auto;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index1.php">Home</a>
        <a href="my_properties.php">My Properties</a>
        <a href="upload_property.php">Upload Property</a>
        <a href="search_properties.php">Search Properties</a>
        <a href="view_property.php">View Property</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
        <a href="contact.html">Contact</a>
    </div>

    <!-- Property View Heading -->
    <div class="header-area">
        <h1>Property View</h1>
    </div>

    <!-- Properties Container -->
    <div class="properties-container">
        <h1>Properties Available</h1>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="property-item">
                    <?php if ($row['photo']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Property Image">
                    <?php else: ?>
                        <img src="default-property.png" alt="Default Property Image">
                    <?php endif; ?>
                    <div>
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p><strong>Place:</strong> <?php echo htmlspecialchars($row['place']); ?></p>
                        <p><strong>District:</strong> <?php echo htmlspecialchars($row['district']); ?></p>
                        <p><strong>State:</strong> <?php echo htmlspecialchars($row['state']); ?></p>
                        <p><strong>Size:</strong> <?php echo htmlspecialchars($row['size']); ?> cent</p>
                        <a href="property_details.php?property_id=<?php echo $row['property_id']; ?>">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No properties available.</p>
        <?php endif; ?>
    </div>
</body>

</html>