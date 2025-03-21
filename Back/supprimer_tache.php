<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("location:../connexion/connexion.php");
    exit();
}

require_once("../Back/bd.php"); // Assurez-vous d'avoir un fichier de connexion à la BDD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID de la tâche à supprimer
    $tache_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // Vérifier que l'ID est valide
    if ($tache_id > 0) {
        try {
            // Préparer la requête de suppression
            $stmt = $cnx->prepare("DELETE FROM taches WHERE id = :id AND utilisateur_id = :utilisateur_id");
            $stmt->execute([
                ':id' => $tache_id,
                ':utilisateur_id' => $_SESSION['id']
            ]);

            // Vérifier si la tâche a été supprimée
            if ($stmt->rowCount() > 0) {
                $_SESSION["succes"] = "Tâche supprimée avec succès.";
            } else {
                $_SESSION["erreur"] = "Aucune tâche trouvée avec cet ID ou vous n'avez pas la permission de la supprimer.";
            }
        } catch (PDOException $e) {
            // Gestion des erreurs
            $_SESSION["erreur"] = "Erreur lors de la suppression de la tâche : " . $e->getMessage();
        }
    } else {
        $_SESSION["erreur"] = "ID de tâche invalide.";
    }

    // Redirection vers la page principale
    header("location:../Main/todo.php");
    exit();
}
?>