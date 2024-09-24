<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if property_id is passed
if (!isset($_GET['property_id'])) {
    die("Property ID is not specified.");
}

$property_id = $_GET['property_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch property details
$sql = "SELECT place, district, state, size, price, photo, user_id FROM properties WHERE property_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $property_id);
$stmt->execute();
$stmt->bind_result($place, $district, $state, $size, $price, $photo, $user_id);
$stmt->fetch();
$stmt->close();

// Fetch author details
$sql = "SELECT username, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($author_name, $author_email, $author_phone);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Property Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="common.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }





/* Main Header Area */
.main-header-area {
    position: sticky;
    top: 0;
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

.nav-brand img {
    width: 150px;
}

.classy-navbar-toggler {
    display: none;
    cursor: pointer;
}

.classy-navbar-toggler .navbarToggler {
    width: 30px;
    height: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.classy-navbar-toggler .navbarToggler span {
    height: 3px;
    background-color: #fff;
}

.classy-menu {
    display: flex;
    align-items: center;
}

.classy-menu .classycloseIcon {
    display: none;
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

.classynav li:first-child {
    margin-left: 0;
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

@media (max-width: 992px) {
    .classy-navbar-toggler {
        display: block;
    }

    .classy-menu {
        position: absolute;
        top: 100%;
        right: 0;
        left: 0;
        background-color: #333;
        flex-direction: column;
        align-items: flex-start;
        display: none;
        padding: 15px;
    }

    .classy-menu.active {
        display: flex;
    }

    .classynav {
        flex-direction: column;
        width: 100%;
    }

    .classynav li {
        width: 100%;
        margin: 10px 0;
    }

    .classy-navbar-toggler .navbarToggler span {
        background-color: #ff4a17;
    }

    .classycloseIcon {
        display: block;
        cursor: pointer;
    }

    .classycloseIcon .cross-wrap {
        width: 25px;
        height: 25px;
        position: relative;
    }

    .classycloseIcon .cross-wrap span {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #fff;
        transform: translateY(-50%);
    }

    .classycloseIcon .cross-wrap span.top {
        transform: rotate(45deg);
    }

    .classycloseIcon .cross-wrap span.bottom {
        transform: rotate(-45deg);
    }
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

        

        

       
    </style>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="south-load"></div>
    </div>
    <header class="header-area">
        <!-- Top Header Area -->
        
        
        <!-- Main Header Area -->
        <div class="main-header-area" id="stickyHeader">
            <div class="classy-nav-container breakpoint-off">
                <!-- Classy Menu -->
                
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

    <div class="properties-container">
        <?php if ($photo): ?>
            <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="Property Image">
        <?php else: ?>
            <img src="default-property.png" alt="Default Property Image">
        <?php endif; ?>
        <div class="property-info">
            <p><strong>Place:</strong> <?php echo htmlspecialchars($place); ?></p>
            <p><strong>District:</strong> <?php echo htmlspecialchars($district); ?></p>
            <p><strong>State:</strong> <?php echo htmlspecialchars($state); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($size); ?> cent</p>
            <p><strong>Price:</strong> ₹<?php echo number_format(htmlspecialchars($price), 2); ?></p>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Author Details</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Name:</strong></p>
                        <p><strong>Email:</strong></p>
                        <p><strong>Phone:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p><?php echo htmlspecialchars($author_name); ?></p>
                        <p><?php echo htmlspecialchars($author_email); ?></p>
                        <p><?php echo htmlspecialchars($author_phone); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/classy-nav.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/active.js"></script>

</html>