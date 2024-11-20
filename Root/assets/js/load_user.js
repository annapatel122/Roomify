document.addEventListener('DOMContentLoaded', () => {
    const storedUsername = localStorage.getItem('username') || sessionStorage.getItem('username');
    const navUsername = document.getElementById('nav-username');

    if (storedUsername && navUsername) {
        navUsername.textContent = storedUsername;
    } else if (navUsername) {
        navUsername.textContent = 'Guest';
    }
});