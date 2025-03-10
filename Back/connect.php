<?php
include('bd.php');
session_start();

if (isset($_POST)) {
    if (isset($_POST["email"], $_POST["pass"]) && !empty($_POST["email"]) && !empty($_POST["pass"])) {
        $email = trim($_POST["email"]);
        $pass = $_POST["pass"];

        // Validation du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["erreur"] = "<div id='erreur'class='erreur'>Format d'email invalide</div>";
            header('location: ../index.php');
            exit();
        }

        try {
            // Requête pour récupérer l'utilisateur
            $req = $cnx->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $req->execute(array($email));
            $user = $req->fetch();

            if ($user) {
                // Vérification du mot de passe
                if (password_verify($pass, $user['passe'])) {
                    $_SESSION['login'] = $email;
                    $_SESSION['id'] = $user['id_utilisateur'];
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['profil'] = $user['image_profil'];
                    header('location: ../Main/todo.php');
                    $_SESSION['succes'] = "<div id='succes' class='succes'>Bienvenue {$_SESSION['login']}, vous êtes connecté </div>";
                   
                    exit();
                } else {
                    $_SESSION["erreur"] = "<div id='erreur' class='erreur'>Email ou mot de passe incorrect</div>";
                    header('location: ../index.php');
                    exit();
                }
            } else {
                $_SESSION["erreur"] = "<div id='erreur' class='erreur'>Email ou mot de passe incorrect</div>";
                header('location: ../index.php');
                exit();
            }
        } catch (PDOException $e) {
            // Gestion des erreurs SQL
            $_SESSION["erreur"] = "<div id='erreur' class='erreur'>Une erreur s'est produite lors de la connexion. Veuillez réessayer.</div>";
            header('location: ../index.php');
            exit();
        }

        // Fermeture de la requête
        $req = null;
    } else {
        $_SESSION["erreur"] = "<div class='erreur'>Vous devez remplir tous les champs</div>";
        header('location: ../index.php');
        exit();
    }
}
?>