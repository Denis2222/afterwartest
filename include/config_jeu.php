<?php

if(!isset($GLOBALS['skin'])){
  $GLOBALS['skin'] = "/skin/original/";
}

if(!isset($_SESSION['skin'])){
  $_SESSION['skin'] = "/skin/original/";
}

//adresse pour le mail de validation
define("URL_JEU",'http://after-war.com/');//ne pas oublier le / a la fin


require_once($base_dir.'/include/config_jeu_recherche.php');
require_once($base_dir.'/include/config_jeu_batiment.php');
require_once($base_dir.'/include/config_jeu_unite.php');
require_once($base_dir.'/include/config_jeu_phrase.php');
require_once($base_dir.'/include/config_jeu_partie.php');

define("RECUP_PA_DEPART",'100'); // 10 PA / Heure
//inscription
define("BOIS_DEPART",800);
define("ORS_DEPART",800);
//augmentation ressources
define("COEF_AUG_ORS",'0.02'); // 0.002
define("COEF_AUG_BOIS",'0.02'); // 0.003
//cout d'un paysan
define("COUT_PAYSANS_ORS",'150');
define("COUT_PAYSANS_BOIS",'125');
//gain par heure dr ressource avec un paysan
define("GAIN_PAYSANS_ORS",'1');
define("GAIN_PAYSANS_BOIS",'1');
// Ressources maximum
define("COEF_HDV",'350');
define("COEF_MINE",'125');
define("COEF_SCIE",'110');

define("CAPACITE_ENTREPOT",1000);

define("NB_PAYSANS_PA_NIV",5);

define("POINT_DE_CARAC_PAR_NIVEAU",'4');

define("DUREE_COMBAT_CORE",2); // Durée entre deux vérification d'un combat par le core en seconde

define("TIME_COOKIE",604800); //7 jours
define("TIME_INACTIF_MAX",604800); //7 jours (used dans partie)

define("VISITEUR_VALIDE",86400); // 24 Heures 

function coef_pillage_bois(){
  return 20;
}

function coef_pillage_ors(){
  return 20;
}

function lienLogin($login,$id){
  $html = ''.ahref($login,'./include/statistiques.php?jid='.$id,"contenu").'';
  return $html;
}
////////////////////////////////

$nb_min_ville_pour_prise = 0;

$OrsCreaVille = array(5000,10000,25000,60000,140000,180000,250000,500000,1000000,3000000);// Ors
$BoisCreaVille = array(5000,10000,25000,55000,130000,170000,230000,450000,1000000,3000000);// Bois


$TempsCase = 50;// Temps par defaut sur une case

$descriptionCaserne = '<br />La caserne sert à la production des unités.<br />L\'amélioration de ce bâtiment permet de produire de nouvelles unités.';
$descriptionHdv = '<br />L\'hôtel de ville est le point central de votre cité.<br />L\'amélioration de ce bâtiment permet de relever la limite de stockage de ressources.';
$descriptionMine = '<br />La mine produit de l\'or.<br />L\'amélioration de ce bâtiment permet d\'augmenter le maximum de paysans dans la mine.';
$descriptionScierie = '<br />La scierie produit du bois.<br />L\'amélioration de ce bâtiment permet d\'augmenter le maximum de paysans dans la scierie.';
$descriptionEntrepot = '<br />L\'entrepot permet de stocker des ressources.<br />L\'amélioration de ce bâtiment permet de stockage un nombre important de ressource.';
$descriptionTour = '<br />La tour d\'observation permet d\'espionner ses ennemis.<br />L\'amélioration de ce bâtiment augmente le champs de vision';
$descriptionRecherche = '<br />Le centre de recherche permet d\'acquérir de nouvelles technologie.<br /> Un niveau plus èlevé plus vous donne la possibilité d\'accéder à des technologies plus pointu.';

/*
function apc_compile_dir($root, $recursively = true){	
  $compiled   = true;
  switch($recursively){
     case    true:
        foreach(glob($root.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir)
           $compiled   = $compiled && apc_compile_dir($dir, $recursively);
     case    false:
        foreach(glob($root.DIRECTORY_SEPARATOR.'*.php') as $file)
           $compiled   = $compiled && apc_compile_file($file);
     break;
  }
  return  $compiled;
}
*/
function coutCreationHero($nb,$type)
{
  if($type == 'ors'){
    return 100+100*$nb*$nb*$nb;
  } 
  elseif($type == 'bois'){
    return 100+95*$nb*$nb*$nb;
  }
}

function rap0($nb){ // Pour plus beau rapport de combat
  if($nb == 0 OR $nb ==""){
    return '<font color="#333333">0</font>';
  }else{
    return $nb;
  }
}

function formRess($ress){
  $var = number_format($ress, 0, ',', '.');
  return $var;
}

function productionLevel($niveau,$temps){
  return ($temps - ((($niveau*2)*$temps)/100));
}

function prefixMapPartie($type){ // Prefix des nom de table sur les partie
  switch ($type){
    case 'debut' :
      return 'z_map';
    break;
    case 'rush' :
      return 'y_map';
    break;
    case 'mort' :
      return 'x_map';
    break;
    case 'mortequipe' :
      return 'v_map';
    break;
    default :
      return 'p_map';
    break;
    }
}
//==============================================================================
//==================== Fonctions pour un peu tout ==============================
//==============================================================================

function ahref($content,$param,$div,$type = 0){ // Fonction pour les liens AJAX
    if($type == 0){
      $html = '<a href="#" onClick="ajaxLoad(\''.$div.'\',\''.$param.'\')">'.$content.'</a>';
    }else{
      $html = '<a href="#" onClick="ajaxLoad(\''.$div.'\',\''.$param.'\',\''.$type.'\')">'.$content.'</a>';
    }
    return $html;
}

function onClick($param,$div){ // AJAX BOUTON
    $html = 'onClick="ajaxLoad(\''.$div.'\',\''.$param.'\')"';
    return $html;
}


function decoHaut($html){
$html = '<table style="minHeight:200px;" cellspacing="1" cellpadding="2" class="detail_ville"><tr class="tr_message">
              <td>'.$html.'</td>
            </tr>          
            <tr>
              <td valign="top">';
return $html;
}
$decoBas = '</td>  
            </tr>
            <tr class="tr_message">
              <td></td>
            </tr>
          </table>';
$MenuHautCompte = menuHaut();
    
function menuHaut()
{
  if(!isset($_SESSION['jid']) OR $_SESSION['jid']==0)
  {
    if($_GET['div'] == "menu_haut" OR $GLOBALS['home'] == 1)
    {
      echo '
      <a href="./"><img id="menu_haut_onglet" src="./skin/original/texte/m_accueil.png" alt="Accueil"/></a>
      '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_login.png" alt="login"/></a>','data.login.php?div=contenu&action=connect',"contenu").'
      <a href="./inscription.php"><img id="menu_haut_onglet" src="./skin/original/texte/m_inscription.png" alt="inscription"/></a>
      <a href="./histoire.php"><img id="menu_haut_onglet" src="./skin/original/texte/m_histoire.png" alt="histoire"/></a>
       '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_bar.png" alt="bar"/>','data.php?div=contenu&choix=bar',"contenu").'
      <a href="http://wiki.after-war.com/index.php?title=Accueil" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_wiki.png" alt="wiki"/></a>      
      '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_staff.png" alt="staff"/>','data.login.php?div=contenu',"contenu").''
      //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_boutique.png" alt="boutique"/></a>
      ;
    }
  }
  elseif($_SESSION['jid']!=0)
  {
    if(!empty($_SESSION['etat']) AND $_SESSION['etat']==1)
    {
      if($_GET['div'] == "menu_haut")
      {
        echo'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_moncompte.png" alt="mon compte"/>','data.php?div=contenu&general=compte',"contenu").'        
        '.ahref('<img  id="menu_haut_onglet" src="./skin/original/texte/m_messages.png" alt="Messages"/>','data.php?div=contenu&choix=messagerie',"contenu").'
        '.ahref('Stats','./include/statistiques.php',"contenu").'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_histoire.png" alt="histoire"/>','data.login.php?div=contenu&action=histoire',"contenu").'
        <a href="http://wiki.after-war.com/index.php?title=Accueil" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_wiki.png" alt="wiki"/></a>       
        <a href="login.php"><img id="menu_haut_onglet" src="./skin/original/texte/m_sedeco.png" alt="Se déconnecter"/></a>';
        //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_fairedon.png" alt="faire un don"/></a>
        //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_boutique.png" alt="boutique"/></a>
        
      }
    }
    elseif(!empty($_SESSION['etat']) AND $_SESSION['etat']> 1 AND $_SESSION['typePartie']!="")
    {
      if(isset($_GET['div']) AND $_GET['div'] == "menu_haut"){
        echo'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_moncompte.png" alt="mon compte"/>','data.php?div=contenu&general=compte',"contenu").'        
        '.ahref('<img  id="menu_haut_onglet" src="./skin/original/texte/m_messages.png" alt="Messages"/>','data.php?div=contenu&choix=messagerie',"contenu").'
        '.ahref('<img  id="menu_haut_onglet" src="./skin/original/texte/m_alliance.png" alt="Alliance"/>','data.php?div=contenu&choix=alliance',"contenu").'
        '.ahref('Stats','./include/statistiques.php',"contenu").'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_histoire.png" alt="histoire"/>','data.login.php?div=contenu&action=histoire',"contenu").'
        <a href="http://wiki.after-war.com/index.php?title=Accueil" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_wiki.png" alt="wiki"/></a>
        <a href="login.php"><img id="menu_haut_onglet" src="./skin/original/texte/m_sedeco.png" alt="Se déconnecter"/></a>
        ';        
        //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_fairedon.png" alt="faire un don"/></a>
        //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_boutique.png" alt="boutique"/></a>
        
        
      }
    }
    elseif(!empty($_SESSION['etat']) AND $_SESSION['etat']> 1 AND $_SESSION['typePartie']=="")
    {
      if(isset($_GET['div']) AND $_GET['div'] == "menu_haut")
      {
        echo'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_moncompte.png" alt="mon compte"/>','data.php?div=contenu&general=compte',"contenu").'
        '.ahref('<img  id="menu_haut_onglet" src="./skin/original/texte/m_listeparties.png" alt="Liste parties"/>','data.php?div=contenu&action=liste',"contenu").'        
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_messages.png" alt="Messages"/>','data.php?div=contenu&choix=messagerie',"contenu").'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_alliance.png" alt="Alliance"/>','data.php?div=contenu&action=alliance',"contenu").'
        '.ahref('<img id="menu_haut_onglet" src="'.$GLOBALS['skin'].'texte/m_stats.png" alt="statistiques"/>','./include/statistiques.php',"contenu").'
        <a href="http://forum.after-war.com/index.php" target="_blank"><img id="menu_haut_onglet" src="'.$GLOBALS['skin'].'texte/m_forum.png" alt="forum"/></a>
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_bar.png" alt="bar"/>','data.php?div=contenu&choix=bar',"contenu").'
        '.ahref('<img id="menu_haut_onglet" src="./skin/original/texte/m_histoire.png" alt="histoire"/>','data.login.php?div=contenu&action=histoire',"contenu").'
        <a href="http://wiki.epi-log.com/index.php?title=Accueil" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_wiki.png" alt="wiki"/></a>        
        <a href="login.php"><img id="menu_haut_onglet" src="./skin/original/texte/m_sedeco.png" alt="Se déconnecter"/></a>';
        //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_fairedon.png" alt="faire un don"/></a>
        //<a href="" target="_blank"><img id="menu_haut_onglet" src="./skin/original/texte/m_boutique.png" alt="boutique"/></a>
      }
    }
  }
}

function icoBois(){
return '<img src="'.$GLOBALS['skin'].'icones/biok.png" alt="Bois"/>';
}
function icoOrs(){
 return '<img src="'.$GLOBALS['skin'].'icones/vledac.png" alt="Or"/>';
}

function icoTime(){
 return '<img src="'.$GLOBALS['skin'].'icones/time.png" alt="Temps"/>';
}

function icoUnite($id,$taille=35){
 return '<img src="'.$GLOBALS['skin'].'unites/'.$id.'.png" alt="Unité '.$id.' " title="'.$GLOBALS['unite'][$id].' Att:'.$GLOBALS['Aunite'][$id].' Def:'.$GLOBALS['Dunite'][$id].' PV:'.$GLOBALS['PVunite'][$id].'" height="'.$taille.'"/>';
}

function icoAttack(){
 return '<img src="'.$GLOBALS['skin'].'icones/attack.png" alt="Attaquer" title="Attaquer"/>';
}

function icoGoto(){
 return '<img src="'.$GLOBALS['skin'].'icones/goto.png" width="20" alt="Se Déplacer" title="Se Déplacer"/>';
}


function icoAllyGauche(){
 return '<img src="'.$GLOBALS['skin'].'icones/ally_gauche.png" alt="Arrivé d\'un allié" title="Arrivé d\'un allié"/>';
}
function icoAllyDroite(){
 return '<img src="'.$GLOBALS['skin'].'icones/ally_droite.png" alt="Départ d\'un allié" title="Départ d\'un allié"/>';
}
function icoMoiGauche(){
 return '<img src="'.$GLOBALS['skin'].'icones/me_gauche.png" alt="Un de mes héro arrive" title="Un de mes héro arrive"/>';
}
function icoMoiDroite(){
 return '<img src="'.$GLOBALS['skin'].'icones/me_droite.png" alt="Un de mes héro part" title="Un de mes héro part"/>';
}
function icoMoiAtt(){
 return '<img src="'.$GLOBALS['skin'].'icones/me_att.png" alt="J\'attaque" title="J\'attaque"/>';
}
function icoEnemisGauche(){
 return '<img src="'.$GLOBALS['skin'].'icones/enemis_gauche.png" alt="Un enemis arrive" title="Un enemis arrive"/>';
}
function icoEnemisDroite(){
 return '<img src="'.$GLOBALS['skin'].'icones/enemis_droite.png" alt="Un enemis part" title="Un enemis part"/>';
}
function icoEnemisAtt(){
 return '<img src="'.$GLOBALS['skin'].'icones/enemis_att.png" alt="Un enemis attaque" title="Un enemis attaque"/>';
}

function icoMoi(){
 return '<img src="'.$GLOBALS['skin'].'map/hero/hero_me.png" alt="Mon héro en stand by" title="Mon héro en stand by"/>';
}

function icoAlly(){
 return '<img src="'.$GLOBALS['skin'].'map/hero/hero_ally.png" alt="Un allié en stand by" title="Un allié en stand by"/>';
}

function icoEnemis(){
 return '<img src="'.$GLOBALS['skin'].'map/hero/hero_enemis.png" alt="Un enemis en stand by" title="Un enemis en stand by"/>';
}

function icoColoniser(){
 return '<div class="btn-coloniser"><img src="'.$GLOBALS['skin'].'design/btn-coloniser.png" alt="Coloniser cette case" title="Coloniser cette ville abandonnée" /></div>';
}

function nomValide($nom){
  
  //if($nom != "" && $nom != " " && mb_eragi("^[a-zA-Z0-9_ ]{2,}[a-zA-Z0-9_ ]$",$nom) && strlen($nom) <= 15)
    return true;
  //else
    //return false;
}

function emailValide($email){
  $atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
  $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
  $regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
  '(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
                                  // séparés par des caractères autorisés avant l'arobase
  '@' .                           // Suivis d'un arobase
  '(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
                                  // séparés par des points
  $domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine
  
  // test de l'adresse e-mail
  if (preg_match($regex, $email)) {
      return true;
  } else {
      return false;
  }
}

function temp_seconde($seconde){
    $seconde++;
  $seconde = intval($seconde);
  $heure = $seconde / 3600 ;
  $heure = intval($heure) ;
  $seconde = $seconde - $heure*3600 ;
  $min = $seconde / 60 ;
  $min = intval($min) ;
  $min = intval($min);
  $seconde = $seconde - $min*60 ;
  if($min<10)
    $min='0'.$min;
  if($seconde<10)
    $seconde='0'.$seconde;
    if ($heure != 0){
      return $heure.':'.$min.':'.$seconde;
    }elseif ($min !=0){
      return '0:'.$min.':'.$seconde ;
    }else{
      return '0:0:'.$seconde ;
    }

}

function temp_complet($seconde){
$seconde = intval($seconde);

  $jour = intval($seconde / 86400);
  $seconde = $seconde- (86400*$jour);
  
  $heure = intval($seconde/3600);
  $seconde = $seconde - ($heure*3600);
  
  $min = intval($seconde/60);
  $seconde = $seconde - ($min*60) ;

  if ($jour != 0){
    return $jour.'j '.$heure.'h '.$min.'min '.$seconde.'s';
  }elseif($heure != 0){
    return $heure.'h '.$min.'min '.$seconde.'s';
  }elseif ($min !=0){
    return ' '.$min.'min '.$seconde.'s' ;
  }else{
    return ' '.$seconde.'s' ;
  }

}



$avant_textarea = '
<table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_01.png);background-repeat:no-repeat;width:5px;height:4px;"> </td>
        <td class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_02.png);background-repeat:repeat-x;height:4px;"></td>
        <td class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_04.png);background-repeat:no-repeat;width:5px;height:4px;"> </td>
    </tr>
    <tr>
        <td  class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_05.png);background-repeat:repeat-y;" ></td>
        <td  class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_06.png);background-repeat:repeat-xy;">';

$apres_textarea = '</td>
        <td  class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_07.png);background-repeat:repeat-y;"></td>
    </tr>
    <tr>
        <td  class="txtarea"  style="background-image: url('.$_SESSION['skin'].'textarea/tab_08.png);background-repeat:no-repeat;width:5px;height:4px;"> </td>
        <td  class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_09.png);background-repeat:repeat-x;"></td>
        <td  class="txtarea" style="background-image: url('.$_SESSION['skin'].'textarea/tab_10.png);background-repeat:no-repeat;width:5px;height:4px;"> </td>
    </tr>
</table>';


function attPossible($ally1,$prop1){
  $ally2 = $GLOBALS['alliance'];
  $prop2 = $_SESSION['jid'];
  
  if($prop1 == $prop2){
    return 0; // Meme joueur
  }elseif($ally1 == "" OR $ally1 == 0){
    $ally1 = 0; 
    return 1; // Enemis
  }elseif($ally2 == "" OR $ally2 == 0){
    $ally2 = 0;
    return 1; // Enemis
  }elseif($ally2 == $ally1){
    return 0; // Ally
  }else{
    return 1; // Sinon pas allié et pas meme joueur , alors c'est un méchant
  }
}

function etatDiplomatie($ally1,$prop1){ // Return 2 Même joueur 1 même ally ,0 Enemis
  $ally2 = $GLOBALS['alliance'];
  $prop2 = $_SESSION['jid'];
  if($prop1 == $prop2){
    return 2; // Meme joueur
  }elseif($ally1 == "" OR $ally1 == 0){
    $ally1 = 0; 
    return 0; // Enemis
  }elseif($ally2 == "" OR $ally2 == 0){
    $ally2 = 0;
    return 0; // Enemis
  }elseif($ally2 == $ally1){
    return 1; // Ally
  }else{
    return 0; // Sinon pas allié et pas meme joueur , alors c'est un méchant
  }
}

function envoiMail($to,$id,$key,$login,$mdp){
    //$to = 'allolivier@gmail.com';
    // Sujet
    $subject = 'Activation After War';
    // message
    $message = '
      <html>
      <head>
      <title>Activation de votre compte sur After War</title>
      </head>
      <body bgcolor="#142110" style="font-family:Verdana; font-size:12px; background-color:#142110;">
        <table cellpadding="0" cellspacing="0" border="0" align="center">
          <tr>
            <td height="78"><img src="'.URL_JEU.'skin/original/design/inscrire.png" alt="vous venez de vous inscrire sur EPILOG" style="display:block;" /></td>
          </tr>
          <tr>
            <td height="56"><img src="'.URL_JEU.'skin/original/design/espagne.png" height="56" style="display:block;"/></td>
          </tr>
        </table><table cellpadding="0" cellspacing="0" border="0" align="center">
          <tr>
            <td><img src="'.URL_JEU.'skin/original/design/login_mail.png" alt="votre login:" style="display:block;"/></td>
            <td background="'.URL_JEU.'skin/original/design/login2.png" colspan="2" width="455" style="padding-left:10px;color:#b9b420;font-family:verdana;font-size:12px;"><font color="b9b420">'.$login.'</font></td>    
          </tr>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" align="center">
          <tr>
            <td><img src="'.URL_JEU.'skin/original/design/mdp_mail.png" alt="votre mot de passe:"  style="display:block;"/></td>
            <td background="'.URL_JEU.'skin/original/design/mdp2.png" colspan="2" width="399" style="padding-left:10px;color:#b9b420;font-family:verdana;font-size:12px;">'.$mdp.'<br /></td>    
          </tr>
          <tr>
            <td height="73" colspan="3"><img src="'.URL_JEU.'skin/original/design/connecter.png" alt="Pour activer votre compte, cliquez sur le lien ci-dessous" style="display:block;"/></td>
          </tr>
          <tr>
            <td background="'.URL_JEU.'skin/original/design/lienconnecter.png" colspan="3" width="409" height="77" style="color:#b9b420;font-family:verdana;font-size:12px;">&#160;&#160;&#160;&#160;&#160;<a href="'.URL_JEU.'activation.php?id='.$id.'&key='.$key.'" style="color:#b9b420;font-family:verdana;font-size:12px;">Cliquez ici</a><br /><br />&#160;&#160;&#160;&#160;&#160;<span style="color:#b9b420;font-family:verdana;font-size:12px;">Si le lien ne marche pas, copiez le lien ci-dessous :</span><br />&#160;&#160;&#160;&#160;&#160;<font color="b9b420" style="font-family:verdana;font-size:10px;color#b9b420;">'.URL_JEU.'activation.php?id='.$id.'&key='.$key.'</font></td>
          </tr>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" align="center">
          <tr>
            <td><img src="'.URL_JEU.'skin/original/design/merci.png" alt="Merci pour votre inscription et bon jeu !"  style="display:block;" /></td>
            <td><img src="'.URL_JEU.'skin/original/design/bonjeu.png" style="display:block;" /></td>
            <td><img src="'.URL_JEU.'skin/original/design/austrad.png" style="display:block;" /></td>
            <td><img src="'.URL_JEU.'skin/original/design/austrag.png" style="display:block;" /></td>    
          </tr> 
          <tr> 
            <td background="'.URL_JEU.'skin/original/design/fin.png" colspan="4" width="580">&#160;&#160;&#160;&#160;&#160;<a href="'.URL_JEU.'" style="color:#b9b420;">www.epi-log.com</a></td>
          <tr>
        </table>
      </body>
      </html>';
      
    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
    //$headers  = 'MIME-Version: 1.0' . "\r\n";
    //$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    
    
    $headers = "MIME-version: 1.0\n";
    $headers .= "Content-type: text/html; charset= iso-8859-1\n";
    
    $headers .= 'From: After War <mail@after-war.com>' . "\r\n";
    // Envoi
    //$result = mail($to, $subject, $message, $headers);
	$result = true;
    /*
    if($result == true)
      echo 'evoi réussi';
    else
      echo 'echec';
      */
    return $result;
}

?>
