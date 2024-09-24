<?php
session_start();
error_reporting(E_ERROR | E_PARSE);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RealEstiMate</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            display: flex;
            width: 100%;
            background: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background: #0066cc;
            color: #fff;
            padding: 30px 20px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            font-weight: bold;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 50px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            transition: color 0.3s;
        }

        .sidebar ul li a:hover {
            color: #cce7ff;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            background-color: #f0f2f5;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .main-content h1 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #0066cc;
        }

        .content-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #e0e0e0;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background: #f0f2f5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="#" id="dashboardLink">Dashboard</a></li>
                <li><a href="#" id="usersLink">Users</a></li>
                <li><a href="#" id="propertiesLink">Properties</a></li>
                <li><a href="#" id="inquiriesLink">Inquiries</a></li>
                <li><a href="#" id="financeLink">Finance</a></li>
                <li><a href="./logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div id="dashboard" class="content-section">
                <h1>Welcome to Admin Dashboard</h1>
                <p>Select an option from the sidebar to manage the respective sections.</p>
            </div>
            <div id="users" class="content-section" style="display:none;">
                <h1>Manage Users</h1>
                <?php
                $conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Handle user deletion (soft delete)
                if (isset($_GET['delete_user_id'])) {
                    $delete_user_id = $_GET['delete_user_id'];
                    $sql_delete = "UPDATE users SET is_active = 0 WHERE user_id = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $delete_user_id);
                    $stmt_delete->execute();
                    $stmt_delete->close();
                } ?>
                <script>
                    $(document).ready(function() {
                        $('.content-section').hide();
                        $('#users').show();
                    });
                </script>
                <?php

                // Display active users
                $sql = "SELECT * FROM users";
$result = $conn->query($sql);
$i = 0;
if ($result->num_rows > 0) {
    echo "<table><tr><th>Sl no:</th><th>Username</th><th>Email</th><th>Phone</th><th>Address</th><th>Profile</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $i = $i + 1;
        $status = $row["is_active"] ? "Active" : "Inactive";
        echo "<tr>
            <td>" . $i . "</td>
            <td>" . $row["username"] . "</td>
            <td>" . $row["email"] . "</td>
            <td>" . $row["phone"] . "</td>
            <td>" . $row["address"] . "</td>
            <td><img width='100' height='100' src='./uploads/" . $row["photo"] . "'></td>
            <td>" . $status . "</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No users found.";
}

                $conn->close();
                ?>
            </div>
            <div id="properties" class="content-section" style="display:none;">
    <h1>Manage Properties</h1>
    <?php
    $conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle property deletion
    if (isset($_GET['delete_property_id'])) {
        $delete_property_id = $_GET['delete_property_id'];
        $sql_delete = "DELETE FROM properties WHERE property_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $delete_property_id);
        $stmt_delete->execute();
        $stmt_delete->close();
    }

    $sql = "SELECT * FROM properties";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table><tr><th>City</th><th>State</th><th>Size(in cent)</th><th>Image</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>"  . $row["place"] . "</td><td>" . $row["state"] . "</td><td>" . $row["size"] . "</td><td><img src='./uploads/" . $row["photo"] . "' width='100'></td>
            <td>
                <a href='property_details.php?property_id=" . $row["property_id"] . "'>View</a>
            </td></tr>";
        }
        echo "</table>";
    } else {
        echo "No properties found.";
    }

    $conn->close();
    ?>
</div>

            <div id="inquiries" class="content-section" style="display:none;">
                <h1>Manage Inquiries</h1>
                <?php
                $conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM inquiries";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>ID</th><th>List ID</th><th>User ID</th><th>Message</th><th>Status</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["inquiry_id"] . "</td><td>" . $row["list_id"] . "</td><td>" . $row["user_id"] . "</td><td>" . $row["message"] . "</td><td>" . $row["status"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
            </div>
            <div id="finance" class="content-section" style="display:none;">
                <h1>Manage Finance</h1>
                <?php
                $conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM finance";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>ID</th><th>Property ID</th><th>Type</th><th>Amount</th><th>Date</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["finance_id"] . "</td><td>" . $row["property_id"] . "</td><td>" . $row["type"] . "</td><td>" . $row["amount"] . "</td><td>" . $row["date"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dashboardLink').click(function() {
                $('.content-section').hide();
                $('#dashboard').show();
            });
            $('#usersLink').click(function() {
                $('.content-section').hide();
                $('#users').show();
            });
            $('#propertiesLink').click(function() {
                $('.content-section').hide();
                $('#properties').show();
            });
            $('#inquiriesLink').click(function() {
                $('.content-section').hide();
                $('#inquiries').show();
            });
            $('#financeLink').click(function() {
                $('.content-section').hide();
                $('#finance').show();
            });
        });
    </script>
</body>

</html>