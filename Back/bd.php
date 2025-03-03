<?php   
 $base= "taskMaster" ;
 $host= "localhost";
 $user= "root";
 $pass= "";
 
 try {
     $cnx = new PDO("mysql:host=$host;dbname=$base", $user, $pass);
     $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 } catch (PDOException $e) {
     echo "Connection failed: " . $e->getMessage();
 }







?>