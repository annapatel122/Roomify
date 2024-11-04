// Sample profiles data (Replace this with actual data from your database)
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
    {
        name: "Sarah Johnson",
        age: 24,
        occupation: "Software Engineer",
        location: "San Francisco, CA",
        bio: "Looking for a clean, quiet apartment. I enjoy cooking and watching movies. Early riser and very organized!"
    },
    {
        name: "Michael Chen",
        age: 27,
        occupation: "UX Designer",
        location: "San Francisco, CA",
        bio: "Creative professional seeking roommate with similar interests. Love art, music, and good conversation."
    }
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
    container.classList.add('nope');
    setTimeout(() => {
        currentProfileIndex = (currentProfileIndex - 1 + profiles.length) % profiles.length;
        updateProfile(currentProfileIndex);
        container.classList.remove('nope');
    }, 300);
});

document.getElementById('right-arrow').addEventListener('click', () => {
    container.classList.add('like');
    setTimeout(() => {
        currentProfileIndex = (currentProfileIndex + 1) % profiles.length;
        updateProfile(currentProfileIndex);
        container.classList.remove('like');
    }, 300);
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
            document.getElementById('right-arrow').click();
        } else {
            document.getElementById('left-arrow').click();
        }
    } else {
        cardElement.style.transform = 'translateX(0) rotate(0)';
        container.classList.remove('like', 'nope');
    }
}

// Load user name from localStorage
document.addEventListener('DOMContentLoaded', function() {
    const userData = JSON.parse(localStorage.getItem('userData')) || {};
    if (userData.name) {
        document.getElementById('nav-username').textContent = userData.name.split(' ')[0];
    }
});

function logout() {
    localStorage.removeItem('userData');
    window.location.href = 'login-page.html';
}
