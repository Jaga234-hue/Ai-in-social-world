<?php
$host = "localhost"; // Change if using a different server
$user = "root"; // Default XAMPP username
$pass = ""; // Default XAMPP password (empty)
$dbname = "ai-social-world"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

echo "Connected successfully";
?>
