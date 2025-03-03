// Variables globales
let activeCategoryId = 0;
const API_PATHS = {
    loadTasks: '../Back/get_task.php',
    toggleStatus: '../Back/editer_status.php',
    deleteTask: '../Back/supprimer_tache.php',
    editTask: '../Back/editer_tache.php'
};

// Fonction principale exécutée quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    initMessageDisappear();
    initDateTimePickers();
    initToggleMenus();
    initCategoryHandlers();
    initTaskHandlers();
    
    // Charger la catégorie par défaut (première catégorie ou "Aujourd'hui")
    const defaultCategory = document.querySelector('.category-item');
    if (defaultCategory) {
        defaultCategory.click();
    }
});

// 1. Gestion des messages d'erreur/succès
function initMessageDisappear() {
    setTimeout(function() {
        const elements = {
            error: document.getElementById('erreur'),
            success: document.getElementById('succes')
        };
        
        for (let key in elements) {
            if (elements[key]) {
                elements[key].classList.add('fade-out'); 
                setTimeout(() => elements[key].remove(), 2000); 
            }
        }
    }, 3000);
}

// 2. Initialisation des sélecteurs de date et heure
function initDateTimePickers() {
    // Sélecteur de date
    if (document.getElementById('date-picker')) {
        flatpickr("#date-picker", {
            locale: "fr",
            dateFormat: "Y-m-d",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr) {
                document.querySelectorAll('.date-option-btn').forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.dataset.value === dateStr) {
                        btn.classList.add('active');
                    }
                });
            }
        });
        
        // Gestion des boutons d'options de date
        document.querySelectorAll('.date-option-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.date-option-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const datePicker = document.getElementById('date-picker');
                datePicker.value = this.dataset.value;
                datePicker._flatpickr.setDate(this.dataset.value);
            });
        });
    }
    
    // Sélecteur d'heure
    if (document.getElementById('time-picker')) {
        flatpickr("#time-picker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            onChange: function(selectedDates, timeStr) {
                document.querySelectorAll('.time-option-btn').forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.dataset.value === timeStr) {
                        btn.classList.add('active');
                    }
                });
            }
        });
        
        // Gestion des boutons d'options d'heure
        document.querySelectorAll('.time-option-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.time-option-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const timePicker = document.getElementById('time-picker');
                timePicker.value = this.dataset.value;
                if (this.dataset.value) {
                    timePicker._flatpickr.setDate(this.dataset.value);
                } else {
                    timePicker._flatpickr.clear();
                }
            });
        });
    }
}

// 3. Initialisation des menus déroulants
function initToggleMenus() {
    const toggles = {
        date: document.getElementById('date-picker-toggle'),
        time: document.getElementById('time-picker-toggle'),
        category: document.getElementById('category-picker-toggle')
    };

    const options = {
        date: document.getElementById('date-picker-options'),
        time: document.getElementById('time-picker-options'),
        category: document.getElementById('category-picker-options')
    };

    // Fonction pour fermer toutes les options
    function closeAllOptions() {
        Object.values(options).forEach(option => {
            if (option) option.style.display = 'none';
        });
        Object.values(toggles).forEach(toggle => {
            if (toggle) toggle.classList.remove('active');
        });
    }

    // Gestionnaires d'événements pour les boutons de bascule
    for (const key in toggles) {
        if (toggles[key]) {
            toggles[key].addEventListener('click', function() {
                const isActive = this.classList.contains('active');
                closeAllOptions();
                if (!isActive) {
                    if (options[key]) options[key].style.display = 'block';
                    this.classList.add('active');
                }
            });
        }
    }

    // Gestionnaires pour les boutons de fermeture des options
    document.querySelectorAll('.close-options').forEach(button => {
        button.addEventListener('click', closeAllOptions);
    });

    // Fermer les options en cliquant à l'extérieur
    document.addEventListener('click', function(event) {
        const isClickInside = event.target.closest('.floating-options') || 
                             event.target.closest('.option-toggle');
        if (!isClickInside) {
            closeAllOptions();
        }
    });
}

// 4. Initialisation des gestionnaires de catégories
function initCategoryHandlers() {
    // Gestion de la sélection de catégorie dans la barre latérale
    const categoryItems = document.querySelectorAll('.category-item');
    const categoryTitle = document.getElementById('category-title');
    const categorieIdInput = document.getElementById('categorie_id');
    const categorieSelect = document.getElementById('categorie-select');

    // Gestion du dropdown de catégorie dans le formulaire
    if (categorieSelect && categorieIdInput) {
        categorieSelect.addEventListener('change', function() {
            categorieIdInput.value = this.value;
        });
    }

    // Gestion des clics sur les catégories
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const categoryId = this.dataset.id;
            activeCategoryId = categoryId;
            
            // Mise à jour visuelle
            categoryItems.forEach(cat => cat.classList.remove('active'));
            this.classList.add('active');
            
            // Mettre à jour le titre
            if (categoryTitle) {
                const categoryName = this.querySelector('.category-name').textContent;
                updateCategoryTitle(categoryName);
            }
            
            if (categorieIdInput) {
                categorieIdInput.value = categoryId;
            }

            // Charger les tâches de cette catégorie
            loadTasks(categoryId);
        });
    });

    // Modal d'ajout de catégorie
    const addCategoryBtn = document.querySelector('.add-category');
    const modal = document.getElementById('add-category-modal');
    
    if (addCategoryBtn && modal) {
        const closeBtn = modal.querySelector('.close');

        addCategoryBtn.addEventListener('click', function() {
            modal.style.display = "block";
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = "none";
            });
        }

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    }
}

// 5. Initialisation des gestionnaires de tâches
function initTaskHandlers() {
    // Les gestionnaires pour éditer/supprimer les tâches sont ajoutés dynamiquement
    // lorsque les tâches sont chargées
}

// 6. Fonction pour charger les tâches
function loadTasks(categoryId) {
    fetch(`${API_PATHS.loadTasks}?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            updateTasksUI(data);
        })
        .catch(error => console.error('Erreur:', error));
}

// 7. Fonction pour marquer une tâche comme terminée/non terminée
function toggleTaskStatus(taskId, isChecked) { 
    fetch(API_PATHS.toggleStatus, { 
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
        body: `task_id=${taskId}&status=${isChecked ? 1 : 0}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animation de suppression si la tâche est terminée             
            if (isChecked) {        
                setTimeout(() => {  
                    const taskElement = document.querySelector(`.task-item[data-id="${taskId}"]`);  
                    if (taskElement) { 
                        taskElement.style.opacity = "0"; 
                        setTimeout(() => { taskElement.remove(); }, 300);  
                    }
                }, 1000);        
            }
        }
    })
    .catch(error => console.error('Erreur:', error));
}

// 8. Fonction pour supprimer une tâche
function deleteTask(taskId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette tâche?")) {
        fetch(API_PATHS.deleteTask, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${taskId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskElement = document.querySelector(`.task-item[data-id="${taskId}"]`);
                if (taskElement) {
                    taskElement.style.opacity = "0";
                    setTimeout(() => {
                        taskElement.remove();
                    }, 300);
                }
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
}

// 9. Fonction pour éditer une tâche
function editTask(taskId) {
    // Rediriger vers la page d'édition
    window.location.href = `${API_PATHS.editTask}?id=${taskId}`;
}

// 10. Fonction pour mettre à jour le titre de la catégorie
function updateCategoryTitle(categoryName) {
    const categoryTitle = document.getElementById('category-title');
    const dateTodayElement = document.querySelector('.date-today');
    
    if (categoryTitle) {
        categoryTitle.textContent = categoryName;
    }

    // Mettre à jour la date si la catégorie est "Aujourd'hui"
    if (dateTodayElement) {
        if (categoryName === "Aujourd'hui") {
            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateTodayElement.textContent = today.toLocaleDateString('fr-FR', options);
            dateTodayElement.style.display = 'block'; // Afficher l'élément
        } else {
            dateTodayElement.style.display = 'none'; // Masquer l'élément
        }
    }
}

// 11. Fonction UNIQUE pour mettre à jour l'interface des tâches
function updateTasksUI(data) {
    const taskList = document.querySelector('.task-list');
    if (!taskList) return;

    taskList.innerHTML = ''; // Vider la liste actuelle
    
    // Si nous n'avons pas de tâches mais un message
    if (data.tasks.length === 0 && data.message) {
        const emptyMessage = document.createElement('li');
        emptyMessage.className = 'task-item';
        emptyMessage.style.justifyContent = 'center';
        emptyMessage.style.padding = '20px';
        emptyMessage.innerHTML = `
            <div style="text-align: center; color: #666;">
                <i class="far fa-smile" style="font-size: 24px; margin-bottom: 10px;"></i>
                <p>${data.message}</p>
            </div>
        `;
        taskList.appendChild(emptyMessage);
    } else {
        // Afficher les tâches si la catégorie n'est pas vide
        data.tasks.forEach(task => {
            const taskItem = document.createElement('li');
            taskItem.className = 'task-item';
            taskItem.setAttribute('data-id', task.id);
            taskItem.style.borderLeftColor = task.categorie_couleur;

            taskItem.innerHTML = `
                <label class="task-checkbox">
                    <input type="checkbox" ${task.terminee == 1 ? 'checked' : ''} 
                           onchange="toggleTaskStatus(${task.id}, this.checked)">
                    <span class="checkbox-custom"></span>
                </label>
                <div class="task-content">
                    <div class="task-title">${task.description}</div>
                    <div class="task-details">
                        <span><i class="far fa-calendar"></i> ${new Date(task.date_echeance).toLocaleDateString('fr-FR')}</span>
                        ${task.heure_echeance ? `<span><i class="far fa-clock"></i> ${task.heure_echeance}</span>` : ''}
                        <span><i class="fas fa-tag" style="color: ${task.categorie_couleur};"></i> ${task.categorie_nom}</span>
                    </div>
                </div>
                <div class="task-actions">
                    <button class="task-action-btn" onclick="editTask(${task.id})"><i class="fas fa-edit"></i></button>
                    <button class="task-action-btn" onclick="deleteTask(${task.id})"><i class="fas fa-trash"></i></button>
                </div>
            `;
            taskList.appendChild(taskItem);
        });
    }
}

// Exposer les fonctions nécessaires globalement
window.toggleTaskStatus = toggleTaskStatus;
window.deleteTask = deleteTask;
window.editTask = editTask;