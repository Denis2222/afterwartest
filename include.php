<?php
ini_set("default_charset","UTF-8");

error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);

$base_dir = dirname(__FILE__);

function mic_float(){
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
$time_start = mic_float();

require_once($base_dir."/include/config.php");
require_once($base_dir."/include/config_jeu.php");
require_once($base_dir."/classe/class.db.php");

$db = new DB(DB_DB, DB_HOST, DB_USER, DB_PASS);

require_once($base_dir."/apc.php");
require_once($base_dir."/classe/class.ville.php");
require_once($base_dir."/classe/class.joueur.php");
require_once($base_dir."/classe/class.mj.php");
require_once($base_dir."/classe/class.partie.php");
require_once($base_dir."/classe/class.ville.php");
require_once($base_dir."/classe/class.entrainement.php");
require_once($base_dir."/classe/class.caserne.php");
require_once($base_dir."/classe/class.hoteldeville.php");
require_once($base_dir."/classe/class.mine.php");
require_once($base_dir."/classe/class.scierie.php");
require_once($base_dir."/classe/class.tour.php");
require_once($base_dir."/classe/class.tour.espionnage.php");
require_once($base_dir."/classe/class.uarm.php");
require_once($base_dir."/classe/class.entrepot.php");
require_once($base_dir."/classe/class.recherche.php");
require_once($base_dir."/classe/class.marche.php");
require_once($base_dir."/classe/class.technologie.php");
require_once($base_dir."/classe/class.apercu.php");
require_once($base_dir."/classe/class.action.php");
require_once($base_dir."/classe/class.joueur.php");
require_once($base_dir."/classe/class.tuto.php");
require_once($base_dir."/classe/class.hero.php");
require_once($base_dir."/classe/class.garnison.php");
require_once($base_dir."/classe/class.inventaire.php");
require_once($base_dir."/classe/class.map.php");
require_once($base_dir."/classe/class.case.php");
require_once($base_dir."/classe/class.alliance.php");
require_once($base_dir."/classe/class.combat.php");   
require_once($base_dir."/classe/class.stats.php");  
require_once($base_dir."/include/fonctions_message.php"); 
require_once($base_dir."/include/tronque.php"); 
require_once($base_dir."/include/mot.php"); 

$time_fin = mic_float();