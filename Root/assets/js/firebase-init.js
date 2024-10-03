
// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.14.0/firebase-analytics.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.14.0/firebase-firestore.js";
import { getAuth } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyArz6j_kjK-GcvhzjG_FoFixXHxwRQxwsY",
  authDomain: "roomify-3ca92.firebaseapp.com",
  projectId: "roomify-3ca92",
  storageBucket: "roomify-3ca92.appspot.com",
  messagingSenderId: "73203416533",
  appId: "1:73203416533:web:dc8ebb09a074c8de63915c",
  measurementId: "G-0K9PKKFFNF"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
// const analytics = getAnalytics(app);

// Initialize Firebase Authentication and export it
export const auth = getAuth(app);
//Initialize Firestore and export it
export const db = getFirestore(app);

