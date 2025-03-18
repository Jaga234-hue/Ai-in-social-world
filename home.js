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

        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("follow-button")) {
                let userItem = event.target.closest(".search-result-item");
                let opponentShow = document.getElementById("opponent-show");
        
                if (userItem) {
                    let username = userItem.querySelector("h3").innerText;
                    let userId = userItem.querySelector(".user-id").innerText;
                    let profilePic = userItem.querySelector("img").src;
        
                    // Display opponent details
                    opponentShow.innerHTML = `
                        <div class="opponent-details">
                            <img src="${profilePic}" alt="Profile Picture">
                            <h3>${username}</h3>
                             
                        </div>
                    `;
                    {/* <p><strong>User ID:</strong> ${userId}</p>
                    <button id="close-btn">Close</button>   */}
                    opponentShow.style.display = "block";
                    resultsContainer.style.display = "none";

        
                    // Close button functionality
                    document.getElementById("close-btn").addEventListener("click", function() {
                        opponentShow.style.display = "none";
                    });
                }
            }
        });
        
        