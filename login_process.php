<?php
// Dummy login process script
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Here you would typically check the user's credentials in the database.
    // For simplicity, we are just redirecting to the home page.
    echo "Login successful for: " . htmlspecialchars($email);
    // header("Location: index.php");
}
?>
