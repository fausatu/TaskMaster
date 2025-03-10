document.addEventListener('DOMContentLoaded', function() {
    // Supprimez cette alerte qui bloque l'exécution
    // alert('JavaScript is executing!');
    
    // Éléments du DOM
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const body = document.body;
    
    console.log('Menu toggle:', menuToggle);
    console.log('Sidebar:', sidebar);
    console.log('Sidebar overlay:', sidebarOverlay);
    
    // Fonction pour ouvrir/fermer la sidebar
    function toggleSidebar() {
        console.log('Toggling sidebar');
        sidebar.classList.toggle('sidebar-open');
        sidebarOverlay.classList.toggle('active');
        body.style.overflow = sidebar.classList.contains('sidebar-open') ? 'hidden' : '';
        menuToggle.classList.toggle('active');
    }
    
    // Attacher les écouteurs d'événements avec vérification
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            console.log('Menu button clicked');
            e.preventDefault();
            toggleSidebar();
        });
    } else {
        console.error('Menu toggle button not found!');
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            console.log('Overlay clicked');
            toggleSidebar();
        });
    } else {
        console.error('Sidebar overlay not found!');
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
        }
    });
});