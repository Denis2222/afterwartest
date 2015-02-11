<?php
/*============================================================================*/
/*============================ DONNEES BATIMENTS=============================*/
/*============================================================================*/
function coutBat($bat,$niv){
 switch($bat){
  case "hdv" :
    {  $bois = 250;  $ors=200;  $cout = ($niv*1.5);  $temps_constr=50+(10*$niv*$niv*$niv);}
  break;
  
  case "mine" :
    {  $bois = 175;  $ors=125;  $cout = ($niv*1.5);  $temps_constr=35+(12*$niv*$niv);}
  break; //$cout = 40+(75*$niv*$niv);
  
  case "scierie" :
    {  $bois = 175;  $ors=125;  $cout = ($niv*1.5);  $temps_constr=45+(11*$niv*$niv);}
  break; // $cout = 35+(80*$niv*$niv);
  
  case "caserne" :
    {  $bois = 375;  $ors=300;  $cout = ($niv*1.5);  $temps_constr=65+(9*$niv*$niv);}
  break; //$cout = 30+(50*$niv*$niv);

  case "tour" :
    {  $bois = 2200;  $ors=2000;  $cout = ($niv*1.5);  $temps_constr=10+(5*$niv*$niv);}
  break;//$cout = 1+(50*$niv*$niv);
    
  case "uarm" :
    {  $bois = 3200;  $ors=3000;  $cout = ($niv*1.5);  $temps_constr=10+(5*$niv*$niv);}
  break;//$cout = 1+(50*$niv*$niv);
  
  case "recherche" :
    {  $bois = 600;  $ors=500;  $cout = ($niv*1.5);  $temps_constr=10+(5*$niv*$niv);}
  break;//$cout = 1+(50*$niv*$niv);
  
  case "entrepot" :
    {  $bois = 1200;  $ors=1000;  $cout = ($niv*1.5);  $temps_constr=10+(5*$niv*$niv);}
  break;//$cout = 1+(50*$niv*$niv);
  
  case "marche" :
    {  $bois = 350;  $ors=300;  $cout = ($niv*1.5);  $temps_constr=10+(5*$niv*$niv);}
  break;//$cout = 1+(50*$niv*$niv);
  
  }
  return array('bois'=>round($bois*$cout), 'ors'=>round($ors*$cout), 'temps'=>$temps_constr);
}

$batiment = array(
"hdv",
"mine",
"scierie",
"caserne",
"recherche",
"entrepot",
"tour",
"uarm",
"marche"
);

$batiment_nom = array(
"Hôtel de ville",
"Mine",
"Scierie",
"Caserne",
"Centre de Recherche",
"Entrepôt",
"Tour d'observation",
"Usine D'armement",
"Magasin"
);

$batRequire = array(
"hdv"=>array(),
"mine"=>array(
  "hdv"=>1),
"scierie"=>array(
  "hdv"=>1),
  
"caserne"=>array(
  "hdv"=>2,
  "mine"=>1,
  "scierie"=>1),
  
"recherche"=>array(
  "hdv"=>5,
  "mine"=>6,
  "scierie"=>6,
  "caserne"=>3),

"entrepot"=>array(
  "hdv"=>8,
  "caserne"=>5,
  "recherche"=>3),

"tour"=>array(
  "hdv"=>10,
  "caserne"=>10,
  "recherche"=>6),
  
"uarm"=>array(
  "hdv"=>12,
  "caserne"=>12,
  "recherche"=>8),
  
"marche"=>array(
  "hdv"=>29,
  "caserne"=>29,
  "recherche"=>13,
  "uarm"=>26)
);

$batRechercheRequire = array(
"hdv"=>array(),
"mine"=>array(),
"scierie"=>array(),

"caserne"=>array(),

  
"recherche"=>array(),

"entrepot"=>array(
  "fonderie"=>2),

"tour"=>array(
  "fonderie"=>5,
  "espionnage"=>1,
  "poudre"=>3),
  
"uarm"=>array(
  "moteur_explosion"=>4,
  "armement"=>1,
  "fonderie"=>6,
  "espionnage"=>4),
  
"marche"=>array(
  "moteur_explosion"=>18,
  "armement"=>21,
  "fonderie"=>19,
  "espionnage"=>26)
 
);
/*============================================================================*/
/*============================ FIN DONNEES BATIMENTS=============================*/
/*============================================================================*/
?>