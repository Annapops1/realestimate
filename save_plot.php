<!-- save_plot.php -->
<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'realestimate');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$place = $_POST['place'];
$state = $_POST['state'];
$plot_size = $_POST['plot_size'];
$price = $_POST['price'];
$transaction_type = $_POST['transaction_type'];
$photo = $_FILES['photo']['name'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($photo);

if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
    $sql = "INSERT INTO properties (property_type, transaction_type, place, state, plot_size, price, photo)
            VALUES ('plot', '$transaction_type', '$place', '$state', '$plot_size', '$price', '$photo')";

    if ($conn->query($sql) === TRUE) {
        echo "Plot details uploaded successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}

$conn->close();
?>
