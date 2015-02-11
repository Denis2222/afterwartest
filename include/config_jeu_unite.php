<?php

/*============================================================================*/
/*============================ DONNEES DES UNITES=============================*/
/*============================================================================*/
// Lors d'un ajout pensser à modifier aussi AJAX.js::dep_unite()

$nbUnite=7;//nombre d'unite dans la jeu

$unite = array(
'Gardien',
'Sniper',
'Grenadier',
'Jeep',
'Ombre',
'Nettoyeur',
'Tank Azathoth',
'SAM',

); // Nom unité

$Aunite = array( // Attaque
2, // Att U0
2, // Att U1
7, // Att U2
5, // Att U3
10, // Att U4
15, // Att U5
19, // Att U6
42 // Att U7
);

$Dunite = array( // Defense
2,
5,
2,
5,
5,
8,
15,
2
);

$Tunite = array( // Temps de production
70,
90,
120,
130,
210,
290,
335,
550
);   

$Ounite = array( // Ors
80,
100,
350,
250,
500,
750,
950,
1200
);

$Bunite = array( // Bois
80,
250,
100,
250,
250,
400,
750,
1050
);

$typeDestruction = 6; // Type de l'unité de destruction 



$PointAttUnite = array( // point gagné Attaque
1,
6,
3,
7,
5,
9,
15,
15
);

$PointDefUnite = array( // point gagne Defense
1,
2,
3,
3,
7,
5,
3,
3
);

$PVunite = array( // Point de vie
40,
60,
80,
100,
120,
160,
450,
150
);

$PointConssomation  = array(1,2,3,4,5,6,7,1);


/* PREREQUIS */

/*
$NeedUnite = array( //Niveau Caserne
1,
3,
5,
7,
10,
12,
17,
1
);
*/

$UniteBatRequire = array( // Prerequis Batiment des unités
0=>array(
  "caserne"=>1,
  "hdv"=>2),
1=>array(
  "hdv"=>4,
  "caserne"=>3,
  "recherche"=>1),
2=>array(
  "hdv"=>6,
  "caserne"=>6),
3=>array(
  "caserne"=>8),
4=>array(
  "caserne"=>10,
  "tour"=>1),
  
5=>array(
  "uarm"=>1,
  "caserne"=>12,
  "hdv"=>12),
  
6=>array(
  "caserne"=>14,
  "uarm"=>6,
  "tour"=>6),
  
7=>array(
  "hdv"=>15,
  "caserne"=>15,
  "uarm"=>8)
  
);

$UniteRechRequire = array( // Prerequis Recherche des unités
0=>array(
  ),
1=>array(
  "fonderie"=>1
  ),
2=>array(
  "fonderie"=>3,
  "poudre"=>1),
3=>array(
  "fonderie"=>4,
  "poudre"=>2,
  "moteur_explosion"=>1),
4=>array(
  "fonderie"=>5,
  "poudre"=>3,
  "espionnage"=>1),
  
5=>array(
  "fonderie"=>6,
  "poudre"=>4,
  "espionnage"=>4,
  "armement"=>1,
  "moteur_explosion"=>4),
  
6=>array(
  "moteur_explosion"=>8,
  "fonderie"=>8,
  "poudre"=>6,
  "espionnage"=>5,
  "armement"=>3),
  
7=>array(
  "espionnage"=>8,
  "poudre"=>10,
  "armement"=>6)
);

/*============================================================================*/
/*============================ FIN DONNEES UNITES FACTION JOUEUR==============*/
/*============================================================================*/

?>