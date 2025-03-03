  <?php 
      session_start();
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/index.css">
    <script src="../js/main.js"></script>
</head>
<body>

<?php
   if (isset($_SESSION["erreur"])) {
       echo   $_SESSION["erreur"] ;
       unset($_SESSION["erreur"]); 
   }
   ?>
<div class="register-container">
        <h1>Inscription</h1>
        <form action="../Back/inscrit.php" method="POST">
           
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
        
            <div class="input-group">
                <label for="pass">Mot de passe</label>
                <input type="password" id="pass" name="pass" required>
            </div>
           
            <div class="input-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
           
            <div class="input-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            
            <button type="submit">S'inscrire</button>
        </form>
        
        <div class="login-link">
            <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a></p>
        </div>
    </div>
</body>
</html>