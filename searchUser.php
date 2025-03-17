<?php
require_once('dbconnect.php');

if (isset($_POST['query'])) {
    $searchQuery = trim($_POST['query']);

    // Prevent SQL Injection
    $stmt = $conn->prepare("SELECT user_id, username, email, profile_picture FROM users WHERE username LIKE ? OR email LIKE ?");
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="search-result-item">';
            echo '<img src="" alt="Profile Picture">';
            echo '<p><strong>Name:</strong> ' . htmlspecialchars($row['name']) . '</p>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
            echo '<p><strong>UN:</strong> <h1 style="display: inline; color: red; font-size: 24px;">' . htmlspecialchars($row['unique_number']) . '</h1></p>';
            echo '<button class="follow-button" style="background-color: blue; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">+</button>';
            echo '</div>';
        }
    } else {
        echo "<p>No results found</p>";
    }

    $stmt->close();
}
?>
