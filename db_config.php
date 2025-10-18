<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$port = "3306";
$db_name = "boarding_gigs";

// Connect to database using mysqli_connect
$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $port);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Start session for user authentication
session_start();
?>
