<?php 
session_start();
session_destroy();
//Redirection
header("Location:connexion.php?logout=true");
exit;
?>