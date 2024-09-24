<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

$user_name = $is_logged_in ? $_SESSION['username'] : 'Guest';
$user_photo = $is_logged_in ? $_SESSION['photo'] : 'https://via.placeholder.com/30';
$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details using username
$sql = "SELECT username, email, phone, address, photo FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $user_name);
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
    <title>RealEstimate - Your Dream Home Awaits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
        }
        
        .navbar {
            background-color: var(--secondary-color);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            color: #fff !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .hero-section {
            background-image: url('https://source.unsplash.com/random/1600x900/?luxury,house');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            padding: 2rem 0;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 10px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .features-section {
            padding: 5rem 0;
            background-color: #fff;
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .featured-properties {
            padding: 5rem 0;
            background-color: #f8f9fa;
        }
        
        .property-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
        }
        
        .footer {
            background-color: var(--secondary-color);
            color: #fff;
            padding: 3rem 0 1rem;
        }
        
        .footer-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .footer-link {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: var(--primary-color);
        }
        
        .dropdown-menu {
            background-color: var(--secondary-color);
        }
        
        .dropdown-item {
            color: #fff;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .navbar .dropdown-menu {
            margin-top: 0.5rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .navbar .dropdown-item {
            padding: 0.5rem 1.5rem;
        }
        
        section {
            margin-bottom: 3rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">RealEstimate</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index1.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="propertiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Properties
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="propertiesDropdown">
                            <li><a class="dropdown-item" href="view_property.php">View Property</a></li>
                            <li><a class="dropdown-item" href="upload_property.php">Upload Property</a></li>
                            <li><a class="dropdown-item" href="search_properties.php">Search Properties</a></li>

                        </ul>
                    </li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="User Profile" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">                                <span class="d-none d-md-inline"><?php echo htmlspecialchars($user_name); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                <li><a class="dropdown-item" href="my_properties.php">My Properties</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero-section">
            <div class="hero-overlay"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="hero-content text-center">
                            <h1 class="hero-title">Find Your Dream Home</h1>
                            <p class="hero-subtitle">Discover the perfect property with RealEstimate</p>
                            <div class="d-flex justify-content-center">
                                <a href="rent_properties.php" class="btn btn-primary btn-lg me-3">Rent a Property</a>
                                <a href="buy_properties.php" class="btn btn-outline-light btn-lg">Buy a Property</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <i class="fas fa-home feature-icon"></i>
                        <h3 class="feature-title">Wide Range of Properties</h3>
                        <p>Explore our diverse portfolio of properties to find your perfect match.</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <i class="fas fa-dollar-sign feature-icon"></i>
                        <h3 class="feature-title">Best Price Guarantee</h3>
                        <p>We ensure you get the best value for your investment in the real estate market.</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <i class="fas fa-user-tie feature-icon"></i>
                        <h3 class="feature-title">Expert Guidance</h3>
                        <p>Our team of professionals is here to assist you throughout your property journey.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="featured-properties">
            <div class="container">
                <h2 class="text-center mb-5">Featured Properties</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card property-card">
                            <img src="https://source.unsplash.com/random/400x300/?apartment" class="card-img-top" alt="Property 1">
                            <div class="card-body">
                                <h5 class="card-title">Luxury Apartment</h5>
                                <p class="card-text">A stunning apartment with modern amenities in the heart of the city.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card property-card">
                            <img src="https://source.unsplash.com/random/400x300/?house" class="card-img-top" alt="Property 2">
                            <div class="card-body">
                                <h5 class="card-title">Family Home</h5>
                                <p class="card-text">Spacious family home with a beautiful garden in a quiet neighborhood.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card property-card">
                            <img src="https://source.unsplash.com/random/400x300/?villa" class="card-img-top" alt="Property 3">
                            <div class="card-body">
                                <h5 class="card-title">Seaside Villa</h5>
                                <p class="card-text">Luxurious villa with breathtaking ocean views and private beach access.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">About RealEstimate</h4>
                    <p>We are dedicated to helping you find your perfect property, whether you're looking to buy, sell, or rent.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Home</a></li>
                        <li><a href="#" class="footer-link">Properties</a></li>
                        <li><a href="#" class="footer-link">About Us</a></li>
                        <li><a href="#" class="footer-link">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">Contact Us</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 Real Estate St, City, Country</p>
                    <p><i class="fas fa-phone me-2"></i> +1 (123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i> info@realestate.com</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2023 RealEstimate. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>