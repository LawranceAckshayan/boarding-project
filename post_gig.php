<?php
include('db_config.php');

// Check if user is logged in and is a poster
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'poster') {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $conditions = $_POST['conditions'];
    $phone_number = $_POST['phone_number'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];
    
    // Handle photo upload
    $photo_path = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = "uploads/";
        $file_name = time() . "_" . $_FILES['photo']['name'];
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_path = $target_file;
        }
    }
    
    if ($title && $description && $location && $conditions && $phone_number && $price) {
        // Insert gig into database
        $query = "INSERT INTO gigs (user_id, title, description, location, conditions, phone_number, photo, price) VALUES ('$user_id', '$title', '$description', '$location', '$conditions', '$phone_number', '$photo_path', '$price')";
        $result = mysqli_query($connection, $query);
        
        if ($result) {
            $gig_id = mysqli_insert_id($connection);
            // Redirect to payment page with fixed posting fee of 200
            header("Location: payment.php?gig_id=$gig_id&fee=200");
            exit();
        } else {
            $error = "Failed to post gig. Please try again.";
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Gig - Boarding Gigs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h2>Boarding Gigs</h2>
            <div class="nav-links">
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="form-box-wide">
            <h1>Post a New Gig</h1>
            
            <?php if ($error) { ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php } ?>
            
            <?php if ($success) { ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php } ?>
            
            <form method="POST" action="post_gig.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Gig Title:</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Location:</label>
                    <input type="text" name="location" required>
                </div>
                
                <div class="form-group">
                    <label>Conditions:</label>
                    <textarea name="conditions" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="tel" name="phone_number" placeholder="07XXXXXXXX" required>
                </div>
                
                <div class="form-group">
                    <label>Monthly Rent (Rs.):</label>
                    <input type="number" name="price" step="0.01" required>
                    <small style="color: #7f8c8d;">Enter the monthly rent amount</small>
                </div>
                
                <div class="form-group">
                    <label>Photo:</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
                
                <button type="submit" class="btn btn-primary">Post Gig</button>
            </form>
        </div>
    </div>
</body>
</html>
