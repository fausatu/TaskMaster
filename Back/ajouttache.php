<?php
include("bd.php");
session_start();
// Remplacez le gestionnaire de soumission de formulaire actuel dans ajouttache.php avec ce code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = htmlspecialchars($_POST['description']);
    
    // Récupérer l'ID de catégorie depuis le champ caché qui est maintenant correctement mis à jour
    $categorie_id = isset($_POST['categorie_id']) ? intval($_POST['categorie_id']) : 1;
    
    // Si le menu déroulant de sélection de catégorie a également été soumis, utilisez cette valeur
    if (isset($_POST['categorie']) && !empty($_POST['categorie'])) {
        $categorie_id = intval($_POST['categorie']);
    }
    
    $date_echeance = !empty($_POST['date_echeance']) ? $_POST['date_echeance'] : date('Y-m-d');
    $heure_echeance = !empty($_POST['heure_echeance']) ? $_POST['heure_echeance'] : NULL;
    $date_rappel = !empty($_POST['date_rappel']) && !empty($_POST['heure_rappel']) ?
                $_POST['date_rappel'] . ' ' . $_POST['heure_rappel'] : NULL;
        
    $utilisateur_id = $_SESSION['id'];
        
    try {
        $stmt = $cnx->prepare("INSERT INTO taches (description, date_echeance, heure_echeance, date_rappel, categorie_id, utilisateur_id)
                            VALUES (:description, :date_echeance, :heure_echeance, :date_rappel, :categorie_id, :utilisateur_id)");
                
        $stmt->execute([
            ':description' => $description,
            ':date_echeance' => $date_echeance,
            ':heure_echeance' => $heure_echeance,
            ':date_rappel' => $date_rappel,
            ':categorie_id' => $categorie_id,
            ':utilisateur_id' => $utilisateur_id
        ]);
                
        $_SESSION["succes"] = '<div id="succes" class="succes alert alert-success">Tâche ajoutée avec succès!</div>';
    } catch(PDOException $e) {
        $_SESSION["erreur"] = '<div id="succes" class="succes alert alert-danger">Erreur lors de l\'ajout de la tâche: ' . $e->getMessage() . '</div>';
    }
        
    // Répondre avec JSON pour les requêtes AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        echo json_encode(['success' => true]);
        exit;
    }
    
    // Pour les soumissions de formulaire normales
    header("location: ../Main/todo.php");
    exit();
}
?>