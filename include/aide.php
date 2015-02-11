<?php

echo '<div class="aide">';

if(isset($_SESSION['tuto']) AND $_SESSION['typePartie'] == "debut"){

if($_SESSION['tuto'] == ""){$_SESSION['tuto']=0;}

  if($_SESSION['tuto'] == 0){
    if(isset($_GET['div']) AND isset($_GET['v'])){
      $_SESSION['tuto'] = 1;
    }else{
      echo '<p><h4>Bienvenue dans vos quartiers</h4><br />
      Tu viens de franchir la première étape de ton apprentissage.<br /><br />
      Tu est actuellement sur une partie automatisée.<br /> Je vais donc te suivre tout au long de ta première "mission".<br /><br />
      <strong>Tu as tout intérêt à suivre cette partie d\'initiation</strong>.
      <br />Une armée ennemie est déja en route vers ta ville.
      <br /><br />Suis bien les différentes étapes afin d\'obtenir une défense capable de repousser l\'attaquant.
      ';
      echo '<br /><br />Va maintenant vérifier l\'état de production de ta ville.
      Pour cela, clique sur <b>"Ma ville"</b> dans le menu de gauche. <br /></p>';
    }
  }
  
  if($_SESSION['tuto'] == 1){
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="hdv" ){
      $_SESSION['tuto'] = 2;
    }else{
      echo 'Te voila maintenant sur la vue principale de ta première ville.<br /><br />
      Clique maintenant sur <b>l\'hôtel de ville</b>.</br>';

    }
  }
  
  if($_SESSION['tuto'] == 2){
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="hdv" AND isset($_GET['p']) AND $_GET['p'] == "p"){
      $_SESSION['tuto'] = 3;
    }else{
    
      echo 'Tu te trouves maintenant dans les options de ce bâtiment.<br /><br />
      Clique maintenant sur l\'onglet <b>paysans</b> de l\'hôtel de ville histoire de les mettre au travail.';
      
    }
  }
  
  if($_SESSION['tuto'] == 3){
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="hdv" AND (isset($_GET['p']) AND $_GET['p'] == "a") OR !isset($_GET['p'])){
      $_SESSION['tuto'] = 4;

    }else{
    echo 'Cette interface permet de répartir tes paysans dans la mine ou dans la scierie. Cela a pour but pour d\'augmenter la production de bois et d\'or.<br /><br />
    Pour ce faire, utilise les boutons <b>+</b> et <b>-</b>. Tes ressources commencent donc à augmenter.<br /><br />
    Tu vas maintenant augmenter le niveau de ton Hôtel de ville. Pour ce faire, clique sur <b>Amélioration</b>';
    }
  }

  
  if($_SESSION['tuto'] == 4){
    
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="hdv" AND (isset($_GET['p']) AND $_GET['p'] == "a") OR !isset($_GET['p']) AND $_GET['lvlup'] == "hdv"){
      $_SESSION['tuto'] = 5;
    }else{
    echo 'Clique maintenant sur le bouton <strong>Amélioration</strong>. <br />';
    }
  }
  
  if($_SESSION['tuto'] == 5){
    
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="caserne" AND (isset($_GET['p']) AND $_GET['p'] == "a") OR !isset($_GET['p']) AND $_GET['lvlup'] == "caserne"){
      $_SESSION['tuto'] = 6;
    }else{
    echo 'L\'<strong>Hôtel de ville</strong> est maintenant en cour d\'amélioration.<br /><br />Celle-ci terminée, tu pourra lancer la construction d\'une <b>caserne</b>.';
    }
  }  
  
  if($_SESSION['tuto'] == 6){
    
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="caserne" AND isset($_GET['p']) AND $_GET['p'] == "e" ){
      $_SESSION['tuto'] = 7;
    }else{
    echo 'Une fois la construction de la caserne terminée, rends toi dans l\'onglet <strong>Entrainement</strong> de celle ci. ';
    }
  }  
  
  if($_SESSION['tuto'] == 7){
    if(isset($_GET['div']) AND isset($_GET['h'])){
      $_SESSION['tuto'] = 8;
    }else{
    echo 'Avec cette interface tu vas pouvoir recruter des unités. Tappe le nombre d\'unité que tu désire entrainer et valide.<br /><br />
    Pendant que la production suit son cours, rends toi sur l\'interface de ton héro. 
    Pour cela, clique maintenant sur <strong>Mon Héro</strong> dans le menu de gauche.';
    }
  }
  
  if($_SESSION['tuto'] == 8){
    if(isset($_GET['div']) AND isset($_GET['h']) AND $_GET['o'] == "inventaire"){
      $_SESSION['tuto'] = 9;
    }else{
    echo 'Dans ce panneau, tu peux apercevoir la carte avec ta ville et ton héro.<br /><br /><b>Surtout ne déplace pas ton héro</b>.<br /><br />
    Rends toi ensuite dans l\'<strong>Inventaire</strong> du héro afin d\'améliorer ses statistiques de défense.';
    }
  }
  
  
  if($_SESSION['tuto'] == 9){
    if(isset($_GET['div']) AND isset($_GET['v']) AND isset($_GET['o']) AND $_GET['o']=="hdv" AND isset($_GET['p']) AND $_GET['p'] == "g"){
      $_SESSION['tuto'] = 10;
    }else{
    echo 'Ajoute des points de défense à ton héro. <br /> Une fois terminé, retourne à l\'hotel de ville dans l\'onglet <strong>Garnison</strong>.';
    }
  }
  
  if($_SESSION['tuto'] == 10){
    if(0){
      $_SESSION['tuto'] = 11;
    }else{
    echo 'Tu devrais normalement avoir quelques unités disponible.<br /><br />Tu peux transférer les unités de ta ville vers tes héros depuis ce panneau d\'affichage en utilisant les fléches.<br /><br />Je te laisse découvrir le fonctionnement et te souhaite bonne chance pour ton premier combat !<br /><br />Pour plus d\'informations, n\'hésite pas à demander sur le forum ou sur le bar. <br /> ';
    }
  }

  
echo '<br /><br />Etape '.$_SESSION['tuto'].' / 10 <br /><br />';
/*
echo '<br /><br />Data Get:<br />';
  print_r($_GET);*/
}elseif($_SESSION['typePartie'] == "rush"){
echo '<b>Objectifs de la mission</b> :<br />Augmenter votre production jusqu\'a 150 Ors / Minutes et 125 Bois / Minutes.

<br /><br /> <b>Tous les moyens sont bons</b>.<br />Prennez d\'autres villes. Pillez vos voisins. <br /><br />Bonne chance...
';




}elseif($_SESSION['typePartie'] == "prise"){
echo '<b>Objectifs de la mission :</b><br /> 
Prendre la ville Kelagan au milieu de la zone par tous les moyens.<br /><br />
Attention la ville est très fortement défendu.<br />
Vous pouvez attaquer en alliance.<br /><br />

La ville se situe au coordonnées suivants:<br />
<b>X = 25</b><br />
<b>Y = 25</b><br /><br />
Bon courage.<br />
Over.
';




}else{


function aideHdv(){
?>
<h3>Aide Hotel de Ville</h3>
<br />
<?php
  switch ($_GET['p']){ // batimant
    case 'a' :
      echo 'amélioration';
    break;
    case 'g' :
      echo '<p>Dans cet onglet, vous pouvez voir les unités qui gardent votre ville</p><p>Pour produire des unités il faut se rendre dans la caserne</p>';
    break;
    case 'p';
      echo 'paysans';
    break;
    case 'd';
      echo 'description';
    break;
    default :
      echo 'L\'hotel de ville est le point central de votre cité<br /> Plus ce batiment est dévellopé plus vous pourrez stocker des ressources, vous proteger en cas d\'attaque, et faire travailler des paysans.';
    break;
    };
?>
</p>
<?php
}


function aideCaserne(){
?>
<h3>Aide Caserne</h3>
<br />
<?php

  switch ($_GET['p']){ // batimant
    case 'a' :
      echo 'amélioration';
    break;
    case 'e' :
      echo 'Pour recruter des unités.<br />Il faut saisir le nombre d\'unités que vous souhaitez produire et valider. <br /><br /> Si vous avez sufisament de ressources une file de production devrait apparaitre aprés la validation.<br /><br /> Les troupes entrainé dans la caserne , rejoigne automatiquement aprés la fin de l\'entrainement la garnison de la ville.';
    break;
    case 'r';
      echo 'Pour recruter un nouveau héro il vous suffit de posséder les ressources nécéssaire. <br /><br /> Entrez le nom que vous souhaitez lui donner , et validez. Le nouveau héro serat immédiatement disponible. <br /><br /> Les ressources nécéssaires pour recruter un nouveau héro augmente proportionellement au nombre de héro que vous controllez.';
    break;
    default :
      echo 'La caserne sert à la production des unités<br /><br />
      En améliorant son niveau, vous aurez accés à de nouveaux types d\'unités et vos troupes seront entrainé plus rapidement.
      
      ';
    break;
    };
}

function aideScie(){
  switch ($_GET['p']){ // batimant
    case 'a' :
      echo 'amélioration';
    break;
    case 'p';
      echo 'paysans';
    break;
    default :
      echo 'Page de présentation<br />';
    break;
    };
}

function aideMine(){
?>
<h3>Aide Mine</h3>
<br />
<?php

  switch ($_GET['p']){ // batimant
    case 'a' :
      echo 'amélioration';
    break;
    case 'p';
      echo 'paysans';
    break;
    default :
      echo 'La mine sert à extraire de l\'or, métal lourd redevenu la seule monnaie utile en ces temps sombre..<br /> La mine doit être alimenté en main d\'oeuvre pour pouvoir produire, plus tu as de mineur qui travaille à l\'interieur et plus sa production serat èlevé. 
      <br /><br /> <h4>Attention</h4> <br /> N\'oublie pas d\'améliorer le niveau de la mine qui est proportionnel à sa qualité, cela augmente son rendement et évite les risques ... <br />Si tu as trop de mineur dans une mine peu dévellopé, les mineurs courent de gros risques...';
    break;
    };
}

if(!isset($_GET['v']) && !isset($_GET['h'])){//avant que l'on clic sur un lien
  echo '<br />Cliquez sur un lien pour obtenir l\'aide correspondante<br />';

}


if(isset($_GET['v'])){//aide pour les villes';
  
  switch ($_GET['o']){ // batimant
    case 'hdv' :
      aideHdv();
    break;
    case 'scierie' :
      aideScie();
    break;
    case 'mine';
      aideMine();
    break;
    case 'caserne';
      aideCaserne();
    break;
    default :
      echo 'Presentation générale de la ville<br />';
    break;
    };
}

if(isset($_GET['h'])){//on est sur le hero
  echo ' aide pour les hero';
}
}
echo '</div>';
?>