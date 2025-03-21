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

        const resultsContainer = document.getElementById("searchResults");
        function searchUsers() {
            let query = document.getElementById("searchInput").value.trim();
            
        
            if (query === "") {
                resultsContainer.innerHTML = "";
                return;
            }
        
            // Validate numeric input
            if (!/^\d+$/.test(query)) {
                resultsContainer.innerHTML = "<p class='error'>Please enter a valid numeric User ID</p>";
                return;
            }
        
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "search.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        resultsContainer.innerHTML = xhr.responseText;
                    } else {
                        resultsContainer.innerHTML = "<p class='error'>Error fetching results</p>";
                    }
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

        
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('msgbtn')) {
                const targetUserId = e.target.getAttribute('data-userid');
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_request.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onload = function() {
                    const response = JSON.parse(this.responseText);
                    if (response.status === 'success') {
                        alert('Friend request sent!');
                    } else {
                        alert('Error: ' + response.message);
                    }
                };
                
                xhr.send('target_user_id=' + encodeURIComponent(targetUserId));
            }
        });
        