let currentProfileIndex = 0;

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
    // Additional profiles...
];

const cardElement = document.getElementById('profile-card');
const container = document.getElementById('swipe-container');
let isDragging = false;
let startX = 0;
let currentX = 0;

// Load the first profile
loadProfile(profiles[currentProfileIndex]);

function loadProfile(profile) {
    cardElement.querySelector('img').src = profile.image || "";
    cardElement.querySelector('h2').textContent = `${profile.name}, ${profile.age}`;
    const infoParagraphs = cardElement.querySelectorAll('p');
    infoParagraphs[0].innerHTML = `<strong>Occupation:</strong> ${profile.occupation}`;
    infoParagraphs[1].innerHTML = `<strong>Location:</strong> ${profile.location}`;
    infoParagraphs[2].innerHTML = `<strong>About Me:</strong> ${profile.bio}`;
}

function updateProfile(index) {
    if (index < profiles.length) {
        loadProfile(profiles[index]);
    } else {
        cardElement.style.display = 'none';
        alert('No more profiles to display.');
    }
}

// Arrow buttons
document.getElementById('left-arrow').addEventListener('click', () => {
    swipeLeft();
});

document.getElementById('right-arrow').addEventListener('click', () => {
    swipeRight();
});

function swipeLeft() {
    container.classList.add('nope');
    setTimeout(() => {
        currentProfileIndex = (currentProfileIndex - 1 + profiles.length) % profiles.length;
        updateProfile(currentProfileIndex);
        container.classList.remove('nope');
    }, 300);
}

function swipeRight() {
    container.classList.add('like');
    setTimeout(() => {
        currentProfileIndex = (currentProfileIndex + 1) % profiles.length;
        updateProfile(currentProfileIndex);
        container.classList.remove('like');
    }, 300);
}

// Keyboard controls for swiping
document.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowLeft') {
        swipeLeft();
    } else if (event.key === 'ArrowRight') {
        swipeRight();
    }
});

// Touch and mouse events for card swiping
cardElement.addEventListener('mousedown', startDragging);
cardElement.addEventListener('touchstart', startDragging);
document.addEventListener('mousemove', drag);
document.addEventListener('touchmove', drag);
document.addEventListener('mouseup', stopDragging);
document.addEventListener('touchend', stopDragging);

function startDragging(event) {
    isDragging = true;
    startX = event.type === 'mousedown' ? event.clientX : event.touches[0].clientX;
    currentX = startX;
    cardElement.style.transition = 'none';
}

function drag(event) {
    if (!isDragging) return;

    const x = event.type === 'mousemove' ? event.clientX : event.touches[0].clientX;
    const deltaX = x - startX;
    currentX = x;
    cardElement.style.transform = `translateX(${deltaX}px) rotate(${deltaX * 0.05}deg)`;

    if (deltaX > 0) {
        container.classList.add('like');
        container.classList.remove('nope');
    } else {
        container.classList.add('nope');
        container.classList.remove('like');
    }
}

function stopDragging() {
    if (!isDragging) return;

    isDragging = false;
    const deltaX = currentX - startX;
    cardElement.style.transition = 'transform 0.3s ease';

    if (Math.abs(deltaX) > 100) {
        if (deltaX > 0) {
            swipeRight();
        } else {
            swipeLeft();
        }
    } else {
        cardElement.style.transform = 'translateX(0) rotate(0)';
        container.classList.remove('like', 'nope');
    }
}


// Logout function
function logout() {
    localStorage.removeItem('userData');
    window.location.href = '/Roomify/Root/html-pages/login-page.html';
}
