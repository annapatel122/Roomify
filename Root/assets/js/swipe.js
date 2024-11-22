let currentProfileIndex = 0;
let profiles = [];

const cardElement = document.getElementById('profile-card');
const container = document.getElementById('swipe-container');
let isDragging = false;
let startX = 0;
let currentX = 0;

fetch('/Roomify/Root/api/get_profiles.php')
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Error from server:', data.message);
            alert('Error: ' + data.message);
            return;
        }
        profiles = data.profiles;
        console.log('Profiles fetched:', profiles); // For debugging
        if (profiles && profiles.length > 0) {
            loadProfile(profiles[currentProfileIndex]);
        } else {
            cardElement.style.display = 'none';
            alert('No profiles available.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });

// Remove the premature call to loadProfile
// loadProfile(profiles[currentProfileIndex]);

function loadProfile(profiles) {
    if (!profiles) {
        console.error('Profile is undefined or null');
        return;
    }
    cardElement.querySelector('img').src = profiles.profile_picture || "../assets/images/default-profile.png";
    cardElement.querySelector('h2').textContent = `${profiles.full_name}, ${profiles.age}`;
    document.getElementById('profile-occupation').textContent = profiles.occupation;
    document.getElementById('profile-location').textContent = profiles.location;
    document.getElementById('profile-gender').textContent = profiles.gender;
    document.getElementById('profile-movein-date').textContent = profiles.move_in_date;
    document.getElementById('profile-budget').textContent = profiles.budget;
    document.getElementById('profile-bio').textContent = profiles.bio;
}

function handleSwipe(swipeType) {
    const swipedUserId = profiles[currentProfileIndex].user_id;
    fetch('/Roomify/Root/api/record_swipe.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ swipeType, swipedUserId }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.match) {
            alert('You have a new match!');
        }
        currentProfileIndex++;
        if (currentProfileIndex < profiles.length) {
            loadProfile(profiles[currentProfileIndex]);
        } else {
            cardElement.style.display = 'none';
            alert('No more profiles to display.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

// Arrow click events
document.getElementById('left-arrow').addEventListener('click', () => {
    handleSwipe('dislike');
});

document.getElementById('right-arrow').addEventListener('click', () => {
    handleSwipe('like');
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

// Logout function
function logout() {
    window.location.href = '/Roomify/Root/html-pages/login-page.html';
}

// Save and apply settings
function saveSettings() {
    const settings = {
        theme: document.getElementById('theme-preference').value
    };

    localStorage.setItem('userSettings', JSON.stringify(settings));
    applyTheme(settings.theme);
}

function applyTheme(theme) {
    document.body.setAttribute('data-theme', theme);
}

window.addEventListener('storage', function(e) {
    if (e.key === 'userSettings') {
        const settings = JSON.parse(e.newValue);
        applyTheme(settings.theme);
    }
});
