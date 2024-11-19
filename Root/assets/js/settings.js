const THEMES = {
    LIGHT: 'light',
    DARK: 'dark'
};

const DEFAULT_SETTINGS = {
    notifications: {
        email: true,
        messages: true,
        matches: true
    },
    privacy: {
        publicProfile: true,
        showOnline: true
    },
    theme: THEMES.LIGHT
};


function initializeApp() {
    checkUserData();
    loadSettings();
    initializeOnlineStatus();
}

function checkUserData() {
    console.log('Page loaded, checking user data...');
    const userData = JSON.parse(localStorage.getItem('userData'));
    const navUsername = document.getElementById('nav-username');
    if (navUsername) {
        navUsername.textContent = userData?.name?.split(' ')[0] || 'Click to add name';
    }
}


function saveSettings() {
    try {
        const settings = {
            notifications: {
                email: document.getElementById('email-notifications')?.checked ?? true,
                messages: document.getElementById('message-notifications')?.checked ?? true,
                matches: document.getElementById('match-notifications')?.checked ?? true
            },
            privacy: {
                publicProfile: document.getElementById('profile-visibility')?.checked ?? true,
                showOnline: document.getElementById('show-online-status')?.checked ?? true
            },
            theme: document.getElementById('theme-preference')?.value || THEMES.LIGHT
        };

        localStorage.setItem('userSettings', JSON.stringify(settings));
        updateNavOnlineStatus(settings.privacy.showOnline);
        applyTheme(settings.theme);
        
        showNotification('Settings saved successfully!');
    } catch (error) {
        console.error('Error saving settings:', error);
        showNotification('Failed to save settings', 'error');
    }
}
function showNotification(message, type = 'success') {
    const notificationContainer = document.createElement('div');
    notificationContainer.className = `notification ${type}`;
    notificationContainer.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 5px;
        background-color: ${type === 'success' ? '#4CAF50' : '#f44336'};
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
        transition: opacity 0.3s ease;
    `;
    notificationContainer.textContent = message;
    document.body.appendChild(notificationContainer);


    setTimeout(() => {
        notificationContainer.style.opacity = '0';
        setTimeout(() => notificationContainer.remove(), 300);
    }, 2700);
}


function initializeOnlineStatus() {
    const settings = JSON.parse(localStorage.getItem('userSettings'));
    const showOnline = settings?.privacy?.showOnline ?? true;
    
    const updateStatus = () => updateNavOnlineStatus(showOnline);
    
    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    
    updateStatus();
    
    return () => {
        window.removeEventListener('online', updateStatus);
        window.removeEventListener('offline', updateStatus);
    };
}


function updateNavOnlineStatus(show) {
    const onlineIndicator = document.getElementById('online-indicator');
    if (!onlineIndicator) return;

    onlineIndicator.style.display = show ? 'inline-flex' : 'none';

    if (show) {
        const isOnline = navigator.onLine;
        onlineIndicator.className = `online-indicator ${isOnline ? 'online' : 'offline'}`;
        onlineIndicator.innerHTML = `
            <span class="status-dot"></span>
            <span class="status-text">${isOnline ? 'Online' : 'Offline'}</span>
        `;
    }
}

function applyTheme(theme) {
    document.body.classList.toggle('dark-mode', theme === THEMES.DARK);
    saveThemePreference(theme);
}

function saveThemePreference(theme) {
    try {
        const settings = JSON.parse(localStorage.getItem('userSettings')) || {};
        settings.theme = theme;
        localStorage.setItem('userSettings', JSON.stringify(settings));
    } catch (error) {
        console.error('Error saving theme preference:', error);
    }
}

function toggleDarkMode() {
    const themeSelect = document.getElementById('theme-preference');
    if (themeSelect) {
        applyTheme(themeSelect.value);
    }
}

function loadSettings() {
    try {
        const settings = JSON.parse(localStorage.getItem('userSettings')) || {};
        
      
        const mergedSettings = {
            ...DEFAULT_SETTINGS,
            ...settings,
            notifications: { ...DEFAULT_SETTINGS.notifications, ...settings.notifications },
            privacy: { ...DEFAULT_SETTINGS.privacy, ...settings.privacy }
        };

        applySettings(mergedSettings);
    } catch (error) {
        console.error('Error loading settings:', error);
        applySettings(DEFAULT_SETTINGS);
    }
}


function applySettings(settings) {
   
    Object.entries(settings.notifications).forEach(([key, value]) => {
        const element = document.getElementById(`${key}-notifications`);
        if (element) element.checked = value;
    });

  
    const privacyMappings = {
        publicProfile: 'profile-visibility',
        showOnline: 'show-online-status'
    };
    
    Object.entries(settings.privacy).forEach(([key, value]) => {
        const elementId = privacyMappings[key];
        const element = document.getElementById(elementId);
        if (element) element.checked = value;
    });

    const themeSelect = document.getElementById('theme-preference');
    if (themeSelect) themeSelect.value = settings.theme;
    applyTheme(settings.theme);
}


function logout() {
    try {
       
        localStorage.removeItem('userData');
        localStorage.removeItem('userSettings');
        
        
        window.location.href = '/Roomify/Root/html-pages/login-page.html';
    } catch (error) {
        console.error('Error during logout:', error);
        showNotification('Logout failed', 'error');
    }
}


document.addEventListener('DOMContentLoaded', initializeApp);

document.getElementById('save-settings')?.addEventListener('click', saveSettings);