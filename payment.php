<?php
include('db_config.php');

// Check if user is logged in and is a poster
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'poster') {
    header("Location: index.php");
    exit();
}

// Get gig ID from URL
$gig_id = $_GET['gig_id'];
$posting_fee = isset($_GET['fee']) ? $_GET['fee'] : 200; // Fixed posting fee of 200

// Get gig details
$query = "SELECT * FROM gigs WHERE id='$gig_id'";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$gig = mysqli_fetch_assoc($result);

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_name = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];
    $user_id = $_SESSION['user_id'];
    $amount = $posting_fee; // Use fixed posting fee, not the gig price
    
    if ($card_name && $card_number && $expiry && $cvv) {
        // Insert payment record
        $payment_query = "INSERT INTO payments (gig_id, user_id, card_name, card_number, amount) VALUES ('$gig_id', '$user_id', '$card_name', '$card_number', '$amount')";
        $payment_result = mysqli_query($connection, $payment_query);
        
        if ($payment_result) {
            // Redirect to success page
            header("Location: payment_success.php?gig_id=$gig_id");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Boarding Gigs</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
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
        <div class="payment-container">
            <h1>Payment Gateway</h1>
            
            <div class="payment-info">
                <h3>Gig Posting Fee</h3>
                <p><strong>Title:</strong> <?php echo $gig['title']; ?></p>
                <p><strong>Location:</strong> <?php echo $gig['location']; ?></p>
                <p><strong>Monthly Rent:</strong> <span style="color: #555;">Rs. <?php echo number_format($gig['price'], 2); ?>/month</span></p>
                <p><strong>Posting Fee:</strong> <span class="price-highlight">Rs. <?php echo number_format($posting_fee, 2); ?></span></p>
                <p class="small-text">Pay the posting fee to activate your gig listing</p>
            </div>
            
            <div class="payment-modal">
                <h3>Enter Payment Details</h3>
                
                <form method="POST" action="payment.php?gig_id=<?php echo $gig_id; ?>" id="paymentForm">
                    <div class="form-group">
                        <label>Cardholder Name:</label>
                        <input type="text" name="card_name" id="card_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Card Number:</label>
                        <input type="text" name="card_number" id="card_number" maxlength="16" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Expiry Date (MM/YY):</label>
                            <input type="text" name="expiry" id="expiry" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        
                        <div class="form-group">
                            <label>CVV:</label>
                            <input type="text" name="cvv" id="cvv" maxlength="3" required>
                        </div>
                    </div>
                    
                    <div class="payment-actions">
                        <button type="submit" class="btn btn-primary btn-large">Pay Rs. <?php echo number_format($posting_fee, 2); ?></button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
                
                <div class="payment-security">
                    <p>🔒 Secure Payment Gateway</p>
                    <p class="small-text">Your payment information is safe and encrypted</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
