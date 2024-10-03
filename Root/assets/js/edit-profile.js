import { auth } from 'https://camisrutt.github.io/Roomifytest/Root/assets/js/firebase-init.js';
import {
    onAuthStateChanged,
    updateProfile,
  } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';
  import {
    getFirestore,
    doc,
    getDoc,
    updateDoc,
  } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-firestore.js';
  import {
    getStorage,
    ref,
    uploadBytes,
    getDownloadURL,
  } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-storage.js';
  
  const db = getFirestore();
  const storage = getStorage();
  
  let currentUser;
  
  onAuthStateChanged(auth, async (user) => {
    if (user) {
      currentUser = user;
  
      // Get user data from Firestore
      const docRef = doc(db, 'users', user.uid);
      const docSnap = await getDoc(docRef);
  
      if (docSnap.exists()) {
        const userData = docSnap.data();
  
        // Populate form fields with existing data
        document.getElementById('displayName').value = userData.displayName || '';
        document.getElementById('occupation').value = userData.occupation || '';
        document.getElementById('location').value = userData.location || '';
        document.getElementById('gender').value = userData.gender || '';
        document.getElementById('moveInDate').value = userData.moveInDate || '';
        document.getElementById('budget').value = userData.budget || '';
        document.getElementById('bio').value = userData.bio || '';
      } else {
        console.log('No such document!');
      }
    } else {
      // No user is signed in. Redirect to login page.
      window.location.href = 'login-page.html';
    }
  });
  
  document.getElementById('edit-profile-form').addEventListener('submit', async (e) => {
    e.preventDefault();
  
    // Get updated profile data from form
    const displayName = document.getElementById('displayName').value;
    const occupation = document.getElementById('occupation').value;
    const location = document.getElementById('location').value;
    const gender = document.getElementById('gender').value;
    const moveInDate = document.getElementById('moveInDate').value;
    const budget = document.getElementById('budget').value;
    const bio = document.getElementById('bio').value;
  
    // Handle profile picture upload (if changed)
    const profilePictureInput = document.getElementById('profilePicture');
    let profilePictureUrl = '';
  
    try {
      // Update displayName in Authentication profile if changed
      if (currentUser.displayName !== displayName) {
        await updateProfile(currentUser, { displayName });
      }
  
      // If a new profile picture is selected
      if (profilePictureInput.files.length > 0) {
        const file = profilePictureInput.files[0];
        const storageRef = ref(storage, `profilePictures/${currentUser.uid}/${file.name}`);
        await uploadBytes(storageRef, file);
        profilePictureUrl = await getDownloadURL(storageRef);
      }
  
      // Update user data in Firestore
      const docRef = doc(db, 'users', currentUser.uid);
      const updatedData = {
        displayName,
        occupation,
        location,
        gender,
        moveInDate,
        budget,
        bio,
      };
  
      if (profilePictureUrl) {
        updatedData.profilePictureUrl = profilePictureUrl;
      }
  
      await updateDoc(docRef, updatedData);
  
      alert('Profile updated successfully!');
      // Redirect back to profile dashboard
      window.location.href = 'profile-dash.html';
    } catch (error) {
      console.error('Error updating profile:', error);
      alert('Error updating profile: ' + error.message);
    }
  });