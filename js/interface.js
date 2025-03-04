// Ajouts et améliorations complémentaires au script principal
document.addEventListener('DOMContentLoaded', function() {
    // Modification du thème des datepickers pour utiliser le mode sombre
    if (window.flatpickr) {
        // Appliquer le thème sombre aux instances existantes
        const datePickers = document.querySelectorAll('.flatpickr-input');
        datePickers.forEach(picker => {
            if (picker._flatpickr) {
                picker._flatpickr.set('theme', 'dark');
            }
        });
    }
    
    // Amélioration des animations pour la complétion des tâches
    const originalToggleTaskStatus = window.toggleTaskStatus;
    window.toggleTaskStatus = function(taskId, isCompleted) {
        // Appliquer l'animation améliorée
        const taskElement = document.querySelector(`.task-item[data-id="${taskId}"]`);
        if (taskElement) {
            if (isCompleted) {
                taskElement.style.opacity = '0.7';
                taskElement.style.transform = 'translateX(5px)';
                setTimeout(() => {
                    taskElement.style.transform = 'translateX(0)';
                }, 300);
            } else {
                taskElement.style.opacity = '1';
            }
        }
        
        // Appeler la fonction originale pour le traitement côté serveur
        originalToggleTaskStatus(taskId, isCompleted);
    };
    
    // Amélioration de la fonction de suppression avec animation modifiée
    const originalDeleteTask = window.deleteTask;
    window.deleteTask = function(taskId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
            const taskElement = document.querySelector(`.task-item[data-id="${taskId}"]`);
            if (taskElement) {
                // Animation améliorée
                taskElement.style.transform = 'translateX(-20px)';
                taskElement.style.opacity = '0';
                
                setTimeout(() => {
                    // Appeler la fonction originale après l'animation
                    originalDeleteTask(taskId);
                }, 300);
            } else {
                // Si l'élément n'est pas trouvé, appeler directement la fonction originale
                originalDeleteTask(taskId);
            }
            return false; // Empêcher l'appel direct à la fonction originale
        }
    };
    
    // Ajout des attributs data-id manquants pour une manipulation plus facile du DOM
    document.querySelectorAll('.task-item').forEach((item) => {
        if (!item.dataset.id) {
            const editButton = item.querySelector('.task-action-btn[onclick^="editTask"]');
            if (editButton) {
                const match = editButton.getAttribute('onclick').match(/editTask\((\d+)\)/);
                if (match && match[1]) {
                    item.dataset.id = match[1];
                }
            }
        }
    });
    
    // Amélioration de la gestion des messages de succès
    const successMessage = document.querySelector('.succes');
    if (successMessage) {
        successMessage.style.transition = 'opacity 0.6s ease';
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 600);
        }, 3000);
    }
});