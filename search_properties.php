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

// Initialize variables for the search
$district = '';
$state = '';
$min_size = '';
$max_size = '';
$sql = "SELECT property_id, title, place, district, state, size, photo FROM properties WHERE 1=1";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $district = $_POST['district'] ?? '';
    $state = $_POST['state'] ?? '';
    $min_size = $_POST['min_size'] ?? '';
    $max_size = $_POST['max_size'] ?? '';

    // Filter by district
    if (!empty($district)) {
        $sql .= " AND district LIKE '%" . $conn->real_escape_string($district) . "%'";
    }

    // Filter by state
    if (!empty($state)) {
        $sql .= " AND state LIKE '%" . $conn->real_escape_string($state) . "%'";
    }

    // Filter by size range
    if (!empty($min_size)) {
        $sql .= " AND size >= " . intval($min_size);
    }
    if (!empty($max_size)) {
        $sql .= " AND size <= " . intval($max_size);
    }
}

// Execute the query
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Properties - RealEstiMate</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .properties-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            width: 100%;
            margin: 5% auto;
            box-sizing: border-box;
        }

        .properties-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .properties-container h1 {
            margin-bottom: 20px;
            font-size: 32px;
            color: #333;
            text-align: center;
        }

        .property-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .property-info p {
            font-size: 18px;
            color: #666;
            flex: 1 1 45%;
            margin: 10px 0;
        }

        .description {
            margin-top: 30px;
            font-size: 16px;
            color: #444;
            line-height: 1.6;
        }

        .description p {
            margin: 0;
        }

        /* Header styles */
        .header-area {
            width: 100%;
            position: relative;
            z-index: 10;
        }

        .top-header-area {
            background-color: black;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .top-header-area .email-address a,
        .top-header-area .phone-number a {
            color: #fff;
            font-size: 14px;
            text-decoration: none;
        }

        .search-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            width: 100%;
            margin-top: 5%;
        }

        .search-container h1 {
            font-size: 24px;
            color: #0066cc;
            margin-bottom: 20px;
        }

        .search-container form {
            display: flex;
            flex-direction: column;
        }

        .search-container label {
            margin-bottom: 5px;
            font-size: 16px;
            color: #333;
        }

        .search-container input[type="text"],
        .search-container input[type="number"],
        .search-container select {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .search-container button {
            background-color: #0066cc;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-container button:hover {
            background-color: #005bb5;
        }

        .property-item {
            display: flex;
            align-items: center;
            margin-top: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .property-item img {
            width: 150px;
            height: 100px;
            border-radius: 10px;
            margin-right: 20px;
        }

        .property-item h2 {
            margin: 0;
            font-size: 20px;
            color: #0066cc;
        }

        .property-item p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }

        .property-item a {
            text-decoration: none;
            background-color: #0066cc;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .property-item a:hover {
            background-color: #005bb5;
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="south-load"></div>
    </div>
    <header class="header-area">
        <!-- Top Header Area -->
        <div class="top-header-area">
            <div class="h-100 d-md-flex justify-content-between align-items-center">
                <div class="email-address">
                    <a href="mailto:contact@southtemplate.com">contact@southtemplate.com</a>
                </div>
                <div class="phone-number d-flex">
                    <div class="icon">
                        <img src="img/icons/phone-call.png" alt="">
                    </div>
                    <div class="number">
                        <a href="tel:+45 677 8993000 223">+45 677 8993000 223</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header Area -->
        <div class="main-header-area" id="stickyHeader">
            <div class="classy-nav-container breakpoint-off">
                <!-- Classy Menu -->
                <nav class="classy-navbar justify-content-between" id="southNav">
                    <!-- Logo -->
                    <a class="nav-brand" href="index.html"><img src="img/core-img/logo.png" alt=""></a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu">
                        <!-- close btn -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
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
                            <!-- Nav End -->
                        </div>
                </nav>
            </div>
        </div>
    </header>
    <center>
        <div class="search-container">
            <h1>Search Properties</h1>
            <form method="POST" action="search_properties.php">
                <label for="district">District</label>
                <input type="text" name="district" id="district" value="<?php echo htmlspecialchars($district); ?>">

                <label for="state">State</label>
                <input type="text" name="state" id="state" value="<?php echo htmlspecialchars($state); ?>">

                <label for="min_size">Minimum Size (cent)</label>
                <input type="number" name="min_size" id="min_size" value="<?php echo htmlspecialchars($min_size); ?>">

                <label for="max_size">Maximum Size (cent)</label>
                <input type="number" name="max_size" id="max_size" value="<?php echo htmlspecialchars($max_size); ?>">

                <button type="submit">Search</button>
            </form>

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
                            <p><strong>Size:</strong> <?php echo htmlspecialchars($row['size']); ?> sq ft</p>
                            <a href="property_details.php?property_id=<?php echo $row['property_id']; ?>">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No properties found matching your criteria.</p>
            <?php endif; ?>
        </div>
    </center>
</body>
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/classy-nav.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/active.js"></script>

</html>