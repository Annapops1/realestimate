<!-- save_house.php -->
<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'realestimate');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$place = $_POST['place'];
$state = $_POST['state'];
$house_size = $_POST['house_size'];
$num_bedrooms = $_POST['num_bedrooms'];
$num_bathrooms = $_POST['num_bathrooms'];
$furnishing = $_POST['furnishing'];
$amenities = $_POST['amenities'];
$address = $_POST['address'];
$price = $_POST['price'];
$transaction_type = $_POST['transaction_type'];
$photo = $_FILES['photo']['name'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($photo);

if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
    $sql = "INSERT INTO properties (property_type, transaction_type, place, state, house_size, num_bedrooms, num_bathrooms, furnishing, amenities, address, price, photo)
            VALUES ('house', '$transaction_type', '$place', '$state', '$house_size', '$num_bedrooms', '$num_bathrooms', '$furnishing', '$amenities', '$address', '$price', '$photo')";

    if ($conn->query($sql) === TRUE) {
        echo "House details uploaded successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Sorry, there was an error uploading your file.";
}

$conn->close();
?>
