document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si nous sommes sur une page où le bouton de paramètres existe
    const settingsIcon = document.querySelector('.settings-icon');
    if (!settingsIcon) return;

    // Charger le modal via AJAX si nécessaire
    let settingsModalLoaded = false;
    let settingsModal;

    // Fonction pour charger le modal
    function loadSettingsModal() {
        if (settingsModalLoaded) return Promise.resolve();

        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'modal-param.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Ajouter le modal au DOM
                    document.body.insertAdjacentHTML('beforeend', xhr.responseText);
                    
                    
                    
                    settingsModalLoaded = true;
                    settingsModal = document.getElementById('settings-modal');
                    resolve();
                } else {
                    reject(new Error('Erreur lors du chargement du modal'));
                }
            };
            xhr.onerror = reject;
            xhr.send();
        });
    }

   

    // Ouvrir le modal de paramètres
    settingsIcon.addEventListener('click', function() {
        loadSettingsModal().then(() => {
            const closeBtn = settingsModal.querySelector('.close');
            const themeItem = document.getElementById('theme-settings-item');
            const logoutBtn = document.getElementById('logout-btn');
            
            // Afficher le modal
            settingsModal.style.display = 'block';
            setTimeout(() => {
                settingsModal.classList.add('show');
            }, 10);
            
            // Fermer le modal
            closeBtn.addEventListener('click', function closeModal() {
                settingsModal.classList.remove('show');
                setTimeout(() => {
                    settingsModal.style.display = 'none';
                }, 300);
                // Retirer l'événement pour éviter les doublons
                closeBtn.removeEventListener('click', closeModal);
            });
            
            // Fermer le modal si on clique en dehors
            function windowClickHandler(event) {
                if (event.target == settingsModal) {
                    settingsModal.classList.remove('show');
                    setTimeout(() => {
                        settingsModal.style.display = 'none';
                    }, 300);
                    window.removeEventListener('click', windowClickHandler);
                }
            }
            window.addEventListener('click', windowClickHandler);
            
            // Ouvrir le modal de thème lorsqu'on clique sur l'élément de thème
            if (themeItem) {
                themeItem.addEventListener('click', function openThemeModal() {
                    // Fermer le modal de paramètres
                    settingsModal.classList.remove('show');
                    setTimeout(() => {
                        settingsModal.style.display = 'none';
                        
                        // Ouvrir le modal de thème
                        const themeModal = document.getElementById('theme-customize-modal');
                        if (themeModal) {
                            themeModal.style.display = 'block';
                            setTimeout(() => {
                                themeModal.classList.add('show');
                            }, 10);
                        }
                    }, 300);
                    // Retirer l'événement pour éviter les doublons
                    themeItem.removeEventListener('click', openThemeModal);
                });
            }
            
            // Gérer la déconnexion
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function logout() {
                    // Rediriger vers la page de déconnexion
                    window.location.href = '../Back/logout.php';
                    // Retirer l'événement pour éviter les doublons
                    logoutBtn.removeEventListener('click', logout);
                });
            }
            
            // Gestion des éléments cliquables
            const settingsItems = settingsModal.querySelectorAll('.settings-item');
            settingsItems.forEach(item => {
                // Ne pas appliquer d'effet de clic aux éléments qui ont juste un toggle
                if (!item.querySelector('.toggle-switch')) {
                    item.addEventListener('click', function() {
                        // Ajouter un effet visuel au clic
                        this.style.backgroundColor = 'rgba(var(--primary-color-rgb, 74, 108, 250), 0.1)';
                        setTimeout(() => {
                            this.style.backgroundColor = '';
                        }, 200);
                    });
                }
            });
        }).catch(error => {
            console.error('Erreur:', error);
        });
    });

    
});

