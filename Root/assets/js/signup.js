// signup.js

import { 
    auth,
    db,
} from 'https://camisrutt.github.io/Roomifytest/Root/assets/js/firebase-init.js';
import {
    createUserWithEmailAndPassword,
    updateProfile,
  } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';
import {
    getFirestore,
    doc,
    setDoc,
  } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-firestore.js';

document.getElementById('signup-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    // Get user info
    const username = document.getElementById('username').value; // Get the username
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
        // Create new user
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        // Update the user's profile with the username
        await updateProfile(user, { displayName: username });

        // Save additional user data to Firestore
        await setDoc(doc(db, 'users', user.uid), {
            uid: user.uid,
            username: username,
            email: email,
            // Add other default profile fields here
            occupation: '',
            location: '',
            gender: '',
            moveInDate: '',
            budget: '',
            bio: ''
        });

        // Redirect to profile-dash.html
        window.location.href = 'https://camisrutt.github.io/Roomifytest/Root/html-pages/profile-dash.html';
    } catch (error) {
        console.error('Error:', error.message);
        alert(error.message);
    }
});
