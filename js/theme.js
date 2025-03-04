document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le thème
    initTheme();

    // Gérer les clics sur les options de thème
    const themeOptions = document.querySelectorAll('.ms-theme-option');
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const theme = this.getAttribute('data-theme');
            applyTheme(theme);
            document.querySelector('.theme-dropdown').classList.remove('show');
        });
    });

    // Fermer le dropdown en cliquant ailleurs
    document.addEventListener('click', function(e) {
        const themeDropdown = document.querySelector('.theme-dropdown');
        const themeToggleBtn = document.querySelector('.theme-toggle-btn');

        if (!themeDropdown.contains(e.target) && !themeToggleBtn.contains(e.target)) {
            themeDropdown.classList.remove('show');
        }
    });
});

function initTheme() {
    const savedTheme = localStorage.getItem('taskmaster-theme') || 'light';
    applyTheme(savedTheme);
}

function applyTheme(theme) {
    // Supprimer toutes les classes de thème existantes
    document.body.classList.remove('theme-light', 'theme-dark', 'theme-blue', 'theme-purple', 'theme-green', 'theme-pink', 'theme-red', 'theme-orange', 'theme-teal', 'theme-custom');

    // Ajouter la classe du nouveau thème
    document.body.classList.add(`theme-${theme}`);

    // Si c'est un thème personnalisé, appliquer les couleurs personnalisées
    if (theme === 'custom') {
        const primaryColor = localStorage.getItem('taskmaster-custom-primary') || '#4a6cfa';
        const sidebarColor = localStorage.getItem('taskmaster-custom-sidebar') || '#323131';
        const mainBgColor = localStorage.getItem('taskmaster-custom-bg') || '#f5f5f5';
        const textColor = localStorage.getItem('taskmaster-custom-text') || '#333333';

        document.documentElement.style.setProperty('--primary-color', primaryColor);
        document.documentElement.style.setProperty('--sidebar-bg', sidebarColor);
        document.documentElement.style.setProperty('--main-bg', mainBgColor);
        document.documentElement.style.setProperty('--text-color', textColor);
    }

    // Stocker le thème dans localStorage
    localStorage.setItem('taskmaster-theme', theme);
}