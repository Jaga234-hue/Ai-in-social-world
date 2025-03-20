<?php
require_once 'dbconnect.php';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];//message
}
echo $message . '<br>'; 
if (isset($_COOKIE['opponent_id'])) {
    $opponent_id = $_COOKIE['opponent_id'];//receiver_id
}

if (!isset($_COOKIE['username']) && !isset($_COOKIE['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit;
}

// Get logged-in user's ID
$loginUsername = isset($_COOKIE['username']) ? $_COOKIE['username'] : $_COOKIE['email'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $loginUsername, $loginUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$loggedInUserId = $user['user_id'];//sender_id



$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $loggedInUserId, $opponent_id, $message);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
$stmt->close();
$conn->close();


?>