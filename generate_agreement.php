<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if TCPDF exists
if (!file_exists('vendor/autoload.php')) {
    die("Error: TCPDF not installed. Please run 'composer require tecnickcom/tcpdf'");
}

require_once 'vendor/autoload.php';
use TCPDF;

class PropertyAgreement extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, 'PROPERTY SALE AGREEMENT', 0, true, 'C');
        $this->Ln(10);
    }
}

function generateAgreement($property_id) {
    try {
        // Create agreements directory if it doesn't exist
        if (!file_exists('agreements')) {
            mkdir('agreements', 0777, true);
        }

        // Connect to database
        $conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Fetch property and owner details
        $sql = "SELECT p.*, u.username, u.email, u.phone 
                FROM properties p 
                JOIN users u ON p.user_id = u.user_id 
                WHERE p.property_id = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $property_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if (!$data) {
            throw new Exception("Property not found");
        }

        // Create PDF
        $pdf = new PropertyAgreement();
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Current date
        $date = date('d/m/Y');

        // Agreement content
        $content = <<<EOD
        THIS AGREEMENT is made on {$date}

        SELLER DETAILS:
        Name: {$data['username']}
        Email: {$data['email']}
        Phone: {$data['phone']}

        PROPERTY DETAILS:
        Location: {$data['place']}
        District: {$data['district']}
        State: {$data['state']}
        Size: {$data['size']} cent
        Price: ₹{$data['price']}

        TERMS AND CONDITIONS:

        1. PROPERTY DESCRIPTION
        The seller agrees to sell the above-mentioned property with all its existing conditions.

        2. PURCHASE PRICE
        The agreed purchase price is ₹{$data['price']} (Indian Rupees).

        3. PROPERTY INSPECTION
        The buyer has the right to inspect the property thoroughly before finalizing the purchase.

        4. SECURITY MEASURES
        - Property is legally verified
        - All necessary permits are obtained
        - Property boundaries are clearly marked
        - No pending legal disputes

        5. PAYMENT TERMS
        - Initial deposit: 10% of the total amount
        - Remaining payment: To be made at the time of registration

        Signatures:

        _____________________                    _____________________
        Seller's Signature                       Buyer's Signature

        Date: _____________                      Date: _____________
        EOD;

        $pdf->writeHTML($content, true, false, true, false, '');

        // Generate unique filename
        $filename = 'agreement_' . $property_id . '_' . date('YmdHis') . '.pdf';
        $filepath = __DIR__ . '/agreements/' . $filename;

        // Save PDF
        $pdf->Output($filepath, 'F');
        
        if (!file_exists($filepath)) {
            throw new Exception("Failed to create PDF file");
        }

        return ['success' => true, 'filename' => $filename];

    } catch (Exception $e) {
        error_log("Agreement generation error: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Handle the request
if (isset($_GET['property_id'])) {
    $property_id = $_GET['property_id'];
    $result = generateAgreement($property_id);
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>
