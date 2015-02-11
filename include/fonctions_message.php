<?php


function affichage_message($id,$page){ // Affiche la liste des message recu
  	$nbMessPage=19;
  	$retour = $GLOBALS["db"]->query("SELECT COUNT(*) AS nb_messages FROM e_message WHERE type = 'message' AND destinataire = '".$id."'");
    $donnees = mysql_fetch_array($retour);
    $totalDesMessages = $donnees['nb_messages'];
    // On calcule le nombre de pages à créer
    $nombreDePages  = ceil($totalDesMessages / $nbMessPage);
    $premierMessageAafficher = ($page - 1) * $nbMessPage;
    $sql=$GLOBALS["db"]->query("SELECT * FROM e_message WHERE type = 'message' AND destinataire = '".$id."' ORDER BY date DESC LIMIT $premierMessageAafficher , $nbMessPage");
  	    //echo '<table border="2" width="100%"><tr><td>Sujet</td><td>Expéditeur</td><td>Date</td></tr>';
        //<form action="./index.php?m=8" method="post">  
             echo '<table cellspacing="1" cellpadding="2" class="message"><tr class="tr_message">    
                  <td width="22"></td>
                  <td>Sujet</td>
                  <td>Expéditeur</td>    
                  <td width="125">Date</td>
                </tr> ';
        if($totalDesMessages == 0){
        echo '<tr >    
              <td colspan="4">Vous n\'avez pas de messages</td>
              </tr> ';
        }
        $paslu == 0;
        while($mess=mysql_fetch_array($sql)){
              $sql2=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id = '".$mess['expediteur']."'")or die(mysql_error());
              $expediteur=mysql_fetch_array($sql2);
              if($mess['lu']==0){
                $paslu++;
                $mess['sujet']=$mess['sujet'].' (pas lu)';
              }
                echo '  <tr>
                        <td><input type="Checkbox" name="messadel" value="'.$mess['id'].'" onClick="nbChek(this.checked,this.value)"></td>    
                        <td class="s7">
                        ';
                echo ahref($mess['sujet'],'data.php?div=contenu&choix=messagerie&m=4&id='.$mess['id'],"contenu");
                        //<a href="index.php?m=4&id='.$mess['id'].'">'.$mess['sujet'].'</a></td>
                echo '  <td><a href="statistique.php?mid='.$expediteur['id'].'">'.$expediteur['login'].'</a></td>
                        <td>'.date("d.m.y.   H:i:s",$mess['date']).'</td>  
                      </tr> ';
              
        }
        if($paslu == 0){
          $j = new Joueur();
          $j->load($id);
          if($j->position == 3){
            $j->position = 2;
            $GLOBALS['position'] = 2;
          }
          if($j->position == 1){
            $j->position = 0;
            $GLOBALS['position'] = 0;
          }
          $j->save();
          
        }
        echo ' <tr class="tr_message">
                  <td></td>         
                  <td class="s7"colspan="2">';
                  //<input class="del_mess" name="delete" type="Submit" value="Effacer">
        echo '&nbsp;<button class="search" type="submit" title="Supprimer" onClick="supprMessage(\''.$_GET['m'].'\')"></button>';
        echo '&nbsp;&nbsp;<span id="nbSelect"></span></td>         
                  <td align="right"><span class="c">';
        if($page>1)
          echo'<a href="#" onClick="ajaxLoad(\'contenu\',\'data.php?div=contenu&choix=messagerie&m=1&p='.($page-1).'\');initChek();"><b>&laquo;</b></a>';
        echo'</span><span class="c">';
        if($page<$nombreDePages)
          echo'<a href="#" onClick="ajaxLoad(\'contenu\',\'data.php?div=contenu&choix=messagerie&m=1&p='.($page+1).'\');initChek();"><b>&raquo;</b></a>';                                    
        echo'</span>&nbsp;</td></tr>';
        echo '</table>';
        echo '<div id="return"></div>';
}

function affichage_envoye($id){ // Affiche la liste des message envoyé
  	$sql=mysql_query("SELECT * FROM e_message WHERE type = 'message' AND expediteur = '".$id."' ORDER BY date DESC")or die(mysql_error());
  	    //echo '<table border="2" width="100%"><tr><td>Sujet</td><td>Expéditeur</td><td>Date</td></tr>';
        echo '<table cellspacing="1" cellpadding="2" class="message">  
                <tr class="tr_message">    
                  <td width="22"></td>
                  <td>Sujet</td>
                  <td>Destinataire</td>    
                  <td width="125">Date</td>
                </tr> ';
        while($mess=mysql_fetch_array($sql)){
              $sql2=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id = '".$mess['destinataire']."'")or die(mysql_error());
              $destinataire=mysql_fetch_array($sql2);
              if($mess['lu']==0){
              $mess['sujet']=$mess['sujet'].' (pas lu)';
              }
              echo '  <tr>
                        <td><input type="Checkbox" id="Checkbox" name="n1" value="'.$mess['id'].'" onClick="nbChek(this.checked,this.value)"></td>    
                        <td class="s7">';
                        //<a href="index.php?m=5&id='.$mess['id'].'">'.$mess['sujet'].'</a></td>
              echo ahref($mess['sujet'],'data.php?div=contenu&choix=messagerie&m=5&id='.$mess['id'],"contenu");
              echo'<td><a href="statistique.php?mid='.$destinataire['id'].'">'.$destinataire['login'].'</a></td>
                      <td>'.date("d.m.y.   H:i:s",$mess['date']).'</td>
                      </tr> ';
        }
        echo ' <tr class="tr_message">
                  <td>&nbsp;</td>         
                  <td class="s7"colspan="2">';
        echo '&nbsp;<button class="search" type="submit" title="Supprimer" onClick="supprMessage(\''.$_GET['m'].'\')"></button>';
        echo '&nbsp;&nbsp;<span id="nbSelect"></span></td>         
         
                  <td align="right"><span class="c"><b>&laquo;</b></span><a href="index.php?m=1&n=10">&raquo;</a>&nbsp;</td>     
               </tr>';
        echo '</table>';
}

function affichage_rapport($id){ // Affiche la liste des rapports
  	$sql=mysql_query("SELECT * FROM e_message WHERE type = 'rapport' AND destinataire = '".$id."' ORDER BY date DESC")or die(mysql_error());
  	    //echo '<table border="2" width="100%"><tr><td>Sujet</td><td>Expéditeur</td><td>Date</td></tr>';
        echo '<table cellspacing="1" cellpadding="2" class="message">  
                <tr class="tr_message">    
                  <td width="22"></td>
                  <td>Sujet</td>
                  <td width="125">Date</td>
                </tr> ';
                $nb=0;
                $paslu = 0;
        while($mess=mysql_fetch_array($sql)){
          $nb++;
              $sql2=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id = '".$mess['expediteur']."'")or die(mysql_error());
              $expediteur=mysql_fetch_array($sql2);
              if($mess['lu']==0){
                $paslu++;
              $mess['sujet']=$mess['sujet'].' (pas lu)';
              }
              echo '  <tr>
                        <td><input type="Checkbox" name="n1" value="'.$mess['id'].'" onClick="nbChek(this.checked,this.value)"></td>    
                        <td class="s7">';
              echo ahref($mess['sujet'],'data.php?div=contenu&choix=messagerie&m=4&id='.$mess['id'],"contenu");
              //<a href="index.php?m=1&id='.$mess['id'].'">'.$mess['sujet'].'</a>
              echo '  </td>
                        <td>'.date("d.m.y.   H:i:s",$mess['date']).'</td>  
                      </tr> ';
        }
        
        if($paslu == 0){
          $j = new Joueur();
          $j->load($id);
          if($j->position == 3){
            $j->position = 1;
            $GLOBALS['position'] = 1;
          }
          if($j->position == 2){
            $j->position = 0;
            $GLOBALS['position'] = 0;
          }
          $j->save();
          
        }
        if($nb == 0){
          echo '<tr><td colspan="4">Vous n\'avez pas de rapports</td></tr>';
        }
        echo ' <tr class="tr_message">
                  <td class="s7"colspan="2">';
        echo '&nbsp;<button class="search" type="submit" title="Supprimer" onClick="supprMessage(\''.$_GET['m'].'\')"></button>';
        echo '&nbsp;&nbsp;<span id="nbSelect"></span></td>         
                  <td align="right"><span class="c"><b>&laquo;</b></span><a href="index.php?m=2&n=10">&raquo;</a>&nbsp;</td>     
               </tr>';
        echo '</table>';
}

function voir_message_recu($id_joueur,$id_mess){ // Permet de visioner un message reçu 
  $sql=$GLOBALS["db"]->query("SELECT * FROM e_message WHERE id = '".$id_mess."'")or die(mysql_error());
  $mess=mysql_fetch_array($sql);
  if($mess['lu']==0){// Si il est nouveau alors on le met comme LU !
    $GLOBALS["db"]->query("UPDATE `e_message` SET `lu` = '1' WHERE `id` ='".$mess['id']."';")or die(mysql_error());
  }
  if ($mess['destinataire']==$id_joueur){ // On vérifie bien que c'est bien un message destiné au Joueur
    $sql=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id = '".$mess['expediteur']."'")or die(mysql_error());
    $expediteur=mysql_fetch_array($sql);
    echo '<table width="450" cellspacing="0" cellpadding="0" class="tab_lire_message">
      <tr>
        <td colspan="5"><img src="'.$_SESSION['skin'].'message/haut.png" width="440" height="41" border="0"></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td width="130" rowspan="2"><img src="'.$_SESSION['skin'].'message/expediteur.png" width="113" height="34" border="0"></td>
        <td width="230" background="'.$_SESSION['skin'].'message/underline.png">'.$expediteur['login'].'</td>
        <td width="100" class="right">'.date("d.m.y",$mess['date']).'</td>
        <td width="12"></td>
      </tr>
      
      <tr>
        <td width="3"></td>
        <td width="230" background="'.$_SESSION['skin'].'message/underline.png">'.$mess['sujet'].'</td>
        <td width="100" class="right">'.date("H:i:s",$mess['date']).'</td>
        <td width="12"></td>
      </tr>
      
      <tr>
        <td colspan="5"><img src="'.$_SESSION['skin'].'message/ligne.gif" width="440" height="18" border="0"></td>
      </tr>
      
      <tr>
        <td width="3"></td>
        <td class="td_message_contenu" colspan="3" background="./skin/original/message/underline.png" valign="top" height="300px">
        '.nl2br(stripslashes(($mess['contenu']))).'
        </td>
        <td width="12"></td>
      </tr>
      <tr>
        <td colspan="5" align="center"></td>
      </tr>
      <tr>
        <td colspan="5" height="100"><img src="'.$_SESSION['skin'].'message/ligne.gif" width="440" height="18" border="0">';
        //<a href="./index.php?m=6&mid='.$mess['id'].'&jid='.$mess['expediteur'].'">Répondre</a>
        if($mess['type'] != "rapport"){
        echo ahref(' Répondre<br />','data.php?div=contenu&choix=messagerie&m=6&mid='.$mess['id'].'&jid='.$mess['expediteur'],"contenu");
        }
        
        echo '</td>
      </tr>
    </table>';
  }
}

function voir_message_envoye($id_joueur,$id_mess){ // Permet de visioner un message envoyé
  $sql=$GLOBALS["db"]->query("SELECT * FROM e_message WHERE id = '".$id_mess."'")or die(mysql_error());
  $mess=mysql_fetch_array($sql);
  if ($mess['expediteur']==$id_joueur){ // On vérifie bien que c'est bien un message destiné au Joueur
    $sql=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id = '".$mess['destinataire']."'")or die(mysql_error());
    $destinataire=mysql_fetch_array($sql);
    echo '<table width="450" cellspacing="0" cellpadding="0" class="tab_lire_message">
      <tr>
        <td colspan="5"><img src="'.$_SESSION['skin'].'message/haut2.png" width="440" height="41" border="0"></td>
      </tr>
      <tr>
        <td width="3"></td>
        <td width="130" rowspan="2"><img src="'.$_SESSION['skin'].'message/destinataire.png" width="113" height="34" border="0"></td>
        <td width="230" background="'.$_SESSION['skin'].'message/underline.png">'.$destinataire['login'].'</td>
        <td width="100" class="right">'.date("d.m.y",$mess['date']).'</td>
        <td width="12"></td>
      </tr>
      
      <tr>
        <td width="3"></td>
        <td width="230" background="'.$_SESSION['skin'].'message/underline.png">'.$mess['sujet'].'</td>
        <td width="100" class="right">'.date("H:i:s",$mess['date']).'</td>
        <td width="12"></td>
      </tr>
      
      <tr>
        <td colspan="5"><img src="'.$_SESSION['skin'].'message/ligne.gif" width="440" height="18" border="0"></td>
      </tr>
      
      <tr>
        <td width="3"></td>
        <td class="td_message_contenu" colspan="3" background="'.$_SESSION['skin'].'message/underline.png" valign="top" height="300px">
        '.nl2br(stripslashes($mess['contenu'])).'
        </td>
        <td width="12"></td>
      </tr>
      <tr>
        <td colspan="5" align="center"></td>
      </tr>
      <tr>
        <td colspan="5"><img src="'.$_SESSION['skin'].'message/ligne.gif" width="440" height="18" border="0"></td>
      </tr>
    </table>';
  }
}

function formulaire_ecriture_message($destinataire=0,$mess_repondu=0,$id=0){ // Permet de taper un message
  if($mess_repondu!=0){
    $sql=$GLOBALS["db"]->query("SELECT * FROM e_message WHERE id = '".$mess_repondu."'")or die(mysql_error());
    $mess=mysql_fetch_array($sql);
  }
   // On vérifie bien que c'est bien un message destiné au Joueur
    
    if($destinataire!=0){
      if ($mess['destinataire']==$id){
        $sql=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id = '".$destinataire."'")or die(mysql_error());
        $destinataire=mysql_fetch_array($sql);
        $login_desinataire=$destinataire['login'];
        $sujet="RE:".$mess['sujet'];
        $contenu='

__________________
'.$destinataire['login'].' à écrit :
'.$mess['contenu'];
      }
    }else{
    $login_desinataire="";
    $sujet="";
    $contenu="";
    }
//echo '<form method="post" action="index.php?m=7" accept-charset="UTF-8" name="msg">';
    echo '<table width="430" cellspacing="0" cellpadding="0" class="tab_lire_message">
            <tr>
              <td colspan="5"><img src="'.$GLOBALS['skin'].'message/haut2.png" width="440" height="41" border="0"></td>
            </tr>
            <tr>
              <td width="12"></td>
              <td width="130" rowspan="2"><img src="'.$GLOBALS['skin'].'message/destinataire.png" width="113" height="34" border="0"></td>
              <td width="230" background="'.$GLOBALS['skin'].'message/underline.png"><input type="Text" name="destinataire" id="message_input_dest" value="'.$destinataire['login'].'" size="30" maxlength="20" ></td>
              <td width="100" class="right"></td>
              <td width="12"></td>
            </tr>
            
            <tr>
              <td width="12"></td>
              <td width="230" background="'.$GLOBALS['skin'].'message/underline.png"><input type="Text" name="sujet" id="message_input_sujet" value="'.$sujet.'" size="30" maxlength="35"></td>
              <td width="100" class="right"></td>
              <td width="12"></td>
            </tr>
            
            <tr>
              <td colspan="5"><img src="'.$GLOBALS['skin'].'message/ligne.gif" width="440" height="18" border="0"></td>
            </tr>
            
            <tr>
              <td width="12"></td>
              <td width="406" colspan="3" >
              
              <textarea name="contenu" id="message_textarea" cols="52" rows="15" class="f10">'.stripslashes($contenu).'</textarea>
              
              </td>
              <td width="12"></td>
            </tr>
            <tr>
              <td colspan="5" align="center"></td>
            </tr>
            <tr>
              <td colspan="5"><img src="'.$GLOBALS['skin'].'message/ligne.gif" width="440" height="18" border="0"></td>
            </tr>
          </table>
            &nbsp;<button class="search" type="submit" title="Envoyer" onClick="sendFormEnvoyerMessage()"></button>
            <div id="return"></div>
          ';// <input type="submit" class="del_mess" value="Envoyer"></form>

}


function envoyer_message($id_expediteur,$login_destinataire,$sujet,$contenu){ // Pour Envoyer le message
  $sql=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE login='".$login_destinataire."'");
  $destinataire=mysql_fetch_array($sql);
  $sql2=$GLOBALS["db"]->query("SELECT COUNT(*) AS nb FROM e_message WHERE expediteur='".$id_expediteur."' AND destinataire='".$destinataire['id']."'  AND contenu='".$contenu."' AND sujet='".$sujet."'");
  $present=mysql_fetch_array($sql2);
  if($present['nb'] == 0)
  {
    $date=time();
    if ($sujet==""){
      $sujet= "Néant ...";
    }
    $sujet=addslashes(mysql_real_escape_string(htmlspecialchars($sujet)));
    $contenu=mysql_real_escape_string(htmlspecialchars($contenu));
    
    $j = new Joueur();
    $j->loadSimple($destinataire['id']);

    if($j->position == 0){

      $j->position = 1;
    }elseif($j->position == 2){

      $j->position = 3;
    }

    $j->save();

    
    $requete ="INSERT INTO `e_message` (
    `id` ,
    `type` ,
    `date` ,
    `expediteur` ,
    `destinataire` ,
    `contenu` ,
    `sujet` ,
    `lu`
    )
    VALUES (
    NULL , 'message', '".$date."', '".$id_expediteur."', '".$destinataire['id']."', '".$contenu."', '".$sujet."', '0'
    );";
    
    $GLOBALS["db"]->query($requete)or die(mysql_error());
    echo 'Message envoyé';
  }
  else
    echo 'Message déjà envoyé';
}

function envoyer_rapport($id_destinataire,$sujet,$contenu){ // Pour Envoyer le message
  $sql=$GLOBALS["db"]->query("SELECT * FROM j_compte WHERE id='".$id_destinataire."'");
  $destinataire=mysql_fetch_array($sql);
  $sql2=$GLOBALS["db"]->query("SELECT COUNT(*) AS nb FROM e_message WHERE destinataire='".$destinataire['id']."'  AND contenu='".$contenu."' ");
  $present=mysql_fetch_array($sql2);
  if($present['nb'] == 0)
  {
    $date=time();
    if ($sujet==""){
      $sujet= "Néant ...";
    }
    
    $j = new Joueur();
    $j->load($destinataire['id']);
 
    if($j->position == 0){
      $j->position = 2;
      $GLOBALS['position'] = 2;
    }elseif($j->position == 1){
      $j->position = 3;
      $GLOBALS['position'] = 3;
    }
    $j->save();
    
    $contenu=mysql_real_escape_string($contenu);
    
    $requete ="INSERT INTO `e_message` (
    `id` ,
    `type` ,
    `date` ,
    `expediteur` ,
    `destinataire` ,
    `contenu` ,
    `sujet` ,
    `lu`
    )
    VALUES (
    NULL , 'rapport', '".$date."', '".$id_expediteur."', '".$destinataire['id']."', '".$contenu."', '".$sujet."', '0'
    );";
    
    $GLOBALS["db"]->query($requete)or die(mysql_error());
    //echo 'Message envoyé';
  }
  else{
    //echo 'Message déjà envoyé';
  }
    
}

function nonLu($idJ)
{
  $nb = 0;
  $sql=$GLOBALS["db"]->query("SELECT id FROM e_message WHERE destinataire = '".$idJ."' AND lu = 0");
  while($donnes=mysql_fetch_array($sql))
  {
    $nb++;
  }
  return $nb;


}