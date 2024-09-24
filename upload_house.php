<!-- upload_house.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload House - RealEstiMate</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Upload House Details</h1>
    <form action="save_house.php" method="POST" enctype="multipart/form-data">
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
            <label for="house_size">House Size (in square feet):</label>
            <input type="text" name="house_size" id="house_size" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="num_bedrooms">Number of Bedrooms:</label>
            <input type="number" name="num_bedrooms" id="num_bedrooms" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="num_bathrooms">Number of Bathrooms:</label>
            <input type="number" name="num_bathrooms" id="num_bathrooms" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="furnishing">Furnishing:</label>
            <select name="furnishing" id="furnishing" class="form-control" required>
                <option value="">Select Furnishing</option>
                <option value="furnished">Furnished</option>
                <option value="semi-furnished">Semi-Furnished</option>
                <option value="unfurnished">Unfurnished</option>
            </select>
        </div>

        <div class="form-group">
            <label for="amenities">Amenities:</label>
            <textarea name="amenities" id="amenities" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea name="address" id="address" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" name="price" id="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="photo">House Image:</label>
            <input type="file" name="photo" id="photo" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload House</button>
    </form>
</div>
</body>
</html>
