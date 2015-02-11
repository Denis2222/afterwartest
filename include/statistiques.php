<?php
session_start();
function microtime_float(){
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();

require_once("../include.php");
require_once("./fonctions_statistiques.php");

?>


<div class="contenu">
  <div class="contenu_header_fond">
    <div align="center"><h3>Statistiques</h3>
<?php

echo ahref('Partie','./include/statistiques.php?a=1&type=j',"contenu");
echo ' | ';
echo ahref('Alliance','./include/statistiques.php?a=0&type=a',"contenu");

if(isset($_SESSION['partie']) && $_SESSION['partie'] != 0 && $_SESSION['partie'] != '')
{
  echo ' | ';
  echo ahref('Général','./include/statistiques.php?a=0&type=j',"contenu");
  //echo ' | ';
  //echo ahref('Partie Alliance','./include/statistiques.php?a=1&type=a',"contenu");
}


?>
    </div>
  </div>
  <div class="contenu_fond_gauche" style="padding-left: 7px; padding-top: 6px;">
<?php

if(isset($_GET['jid']))
{
  
}
else
{
if(!isset($_GET['type']))
  $_GET['type'] = 'j';
if(!isset($_GET['tri']))
  $_GET['tri'] = 'hab';
if(!isset($_GET['p']))
  $_GET['p'] = 0;
if(!isset($_GET['a']))
  $_GET['a'] = 1;

if($_GET['a'] == '1')
{

   
  if($_GET['type'] == 'j')
    statJoueurPartie($_SESSION['partie'],$_GET['tri'],$_GET['p'],$_GET['find']);
  //if($_GET['type'] == 'a')
  //  statAlliancePartie($_SESSION['partie'],$_GET['tri'],$_GET['p']);
     
}
else
{
  if($_GET['type'] == 'j')
    statJoueur($_GET['tri'],$_GET['p'],$_GET['find']);
  if($_GET['type'] == 'a')
    statAlliance($_GET['tri'],$_GET['p']);
}
}
?>




</div>  
<div class="contenu_fond_centre" align="center">

  <?php
    if(isset($_GET['jid'])){
      infoJoueur($_GET['jid']);
    }
  ?>

    </div>     
    <div class="contenu_fond_droite" align="center">
    </div>     
</div>
<?php
                $time_end = microtime_float();
                $time = round($time_end - $time_start,4);
                echo '<div id="gentime">GenTime : '.$time.'('.$GLOBALS['db']->getExecTime().'s)  Requetes : '.$GLOBALS['db']->nbQueries.' </div> ';
                ?>