<!-- select_property_type.php -->
<?php
$property_type = $_POST['property_type'];
$transaction_type = $_POST['transaction_type'];

if ($property_type === 'plot') {
    header("Location: upload_plot.php?transaction_type=" . urlencode($transaction_type));
} elseif ($property_type === 'house') {
    header("Location: upload_house.php?transaction_type=" . urlencode($transaction_type));
} else {
    echo "Invalid selection.";
}
exit();
?>
