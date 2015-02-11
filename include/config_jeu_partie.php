<?php

// REGLAGE DES PARTIES /////////
// partie => debut
define("TEMPS_AVANT_ATTAQUE",900);//de deneluan //900 = 15 minutes
//partie => rush
define("FIN_RUSH_PROD_ORS",150);
define("FIN_RUSH_PROD_BOIS",125);
//partie => mort
define("TEMPS_MINI_AVANT_FIN_MORT",604860);// 7 jours et 1 minute
define("TEMPS_OUVERTURE_ENTREE_MORT",604800);// 2 jours =172800 // 7 jours = 604800
//partie => mortequipe
define("TEMPS_MINI_AVANT_FIN_MORTEQUIPE",604860);// 7 jours et 1 minute
define("TEMPS_OUVERTURE_ENTREE_MORTEQUIPE",604800);// 2 jours =172800 // 7 jours 
// partie => prise
define("NOM_ADMIN",'Kazhou');
define("MDP_ADMIN",'vbfgh');
define("NOM_VILLE",'Extrem');

$htmlDebutGagne = '
    <p><strong>Félicitations</strong>, tu as remporté une bataille <br />mais pas la guerre ! <br /><br />
    Voici maintenant la seconde étape de ton apprentissage. <br /> L\'objectif sera d\'augmenter la production de ton empire !<br /><br />
    Plus vite tu augmenteras tes ressources, plus vite cette étape prendra fin.<br /><br />
    Il te seras possible de rentrer en contact avec d\'autres joueurs pour créer ou intégrer une alliance .<br /><br />
    Bon courage, et que le meilleur gagne !
    </p>';
    
$htmlDebutPerdu = '    
    <p><b>Echec</b>, tu as échoué contre l\'attaquant.<br /><br />
    Si tu refuses de rester sur cette défaite, nous t\'invitons à retenter ta chance.<br /><br />            
    N\'oublie pas l\'<b>"Aide"</b> qui te seras très utile pour comprendre les rouages de l\'évolution au sein d\'After War.
    <br /><br />Je te propose de recommencer la mission pour te perfectionner.<br /><br />
    Bon courage et ne baisse pas les bras !<br /></p>';
    
$htmlRushGagne = '
              <p><strong>Félicitations</strong>, tu as remporté une autre importante mission !<br /><br />
              Le but de cette troisième partie sera de capturer la ville centrale, contrôlé par l\'infame empereur kazhou.<br /><br />
              Le premier à compléter cet objectif signera la fin de la partie, et entrera ainsi dans le <strong>Hall des Eros</strong> ! Tous les membres de son alliance seront aussi victorieux.<br /><br />
              Il t\'es bien entendu toujours possible de piller d\'autres joueurs, ou d\'intégrer une alliance.
              <br /><br />
              <b> Que le meilleur gagne ! </b>
              </p>'; 
              
$htmlRushPerdu = '
              <p><strong>Echec</strong>, tu t\'es fait latter !<br /><br />
              On y retourne !
              <br /><br />
              <b> Et cette fois, gagne ! </b>
              </p>'; 

$htmlPriseGagne = '
              <p><strong>Félicitation</strong>, tu as remporté la guerre contre l\'empereur Kazhou !<br /><br />
              <br />Bienvenue dans le <strong>Hall des Eros</strong> !
              </p>'; 
              
$htmlPrisePerdu = '
              <p><strong>Echec</strong>, tu as échoué face à l\'empereur Kazhou !<br /><br />
              Honte à toi, vil vermisceau, recommence et gagne !<br /><br />
              </b>
              </p>'; 

$htmlMortGagne = '
              <p><strong>Félicitation</strong>, tu as remporté la bataille mais pas la guerre !<br /><br />
              Tu peux maintenant choisir le type de partie à accomplir...<br /><br />
              Une nouvelle carte, de nouveaux joueurs à affronter... <br />
              <b> Bonne chance pour la suite et que le meilleur gagne soldat! </b>
              </p>'; 
              
$htmlMortPerdu = '
              <p><strong>Echec</strong>, tu as perdu la partie !<br /><br />
              Retente ta chance!
              <br /><br />
              <b> Et cette fois, gagne ! </b>
              </p>';
              
$htmlMortEquipeGagne = '
              <p><strong>Félicitation</strong>, toi et ton alliance avez emporté la bataille!<br /><br />
              Tu peux maintenant choisir le type de partie à accomplir...<br /><br />
              Une nouvelle carte, de nouveaux joueurs à affronter... <br />
              <b> Bonne chance pour la suite et que le meilleur gagne soldat! </b>
              </p>'; 
              
$htmlMortEquipePerdu = '
              <p><strong>Echec</strong>, votre équipe n\'a pas remporté la partie!<br /><br />
              Retentez votre chance!
              <br /><br />
              <b> Et cette fois, gagnez ! </b>
              </p>'; 

              
$htmlAutreGagne = '
              <p><strong>Félicitation</strong>, tu as remporté une bataille mais pas la guerre !<br /><br />
              </p>'; 
              
$htmlAutrePerdu = '
              <p><strong>Echec</strong>, tu as perdu la partie !<br /><br />
              Retente ta chance!
              <br /><br />
              <b> Et cette fois, gagne ! </b>
              </p>';
?>