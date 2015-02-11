<?php

if(!isset($_SESSION['jid']))
  session_start();
require_once("include.php");
                                                                                                                                                               
    $joueur= new Joueur();
    $joueur->loadSimple($_SESSION['jid']);
    $my_alliance = new Alliance();
    $my_alliance->load($joueur->guilde); 
if(isset($_POST['option'])){

if(($_POST['option'])=='newAlly'){
  if(isset($_POST['t']) AND $_POST['t'] != "" AND isset($_POST['n']) AND $_POST['n'] != "" AND isset($_POST['descrI']) AND $_POST['descrI'] != "" AND isset($_POST['descrE']) AND $_POST['descrE'] != ""){
    if(nomValide($_POST['t']) && nomValide($_POST['n']) ){
      if( ! Alliance::nomLibre($_POST['t'],$_POST['n']))
        echo 'Le Nom ou le Tag est déjà utilisé';
      else
      {
      $t=$_POST['t'];
      $n=$_POST['n'];
      $descrI=$_POST['descrI'];
      $descrE=$_POST['descrE'];
        $ally = new Alliance();
        
        $return = $joueur->changerAlliance($_SESSION['jid'],0,"","");
        if($return == 0){
          $id=$ally->insert($t,$n,$descrI,$descrE,$_SESSION['jid']);
          $joueur->changerAlliance($_SESSION['jid'],$id,$n,$t);
          $joueur= new Joueur();
          $joueur->loadSimple($_SESSION['jid']);
          $joueur->guilde=$id;
          $joueur->save();
          echo 'Création réussie ...';
        }else{
          echo 'Impossible pour le moment : '.$return;
        }
      } 
    }
    else
      echo 'Nom ou Tag : seul les caratères alpha-numériques le _ et l\'espace sont autorisé (15 maximum).';
  }
  else
    echo 'Un des champs est vide.';

}

if($_POST['option'] == "changerDescription"){
    $joueur= new Joueur();
    $joueur->loadSimple($_SESSION['jid']);
    $my_alliance = new Alliance();
    $my_alliance->load($joueur->guilde);   
    $my_alliance->descriptionI=htmlspecialchars($_POST['descrI']);
    $my_alliance->descriptionE=htmlspecialchars($_POST['descrE']);
    $my_alliance->save();
    echo 'Sauvegarde réussié.';
  }

if($_POST['option'] == "NewRang"){
  $id=Joueur::returnID($_POST['i']);
  if(!$id){
    echo 'Ce joueur n\'existe pas.';
  }else{
    $joueurAModif= new Joueur();
    $joueurAModif->loadSimple($id);
    $joueur= new Joueur();
    $joueur->loadSimple($_SESSION['jid']);
    if($joueurAModif->guilde == $joueur->guilde)
    {
      $my_alliance = new Alliance();
      $my_alliance->load($joueur->guilde); 
      $my_alliance->ajoutRang($_POST['r'],$id);
      $my_alliance->save();
      echo 'Enregistrement effectué';
    }
    else
    {
    echo 'Ce joueur n\'appartient pas à votre alliance.';
    }
  }
  
}
       
}else{
					//echo 'action =alliance';  
		      $html = '<div class="contenu">
		      <div class="contenu_header_fond">
		        <br /><div align="center"><img src="'.$_SESSION['skin'].'/design/alliance.png" /></div>
		      </div>
		      <div class="contenu_fond_gauche" style="padding-left: 50px; padding-top: 6px;">';
		      $html.='<h2>';	
          if($joueur->guilde==0){ //si le joueur n'est pas dans une alliance			  

          }
          else
          {
            $html.=ahref('Accueil','alliance.php?t=p',"contenu").'<br />';
            $html.=ahref('Membres','alliance.php?t=i',"contenu").'<br />';
            $html.=ahref('Actus','alliance.php?t=g',"contenu");
            
  					if($my_alliance->droitSufisant('membre',$_SESSION['jid']))
  		          $html .='<br />'.ahref('Demandes','alliance.php?t=d',"contenu");
  					if($my_alliance->droitSufisant('total',$_SESSION['jid']))
  		          $html .='<br />'.ahref('Droits','alliance.php?t=c',"contenu");
  					if($my_alliance->droitSufisant('rang',$_SESSION['jid']))
  		          $html .='<br />'.ahref('Rangs','alliance.php?t=r',"contenu");					
            if($my_alliance->droitSufisant('description',$_SESSION['jid']))
  		          $html .='<br />'.ahref('Description','alliance.php?t=t',"contenu");
  		      $html .='<br />'.ahref('Options','alliance.php?t=o',"contenu");
          }
          $html.='</h2>';
          $html.='</div>
		      
		      <div class="contenu_fond_centre" align="center">';
		       
		
		        
		        
		        if($joueur->guilde == 0){ //si le joueur n'est pas dans une alliance
		          $html .= ahref('Liste','alliance.php?t=l',"contenu").' | '.
		          ahref('Créer','alliance.php?t=c',"contenu");
				      $html .= '';
		          if(!isset($_GET['t'])) $_GET['t']='l';
		          switch ($_GET['t']){
		            case 'l' :
		              //liste des alliances
		              //$html .= 'Liste des alliances';
		              if(isset($_GET['a'])){
		                $my_alli = new Alliance();
		                $my_alli->load($_GET['a']);
                    $my_alli->supprDemande($joueur->id);
		                $my_alli->save();
		              }

                  $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $html .='                
		                      <tr class="tr_message">
		                      <td><h3> Tag </h3></td>
		                      <td><h3> Nom </h3></td>
		                      <td><h3> Membres </h3></td>
		                      <td><h3> Demande en cour </h3></td>
		                      <td>+ Info</td>
		                      </tr>
		                      ';
		              $allys = Alliance::listeAlliance();
		              if(is_array($allys)){
  		              foreach ( $allys as $id){        
  		                $ally = new Alliance();
  		                $ally->load($id);
  		                $html .='                
  		                        <tr>
  		                        <td>'.$ally->tag.' </td>
  		                        <td>'.$ally->nom.' </td>
  		                        <td>'.$ally->nbMembres().'</td>';
  		                        if($ally->enCourDemande($joueur->id))
    		                        $html .='<td>'.ahref('Annuler','alliance.php?t=l&a='.$id,"contenu").'</td>';
  		                        else
  		                          $html .='<td> </td>';
  		                        $html .='<td>'.ahref('<img src="'.$_SESSION['skin'].'/design/infos.gif" alt="infos" />','alliance.php?t=i&a='.$id,"contenu").'</td>';		                        
                      //$html .='<td></td></tr>';
  		              }
                  }else{
                  $html .="Pas encore d'alliance";
                  }     
		              $html .='</table>';
		            break;
		            case 'i' :
		              //info de l'alliance $_GET['a']
		              $my_alliance = new Alliance();
		              $my_alliance->load($_GET['a']);
            
		            
	
		              $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $i=0;
		              //$di=$my_alliance->descriptionI;
		              //$de=$my_alliance->descriptionE;
		              if(is_array($my_alliance->rang)){
                    foreach($my_alliance->rang as $key => $value){
  		                $joueur= new Joueur();
  		                $joueur->loadSimple($key);
  		                $i++;
  		                if ($i%2 == 0){
  		                $html .= '<tr class="tr_noir"><td> ';
  		                }else{
  		                $html .= '<tr class="tr_gris" ><td> ';
  		                }              
  		                $html .= $joueur->login.' </td><td> '.$value.' </td></tr>'; 
  		              }
		              }
		              $html .= '</table>';  
                  $html .= '<br /><br />';	                  
                  $html .= '<img src="'.$_SESSION['skin'].'/design/pres_alli.png" alt="Présentation de l\'alliance" />';
                  $html .= '<br /><br />';
                  $html .= '<table>';	              
                  //$html .= '</td></tr><tr><td colspan="2">';
		              $html .= '<tr><td>';
                  $html .= $GLOBALS['avant_textarea'].'<div style="background:none;border:none;color:#999900;width:400px;min-height:100px;">';
		              $html .= '<div class="desc_alli">'.nl2br($my_alliance->descriptionEtuned).'</div>';
		              $html .= '</div>'.$GLOBALS['apres_textarea'];
		              $html .= '</td></tr>';
                  $html .='</table>';
		              $html .= ahref('<div class="rejoindre"><img src="/design/btn-rejoindre.png" alt="rejoindre une alliance" /></div>','alliance.php?t=d&a='.$_GET['a'],"contenu");              
		            break;
		            case 'd' :
		              //demande d'hadésion
		              $my_alliance = new Alliance();
		              $my_alliance->load($_GET['a']);
		              if($my_alliance->ajoutDemande($_SESSION['jid'])){//demande corect
		                $my_alliance->save();
		                $html .= '<br /><br />Vous avez fait la demande pour entrez dans l\'alliance '.$my_alliance->nom.'<br />';
		              }else
		                $html .= '<br /><br />Vous avez déjà fait la demande pour entrer dans cette alliance <br />';
		            break;
		            case 'c' :
		              //création d'une alliance
		                 $html .='<table cellspacing="1" cellpadding="2" class="message">
		              	     <tr class="tr_message"><td width="100%" colspan="2" align="center"><h3>Création de l\'alliance<h3></td></tr>
		              	     <tr>
		                        <td width="36%" align="right"> Tag&nbsp;: </td>
		                        <td align="center"> <input id="ally_tag" class="inputText" name="ally_tag" maxlength="50" autocomplete="off" type="text" value=""></td>
		                     </tr>
		                     <tr>  
		                        <td width="150" align="right"> Nom&nbsp;: </td>
		                        <td align="center"> <input id="ally_nom" class="inputText" name="ally_nom" maxlength="50" autocomplete="off" type="text" value=""></td>
		                     </tr>
		                     <tr>  
		                        <td colspan="2"> Description Interne (visible que par les membres): </td>
		                     </tr>
		                     <tr>   
		                        <td colspan="2" align="center">';
                      $html .= $GLOBALS['avant_textarea'].' <div style="background:none;border:none;color:#999900;width:400px;min-height:100px;">';
                      $html .= '<textarea id="ally_descrI" name="ally_descrI" class="inputTextarea" rows="6" cols="47" name="info" style="background:transparent;color:#999900;" ></textarea>';  
                      $html .= '</div>'.$GLOBALS['apres_textarea'];
                      $html .= '
                            </td>
		                     </tr>
		                     <tr>  
		                        <td colspan="2"> Description Externe (visible par tous le monde): </td>
		                     </tr>
		                     <tr>    
		                        <td colspan="2" align="center">'; 
                      $html .= $GLOBALS['avant_textarea'].' <div style="background:none;border:none;color:#999900;width:400px;min-height:100px;">';                            
                      $html .= '<textarea id="ally_descrE" name="ally_descrE" class="inputTextarea" rows="6" cols="50" name="info" style="background:transparent;color:#999900;"></textarea>';
                      $html .= '</div>'.$GLOBALS['apres_textarea'];      
                      $html .= '      
                            </td>
		                     </tr>
		                     </table>
		                     <table cellspacing="1" cellpadding="2" class="message">
		                     <tr>
		                        <td width="100%" align="center">  &nbsp;<button class="search" type="submit" title="Search" onClick="sendFormCreationAlliance()"></button></td>
		                     </tr>
		                     <tr>
		                        <td><div id="return"></div></td>
		                     </tr>
		                    </table>';
		              
		            break;
		            default :
		              //liste des alliances
		              $html .= 'erreur de choix'; 
		          }
		        }else{//si le joueur est dans une alliance
		         // $my_alliance = new Alliance();
		          //$my_alliance->load($joueur->alliance);          
				      //$demande = $my_alliance->demande;//utilisé dans la partie demande

				  
          
          

		          if(!isset($_GET['t'])) $_GET['t']='p';
		          switch ($_GET['t']){
		            case 'p' :
		              //page d'acceuille/présentation

		              //$ally = new Alliance();
		              //$ally->load($joueur->alliance);



		
		              $html .= 'Vous etes dans l\'alliance <b>'.$my_alliance->nom.'</b><br />';
		             
	
		              $html .= '<br /><br /><table cellspacing="1" cellpadding="2" class="message">';
		              $i=0;
		              //$di=$my_alliance->descriptionI;
		              //$de=$my_alliance->descriptionE;
		              if(is_array($my_alliance->rang))
                  foreach($my_alliance->rang as $key => $value){
		                $joueur= new Joueur();
		                $joueur->loadSimple($key);
		                $i++;
		                if ($i%2 == 0){
		                $html .= '<tr class="tr_noir"><td> ';
		                }else{
		                $html .= '<tr class="tr_gris" ><td> ';
		                }              
		                $html .= $joueur->login.' </td><td> '.$value.' </td></tr>'; 
		              }
		              $html .= '</table>';  
                  
                  $html .= '<table ><tr><td align="center">';
                  $html .= '<h2>Description interne</h2></tr></td><tr><td>';		              
		              $html .= $GLOBALS['avant_textarea'].'<div style="background:none;border:none;color:#999900;width:400px;min-height:100px;">';
		              $html .= nl2br($my_alliance->descriptionItuned);
		              $html .= '</div>'.$GLOBALS['apres_textarea'];
		              $html .= '</td></tr><tr><td align="center">';
                  //$html .= '</td></tr><tr><td colspan="2">';
                  $html .= '<h2>Description Externe</h2></tr></td><tr><td>';
		              $html .= $GLOBALS['avant_textarea'].'<div style="background:none;border:none;color:#999900;width:400px;min-height:100px;">';
		              $html .= nl2br($my_alliance->descriptionEtuned);
		              $html .= '</div>'.$GLOBALS['apres_textarea'];
		              $html .= '</td></tr></table>';
		
		            break;
		            case 'i' :
		              //liste des membres
		              //$ids_membres=$my_alliance->listeMembre();
		              if(isset($_GET['e'])){
		                $my_alliance->supprMembre($_GET['e']).
		                $my_alliance->save();                 
                    $html .= 'le joueur '.$_GET['e'].' a était expulsé';		                
		              }
		              
		              
		              $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $html .= '<tr class="tr_message"><td>Nom</td><td>Points</td><td>En jeu</td>';
                  if($my_alliance->droitSufisant('membre',$_SESSION['jid']))
                    $html .= '<td>Expulsion</td>';
                  $html .= '</tr>';
		              $i = 0;
		              $lm=$my_alliance->listeMembre();
		              if(is_array($lm))
                  foreach($lm as $id){
                  
                    $i++;
		                $joueur= new Joueur();
		                $joueur->loadSimple($id);
		                if ($i%2 == 0){
		                $html .= '<tr class="tr_noir"><td> ';
		                }else{
		                $html .= '<tr class="tr_gris"><td> ';
		                }
		                
		                $html .= ''.$joueur->login.' </td><td> '.$joueur->points.' </td><td> ';
		                if($joueur->partie==0) 
		                  $html .= 'Non';
		                else 
		                  $html .= 'Oui';
		                $html .= ' </td>';
                    if($my_alliance->droitSufisant('membre',$_SESSION['jid']) )
                      $html .= '<td>';
                      if(! $my_alliance->droitSufisant('total',$joueur->id))
                        $html .=ahref('Expulser','alliance.php?t=i&e='.$joueur->id.'',"contenu");
                      $html .='</td>';
                    $html .= '</tr>';
		              
		              }
		              $html .= '<tr class="tr_message"><td colspan="3"> </td><td align="right"><span class="c"><a href="#">&laquo;</a></span><a href="#">&raquo;</a>&nbsp;</td></tr>';
		              $html .= '</table>';
		            break;
		            
		            case 'g' :
		            //actualité de l'alliance    
		              $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $html .= '<tr class="tr_message"><td>Date</td><td>Evenement</td></tr>';
		              $i = 0;
		              $tab=array_reverse($my_alliance->log,true);
                  foreach($tab as $key => $value){
                    $i++;		                
		                if ($i%2 == 0){
		                $html .= '<tr class="tr_noir"><td> ';
		                }else{
		                $html .= '<tr class="tr_gris"><td> ';
		                }
		                $html .= ''.date("d/m/Y H:i:s",$key).' </td><td> '.$value.' </td> ';
		              }
                  $html .= '<tr class="tr_message"><td> </td><td align="right"><span class="c"><a href="#">&laquo;</a></span><a href="#">&raquo;</a>&nbsp;</td></tr>';
		              $html .= '</table>';
		            break;
		            
		            case 'd' :
                  //gérer les demandes
		              if(isset($_GET['i'])){
                    $my_alliance = new Alliance();
		                $my_alliance->load($joueur->guilde);          
                    $my_alliance->accepterDemande($_GET['i']);
                    $my_alliance->save();
                    $html .= 'Le joueur a était accepté.';
		              }
		              //$my_alliance = new Alliance();
		              //$my_alliance->load($joueur->alliance); 

		              $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $html .= '<tr class="tr_message"><td> <h3>Nom</h3> </td><td> <h3>Points</h3> </td><td> <h3>Accepter</h3> </td></tr>';
		              $i=0;
                  foreach($my_alliance->demande as $id){
		                $joueur= new Joueur();
		                $joueur->loadSimple($id);
		                $i++;
		                if ($i%2 == 0){
		                $html .= '<tr class="tr_noir"><td> ';
		                }else{
		                $html .= '<tr class="tr_gris" ><td> ';
		                }
		                
		                $html .= $joueur->login.' </td><td> '.$joueur->points.' </td><td>'.ahref('Accepter','alliance.php?t=d&i='.$id,"contenu").'</td></tr>';              
		              }
		              $html .= '<tr class="tr_message"><td colspan="2"> </td><td align="right"><span class="c"><a href="#">&laquo;</a></span><a href="#">&raquo;</a>&nbsp;</td></tr>';
		              $html .= '</table>';              
		            break;
		            case 'c' :
		              //gestion des controls(droits)
		              if(isset($_GET['id']) && isset($_GET['d'])){
		                 //$html .= ' id '.$_GET['id'].' d '.$_GET['d'].'<br />';
		                 $my_alliance->inverserDroit($_GET['d'],$_GET['id']);
		                 $my_alliance->save();
		              }
		              $html .= '<h2>Controles des droits de l\'alliance</h2>';
		              $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $html .= '<tr class="tr_message"><td>Nom</td><td>Total</td><td>Membres</td><td>Rangs</td><td>Poste</td></tr>';
                  //$ally = new Alliance();
		              //$ally->load($joueur->alliance);
		              $i=0;
                  foreach($my_alliance->listeMembre() as $id){
                  $i++;
                    $joueur= new Joueur();
		                $joueur->loadSimple($id);
		                if ($i%2 == 0){
		                $html .= '<tr class="tr_noir"><td> ';
		                }else{
		                $html .= '<tr class="tr_gris"><td> ';
		                }
  		              
                    $html .= $joueur->login;
                    $html .= '</td><td>';
                    
                    if($my_alliance->aLeDroit('total',$id))
                      $r= ' Oui '; 
                    else
                      $r= ' Non ';
                     if($id == $_SESSION['jid'])//protection le fonctateur ne peut pas s'enlever les deroits totaux
                        $html .=$r;
                     else
                        $html .=ahref($r,'alliance.php?t=c&id='.$id.'&d=total',"contenu");                    
                    $html .= '</td><td>';
                    if($my_alliance->aLeDroit('membre',$id))
                      $r= ' Oui '; 
                    else
                      $r= ' Non ';  
                      $html .=ahref($r,'alliance.php?t=c&id='.$id.'&d=membre',"contenu");                  
                    $html .= '</td><td>';
                    if($my_alliance->aLeDroit('rang',$id))
                      $r= ' Oui '; 
                    else
                      $r= ' Non ';
                      $html .=ahref($r,'alliance.php?t=c&id='.$id.'&d=rang',"contenu");                    
                    $html .= '</td><td>';
                    if($my_alliance->aLeDroit('description',$id))
                      $r= ' Oui '; 
                    else
                      $r= ' Non ';
                      $html .=ahref($r,'alliance.php?t=c&id='.$id.'&d=description',"contenu");                     
                    $html .= ' </td></tr> '; 
                  } 
                  $html .= '<tr class="tr_message"><td colspan="4"> </td><td align="right"><span class="c"><a href="#">&laquo;</a></span><a href="#">&raquo;</a>&nbsp;</td></tr>';
		              $html .= '</table>';
		              
		            break;
		            case 'r' :
		              //modification des rangs
		              if(isset($_GET['i'])){
		                $my_alliance->supprRang($_GET['i']);
		                $my_alliance->save();
		              }
		            
		              
		              //$ally = new Alliance();
		              //$ally->load($joueur->alliance);
		              $html .= '<table cellspacing="1" cellpadding="2" class="message">';
		              $html .= '<tr class="tr_message"><td> <h3>Nom</h3> </td><td> <h3>Rang</h3> </td><td>&nbsp;</td></tr>';
		              $i=0;
                  foreach($my_alliance->rang as $key => $value){
		                $joueur= new Joueur();
		                $joueur->loadSimple($key);
		                $i++;
		                if ($i%2 == 0){
		                $html .= '<tr class="tr_noir"><td> ';
		                }else{
		                $html .= '<tr class="tr_gris" ><td> ';
		                }              
		                $html .= $joueur->login.' </td><td> '.$value.' </td><td>'.ahref('Supprimer','alliance.php?t=r&i='.$key,"contenu").'</td></tr>'; //   <td>'.ahref('Accepter','alliance.php?t=d&i='.$id,"contenu").'</td>          
		              }
		              $html .= '<tr class="tr_message"><td colspan="2"> </td><td align="right"><span class="c"><a href="#">&laquo;</a></span><a href="#">&raquo;</a>&nbsp;</td></tr>';
		              $html .= '</table>';  
		              $html .= '<table width="80%"><tr><td>';
		              $html .= 'Pseudo : <input id="pseudo" class="inputText" name="pseudo" maxlength="20" autocomplete="off" type="text" value=""></td>
		                        <td>Rang : <input id="rang" class="inputText" name="rang" maxlength="20" autocomplete="off" type="text" value=""></td>';
                  $html .= '</tr><tr><td colspan="2"> &nbsp;<button class="search" type="submit" title="Search" onClick="sendFormNouveauRang()"></button><div id="return"></div></td></tr></table>';
		            break;      
                         
		            case 't' :
		              if(isset($_GET['i']))
		                echo $_GET['i'];
		              //modification des textes des présentation
		              $html .= '<br /><br />';
		              $html .= '<h2>Description interne</h2><br />';
		              $html .= $GLOBALS['avant_textarea'].'<textarea id="descrI" rows="6" cols="40" class="inputText" style="background:none;border:none;color:#999900;">';
		              $html .= $my_alliance->descriptionI;
		              $html .= '</textarea>'.$GLOBALS['apres_textarea'].'<br /><br />';
		              $html .= '<h2>Description externe</h2><br />';
		              $html .= $GLOBALS['avant_textarea'].'<textarea id="descrE" rows="6" cols="40" class="inputText" style="background:none;border:none;color:#999900;">';
		              $html .= $my_alliance->descriptionE;
		              $html .= '</textarea>'.$GLOBALS['apres_textarea'];
		              $html .= '<br />';
		              $html .= ' &nbsp;<button class="search" type="submit" title="Search" onClick="sendFormChangerDescriptionAlliance()"></button>';
		              $html .= '<div id="return"></div>';
                break;
                
                case 'o' :
                //option du joueur
                  /*if($my_alliance->droitSufisant('total',$_SESSION['jid']))//option administrateur
                  {
                    //$html .='<br />'.ahref('Quitter l\'alliance','alliance.php?t=o&o=q',"contenu");
                  
                  }
                  else //option d'un membre simple
                  {
                  */
                    if(isset($_GET['o'])){
                      if($_GET['o'] == 'q'){
                        //$ally = new Alliance();
		                    //$ally->load($joueur->alliance);    
                        $my_alliance->supprMembre($_SESSION['jid']);
                        $my_alliance->save();
                        $html .= 'Vous avez bien quitté l\'alliance';
                        }                   
                    }
                    $html .='<br />'.ahref('Quitter l\'alliance','alliance.php?t=o&o=q',"contenu");
                  //}
                
                break;
		         
		            default :
		              //page d'acceuille/présentation
		              $html .= 'erreur de choix'; 
		          }   
		        }     
		         
		       
		      $html .= '</div>
		      <div class="contenu_fond_droite">
		      
		      </div>
		      </div>';
		      echo $html;
}
?>
