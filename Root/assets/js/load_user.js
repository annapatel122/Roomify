        // Fetch username from the backend
        async function loadUsername() {
            try {
                const response = await fetch('/Roomify/Root/api/get_username.php');
                const data = await response.json();

                if (data.username) {
                    document.getElementById('nav-username').textContent = data.username;
                } else {
                    console.error('Error fetching username:', data.error);
                    document.getElementById('nav-username').textContent = 'Guest';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('nav-username').textContent = 'Guest';
            }
        }

        document.addEventListener('DOMContentLoaded', loadUsername);