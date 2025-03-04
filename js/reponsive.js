// Assurez-vous que ce code est ajouté à votre fichier reponsive.js
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const body = document.body;
    
    // Vérifions que les éléments sont bien trouvés
    console.log('Menu toggle:', menuToggle);
    console.log('Sidebar:', sidebar);
    console.log('Sidebar overlay:', sidebarOverlay);
    
    // Fonction pour ouvrir/fermer la sidebar
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const body = document.body;
    
        sidebar.classList.toggle('sidebar-open');
        sidebarOverlay.classList.toggle('active');
        body.style.overflow = sidebar.classList.contains('sidebar-open') ? 'hidden' : '';
    }
    
    // Attacher les écouteurs d'événements avec vérification
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            console.log('Menu button clicked');
            e.preventDefault();
            toggleSidebar();
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            console.log('Overlay clicked');
            toggleSidebar();
        });
    }
    
    // Fermer la sidebar lorsqu'on clique sur un élément de la liste
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });
    
    // Réinitialiser l'état de la sidebar lors du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('sidebar-open');
            menuToggle.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            body.style.overflow = '';
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        }
    });
});