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
        .header { background: #3b5998; }
        .left { background: #dfe3ee; }
        .right { background: #f0f2f5; }
        .footer { background: #fff; }
        .profile-pic { background: #8b9dc3; }
        .search-bar { background: #8b9dc3; }
        .request { background: #fff; }
        .friend-list { background: #fff; }
        .setting { background: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="profile-icn" onclick="toggleProfile()">
                <div class="profile-pic"></div>
                <div class="profile-name">John Doe</div>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search users..." onkeyup="searchUsers(this.value)">
            </div>
            <div class="menu" onclick="toggleMenu()"><h1>☰</h1></div>
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
            // Add profile details toggle logic
            console.log('Profile clicked');
        }

        function searchUsers(query) {
            // Add search logic
            console.log('Searching for:', query);
        }
    </script>
</body>
</html>