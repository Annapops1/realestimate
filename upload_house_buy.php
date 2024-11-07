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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload and property insertion logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $place = $_POST['place'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $price = $_POST['price'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $photos = $_FILES['photo']; // Changed to handle multiple files
    $target_dir = "uploads/";
    $size = $_POST['size']; // Retrieve size from the form input

    // Validate inputs
    if (empty($place) || empty($bedrooms) || empty($price) || empty($state) || empty($district) || empty($photos['name'][0]) || empty($size)) {
        echo "All fields are required.";
    } elseif (!is_numeric($bedrooms) || $bedrooms < 1) {
        echo "Bedrooms must be a positive integer.";
    } elseif (!is_numeric($bathrooms) || $bathrooms < 1) {
        echo "Bathrooms must be a positive integer.";
    } elseif (!is_numeric($price) || $price <= 0) {
        echo "Price must be a positive number.";
    } else {
        // Check for duplicates
        $checkSql = "SELECT * FROM properties WHERE place = ? AND bedrooms = ? AND price = ? AND state = ? AND district = ? ";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("sssss", $place, $bedrooms, $price, $state, $district);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "This property already exists.";
        } else {
            $uploadedPhotos = [];
            foreach ($photos['name'] as $key => $photo) {
                $target_file = $target_dir . basename($photo);
                if (move_uploaded_file($photos['tmp_name'][$key], $target_file)) {
                    $uploadedPhotos[] = $photo; // Store uploaded photo names
                } else {
                    die("Sorry, there was an error uploading your file: " . $photo);
                }
            }

            // Prepare the SQL statement
            $sql = "INSERT INTO properties (user_id, place, bedrooms, bathrooms, price, photo, property_type, transaction_type, state, district, size_sqft)
                    VALUES (?, ?, ?, ?, ?, ?, 'house', 'buy', ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing the statement: " . $conn->error);
            }

            // Bind parameters
            $photosList = implode(',', $uploadedPhotos); // Convert array to comma-separated string
            $stmt->bind_param("issssssss", $_SESSION['user_id'], $place, $bedrooms, $bathrooms, $price, $photosList, $state, $district, $size);

            if ($stmt->execute()) {
                echo "House uploaded successfully.";
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
    <title>Upload House for Buy - RealEstiMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="common.css"> <!-- Include your common CSS file -->
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
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px; /* Space between label and input */
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
    <h1>Upload House for Buy</h1>
</header>
<div class="upload-container">
    <form action="upload_house_buy.php" method="post" enctype="multipart/form-data">
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
            <label for="bedrooms">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" min="1" required oninput="validateBedrooms()">
            <div class="error" id="bedroomsError" style="color: red;"></div>
        </div>

        <div class="form-group">
            <label for="bathrooms">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" min="1" required oninput="validateBathrooms()">
            <div class="error" id="bathroomsError" style="color: red;"></div>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required oninput="validatePrice()">
            <div class="error" id="priceError" style="color: red;"></div>
        </div>
        <div class="form-group">
            <label for="size">Size (in cent):</label>
            <input type="number" id="size" name="size" min="5" value="<?php echo htmlspecialchars($size); ?>" required oninput="validateSize()">
            <div class="error" id="sizeError" style="color: red;"></div>
        </div>

        <div class="form-group">
            <label for="photo">Upload Images:</label>
            <input type="file" id="photo" name="photo[]" accept="image/*" required multiple> <!-- Allow multiple file uploads -->
        </div>

        <input type="submit" value="Upload House">
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

    function validatePrice() {
        const priceInput = document.getElementById('price');
        const priceError = document.getElementById('priceError');
        const priceValue = parseFloat(priceInput.value); // Ensure the value is treated as a number

        if (priceValue < 10000) {
            priceError.textContent = "Price must be at least 10,000."; // Display error message
        } else if (priceValue <= 0) {
            priceError.textContent = "Price must be a positive number."; // Display error message
        } else {
            priceError.textContent = ""; // Clear error message
        }
    }

    function validateSize() {
        const sizeInput = document.getElementById('size');
        const sizeError = document.getElementById('sizeError');
        const sizeValue = parseFloat(sizeInput.value); // Ensure the value is treated as a number

        if (sizeValue <= 0) {
            sizeError.textContent = "The number has to be positive."; // Display error message
        } else {
            sizeError.textContent = ""; // Clear error message
        }
    }

    function validateBedrooms() {
        const bedroomsInput = document.getElementById('bedrooms');
        const bedroomsError = document.getElementById('bedroomsError');
        const bedroomsValue = parseInt(bedroomsInput.value); // Ensure the value is treated as a number

        if (bedroomsValue < 1) {
            bedroomsError.textContent = "Bedrooms must be greater than zero."; // Display error message
        } else {
            bedroomsError.textContent = ""; // Clear error message
        }
    }

    function validateBathrooms() {
        const bathroomsInput = document.getElementById('bathrooms');
        const bathroomsError = document.getElementById('bathroomsError');
        const bathroomsValue = parseInt(bathroomsInput.value); // Ensure the value is treated as a number

        if (bathroomsValue < 1) {
            bathroomsError.textContent = "Bathrooms must be greater than zero."; // Display error message
        } else {
            bathroomsError.textContent = ""; // Clear error message
        }
    }
</script>
</body>
</html>