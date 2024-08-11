<?php
require('config.php'); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and validate input data
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $profileImage = $_FILES['profile_image']['name'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, fullname, email, password, phone, address, photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssss", $username, $name, $email, $hashedPassword, $phone, $address, $profileImage);

        if ($stmt->execute()) {
            // Move the uploaded file to the target directory
            $targetDir = "./assets/img/Upload/";
            $targetFilePath = $targetDir . $profileImage;
            move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath);

            // Redirect to index.php after successful registration
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL: " . $conn->error;
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RealEstiMate</title>
    <style>
        /* Your CSS styles similar to the login page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>
    <script>
        $(document).ready(function() {
            $("#registerForm").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 6
                    },
                    name: {
                        required: true,
                        minlength: 5,
                        alpha: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                    },
                    address: {
                        required: true,
                        maxlength: 50
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        mypassword: true
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    },
                    profile_image: {
                        required: true,
                        extension: "png|jpeg|jpg"
                    }
                },
                messages: {
                    username: {
                        required: "Please enter a username",
                        minlength: "Username must be at least 6 characters long"
                    },
                    name: {
                        required: "Please enter your full name",
                        minlength: "Full name must be at least 5 characters long"
                    },
                    phone: {
                        required: "Please enter your phone number",
                        digits: "Please enter a valid phone number",
                        minlength: "Phone number must be exactly 10 digits long",
                        maxlength: "Phone number must be exactly 10 digits long",
                    },
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address.",
                    },
                    address: {
                        required: "Please enter your address",
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Password must be at least 8 characters long"
                    },
                    confirm_password: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    profile_image: {
                        required: "Please upload your profile image",
                        extension: "Only png and jpeg files are allowed"
                    },
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });

            $.validator.addMethod("alpha", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            }, "Alphabetic characters only");

            $.validator.addMethod('mypassword', function(value, element) {
                return this.optional(element) || (value.match(/[a-zA-Z]/) && value.match(/[0-9]/));
            }, 'Password must contain at least one numeric and one alphabetic character.');
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form id="registerForm" action="register.php" method="post" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="file" name="profile_image" required>
            <input type="submit" value="Register">
        </form>
        <div class="login-link">
            <a href="index.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>
