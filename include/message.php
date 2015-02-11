
<div class="contenu">
  <div class="contenu_header_fond">
      <br /><div align="center">Messagerie</div>
  </div>
    
  <div class="contenu_fond_gauche" style="padding-left: 7px; padding-top: 6px;">
    <h2>
    <?php
      echo '<div class="g_message">';
      echo '<a href="#" onClick="ajaxLoad(\'contenu\',\'data.php?div=contenu&choix=messagerie&m=1\');initChek();">Boite de réception</a><br />';
      echo '<a href="#" onClick="ajaxLoad(\'contenu\',\'data.php?div=contenu&choix=messagerie&m=2\');initChek();">Rapports</a><br />';
      echo '<a href="#" onClick="ajaxLoad(\'contenu\',\'data.php?div=contenu&choix=messagerie&m=3\');initChek();">Envoyés</a><br />';      
     
      //echo ahref('Rapports','data.php?div=contenu&choix=messagerie&m=2',"contenu").'<br />';
      //echo ahref('Envoyés','data.php?div=contenu&choix=messagerie&m=3',"contenu").'<br />';
      echo ahref('Ecrire','data.php?div=contenu&choix=messagerie&m=6',"contenu").'<br />';
      echo '</div>';
      ?>


<?php
     
    
    ?>
    </h2>
  </div>
  <div class="contenu_fond_centre" align="center">
  	<?php
  	if(!isset($_GET['m']))
  	   $_GET['m'] = 1;
  	if(!isset($_GET['p']))
  	   $_GET['p'] = 1;  	   
  	if($_GET['m']==1){// Message Reçu
      affichage_message($j->id,$_GET['p']);
    }
    if($_GET['m']==2){// Rapport
    	affichage_rapport($j->id);
    }
    if($_GET['m']==3){// Message Envoyé
    	affichage_envoye($j->id);
    }
    if($_GET['m']==4){// Voir message Reçu
    	voir_message_recu($j->id,$_GET['id']);
    }
    if($_GET['m']==5){// Voir message Envoyé
    	voir_message_envoye($j->id,$_GET['id']);
    }
    if($_GET['m']==6){// Ecrire un message
          //print_r($_SESSION);
    	if (isset($_GET['jid']) AND isset($_GET['mid'])){
    	formulaire_ecriture_message($_GET['jid'],$_GET['mid'],$j->id);
    	}else{
    	formulaire_ecriture_message();
      }
    }
  /*  if($_GET['m']==7){// Envoi de message
      envoyer_message($j->id,$_GET['destinataire'],$_GET['sujet'],$_GET['contenu']);
      affichage_envoye($j->id);
    }
    
    if($_GET['m']==8){// Supression de message
      if (isset($_POST['messadel'])){
        foreach ($_POST['messadel'] as $choix)
      		{
      		  $GLOBALS["db"]->query("DELETE FROM `e_message` WHERE `e_message`.`id` = $choix ;")or die(mysql_error());
      		}
      	affichage_message($j->id);
      }
    }
    */
    //<input id='test'/>
    ?>
    
  </div>
</div>