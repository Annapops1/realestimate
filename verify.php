<?php
// In your payment verification logic
if (/* payment failed condition */) {
    $_SESSION['payment_failure'] = "Oops, something went wrong. Payment failed.";
    header("Location: profile.php");
    exit();
}
?>