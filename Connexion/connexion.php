<?php
 include('../Back/bd.php');
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
<?php if (isset($_SESSION["erreur"])) {
      if (isset($_SESSION["erreur"])) {
          echo $_SESSION["erreur"];
          unset($_SESSION["erreur"]); 
      }
      
  }
  ?>

<div class="login-container">
      <h1>Connexion</h1>
      <form id="normal-login" action="../Back/connect.php" method="POST">
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
          <label for="pass">Mot de passe</label>
          <input type="password" id="pass" name="pass" required>
        </div>
        <button type="submit">Se connecter</button> <br> <br>
        <a href="inscription.php">Pas de compte? Creer en un.</a>
      </form>
</div>
</body>
</html>