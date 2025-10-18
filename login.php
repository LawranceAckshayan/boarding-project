<?php
include('db_config.php');

$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if ($email && $password) {
        $hashed_password = md5($password);
        
        // Check user credentials
        $query = "SELECT * FROM users WHERE email='$email' AND password='$hashed_password'";
        $result = mysqli_query($connection, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            
            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Boarding Gigs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Login</h1>
            
            <?php if ($error) { ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php } ?>
            
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
