<?php

//echo $_SERVER["SERVER_ADDR"]; 
include_once("../include.php");
$time_start = microtime(true);

		$sql = $GLOBALS["db"]->query('SELECT id FROM j_partie ');
		while($donnees = mysql_fetch_array($sql)){
      $s = new Partie();
      $s->load($donnees['id']);
      $s->calculStat();
      $s->save();
      }
      
$time_end = microtime(true);
$time = $time_end - $time_start;

 echo 'Génération des statistiques réussie en '.$time;     
?>