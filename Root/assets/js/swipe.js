// Sample profiles data (Replace this with actual data from your database)
const profiles = [
    {
        name: "Alex Johnson",
        age: 25,
        occupation: "Software Engineer",
        location: "New York",
        bio: "Loves coding and coffee.",
        image: "path_to_image1.jpg"
    },
    {
        name: "Maria Gomez",
        age: 28,
        occupation: "Graphic Designer",
        location: "Los Angeles",
        bio: "Artist at heart.",
        image: "path_to_image2.jpg"
    },
    // Add more profiles as needed
];

let currentProfileIndex = 0;

const cardElement = document.getElementById('profile-card');

function loadProfile(profile) {
    cardElement.querySelector('img').src = profile.image;
    cardElement.querySelector('h2').textContent = `${profile.name}, ${profile.age}`;
    cardElement.querySelector('p:nth-of-type(1)').innerHTML = `<strong>Occupation:</strong> ${profile.occupation}`;
    cardElement.querySelector('p:nth-of-type(2)').innerHTML = `<strong>Location:</strong> ${profile.location}`;
    cardElement.querySelector('p:nth-of-type(3)').innerHTML = `<strong>About Me:</strong> ${profile.bio}`;
}

loadProfile(profiles[currentProfileIndex]);

// Swipe functionality
let startX = 0;
let currentX = 0;
let isDragging = false;

cardElement.addEventListener('mousedown', startDrag);
cardElement.addEventListener('touchstart', startDrag);

cardElement.addEventListener('mousemove', onDrag);
cardElement.addEventListener('touchmove', onDrag);

cardElement.addEventListener('mouseup', endDrag);
cardElement.addEventListener('touchend', endDrag);

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
    setTimeout(() => {
        // Process the swipe
        if (direction === 'right') {
            console.log('Matched with:', profiles[currentProfileIndex].name);
            // Add match handling logic here
        } else {
            console.log('Rejected:', profiles[currentProfileIndex].name);
            // Add rejection handling logic here
        }

        // Load next profile
        currentProfileIndex++;
        if (currentProfileIndex < profiles.length) {
            cardElement.style.transition = 'none';
            cardElement.style.transform = 'translateX(0) rotate(0)';
            loadProfile(profiles[currentProfileIndex]);
        } else {
            // No more profiles
            cardElement.style.display = 'none';
            alert('No more profiles to display.');
        }
    }, 300); // Wait for the swipe-out animation to finish
}
