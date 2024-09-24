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

// Fetch property details if ID is provided
if (isset($_GET['property_id'])) {
    $property_id = $_GET['property_id'];

    $sql = "SELECT * FROM properties WHERE property_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $property_id, $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $property = $result->fetch_assoc();
        } else {
            die("Property not found.");
        }
    } else {
        die("Error: " . $stmt->error);
    }
    $stmt->close();
} else {
    die("No property ID specified.");
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $place = $_POST['place'];
    $state = $_POST['state'];
    $size = $_POST['size'];
  

    // Handle file upload if a new file is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // File upload success
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        // Keep the existing photo
        $photo = $property['photo'];
    }

    // Prepare the SQL statement
    $sql = "UPDATE properties SET  place = ?, state = ?, size = ?, photo = ? WHERE property_id = ? AND user_id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssisii",  $place,  $state, $size, $photo, $property_id, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
                alert('Property updated successfully.');
                window.location.href = 'my_properties.php?property_id={$property_id}'; // Redirect to view the updated property
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property - RealEstiMate</title>
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
            margin: 10% auto;
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

        .upload-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 5%;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }

        .upload-container h1 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        .upload-container form {
            display: flex;
            flex-direction: column;
        }

        .upload-container form label {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .upload-container form input[type="text"],
        .upload-container form input[type="file"],
        .upload-container form textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        .upload-container form textarea {
            resize: vertical;
            height: 150px;
        }

        .upload-container form input[type="submit"] {
            background-color: #0066cc;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-container form input[type="submit"]:hover {
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

    <div class="upload-container">
        <h1>Edit Property</h1>
        <form action="edit_property.php?property_id=<?php echo $property_id; ?>" method="post" enctype="multipart/form-data">

            <label for="place">Place:</label>
            <input type="text" id="place" name="place" value="<?php echo htmlspecialchars($property['place']); ?>" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($property['state']); ?>" required>

            
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*">

            <input type="submit" value="Update Property">
        </form>
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