<?php
session_start();
include("headeur.php");
include("fichier.class.php");
?> 
<table width="100%" align="right">
<tr VALIGN="top">
<td align="left"><font size="1">

<?php

if(isset($_GET['delDungeon'])){
 unlink('./dungeon/'.$_GET['delDungeon']);
	if(isset($_GET['current']) AND $_GET['current'] == 1){
		session_destroy();
	}
}


if(isset($_GET['dungeon'])){

if(isset($_SESSION['editeur_donjon'])){
$donjon=unserialize($_SESSION['editeur_donjon']);
$donjon->enregistrer($donjon->nom);
}

	$Fichier = 'dungeon/'.$_GET['dungeon'];
	if (is_file($Fichier)) {
		if ($TabFich = file($Fichier)) {
			//echo "Chargement effectué avec succés";
			$_SESSION['editeur_nom_donjon']=$_GET['dungeon'];
			$_SESSION['editeur_donjon']=$TabFich[0];
			$donjon=unserialize($TabFich[0]);
		}
		else {
			echo "Le fichier ne peut être lu...<br>";
		}
	}
	else {
		echo "Pas de fichier sélectionné<br>";
	}
}else{
	if(isset($_SESSION['editeur_donjon'])){
		$donjon=unserialize($_SESSION['editeur_donjon']);
	}else{
		echo "Générez et sélectionnez un donjon.";
	}
}

	$rep = "dungeon/";
	$dir = opendir($rep);
	echo 'Etages :';
	while ($f = readdir($dir)){
	   if(is_file($rep.$f)){
			if(isset($_SESSION['editeur_nom_donjon']) AND $f == $_SESSION['editeur_nom_donjon']){
			  echo '<li><a href="./editeur.php?dungeon='.$f.'">'.$f.' <=== [|]</a> <a href="./editeur.php?delDungeon='.$f.'&current=1" onclick="return confirm(\' Etes vous sur de vouloir supprimer ce donjon ? \');"><img src="./img/delete.png"/></a>';
		      echo "<br>";
			}else{
		      echo '<li><a href="./editeur.php?dungeon='.$f.'">'.$f.'</a> <a href="./editeur.php?delDungeon='.$f.'" onclick="return confirm(\' Etes vous sur de vouloir supprimer ce donjon ? \');"><img src="./img/delete.png"/></a>';
		      echo "<br>";
			}
	   }
	}
	closedir($dir);
/*
}else{

}*/
?>
</font></td>
<td>
<?php

?>
</td>
<td><?php
if(isset($_GET['X']) AND isset($_GET['Y'])){
	$X=$_GET['X'];
	$Y=$_GET['Y'];
}else{
	$X=0;
	$Y=0;
}
	if(isset($_GET['a'])){
	
		if($_GET['a'] == "creuser" AND isset($_GET['dir'])){
			$donjon->ccX=$_GET['X'];
			$donjon->ccY=$_GET['Y'];
			$donjon->casser_mur($_GET['dir']);
			if($_GET['dir'] ==1) $Y--;
			if($_GET['dir'] ==2) $Y++;
			if($_GET['dir'] ==3) $X++;
			if($_GET['dir'] ==4) $X--;
		}
		if($_GET['a'] == "remplir" AND isset($_GET['dir'])){
			$donjon->ccX=$_GET['X'];
			$donjon->ccY=$_GET['Y'];
			$donjon->remplir_mur($_GET['dir']);
			if($_GET['dir'] ==1) $Y--;
			if($_GET['dir'] ==2) $Y++;
			if($_GET['dir'] ==3) $X++;
			if($_GET['dir'] ==4) $X--;
		}
		if($_GET['a'] == "porte" AND isset($_GET['dir'])){
			//$salle=$donjon->cherche_salle($_GET['X'],$_GET['Y']);
			if($_GET['dir']==1){$YA=$_GET['Y']-1; $XA = $_GET['X'];}
			if($_GET['dir']==2){$YA=$_GET['Y']+1; $XA = $_GET['X'];}
			if($_GET['dir']==3){$YA = $_GET['Y']; $XA=$_GET['X']+1;}
			if($_GET['dir']==4){$YA = $_GET['Y']; $XA=$_GET['X']-1;}
			$donjon->ajouter_porte($_GET['X'],$_GET['Y'],$XA,$YA,$_GET['dir']);//$salle['nb'],
		}
		if($_GET['a']=="deletePorte" AND isset($_GET['id'])){
			$donjon->porte[$_GET['id']]['XD']= -1;
			$donjon->porte[$_GET['id']]['YD']= -1;
			$donjon->porte[$_GET['id']]['dir']= 0;
		}
		
		if($_GET['a'] == "teleporteur" AND isset($_POST['X']) AND isset($_POST['Y']) AND isset($_POST['dungeon'])){
			$donjon->ajouter_teleporteur($_GET['X'],$_GET['Y'],$donjon->nom,$_POST['X'],$_POST['Y'],$_POST['dungeon']);
		}
		
		if($_GET['a']=="monstre" AND isset($_POST['syntaxe_monstre'])){ // Si on ajoute un monstre
			$donjon->ajouter_monstre($X,$Y,$_POST['syntaxe_monstre']);
			echo '<br>Monstre ['.$_POST['syntaxe_monstre'].'] ajouté<br>';
		}
		if($_GET['a']=="deletem" ){
			$donjon->map[$X][$Y]['m']=0;
		}
		
		if($_GET['a']=="action" AND isset($_POST['syntaxe_action'])){ // Si on ajoute une action
			$donjon->ajouter_action($X,$Y,$_POST['syntaxe_action']);
			echo '<br>Actions ['.$_POST['syntaxe_action'].'] ajouté<br>';
		}
		if($_GET['a']=="deletea" ){  // Si on enleve une action
			$donjon->map[$X][$Y]['a']=0;
		}
		if($_GET['a']=="prerequis" AND isset($_POST['syntaxe_prerequis'])){ // Si on ajoute un prerequis
			$donjon->ajouter_prerequis($X,$Y,$_POST['syntaxe_prerequis']);
			echo '<br>Prerequis ['.$_POST['syntaxe_prerequis'].'] ajouté<br>';
		}
		if($_GET['a']=="deletep" ){ // Si on enleve un prerequis
			$donjon->map[$X][$Y]['p']=0;
		}
		
		if($_GET['a']=="enregistrer"){
			$donjon->enregistrer($donjon->nom);
			echo '<br>Enregistrement effectué<br>';
		}
		if($_GET['a']=="export_xml"){
			$donjon->export_xml($donjon->nom);
			echo '<br>Export XML effectué<br>';
		}
		
		if($_GET['a']=="in"){
			$donjon->map[$X][$Y]['in']=1;
		}
		if($_GET['a']=="deletein"){
			$donjon->map[$X][$Y]['in']=0;
		}
		if($_GET['a']=="creerSalle"){
			echo $donjon->creerSalle($X,$Y);
		}
		if($_GET['a']=="deleteSalle"){
			echo $donjon->deleteSalle($X,$Y);
		}
		if($_GET['a']=="RAZ"){
			echo $donjon->init($donjon->width,$donjon->height);
		}
	}


if(!isset($_GET['X']) AND !isset($_GET['Y'])){
	$X=0;
	$Y=0;
}

if(isset($_SESSION['editeur_donjon'])){
	echo 'Case Selectionnée : <font color="#FF1111">X: '.$X.' Y:'.$Y.' </font><br />';
	$donjon->etat_case($X,$Y);

	echo '<a href="./editeur.php?a=enregistrer" title="<div class=\'infobulle\'>Enregistre les modification effectué ( Sauvegarde automatique si on change de donjon à gauche )</div>" '.tooltip().'>Enregistrer</a></br>';
	echo '<a href="./editeur.php?a=RAZ" title="<div class=\'infobulle\'>Vide complétement le donjon</div>" '.tooltip().' onclick="return confirm(\' Etes vous sur de vouloir Réinitialiser ce donjon ? \');">RAZ</a></br>';
	echo '</td><td>';
	
	
	
	$donjon->affichage_editeur_position($X,$Y);
	$donjon->affichage_editeur_monstre();
	$donjon->affichage_editeur_action();
	$donjon->affichage_editeur_prerequis();
	$donjon->affichage_editeur_lien($X,$Y);
	
	$_SESSION['editeur_donjon']=serialize($donjon);
}

?>

</td>
</tr>
</table
