<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if property_id is passed
if (!isset($_GET['property_id'])) {
    die("Property ID is not specified.");
}

$property_id = $_GET['property_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch property and owner details
$sql = "SELECT p.*, u.username, u.email, u.phone 
        FROM properties p 
        JOIN users u ON p.user_id = u.user_id 
        WHERE p.property_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Agreement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background: #f8f9fa;
        }

        .agreement-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #2c5282;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            color: #2c5282;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #2c5282;
            padding-bottom: 5px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            color: #4a5568;
        }

        .value {
            color: #2d3748;
        }

        .terms {
            margin: 20px 0;
        }

        .terms li {
            margin-bottom: 10px;
        }

        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            text-align: center;
        }

        .date {
            text-align: right;
            margin-bottom: 30px;
        }

        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2c5282;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .print-button:hover {
            background: #1a365d;
            transform: translateY(-2px);
        }

        @media print {
            .print-button {
                display: none;
            }
            body {
                padding: 0;
                background: white;
            }
            .agreement-container {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="agreement-container">
        <div class="header">
            <h1>PROPERTY SALE AGREEMENT</h1>
            <div class="date">Date: <?php echo date('d/m/Y'); ?></div>
        </div>

        <div class="section">
            <h2 class="section-title">SELLER DETAILS</h2>
            <div class="details-grid">
                <span class="label">Name:</span>
                <span class="value"><?php echo htmlspecialchars($data['username']); ?></span>
                
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($data['email']); ?></span>
                
                <span class="label">Phone:</span>
                <span class="value"><?php echo htmlspecialchars($data['phone']); ?></span>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">PROPERTY DETAILS</h2>
            <div class="details-grid">
                <span class="label">Location:</span>
                <span class="value"><?php echo htmlspecialchars($data['place']); ?></span>
                
                <span class="label">District:</span>
                <span class="value"><?php echo htmlspecialchars($data['district']); ?></span>
                
                <span class="label">State:</span>
                <span class="value"><?php echo htmlspecialchars($data['state']); ?></span>
                
                <span class="label">Size:</span>
                <span class="value"><?php echo htmlspecialchars($data['size']); ?> cent</span>
                
                <span class="label">Price:</span>
                <span class="value">₹<?php echo htmlspecialchars($data['price']); ?></span>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">TERMS AND CONDITIONS</h2>
            <ol class="terms">
                <li>The seller agrees to sell the above-mentioned property with all its existing conditions.</li>
                <li>The property is free from all encumbrances and legal disputes.</li>
                <li>All necessary documents will be provided by the seller.</li>
                <li>Property boundaries are clearly marked and documented.</li>
                <li>The buyer has the right to inspect the property thoroughly.</li>
                <li>Initial deposit: 10% of the total amount.</li>
                <li>Remaining payment to be made at the time of registration.</li>
                <li>All legal formalities must be completed within 30 days.</li>
            </ol>
        </div>

        <div class="signature-section">
            <div>
                <div class="signature-line">Seller's Signature</div>
            </div>
            <div>
                <div class="signature-line">Buyer's Signature</div>
            </div>
        </div>
    </div>

    <button class="print-button" onclick="window.print()">Print Agreement</button>

    <script>
        // Add any additional JavaScript if needed
    </script>
</body>
</html>
