<?php 
session_start(); 
require_once("../Back/bd.php");

$categoryId = $_GET['category_id']; 
$utilisateur_id = $_SESSION['id'];

// Get tasks for selected category
$stmt = $cnx->prepare("SELECT t.*, c.nom as categorie_nom, c.couleur as categorie_couleur
                       FROM taches t
                       JOIN categories c ON t.categorie_id = c.id
                       WHERE t.utilisateur_id = :utilisateur_id 
                       AND (t.categorie_id = :categorie_id OR 
                            (:categorie_id = 0 AND t.date_echeance = CURDATE()))
                       ORDER BY t.id DESC");
$stmt->execute([
    ':utilisateur_id' => $utilisateur_id,
    ':categorie_id' => $categoryId 
]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get category name
$stmt = $cnx->prepare("SELECT nom FROM categories WHERE id = :categorie_id");
$stmt->execute([':categorie_id' => $categoryId]);
$categoryName = $stmt->fetchColumn();

// Custom message for empty categories
$message = '';
if (empty($tasks)) {
    switch ($categoryName) {
        case 'Aujourd\'hui':
            $message = 'Aucune tâche pour aujourd\'hui. Profitez de votre journée !';
            break;
        case 'Important':
            $message = 'Aucune tâche importante pour le moment.';
            break;
        case 'Planifiées':
            $message = 'Aucune tâche planifiée.';
            break;
        default:
            $message = 'Cette catégorie est vide.';
            break;
    }
}

// Return JSON response
echo json_encode([
    'categoryName' => $categoryName,
    'tasks' => $tasks,
    'message' => $message 
]);
?>