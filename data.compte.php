<?php
error_reporting(E_ALL ^ E_NOTICE);
//session_start();
//echo '<br />eeeeeeeeeeeeeee<br /> ';
//print_r($_SESSION);
//require_once("include.php");
/*
echo ' $_SESSION ';
print_r($_SESSION);
echo ' $_GET ';
print_r($_GET);
echo ' $_POST ';
print_r($_POST);
echo '<br />';
*/

 

if(!isset($_SESSION['partie']) OR $_SESSION['partie']==0){
 if($_GET['div'] == "menu_haut"){
    echo $GLOBALS['MenuHautCompte'];
  }
        
  if($_GET['div'] == "menu_gauche"){
       
  }
        
  if($_GET['div'] == "contenu"){
  
  
    $staticPartie = new Partie();
    $j= new Joueur();
    $j->loadSimple($_SESSION['jid']);
    
//demande de vérification de mail    
    if($j->etat == 0)
      $_GET['action'] = "mailAVerif";    

    if($j->etat == 1)
      $_GET['action'] = "compteActif";

    if(!isset($_GET['action']) && $j->etat > 1 && $j->etat < 4)
    {

      //echo 'lancement dune parite';
        
        if ($j->etat == 2)
        {
            $j->lancerPartie(0,'Mon héro','Ma ville');
            $j->save();    
        }
        elseif($j->etat == 3)
        {
            $j->lancerPartie($staticPartie->dernierePartieRush(),'Mon héro','Ma ville');
            $j->save(); 
        }
        else
        {
            //echo 'Lancement...';
            //$j->save();             
        }
        exit();
        
    }
 
 
 
    if($_GET['choix'] == "messagerie"){
      require_once("./include/message.php");
      exit();
    }
  
  	switch ($_GET['action']){
 /*
  	  case "bar" :
          //$login = $j->login;
          require_once ("chat/index.php");


  	  break;
 */ 

     case "mailAVerif":
        $html = '<div class="contenu">
		          <div class="contenu_header_fond">
		          <br /><div align="center">Compte non activé</div>
		          </div>
		    		  		  	
		        	<div class="contenu_fond_centre">
              <br /><br /><div align="center"><b>Votre compte n\'a pas encore été activé.</b><br /><br />
              Vous devez cliquer sur le lien de confirmation que vous avez recu par email pour démarrer une partie<br /></div>
							</div>
		        	
		    </div>
		    ';
		    echo $html;	  	     

  	   break;
  	  case "compteActif":
  	  

      $html = '<div class="contenu">
		          <div class="contenu_header_fond">
		          <br /><div align="center"><b>Compte activé</b></div>
		          </div>	  		  	
		        	<div class="contenu_fond_centre">
              <br /><br /><div align="center"><h3>Votre compte a bien été activé</h3><br /><br /></div>
							<div class="activer">
							<p>Ca y est tu es enfin prêt pour ta première mission. Pour celle-ci, ne cherche pas à faire le mariole . Dès que tu va cliquer sur valider, une attaque sera lancée sur ta ville.
              <br /><br />
              Le but de la mission ? <br /><b>Détruire l\'armée qui arrive !</b><br /><br /> 
							Simple ? oui mais il faut d\'abord se familiariser avec l\'interface, il est donc très fortement conseillé de suivre pas à pas les ordres de l\'instructeur.
              Pour l\'appeler, ce seras le <strong>?</strong> en haut à droite.
              Il t\'expliquera très rapidement les bases.<br /><br />  
              Mais attention, il est un peu gâteux donc si tu ne suis pas exactement ces ordres, il te dira vite n\'importe quoi. 
              Sa santé mentale est devenue un problème avec le temps. Malheureusement personne n\'a envie de s\'occuper des débutant...<br /><br />
              
              Bref demande lui de suite après avoir cliqué sur <b>jouer</b>..<br /><br />
              
              Sinon démerde-toi ! Il te donnera les bases pour accomplir la deuxième mission. Tu seras tout seul au milieu d\'un champs de bataille.<br /><br />
              
              Bon courage !<br /><br />
              
              <center>&nbsp;<button class="jouernonoob" type="submit" title="Lancer" onClick="startNoobPartie()"></button></center></p> <br /><br />
							
							</div>
							<center><img src="skin/original/design/header4.png" /></center><br /><br />
              <div id="return"></div>             
              </div>
		    </div>
		    ';
		    echo $html;	  
        	      
  	   break;
  		case "commentJouer" :
				$html = '<div class="contenu">
		          <div class="contenu_header_fond">
		          <br /><div align="center">Apprend ici comment jouer.</div>
		          </div>
		          <div class="contenu_fond_gauche" style="padding-left: 7px; padding-top: 6px;">		  		  	 
		          </div>		  		  	
		        	<div class="contenu_fond_centre">
              <br /><br /><div align="center">Moins tu dors , Plus tes fort !</div>
							</div>
		        	<div class="contenu_fond_droite">
		  
		  		    </div>
		    </div>
		    ';
		    echo $html;		

  		
  		break;
      
      case "lancer" :
		  	//echo 'action =lancer';
//echo '<br />fffffffffffff<br /> ';
//print_r($_SESSION);
        $p=new Partie();
        $p->load($_GET['c']);
				$html = '<div class="contenu">
		          <div class="contenu_header_fond">
		              <br /><div align="center"><img src="skin/original/design/commencerguerre.png" /></div>
		          </div>
		        	<div class="contenu_fond_centre">';
		            	$html .= '<br /><br /><br />';
		            	
		          $html .= '<div class="textparties">
		            	<h3>Description de la partie : </h3>
		            	
		            	<p>Cette partie permet de se foutre sur la gueule sans foi ni loi.<br />
		            	Votre but ultime sera de motoculter tous vos adversaires.<br />
		            	Armez vous et devenez le mettre des lieux.<br /><br /><br /><br /></p>';
       	
                  
              if($p->type == 'mortequipe' && $j->guilde == 0)
		          {
		              $html .= '<h3>Vous devez faire partie d\'une alliance pour participer à ce type de partie.</h3>';
		          }		
              else
              {       
		          if($p->debut < time())
		          {
		              $html .= '<h3>Pour votre mission vous serez muni d\'une <b>ville</b> et d\'un <b>héro</b>.</h3>
		              <table>
		              <tr>
		                  <td>Ma ville : </td>
		                  <td><input id="nom_ville" class="inputText" name="nom_ville" maxlength="50" autocomplete="off" type="text" value=""></td>
		              </tr>
		              <tr>
		                  <td>Mon héro : </td>
		                  <td><input id="nom_hero" class="inputText" name="nom_hero" maxlength="50" autocomplete="off" type="text" value=""></td>
		              </tr>
		              <tr>
		                  <td colspan="2" align="right">&nbsp;<button class="search" type="submit" title="Search" onClick="sendFormLancerPartie('.$_GET['c'].')"></button></td>
		              </tr>
		              <tr>
		                  <td colspan="2"> <div id="return"> </div></td>
		              </tr>
		              </table>
		              </div>
		              <br /><br /><br /><br /><div align="center"><img src="skin/original/design/header4.png" /></div>
		              </div>';
		            }
		            else
		            {
		              $html .= '<h3>Début dans : '.temp_seconde($p->debut - time() ).' </h3>';
                  
		            }
		          }

		              
		    $html .= '</div>';
		    echo $html;		    
  			break;
  			
  			
  			case "alliance" :
					require_once("alliance.php");
  			break;
  			
  			
  			case "liste" :
  			 
         $html = '<div class="contenu">
          <div class="contenu_header_fond">
              <br /><div align="center"><img src="skin/original/design/listeparties.png" /></div>
          </div>
          <div class="contenu_fond_gauche" style="padding-left: 7px; padding-top: 6px;">';

					$idMortN = $staticPartie->idPartieJouable('mort','normal');
					$pMortN = new Partie();
					$pMortN->load($idMortN);
          $idMortR = $staticPartie->idPartieJouable('mort','rapide');
					$pMortR = new Partie();
					$pMortR->load($idMortR);
					$idMortequipeN = $staticPartie->idPartieJouable('mortequipe','normal');
					$pMortequipeN = new Partie();
					$pMortequipeN->load($idMortequipeN);
					$idMortequipeR = $staticPartie->idPartieJouable('mortequipe','rapide');
					$pMortequipeR = new Partie();
					$pMortequipeR->load($idMortequipeR);
					
  				 $html .='<table width=700>
  				          <tr>
                      <td width="50%" align="center"><h2>Parties Normales</h2></td>
                      <td width="50%" align="center"><h2>Parties Rapides</h2></td>
                    </tr>
  				          <tr>
                      <td width="50%">Parties allant à une <b><big>vitesse classique</big></b>, plus <b><big>facile à jouer</big></b> et nessecitant une présence modérée.</td>
                      <td width="50%">Parties ayant une <b><big>vitesse élevée</big></b>, qui sera le domaine de prédilection des <b><big>joueurs intensifs</big></b>.</td>
                    </tr>
  				          <tr>
                      <td width="50%" align="center" colspan="2"><br /><h3>Faites vous respecter !</h3></td>
                    </tr> 
  				          <tr>
                      <td width="50%" align="center" colspan="2"><br /><b><big>UN seul pourra gagner pour cela il devra pulvériser ses ennemis :)</big></b></td>
                    </tr> 
  				          <tr>
                      <td width="50%">
                      Nom : '.$pMortN->nom.'<br />
                      Débuté il y a : '.temp_complet(time() - $pMortN->debut).'<br />
                      Joueurs : '.$GLOBALS['db']->countOf('j_compte','partie = '.$idMortN).'<br />
                      Prochaine partie dans : '.temp_complet(TEMPS_MINI_AVANT_FIN_MORT - (time() - $pMortN->debut)).'<br />
                      '.ahref('JOUER','data.php?div=contenu&action=lancer&c='.$pMortN->id,"contenu").'<br />               
                      </td>
                      <td width="50%">
                      Nom : '.$pMortR->nom.'<br />
                      Débuté il y a : '.temp_complet(time() - $pMortR->debut).'<br />
                      Joueurs : '.$GLOBALS['db']->countOf('j_compte','partie = '.$idMortR).'<br />
                      Prochaine partie dans : '.temp_complet(TEMPS_MINI_AVANT_FIN_MORT - (time() - $pMortR->debut)).'<br />
                      '.ahref('JOUER','data.php?div=contenu&action=lancer&c='.$pMortR->id,"contenu").'<br />               
                      </td>
                    </tr>
  				          <tr>
                      <td width="50%" align="center" colspan="2"><br /><h3>Que la meilleur alliance gagne !</h3></td>
                    </tr> 
  				          <tr>
                      <td width="50%" align="center" colspan="2"><br /><b><big>Partie en alliance. Une seul doit survire, a vos arme :)</big></b></td>
                    </tr> 
  				          <tr>
                      <td width="50%">
                      Nom : '.$pMortequipeN->nom.'<br />
                      Débuté il y a : '.temp_complet(time() - $pMortequipeN->debut).'<br />
                      Joueurs : '.$GLOBALS['db']->countOf('j_compte','partie = '.$idMortequipeN).'<br />
                      Prochaine partie dans : '.temp_complet(TEMPS_MINI_AVANT_FIN_MORTEQUIPE - (time() - $pMortequipeN->debut)).'<br />
                      '.ahref('JOUER','data.php?div=contenu&action=lancer&c='.$pMortequipeN->id,"contenu").'<br />               

                      </td>
                      <td width="50%">
                      Nom : '.$pMortequipeR->nom.'<br />
                      Débuté il y a : '.temp_complet(time() - $pMortequipeR->debut).'<br />
                      Joueurs : '.$GLOBALS['db']->countOf('j_compte','partie = '.$idMortequipeR).'<br />
                      Prochaine partie dans : '.temp_complet(TEMPS_MINI_AVANT_FIN_MORTEQUIPE - (time() - $pMortequipeR->debut)).'<br />
                      '.ahref('JOUER','data.php?div=contenu&action=lancer&c='.$pMortequipeR->id,"contenu").'<br />               

                      </td>
                    </tr>
  				          
  				          </table><br /><br />
  				          '.ahref('Test','data.php?div=contenu&action=lancer&c='.$staticPartie->dernierePartiePrise(),"contenu");
					/*			          
  		  	$html .='<table width=700>';
                
								$ids_partie = $staticPartie->listeParties();
                $html .='                
		                      <tr class="tr_message">
  		                      <th align="center"><h3> Nom </h3></td>
  		                      <th align="center"><h3> Durée de la partie </h3></td>
  		                      <th align="center"><h3> Début </h3></td>
  		                      <th align="center"><h3> Nombre de joueurs </h3></td>
  		                      <th align="center"><h3> Vitesse </h3></td>
  		                      <th align="center"><h3> Type </h3></td>
		                      </tr>
		                      <tr><th></th></tr>
		                      <tr><th></th></tr>
		                      <tr><th></th></tr>
		                      <tr><th></th></tr>
		                      ';
                $i = 0;
                if(is_array($ids_partie))
                foreach ( $ids_partie as $id){
                  //echo $id.'<br />';
		              $part = new Partie();
		              $part->load($id);
                  $nbJ = $GLOBALS['db']->countOf('j_compte','partie = '.$id);
		              //print_r($ids_partie);
		              if($part->type != "debut" ){
		              $i++;
  		                if ($i%2 == 0){
  		                $html .= '<tr class="tr_noir">';
  		                }else{
  		                $html .= '<tr class="tr_gris" >';
  		                }              
  		            
                  $html .='                
                        	
                          	<td align="center">'.ahref($part->nom,'data.php?div=contenu&action=lancer&c='.$id,"contenu").'</td>
                          	<td align="center">illimité</td>                  	
                            <td align="center">'.date("d-m-Y à H\hi",$part->debut).'</td>
                            <td align="center">'.$nbJ.'</td>
                          	<td align="center">'.$part->vitesse.'</td>
                          	<td align="center">'.$part->type.'</td>
													</tr>
													<img src="skin/original/design/header4.png" style="position:absolute; left:165px; top:360px;" />
                        	';
                  }
                  unset($part);     	
                }
                
                        	
                  //$html .=ahref(' JOUER ','data.php?div=contenu&action=lancer&c=1',"contenu");
              	
								$html .='</table><br /><br /><br />';
							*/	          
          $html .='</div>
  		  	
        	<div class="contenu_fond_centre" align="center">
            <br />
                
                
          </div>
        	<div class="contenu_fond_droite">
  
  		    </div>
        </div>';
        echo $html;
        //echo '<br />ffffffffffffffffffffffff<br /> ';
//print_r($_SESSION);
        break;
        
        
        /////////////////////////

        
        
        
        default :
   		    //echo 'pas d action';
			    echo '
			    <div class="contenu">
			          <div class="contenu_header_fond">
			              <br /><div align="center"><img src="skin/original/design/bienvenueterre.png" /></div>
			          </div>
			          <div class="contenu_fond_gauche" style="padding-left: 7px; padding-top: 6px;">
			  		  	 <img src="./skin/original/design/buymore2.jpg"/>
			          </div>
			  		  	
			        	<div class="contenu_fond_centre">
			            
			          </div>
			        	<div class="contenu_fond_droite">
			  
			  		    </div>
			    </div>
			    ';        
        
        break;
		}
		echo '<div id="gentime">GenTime : '.$time.'('.$GLOBALS['db']->getExecTime().'s)  Requetes : '.$GLOBALS['db']->nbQueries.' </div> ';
  }
}
else
{
echo 'Actualiser la page pour continuer';
}
