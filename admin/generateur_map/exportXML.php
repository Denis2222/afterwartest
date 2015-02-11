<?php
session_start();
$title="Exportation XML";
include("headeur.php");


if(isset($_GET['action']) AND $_GET['action']=="EXPORT" AND $_POST['XMLname']){


function EXPORT($timelimit,$autoinit,$prerequis,$boss_id,$boss_x,$boss_y,$boss_z){
	$xml='';
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'.'<donjon timelimit="'.$timelimit.'" boss_id="'.$boss_id.'" boss_x="'.$boss_x.'" boss_y="'.$boss_y.'" boss_z="'.$boss_z.'">';
		for($x=$_SESSION['nb_lvl_plus'];$x>=$_SESSION['nb_lvl_moins'];$x--){
			echo $_SESSION['lvl_'.$x];
			echo '  '.$x.'<br>';
			
			$Fichier = 'dungeon/'.$_SESSION['lvl_'.$x];
			if (is_file($Fichier)) {
				if ($TabFich = file($Fichier)) {
					$donjon=unserialize($TabFich[0]);
					$xml .= $donjon->export_xml($x);
					
				}
				else {
					echo "Le fichier ne peut être lu...<br>";
					return 0;
				}
			}
			else {
				echo "Pas de fichier sélectionné<br>";
				return 0;
			}
			
			
		}
	$xml .= '</donjon>';
	$djnfile = fopen('./xml/'.$_POST['XMLname'].'.xml', 'w');
	fseek($djnfile, 0); 
	fputs($djnfile, $xml); 
	fclose($djnfile);
}
$timelimit=0;$autoinit=0;$prerequis=0;$boss_id=0;$boss_x=0;$boss_y=0;$boss_z=0;
if(isset($_POST['timelimit'])){ $timelimit = $_POST['timelimit'];}
if(isset($_POST['boss_id'])){ $boss_id = $_POST['boss_id'];}
if(isset($_POST['boss_x'])){ $boss_x = $_POST['boss_x'];}
if(isset($_POST['boss_y'])){ $boss_y = $_POST['boss_y'];}
if(isset($_POST['boss_z'])){ $boss_z = $_POST['boss_z'];}
EXPORT($timelimit,$autoinit,$prerequis,$boss_id,$boss_x,$boss_y,$boss_z);


}else{
echo '<table>';

if(isset($_GET['lvl'])){
	if($_GET['lvl']==0){
	$_SESSION['lvl_0']=$_GET['dungeon'];
	$_SESSION['nb_lvl_plus']=0;
	$_SESSION['nb_lvl_moins']=0;
	}

}
if(isset($_GET['sens'])){

		if($_GET['sens']=="haut"){

			$_SESSION['nb_lvl_plus']++;
			$_SESSION['lvl_'.$_SESSION['nb_lvl_plus']]=$_GET['dungeon'];
		}elseif($_GET['sens']=="bas"){

			$_SESSION['nb_lvl_moins']--;
			$_SESSION['lvl_'.$_SESSION['nb_lvl_moins']]=$_GET['dungeon'];
		}
	}


if(!isset($_SESSION['lvl_0'])){

echo 'Sélectionnez le niveau 0 ( Cette sélection permet de choisir le niveau d\'entrée du donjon .(rez de chaussé ) :';

	$rep = "dungeon/";
	$dir = opendir($rep);

	while ($f = readdir($dir)){
	   if(is_file($rep.$f)){
	      echo '<li><a href="./exportXML.php?dungeon='.$f.'&lvl=0">Nom :'.$f.'</a>';
	      echo "<br>";
	   }
	}
	closedir($dir);
}elseif(isset($_SESSION['lvl_0'])){
	echo 'Rajouter un étage :';
	$rep = "dungeon/";
	$dir = opendir($rep);

	while ($f = readdir($dir)){
	   if(is_file($rep.$f)){
	      echo '<li>Rajouter '.$f.'<a href="./exportXML.php?dungeon='.$f.'&sens=haut"> En dessus </a>  OU  <a href="./exportXML.php?dungeon='.$f.'&sens=bas"> En dessous </a>';
	      echo "<br>";
	   }
	}
	closedir($dir);
}
echo '</table>';

	if(isset($_SESSION['lvl_0'])){
		echo '<br><br><br><br>Plan des niveau .<table border="1">';
		echo '<tr><td>Dungeon</td><td>Etage </td></tr>';
		if(isset($_SESSION['lvl_0'])){
			for($x=$_SESSION['nb_lvl_plus'];$x>=$_SESSION['nb_lvl_moins'];$x--){
				echo '<tr><td>'.$_SESSION['lvl_'.$x].'</td><td>'.$x.'</td></tr>';
			}
		}
		
		
		
		echo '</table>';
		echo '<br><form name="dungeon" method="post" action="'.$_SERVER['PHP_SELF'].'?action=EXPORT">
		Nom du fichier : '.tips("Nom du donjon").'<input type="text" name="XMLname" value=""><br />
		TimeLimit : '.tips("Une journée minimum  , il est auto initialisé, il s'agit du nb de seconde avant que le donjon soit reinitialisé .").' '.listeTimeLimit(100).'<br />
		Boss ID : '.tips("ID du boss final du donjon").'<input type="text" name="boss_id" value=""><br />
		Boss X : '.tips("Coordonnée X du boss final du donjon").'<input type="text" name="boss_x" value=""><br />
		Boss Y : '.tips("Coordonnée Y du boss final du donjon").'<input type="text" name="boss_y" value=""><br />
		Boss Z : '.tips("Coordonnée Z du boss final du donjon").'<input type="text" name="boss_z" value=""><br />
		<input type="submit" value="Exporter"></form>';
	}
}
?>
