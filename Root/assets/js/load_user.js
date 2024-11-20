async function loadUsername() {
    try {
        const response = await fetch('/Roomify/Root/api/get_username.php'); // Update path as needed
        const text = await response.text(); // Read raw response for debugging
        console.log('Raw response:', text); // Log raw response

        // Parse the response as JSON
        const data = JSON.parse(text);

        if (data.username) {
            document.getElementById('nav-username').textContent = data.username;
        } else {
            console.error('Error fetching username:', data.error);
            document.getElementById('nav-username').textContent = 'Guest';
        }
    } catch (error) {
        console.error('Error fetching user data:', error);
        document.getElementById('nav-username').textContent = 'Guest';
    }
}

document.addEventListener('DOMContentLoaded', loadUsername);