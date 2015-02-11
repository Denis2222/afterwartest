<?php
include("./templates/header.php");
include("./include.php");
?>


  <div id="global">		
    <div id="menu_haut">
      <?php 
      $GLOBALS['home'] = 1;
      echo menuHaut();
       ?>
    </div>		
    <div id="loading" valign="center">
    </div>		
    <div id="menu_gauche">
    </div>		
    <div id="contenu">
      <div class="contenu">
        <div class="contenu_header_fond">
          Merci de passer !
        </div>
        <div class="contenu_fond_centre" align="left">
          <br />

     
     
<?php

if(isset($_SESSION['jid'])){
    echo 'Quel culot !';
}else{

  if(isset($_GET['i'])){
    if($_SESSION['visiteur'] !=1){
      $id_linker = $_GET['i'];
      $ip_visiteur = $_SERVER["REMOTE_ADDR"];
      $sql = "SELECT * FROM e_visiteur WHERE ip = '".$ip_visiteur."'";
      $return = $GLOBALS['db']->query($sql);
      
      $nb = 0;
      while ($data = mysql_fetch_array($return)){
        $nb++;
      }
      
      if($nb ==0){ // Premiere fois qu'il vient
        $sql = "INSERT INTO e_visiteur(id,ip,date,id_lien) 
        VALUES('','".$ip_visiteur."','".time()."','".$id_linker."')";
        $GLOBALS['db']->query($sql);
        $j = new Joueur();
        $j->loadSimple($id_linker);
        $nb_ville = count($j->idVille);
        $key_ville  = mt_rand(0,$nb_ville-1);
        
        $ville = new Ville();
        $ville->load($j->idVille[$key_ville]['id']);
        $ville->paysans ++ ;
        $ville->save();
        
        
        echo 'Vous venez d\'aporter votre aide à '.$j->login.'.<br /> Un Paysan Suplémentaire qui pourras travailler dans une de ses villes !';
        $_SESSION['visiteur'] = 1;
      }else{ // Déja pointé son nez ici
        dejaVenu();
        echo 'sans session';
      }
    }else{
      dejaVenu();
    }
  }else{
    echo 'Tu vote pour qui ?';
  }
}


function dejaVenu(){
   echo 'Tes déja venu ici toi !';
   $_SESSION['visiteur'] = 1;
}
?>     
     
     
         

        </div>
      </div>





<?php
include("./templates/footer.php");
?>