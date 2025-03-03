<?php
session_start();
if(!isset($_SESSION["login"])){
    header("location:../connexion/connexion.php");
    exit();
}

require_once("../Back/bd.php"); // Assurez-vous d'avoir un fichier de connexion à la BDD

// Charger les catégories
$utilisateur_id = $_SESSION['id'];

// Charger les catégories système
$stmt = $cnx->prepare("SELECT * FROM categories WHERE type = 'systeme'");
$stmt->execute();
$categories_systeme = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Charger les catégories personnalisées de l'utilisateur
$stmt = $cnx->prepare("SELECT * FROM categories WHERE type = 'personnalise' AND utilisateur_id = :utilisateur_id");
$stmt->execute([':utilisateur_id' => $utilisateur_id]);
$categories_perso = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compter les tâches pour chaque catégorie système
foreach ($categories_systeme as &$categorie) {
    $stmt = $cnx->prepare("SELECT COUNT(*) FROM taches WHERE categorie_id = :categorie_id ");   
     $stmt->execute([
        ':categorie_id' => $categorie['id'],
        
    ]);
    $categorie['nombre_taches'] = $stmt->fetchColumn();
}
unset($categorie); // Réinitialiser la référence après la boucle

// Compter les tâches pour chaque catégorie personnalisée
foreach ($categories_perso as &$categori) {
    $stmt = $cnx->prepare("SELECT COUNT(*) FROM taches WHERE categorie_id = :categorie_id AND utilisateur_id = :utilisateur_id");
    $stmt->execute([
        ':categorie_id' => $categori['id'],
        ':utilisateur_id' => $utilisateur_id
    ]);
    $categori['nombre_taches'] = $stmt->fetchColumn();
}
unset($categori); // Réinitialiser la référence après la boucle
// Charger toutes les tâches de l'utilisateur (sans filtrer par date)
$stmt = $cnx->prepare("SELECT t.*, c.nom as categorie_nom, c.couleur as categorie_couleur 
                      FROM taches t 
                      JOIN categories c ON t.categorie_id = c.id 
                      WHERE t.utilisateur_id = :utilisateur_id
                      ORDER BY t.date_echeance, t.id DESC");
$stmt->execute([
    ':utilisateur_id' => $utilisateur_id
]);
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>