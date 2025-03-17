<?php
require_once('dbconnect.php');
// Check if cookies are set before accessing them
// default

$user_id = 0000;
$username = 'username';
$email = 'email@gmail.com';

if (isset($_COOKIE["username"]) || isset($_COOKIE["email"])) {
    $username = isset($_COOKIE["username"]) ? $_COOKIE["username"] : null;
    $email = isset($_COOKIE["email"]) ? $_COOKIE["email"] : null;

    $query = "SELECT user_id, username, email FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user["user_id"];
        $username = $user["username"];
        $email = $user["email"];
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Cookies not set";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Interface</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #3b5998;
            color: white;
        }

        .profile-icn {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dfe3ee;
        }

        .profile-name {
            font-weight: bold;
        }

        .search-bar input {
            padding: 8px;
            border-radius: 20px;
            border: none;
            width: 200px;
        }

        .menu {
            display: none;
            cursor: pointer;
        }

        /* Body Styles */
        .body {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .left {
            width: 250px;
            background: #f7f7f7;
            padding: 1rem;
            transition: transform 0.3s;
        }

        .right {
            flex: 1;
            background: #e9ebee;
            padding: 1rem;
        }

        /* Footer Styles */
        .footer {
            background: #fff;
            padding: 1rem;
            border-top: 1px solid #ddd;
        }

        /* Left Panel Sections */
        .request {
            background: #fff;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .friend-list {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .setting {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .left {
                position: fixed;
                top: 0;
                left: -250px;
                bottom: 0;
                z-index: 100;
            }

            .left.active {
                transform: translateX(250px);
            }

            .menu {
                display: block;
            }

            .profile-name {
                display: none;
            }
        }

        /* Color Coding for Identification */
        .header {
            background: #3b5998;
        }

        .left {
            background: #dfe3ee;
        }

        .right {
            background: #f0f2f5;
        }

        .footer {
            background: #fff;
        }

        .profile-pic {
            background: #8b9dc3;
        }

        .search-bar {
            background: #8b9dc3;
        }

        .request {
            background: #fff;
        }

        .friend-list {
            background: #fff;
        }

        .setting {
            background: #fff;
        }

        .profileDetails {
            position: fixed;
            top: -100%;
            /* Start hidden above */
            left: 0;
            /* Stay on the left side */
            width: 300px;
            /* Adjust width as needed */
            background: red;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            transition: top 0.5s ease-in-out;
            /* Animate only top */
            z-index: 1000;
        }

        /* When active, slide down into view */
        .profileDetails.active {
            top: 10%;
            /* Moves to visible position */
        }

        /* Styling for the profile picture container */
        .profilePic {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
            border: 3px solid #fff;
            margin: 0 auto 10px;
        }

        /* Styling for the uploaded image */
        .profilePic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Styling for file input (hidden but clickable) */
        #profilePicInput {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* User ID */
        .user-id {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            text-align: center;
            background: #ddd;
            padding: 5px 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Username */
        .profileName {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            text-align: center;
            margin-bottom: 5px;
        }

        /* Email */
        .profile-email {
            font-size: 14px;
            color: #ddd;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="profile-icn" id="profileIcn" onclick="toggleProfile()">
                <div class="profile-pic"></div>
                <div class="profile-name"><?php echo $username ?></div>
            </div>
            <div class="profileDetails" id="profileDetails" style="display: none;">
                <div class="profilePic" id="profilePic">
                    
                </div>
                <div class="user-id" id="userId"><?php echo $user_id ?></div>
                <div class="profileName" id="profileName"><?php echo $username ?></div>
                <div class="profile-email" id="profileEmail"><?php echo $email ?></div>
                <label for="bio" id="bioLabel">Bio: </label>
                <div class="edit-profile" id="editProfile" onclick="editProfile()" style="cursor:pointer">✏️</div>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search users by username or id" onkeyup="searchUsers()">
            </div>
            <div class="search-div" id="searchDiv" style="display: none;">
                <div class="search-user" id="searchUser"></div>
            </div>
            <div class="menu" onclick="toggleMenu()">
                <h1>☰</h1>
            </div>
        </div>

        <div class="body">
            <div class="left" id="leftPanel">
                <div class="request">
                    <h3>Requests</h3>
                    <div class="sent">Sent: 2</div>
                    <div class="received">Received: 3</div>
                </div>
                <div class="friend-list">
                    <h3>Friends</h3>
                    <div class="friend">Alice Smith</div>
                    <div class="friend">Bob Johnson</div>
                </div>
                <div class="setting">
                    <h3>Settings</h3>
                    <div>Privacy</div>
                    <div>Account</div>
                </div>
            </div>

            <div class="right">
                <div class="post-section">
                    <h2>Posts</h2>
                    <div class="post">Latest updates appear here...</div>
                </div>
                <div class="chat-section">
                    <h2>Chats</h2>
                    <div class="chat">Recent messages...</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <input type="text" placeholder="Type a message..." style="width: 100%; padding: 8px;">
        </div>
    </div>

    <script>
        function toggleMenu() {
            const leftPanel = document.getElementById('leftPanel');
            leftPanel.classList.toggle('active');
        }

        function toggleProfile() {
            const profileDetails = document.getElementById('profileDetails');

            if (profileDetails.style.display === 'none' || profileDetails.style.display === '') {
                profileDetails.style.display = 'block'; // Make it visible
                setTimeout(() => {
                    profileDetails.classList.add('active'); // Trigger animation
                }, 10);
            } else {
                profileDetails.classList.remove('active'); // Hide with animation
                setTimeout(() => {
                    profileDetails.style.display = 'none'; // Hide completely after animation
                }, 500); // Matches CSS transition duration
            }
        }

        document.getElementById("profilePicInput").addEventListener("change", function(event) {
            const file = event.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader(); // Create a FileReader
                reader.onload = function(e) {
                    document.getElementById("profilePicImg").src = e.target.result; // Set image source
                };
                reader.readAsDataURL(file); // Read file as a Data URL
            }
        });

        function searchUser() {
            let query = document.getElementById("searchInput").value.trim();

            if (query.length === 0) {
                document.getElementById("searchResults").innerHTML = ""; // Clear results if empty
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "searchUser.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("searchResults").innerHTML = xhr.responseText;
                }
            };

            xhr.send("query=" + encodeURIComponent(query));
        }
    </script>
</body>

</html>