
<?php include("../Back/bd.php"); include("../Back/donnee.php"); ?> <!DOCTYPE html> <html lang="fr"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device







<?php

session_start();
include("bd.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["id"])) {
      header("Location: ../index.php");
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
                <button class="task-action-btn" onclick="shareTask(<?php echo $tache['id']; ?>)"><i class="fas fa-share-alt"></i></button>
                <button class="task-action-btn" onclick="deleteTask(<?php echo $tache['id']; ?>)"><i class="fas fa-trash"></i></button>
            </div>

        </li>
        <?php endforeach; ?>
        <?php if(empty($taches)): ?>
        <li class="task-item" style="justify-content: center; padding: 20px;">
            <div style="text-align: center; color: #666;">
                <i class="far fa-smile" style="font-size: 24px; margin-bottom: 10px;"></i>
                <p>Aucune tâche pour cette catégorie.</p>
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
