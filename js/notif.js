// Demander la permission des notifications
function askNotificationPermission() {
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Permission accordée pour les notifications.');
                registerServiceWorker();
            }
        });
    }
}

// Enregistrer le Service Worker
function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(registration => {
            console.log('Service Worker enregistré avec succès.');
        }).catch(error => {
            console.error('Échec de l\'enregistrement du Service Worker :', error);
        });
    }
}

// Appeler cette fonction au chargement de la page
askNotificationPermission();