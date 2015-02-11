<?php

require_once("../../include.php");
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
// tel 06.77.77.64.90
 ?>