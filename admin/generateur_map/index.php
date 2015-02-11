<?php
session_start();
include("headeur.php");


if(isset($_POST['RANDOMNESS'])){
	
	
	if(isset($_SESSION['param']['RANDOMNESS'])){

		$_SESSION['param']['RANDOMNESS']= $_POST['RANDOMNESS'];
	    $_SESSION['param']['SPARSENESS']=$_POST['SPARSENESS'];
		$_SESSION['param']['DEADENDS']=$_POST['DEADENDS'];
	    $_SESSION['param']['WIDTH']=$_POST['WIDTH'];
	    $_SESSION['param']['HEIGHT']= $_POST['HEIGHT'];
	    $_SESSION['param']['ROOM_MIN_WIDTH']= $_POST['ROOM_MIN_WIDTH'];
	    $_SESSION['param']['ROOM_MAX_WIDTH']= $_POST['ROOM_MAX_WIDTH'];
	    $_SESSION['param']['ROOM_MIN_HEIGHT']=$_POST['ROOM_MIN_HEIGHT'];
	    $_SESSION['param']['ROOM_MAX_HEIGHT']= $_POST['ROOM_MAX_HEIGHT'];
	    $_SESSION['param']['ROOMCOUNT']= $_POST['ROOMCOUNT'];

		$post=formulaire($_POST);
	}else{
		$post=formulaire($_POST); // Affichage du formulaire si il à déja été modifié
	}
	
	define('SPECIAL_AOM',0); // 1 pour spécial AOM c'est à dire les portes toujours sur les Case nord sud est ouest des salle  qui font toutes 3*3 cases !!! à 0 les salles peuvent avoir les portes dans tous les sens. et le placement des salles est géré différement !
	define('RANDOMNESS',$post['RANDOMNESS']);
	define('SPARSENESS',$post['SPARSENESS']);
	define('DEADENDS',$post['DEADENDS']);
	define('WIDTH',$post['WIDTH']);
	define('HEIGHT',$post['HEIGHT']);
	define('ROOM_MIN_WIDTH',$post['ROOM_MIN_WIDTH']);
	define('ROOM_MAX_WIDTH',$post['ROOM_MAX_WIDTH']);
	define('ROOM_MIN_HEIGHT',$post['ROOM_MIN_HEIGHT']);
	define('ROOM_MAX_HEIGHT',$post['ROOM_MAX_HEIGHT']);
	define('ROOMCOUNT',$post['ROOMCOUNT']);
	
	//Inutile
	define('DOOR',$post['DOOR']);
	define('DIR1DOOR',$post['DIR1DOOR']);
	define('INITX',$post['INITX']);
	define('INITY',$post['INITY']);

	

	
?>
<form name="dungeon" method="post" action="./index.php">
Nom du fichier : <input type="text" name="dungeonName" value="nom du donjon"><input type="submit" value="Enregistrer">
</form>
<?php


$donjon = new Dungeon();  
$donjon->init(WIDTH,HEIGHT); // Initialisation de la taille
$donjon->premiere_cellule(INITX,INITY); // On initialise la premiere celulle (ccX et ccY )
$donjon->generer_labyrinthe(); // On génere le labyrinthe parfait
$donjon->sparsify(); // Réduit la densité
$donjon->deadends(); // Termine les cul de sac
$donjon->salles(); // Genere les salles
//$donjon->affichage_editeur_position(0,0); // Affichage de l'apercu de la carte 
$donjon->affichage_route();
$_SESSION['dungeon_generate']=serialize($donjon); // On balance le donjon généré dans une variable session.



}else{
	$post=formulaire($_SESSION['param']); // On met le formulaire par defaut
}

if(isset($_SESSION['dungeon_generate']) AND isset($_POST['dungeonName'])){
$donjon = unserialize($_SESSION['dungeon_generate']);
$donjon->nom=$_POST['dungeonName'];
$donjon->enregistrer($_POST['dungeonName']);
$donjon->affichage_editeur_position(0,0);
echo ''.$_POST['dungeonName'].'.djn sauvegardé avec succès!';
}



?>
</font>