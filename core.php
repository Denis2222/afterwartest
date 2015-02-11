
<?php

//@ log.epilog@gmail.com
//mdp logducore
//set_time_limit(0);
function microtime_float(){
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}




require_once("./include.php");
$temps_deb = time();
$time_start = microtime_float();
$tmpSql=0;
$tmpSqlTotal = 0;

while(time() < $temps_deb+1){

    $now = time();

    $sql = 'SELECT * FROM e_actions WHERE time <= '.$now;
    $return = $GLOBALS['db']->query($sql);
    while($row = mysql_fetch_array($return)){
       if($row['time']<= $now){
            $action = unserialize($row['object']);

            $data_log = $action->faireAction();
            echo $data_log;
            $action->destroy($row['id']);
            unset($action);
       }
    }

    /*
    $sql = "SELECT id FROM j_partie WHERE gagnant = 0 AND type = 'debut'";
    $return = $GLOBALS['db']->query($sql);
    while($row = mysql_fetch_array($return)){
       $p = new Partie();
       $p->load($row['id']);
       $p->fini();
    }
	*/
	
    $tmpSql = ((($GLOBALS['db']->getExecTime())*1000) - $tmpSql);
    $tmpSqlTotal += $tmpSql;
	sleep(1);
	
}

/* LANCEMENT UNE FOIS PAR MINUTES */


    $sql = "SELECT id FROM j_partie WHERE gagnant = 0 AND type != 'debut'";
    $return = $GLOBALS['db']->query($sql);
    while($row = mysql_fetch_array($return)){
       $p = new Partie();
       $p->load($row['id']);
       $p->fini();
    }

    
    $time = time();
    $time_delete = $time - VISITEUR_VALIDE;
    //$delete_visit = "DELETE FROM e_visiteur WHERE date < ".$time_delete."";
    //$GLOBALS['db']->query($delete_visit);
	/* ---- */

$time_end = microtime_float();
$time = round($time_end - $time_start,4);             
$date = date("d-m-Y");
$heure = date("H:i:s");
echo ' date : '.$date .' '. $heure;
echo ' Core Time '.$time.' s . SQL(moyenne) : '.($tmpSqlTotal/59).' ms. Requetes : '.$GLOBALS['db']->nbQueries.' ';
?>
