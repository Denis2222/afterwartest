<?php
session_start();
require_once("include.php");


$j=new Joueur();
$j->loadSimple($_GET['id']);
$pass=md5($j->login.'_'.$j->mdp);
if($pass == $_GET['key']){
  $j->etat = 1;
  $j->save();
  $_SESSION['jid']=$j->id;
}

header("Location: index.php");
exit();
?>
