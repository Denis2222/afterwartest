<?php
ini_set("default_charset","UTF-8");
require_once("../include/config.php");
require_once("../include/config_jeu.php");
require_once("../classe/class.db.php");

$db = new DB(DB_DB, DB_HOST, DB_USER, DB_PASS);

require_once("../classe/class.ville.php");
require_once("../classe/class.entrainement.php");

require_once("../classe/class.action.php");
require_once("../classe/class.joueur.php");
require_once("../classe/class.hero.php");
    require_once("../classe/class.garnison.php");
    require_once("../classe/class.inventaire.php");
    require_once("../classe/class.map.php");
    require_once("../classe/class.case.php");
    require_once("../classe/class.alliance.php");
    
require_once("./generateur_map/dungeon.class.php");
    
    
    
$x=1;
$y=1;
$x_max=35;
$y_max=35;

/* Coef du décor */
$arbre=30;
$meka=0;

$souche=5;
$cratere=5;
$squelete=1;

$mapDB = "e_map2";
/*
for ($x=1;$x<=$x_max;$x++){
 for ($y=1;$y<=$y_max;$y++){
   echo 'case :'.$x.'-'.$y;
   $rand=rand(0,100);
   if($rand<=$squelete){
    $type="squelete";
    $mvt=0;
    }else{
     $rand=rand(0,100);
     if ($rand<=$cratere){
	    $type="cratere";
	    $mvt=0;
	    }else{
	    $rand=rand(0,100);
       if ($rand<=$souche){
		$type="souche";
		$mvt=0;
		}else{
	  $rand=rand(0,100);
	     if ($rand<=$meka){
		    $type="meka";
		    $mvt=1;
		    }else{
		    $rand=rand(0,100);
	       if ($rand<=$arbre){
		      $rand=rand(0,10);
		      $type='arbre'.$rand;
		      $mvt=1;
		      }else{
		       $rand=rand(1,20);
		       $type='herbe'.$rand;
		       $mvt=0;
		      }
		    }
		 }
	     }
    }
    echo 'case :'.$x.'-'.$y;
    $vide="";
    $GLOBALS['db']->query ('INSERT INTO '.$mapDB.' VALUES("'.$x.'","'.$y.'","'.$mvt.'","'.$type.'","'.$vide.'","'.$vide.'","'.$vide.'","'.$vide.'")') or die (mysql_error()) ;
  }
}
*/

	$Fichier = './generateur_map/dungeon/route_35_35.djn';
	if (is_file($Fichier)) {
		if ($TabFich = file($Fichier)) {
			//echo "Chargement effectué avec succés";
			$donjon=unserialize($TabFich[0]);
		}
	}

$donjon->affichage_route();

for($x=1;$x<=35;$x++){
  for($y=1;$y<=35;$y++){
    $case = new CaseObject($x,$y,"e_map2");
    $case->ajouterRoute($donjon->map[$x][$y]['1'],$donjon->map[$x][$y]['2'],$donjon->map[$x][$y]['3'],$donjon->map[$x][$y]['4']);
    $case->save();
  }
}

// tel 06.77.77.64.90
 ?>