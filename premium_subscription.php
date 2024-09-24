<?php
require('vendor/autoload.php');

// Razorpay API Key
$apiKey = 'rzp_test_UY1y7bu0apmIK4'; // Replace with your Key ID

// Create order
$orderData = [
    'receipt'         => 'rcptid_11',
    'amount'          => 99900, // Amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto capture
];

// Move order creation to a separate backend file
$orderId = createRazorpayOrder($orderData);

function createRazorpayOrder($orderData) {
    return 'order_placeholder_id';
}

?>

<form action="verify.php" method="POST">
    <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="<?php echo $apiKey; ?>"
        data-amount="<?php echo $orderData['amount']; ?>" 
        data-currency="<?php echo $orderData['currency']; ?>"
        data-order_id="<?php echo $orderId; ?>"
        data-buttontext="Buy Premium Subscription"
        data-name="RealEstiMate"
        data-description="Premium Subscription"
        data-image="https://your-logo-url.com/logo.png"
        data-theme.color="#F37254">
    </script>
    <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
</form>
