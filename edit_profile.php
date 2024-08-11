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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $photo = $_FILES['photo']['name'];

    // File upload handling
    if (!empty($photo)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, address = ?, photo = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $username, $email, $phone, $address, $photo, $user_id);
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $phone, $address, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch user details
$sql = "SELECT username, email, phone, address, photo FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $address, $photo);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .edit-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            margin-top: 5%;
        }

        .edit-container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .edit-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .edit-container input[type="text"],
        .edit-container input[type="email"],
        .edit-container input[type="tel"],
        .edit-container input[type="file"],
        .edit-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .edit-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #0066cc;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-container input[type="submit"]:hover {
            background-color: #005bb5;
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
        <div class="edit-container">
            <h1>Edit Profile</h1>
            <form action="edit_profile.php" method="post" enctype="multipart/form-data">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="phone">Phone</label>
                <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

                <label for="address">Address</label>
                <textarea name="address" id="address" required><?php echo htmlspecialchars($address); ?></textarea>

                <label for="photo">Profile Photo</label>
                <input type="file" name="photo" id="photo" accept="image/*">

                <input type="submit" value="Update Profile">
            </form>
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