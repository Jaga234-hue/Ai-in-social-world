    // home.js
    const profileDetails = document.getElementById('profileDetails');
    const leftPanel = document.getElementById('leftPanel');
    const editProfile = document.getElementById('editBio');
    const editbtn = document.getElementById('editProfile');
    const closebtn = document.getElementById('close');

        function toggleMenu() {
            leftPanel.classList.toggle('active');
        }

        function toggleProfile() {

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
        
        editbtn.addEventListener('click', () => {
            editProfile.style.display = 'block';
            profileDetails.style.display = 'none';
        });

        closebtn.addEventListener('click', () => {
            editProfile.style.display = 'none';
           
        });
