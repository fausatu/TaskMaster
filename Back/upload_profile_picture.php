<?php
require_once './bd.php';
session_start();

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fonction pour journaliser les erreurs
function logError($message) {
   $_SESSION['erreur']= "<div class='erreur'>".error_log($message) . "</div>";
    header("Location: ../Main/todo.php");
    exit();
   
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Vérifier si un fichier a été téléchargé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    echo "<pre>"; print_r($_FILES); echo "</pre>"; // Debug
    
    // Vérifier s'il y a des erreurs de téléchargement
    if ($_FILES['profile_picture']['error'] > 0) {
        $error = "";
        switch ($_FILES['profile_picture']['error']) {
            case 1: $_SESSION['erreur'] = "<div class='erreur'>La taille du fichier dépasse la limite autorisée par le serveur</div>"; break;
            case 2: $_SESSION['erreur'] = "<div class='erreur'>La taille du fichier dépasse la limite autorisée par le formulaire</div> " ; break;
            case 3: $_SESSION['erreur'] = "<div class='erreur'>Le fichier n'a été que partiellement téléchargé </div> "; break;
            case 4: $_SESSION['erreur'] = " <div class='erreur'>Aucun fichier n'a été téléchargé </div> "; break;
            default: $_SESSION['erreur'] = "<div class='erreur'>Erreur inconnue </div>";
        }
        logError($error);
        exit();
    }
    
    $userId = $_SESSION['id'];
    $uploadDir = '../uploads/profiles/';
    
    // Générer un nom de fichier unique pour éviter les conflits
    $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $newFileName = 'profile_' . $userId . '_' . time() . '.' . $fileExtension;
    $uploadFile = $uploadDir . $newFileName;
    
    // Vérifier le type de fichier
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
        $_SESSION["erreur"]="<div class='erreur'>Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.</div>";
        header("Location: ../Main/todo.php");
        exit();
    }
    
    // Vérifier la taille du fichier (max 2MB)
    if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
         $_SESSION["erreur"]="<div class='erreur'>La taille du fichier dépasse la limite autorisée (2MB).</div>";
        header("Location: ../Main/todo.php");
        exit();
    }
    
   
    
    
    
    // Déplacer le fichier téléchargé
    try {
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            // Mettre à jour la base de données avec le chemin de l'image
            $stmt = $cnx->prepare("UPDATE utilisateurs SET image_profil = ? WHERE id_utilisateur = ?");
            
            if (!$stmt) {
                 $_SESSION["erreur"]="<div class='erreur'>Une erreur s'est produite lors de la mise à jour de la photo de profil. Veuillez réessayer.</div>";
                exit();
            }
            
            if ($stmt->execute([$uploadFile, $userId])) {
                $_SESSION['profil'] = $uploadFile; // Mettre à jour la session
                $_SESSION['success'] = " <div class='succes'>Photo de profil mise à jour avec succès. </div>";
                header("Location: ../Main/todo.php");
            
            exit();
            } else {
                 $_SESSION["erreur"]="<div class='erreur'>Une erreur s'est produite lors de la mise à jour de la photo de profil. Veuillez réessayer.</div>";
            }
        } else {
            $_SESSION["erreur"]="<div class='erreur'>Une erreur s'est produite lors du téléchargement du fichier. Veuillez réessayer.</div>";
            header("Location: ../Main/todo.php");
        }
    } catch (Exception $e) {
         $_SESSION["erreur"]="<div class='erreur'>Une erreur s'est produite lors du téléchargement du fichier. Veuillez réessayer.</div>";
        header("Location: ../Main/todo.php");
    }
} else {
    $_SESSION["erreur"]="<div class='erreur'>Aucun fichier n'a été téléchargé.</div>";
    header("Location: ../Main/todo.php");
}
?>