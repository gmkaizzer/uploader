<?php
// Database connection settings
$servername = "localhost";
$username = "uploader_user";  // Replace with your actual MySQL username
$password = "Christian30$$";  // Replace with your actual password
$database = "uploader_db";    // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Log error to a file (for production environments, avoid showing it to users)
    error_log("Connection failed: " . $conn->connect_error, 3, "error_log.txt");
    
    // Return a generic error message to the user
    die("Connection failed. Please try again later.");
} else {
    echo "Connected successfully";
}
?>
