// Sample profiles data (Replace this with actual data from your database)
const profiles = [
    {
        name: "Alex Johnson",
        age: 25,
        occupation: "Software Engineer",
        location: "New York",
        bio: "Loves coding and coffee.",
        image: "images/profile1.jpg"
    },
    {
        name: "Maria Gomez",
        age: 28,
        occupation: "Graphic Designer",
        location: "Los Angeles",
        bio: "Artist at heart.",
        image: "images/profile2.jpg"
    },
    {
        name: "David Lee",
        age: 30,
        occupation: "Marketing Manager",
        location: "Chicago",
        bio: "Enjoys hiking and photography.",
        image: "images/profile3.jpg"
    },
    {
        name: "Samantha Brown",
        age: 27,
        occupation: "Teacher",
        location: "Austin",
        bio: "Book lover and coffee enthusiast.",
        image: "images/profile4.jpg"
    },
    {
        name: "Michael Chen",
        age: 26,
        occupation: "Accountant",
        location: "San Francisco",
        bio: "Foodie who loves exploring new restaurants.",
        image: "images/profile5.jpg"
    },
    {
        name: "Emily Davis",
        age: 24,
        occupation: "Nurse",
        location: "Seattle",
        bio: "Passionate about health and wellness.",
        image: "images/profile6.jpg"
    },
    {
        name: "James Wilson",
        age: 29,
        occupation: "Lawyer",
        location: "Boston",
        bio: "Avid runner and traveler.",
        image: "images/profile7.jpg"
    },
    {
        name: "Olivia Martinez",
        age: 23,
        occupation: "Student",
        location: "Miami",
        bio: "Studying psychology, loves beach days.",
        image: "images/profile8.jpg"
    },
    // Add more profiles as needed
];

let currentProfileIndex = 0;

const cardElement = document.getElementById('profile-card');

// Load the first profile
loadProfile(profiles[currentProfileIndex]);

function loadProfile(profile) {
    cardElement.querySelector('img').src = profile.image;
    cardElement.querySelector('h2').textContent = `${profile.name}, ${profile.age}`;
    const infoParagraphs = cardElement.querySelectorAll('p');
    infoParagraphs[0].innerHTML = `<strong>Occupation:</strong> ${profile.occupation}`;
    infoParagraphs[1].innerHTML = `<strong>Location:</strong> ${profile.location}`;
    infoParagraphs[2].innerHTML = `<strong>About Me:</strong> ${profile.bio}`;
}

// Swipe functionality variables
let startX = 0;
let currentX = 0;
let isDragging = false;

// Event listeners for drag/swipe
cardElement.addEventListener('mousedown', startDrag);
cardElement.addEventListener('touchstart', startDrag);

cardElement.addEventListener('mousemove', onDrag);
cardElement.addEventListener('touchmove', onDrag);

cardElement.addEventListener('mouseup', endDrag);
cardElement.addEventListener('touchend', endDrag);

// Arrow buttons
const leftArrow = document.getElementById('left-arrow');
const rightArrow = document.getElementById('right-arrow');

leftArrow.addEventListener('click', () => {
    cardElement.style.transition = 'transform 0.3s ease';
    cardElement.style.transform = 'translateX(-1000px) rotate(-30deg)';
    handleSwipe('left');
});

rightArrow.addEventListener('click', () => {
    cardElement.style.transition = 'transform 0.3s ease';
    cardElement.style.transform = 'translateX(1000px) rotate(30deg)';
    handleSwipe('right');
});

// Keyboard controls
document.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') {
        cardElement.style.transition = 'transform 0.3s ease';
        cardElement.style.transform = 'translateX(-1000px) rotate(-30deg)';
        handleSwipe('left');
    } else if (event.key === 'ArrowRight') {
        cardElement.style.transition = 'transform 0.3s ease';
        cardElement.style.transform = 'translateX(1000px) rotate(30deg)';
        handleSwipe('right');
    }
});

function startDrag(event) {
    isDragging = true;
    startX = getPositionX(event);
    cardElement.style.transition = 'none';
}

function onDrag(event) {
    if (!isDragging) return;
    currentX = getPositionX(event);
    const deltaX = currentX - startX;
    cardElement.style.transform = `translateX(${deltaX}px) rotate(${deltaX * 0.05}deg)`;
}

function endDrag(event) {
    if (!isDragging) return;
    isDragging = false;
    const deltaX = currentX - startX;
    cardElement.style.transition = 'transform 0.3s ease';

    if (deltaX > 150) {
        // Swiped right
        cardElement.style.transform = 'translateX(1000px) rotate(30deg)';
        handleSwipe('right');
    } else if (deltaX < -150) {
        // Swiped left
        cardElement.style.transform = 'translateX(-1000px) rotate(-30deg)';
        handleSwipe('left');
    } else {
        // Reset position
        cardElement.style.transform = 'translateX(0) rotate(0)';
    }
}

function getPositionX(event) {
    if (event.type.startsWith('touch')) {
        return event.touches[0].clientX;
    } else {
        return event.clientX;
    }
}

function handleSwipe(direction) {
    const swipeContainer = document.querySelector('.swipe-container');

    // Apply visual feedback
    if (direction === 'right') {
        // Like: Apply pastel green background
        swipeContainer.classList.add('like');
    } else {
        // Nope: Apply pastel red background
        swipeContainer.classList.add('nope');
    }

    // Proceed with the swipe animation and logic
    setTimeout(() => {
        // Process the swipe result
        if (direction === 'right') {
            console.log('Matched with:', profiles[currentProfileIndex].name);
            // TODO: Add match handling logic here
        } else {
            console.log('Rejected:', profiles[currentProfileIndex].name);
            // TODO: Add rejection handling logic here
        }

        // Remove visual feedback after delay
        setTimeout(() => {
            // Remove 'like' and 'nope' classes
            swipeContainer.classList.remove('like', 'nope');
            // Add 'reset' class to transition back to original color
            swipeContainer.classList.add('reset');

            // Remove 'reset' class after transition
            setTimeout(() => {
                swipeContainer.classList.remove('reset');
            }, 500); // This should match the CSS transition duration
        }, 300); // Delay before removing visual feedback

        // Load the next profile
        currentProfileIndex++;
        if (currentProfileIndex < profiles.length) {
            // Reset card position and load new profile
            cardElement.style.transition = 'none';
            cardElement.style.transform = 'translateX(0) rotate(0)';
            loadProfile(profiles[currentProfileIndex]);

            // Reset drag positions
            startX = 0;
            currentX = 0;
        } else {
            // No more profiles
            cardElement.style.display = 'none';
            alert('No more profiles to display.');
        }
    }, 300); // Wait for the swipe-out animation to finish
}
