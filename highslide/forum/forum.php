<?php
session_start();
if (!isset($_SESSION['login'])){

}else{
include("../fonctions.php");
require("./fonctions_forum.php");
//include("./batiments/fonctions_bat.php");
Conexion_bdd();
$donnees_ville= mysql_query("SELECT * FROM e_ville WHERE proprietaire = '".$_SESSION['login']."'")or die(mysql_error()) ;
$donnees_hero= mysql_query("SELECT * FROM e_hero  WHERE proprietaire = '".$_SESSION['login']."' ")or die(mysql_error()) ;
mysql_close();
}
$title = 'Epilog  1.0 Développement en cours';
//include ("../pres.php");

?>
  <div id="a"class="page_jeu">
    <div class="menu_onglet">
<?php
if (isset($_GET['action']) AND $_GET['action']=="new" AND isset($_GET['cat'])){
formulaire_nouveau_topic();
}elseif(isset($_GET['t'])AND isset($_GET['c'])AND isset($_GET['f'])){  // DIRECT TOPIC ==============
  Conexion_bdd();
  $forum=mysql_query("SELECT ID,nom FROM f_forum WHERE ID= ".$_GET['f']."");
  $array_forum=mysql_fetch_array($forum);
  $categorie=mysql_query("SELECT ID,nom,restriction FROM f_categorie WHERE ID= ".$_GET['c']."")or die(mysql_error()) ;
  $topic=mysql_query("SELECT ID,ID_membre,nom,description FROM f_topic WHERE ID_categorie= ".$_GET['c']."")or die(mysql_error()) ;
  $reponse=mysql_query("SELECT ID,ID_membre,reponse,ID_membre FROM f_reponse WHERE ID_topic=".$_GET['t']."")or die(mysql_error()) ;
  $autorisation=0;
  
  $array_categorie=mysql_fetch_array($categorie);
  if($array_categorie['restriction']==0){ 
  $autorisation = 1 ;
  
  }else{
    $compte = mysql_query("SELECT ID,alliance FROM e_compte WHERE ID = '".$_SESSION['login']."'")or die(mysql_error()) ;
    $array_compte=mysql_fetch_array($compte);
      if ($array_compte['alliance']==$array_categorie['restriction']AND $array_categorie['ID']==$_GET['c']){
      $autorisation=1;

      }
  }
  mysql_close();
  $array_topic = mysql_fetch_array($topic);
  
  if ($autorisation==0){
  ?>

    Erreur !  
    </div>
    <div id="b"class="menu_jeu">
    Vous n’avez pas accès à cette partie du forum.
    </div>
  </div>
  <?php
  }elseif($autorisation==1){
    ?>

    <?php echo '<div class="forum_titre"><a href="./forum.php?f='.$_GET['f'].'"> Forum : '.$array_forum['nom'].'</a> => <a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'"> Categorie : '.$array_categorie['nom'].'</a>  => <a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'&t='.$_GET['t'].'"> Topic : '.$array_topic['nom'].'</a></div>'; ?> 
    </div>
    <div id="b"class="menu_jeu">
    <div class="zone_reponse">
    <table cellspacing="1" cellpadding="2" class="tbg">
    
    <?php
    mysql_data_seek($reponse,0);
    Conexion_bdd();
    while ($array_reponse = mysql_fetch_array($reponse)){
    ?>
    <tr class="rbg">
    <td id="td_reponse">
    <?php 
    echo nl2br(addslashes(htmlentities ($array_reponse['reponse'])));
    ?>
    </td>
    <td id="td_membre">
    <?php
    echo $array_reponse['ID_membre'];
    
    $membre=mysql_query("SELECT login,description,avatar,points FROM e_compte WHERE ID=".$array_reponse['ID_membre']."")or die(mysql_error()) ;
    $array_membre = mysql_fetch_array($membre);
    echo $array_membre['login'];
    ?>
    </td>
    </tr>
    <?php
    }
    mysql_close;
    ?>
    
    
    </table>
    </div>
    </div>
  </div>
  <?php

  }
}elseif(isset($_GET['c'])){ // DIRECT CATEGORIE ===============
  Conexion_bdd();
  $forum=mysql_query("SELECT ID,nom FROM f_forum WHERE ID= ".$_GET['f']."");
  $array_forum=mysql_fetch_array($forum);
  $categorie=mysql_query("SELECT ID,nom,restriction FROM f_categorie WHERE ID= ".$_GET['c']."")or die(mysql_error()) ;
  $topic=mysql_query("SELECT ID,nom,description FROM f_topic WHERE ID_categorie= ".$_GET['c']."")or die(mysql_error()) ;
  $autorisation=0;
  
  $array_categorie=mysql_fetch_array($categorie);
  if($array_categorie['restriction']==0){ 
  $autorisation = 1 ;
  
  }else{
    $compte = mysql_query("SELECT ID,alliance FROM e_compte WHERE ID = '".$_SESSION['login']."'")or die(mysql_error()) ;
    $array_compte=mysql_fetch_array($compte);
      if ($array_compte['alliance']==$array_categorie['restriction']AND $array_categorie['ID']==$_GET['c']){
      $autorisation=1;
      }
  }
  mysql_close();
  if ($autorisation==0){
  ?>
  <div id="a"class="page_jeu">
    <div class="menu_onglet">
    Erreur !  
    </div>
    <div id="b"class="menu_jeu">
    Vous n’avez pas l'autorisation d'accéder à cette catégorie.
    </div>
  </div>
  <?php
  }elseif($autorisation==1){ // Accé confirmé pour la catégorie 
  ?>
  <div id="a"class="page_jeu">
    <div class="menu_onglet">
    <?php echo '<div class="forum_titre"><a href="./forum.php?f='.$_GET['f'].'"> Forum : '.$array_forum['nom'].'</a> => <a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'"> Categorie : '.$array_categorie['nom'].'</a></div>'; ?>
    </div>
    <div id="b"class="menu_jeu">
    <tr class="rbg">
    <table cellspacing="1" cellpadding="2" class="tbg">
     <tr class="rbg">
     <td id="td_categorie">Sujet</td><td id="td_categorie">Message</td>
     </tr>
    <?php
    Conexion_bdd();
    while ($array_topic = mysql_fetch_array($topic)){
    ?>

      <td id="td_categorie">
      <?php echo '<div class="categorie"><a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'&t='.$array_topic['ID'].'">'.$array_topic['nom'].':</a></div><div class="description">'.$array_topic['description'].'</div>';?>
      </td>
      <td id="td_categorie">
      <?php echo nb_reponse_by_topic($array_topic['ID']);?>
      </td>
    </tr>
    
    <?php
    }
    mysql_close();
    ?>

    </table>
    <a href="forum.php?action=new&cat=<?php echo $_GET['c'] ?>">Nouveau Message</a>
    </div>
  </div>
  <?php
  }

}elseif(isset($_GET['f'])){ //VUE FORUM =======================
Conexion_bdd();
$forum=mysql_query("SELECT ID,nom FROM f_forum WHERE ID= ".$_GET['f']."");
$categorie=mysql_query("SELECT ID,nom,description FROM f_categorie WHERE ID_forum= ".$_GET['f']."")or die(mysql_error()) ;
mysql_close();
$array_forum=mysql_fetch_array($forum);
?>
<div id="a"class="page_jeu">
  <div class="menu_onglet">
    <?php
    echo '<div class="forum_titre">Forum : '.$array_forum['nom'].'</div>';
    ?>
  </div>
  <div id="b"class="menu_jeu">
    <table cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
          <td>Sujet</td>
          <td>Topic</td>
          <td width="125">Message</td>
        </tr>
    <div class="categorie">
    <?php
    Conexion_bdd();
    while ($array_categorie = mysql_fetch_array($categorie)){
    ?>
    <tr>
      <td id="td_categorie">
      <?php echo '<div class="titre_categorie"><a href="./forum.php?f='.$_GET['f'].'&c='.$array_categorie['ID'].'">'.$array_categorie['nom'].':</a></div><div class="description">'.$array_categorie['description'].'</div>';?>
      </td>
      <td>
      <?php 
      echo nb_topic($array_categorie['ID']);
      ?>
      </td>
      <td width="125">
      <?php 
      echo nb_reponse_by_cat($array_categorie['ID']);
      ?>
      </td>
    </tr>
    <?php
    
    }
    mysql_close();
    ?>
    </div>
    </table>
  </div>
</div>
<?php
}
?>

</body>
</html>
