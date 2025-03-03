

Start new chat
Chats
Recents
Dynamically Updating Task List by Category
Fixing Task Category and Display Issues in TaskMaster
Feedback on TaskMaster Web App
Improved Date and Time Notification Selection
Completing a Task Management Web App
Customizable Category-Specific Content Display
Database Structure for Task Management App
Implementing PWA Functionality for a Todo List
Integrating Google Sign-In to Your App
Updating PWA manifest and index.html files
Python Web Development Example with Flask
Introduction to AJAX and JavaScript for Beginners
Networking System Programming Exam
Updating to Windows 11 22H2 - Partition Error
Fixing Undefined Index Errors in Student Dashboard
Creating a Local Network with a Switch
Student Bulletin Download Page
Designing a Visually Appealing User Page
Expanding the Fast&Delices Admin Dashboard
Elegant Admin Dashboard Styling
Improving Delivery Driver Dashboard Design
Troubleshooting redirect to recap.php after order placement
Optimizing a Restaurant Ordering System
Optimizing Web Design and Performance
Incomplete Restaurant Menu PHP Code
Fast Food Database and Admin/Client Interface
Architecture of 2G, 3G, 4G, and 5G Cellular Networks
Handling Multiple Control Notes for Students
Single-Page Student Dashboard
Reviewing Security and Implementation of Teacher Profile Page
View all
Free plan

FA
akintoyessef@gmail.com
FA


Dynamically Updating Task List by Category

1

Share

<?php include("../Back/bd.php"); include("../Back/donnee.php"); ?> <!DOCTYPE html> <html lang="fr"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device




Retry
FA
function toggleTaskStatus(taskId, isChecked) {     fetch('modifier_statut_tache.php', {         method: 'POST',         headers: {             'Content-Type': 'application/x-www-form-urlencoded',         },         body: id=${taskId}&terminee=${isChecked ? 1 : 0}     })     .then(response => response.json())     .then(data => {         if (data.success) {             // Animation de suppression si la tâche est terminée             if (isChecked) {                 setTimeout(() => {                     const taskElement = document.querySelector(.task-item[data-id="${taskId}"]);                     if (taskElement) {                         taskElement.style.opacity = "0";                         setTimeout(() => {                             taskElement.remove();                         }, 300);                     }                 }, 1000);             }         }     })     .catch(error => console.error('Erreur:', error)); } ce code permet de modfier une tache juste ou son statu aussi



<?php
// Créer ce fichier à l'emplacement ../Back/charger_taches.php

session_start();
include("bd.php");
include("fonctions.php"); // Si vous avez des fonctions utilitaires

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["id_utilisateur"])) {
    echo "Erreur: Utilisateur non connecté";
    exit;
}

// Récupérer l'ID de la catégorie
$categorie_id = isset($_POST['categorie_id']) ? intval($_POST['categorie_id']) : 0;

// Si aucune catégorie n'est spécifiée, arrêter
if ($categorie_id <= 0) {
    echo "Erreur: Catégorie invalide";
    exit;
}

// Récupérer les tâches pour cette catégorie
try {
    // Requête SQL pour récupérer les tâches de la catégorie sélectionnée
    $requete = $bdd->prepare("
        SELECT t.*, c.nom as categorie_nom, c.couleur as categorie_couleur
        FROM taches t
        JOIN categories c ON t.id_categorie = c.id
        WHERE t.id_utilisateur = :id_utilisateur 
        AND (t.id_categorie = :id_categorie OR :id_categorie IN (
            SELECT id FROM categories WHERE nom = 'Aujourd\\'hui' AND 
            DATE(t.date_echeance) = CURDATE() OR
            nom = 'Cette semaine' AND 
            YEARWEEK(t.date_echeance, 1) = YEARWEEK(CURDATE(), 1) OR 
            nom = 'Important' AND 
            t.important = 1
        ))
        ORDER BY t.date_echeance ASC, t.heure_echeance ASC
    ");
    
    $requete->execute([
        ':id_utilisateur' => $_SESSION["id_utilisateur"],
        ':id_categorie' => $categorie_id
    ]);
    
    $taches = $requete->fetchAll(PDO::FETCH_ASSOC);
    
    // Générer le HTML des tâches
    ob_start();
    ?>
    <ul class="task-list">
        <?php foreach ($taches as $tache): ?>
        <li class="task-item" data-id="<?php echo $tache['id']; ?>" style="border-left-color: <?php echo $tache['categorie_couleur']; ?>">
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
                <p>Aucune tâche pour cette catégorie. Tout est bien organisé!</p>
            </div>
        </li>
        <?php endif; ?>
    </ul>
    <?php
    $html = ob_get_clean();
    echo $html;
    
} catch (PDOException $e) {
    echo "Erreur de base de données: " . htmlspecialchars($e->getMessage());
}
?>
Last edited just now


Publish
Claude