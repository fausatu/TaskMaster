<?php
session_start();
include("bd.php");
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté.']);
    exit;
}
$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
try {
    $userId = $_SESSION['id'];
    
    
    $query = "
        SELECT 
            t.id, t.description, t.date_creation, t.date_echeance, t.heure_echeance, 
            t.terminee, t.categorie_id, c.nom as categorie_nom, c.couleur as categorie_couleur,
            CASE WHEN tp.tache_id IS NOT NULL THEN 1 ELSE 0 END as is_shared,
            tp.permissions,
            CONCAT(u.prenom, ' ', u.nom) as shared_by,
            (SELECT nom FROM utilisateurs WHERE id_utilisateur = tp.partage_par) as shared_by_name
        FROM 
            taches t
        JOIN 
            categories c ON t.categorie_id = c.id
        LEFT JOIN 
            taches_partagees tp ON (t.id = tp.tache_id OR t.id = tp.tache_originale_id)
        LEFT JOIN 
            utilisateurs u ON tp.partage_par = u.id_utilisateur
        WHERE 
            t.utilisateur_id = ? 
            AND (c.id = ? OR ? = 0)
            AND (t.terminee = 0 OR 
                (t.terminee = 1 AND DATE(t.date_modification) = CURDATE()))
        ORDER BY 
            t.date_echeance ASC, t.heure_echeance ASC
    ";
    
    $stmt = $cnx->prepare($query);
    $stmt->execute([$userId, $categoryId, $categoryId]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $message = '';
    if (empty($tasks)) {
        // Message personnalisé selon la catégorie
        if ($categoryId == 1) { // Si c'est la catégorie "Aujourd'hui"
            $message = "Aucune tâche pour aujourd'hui. Profitez de votre journée!";
        } elseif ($categoryId == 2) { // Si c'est "À venir"
            $message = "Pas de tâches à venir. Votre planning est libre!";
        } else {
            $message = "Aucune tâche dans cette catégorie. Vous êtes à jour!";
        }
    }
    
    echo json_encode([
        'success' => true,
        'tasks' => $tasks,
        'message' => $message,
        'category_id' => $categoryId
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}