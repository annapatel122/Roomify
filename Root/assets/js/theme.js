// theme.js
function initializeTheme() {
    const userSettings = JSON.parse(localStorage.getItem('userSettings')) || {};
    const theme = userSettings.theme || 'light';
    applyTheme(theme);
}

function applyTheme(theme) {
    document.body.classList.remove('light-mode', 'dark-mode');
    document.body.classList.add(`${theme}-mode`);
    localStorage.setItem('currentTheme', theme);
}

// 初始化主题
document.addEventListener('DOMContentLoaded', initializeTheme);