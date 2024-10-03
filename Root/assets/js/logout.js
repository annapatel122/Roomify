// logout.js

import { auth } from 'https://camisrutt.github.io/Roomifytest/Root/assets/js/firebase-init.js';
import { signOut } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';

document.getElementById('logout-button').addEventListener('click', () => {
    signOut(auth)
        .then(() => {
            // Sign-out successful.
            alert('User logged out successfully!');
            window.location.href = 'login-page.html';
        })
        .catch((error) => {
            const errorMessage = error.message;
            console.error('Error:', errorMessage);
            alert(errorMessage);
        });
});