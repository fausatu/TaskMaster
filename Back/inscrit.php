<?php 
  include("bd.php");
  session_start();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["email"], $_POST["pass"], $_POST["nom"], $_POST["prenom"]) &&
          !empty($_POST["email"]) && !empty($_POST["pass"]) && !empty($_POST["nom"]) && !empty($_POST["prenom"])) {
          
          $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["erreur"] = "<div id='erreur'class='erreur'>Format d'email invalide</div>";
            header('location: ../index.php');
            exit();
        }
          $pass = filter_input(INPUT_POST, 'pass');
          $hash = password_hash($pass, PASSWORD_DEFAULT);
          $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

          // Verification de l'existance de l'utilisateur
          $verif = $cnx->prepare("SELECT * FROM utilisateurs WHERE email = ?");
          $verif->execute(array($email));
          if ($verif->rowCount() > 0) {
              $_SESSION["erreur"] = "<div id='erreur' class='erreur'>Utilisateur existe déjà, connectez-vous</div>";
              header("location: ../connexion/inscription.php");
          } else {
              $req = $cnx->prepare("INSERT INTO utilisateurs (email, passe, nom, prenom) VALUES (?,?,?,?)");
              $req->execute(array($email, $hash, $nom, $prenom));
              $req->closeCursor();
              if ($req) {
                  $_SESSION["succes"] = "<div id='succes' class='succes'>Vous etes inscrits, connectez-vous</div>";
                  header('Location: ../Connexion/connexion.php');
              } else {
                  $_SESSION["erreur"] = "<div id='erreur' class='erreur'>Erreur lors de l'inscription</div>";
                  header("location: ../connexion/inscription.php");
              }
          }
      } 
  }

 

?>