<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("include.php");




if(!isset($_SESSION['jid']) OR $_SESSION['jid']==0){

        
  if($_GET['div'] == "menu_gauche"){
  
  }
        
  if($_GET['div'] == "contenu" OR $GLOBALS['home'] == 1){
    if(!isset($_GET['action'])){
    $inscrits = $GLOBALS['db']->countOfAll('j_compte');
    $actifs = $GLOBALS['db']->countOf('j_compte',(' date_dern_action > '.(time() - 86400)));
    $enLigne = $GLOBALS['db']->countOf('j_compte',(' date_dern_action > '.(time() - 900)));
    echo '
    <div class="contenu_accueil" onLoad="Chargement()">
       <div class="rubrique">
          <div class="s_rub">
            <center><img src="skin/original/design/screen.png" alt="screenshots" /></center>
            <center>
           
            '.ahref('<img src="skin/original/design/img_screen.png" alt="screenshots" class="screen"/>','data.login.php?div=contenu&action=screenshot',"contenu").'
            </center>
          </div>
          <div class="s_rub">
            <center><img src="skin/original/design/statistiques.png" alt="screenshots" /></center>
            <table cellpadding="0" cellspacing="0" width="190">
            <tr>
              <td>Joueurs inscrits</td>
              <td align="right"><b>'.$inscrits.'</b></td>
            </tr>
            <tr>
              <td>Joueurs actifs (24H)</td>
              <td align="right"><b>'.$actifs.'</b></td>
            </tr>
            <tr>
              <td>Joueurs en ligne</td>
              <td align="right"><b>'.$enLigne.'</b></td>
            </tr>
            </table>
          </div>
          <div class="s_rub2">
            <center><img src="skin/original/design/news.png" alt="news" /></center>           

          </div>
       </div>
       <div class="accueil">
        <center><img src="skin/original/design/acc_header2.png" alt="news" class="header"/></center>
        <div class="acc_inscri">
          <strong>After War</strong> est un jeu de gestion / stratégie <b>innovant</b>, <b>immersif</b> et <b>gratuit</b> par navigateur.<br /><br />Inscrivez-vous et découvrez ce jeu <b>épique</b>. 
          
          <a href="./inscription.php"><div class="inscri"></div></a>
          </div>
        <div class="login">
          <img src="skin/original/design/login_acc2.png" alt="login" /><br />
          <input id="login_login" class="inputText" name="login_login" maxlength="50" autocomplete="off" type="text" value="'.$cookie_login.'"><br />
          <img src="skin/original/design/mdp.png" alt="mot de passe" class="mdp"/><br />
          <input id="mdp_login" class="inputText" name="mdp_login" maxlength="50" autocomplete="off" type="password" value="'.$cookie_login.'" onkeypress="if (event.keyCode == 13) {sendFormLogin();}"><br />
          <a href="">Mot de passe oublié ?</a><br />
          <button class="search" type="submit" title="Search" onClick="sendFormLogin()"></button>
          <div id="returnPass"> &nbsp; </div>
        </div>
        <div class="descri">
          <img src="skin/original/design/img_buymore.png" alt="paysage post-apocalyptic" width="300"/>
          <p>
          Bienvenue à toi étranger ou vétéran
          dans cette contrée dévastée par une
          effroyable guerre nucléaire, où 
          notre bonne vielle Terre fut rendue 
          inhabitable durant plusieurs siècles 
          à cause des radiations, détruisant la
          vie sur son passage...<br><br>
          
          Mais suffisait-il d\'une explosion
          pour réduire à néant le monde 
          vivant ?<br><br>
          
          Péniblement, des humains se relèvent
          des cendres nucléaires.<br><br>
          
          Endossez le rôle d\'un de ces 
          survivants puis, <b>bâtissez</b> ,<b>développez</b>
          et <b>imposez-vous</b> dans un monde 
          sans merci. 
          </p>
       </div>   
       <noscript><center><strong><font color="red">Javascript est indispenssable pour jouer !</font></strong></center></noscript>
    </div>
    ';
    }elseif($_GET['action']=="connect"){
          if(isset($_COOKIE['login']) AND $_COOKIE['login'] !="" AND isset($_COOKIE['mdp']) AND $_COOKIE['mdp'] != ""){
            $cookie_login = $_COOKIE['login'];
            $cookie_mdp = "autopassword";
          }else{
            $cookie_login = "";
            $cookie_mdp = "";
          }
         echo '<div class="contenu">
          <div class="contenu_header_fond">
              <br /><div align="center"><img src="skin/original/design/bienvenueterre.png" /></div>
          </div>
          <div class="contenu_fond_centre" align="center">
            <br />
                <table>
              	<tr><td>Demande d\'identification du sujet :</td><td> </td></tr>
              	<tr><td>Login :</td><td></td></tr>
              	<tr><td><input id="login_login" class="inputText" name="login_login" maxlength="50" autocomplete="off" type="text" value="'.$cookie_login.'"></td><td></td></tr>
              	<tr><td>Mot de passe :</td><td></td></tr>
              	<tr><td><input id="mdp_login" class="inputText" name="mdp_login" maxlength="50" autocomplete="off" type="password" value="'.$cookie_mdp.'" onkeypress="if (event.keyCode == 13) {sendFormLogin();}"></td>
                <td> &nbsp;<button class="search" type="submit" title="Search" onClick="sendFormLogin()"></button> </td>
              	</tr>
              	<tr><td><div id="returnPass"> &nbsp; </div></td><td> </td></tr>
              	
              	</table>
                <br /><br />
          </div>
        </div>';

    }elseif($_GET['action']=="new"){
         /* $html= '<div class="contenu">
          <div class="contenu_header_fond">
              <br /><div align="center"><img src="skin/original/design/bienvenueterre.png" /></div>
          </div>
          <div class="contenu_fond_centre"  align="left">
            <br />';

           $html .='
<div align="center"> Tiens, voila de la bleusaille qui se sent d\'attaque...<br /> Si tu est sur que tu ne tes pas trompé de porte, remplis ce formulaire.<br /><br /></div>


              	    
      <div align="center"><table>
	     <tr><td width="100%" colspan="2" align="center"><h2>Bulletin&nbsp;-&nbsp;Inscription<h2></td></tr>
	     <tr><td colspan="2">&nbsp;</td></tr>
       <tr><td colspan="2">Ce sera ta seule identité dans ce monde !</td></tr>
	     <tr>
          <td> Nom de code&nbsp;: </td>
          <td> <input id="lns_login" class="inputText" name="lns_login" maxlength="50" autocomplete="off" type="text" value=""></td>
       </tr>
 
       <tr><td colspan="2">&nbsp;</td></tr>
       <tr><td colspan="2">Et la preuve de ton identité.</td></tr>
       <tr>  
          <td> Passe &nbsp;: </td>
          <td> <input id="ins_pass" class="inputText" name="ins_pass" maxlength="50" autocomplete="off" type="password" value=""></td>
       </tr>

       <tr><td colspan="2">&nbsp;</td></tr>    
       <tr><td colspan="2">Afin de vérifier que tu possèdes un chez toi,<br /> et que tu n\'est pas une machine...</td></tr>   
       <tr>  
          <td> Mail @&nbsp;: </td>
          <td> <input id="ins_mail" class="inputText" name="ins_mail" maxlength="50" autocomplete="off" type="text" value=""></td>
       </tr>
       <tr><td colspan="2">&nbsp;</td></tr>
       <tr>
          <td colspan="2" align="center">  &nbsp;<button class="search" type="submit" title="Search" onClick="sendFormInscription()"></button></td>   
       </tr>
       <tr>
          <td colspan="2"> </td>
       </tr>
      </table>
      <br /><div align="center"><img src="skin/original/design/header4.png" /></div>
      <div id="return"> </div>
      </div>
	    </div>
      </div>';
    echo $html;
      /*                   <tr>  
                        <td> Ma&nbsp;ville&nbsp;: </td>
                        <td> <input id="ins_ville" class="inputText" name="ins_ville" maxlength="50" autocomplete="off" type="text" value=""></td>
                     </tr>
                     <tr>  
                        <td> Mon&nbsp;héro&nbsp;: </td>
                        <td> <input id="ins_hero" class="inputText" name="ins_hero" maxlength="50" autocomplete="off" type="text" value=""></td>
                     </tr>
    */
    }elseif($_GET['action']=="histoire"){
          $html= '<div class="contenu">
          <div class="contenu_header_fond">
             <img src="./skin/original/design/histoire.png" alt="histoire" class="histoire"/>
          </div>
          <div class="contenu_fond_centre" align="left">
            <br />';
            include("include/histoire.php");
            if(!isset($_GET['step'])){
             $html .= '<div class="histoire2">';
             $html .=$step[1]; 
             $html .= '</div>';
             
               $html.=  ''.ahref('<img id="menu_haut_onglet" src="./skin/original/design/suivant.png" alt="Suivant" class="lien_suivant"/>','data.login.php?div=contenu&action=histoire&step=2',"contenu").'';
            }else{

               $html .= '<div class="histoire2">';
               $html .=$step[$_GET['step']]; 
               $html .= '<div class="liens_histoire">';
               if($_GET['step'] < count($step)){
                  $html.=  ''.ahref('<img id="menu_haut_onglet" src="./skin/original/design/suivant.png" class="lien_suivant" alt="Suivant"/>','data.login.php?div=contenu&action=histoire&step='.($_GET['step']+1),"contenu").'';
              }
               
               $html.=  ''.ahref('<img id="menu_haut_onglet" src="./skin/original/design/precedent.png" alt="Précédent" class="lien_precedent" />','data.login.php?div=contenu&action=histoire&step='.($_GET['step']-1),"contenu").'';
               
               $html .= '</div>';
               $html .= '</div><br /><br />'; 
            }
            
           $html .='

      </div>

';
    echo $html;
    }elseif($_GET['action']=="screenshot"){
          $html= '<div class="contenu">
          <div class="contenu_header_fond">
             <img src="./skin/original/design/histoire.png" alt="histoire" class="histoire"/>
          </div>
          <div class="contenu_fond_centre" align="left">
            <br />';
            include("include/screenshot.php");
            if(!isset($_GET['step'])){
             $html .= '<div class="histoire2">';
             $html .=$screen[1]; 
             $html .= '</div>';
             
               $html.=  ''.ahref('<img id="menu_haut_onglet" src="./skin/original/design/suivant.png" alt="Suivant" class="lien_suivant"/>','data.login.php?div=contenu&action=screenshot&step=2',"contenu").'';
            }else{

               $html .= '<div class="histoire2">';
               $html .=$screen[$_GET['step']]; 
               $html .= '<div class="liens_histoire">';
               if($_GET['step'] < count($screen)){
                  $html.=  ''.ahref('<img id="menu_haut_onglet" src="./skin/original/design/suivant.png" class="lien_suivant" alt="Suivant"/>','data.login.php?div=contenu&action=screenshot&step='.($_GET['step']+1),"contenu").'';
              }
               if($_GET['step'] > 1){
               $html.=  ''.ahref('<img id="menu_haut_onglet" src="./skin/original/design/precedent.png" alt="Précédent" class="lien_precedent" />','data.login.php?div=contenu&action=screenshot&step='.($_GET['step']-1),"contenu").'';
               }
               $html .= '</div>';
               $html .= '</div><br /><br />'; 
            }
            
           $html .='

      </div>

';
    echo $html;
    }
  }
}
else
{
//echo 'Actualiser la page pour continuer';
}
