<?php
session_start();
require 'dbconnect.php'; // Include database connection

function generateUserId($conn) {
    do {
        $user_id = rand(10000000, 99999999); // Generate an 8-digit random ID
        $query = "SELECT user_id FROM users WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $query);
    } while (mysqli_num_rows($result) > 0); // Ensure ID is unique
    return $user_id;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Signup Handling
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already registered. Try logging in.'); window.location.href='index.html';</script>";
        } else {
            // Generate a unique user_id
            $user_id = generateUserId($conn);
            
            // Insert new user into database
            $insert_query = "INSERT INTO users (user_id, username, email, password_hash) VALUES ('$user_id', '$username', '$email', '$hashed_password')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<script>alert('Signup successful! Please login.'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Signup failed. Try again later.'); window.location.href='index.php';</script>";
            }
        }
    } elseif (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {
        // Login Handling
        $loginUsername = mysqli_real_escape_string($conn, $_POST['loginUsername']);
        $loginPassword = mysqli_real_escape_string($conn, $_POST['loginPassword']);

        // Check if user exists
        $query = "SELECT * FROM users WHERE email = '$loginUsername' OR username = '$loginUsername'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($loginPassword, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                echo "<script>alert('Login successful!'); window.location.href='home.php';</script>";
            } else {
                echo "<script>alert('Invalid password. Try again.'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('User not found. Please sign up.'); window.location.href='index.php';</script>";
        }
    }
}
?>
