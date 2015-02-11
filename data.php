<?php
error_reporting(E_ALL);
 ini_set("display_errors", 1);
session_start();

//echo ' $_SESSION ';
/*
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/
//setcookie("login","",0,"/","",0);
//setcookie("mdp","",0,"/","",0);

//phpinfo();        
//usleep(0200000);

$cc=$_SESSION['position'][1];

if(isset($_GET['logOut'])){
        session_destroy();
        setcookie("login","",0,"/","",0);
        setcookie("mdp","",0,"/","",0);
        //setcookie("login");
        //setcookie("mdp");
        $_SESSION['jid'] = 0;   
}
//print_r($_COOKIE);
function microtime_float(){
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();

$time_s = microtime_float();
require_once("include.php");
$time_e = microtime_float();

$timeinclude = $time_e-$time_s;


//Joueur::changerAlliance(2,2,"moimomo","momo");

if(isset($_GET['aide']) AND $_GET['aide']==1){
  require_once("include/aide.php"); 
  exit();
}



$staticPartie = new Partie();

//print_r($_GET);
if(isset($_GET['action']) && $_GET['action']=='activation'){
  require_once("include/activation.php");
}



if(isset($_POST['action'])){ // action généré par le JS
  if($_POST['action']=="login"){
    $id=0;
	$joueur = new Joueur();
    if(isset($_POST['login']) AND $_POST['login'] != "" AND isset($_POST['mdp']) AND $_POST['mdp'] != ""){
      if($_POST['mdp'] == "autopassword")
        $id = $joueur->login($_POST['login'],$_COOKIE['mdp'],1);
      else
        $id = $joueur->login($_POST['login'],$_POST['mdp'],0);
    }elseif(isset($_COOKIE['login']) AND $_COOKIE['login'] != "" AND isset($_COOKIE['mdp']) AND $_COOKIE['mdp'] != ""){
      $id = $joueur->login($_COOKIE['login'],$_COOKIE['mdp'],1);     
    }
    if($id != 0){
      //if(!isset($_COOKIE['login'])){
        setcookie("login",$_POST['login'],time()+TIME_COOKIE);
        setcookie("mdp",md5($_POST['mdp']),time()+TIME_COOKIE);
      //}
      //$_SESSION['jid']=$id;
      echo 'Login OK..........';

    }else{
      echo 'Identification Impossible...';
    }
  }
  
  
  
  if($_POST['action']=="inscription"){
    if(isset($_POST['login']) && ($_POST['login']) != "" && isset($_POST['mdp']) && ($_POST['mdp']) != "" && isset($_POST['mail']) && ($_POST['mail']) != "")
    {
      if(nomValide($_POST['login'])){
        if(emailValide($_POST['mail'])){
		  $joueurLogin = new Joueur();
          if($joueurLogin->testLogin($_POST['login'],$_POST['mail'])){
			//testLogin = true;
			echo 'testLogin = true';
			$joueur = new Joueur();
            $joueur->init($_POST['login'],$_POST['mdp'],$_POST['mail']);
            $idJ = $joueur->insert();
            $key = md5($joueur->login.'_'.$joueur->mdp);

			echo 'ok';
          }
          else
            echo 'Login ou mail déjà utilisé.';
        }
        else
          echo 'L\'adresse email est invalide.';
      }
      else
        echo 'Nom de code invalide.<br />Seul les caratères alpha-numériques _ et espace sont autorisés <br />(3 minimum 15 maximum).';
    }
    else
    {
      echo 'Un des champs n\'est pas rempli.';
    }
  }
  if($_POST['action']=="PasserDicactitiel"){
    $joueur = new Joueur();
    $joueur->loadSimple($_SESSION['jid']); 

    
    if($joueur->etat==1)//c'est juste apres l'inscription donc on lui balance une partie debut
    {
      $joueur->etat=2;
      $joueur->save();
    }
    
    echo 'Lancement...';
    exit();
  }
    
  
  if($_POST['action']=="lancePartie"){
    $joueur = new Joueur();
    $joueur->loadSimple($_SESSION['jid']); 
    $joueur->lancerPartie($_POST['partie'],$_POST['hero'],$_POST['ville']);
    $joueur->save();
    exit();
  }
  
  if($_POST['action']=="NewHero"){
  
    if(!isset($_POST['nom']) || ! nomValide($_POST['nom']))
       echo 'Seul les caratères alpha-numériques le _ et l\'espace sont autorisé (15 maximum).';
    else {
  
      //test si resources suffisantes
      //test si on a le droit de faire un nouveau hero
      
      //création du héro
      $j = new Joueur();
      $j->loadSimple($_SESSION['jid']);
      $nbHero =count($j->idHero) ;
          if($nbHero>9)
            echo 'Vous avez le nombre maximum d\'héro';
          else
          {
            if($staticPartie->heroPresent($j->partie,$_POST['nom']))
              echo 'Nom du héro déjà utilisée.';
            else
            {
              $count = count($j->idHero)+count($j->idVille);
              if(coutCreationHero($count,'ors') > $j->ors || coutCreationHero($count,'bois') > $j->bois )
                echo 'Vous n\'avez pas assez de resources.';
              else
              {
            
                $ville=new Ville;
                $ville->load($cc);
            
                //$j->save();
                
                $idHero = Hero::insert($j->id,$j->alliance,$_POST['nom'],$ville->map);
                $hero = new Hero();
                $hero->load($idHero);
                $hero->init($ville->X,$ville->Y);
                $hero->save();
                
            
                $j->ajoutIdHero($idHero,$_POST['nom']);
                $j->bois -= coutCreationHero($count,'ors');
            		$j->ors -= coutCreationHero($count,'bois');
                $j->save();
              
                echo 'Le héro a été recruté.';
              }
            }
          }
    }
  }
  
     
  if($_POST['action']=="NewVille"){
  
    if(!isset($_POST['nom']) || ! nomValide($_POST['nom']) || !isset($_POST['X']) || !isset($_POST['Y']) ){
       echo 'Seul les caratères alpha-numériques le _ et l\'espace sont autorisé (15 maximum).';
    }else {
  
      //test si resources suffisantes
      //test si on a le droit de faire une nouvelle ville
      
      //création de la ville
      $j = new Joueur();
      $j->loadSimple($_SESSION['jid']);
      $nbVille =count($j->idVille) ;
          if($nbVille>9)
            echo 'Vous avez le nombre maximum de ville';
          else
          {
            if($staticPartie->villePresent($j->partie,$_POST['nom']))
              echo 'Nom de la ville déjà utilisée.';
            else
            {
              $case=new CaseObject($_POST['X'],$_POST['Y'],prefixMapPartie($j->typePartie).$_SESSION['partie']);
              if($case->mine == 1 && count($case->renvoyerIdHeros()) > 0 && $case->heroSurCase($_POST['h']) && count($case->ville) == 0)//un hero a nous sur la case et c'est une mine
                {

                  $hero = new Hero();
                  $hero->load($_POST['h']);
                  $idVille = Ville::insert($_SESSION['partie'],prefixMapPartie($j->typePartie).$_SESSION['partie'],$j->id,$j->alliance,$_POST['nom']);

                  $ville=new Ville;
                  $ville->load($idVille);
                  $ville->implantation($_SESSION['partie'],$_POST['X'],$_POST['Y']);
                  $ville->garnison = $hero->garnison;
                  $ville->save();

                  $j->ajoutIdVille($idVille,$_POST['nom']);
              		$j->supprIdHero($id);
              		$j->maxRess();
                  $j->save();
                  
                  $ally = new Alliance();
                  $ally->load($j->alliance);
                  
                  $case->mine=0;//enlever le mine de la case!
                  $case->ajouterVille($idVille,$j->login,$j->id,$ally->nom,$j->alliance,$_POST['nom']);
                  $case->enleverHero($_POST['h']);
                  $case->save();
                  
                  $hero->delet();                  
                  unset($hero);
                
                  echo 'Ville implantée.';
                }
                else{
                  echo 'Cette case n\'est pas valide';
                }
            }
          }
    }
  }
  
  if($_POST['action'] == "envoiMessage"){ 
    envoyer_message($_SESSION['jid'],$_POST['destinataire'],$_POST['sujet'],$_POST['contenu']);
    //echo 'Message envoyé';
  }
  
  if($_POST['action'] == "changevillename"){
    if(nomValide($_POST['name'])){
          $j= new Joueur();
          $j->load($_SESSION['jid']);
          
            foreach($j->idVille as $key => $value){
              if($value['id'] == $j->ville[$cc]->id){
                $j->idVille[$key]['nom'] = htmlentities($_POST['name']);
              }
            }
  
        $j->ville[$cc]->nom = htmlentities($_POST['name']);
        $j->ville[$cc]->save();
        $j->save();
        
        $case = new CaseObject($j->ville[$cc]->X,$j->ville[$cc]->Y,$j->ville[$cc]->map);
        $case->ville[$j->ville[$cc]->id]['nom'] = htmlentities($_POST['name']);
        $case->save();
        echo 'Changement Effectué';
        exit();
      }else{
        echo 'Nom invalide';
      }
  }
  
  if($_POST['action'] == "changeheroname"){
    if(nomValide($_POST['name'])){
          $j= new Joueur();
          $j->load($_SESSION['jid']);
          $j->hero[$cc] = new Hero();
          $j->hero[$cc]->load($cc);
            foreach($j->idHero as $key => $value){
              if($value['id'] == $j->hero[$cc]->id){
                $j->idHero[$key]['nom'] = htmlentities($_POST['name']);
              }
            }
  
        $j->hero[$cc]->nom = htmlentities($_POST['name']);
        //$j->hero[$cc]->save();
        $j->hero[$cc]->save();
        $j->save();
        
        $case = new CaseObject($j->hero[$cc]->X,$j->hero[$cc]->Y,$j->hero[$cc]->map);
        $case->hero[$j->hero[$cc]->id]['nom'] = htmlentities($_POST['name']);
        $case->save();
        echo 'Changement Effectué';
        exit();
      }else{
        echo 'Nom invalide';
      }
  }

  if($_POST['action'] == "supprMessage"){ 
      if (isset($_POST['messadel'])){
        $ids = explode(",", $_POST['messadel']);
        foreach ($ids as $choix)
      		{
      		  if($choix != 0)
            {
      		    $GLOBALS["db"]->query("DELETE FROM `e_message` WHERE `e_message`.`id` = $choix ;")or die(mysql_error());
            }
          }
      }
  }
  
  if($_POST['action'] == "envoiEspion"){ 
      if (isset($_POST['Xspy']) AND isset($_POST['Yspy'])){
        //echo $_POST['Xspy'].$_POST['Yspy'];
        $j= new Joueur();
        $j->load($_SESSION['jid']);
        $case = new CaseObject($_POST['Xspy'],$_POST['Yspy'],$j->ville[$cc]->map);
        if($case->map != ""){
          $j->ville[$cc]->tourContent->envoyerEspion($_POST['Xspy'],$_POST['Yspy'],$j->ville[$cc]->map,$j->ville[$cc]->X,$j->ville[$cc]->Y);
          $j->ville[$cc]->save();
        }else{
          echo 'Rien la bas !';
        }
        //print_r($j->ville[$_SESSION['position'][1]]->tourContent);
      }
  }


}//fin des post action


if(isset($_GET['choix']) AND $_GET['choix'] == "bar"){
             ?>
             <div class="contenu">
                <div class="contenu_header_fond">
                <div class="bar">
                
                <?php
                $high = "red";
                if(!isset($_GET['room']))
                echo '';
                
                echo ahref('<font color="'.$high.'</font>">General','data.php?div=contenu&choix=bar',"contenu");
                if(!isset($_GET['room']))
                echo '';
                
                if(($_GET['room'])=='alliance')
                echo '<font color="'.$high.'">';   
                             
                echo ahref('Alliance','data.php?div=contenu&choix=bar&room=alliance',"contenu");
                if(($_GET['room'])=='alliance')
                echo '</font>';
                
                if(($_GET['room'])=='partie')
                echo '<strong>';    
                echo ahref('Partie','data.php?div=contenu&choix=bar&room=partie',"contenu");
                if(($_GET['room'])=='partie')
                echo '</strong>';    
                ?>
                </div>
                </div>
              
            	<div class="contenu_fond_centre">
            	<?php
               if(isset($_GET['room'])){
                  $link = "./AJAX/index.php?room=".$_GET['room'];
               }else{
                  $link = "./AJAX/index.php";
               }
              ?>
                
                <iframe src="<?php echo $link; ?>" width="449" height="500" name="myFrame" id="myFrame"></iframe>
                
              </div>
              
            </div>
        <?php
        exit;
        }

if(isset($_SESSION['jid']) AND $_SESSION['jid'] != 0 AND isset($_SESSION['partie']) AND $_SESSION['partie'] != 0)
{


/*
$j = new Joueur();
$j->loadSimple($_SESSION['jid']);
echo '<pre>';
var_dump($j);
echo '</pre>';
*/
/*
  $s = new Stat();
  $s->load(60);
  $s->finPartie(11);
  $s->save();
*/
//print_r(get_defined_constants());
//print_r($_SERVER);
//echo $_SERVER['DOCUMENT_ROOT'] ;


//  $p=new Partie();
//  $p->load($_SESSION['partie']);
//  $p->fini();
//  $p->save();
//echo ' fichier : '.__FILE__.' ligne : '.__LINE__ ;

        $_SESSION['timer']=1;
        $j = new Joueur();
        $h = 0;
        $v = 0;
        
        if(isset($_GET['v'])){
            $v = $_GET['v'];
        }elseif(isset($_SESSION['position']) AND $_SESSION['position'][0] == "v"){
            $v = $_SESSION['position'][1];
        }
        
        if(isset($_GET['h'])){
            $h = $_GET['h'];
        }elseif(isset($_SESSION['position']) AND $_SESSION['position'][0] == "h"){
            $h = $_SESSION['position'][1];
        }
        
        
        
        
        if($v ==0 AND $h ==0 ){
            
        }
        //echo $v.' '.$h.'';
        $j->load($_SESSION['jid'],$v,$h);
        

        
        $_SESSION['login']= $j->login;
        if(isset($_GET['action']) AND $_GET['action'] == "quitterPartie"){
        

                  	   $escuse = "";
                       if($j->partie > 0){
                         //netoyage des villes
                         //print_r($this->idVille);
                         if(is_array($j->idVille) AND count($j->idVille) > 0 ){
                         foreach($j->idVille as $key => $value){
                            $ville = new Ville();
                            $ville->load($value['id']);
                            $case = new CaseObject($ville->X,$ville->Y,$ville->map);
                            if(count($case->hero) >0){
                              foreach($case->hero as $key => $value){
                                if($value['etat'] == 4){
                                  $escuse = 'Vous êtes attaqué !';
                                }
                              }
                            }
                            
                            
                         }
                        }
                         
                        if(is_array($j->idHero) AND count($j->idHero) > 0 ){ // GROS BUG is_array($j->idHero) AND count($j->idVille) > 0
                         foreach($j->idHero as $key => $value){
                            $hero = new Hero();
                            $hero->load($value['id']);
                            
                            if($hero->etat != 1){
                              $escuse = 'Un de vos héros est en déplacement !';
                            }
                            $case = new CaseObject($hero->X,$hero->Y,$hero->map);
                            if(count($case->hero) >0){
                              foreach($case->hero as $key => $value){
                                if($value['etat'] == 4){
                                  $escuse = 'Vous êtes attaqué !';
                                }
                              }
                            }
                         }
                        }
                         
                         if($escuse == ""){ // Pas d'excuse on peut délet 
                            $staticPartie->finPartie($j->id,$j->partie,'gagne');
                            /*
                            $j->partie = 0;
                            $j->etatPartie = "jeu";
                            $j->save();
                   		      */
                            $_SESSION['partie']=0;
                        		$_SESSION['typePartie'] = '';
                        	  echo 'Lancement...';
                        		exit();
                         
                         }else{
                          echo $escuse;
                         }
                         //print_r($this->idHero);
                       }

        
        
        }
        



    if($j->etatPartie != 'jeu')// la partie actuelle est finie
    {

      if(isset($_GET['p'] ) && $_GET['p'] == "finir")//on valide la fin de partie
      {

        $j->partie = 0;
        //$j->etatPartie = 'jeu';
        
        //$_SESSION['partie'] = 0;
        $_SESSION['typePartie'] = '';
        if ($j->etat == 1 || $j->etat == 2 || $j->etat == 3)
        {
            $j->lancerPartie(0,'Mon héro','Ma ville');
            $j->save();    
        }
        else
        {
            echo 'Lancement...';
            $j->save();             
        }
        exit();
      }
      
      if($_GET['div'] == "menu_haut")
      	echo $GLOBALS['MenuHautCompte'];
      if($_GET['div'] == "menu_gauche")
      	echo ' ';
      if($_GET['div'] == "contenu")
        //$p=new Partie();
        //$p->load($j->partie);
        $staticPartie->afficheFinPartie($j->id,$j->partie,$j->etatPartie);
    }
    else
    {
    if(isset($_POST['action'])){
        if($_POST['action']== "changepass"){
                echo $j->changementMotDePasse($_POST['oldPass'],$_POST['newPass']);
                
        }
        if($_POST['action']== "changemail"){
                $j->email = $_POST['newMail'];
                $j->save();
        }
        
        if($_POST['action'] == "changePaysans"){
            if($_SESSION['position'][0] == "v"){
                $_SESSION['AugAJour'] = 0;
                switch($_POST['sens']){
                        case 0: // Rajout de mineur
                           
                            if(isset($_POST['n']) && $_POST['n']==10){
                              if($j->ville[$cc]->paysans>=10){
                                $j->ville[$cc]->paysans-=10;
                                $j->ville[$cc]->mineur+=10;
                                
                              }
                            }elseif($j->ville[$cc]->paysans>0){
                                    
                                    $j->ville[$cc]->paysans--;
                                    $j->ville[$cc]->mineur++;
                            }
                            break;
                        case 1: // Enlever mineur
                            if(isset($_POST['n']) && $_POST['n']==10){
                              if($j->ville[$cc]->mineur>=10){
                                $j->ville[$cc]->mineur-=10;
                                $j->ville[$cc]->paysans+=10;
                              }
                            }elseif($j->ville[$cc]->mineur>0){
                                    $j->ville[$cc]->paysans++;
                                    $j->ville[$cc]->mineur--;
                            }
                            break;
                        case 2: // Rajout de bucheron 
                            if(isset($_POST['n']) && $_POST['n']==10){
                              if($j->ville[$cc]->paysans>=10){
                                $j->ville[$cc]->paysans-=10;
                                $j->ville[$cc]->bucheron+=10;
                              }
                            }elseif($j->ville[$cc]->paysans>0){
                                    $j->ville[$cc]->paysans--;
                                    $j->ville[$cc]->bucheron++;
                            }
                            break;
                        case 3: // Enlever bucheron
                            if(isset($_POST['n']) && $_POST['n']==10){
                              if($j->ville[$cc]->bucheron>=10){
                                $j->ville[$cc]->paysans+=10;
                                $j->ville[$cc]->bucheron-=10;
                              }
                            }elseif($j->ville[$cc]->bucheron>0){
                                    $j->ville[$cc]->paysans++;
                                    $j->ville[$cc]->bucheron--;
                            }
                            break;
                }
                $bucheron_max = $j->ville[$cc]->scierie*NB_PAYSANS_PA_NIV+10;
                $mineur_max = $j->ville[$cc]->mine*NB_PAYSANS_PA_NIV+10;

                if($j->ville[$cc]->bucheron > $bucheron_max){
                  $bucheron_trop = $j->ville[$cc]->bucheron - $bucheron_max;
                  $j->ville[$cc]->bucheron = $bucheron_max;
                  $j->ville[$cc]->paysans += $bucheron_trop;
                }

                if($j->ville[$cc]->mineur > $mineur_max){
                  $mineur_trop = $j->ville[$cc]->mineur - $mineur_max;
                  $j->ville[$cc]->mineur = $mineur_max;
                  $j->ville[$cc]->paysans += $mineur_trop;
                }


                 echo '##########';
                                print_r($j->ville[$cc]);
                                echo '##########';
                
                $j->ville[$cc]->save();
                echo '<table >
                        <tr><td width="160">Paysans disponible : </td><td colspan="2" align="left"><div id="totalPaysans">'.$j->ville[$cc]->paysans.'</div></td></tr>
                        <tr><td width="160">Paysans dans la mine :  </td><td>'.$j->ville[$cc]->mineur.' / '.($j->ville[$cc]->mine*NB_PAYSANS_PA_NIV).' </td><td>&nbsp;<button class="plus10" onClick="changePaysans(0,10)"></button><button class="plus" onClick="changePaysans(0,1)"></button>&nbsp;<button class="moins" onClick="changePaysans(1,1)"></button>&nbsp;<button class="moins10" onClick="changePaysans(1,10)"></button></td></tr>
                        <tr><td width="160">Paysans à la scierie :  </td><td>'.$j->ville[$cc]->bucheron.' / '.($j->ville[$cc]->scierie*NB_PAYSANS_PA_NIV).'</td><td>&nbsp;<button class="plus10" onClick="changePaysans(2,10)"></button><button class="plus" onClick="changePaysans(2,1)"></button>&nbsp;<button class="moins" onClick="changePaysans(3,1)"></button>&nbsp;<button class="moins10" onClick="changePaysans(3,10)"></button></td></tr>
                      </table>';     
            }
        }
        
        if($_POST['action'] == "changeCaracs"){
                if($_SESSION['position'][0] == "h"){
                        if($j->hero[$cc]->garnison->pCaracs>0){
                            switch($_POST['sens']){
                                    case 0: // Attaque 
                                                    $j->hero[$cc]->garnison->caracs['att']++;
                                                    $j->hero[$cc]->garnison->pCaracs--;
                                            break;
                                    case 1: // Defense
                                                    $j->hero[$cc]->garnison->caracs['def']++;
                                                    $j->hero[$cc]->garnison->pCaracs--;
                                            break;
                                    case 2: // Vitesse
                                                    $j->hero[$cc]->garnison->caracs['vit']++;
                                                    $j->hero[$cc]->garnison->pCaracs--;
                                            break;
                                    case 3: // Motivation
                                                    $j->hero[$cc]->garnison->caracs['mot']++;
                                                    $j->hero[$cc]->garnison->pCaracs--;
                                            break;
                            }
                          $j->hero[$cc]->save();
                        }
                        echo $j->hero[$cc]->garnison->AfficherInventaire(1);

                }              
        }
        
         if($_POST['action'] == "recrutePaysans"){
                if($_SESSION['position'][0] == "v"){
                        $ville = new Ville();
                        $ville->load($cc);
                        $recrut = $j->recruterPaysans($_POST['nb']);
                        $ville->paysans+= $recrut;
                        $ville->save();
                        echo $ville->paysans;
                }
         }
         if($_POST['action'] == "recruteUnite"){
         		if($_SESSION['position'][0] == "v"){
            		$ville = new Ville();
            		$ville->load($cc);
            		$nbcree = $j->recruterUnite($_POST['type'],$_POST['nb'],$ville);
            		//$_SESSION['lol'][] = $nbcree;
            		$ville->entrainement->ajouterGroupe($_POST['type'],$nbcree,$ville->caserne);
            		$ville->save();
            		//$_SESSION['lol'][] = 'lol';
            		//$action = new Action();
            		//$action->newAction("entrainement",$ville->id,0,time());         		
            }
         }
         
          if($_POST['action'] == "depUnite"){
              //faire les verif des ids
              
              for($i=0;$i<count($GLOBALS['unite']);$i++){
                if($_POST['v'.$i] != NULL)
                  $uVille[$i]=abs(ceil($_POST['v'.$i]));
                else
                  $uVille[$i]=0;
                if ($_POST['h'.$i] != NULL)
                  
                  $uHero[$i]=abs(ceil($_POST['h'.$i]));
                else
                  $uHero[$i]=0;
              }
              //echo ' eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeé';
              //print_r($uVille);
              //print_r($uHero);
              //echo $_POST['idHero'];

              echo Garnison::deplacerUnite($cc,$_POST['idHero'],$_POST['sens'],$uVille,$uHero);
          }
         
    exit();
    }
        if(isset($_GET['action'])){
          if($_GET['action'] == "move"){
            $_SESSION['move_x'];
            $_SESSION['move_y'];
          }
          //print_r($_SESSION);
          if($_GET['action'] == "uprecherche"){
            if(isset($_GET['rech'])){

              
              $j->recherche->loadVille($j->ville[$cc]);
              //print_r($j->ville[$cc]);
              //echo $j->recherche->Faisable($_GET['rech']);
              if($j->recherche->Faisable($_GET['rech']) == 0 AND $j->ville[$cc]->recherche > 0){ // On peut faire
                //print_r($j->recherche->termine);
                //echo $j->recherche->Ameliorer($_GET['rech']);
                $ress = coutRech($_GET['rech'],$j->recherche->Ameliorer($_GET['rech']));
                //print_r($ress);
                if($j->ors >= $ress['ors'] AND $j->bois >= $ress['bois'] AND $j->recherche->finishtime == 0){
                  //echo '?';
                  $j->ors -= $ress['ors'];
                  $j->bois -= $ress['bois'];

                  $j->recherche->lancerAmelioration($_GET['rech'],$ress['temps']);
                  $j->save();
                }
                
              }else{
                echo 'Bien tenté';
              }
            }
          }
          
          if($_GET['action'] == "aide"){
            echo 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj';
            require_once("./include/aide.php");
          }
        }

        
        if($_GET['div'] == "menu_gauche"){
                echo $j->afficherMenuGauche($_GET);
        }
        
        if($_GET['div'] == "contenu"){
           /* 
	    echo '<pre>';
	    print_r(apc_cache_info());
	    echo '</pre>';
*/
		if(!isset($_GET['choix'])){$_GET['choix']="";}
		
	    switch ($_GET['choix']){
              case 'alliance':
                require_once("alliance.php");
              
              break;
              case 'messagerie':
                require_once("./include/message.php");
              
              break;
              case 'stat':
                //require_once("./include/statistiques.php");
              
              break;    
              
          
              
              default :
                //require_once("core.php"); // NE PAS LANCER !!!
                echo $j->afficheContenu($_GET);

              
              break;
             } 

                $time_end = microtime_float();
                $time = round($time_end - $time_start,3)*1000;     
                //$time = $time_end - $time_start;           
                echo '<div id="footer" style="text-align:left;background-image:none;z-index:50;">GenTime : '.$time.'ms  Détail (Inc :'.round($timeinclude*1000,2).'ms  DB :'.round(($GLOBALS['db']->dbTime)*1000,2).'ms  Req :'.$GLOBALS['db']->nbQueries.' )  </div> ';
                echo '<div id="paroles">';
                
		
                if(mt_rand(0,10) != 1){
                echo $GLOBALS['phrase'][mt_rand(0,count($GLOBALS['phrase'])-1)];
                }else{
                echo phraseCon();
                }
                
                echo '</div>';
                //echo '<div id="help"><a href="#" id="LienDaide" onclick="return hs.htmlExpand(this, { objectType: \'ajax\', preserveContent: true, src:\'./include/aide.php\', width: 306, height: 58 } );" title="Besoin d\'aide ?">?</a></div>';// aideLoad(\'data.php?div=contenu\');
                echo '<div id="help"><a href="#" id="LienDaide" onclick="hs.htmlExpand(this, { contentId: \'ajax\', preserveContent: true, width: 250, height: 400 ,marginRight: 10, marginTop: 50} ); aideLoad(\'data.php?div=contenu\');" title="Besoin d\'aide ?">?</a></div>';// aideLoad(\'data.php?div=contenu\');
 
 /*
foreach($GLOBALS['db']->query_list as $value){
  echo $value;
}*/


                echo '<div id="alert" class="alert"></div>';
                
                
                

  
        }

  }//else de la partie
}else{
  
  if(!isset($_SESSION['partie']) )
  {
	   require_once("data.login.php");//inscription ...
	}
	else 
	{
		  require_once("data.compte.php");//choix d'unr partie ...
	}
}

?>
