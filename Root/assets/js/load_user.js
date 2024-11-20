document.addEventListener('DOMContentLoaded', () => {
    const storedUsername = localStorage.getItem('username') || sessionStorage.getItem('username');
    const navUsername = document.getElementById('nav-username');

    const capitalizeFirstChar = (str) => {
        if (!str) return str;
        return str.charAt(0).toUpperCase() + str.slice(1);
    };

    if (storedUsername && navUsername) {
        navUsername.textContent = capitalizeFirstChar(storedUsername);
    } else if (navUsername) {
        navUsername.textContent = 'Guest';
    }
});