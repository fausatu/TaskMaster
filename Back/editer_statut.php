<?php
session_start();
require_once("bd.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

// Vérifier les paramètres requis
if (!isset($_POST['task_id']) || !isset($_POST['status'])) {
    echo json_encode(['error' => 'Paramètres manquants']);
    exit;
}

$taskId = $_POST['task_id'];
$status = (int)$_POST['status'];
$userId = $_SESSION['id'];

// Vérifier que la tâche appartient à l'utilisateur
$stmt = $cnx->prepare("SELECT id FROM taches WHERE id = :task_id AND utilisateur_id = :utilisateur_id");
$stmt->execute([
    ':task_id' => $taskId,
    ':utilisateur_id' => $userId
]);

if (!$stmt->fetch()) {
    echo json_encode(['error' => 'Accès non autorisé à cette tâche']);
    exit;
}

// Mettre à jour le statut de la tâche
$stmt = $cnx->prepare("UPDATE taches SET terminee = :status WHERE id = :task_id AND utilisateur_id = :utilisateur_id");
$result = $stmt->execute([
    ':status' => $status,
    ':task_id' => $taskId,
    ':utilisateur_id' => $userId
]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Erreur lors de la mise à jour']);
}
?>