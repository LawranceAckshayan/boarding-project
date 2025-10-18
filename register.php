<?php
include('db_config.php');

$error = "";
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];
    
    if ($username && $email && $password && $user_type) {
        // Hash password
        $hashed_password = md5($password);
        
        // Insert user into database
        $query = "INSERT INTO users (username, email, password, user_type) VALUES ('$username', '$email', '$hashed_password', '$user_type')";
        $result = mysqli_query($connection, $query);
        
        if ($result) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Please try again.";
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
    <title>Register - Boarding Gigs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Register</h1>
            
            <?php if ($error) { ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php } ?>
            
            <?php if ($success) { ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php } ?>
            
            <form method="POST" action="register.php">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>I am a:</label>
                    <select name="user_type" required>
                        <option value="">Select...</option>
                        <option value="poster">Gig Poster</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Register</button>
            </form>
            
            <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
