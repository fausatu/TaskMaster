// Dans votre fichier modal-partage.js
document.addEventListener('DOMContentLoaded', function() {
    // Définir la fonction shareTask dans la portée globale (window)
    window.shareTask = function(taskId) {
        // Sélectionner le modal
        const modal = document.getElementById('shareTaskModal');
        // Sélectionner le champ caché pour l'ID de la tâche
        const taskIdInput = document.getElementById('taskIdToShare');
        
        if (modal && taskIdInput) {
            // Définir l'ID de la tâche dans le champ caché
            taskIdInput.value = taskId;
            // Afficher le modal en changeant son style
            modal.style.display = 'block';
            console.log('Modal affiché pour la tâche ID:', taskId);
        } else {
            console.error('Modal ou input taskIdToShare non trouvé!');
        }
    };
    
    // Fermer le modal quand on clique sur le X
    const closeButton = document.querySelector('#shareTaskModal .close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            document.getElementById('shareTaskModal').style.display = 'none';
        });
    }
    
    // Fermer le modal quand on clique en dehors
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('shareTaskModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Gérer la soumission du formulaire
    const shareTaskForm = document.getElementById('shareTaskForm');
    if (shareTaskForm) {
        shareTaskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const taskId = document.getElementById('taskIdToShare').value;
            const sharedUserEmail = document.getElementById('sharedUserEmail').value;
            const permissions = document.getElementById('permissions').value;
            
            // Envoi des données au serveur
            fetch('../Back/partager_tache.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ taskId, sharedUserEmail, permissions }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Tâche partagée avec succès !');
                    document.getElementById('shareTaskModal').style.display = 'none';
                } else {
                    alert('Erreur : ' + (data.error || 'Une erreur inconnue s\'est produite'));
                }
            })
            .catch(error => {
                console.error('Erreur :', error);
                alert('Une erreur s\'est produite lors du partage de la tâche.');
            });
        });
    }
});