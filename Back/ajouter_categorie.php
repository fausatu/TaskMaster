<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("location:../connexion/connexion.php");
    exit();
}

require_once("../Back/bd.php"); // Assurez-vous d'avoir un fichier de connexion à la BDD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = trim($_POST['nom']);
    $icone = trim($_POST['icone']);
    $couleur = trim($_POST['couleur']);
    $utilisateur_id = $_SESSION['id'];

    // Validation des données
    if (empty($nom) || empty($icone) || empty($couleur)) {
        $_SESSION["erreur"] = "Tous les champs sont requis.";
        header("location:../Main/todo.php");
        exit();
    }

    try {
        // Préparer la requête d'insertion avec des points d'interrogation comme placeholders
        $query = "INSERT INTO categories (nom, icone, couleur, type, utilisateur_id) 
                  VALUES (?, ?, ?, 'personnalise', ?)";
        $stmt = $cnx->prepare($query);
        
        // Exécuter la requête avec les valeurs
        $stmt->execute([
            $nom,
            $icone,
            $couleur,
            $utilisateur_id
        ]);

        // Message de succès
        $_SESSION["succes"] = "Catégorie ajoutée avec succès.";
    } catch (PDOException $e) {
        // Gestion des erreurs
        $_SESSION["erreur"] = "Erreur lors de l'ajout de la catégorie : " . $e->getMessage();
    }

    // Redirection vers la page principale
    header("location:../Main/todo.php");
    exit();
}




// charger les categories

$utilisateur_id = $_SESSION['id'];

// Charger les catégories système
$stmt = $cnx->prepare("SELECT * FROM categories WHERE type = 'systeme'");
$stmt->execute();
$categories_systeme = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Charger les catégories personnalisées de l'utilisateur
$stmt = $cnx->prepare("SELECT * FROM categories WHERE type = 'personnalise' AND utilisateur_id = :utilisateur_id");
$stmt->execute([':utilisateur_id' => $utilisateur_id]);
$categories_perso = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compter les tâches pour chaque catégorie
foreach ($categories_systeme as &$categorie) {
    $stmt = $cnx->prepare("SELECT COUNT(*) FROM taches WHERE categorie_id = :categorie_id AND utilisateur_id = :utilisateur_id");
    $stmt->execute([
        ':categorie_id' => $categorie['id'],
        ':utilisateur_id' => $utilisateur_id
    ]);
    $categorie['nombre_taches'] = $stmt->fetchColumn();
}

foreach ($categories_perso as &$categorie) {
    $stmt = $cnx->prepare("SELECT COUNT(*) FROM taches WHERE categorie_id = :categorie_id AND utilisateur_id = :utilisateur_id");
    $stmt->execute([
        ':categorie_id' => $categorie['id'],
        ':utilisateur_id' => $utilisateur_id
    ]);
    $categorie['nombre_taches'] = $stmt->fetchColumn();
}

echo json_encode([
    'categories_systeme' => $categories_systeme,
    'categories_perso' => $categories_perso
]);
?>