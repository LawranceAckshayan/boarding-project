<?php
include('db_config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get gig ID from URL
$gig_id = $_GET['id'];

// Get gig details
$query = "SELECT gigs.*, users.username, users.email FROM gigs JOIN users ON gigs.user_id = users.id WHERE gigs.id='$gig_id'";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$gig = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $gig['title']; ?> - Boarding Gigs</title>
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
        <div class="gig-detail">
            <h1><?php echo $gig['title']; ?></h1>
            
            <?php if ($gig['photo']) { ?>
                <img src="<?php echo $gig['photo']; ?>" alt="Gig Photo" class="gig-detail-image">
            <?php } ?>
            
            <div class="gig-info">
                <div class="info-row">
                    <strong>Location:</strong>
                    <span>📍 <?php echo $gig['location']; ?></span>
                </div>
                
                <div class="info-row">
                    <strong>Monthly Rent:</strong>
                    <span class="price-highlight">Rs. <?php echo number_format($gig['price'], 2); ?>/month</span>
                </div>
                
                <div class="info-row">
                    <strong>Posted by:</strong>
                    <span><?php echo $gig['username']; ?> (<?php echo $gig['email']; ?>)</span>
                </div>
                
                <div class="info-row">
                    <strong>Contact Phone:</strong>
                    <span>📞 <?php echo $gig['phone_number']; ?></span>
                </div>
                
                <div class="info-row">
                    <strong>Posted on:</strong>
                    <span><?php echo date('F j, Y', strtotime($gig['created_at'])); ?></span>
                </div>
            </div>
            
            <div class="section">
                <h3>Description</h3>
                <p><?php echo nl2br($gig['description']); ?></p>
            </div>
            
            <div class="section">
                <h3>Conditions</h3>
                <p><?php echo nl2br($gig['conditions']); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
