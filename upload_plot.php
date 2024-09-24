<!-- upload_plot.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Plot - RealEstiMate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Upload Plot Details</h1>
    <form action="save_plot.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="transaction_type" value="<?php echo $_GET['transaction_type']; ?>">

        <div class="form-group">
            <label for="place">Place:</label>
            <input type="text" name="place" id="place" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="state">State:</label>
            <input type="text" name="state" id="state" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="plot_size">Plot Size (in cents):</label>
            <input type="text" name="plot_size" id="plot_size" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" name="price" id="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="photo">Plot Image:</label>
            <input type="file" name="photo" id="photo" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload Plot</button>
    </form>
</div>
</body>
</html>
