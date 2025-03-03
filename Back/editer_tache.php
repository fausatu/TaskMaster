<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("location:../connexion/connexion.php");
    exit();
}

// Vérifier si le fichier de connexion à la BDD existe
if (!file_exists("bd.php")) {
    $_SESSION["erreur"] = "Erreur critique: Fichier de connexion à la base de données manquant.";
    header("location:../Main/todo.php");
    exit();
}

require_once("bd.php");

// Fonction pour valider le format de date (YYYY-MM-DD)
function isValidDate($date) {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }
    $dateArray = explode('-', $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

// Fonction pour valider le format d'heure (HH:MM)
function isValidTime($time) {
    if (empty($time)) {
        return true; // L'heure est optionnelle
    }
    return preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])$/', $time);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $tache_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $date_echeance = isset($_POST['date_echeance']) ? trim($_POST['date_echeance']) : '';
    $heure_echeance = isset($_POST['heure_echeance']) ? trim($_POST['heure_echeance']) : '';
    $categorie_id = isset($_POST['categorie_id']) ? (int)$_POST['categorie_id'] : 0;

    // Validation des données
    $errors = [];

    if ($tache_id <= 0) {
        $errors[] = "ID de tâche invalide.";
    }

    if (empty($description)) {
        $errors[] = "La description est requise.";
    }

    if (empty($date_echeance) || !isValidDate($date_echeance)) {
        $errors[] = "Format de date invalide.";
    }

    if (!isValidTime($heure_echeance)) {
        $errors[] = "Format d'heure invalide.";
    }

    if ($categorie_id <= 0) {
        $errors[] = "Catégorie invalide.";
    } else {
        // Vérifier que la catégorie appartient bien à l'utilisateur
        try {
            $stmt = $cnx->prepare("SELECT COUNT(*) FROM categories WHERE id = ? AND utilisateur_id = ?");
            $stmt->execute([$categorie_id, $_SESSION['id']]);
            if ($stmt->fetchColumn() == 0) {
                $errors[] = "Catégorie non autorisée.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la vérification de la catégorie: " . $e->getMessage();
        }
    }

    // Si aucune erreur, procéder à la mise à jour
    if (empty($errors)) {
        try {
            // Préparer la requête de mise à jour
            $terminee = isset($tache['terminee']) ? $tache['terminee'] : 0; // Récupérer la valeur actuelle
$stmt = $cnx->prepare("UPDATE taches SET description = ?, date_echeance = ?, heure_echeance = ?, categorie_id = ?, terminee = ?, date_modification = NOW() WHERE id = ? AND utilisateur_id = ?");
$stmt->execute([
    $description,
    $date_echeance,
    $heure_echeance,
    $categorie_id,
    $terminee,
    $tache_id,
    $_SESSION['id']
]);

            // Vérifier si la tâche a été mise à jour
            if ($stmt->rowCount() > 0) {
                $_SESSION["succes"] = "Tâche mise à jour avec succès.";
            } else {
                $_SESSION["erreur"] = "Aucune tâche trouvée avec cet ID ou aucune modification n'a été apportée.";
            }
        } catch (PDOException $e) {
            // Gestion des erreurs
            $_SESSION["erreur"] = "Erreur lors de la mise à jour de la tâche : " . $e->getMessage();
        }
    } else {
        $_SESSION["erreur"] = implode("<br>", $errors);
        // Rediriger vers la page d'édition avec l'ID
        header("location:editer_tache.php?id=" . $tache_id);
        exit();
    }

    // Redirection vers la page principale
    header("location:../Main/todo.php");
    exit();
}

// Récupération de la tâche à éditer
$tache = null;
$categories = [];

// Si la méthode n'est pas POST, on récupère l'ID de la tâche à éditer
if (isset($_GET['id'])) {
    $tache_id = (int)$_GET['id'];

    // Vérifier que l'ID est valide
    if ($tache_id > 0) {
        try {
            // Préparer la requête pour récupérer la tâche
            $stmt = $cnx->prepare("SELECT * FROM taches WHERE id = ? AND utilisateur_id = ?");
            $stmt->execute([$tache_id, $_SESSION['id']]);
            $tache = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si la tâche existe
            if (!$tache) {
                $_SESSION["erreur"] = "Tâche non trouvée.";
                header("location:../Main/todo.php");
                exit();
            }

            // Charger les catégories pour le sélecteur
            $stmt = $cnx->prepare("SELECT * FROM categories WHERE utilisateur_id = ? ORDER BY nom");
            $stmt->execute([$_SESSION['id']]);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($categories)) {
                $_SESSION["erreur"] = "Vous devez créer au moins une catégorie avant de pouvoir éditer une tâche.";
                header("location:../Main/todo.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION["erreur"] = "Erreur lors de la récupération des données : " . $e->getMessage();
            header("location:../Main/todo.php");
            exit();
        }
    } else {
        $_SESSION["erreur"] = "ID de tâche invalide.";
        header("location:../Main/todo.php");
        exit();
    }
} else {
    $_SESSION["erreur"] = "Aucun ID de tâche spécifié.";
    header("location:../Main/todo.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer Tâche - TaskFlow</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Styles additionnels en cas de problème avec le fichier CSS externe */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-primary, .btn-secondary {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-secondary {
            background-color: #f44336;
            color: white;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .actions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Éditer la Tâche</h2>
        <?php if (isset($_SESSION["erreur"])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION["erreur"];
                    unset($_SESSION["erreur"]); 
                ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION["succes"])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION["succes"];
                    unset($_SESSION["succes"]); 
                ?>
            </div>
        <?php endif; ?>
        
        <form action="editer_tache.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($tache['id']); ?>">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($tache['description']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date_echeance">Date d'échéance</label>
                <input type="date" id="date_echeance" name="date_echeance" value="<?php echo htmlspecialchars($tache['date_echeance']); ?>" required>
            </div>
            <div class="form-group">
                <label for="heure_echeance">Heure de rappel</label>
                <input type="time" id="heure_echeance" name="heure_echeance" value="<?php echo htmlspecialchars($tache['heure_echeance']); ?>">
                <small>Optionnel</small>
            </div>
            <div class="form-group">
                <label for="categorie_id">Catégorie</label>
                <select id="categorie_id" name="categorie_id" required>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo htmlspecialchars($categorie['id']); ?>" <?php echo ($categorie['id'] == $tache['categorie_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categorie['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="actions">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="../Main/todo.php" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        // Validation côté client
        document.querySelector('form').addEventListener('submit', function(e) {
            let description = document.getElementById('description').value.trim();
            let dateEcheance = document.getElementById('date_echeance').value.trim();
            
            if (description === '') {
                e.preventDefault();
                alert('La description est requise.');
                return;
            }
            
            if (dateEcheance === '') {
                e.preventDefault();
                alert('La date d\'échéance est requise.');
                return;
            }
        });
    </script>
</body>
</html>