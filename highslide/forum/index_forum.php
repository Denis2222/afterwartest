<?php
session_start();
if (!isset($_SESSION['login'])){
include("./fonctions.php");
}else{
include("./fonctions.php");
include("./forum/fonctions_forum.php");
//include("./batiments/fonctions_bat.php");
Conexion_bdd();
$donnees_ville= mysql_query("SELECT * FROM e_ville WHERE proprietaire = '".$_SESSION['login']."'")or die(mysql_error()) ;
$donnees_hero= mysql_query("SELECT * FROM e_hero  WHERE proprietaire = '".$_SESSION['login']."' ")or die(mysql_error()) ;
mysql_close();
}
$title = 'Epilog  1.0 Développement en cours';
include ("./pres.php");
?>
<script language="javascript" type="text/javascript" src="./forum/smiley.js"></script>
<link href="<?php echo $style_dir;?>forum.css" rel="stylesheet" type="text/css"/>
<div id="a" class="page_jeu" >
  <style type="text/css">
    <!--
    .page_jeu {
    margin-top: 120px;
    }
    //-->
  </style>
<?php 
if (isset($_GET['action']) AND $_GET['action']=="new" AND isset($_GET['cat'])){
formulaire_nouveau_topic($style_dir);
}elseif (isset($_GET['action']) AND $_GET['action']=="post" AND isset($_GET['cat'])){
include("./forum/geticone.php");
Conexion_bdd();
mysql_query("INSERT INTO f_topic values ('', '".$_GET['cat']."', '".$_SESSION['login']."', '".$_POST['titre']."',  '".$_POST['description']."',  '', '', '' )")or die(mysql_error()) ;
$var_id_topic=mysql_query("SELECT @@IDENTITY ;")or die(mysql_error());
$sql_id_topic=mysql_fetch_array($var_id_topic);
mysql_query("INSERT INTO f_reponse values ('', '".$sql_id_topic['@@identity']."', '".$_SESSION['login']."', '".time()."', '".geticone($_POST['message'])."' )")or die(mysql_error()) ;
mysql_close();
  ?>
<SCRIPT LANGUAGE="JavaScript">document.location.href="./forum.php?f=<?php echo $_GET['f']; ?>&c=<?php echo $_GET['cat']; ?>"</SCRIPT>
<?php
}elseif (isset($_GET['action']) AND $_GET['action']=="reponse" AND isset($_GET['cat'])){
formulaire_reponse($style_dir);
}elseif (isset($_GET['action']) AND $_GET['action']=="repond" AND isset($_GET['cat'])){
include("./forum/geticone.php");
Conexion_bdd();
mysql_query("INSERT INTO f_reponse values ('', '".$_GET['t']."', '".$_SESSION['login']."', '".time()."', '".geticone($_POST['message'])."' )")or die(mysql_error()) ;
mysql_close();
  ?>
<SCRIPT LANGUAGE="JavaScript">document.location.href="./forum.php?f=<?php echo $_GET['f']; ?>&c=<?php echo $_GET['cat']; ?>&t=<?php echo $_GET['t']; ?>"</SCRIPT>
<?php
}elseif(isset($_GET['t'])AND isset($_GET['c'])AND isset($_GET['f'])){  // DIRECT TOPIC ==============
  Conexion_bdd();
  $forum=mysql_query("SELECT ID,nom FROM f_forum WHERE ID= ".$_GET['f']."");
  $array_forum=mysql_fetch_array($forum);
  $categorie=mysql_query("SELECT * FROM f_categorie WHERE ID= ".$_GET['c']."")or die(mysql_error()) ;
  $topic=mysql_query("SELECT * FROM f_topic WHERE ID_categorie= ".$_GET['c']." AND id = ".$_GET['t']."")or die(mysql_error()) ;
  $reponse=mysql_query("SELECT * FROM f_reponse WHERE ID_topic=".$_GET['t']." ORDER BY id ASC")or die(mysql_error()) ;
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
  ?> Ereur ! Vous n’avez pas accès à cette partie du forum.
<?php
  }elseif($autorisation==1){
  ?>
  <?php echo '<a href="./forum.php?f='.$_GET['f'].'"> Forum : '.$array_forum['nom'].'</a> => <a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'"> Categorie : '.$array_categorie['nom'].'</a>  => <a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'&t='.$_GET['t'].'"> Topic : '.$array_topic['nom'].'</a>'; ?>
  <a href="forum.php?action=reponse&t=<?php echo $_GET['t']; ?>&f=<?php echo $_GET['f']; ?> &cat=
    <?php echo $_GET['c'] ?> ">
    <br/>
    <img src="./forum/repondre_haut.png" border="0" /></a>
  <table>
    <tr>
      <td >
        Auteur</td>
      <td >
        Messages</td>
    </tr>
<?php
    mysql_data_seek($reponse,0);
    Conexion_bdd();
    while ($array_reponse = mysql_fetch_array($reponse)){
    ?>
    <tr>
      <td class="info">
<?php
    $membre=mysql_query("SELECT login,description,avatar,points FROM e_compte WHERE ID=".$array_reponse['ID_membre']."")or die(mysql_error()) ;
    $array_membre = mysql_fetch_array($membre);
    echo $array_membre['login'];
    echo "<br/>".date_ga($array_reponse['date'])."<br/>".heure($array_reponse['date']);
       
        ?></td>
      <td class="message">
<?php 
    echo nl2br($array_reponse['reponse']);
        ?></td>
    </tr>
<?php
    }
    mysql_close;
    ?>
  </table>
  <a href="forum.php?action=reponse&t=<?php echo $_GET['t']; ?>&f=<?php echo $_GET['f']; ?> &cat=
    <?php echo $_GET['c'] ?> ">
    <img src="./forum/repondre_bas.png" border="0"/></a>
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
  ?> Erreur !  Vous n'avez pas l'autorisation d'accéder à cette catégorie.
<?php
  }elseif($autorisation==1){ // Accé confirmé pour la catégorie 
  ?>
  <?php echo '<a href="./forum.php?f='.$_GET['f'].'"> Forum : '.$array_forum['nom'].'</a> => <a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'"> Categorie : '.$array_categorie['nom'].'</a>'; ?>
  <tr >
    <table>
      <tr >
        <td>
          Sujets</td>
        <td >
          Messages</td>
        <td >
          Dernier message</td>
      </tr>
<?php
    Conexion_bdd();
    while ($array_topic = mysql_fetch_array($topic)){
      ?>
      <td class="forum">
        <?php echo '<div class="titre_categorie"><a href="./forum.php?f='.$_GET['f'].'&c='.$_GET['c'].'&t='.$array_topic['ID'].'">'.$array_topic['nom'].'</a></div><div class="description">'.$array_topic['description'].'</div>';?></td>
      <td class="info" >
        <?php echo nb_reponse_by_topic($array_topic['ID']);?></td>
      <td class="last_info">
<?php $array_msg = dernier_msg($array_topic['ID']);
            echo "le ".date_ga($array_msg['date']).", ".heure($array_msg['date']);
            echo "<br/>";
            $membre=mysql_query("SELECT login FROM e_compte WHERE ID=".$array_msg['ID_membre']."")or die(mysql_error()) ;
            $array_membre = mysql_fetch_array($membre);
            echo "par ".$array_membre['login'];
        ?></td>
  </tr>
<?php
    }
    mysql_close();
  ?>
  </table>
  <a href="forum.php?action=new&f=<?php echo $_GET['f'] ?>&cat=<?php echo $_GET['c'] ?> ">
    <img src="./forum/nouveau_msg.png" border="0" alt="Nouveau message"/></a>
<?php
  }
}elseif(isset($_GET['f'])){ //VUE FORUM =======================
Conexion_bdd();
$forum=mysql_query("SELECT ID,nom FROM f_forum WHERE ID= ".$_GET['f']."");
$categorie=mysql_query("SELECT ID,nom,description FROM f_categorie WHERE ID_forum= ".$_GET['f']."")or die(mysql_error()) ;
mysql_close();
$array_forum=mysql_fetch_array($forum);
  ?>
<?php
    echo '<a href="./forum.php?f='.$_GET['f'].'"> Forum : '.$array_forum['nom'].'</a>';
  ?>
  <table>
    <tr >
      <td>
        Forums</td>
      <td>
        Sujets</td>
      <td>
        Dernier message</td>
    </tr>
<?php
    Conexion_bdd();
    while ($array_categorie = mysql_fetch_array($categorie)){
    ?>
    <tr class="gen_view">
      <td class="forum">
        <?php echo '<div class="titre_categorie"><a href="./forum.php?f='.$_GET['f'].'&c='.$array_categorie['ID'].'">'.$array_categorie['nom'].'</a></div><div class="description">'.$array_categorie['description'].'</div>';?>
<?php echo "<i>(".nb_reponse_by_cat($array_categorie['ID'])." message";
       if ((nb_reponse_by_cat($array_categorie['ID']))>"0"){echo "s";}
        echo ")</i>";?></td>
      <td class="info">
<?php 
      echo nb_topic($array_categorie['ID']);
        ?></td>
      <td  class="last_info">
<?php 
      
      if (nb_reponse_by_cat($array_categorie['ID'])!=0) {
      $array_suj = dernier_sujet($array_categorie['ID']);
      echo '<a href="forum.php?f='.$_GET['f'].'&c='.$array_categorie['ID'].'&t='.$array_suj['id'].'">'.$array_suj['nom'].'</a>';
      echo "<br/>";
      $array_msg = dernier_msg($array_suj['id']);
      echo "le ".date_ga($array_msg['date']).", ".heure($array_msg['date']);
      echo "<br/>";
      $membre=mysql_query("SELECT login FROM e_compte WHERE ID=".$array_msg['ID_membre']."")or die(mysql_error()) ;
            $array_membre = mysql_fetch_array($membre);
            echo "par ".$array_membre['login'];
        } else {
        echo "Pas de message pour l'instant!<br/>
        Soyez le premier!";
        }
        ?></td>
    </tr>
<?php
    
    }
    mysql_close();
    ?>
  </table>
<?php
}
  ?>
</div>
