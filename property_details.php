<?php
session_start();

error_reporting(E_ERROR | E_PARSE);


// Check if property_id is passed
if (!isset($_GET['property_id'])) {
    die("Property ID is not specified.");
}
$current_user_id=$_SESSION['user_id'];
$property_id = $_GET['property_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch property details
$sql = "SELECT place, district, state, size, size_sqft, price, photo, user_id, property_type FROM properties WHERE property_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $property_id);
$stmt->execute();
$stmt->bind_result($place, $district, $state, $size, $size_sqft, $price, $photo, $user_id, $property_type);
$stmt->fetch();
$stmt->close();

// Fetch author details
$user_id = isset($user_id) ? $user_id : 0; // Ensure user_id is set
if ($user_id <= 0) {
    die("Invalid user ID.");
}

$sql = "SELECT username, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error . " - SQL: " . $sql); // Added SQL for debugging
}

// Bind parameters and execute
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error); // Check for execution errors
}
$stmt->bind_result($author_name, $author_email, $author_phone);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['intrestBtn'])) {
    $user_id = $_POST['user_id'];
    $property_id = $_POST['property_id'];
    $status = 'interested'; // or whatever status you want to set

    // Check if the interest already exists
    $checkSql = "SELECT COUNT(*) FROM interest WHERE user_id = ? AND property_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $property_id);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count == 0) {
        // Insert new interest record
        $insertSql = "INSERT INTO interest (user_id, property_id, status) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iis", $user_id, $property_id, $status);
        $insertStmt->execute();
        $insertStmt->close();
        
        // Use JavaScript to show an alert
        echo "<script>alert('Interest expressed successfully!');</script>";
    } else {
        echo "<script>alert('You have already expressed interest in this property.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="common.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            min-height: 100vh; /* Full height of the viewport */
        }

        .properties-container {
            display: flex;
            flex-direction: column; /* Stack children vertically */
            justify-content: space-between; /* Space between top and bottom */
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
            width: 90%; /* Responsive width */
            max-width: 800px; /* Maximum width */
        }

        .properties-container:hover {
            transform: translateY(-5px);
        }

        .main-image {
            text-align: center; /* Center the main image */
            margin-bottom: 20px; /* Space below the main image */
        }

        .main-image img {
            max-width: 70%; /* Set max width to 70% for medium size */
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px; /* Rounded corners */
        }

        .image-row {
            display: flex;
            flex-wrap: wrap; /* Allow images to wrap to the next line */
            margin-bottom: 20px;
            padding: 10px 0;
            justify-content: center; /* Center images */
            overflow: hidden; /* Hide overflow to prevent scrollbar */
        }

        .image-row .thumbnail-image {
            max-width: 100px; /* Set a smaller max width for thumbnails */
            height: auto; /* Maintain aspect ratio */
            margin: 5px; /* Add margin for spacing */
            border-radius: 10px;
            transition: transform 0.3s;
            cursor: pointer; /* Change cursor to pointer */
        }

        .image-row .thumbnail-image:hover {
            transform: scale(1.05);
        }

        .property-info {
            margin-bottom: 20px;
            text-align: center; /* Center text */
        }

        .property-info p {
            margin: 5px 0;
            font-size: 18px;
            color: #333;
        }

        .property-info strong {
            color: #007bff;
        }

        .interest-button {
            margin-top: 20px;
            text-align: center; /* Center button */
        }

        .interest-button .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 16px;
        }

        .interest-button .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .card {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            font-size: 20px;
        }

        .card-body {
            padding: 15px;
        }

        .card-body .row {
            margin: 0;
        }

        .card-body p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: hidden; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.9); /* Darker background for better contrast */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            transition: opacity 0.3s ease; /* Smooth transition for opening */
        }

        .carousel {
            position: relative;
            max-width: 50%; /* Set max width for the carousel */
            margin: auto;
            border-radius: 10px; /* Rounded corners for the carousel */
            overflow: hidden; /* Hide overflow */
        }

        .mySlides {
            display: none; /* Hide all images by default */
            width: 100%; /* Full width */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Shadow for depth */
            animation: fadeIn 0.5s; /* Fade-in animation */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: background-color 0.3s ease;
            border-radius: 50%; /* Circular buttons */
            user-select: none;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8); /* Darker on hover */
        }

        .prev {
            left: 10px; /* Position left */
        }

        .next {
            right: 10px; /* Position right */
        }

        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #ff0000; /* Change color on hover */
        }

        #caption {
            margin: auto;
            text-align: center;
            color: white; /* Caption text color */
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="properties-container">

    <div class="property-info">
            <p><strong>Place:</strong> <?php echo htmlspecialchars($place); ?></p>
            <p><strong>District:</strong> <?php echo htmlspecialchars($district); ?></p>
            <p><strong>State:</strong> <?php echo htmlspecialchars($state); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($size) . ' cent';?></strong> 
                
            </p>
            <p><strong>Price:</strong> ₹<?php echo number_format(htmlspecialchars($price), 2); ?></p>
        </div>
        <!-- Main Image Display -->
        <div class="main-image">
            <img id="mainImage" src="uploads/<?php echo htmlspecialchars(trim(explode(',', $photo)[0])); ?>" alt="Main Property Image" class="property-image">
        </div>
        
        <div class="image-row">
            <?php 
            // Split the image string into an array
            $images = explode(',', $photo); 
            foreach ($images as $image): 
            ?>
                <a href="#" class="image-link" data-image="uploads/<?php echo htmlspecialchars(trim($image)); ?>">
                    <img src="uploads/<?php echo htmlspecialchars(trim($image)); ?>" alt="Property Image" class="thumbnail-image">
                </a>
            <?php endforeach; ?>
        </div>
        
        
        <!-- Add Interest Button -->
        <div class="interest-button">
            <form action="#" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($current_user_id); ?>">
                <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($property_id); ?>">
                <?php if ($user_id !== $current_user_id): // Check if author ID is different from current user ID ?>
                    <button type="submit" name="intrestBtn" class="btn btn-primary">Express Interest</button>
                <?php endif; ?>
            </form>
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
    </div>

    <!-- Modal for Enlarged Image -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
        <div id="caption"></div>
    </div>

    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/classy-nav.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/active.js"></script>
    <script>
        // Get the modal
        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("modalImage");
        var captionText = document.getElementById("caption");
        var mainImage = document.getElementById("mainImage");

        // Get all thumbnail images
        var thumbnails = document.getElementsByClassName("thumbnail-image");
        
        // Loop through the thumbnails and add click event
        for (var i = 0; i < thumbnails.length; i++) {
            thumbnails[i].onclick = function(){
                mainImage.src = this.src; // Change the main image to the clicked thumbnail
                modal.style.display = "flex"; // Show modal
                modalImg.src = this.src; // Set modal image to the clicked thumbnail
                captionText.innerHTML = this.alt; // Set caption
            }
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() { 
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of the modal content
        modal.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        // Close the modal when the Escape key is pressed
        document.onkeydown = function(event) {
            if (event.key === "Escape") {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
