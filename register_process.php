<?php
// register_process.php
// Process registration form submission

$host = '127.0.0.1'; // Database host
$db = 'miniproj'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

// Establish a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $image = $_FILES['image'];

    // Validate input
    if (!preg_match("/^[a-zA-Z\s]+$/", $username)) {
        $message = "Name can only contain letters and spaces.";
        header("Location: register.php?message=" . urlencode($message));
        exit;
    }
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        $message = "Email must be a valid Gmail address.";
        header("Location: register.php?message=" . urlencode($message));
        exit;
    }
    if (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
        header("Location: register.php?message=" . urlencode($message));
        exit;
    }
    if ($image['name'] && !preg_match("/\.(jpg|jpeg|png)$/", $image['name'])) {
        $message = "Profile image must be a .jpg, .jpeg, or .png file.";
        header("Location: register.php?message=" . urlencode($message));
        exit;
    }
    if (!preg_match("/^\d{10}$/", $phone)) {
        $message = "Phone number must be exactly 10 digits.";
        header("Location: register.php?message=" . urlencode($message));
        exit;
    }
    if (empty($address)) {
        $message = "Address cannot be empty.";
        header("Location: register.php?message=" . urlencode($message));
        exit;
    }

    // Insert into the users table
    $password_hash = password_hash($password, PASSWORD_BCRYPT); // Hash the password
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password_hash, $email);
    
    if ($stmt->execute()) {
        // Registration successful
        $message = "Registration successful. Please login.";
        header("Location: index.php?message=" . urlencode($message));
    } else {
        // Registration failed
        $message = "Error: " . $stmt->error;
        header("Location: register.php?message=" . urlencode($message));
    }
    
    $stmt->close();
}

$conn->close();
?>
