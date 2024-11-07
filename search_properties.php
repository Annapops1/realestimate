<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Properties - RealEstiMate</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px 0;
            padding-left: 10%;
        }

        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
            margin-right: 10px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #555;
            font-weight: 500;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
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
</script>
</head>
<body>
    <div class="navbar">
        <a href="index1.php">Home</a>
        <a href="my_properties.php">My Properties</a>
        <a href="upload_property.php">Upload Property</a>
        <a href="search_properties.php">Search Properties</a>
        <a href="view_property.php">View Property</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
        <a href="contact.php">Contact</a>
    </div>

    <div class="container">
        <h1>Search Properties</h1>
        <form method="POST" action="search_properties.php">
            <label for="state">State</label>
            <select name="state" id="state" onchange="populateDistricts()">
                <option value="">Select State</option>
                <!-- Add more states here -->
            </select>

            <label for="district">District</label>
            <select name="district" id="district" onchange="populatePlaces()">
                <option value="">Select District</option>
            </select>

            <label for="place">Place</label>
            <select name="place" id="place">
                <option value="">Select Place</option>
            </select>

            <button type="submit">Search</button>
        </form>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $state = $_POST['state'];
    $district = $_POST['district'];
    $place = $_POST['place'];

    // Database connection
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "miniproj";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Adjust SQL query based on whether place is selected
    if (!empty($place)) {
        $sql = "SELECT * FROM properties WHERE state='$state' AND district='$district' AND place='$place'";
    } else {
        $sql = "SELECT * FROM properties WHERE state='$state' AND district='$district'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<table id='propertiesTable'>";
        echo "<thead><tr><th>State</th><th>District</th><th>Place</th><th>Price</th><th>View</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["state"] . "</td><td>" . $row["district"] . "</td><td>" . $row["place"] . "</td><td>" . $row["price"] . "</td><td><a href='property_details.php?property_id=" . $row["property_id"] . "' class='btn btn-success'>View</a></td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No results found.";
    }

    // Now, fetch similar properties for recommendations
    $recommendation_sql = "SELECT * FROM properties WHERE state='$state' AND district='$district' AND place != '$place' LIMIT 5";
    $recommendation_result = $conn->query($recommendation_sql);

    if ($recommendation_result->num_rows > 0) {
        echo "<h2>Recommended Properties:</h2>";
        echo "<table id='recommendationsTable'>";
        echo "<thead><tr><th>State</th><th>District</th><th>Place</th><th>Price</th><th>View</th></tr></thead>";
        echo "<tbody>";
        while ($row = $recommendation_result->fetch_assoc()) {
            echo "<tr><td>" . $row["state"] . "</td><td>" . $row["district"] . "</td><td>" . $row["place"] . "</td><td>" . $row["price"] . "</td><td><a href='property_details.php?property_id=" . $row["property_id"] . "' class='btn btn-info'>View</a></td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No recommendations available.";
    }

    $conn->close();
}
?>
    </div>
    <script>
        $(document).ready(function() {
            $('#propertiesTable').DataTable();
            $('#recommendationsTable').DataTable();
        });
    </script>
</body>
</html>
