<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

$host = '127.0.0.1'; // Database host
$db = 'miniproj'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = "All fields are required.";
    } elseif ($username == "admin" && $password == "admin1234") {
        $_SESSION['username'] = $username;
        header("Location: admin_dash.php"); // Redirecting to index.html as your template
        exit();
    } else {
        $sql = "SELECT user_id, username, password, is_active FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $username, $hashed_password, $is_active);
            $stmt->fetch();
            if ($is_active == 0) {
                $message = "Your account is deactivated. Please contact support.";
            } elseif (password_verify($password, $hashed_password)) {
                // Secure the session by regenerating the session ID
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header("Location: index1.php"); // Redirecting to index.html as your template
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "No account found with that username.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RealEstiMate</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        h1 {
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #667eea;
            outline: none;
        }

        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }

        .btn {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right, #764ba2, #667eea);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .form-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
        }

        .form-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: #764ba2;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (!empty($message)): ?>
            <div class="error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" action="index.php"> <!-- Keeps form action to index.php -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login" class="btn">
        </form>
        <div class="form-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>

</html>