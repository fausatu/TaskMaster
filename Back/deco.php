<?php

session_start();

// Supprimer toutes les variables de session
$_SESSION = array();

// Détruire la session côté serveur
session_destroy();

// Supprimer le cookie de session si présent
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}


header("Location: ../index.php");
exit();
?>