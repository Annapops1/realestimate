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
$sql = "SELECT place, district, state, size,  price, photo, user_id, property_type FROM properties WHERE property_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $property_id);
$stmt->execute();
$stmt->bind_result($place, $district, $state, $size,  $price, $photo, $user_id, $property_type);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .properties-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 40px auto;
            max-width: 900px;
        }

        .property-info {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .property-info p {
            font-size: 1.1rem;
            margin: 12px 0;
            color: #444;
        }

        .property-info strong {
            color: #2c5282;
            font-weight: 600;
        }

        .main-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
        }

        .main-image img {
            border-radius: 15px;
            transition: transform 0.4s ease;
        }

        .main-image img:hover {
            transform: scale(1.02);
        }

        .image-row {
            background: rgba(248, 249, 250, 0.8);
            padding: 15px;
            border-radius: 12px;
            margin: 25px 0;
        }

        .thumbnail-image {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin: 8px;
            border: 3px solid white;
            transition: all 0.3s ease;
        }

        .thumbnail-image:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .interest-button .btn {
            background: linear-gradient(145deg, #0066ff, #0052cc);
            border: none;
            padding: 14px 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }

        .interest-button .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            background: linear-gradient(145deg, #0052cc, #0066ff);
        }

        .card {
            border: none;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(145deg, #2c5282, #2b6cb0);
            border: none;
            padding: 20px;
        }

        .card-header h2 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        .card-body p {
            color: #444;
            font-size: 1.05rem;
            margin: 8px 0;
        }

        /* Modal Enhancements */
        .modal {
            backdrop-filter: blur(8px);
        }

        .carousel {
            max-width: 400px;
            margin: 20px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .carousel-item {
            height: 350px;
        }

        .carousel-item img {
            height: 100%;
            object-fit: cover;
            width: 100%;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 10px;
        }

        .carousel-indicators {
            margin-bottom: 10px;
        }

        .carousel-indicators li {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin: 0 4px;
        }

        @media (max-width: 768px) {
            .carousel {
                max-width: 100%;
                margin: 10px auto;
            }
            
            .carousel-item {
                height: 250px;
            }
        }

        .prev, .next {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            margin: 0 20px;
        }

        .prev:hover, .next:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        .close {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        #caption {
            font-size: 1.1rem;
            padding: 15px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 8px;
            margin-top: 15px;
        }

        /* Lightbox Navigation Styling */
        .lb-nav a.lb-prev,
        .lb-nav a.lb-next {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lb-container:hover .lb-nav a {
            opacity: 1;
        }

        /* Custom Arrow Buttons */
        .lb-nav a.lb-prev::before,
        .lb-nav a.lb-next::before {
            content: '';
            position: absolute;
            top: 50%;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            transform: translateY(-50%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .lb-nav a.lb-prev::before {
            left: 20px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        .lb-nav a.lb-next::before {
            right: 20px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        .lb-nav a.lb-prev:hover::before,
        .lb-nav a.lb-next:hover::before {
            transform: translateY(-50%) scale(1.1);
            background-color: white;
        }

        /* Custom Close Button */
        .lb-close {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            opacity: 1 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        .lb-close:hover {
            transform: scale(1.1) rotate(90deg);
            background-color: white;
        }

        /* Adjust the data container to not overlap with close button */
        .lb-dataContainer {
            padding-right: 60px;
        }

        .agreement-section {
            margin: 25px 0;
            text-align: center;
        }

        .btn-agreement {
            background: linear-gradient(145deg, #2c5282, #1a365d);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 82, 130, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .btn-agreement:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 82, 130, 0.3);
            background: linear-gradient(145deg, #1a365d, #2c5282);
        }

        .btn-agreement i {
            font-size: 18px;
        }

        #agreementStatus {
            margin-top: 10px;
            color: #666;
            font-style: italic;
        }

        /* Animation for loading spinner */
        .fa-spin {
            animation: fa-spin 2s infinite linear;
        }

        @keyframes fa-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .agreement-section {
            text-align: center;
            padding: 20px;
        }

        .btn {
            background: linear-gradient(145deg, #2c5282, #1a365d);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(44, 82, 130, 0.3);
            background: linear-gradient(145deg, #1a365d, #2c5282);
            color: white;
        }

        .btn i {
            margin-right: 8px;
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
            <a href="uploads/<?php echo htmlspecialchars(trim(explode(',', $photo)[0])); ?>" 
               data-lightbox="property-gallery">
                <img id="mainImage" src="uploads/<?php echo htmlspecialchars(trim(explode(',', $photo)[0])); ?>" 
                     alt="Main Property Image" class="property-image">
            </a>
        </div>
        
        <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicators/dots -->
            <ol class="carousel-indicators">
                <?php 
                $images = explode(',', $photo);
                $uniqueImages = array_unique(array_map('trim', $images));
                foreach ($uniqueImages as $index => $image):
                    if (empty($image)) continue;
                ?>
                    <li data-bs-target="#propertyCarousel" 
                        data-bs-slide-to="<?php echo $index; ?>" 
                        class="<?php echo $index === 0 ? 'active' : ''; ?>">
                    </li>
                <?php endforeach; ?>
            </ol>

            <!-- Slides -->
            <div class="carousel-inner">
                <?php 
                foreach ($uniqueImages as $index => $image):
                    if (empty($image)) continue;
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="uploads/<?php echo htmlspecialchars($image); ?>" 
                             class="d-block w-100" 
                             alt="Property Image <?php echo $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation arrows -->
            <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
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
        <div class="agreement-section container mt-4">
            <button onclick="window.open('view_agreement.php?property_id=<?php echo $property_id; ?>', '_blank')" class="btn btn-primary">
                <i class="fas fa-file-contract"></i> View Agreement
            </button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Image %1 of %2',
            'fadeDuration': 300,
            'imageFadeDuration': 300,
            'positionFromTop': 50,
            'maxWidth': 1200,
            'maxHeight': 800,
            'showImageNumberLabel': false,  // Hide the default image counter
            'disableScrolling': true       // Prevent scrolling while lightbox is open
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.querySelector('.lb-container')) {
                if (e.key === 'ArrowLeft') {
                    document.querySelector('.lb-prev').click();
                }
                if (e.key === 'ArrowRight') {
                    document.querySelector('.lb-next').click();
                }
                if (e.key === 'Escape') {
                    document.querySelector('.lb-close').click();
                }
            }
        });

        document.getElementById('generateAgreement').addEventListener('click', function() {
            const propertyId = <?php echo json_encode($property_id); ?>;
            const statusDiv = document.getElementById('agreementStatus');
            
            console.log('Generating agreement for property:', propertyId); // Debug log
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating agreement...';
            
            fetch(`generate_agreement.php?property_id=${propertyId}`)
                .then(response => {
                    console.log('Response:', response); // Debug log
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data); // Debug log
                    if (data.success) {
                        const link = document.createElement('a');
                        link.href = 'agreements/' + data.filename;
                        link.download = 'Property_Agreement.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> Agreement downloaded successfully!';
                        setTimeout(() => {
                            statusDiv.innerHTML = '';
                        }, 3000);
                    } else {
                        statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error: ' + 
                            (data.error || 'Failed to generate agreement');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error generating agreement';
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the carousel
            var myCarousel = new bootstrap.Carousel(document.getElementById('propertyCarousel'), {
                interval: 5000, // Change slide every 5 seconds
                wrap: true,     // Continuous loop
                keyboard: true  // Keyboard navigation
            });

            // Optional: Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    myCarousel.prev();
                } else if (e.key === 'ArrowRight') {
                    myCarousel.next();
                }
            });
        });
    </script>
</body>
</html>
