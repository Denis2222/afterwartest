<?php
require_once("dungeon.class.php");
require_once("parametres_generateur_defaut.php");
require_once("functions.php");
if(!isset($title)){
$title="Gestionnaire de Donjon ;-)";
}
if(isset($_GET['session']) AND $_GET['session'] == "destroy"){

session_destroy();
}
?>
<html>
   <head>
       <title><?php echo $title; ?></title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	   <script src="./fonctions.js" type="text/javascript"></script> 
       <link rel="stylesheet" media="screen" type="text/css" title="Design" href="./img/design.css" />
	   <link rel="stylesheet" type="text/css" href="./img/decoration.css">
	   <script type="text/javascript" src="./js/tooltip.js"></script>
	   



   </head>
<body>

<div id="tooltip"></div>

<div align="center">
<a href="./index.php">Generateur</a> <?php echo tips("Permet de génerer et d'enregistrer un donjon selon des paramétres."); ?> | <a href="editeur.php"> Editeur d'Etage</a> <?php echo tips("Permet d'éditer un étage enregistré."); ?> | <a href="exportXML.php">Editeur de donjon</a> <?php echo tips("Permet de compiler le donjon final , en positionant les étages , et en indiquant les paramétres nécéssaire à l'utilisation de celui ci."); ?> |  <a href="<?php echo $_SERVER['PHP_SELF'].'?session=destroy'; ?>"> Vider la session </a><?php echo tips("Vide le cache de l'Editeur de donjon ET met par defaut les paramétres du Générateur. "); ?>
</div>
<hr>