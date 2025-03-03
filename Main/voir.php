<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMaster - Gestion de tâches</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
            --text-color: #333;
            --border-color: #ddd;
            --success-color: #2ecc71;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            background-color: #f9f9f9;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 280px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }
        
        .logo i {
            margin-right: 10px;
        }
        
        .categories-section {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #666;
        }
        
        .add-button {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 20px;
            padding: 0;
        }
        
        .category-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .category-item:hover {
            background-color: var(--light-gray);
        }
        
        .category-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .category-name {
            flex: 1;
        }
        
        .category-count {
            background-color: rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 12px;
        }
        
        .selected {
            background-color: #e3f2fd;
            font-weight: 500;
        }
        
        /* Main content styles */
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f9f9f9;
            overflow-y: auto;
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border-color);
        }
        
        .btn-outline:hover {
            background-color: var(--light-gray);
        }
        
        /* Lists grid */
        .lists-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .list-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .list-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .list-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        
        .list-menu {
            color: #666;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        
        .list-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .list-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #888;
        }
        
        .task-count i {
            margin-right: 5px;
        }
        
        .list-date {
            font-style: italic;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 10;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #666;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        textarea.form-input {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }
        
        .color-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        
        .color-option {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            cursor: pointer;
        }
        
        .color-option.selected {
            border: 2px solid var(--dark-gray);
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .show {
            display: flex;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #ccc;
        }
        
        .empty-message {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar with Categories -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-tasks"></i>
                    TaskMaster
                </div>
            </div>
            
            <div class="categories-section">
                <div class="section-header">
                    <span class="section-title">CATÉGORIES</span>
                    <button class="add-button" id="add-category-btn" title="Ajouter une catégorie">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <div class="categories-list">
                    <div class="category-item selected">
                        <div class="category-color" style="background-color: #3498db;"></div>
                        <span class="category-name">Toutes les listes</span>
                        <span class="category-count">8</span>
                    </div>
                    
                    <div class="category-item">
                        <div class="category-color" style="background-color: #2ecc71;"></div>
                        <span class="category-name">Personnel</span>
                        <span class="category-count">3</span>
                    </div>
                    
                    <div class="category-item">
                        <div class="category-color" style="background-color: #e74c3c;"></div>
                        <span class="category-name">Travail</span>
                        <span class="category-count">4</span>
                    </div>
                    
                    <div class="category-item">
                        <div class="category-color" style="background-color: #f39c12;"></div>
                        <span class="category-name">Projets</span>
                        <span class="category-count">1</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content with Lists -->
        <div class="main-content">
            <div class="content-header">
                <h1 class="page-title">Toutes les listes</h1>
                <div class="action-buttons">
                    <button class="btn btn-outline">
                        <i class="fas fa-sort-amount-down"></i>
                        Trier
                    </button>
                    <button class="btn btn-primary" id="add-list-btn">
                        <i class="fas fa-plus"></i>
                        Nouvelle liste
                    </button>
                </div>
            </div>
            
            <div class="lists-grid">
                <!-- List Card 1 -->
                <div class="list-card">
                    <div class="list-header">
                        <h3 class="list-title">Courses hebdomadaires</h3>
                        <button class="list-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <p class="list-description">Liste des courses à faire au supermarché pour la semaine.</p>
                    <div class="list-footer">
                        <div class="task-count">
                            <i class="fas fa-check-circle"></i>
                            3/7 tâches
                        </div>
                        <div class="list-date">Mise à jour: 28 févr.</div>
                    </div>
                </div>
                
                <!-- List Card 2 -->
                <div class="list-card">
                    <div class="list-header">
                        <h3 class="list-title">Projet Alpha</h3>
                        <button class="list-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <p class="list-description">Tâches liées au lancement du projet Alpha pour le client XYZ.</p>
                    <div class="list-footer">
                        <div class="task-count">
                            <i class="fas fa-check-circle"></i>
                            2/5 tâches
                        </div>
                        <div class="list-date">Mise à jour: 1 mars</div>
                    </div>
                </div>
                
                <!-- List Card 3 -->
                <div class="list-card">
                    <div class="list-header">
                        <h3 class="list-title">Rénovation maison</h3>
                        <button class="list-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <p class="list-description">Planification des travaux de rénovation pour la cuisine et la salle de bain.</p>
                    <div class="list-footer">
                        <div class="task-count">
                            <i class="fas fa-check-circle"></i>
                            1/8 tâches
                        </div>
                        <div class="list-date">Mise à jour: 27 févr.</div>
                    </div>
                </div>
                
                <!-- List Card 4 -->
                <div class="list-card">
                    <div class="list-header">
                        <h3 class="list-title">Administratif</h3>
                        <button class="list-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <p class="list-description">Papiers et démarches administratives à traiter ce mois-ci.</p>
                    <div class="list-footer">
                        <div class="task-count">
                            <i class="fas fa-check-circle"></i>
                            0/4 tâches
                        </div>
                        <div class="list-date">Mise à jour: 25 févr.</div>
                    </div>
                </div>
                
                <!-- List Card 5 -->
                <div class="list-card">
                    <div class="list-header">
                        <h3 class="list-title">Vacances d'été</h3>
                        <button class="list-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <p class="list-description">Préparation pour les vacances en Italie, réservations et choses à emporter.</p>
                    <div class="list-footer">
                        <div class="task-count">
                            <i class="fas fa-check-circle"></i>
                            2/6 tâches
                        </div>
                        <div class="list-date">Mise à jour: 28 févr.</div>
                    </div>
                </div>
                
                <!-- List Card 6 -->
                <div class="list-card">
                    <div class="list-header">
                        <h3 class="list-title">Rapport trimestriel</h3>
                        <button class="list-menu">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <p class="list-description">Préparation du rapport trimestriel pour la direction.</p>
                    <div class="list-footer">
                        <div class="task-count">
                            <i class="fas fa-check-circle"></i>
                            1/3 tâches
                        </div>
                        <div class="list-date">Mise à jour: 1 mars</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Category Modal -->
    <div class="modal" id="category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Nouvelle catégorie</h2>
                <button class="modal-close">&times;</button>
            </div>
            <form id="category-form">
                <div class="form-group">
                    <label class="form-label" for="category-name">Nom de la catégorie</label>
                    <input type="text" id="category-name" class="form-input" placeholder="Entrez un nom" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Couleur</label>
                    <div class="color-options">
                        <div class="color-option selected" style="background-color: #3498db;"></div>
                        <div class="color-option" style="background-color: #2ecc71;"></div>
                        <div class="color-option" style="background-color: #e74c3c;"></div>
                        <div class="color-option" style="background-color: #f39c12;"></div>
                        <div class="color-option" style="background-color: #9b59b6;"></div>
                        <div class="color-option" style="background-color: #1abc9c;"></div>
                        <div class="color-option" style="background-color: #34495e;"></div>
                        <div class="color-option" style="background-color: #7f8c8d;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-category">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add List Modal -->
    <div class="modal" id="list-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Nouvelle liste</h2>
                <button class="modal-close">&times;</button>
            </div>
            <form id="list-form">
                <div class="form-group">
                    <label class="form-label" for="list-name">Nom de la liste</label>
                    <input type="text" id="list-name" class="form-input" placeholder="Entrez un nom" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="list-description">Description</label>
                    <textarea id="list-description" class="form-input" placeholder="Décrivez votre liste..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="list-category">Catégorie</label>
                    <select id="list-category" class="form-select">
                        <option value="">Aucune catégorie</option>
                        <option value="1">Personnel</option>
                        <option value="2">Travail</option>
                        <option value="3">Projets</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancel-list">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Modal functionality
        const categoryModal = document.getElementById('category-modal');
        const listModal = document.getElementById('list-modal');
        const addCategoryBtn = document.getElementById('add-category-btn');
        const addListBtn = document.getElementById('add-list-btn');
        const cancelCategoryBtn = document.getElementById('cancel-category');
        const cancelListBtn = document.getElementById('cancel-list');
        const modalCloseButtons = document.querySelectorAll('.modal-close');
        
        addCategoryBtn.addEventListener('click', () => {
            categoryModal.classList.add('show');
        });
        
        addListBtn.addEventListener('click', () => {
            listModal.classList.add('show');
        });
        
        cancelCategoryBtn.addEventListener('click', () => {
            categoryModal.classList.remove('show');
        });
        
        cancelListBtn.addEventListener('click', () => {
            listModal.classList.remove('show');
        });
        
        modalCloseButtons.forEach(button => {
            button.addEventListener('click', () => {
                categoryModal.classList.remove('show');
                listModal.classList.remove('show');
            });
        });
        
        // Color selection
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove selected class from all options
                colorOptions.forEach(opt => opt.classList.remove('selected'));
                // Add selected class to clicked option
                option.classList.add('selected');
            });
        });
        
        // Category selection
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                // Remove selected class from all items
                categoryItems.forEach(cat => cat.classList.remove('selected'));
                // Add selected class to clicked item
                item.classList.add('selected');
                // Update main content title to match selected category
                const categoryName = item.querySelector('.category-name').textContent;
                document.querySelector('.page-title').textContent = categoryName;
            });
        });
        
        // Form submission (prevent default for demo)
        document.getElementById('category-form').addEventListener('submit', (e) => {
            e.preventDefault();
            // Here you would typically send data to server
            categoryModal.classList.remove('show');
            alert('Catégorie créée avec succès!');
        });
        
        document.getElementById('list-form').addEventListener('submit', (e) => {
            e.preventDefault();
            // Here you would typically send data to server
            listModal.classList.remove('show');
            alert('Liste créée avec succès!');
        });
    </script>
</body>
</html>