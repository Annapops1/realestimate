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

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch properties uploaded by the logged-in user
$sql = "SELECT property_id,  place, state FROM properties WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Properties - RealEstiMate</title>
    <link rel="stylesheet" href="common.css">


    <style>
        .property-list {
            list-style-type: none;
            padding: 0;
        }

        .property-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .property-list li:last-child {
            border-bottom: none;
        }

        .property-info {
            flex-grow: 1;
        }

        .property-info h2 {
            font-size: 22px;
            margin: 0;
            color: #0066cc;
        }

        .property-info p {
            margin: 5px 0;
            color: #666;
        }

        .property-actions a {
            margin-left: 10px;
            text-decoration: none;
            background-color: #0066cc;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .property-actions a:hover {
            background-color: #005bb5;
        }

        .property-actions a.delete-btn {
            background-color: #cc0000;
        }

        .property-actions a.delete-btn:hover {
            background-color: #b20000;
        }
    </style>

</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="south-load"></div>
    </div>
    <header class="header-area">

        <!-- Top Header Area -->
        <div class="top-header-area">
            <div class="h-100 d-md-flex justify-content-between align-items-center">
                
                </div>
            </div>
        </div>

        <!-- Main Header Area -->
        <div class="main-header-area" id="stickyHeader">
            <div class="classy-nav-container breakpoint-off">
                <!-- Classy Menu -->
                <nav class="classy-navbar justify-content-between" id="southNav">

                    <!-- Logo -->
                    <a class="nav-brand" href="index.html">

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
    <div class="properties-container" style="
    margin-top: 10%;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 80%; /* Adjust the width as needed */
">
        <h1 style="text-align: center;">My Properties</h1>
        <ul class="property-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <div class="property-info">

                    <p>Location: <?php echo htmlspecialchars($row['place'] . ', ' . $row['state']); ?></p>
                    </div>
                    <div class="property-actions">
                        <a href="property_details.php?property_id=<?php echo $row['property_id']; ?>">View</a>
                        <a href="edit_property.php?property_id=<?php echo $row['property_id']; ?>">Edit</a>
                        <!-- <a href="delete_property.php?property_id=<?php echo $row['property_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a> -->
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

</body>
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<!-- Popper js -->
<script src="js/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="js/bootstrap.min.js"></script>
<!-- Plugins js -->
<script src="js/plugins.js"></script>
<script src="js/classy-nav.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<!-- Active js -->
<script src="js/active.js"></script>

</html>