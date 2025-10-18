<?php
include('db_config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$gig_id = $_GET['gig_id'];

// Get gig details
$query = "SELECT * FROM gigs WHERE id='$gig_id'";
$result = mysqli_query($connection, $query);
$gig = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Boarding Gigs</title>
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
        <div class="success-container">
            <div class="success-icon">✓</div>
            <h1>Payment Successful!</h1>
            
            <div class="success-message">
                <p>Your payment has been processed successfully.</p>
                <p>Your gig <strong><?php echo $gig['title']; ?></strong> is now active and visible to students!</p>
            </div>
            
            <div class="success-details">
                <h3>Transaction Details</h3>
                <p><strong>Posting Fee Paid:</strong> Rs. 200.00</p>
                <p><strong>Monthly Rent Listed:</strong> Rs. <?php echo number_format($gig['price'], 2); ?>/month</p>
                <p><strong>Transaction Date:</strong> <?php echo date('F j, Y - h:i A'); ?></p>
                <p><strong>Status:</strong> <span class="status-success">Completed</span></p>
            </div>
            
            <div class="success-actions">
                <a href="index.php" class="btn btn-primary">View All Gigs</a>
                <a href="post_gig.php" class="btn btn-secondary">Post Another Gig</a>
            </div>
        </div>
    </div>
</body>
</html>
