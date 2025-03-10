<?php 
  include("../Back/bd.php");
  include("../Back/donnee.php");

 $icon= "'https://cdn-icons-png.flaticon.com/512/3135/3135715.png'";

    if(isset($_SESSION["profil"])){
        $phto = $_SESSION["profil"];
    }
    else{
        $phto = $icon;
    }




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMaster - Votre gestionnaire de tâches</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

   
    <!-- Ajout de Flatpickr pour les sélecteurs de date et heure -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/fr.js"></script>
   

    
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/theme.css">
    <link rel="stylesheet" href="../styles//reponsive.css">
    
</head>
<body>
      

<button class="menu-toggle" id="menuToggle">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="app-logo">
            <img src="../images/apple-splash-1334-750.jpg" alt="Logo TaskMaster">
            <h1>TaskMaster</h1>
        </div>

        <div class="user-profile">
            <div class="profile-pic">
                <img src="<?php echo $phto   ?>" alt="Photo de profil">
            </div>
            <div class="user-info">
                <h3><?php echo $_SESSION["nom"]." ".$_SESSION["prenom"]; ?></h3>
                <p><?php echo $_SESSION["login"]; ?></p>
            </div>
            <div class="settings-icon">
                <i class="fas fa-cog"></i>
            </div>
        </div>


        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" placeholder="Rechercher...">
        </div>

        <div class="categories">
            <h3 class="category-title">Catégories</h3>
            <ul class="category-list">
                <?php foreach ($categories_systeme as $categorie): ?>
                <li class="category-item <?php echo ($categorie['nom'] == "Aujourd'hui") ? 'active' : ''; ?>" 
                    data-id="<?php echo $categorie['id']; ?>">
                    <i class="<?php echo $categorie['icone']; ?> category-icon"></i>
                    <span class="category-name"><?php echo $categorie['nom']; ?></span>
                    <span class="category-badge"><?php echo $categorie['nombre_taches']; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="categories">
            <h3 class="category-title">Mes listes</h3>
            <ul class="category-list">
                <?php foreach ($categories_perso as $categori): ?>
                <li class="category-item" data-id="<?php echo $categori['id']; ?>">
                    <i class="<?php echo $categori['icone']; ?> category-icon" style="color: <?php echo $categori['couleur']; ?>;"></i>
                    <span class="category-name"><?php echo $categori['nom']; ?></span>
                    <span class="category-badge"><?php echo $categori['nombre_taches']; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="add-category">
                <i class="fas fa-plus"></i>
                <span>Ajouter une liste</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <?php if (isset($_SESSION["erreur"])): ?>
            
            <?php 
                echo $_SESSION["erreur"];
                unset($_SESSION["erreur"]); 
            ?>
      
    <?php endif; ?>

    <?php if (isset($_SESSION["success"])): ?>
        
            <?php 
                echo $_SESSION["success"];
                unset($_SESSION["success"]); 
            ?>
 <?php endif; ?>
      <?php  // Définir une catégorie par défaut ou une catégorie spécifique
$currentCategory = isset($categories_systeme[0]) ? $categories_systeme[0] : null; // Par exemple, la première catégorie système

// Afficher le titre de la catégorie
?>       
        
        <div class="category-header">
            <div>
            <h2 id="category-title"><i class="<?php echo $categori['icone']; ?> category-icon" style="color: <?php echo $categori['couleur']; ?>;"></i><?php echo htmlspecialchars($currentCategory['nom']); ?></h2>
    <?php if (isset($currentCategory['nom']) && $currentCategory['nom'] === 'Aujourd\'hui') { ?> 
        <p class="date-today"><?php echo date('l, j F Y'); ?></p>
    <?php } ?>
            </div>
    

            <!-- Ajouter dans la sidebar, après la div user-profile -->
<!-- HTML pour le sélecteur de thèmes style Microsoft To Do -->
<!-- Remplacer le large sélecteur de thème par ce petit bouton dans la sidebar -->
<div class="theme-toggle">
    <button class="theme-toggle-btn" title="Options de thème">
        <i class="fas fa-ellipsis-h"></i>
    </button>
    <div class="theme-dropdown">
        <h3 class="theme-dropdown-title">Thèmes</h3>
        <div class="theme-grid">
            <div class="ms-theme-option" data-theme="light" title="Thème clair">
                <div class="ms-theme-color-block" style="background-color: #fff"></div>
                <span class="ms-theme-name">Clair</span>
            </div>
            <div class="ms-theme-option" data-theme="dark" title="Thème sombre">
                <div class="ms-theme-color-block" style="background-color: #252525"></div>
                <span class="ms-theme-name">Sombre</span>
            </div>
            <div class="ms-theme-option" data-theme="blue" title="Bleu">
                <div class="ms-theme-color-block" style="background-color: #0078d7"></div>
                <span class="ms-theme-name">Bleu</span>
            </div>
            <div class="ms-theme-option" data-theme="purple" title="Violet">
                <div class="ms-theme-color-block" style="background-color: #8763b8"></div>
                <span class="ms-theme-name">Violet</span>
            </div>
            <div class="ms-theme-option" data-theme="green" title="Vert">
                <div class="ms-theme-color-block" style="background-color: #107c41"></div>
                <span class="ms-theme-name">Vert</span>
            </div>
            <div class="ms-theme-option" data-theme="red" title="Rouge">
                <div class="ms-theme-color-block" style="background-color: #e74856"></div>
                <span class="ms-theme-name">Rouge</span>
            </div>
            <div class="ms-theme-option" data-theme="orange" title="Orange">
                <div class="ms-theme-color-block" style="background-color: #f7630c"></div>
                <span class="ms-theme-name">Orange</span>
            </div>
            <div class="ms-theme-option" data-theme="pink" title="Rose">
                <div class="ms-theme-color-block" style="background-color: #e83e8c"></div>
                <span class="ms-theme-name">Rose</span>
            </div>
            <div class="ms-theme-option" data-theme="teal" title="Turquoise">
                <div class="ms-theme-color-block" style="background-color: #00b7c3"></div>
                <span class="ms-theme-name">Turquoise</span>
            </div>
        </div>
        <div class="theme-customize-btn">
            <button id="customize-theme-btn">
                <i class="fas fa-palette"></i>
                <span>Personnaliser davantage</span>
            </button>
        </div>
    </div>
</div>
</div>



        <div class="tasks-container">
            <ul class="task-list">
                <?php foreach ($taches as $tache): ?>
                <li class="task-item" style="border-left-color: <?php echo $tache['categorie_couleur']; ?>">
                    <label class="task-checkbox">
                 
                        <input type="checkbox" <?php echo ($tache['terminee'] == 1) ? 'checked' : ''; ?> 
                               onchange="toggleTaskStatus(<?php echo $tache['id']; ?>, this.checked)">
                        <span class="checkbox-custom"></span>
                    </label>
                    <div class="task-content">
                        <div class="task-title"><?php echo htmlspecialchars($tache['description']); ?></div>
                        <div class="task-details">
                            <span><i class="far fa-calendar"></i> <?php echo date('d/m/Y', strtotime($tache['date_echeance'])); ?></span>
                            <?php if($tache['heure_echeance']): ?>
                                <span><i class="far fa-clock"></i> <?php echo $tache['heure_echeance']; ?></span>
                            <?php endif; ?>
                            <span><i class="fas fa-tag" style="color: <?php echo $tache['categorie_couleur']; ?>;"></i> <?php echo $tache['categorie_nom']; ?></span>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="task-action-btn" onclick="editTask(<?php echo $tache['id']; ?>)"><i class="fas fa-edit"></i></button>
                        
                        <button class="task-action-btn" onclick="deleteTask(<?php echo $tache['id']; ?>)"><i class="fas fa-trash"></i></button>
                    </div>
                </li>
                <?php endforeach; ?>
                <?php if(empty($taches)): ?>
                <li class="task-item" style="justify-content: center; padding: 20px;">
                    <div style="text-align: center; color: #666;">
                        <i class="far fa-smile" style="font-size: 24px; margin-bottom: 10px;"></i>
                        <p>Aucune tâche pour aujourd'hui. Profitez de votre journée!</p>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="add-task-container">
    <form id="add-task-form" action="../Back/ajouttache.php" method="post">
    <div class="add-task-input">
            <i class="fas fa-plus"></i>
            <input type="text" name="description" placeholder="Ajouter une tâche..." required id="task-input">
            <input type="hidden" name="categorie_id" id="categorie_id" value="<?php echo $categories_systeme[0]['id']; ?>">
            <div class="task-quick-options">
                <button type="button" id="date-picker-toggle" class="option-toggle"><i class="far fa-calendar-alt"></i></button>
                <button type="button" id="time-picker-toggle" class="option-toggle"><i class="far fa-clock"></i></button>
                <button type="button" id="category-picker-toggle" class="option-toggle"><i class="fas fa-tag"></i></button>
            </div>
            <button type="submit" class="add">Ajouter</button>
        </div>

        <!-- Options qui apparaîtront au-dessus -->
        <div class="floating-options" id="date-picker-options">
            <div class="option-header">
                <h4>Date d'échéance</h4>
                <button type="button" class="close-options"><i class="fas fa-times"></i></button>
            </div>
            <div class="quick-date-options">
                <button type="button" class="date-option-btn active" data-value="<?php echo date('Y-m-d'); ?>">Aujourd'hui</button>
                <button type="button" class="date-option-btn" data-value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">Demain</button>
                <button type="button" class="date-option-btn" data-value="<?php echo date('Y-m-d', strtotime('+7 day')); ?>">Dans une semaine</button>
            </div>
            <div class="date-picker-container">
                <label>Choisir une date</label>
                <input type="text" name="date_echeance" id="date-picker" class="date-picker" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>

        <div class="floating-options" id="time-picker-options">
            <div class="option-header">
                <h4>Heure de rappel</h4>
                <button type="button" class="close-options"><i class="fas fa-times"></i></button>
            </div>
            <div class="quick-time-options">
                <button type="button" class="time-option-btn" data-value="09:00">9:00</button>
                <button type="button" class="time-option-btn" data-value="12:00">12:00</button>
                <button type="button" class="time-option-btn" data-value="18:00">18:00</button>
                <button type="button" class="time-option-btn" data-value="">Aucune</button>
            </div>
            <div class="time-picker-container">
                <label>Choisir une heure</label>
                <input type="text" name="heure_echeance" id="time-picker" class="time-picker" placeholder="Sélectionner">
            </div>
        </div>

        <div class="floating-options" id="category-picker-options">
            <div class="option-header">
                <h4>Catégorie</h4>
                <button type="button" class="close-options"><i class="fas fa-times"></i></button>
            </div>
            <div class="category-select-container">
                <select name="categorie" id="categorie-select">
                    <?php foreach($categories_systeme as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nom']; ?></option>
                    <?php endforeach; ?>
                    <?php if(!empty($categories_perso)): ?>
                        <optgroup label="Mes listes">
                        <?php foreach($categories_perso as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nom']; ?></option>
                        <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </form>
</div>
    </div>

    <!-- Modal d'ajout de catégorie -->
   

   

   


    


    <script>document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si le bouton existe
    const toggleBtn = document.querySelector('.theme-toggle-btn');
    if (toggleBtn) {
        console.log('Bouton trouvé:', toggleBtn);
        
        // Ajouter un événement de clic explicite
        toggleBtn.addEventListener('click', function(e) {
            console.log('Bouton cliqué!');
            e.stopPropagation();
            const themeDropdown = document.querySelector('.theme-dropdown');
            if (themeDropdown) {
                themeDropdown.classList.toggle('show');
                console.log('Dropdown toggled:', themeDropdown);
            } else {
                console.log('Dropdown non trouvé!');
            }
        });
    } else {
        console.log('Bouton non trouvé!');
    }




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
});</script>

<script src="../js/main.js"></script>
    <script src="../js/interface.js"></script>
    <script src="../js/theme.js"></script>
    <script src="../js/reponsive.js?v=1.0"></script>

    <script src="../js/modal-param.js"></script>
    <script src="../js/modal-cat.js"></script>
</div> 
</body>
