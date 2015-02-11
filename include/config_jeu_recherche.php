<?php
/*============================================================================*/
/*============================ DONNEES DES RECHERCHE=============================*/
/*============================================================================*/

$recherche_code = array(
"fonderie",
"poudre",
"moteur_explosion",
"espionnage",
"armement"
);



$recherche_nom = array(

"Fonderie",
"Poudre",
"Moteur à explosion",
"Espionnage",
"Armement"
);

$rechercheRequire = array(

"fonderie"=>array(),
    
"poudre"=>array("fonderie"=>2),
    
"moteur_explosion"=>array("fonderie"=>6),
    
"espionnage"=>array("fonderie"=>6,
                    "poudre"=>5,
                    "moteur_explosion"=>2),
    
"armement"=>array("fonderie"=>8,
                  "poudre"=>7,
                  "moteur_explosion"=>12)
  
);

$rechercheBatimentRequire = array(
    "fonderie"=>array("hdv"=>5,
                      "recherche"=>2,
                      "mine"=>4,
                      "scierie"=>4),
    "poudre"=>array("caserne"=>2,
                    "mine"=>5,
                    "scierie"=>6),
    "moteur_explosion"=>array("hdv"=>12,
                              "caserne"=>13,
                              "entrepot"=>10),
    "espionnage"=>array("hdv"=>6,
                              "caserne"=>5,
                              "scierie"=>6),
    "armement"=>array("hdv"=>15,
                      "caserne"=>14,
                      "entrepot"=>11,
                      "uarm"=>4)
);


function coutRech($bat,$niv){
 switch($bat){
  case "armement" :
    {  $bois = 1400;  $ors=1200;  $cout = ($niv*1.5);  $temps_constr=50+(10*$niv*$niv);}    //$bois = 50;  $ors=10;  $cout = 50+(100*$niv*$niv);  $temps_constr=50+(10*$niv*$niv);}
  break;
 
  case "fonderie" :
    {  $bois = 600;  $ors=500;  $cout = ($niv*1.5);  $temps_constr=50+(10*$niv*$niv);}    //$bois = 50;  $ors=10;  $cout = 50+(100*$niv*$niv);  $temps_constr=50+(10*$niv*$niv);}
  break;
  
  case "moteur_explosion" :
    {  $bois = 1000;  $ors=800;  $cout = ($niv*1.5);  $temps_constr=35+(12*$niv*$niv);}     //$bois = 90;  $ors=10;  $cout = 40+(75*$niv*$niv);  $temps_constr=35+(12*$niv*$niv);}
  break;
  
  case "espionnage" :
    {  $bois = 1200;  $ors=1000;  $cout = ($niv*1.5);  $temps_constr=35+(12*$niv*$niv);}     //$bois = 90;  $ors=10;  $cout = 40+(75*$niv*$niv);  $temps_constr=35+(12*$niv*$niv);}
  break;
  
  case "poudre" :
    {  $bois = 800;  $ors=600;  $cout = ($niv*1.5);  $temps_constr=45+(11*$niv*$niv);}     //$bois = 25;  $ors=75;  $cout = 35+(80*$niv*$niv);  $temps_constr=45+(11*$niv*$niv);}
  break;
  

  }
  return array('bois'=>round($bois+$cout), 'ors'=>round($ors+$cout), 'temps'=>$temps_constr);
}

/*============================================================================*/
/*============================ FIN DONNEES RECHERCHE=============================*/
/*============================================================================*/
?>