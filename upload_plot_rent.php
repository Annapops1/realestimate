<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessages = []; // Initialize the error messages array
$size = $price = ""; // Initialize variables to retain input values

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $place = $_POST['place'];
    $price = $_POST['price'];
    $state = $_POST['state'];
    $size = $_POST['size']; // Size in cent
    $district = $_POST['district'];
    $photos = $_FILES['photo'];
    $target_dir = "uploads/";
    $target_files = [];

    // Validate inputs
    if (empty($place) || empty($state) || empty($district) || empty($photos) || empty($size)) {
        $errorMessages[] = "All fields are required.";
    }
    if (!is_numeric($size) || $size < 5) { // Minimum size requirement of 5 cent
        $errorMessages[] = "Size must be at least 5 cent.";
    }
    if (!is_numeric($price) || $price <= 0) {
        $errorMessages[] = "Price must be a positive number.";
    }

    if (empty($errorMessages)) {
        // Check for duplicate properties
        $checkSql = "SELECT * FROM properties WHERE place = ? AND price = ? AND state = ? AND district = ? AND size = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("sssss", $place, $price, $state, $district, $size);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $errorMessages[] = "This property already exists.";
        } else {
            // Handle multiple file uploads
            foreach ($photos['tmp_name'] as $key => $tmp_name) {
                $photo_name = basename($photos['name'][$key]);
                $target_file = $target_dir . $photo_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $target_files[] = $photo_name; // Add to the array
                } else {
                    die("Sorry, there was an error uploading your file: " . $photo_name);
                }
            }

            // Convert array to comma-separated string
            $photo_string = implode(',', $target_files);

            // Prepare the SQL statement
            $sql = "INSERT INTO properties (user_id, place, price, photo, property_type, transaction_type, state, district, size) 
                    VALUES (?, ?, ?, ?, 'plot', 'rent', ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing the statement: " . $conn->error);
            }

            // Correct binding parameters
            $stmt->bind_param("issssss", $_SESSION['user_id'], $place, $price, $photo_string, $state, $district, $size);

            if ($stmt->execute()) {
                echo "Plot uploaded successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $checkStmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Plot for Rent - RealEstiMate</title>
    <link rel="stylesheet" href="common.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Light background for better contrast */
            margin: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .upload-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 40px auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            border: 1px solid #ced4da; /* Light border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Padding for better touch */
            width: 100%; /* Full width */
            transition: border-color 0.3s; /* Smooth transition */
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #007bff; /* Highlight border on focus */
            outline: none; /* Remove default outline */
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px; /* Space between label and input */
        }
        .error {
            color: red; /* Error message color */
            font-size: 0.9em; /* Smaller font size for error messages */
            margin-top: 5px; /* Space above error message */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="./index1.php">RealEstimate</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="./index1.php">Home</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<header>
    <h1>Upload Plot for Rent</h1>
</header>
<div class="upload-container">
    <form action="upload_plot_rent.php" method="post" enctype="multipart/form-data">
        <!-- State Dropdown -->
        <div class="form-group">
            <label for="state">State:</label>
            <select id="state" name="state" required onchange="populateDistricts()">
                <option value="">Select State</option>
            </select>
        </div>

        <!-- District Dropdown -->
        <div class="form-group">
            <label for="district">District:</label>
            <select id="district" name="district" required onchange="populatePlaces()">
                <option value="">Select District</option>
            </select>
        </div>

        <!-- Place Dropdown -->
        <div class="form-group">
            <label for="place">Place:</label>
            <select id="place" name="place" required>
                <option value="">Select Place</option>
            </select>
        </div>

        <div class="form-group">
            <label for="size">Size (in cent):</label>
            <input type="number" id="size" name="size" min="5" value="<?php echo htmlspecialchars($size); ?>" required>
            <div class="error" id="sizeError"></div>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>
            <div class="error" id="priceError"></div>
        </div>

        <div class="form-group">
            <label for="photo">Upload Images:</label>
            <input type="file" id="photo" name="photo[]" accept="image/*" required multiple>
        </div>

        <input type="submit" value="Upload Plot">
    </form>
</div>

<script>
    // Fetch locations data from PHP file
    fetch('fetch_locations.php') // Adjust the path if necessary
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const stateSelect = document.getElementById('state');
            for (const state in data) {
                const option = document.createElement('option');
                option.value = state;
                option.text = state;
                stateSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

    function populateDistricts() {
        const stateSelect = document.getElementById('state');
        const districtSelect = document.getElementById('district');
        const placeSelect = document.getElementById('place');
        const selectedState = stateSelect.value;

        districtSelect.innerHTML = '<option value="">Select District</option>';
        placeSelect.innerHTML = '<option value="">Select Place</option>';

        if (selectedState) {
            fetch('fetch_locations.php')
                .then(response => response.json())
                .then(data => {
                    const districts = Object.keys(data[selectedState]);
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.text = district;
                        districtSelect.appendChild(option);
                    });
                });
        }
    }

    function populatePlaces() {
        const stateSelect = document.getElementById('state');
        const districtSelect = document.getElementById('district');
        const placeSelect = document.getElementById('place');
        const selectedState = stateSelect.value;
        const selectedDistrict = districtSelect.value;

        placeSelect.innerHTML = '<option value="">Select Place</option>';

        if (selectedState && selectedDistrict) {
            fetch('fetch_locations.php')
                .then(response => response.json())
                .then(data => {
                    const places = data[selectedState][selectedDistrict];
                    places.forEach(place => {
                        const option = document.createElement('option');
                        option.value = place;
                        option.text = place;
                        placeSelect.appendChild(option);
                    });
                });
        }
    }

    // Client-side validation for size and price
    document.getElementById('size').addEventListener('input', function() {
        const sizeInput = this.value;
        const sizeError = document.getElementById('sizeError');
        sizeError.textContent = ''; // Clear previous error message

        if (sizeInput === '' || isNaN(sizeInput) || sizeInput < 5) {
            sizeError.textContent = 'Size must be at least 5 cent.';
        }
    });

    document.getElementById('price').addEventListener('input', function() {
        const priceInput = this.value;
        const priceError = document.getElementById('priceError');
        priceError.textContent = ''; // Clear previous error message

        if (priceInput === '' || isNaN(priceInput) || priceInput <= 0) {
            priceError.textContent = 'Price must be a positive number.';
        }
    });
</script>
</body>
</html>
