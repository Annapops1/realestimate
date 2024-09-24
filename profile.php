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

// Fetch user details
$sql = "SELECT username, email, phone, address, photo FROM users WHERE user_id = ? AND is_active = 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
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
    <link rel="stylesheet" href="common.css">
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

        .profile-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            margin-top: 5%;
        }

        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .profile-container h1 {
            margin-bottom: 15px;
            font-size: 28px;
            color: #333;
        }

        .profile-container p {
            margin: 10px 0;
            font-size: 18px;
            color: #555;
        }

        .profile-container a {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            background-color: #0066cc;
            color: #fff;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .profile-container a:hover {
            background-color: #005bb5;
            box-shadow: 0 0 15px rgba(0, 102, 204, 0.4);
        }

        .profile-container .profile-details {
            margin-top: 20px;
            text-align: left;
        }

        .profile-container .profile-details p {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
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
        <?php
require 'vendor/autoload.php';

// Razorpay API Key
$apiKey = 'rzp_test_UY1y7bu0apmIK4'; // Replace with your Key ID

// Create order
$orderData = [
    'receipt' => 'rcptid_11',
    'amount' => 99900, // Amount in paise
    'currency' => 'INR',
    'payment_capture' => 1, // Auto capture
];

// Move order creation to a separate backend file
$orderId = createRazorpayOrder($orderData);

function createRazorpayOrder($orderData)
{
    return 'order_placeholder_id';
}

// Check for payment failure message
$paymentFailureMessage = isset($_SESSION['payment_failure']) ? $_SESSION['payment_failure'] : '';
unset($_SESSION['payment_failure']); // Clear the message after retrieving it
?>

    </header>
    <center>
    <div class="profile-container">
        <?php if (!empty($paymentFailureMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($paymentFailureMessage); ?>
            </div>
        <?php endif;?>

        <?php if ($photo): ?>
            <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="Profile Photo">
        <?php else: ?>
            <img src="default-avatar.png" alt="Default Avatar">
        <?php endif;?>
        <h1><?php echo htmlspecialchars($username); ?></h1>

        <div class="profile-details">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        </div>

        <a href="edit_profile.php">Edit Profile</a>
        <form action="" method="POST">
<script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $apiKey; ?>"
    data-amount="99900" 
    data-currency="INR"
    data-id="<?php echo 'OID' . rand(10, 100) . 'END'; ?>"
    data-buttontext="Premium Subscription"
    data-name="Atharv Organics"

    data-image="logo.png"
    data-prefill.name="name"
    data-prefill.email="email"
    data-prefill.address="address"
    data-theme.color="#F37254"
></script>
<input type="hidden" custom="Hidden Element" name="hidden">
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