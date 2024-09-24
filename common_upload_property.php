<!-- common_upload_property.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Property Type - RealEstiMate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Upload Property</h1>
    <form action="select_property_type.php" method="POST" class="mt-4">
        <div class="form-group">
            <label for="property_type">Property Type:</label>
            <select name="property_type" id="property_type" class="form-control" required>
                <option value="">Select Property Type</option>
                <option value="plot">Plot</option>
                <option value="house">House</option>
            </select>
        </div>

        <div class="form-group">
            <label for="transaction_type">Transaction Type:</label>
            <select name="transaction_type" id="transaction_type" class="form-control" required>
                <option value="">Select Transaction Type</option>
                <option value="rent">Rent</option>
                <option value="sale">Sale</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Next</button>
    </form>
</div>
</body>
</html>
