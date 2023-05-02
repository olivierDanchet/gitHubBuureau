<?php
session_start();
setcookie('nom');
setcookie('prenom');
setcookie('fidelite');
setcookie('id');
session_destroy();

var_dump($_COOKIE);
header('location:index.php')
?>
