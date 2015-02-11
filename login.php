<?php
session_start();
session_destroy();
//setcookie("login");
//setcookie("mdp");

$here=str_replace('login.php','',$_SERVER['PHP_SELF']);

header("Location:".$here);
exit();
?>