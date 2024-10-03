// profile-dash.js

import { auth } from 'https://camisrutt.github.io/Roomifytest/Root/assets/js/firebase-init.js';
import { onAuthStateChanged } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';
import {
    getFirestore,
    doc,
    getDoc,
  } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-firestore.js';
  
  const db = getFirestore(); // Initialize Firestore
  
  // Ensure the DOM is fully loaded before running the script
  document.addEventListener('DOMContentLoaded', () => {
    onAuthStateChanged(auth, async (user) => {
      if (user) {
        // User is signed in.
        const displayName = user.displayName || 'User';
        document.getElementById('nav-username').textContent = displayName;
  
        // Retrieve user profile from Firestore
        try {
          const docRef = doc(db, 'users', user.uid);
          const docSnap = await getDoc(docRef);
  
          if (docSnap.exists()) {
            const userData = docSnap.data();
  
            // Update the profile information
            document.getElementById('user-fullname').textContent =
              userData.displayName || displayName;
            document.getElementById('user-occupation').textContent =
              userData.occupation || 'Your Occupation';
            document.getElementById('user-location').textContent =
              userData.location || 'Your Location';
            document.getElementById('user-gender').textContent =
              userData.gender || 'Your Gender';
            document.getElementById('user-movein-date').textContent =
              userData.moveInDate || 'Your Move-in Date';
            document.getElementById('user-budget').textContent =
              userData.budget || 'Your Budget';
            document.getElementById('user-bio').textContent =
              userData.bio || 'A short bio about you.';
  
            // Update profile picture if available
            if (userData.profilePictureUrl) {
              document.getElementById('profile-picture').src =
                userData.profilePictureUrl;
            }
          } else {
            console.log('No such document!');
          }
        } catch (error) {
          console.error('Error getting user data:', error);
        }
      } else {
        // No user is signed in.
        window.location.assign ('login-page.html');
      }
    });
  
    // Add event listener for Edit Profile button
    const editProfileButton = document.getElementById('edit-profile-button');
    if (editProfileButton) {
      editProfileButton.addEventListener('click', () => {
        window.location.assign ('edit-profile.html');
      });
    }
  });