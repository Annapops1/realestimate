<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Finance - RealEstiMate</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="properties.php">Properties</a></li>
                <li><a href="inquiries.php">Inquiries</a></li>
                <li><a href="finance.php">Finance</a></li>
                <li><a href="../index.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Manage Finance</h1>
            <!-- Fetch and display finance records from the database -->
            <?php
            // Database connection
            $host = '127.0.0.1'; 
            $db = 'miniproj'; 
            $user = 'root'; 
            $pass = ''; 

            $conn = new mysqli($host, $user, $pass, $db);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM finance";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Property ID</th><th>Type</th><th>Amount</th><th>Date</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["financial_id"]. "</td><td>" . $row["property_id"]. "</td><td>" . $row["metric_type"]. "</td><td>" . $row["amount"]. "</td><td>" . $row["record_date"]. "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
