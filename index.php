<?php
// Start the session BEFORE any output
session_start();

include('db_config.php'); // expects $connection (mysqli)

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Build and run query (use id DESC if created_at doesn't exist)
$sql = "
    SELECT 
        gigs.id,
        gigs.title,
        gigs.description,
        gigs.location,
        gigs.photo,
        gigs.price,
        gigs.status,
        users.username
    FROM gigs
    JOIN users ON gigs.user_id = users.id
    WHERE gigs.status = 'active'
    ORDER BY gigs.id DESC
";

$result = mysqli_query($connection, $sql);

// Helper to safely shorten text
function shorten_text($text, $len = 100) {
    $clean = trim(strip_tags((string)$text));
    if (function_exists('mb_substr')) {
        return mb_strlen($clean) > $len ? mb_substr($clean, 0, $len) . '…' : $clean;
    }
    return strlen($clean) > $len ? substr($clean, 0, $len) . '…' : $clean;
}

// Safe echo helper
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// Pull some session values safely
$username   = $_SESSION['username']   ?? 'User';
$user_type  = $_SESSION['user_type']  ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boarding Gigs - Home</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Hi Welcome</h1>
    <nav class="navbar">
        <div class="nav-container">
            <h2>Boarding Gigs</h2>
            <div class="nav-links">
                <span>Welcome, <?php echo h($username); ?></span>
                <?php if ($user_type === 'poster') { ?>
                    <a href="post_gig.php" class="btn">Post a Gig</a>
                <?php } ?>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Available Boarding Gigs</h1>

        <div class="gigs-grid">
            <?php if ($result && mysqli_num_rows($result) > 0) { ?>
                <?php while ($gig = mysqli_fetch_assoc($result)) { 
                    $title       = h($gig['title'] ?? '');
                    $location    = h($gig['location'] ?? '');
                    $price       = isset($gig['price']) ? number_format((float)$gig['price'], 2) : '0.00';
                    $description = shorten_text($gig['description'] ?? '', 120);
                    $poster      = h($gig['username'] ?? 'Unknown');
                    $photo       = trim((string)($gig['photo'] ?? ''));
                    $photoSafe   = h($photo);
                    $idSafe      = (int)($gig['id'] ?? 0);
                ?>
                    <div class="gig-card">
                        <?php if ($photo !== '') { ?>
                            <img src="<?php echo $photoSafe; ?>" alt="Gig Photo" class="gig-image" />
                        <?php } else { ?>
                            <div class="gig-image-placeholder">No Image</div>
                        <?php } ?>

                        <div class="gig-content">
                            <h3><?php echo $title; ?></h3>
                            <p class="gig-location">📍 <?php echo $location; ?></p>
                            <p class="gig-price">Rs. <?php echo $price; ?>/month</p>
                            <p class="gig-description"><?php echo h($description); ?></p>
                            <p class="gig-poster">Posted by: <?php echo $poster; ?></p>

                            <div class="gig-actions">
                                <a href="view_gig.php?id=<?php echo $idSafe; ?>" class="btn">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="no-gigs">No gigs available at the moment.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>
