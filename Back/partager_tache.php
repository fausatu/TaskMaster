<?php
session_start();
include("bd.php");
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté.']);
    exit;
}

// Récupérer l'ID de la catégorie "Tâches partagées"
$query = "SELECT id FROM categories WHERE nom = 'Taches partagees'";
$prepare = $cnx->prepare($query);
$prepare->execute();
$categorie = $prepare->fetch(PDO::FETCH_ASSOC);
$categorie_id = $categorie['id'];

// Récupérer et valider les données
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['taskId']) || empty($data['sharedUserEmail']) || empty($data['permissions'])) {
    echo json_encode(['success' => false, 'error' => 'Données manquantes ou invalides.']);
    exit;
}

$taskId = intval($data['taskId']);
$sharedUserEmail = filter_var($data['sharedUserEmail'], FILTER_VALIDATE_EMAIL);
$permissions = htmlspecialchars($data['permissions']);

if (!$sharedUserEmail) {
    echo json_encode(['success' => false, 'error' => 'Adresse e-mail invalide.']);
    exit;
}

// Valider les permissions
$allowedPermissions = ['lecture', 'modification'];
if (!in_array($permissions, $allowedPermissions)) {
    echo json_encode(['success' => false, 'error' => 'Permissions invalides.']);
    exit;
}

try {
    $cnx->beginTransaction();

    // 1. Récupérer l'ID de l'utilisateur avec qui partager
    $query = "SELECT id_utilisateur FROM utilisateurs WHERE email = ?";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$sharedUserEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'Aucun utilisateur trouvé avec cet e-mail.']);
        $cnx->rollBack();
        exit;
    }
    $sharedUserId = $user['id_utilisateur'];

    // 2. Récupérer les détails de la tâche à partager
    $query = "SELECT description, date_creation, date_echeance, heure_echeance, date_rappel 
              FROM taches WHERE id = ?";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo json_encode(['success' => false, 'error' => 'Tâche non trouvée.']);
        $cnx->rollBack();
        exit;
    }

    // 3. Créer une nouvelle tâche dans la table `taches` avec la catégorie "Tâches partagées"
    $query = "
        INSERT INTO taches (description, date_creation, date_echeance, heure_echeance, date_rappel, categorie_id, utilisateur_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $cnx->prepare($query);
    $stmt->execute([
        $task['description'],
        $task['date_creation'],
        $task['date_echeance'],
        $task['heure_echeance'],
        $task['date_rappel'],
        $categorie_id, // Catégorie "Tâches partagées"
        $sharedUserId // L'utilisateur avec qui la tâche est partagée
    ]);

    $newTaskId = $cnx->lastInsertId(); // Récupérer l'ID de la nouvelle tâche

    // 4. Enregistrer le partage dans la table `taches_partagees`
    $query = "INSERT INTO taches_partagees (tache_id, utilisateur_id, partage_par, permissions) VALUES (?, ?, ?, ?)";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$taskId, $sharedUserId, $_SESSION['id'], $permissions]); // Utiliser $_SESSION['id'] pour partage_par

    $cnx->commit(); // Valider la transaction
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}