<?php
class Joueur{
    var $id;
    var $login;
    var $mdp;
    var $email;
    var $skin;
    var $etat;//0=> inscrit
        //1=> mail_vérifié et lancement d'une partie noob
        //2=> lancement d'une partie rush
        //3=> lancement d'une partie prise
        //4=> accès a toutes les parties
    var $points;
    var $date_inscription;
    var $date_dern_action;
    var $description;
    var $age;
    var $sexe;
    var $alliance;//aliance dans la partie
    var $guilde;//alliance globale
    var $avatar;
    var $idVille=array();
    var $idHero=array();
    var $bois;
    var $ors;
    var $ville;//info de la ville en cours (si sur une ville)
    var $hero;//info de le hero en cours (si sur un hero)
    var $typePartie;
    var $partie;
    var $etatPartie;
    var $vitesse;
    var $recherche;

    var $orsMax;
    var $boisMax;

    var $augOrsParMinute;
    var $augBoisParMinute;

    function load($id,$ville = 0,$hero = 0,$creation=false){//$creation doit être true si ville et hero =0

            if($id>0){
                $sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE id = '.$id);
                $donnees = mysql_fetch_array($sql);
            }

            if($donnees['partie'] != 0 ){
                $sql = $GLOBALS["db"]->query('SELECT * FROM j_partie WHERE id = '.$donnees['partie']);
                $donneesPartie = mysql_fetch_array($sql);
            }

            $this->id = $donnees['id'];
            $this->login = $donnees['login'];
            $this->mdp = $donnees['mdp'];
            $this->email = $donnees['email'];
            $this->skin = $donnees['skin'];
            $this->etat = $donnees['etat'];
            $this->points = $donnees['points'];
            $this->date_inscription = $donnees['date_inscription'];
            $this->date_dern_action = $donnees['date_dern_action'];
            $this->description = $donnees['description'];
            $this->age = $donnees['age'];
            $this->sexe = $donnees['sexe'];
            $this->alliance = $donnees['alliance'];
            $this->guilde = $donnees['guilde'];
            $this->avatar = $donnees['avatar'];
            $this->idVille = unserialize($donnees['idVille']);
            $this->idHero = unserialize($donnees['idHero']);
            $this->bois = $donnees['bois'];
            $this->ors = $donnees['ors'];
            $this->boisMax = $donnees['boisMax'];
            $this->orsMax = $donnees['orsMax'];
            $this->augOrsParMinute = $donnees['augOrsParMinute'];
            $this->augBoisParMinute = $donnees['augBoisParMinute'];
            $this->position = $donnees['position'];
            $this->partie = $donnees['partie'];
            $this->etatPartie = $donnees['etatPartie'];
            $this->recherche = unserialize($donnees['recherche']);

            if(is_object($this->recherche)){
                    $this->recherche->verifRecherche();
            }else{
                        $this->recherche = new Technologie();
            }

            $this->typePartie = $donneesPartie['type'];
            $this->vitesse = $donneesPartie['vitesse'];

            if(!isset($_SESSION['tuto']) AND $this->typePartie="debut"){
                    $this->tuto = $donnees['tuto'];
                    $_SESSION['tuto'] = $this->tuto;
            }

            $GLOBALS['recherche'] = $this->recherche;

            $GLOBALS['skin'] = $this->skin;
            $GLOBALS['ors'] = $this->ors;
            $GLOBALS['bois'] = $this->bois;
            $GLOBALS['idjoueur'] = $this->id;
            $GLOBALS['alliance'] = $this->alliance;
            $GLOBALS['typePartie'] = $this->typePartie;
            $GLOBALS['partie'] = $this->partie;
            $GLOBALS['vitesse'] = $this->vitesse;
            $GLOBALS['position'] = $this->position;

            $_SESSION['login'] = $this->login;
            $_SESSION['typePartie'] = $this->typePartie;
            $_SESSION['etat'] = $this->etat;


        if(! $creation){ //se n'est pas la création du compte
                    

        if($ville){
            if(is_array($this->idVille)){
                foreach($this->idVille as $key => $value){
                    if($value['id'] == $ville){
                        $this->ville[$value['id']] = new Ville();
                        $this->ville[$value['id']]->load($value['id']);

                        if($this->ville[$value['id']]->nom == ""){
                            //echo $value['id'];
                            $this->deleteIdVille($value['id']);
                            $this->save();
                            echo 'Lancement...';
                            exit();
                        }
                    }
                }
            }
         }
        if($hero){
            if(is_array($this->idHero)){
                foreach($this->idHero as $key => $value){
                    if($value['id'] == $hero){
                        $this->hero[$value['id']] = new Hero();
                        $this->hero[$value['id']]->load($value['id']);

                        if($this->hero[$value['id']]->nom == ""){
                            //echo $value['id'];
                            $this->deleteIdHero($value['id']);
                            $this->save();
                            echo 'Lancement...';
                            exit();
                        }
                    }
                }
            }
        }

        if(!isset($_GET['v']) AND !isset($_GET['h']) AND !is_object($this->hero[$hero])){
			$uneville=0;
		    if(is_array($this->idVille)){
					foreach($this->idVille as $key => $value){
						if($uneville==0){
							$uneville=1;
							$this->ville[$value['id']] = new Ville();
							$this->ville[$value['id']]->load($value['id']);
							$_GET['v'] = $value['id'];
						}
					}
				}
			}
        }
        //$this->maxRess();
    }

    function loadSimple($id){//chargement sans hero ni ville	
            //$sql = $GLOBALS["db"]->query('SELECT j_compte.*,j_partie.vitesse,j_partie.type FROM j_compte,j_partie WHERE j_compte.id = '.$id.' AND j_compte.partie = j_partie.id');
            if($id>0 AND $id != ""){
            $sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE id = '.$id);
            $donnees = mysql_fetch_array($sql);
            }
            if($donnees['partie'] != 0 ){
            $sql = $GLOBALS["db"]->query('SELECT * FROM j_partie WHERE id = '.$donnees['partie']);
            $donneesPartie = mysql_fetch_array($sql);
    }
            $this->id = $donnees['id'];
            $this->login = $donnees['login'];
            $this->mdp = $donnees['mdp'];
            $this->email = $donnees['email'];
            $this->skin = $donnees['skin'];
            $this->etat = $donnees['etat'];
            $this->points = $donnees['points'];
            $this->date_inscription = $donnees['date_inscription'];
            $this->date_dern_action = $donnees['date_dern_action'];
            $this->description = $donnees['description'];
            $this->age = $donnees['age'];
            $this->sexe = $donnees['sexe'];
            $this->alliance = $donnees['alliance'];
            $this->guilde = $donnees['guilde'];
            $this->avatar = $donnees['avatar'];
            $this->idVille = unserialize($donnees['idVille']);
            $this->idHero = unserialize($donnees['idHero']);
            $this->bois = $donnees['bois'];
            $this->ors = $donnees['ors'];
            $this->boisMax = $donnees['boisMax'];
            $this->orsMax = $donnees['orsMax'];
            $this->augOrsParMinute = $donnees['augOrsParMinute'];
            $this->augBoisParMinute = $donnees['augBoisParMinute'];
            $this->position = $donnees['position'];
            $this->partie = $donnees['partie'];
            $this->etatPartie = $donnees['etatPartie'];

            $this->tuto = unserialize($donnees['tuto']);

            $this->typePartie = $donneesPartie['type'];
            $this->vitesse = $donneesPartie['vitesse'];

            $GLOBALS['skin'] = $this->skin;
            $GLOBALS['ors'] = $this->ors;
            $GLOBALS['bois'] = $this->bois;
            $GLOBALS['idjoueur'] = $this->id;
            $GLOBALS['alliance'] = $this->alliance;
            $GLOBALS['typePartie'] = $this->typePartie;
            $GLOBALS['partie'] = $this->partie;
            $GLOBALS['vitesse'] = $this->vitesse;

            $_SESSION['typePartie'] = $this->typePartie;
            $_SESSION['partie'] = $this->partie;
            $_SESSION['alliance'] = $this->alliance;
            $_SESSION['etat'] = $this->etat;
    }

    function insert(){
            $a= array();
            $sql = "INSERT INTO j_compte(id,login,mdp,email,skin,date_inscription,date_dern_action,idVille,idHero,etatPartie)
                                                      VALUES('','".$this->login."','".$this->mdp."','".$this->email."','".$this->skin."','".$this->date_inscription."','".$this->date_dern_action."','".serialize($a)."','".serialize($a)."','jeu')";

            echo '#'.$sql.'#';
            $GLOBALS['db']->query($sql);
            $cpt=$GLOBALS['db']->query("SELECT LAST_INSERT_ID() as nb FROM j_compte " );
            $compt = mysql_fetch_object($cpt);
            $Stat = new Stat();
            $Stat->insert($compt->nb);
            return $compt->nb;
    }

    function login($login,$mdp,$cookie){
     //echo $login.','.$mdp.','.$cookie;
       if($cookie == 0){
            $sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE login = "'.$login.'"');
            $donnees = mysql_fetch_array($sql);
            $return = 0;
            if(isset($donnees['mdp'])){
            if($donnees['mdp'] == $mdp){
              $sql = $GLOBALS["db"]->query('SELECT * FROM j_partie WHERE id = "'.$donnees['partie'].'"');
              $partie = mysql_fetch_array($sql);
        if($partie){
          $_SESSION['vitesse']= $partie['vitesse'];
                      //$_SESSION['partie']= $partie['id'];
              }else{
                      //$_SESSION['partie']= 0;
              }
              $return = $donnees['id'];
              $_SESSION['jid'] = $donnees['id'];
              $_SESSION['login'] = $donnees['login'];
              $_SESSION['skin'] = $donnees['skin'];
              $_SESSION['partie']=$donnees['partie'];


        $j=new Joueur();
        $j->loadSimple($return);
        $j->augRess();
        $j->save();
            }
            }
            return $return;
            }elseif($cookie == 1){
                $sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE login = "'.$login.'"');
            $donnees = mysql_fetch_array($sql);
            $return = 0;
            if(isset($donnees['mdp'])){
            if(md5($donnees['mdp']) == $mdp){
              $sql = $GLOBALS["db"]->query('SELECT * FROM j_partie WHERE id = "'.$donnees['partie'].'"');
              $partie = mysql_fetch_array($sql);
        if($partie){
          $_SESSION['vitesse']= $partie['vitesse'];
          //echo '<br />aaaaaaaaaaaaaaaaa<br />';
          //print_r($partie['id']);//////////////////
                      //$_SESSION['partie']= $partie['id'];
              }else{
                      //$_SESSION['partie']=0;
              }
              $return = $donnees['id'];
              $_SESSION['jid'] = $donnees['id'];
              $_SESSION['skin'] = $donnees['skin'];
              $_SESSION['partie']=$donnees['partie'];
        $j=new Joueur();
        $j->loadSimple($return);
        $j->augRess();
        $j->save();
            }
            }
            return $return;
            }
    }

    function testLogin($login,$mail){//retourn vraie si le login n'existe pas
            $sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE login = "'.$login.'" OR email ="'.$mail.'"');
            $donnees = mysql_fetch_array($sql);
            if(isset($donnees['mdp'])){
              return false;
            }
      return true;
    }

    function returnID($login){
            $sql = $GLOBALS["db"]->query('SELECT id FROM j_compte WHERE login = "'.$login.'"');
            $donnees = mysql_fetch_array($sql);
    if( ! is_null($donnees) )
    return $donnees['id'];
    return false;
    }

    function save(){

    $this->tuto = $_SESSION['tuto'];
    //unset($this->recherche->ville);
    if(isset($GLOBALS['position']) AND $GLOBALS['position'] != 0){
        $this->position = $GLOBALS['position'];
    }

            $sql = "UPDATE j_compte SET
            login = '".$this->login."',
            mdp = '".$this->mdp."',
            email = '".$this->email."',
            skin = '".$this->skin."',
            etat = '".$this->etat."',
            points = '".$this->points."',
            date_inscription = '".$this->date_inscription."',
            date_dern_action = '".$this->date_dern_action."',
            description = '".$this->description."',
            age = '".$this->age."',
            sexe = '".$this->sexe."',
            alliance = '".$this->alliance."',
            guilde = '".$this->guilde."',
            avatar = '".$this->avatar."',
            sexe = '".$this->sexe."',
            idVille = '".serialize($this->idVille)."',
            idHero = '".serialize($this->idHero)."',
            bois = '".$this->bois."',
            ors = '".$this->ors."',
            orsMax ='".$this->orsMax."',
            boisMax = '".$this->boisMax."',
            position = '".$this->position."',
            augOrsParMinute = '".$this->augOrsParMinute."',
            augBoisParMinute = '".$this->augBoisParMinute."',";

            if(is_object($this->recherche)){
              $sql.= "recherche = '".serialize($this->recherche)."',";
            }

            $sql.="

            partie = '".$this->partie."',
            etatPartie = '".$this->etatPartie."',
            tuto = '".$this->tuto."'

            WHERE `id` = '".$this->id."' ;";


            $GLOBALS['db']->query($sql);
    }

    function saveUnChamp($champ,$val,$id){
    if($id == NULL || !isset($id) || $id == 0 )
      $id = $this->id;
    if($id != 0 && $id != NULL && $champ != NULL  )
    {
            $sql =" UPDATE j_compte SET
             $champ = '".$val."' 
             WHERE `id` = '".$id."' ;";
      $GLOBALS['db']->query($sql);
    }
    else
      echo 'Erreur lors de la modification d\'un champ Joueur';
    }

    function init($login,$mdp,$mail){
            $this->login=$login;
            $this->mdp=$mdp;
            $this->email=$mail;
            $this->skin='./skin/original/';
            $this->date_inscription=time();
            $this->date_dern_action=time();
            //$this->bois = BOIS_DEPART;
            //$this->ors = ORS_DEPART;
    }

    function changementMotDePasse($oldPass,$newPass){
            if($this->mdp == $oldPass){
                    $this->mdp=$newPass;
                    $this->save();
                    return "Changement effectué.";
            }else{
                    return "Mauvais mot de passe.";
            }
    }

    function lancerPartie($postPartie,$postHero,$postVille){
    $staticPartie=new Partie();

    if($this->etat==1)//c'est juste apres l'inscription donc on lui balance une partie debut
    {
    //Réglace d'une partie debutant
    $date_debut=time();
    $vitesse=10;
    $tailleX=5;
    $tailleY=5;
    $type='debut';
    $nom='Initiation';
    $population_mine=0;//pourcentage
    //Création de la partie et de la carte
      $p=new Partie();
    $nPartie = $p->newPartie($date_debut,$vitesse,$tailleX,$tailleY,$type,$nom);

    $p->load($nPartie);
    $p->newMap(0);
    //$this->etat=2;
    $this->typePartie = 'debut';
    $this->vitesse = $p->vitesse; 
    $p->off = $this->demarrerPartie($nPartie,$type,'Ma ville','Mon héro');
    $p->save();
    //$hero->init($ville->X,$ville->Y);




    $_SESSION['partie']=$nPartie;
    $_SESSION['typePartie'] = $type;

    $out = 'Lancement...';

    }
    elseif($this->etat==2)//apres la partie debut on lui balance une partie rush
    {
    $idPartie = $staticPartie->dernierePartieRush();
    //type de partie
    $partie = new Partie();
    $partie->load($idPartie);
    $this->vitesse = $partie->vitesse;      
    $this->demarrerPartie($idPartie,$partie->type,$postVille,$postHero);
    $_SESSION['partie']=$idPartie;
    $_SESSION['typePartie'] = $partie->type;
    $out = 'Lancement...';
    }
    elseif($this->etat==3)//apres la partie rush on lui balance une partie prise
    {
    $idPartie = $staticPartie->dernierePartiePrise();
    //type de partie
    $partie = new Partie();
    $partie->load($idPartie);
    $this->vitesse = $partie->vitesse;      
    $this->demarrerPartie($idPartie,$partie->type,$postVille,$postHero);
    $_SESSION['partie']=$idPartie;
    $_SESSION['typePartie'] = $partie->type;
    $out = 'Lancement...';
    }
    else //le joueur a déjà fait les parties obligatoires
    {
    if(isset($postVille) && ($postVille) != "" && isset($postHero) && ($postHero) != "" && isset($postPartie) && ($postPartie) != "")
    {
    //faire le test pour voir si l'on a le droit de rentrer dans la partie
    if($_SESSION['partie'] != 0){//joueur déjà sur une partie
      $out = 'Vous êtes déjà sur une partie';
    }
    else
    {
      if(nomValide($postVille) && nomValide($postHero)){
        if($staticPartie->heroPresent($postPartie,$postHero))//hero déjà sur la partie
          $out = 'Le nom du héro est déjà utilisé dans cette partie';
        else
        {
            if($staticPartie->villePresent($postPartie,$postVille))//ville déjà sur la partie
              $out = 'Le nom de la ville est déjà utilisé dans cette partie';
            else
            {
              //type de partie
              $partie = new Partie();

              $partie->load($postPartie);
              $this->vitesse = $partie->vitesse; 
              //echo '$partie->vitesse; = '. $partie->vitesse; 
              $this->demarrerPartie($postPartie,$partie->type,$postVille,$postHero);
              $_SESSION['partie']=$postPartie;
              $_SESSION['typePartie'] = $partie->type;
              $out = 'Lancement...';
            }
        }
      }
      else
        $out = 'Seul les caratères alpha-numériques le _ et l\'espace sont autorisé (15 maximum).';
    }
    }
    else
    {
    $out = 'Un des champs est vide';
    }
    }
    //echo 'v = '.$GLOBALS['vitesse'];
    echo $out;
    }

    function demarrerPartie($idPartie,$typePartie,$nomVille,$nomHero){
    if($typePartie == 'mortequipe' || $typePartie == 'prise')
    {
    $this->alliance = $this->guilde;
    }
    else
    {
    $this->alliance = 0;
    }

    $map = prefixMapPartie($typePartie).$idPartie;
    $ville = new Ville;
    $idVille = $ville->insert($idPartie,$map,$this->id,$this->alliance,$nomVille);

    $ville->load($idVille);
    $ville->alliance = $this->alliance;
    if($typePartie == 'debut')
    $ville->init($idPartie,true);
    else
    $ville->init($idPartie);
    $ville->save();  

    $hero = new Hero();
    $idHero = $hero->insert($this->id,$this->alliance,$nomHero,$map);

    $hero->load($idHero);
    $hero->init($ville->X,$ville->Y);
    $hero->save();

    $this->idHero=array(0=>array('id'=>$idHero,'nom'=>$nomHero));
    $this->idVille=array(0=>array('id'=>$idVille,'nom'=>$nomVille));

    $this->partie = $idPartie;
    $this->bois = BOIS_DEPART;//BOIS_DEPART;
            $this->ors = ORS_DEPART;//ORS_DEPART;

    switch ($typePartie){
    case 'debut' : 
              $map = prefixMapPartie($typePartie).$this->partie;
    $idHero = Hero::insert(1,0,'Deneluan',$map);
    $hero = new Hero();
    $hero->load($idHero);
    $hero->garnison->AjoutUnite(0,10);
    $hero->garnison->AjoutUnite(1,3);
    $hero->garnison->AjoutUnite(2,1);
    $hero->save();
    /*
    $ally = new Alliance();
    $ally->load($this->alliance);
    */
    $mechant = new Joueur();
    $mechant->load(1);
              $case=new CaseObject($ville->X,$ville->Y,$map);  
    $case->ajouterHero($idHero,$mechant->login,1,'',0,$hero->nom);
    $case->etatHero($idHero,4,1,1,time()+TEMPS_AVANT_ATTAQUE,'v',$ville->id,'att');
    $case->save();

    /*
              $case=new CaseObject(1,1,$map);  
    $case->ajouterHero($idHero,$mechant->login,0,'',0,$hero->nom);
    $case->etatHero($idHero,3,$ville->X,$ville->Y,time()+TEMPS_AVANT_ATTAQUE);
    $case->save();
    */


              break;
              case 'rush';

              break;
              case 'mort' :

              break;
              case 'mortequipe' :

              break;
    }
            $_SESSION['AugAJour'] = 0;
            //echo 'vit = '.$this->vitesse;
            $this->maxRess();
    //$this->augRess();
    //echo $this->augOrsParMinute ;
    $this->bois = BOIS_DEPART;//BOIS_DEPART;
            $this->ors = ORS_DEPART;//ORS_DEPART;
            $this->etatPartie = 'jeu';
            $this->save();
            if($typePartie == 'debut')
              return $idHero;
    }

    function ajoutIdHero($id,$nom){
    if(is_array($this->idHero))
    array_push($this->idHero, array('id'=>$id,'nom'=>$nom));
    else
    $this->idHero[0]=array('id'=>$id,'nom'=>$nom);
    }

    function supprIdHero($id){
    $keys=array_keys($this->idHero);
    if(is_array($keys))
    foreach($keys as $key ){
    if($id != $key)
    $sort[$key]=$this->idHero[$key];
    }
    $this->idHero=$sort;
    }

    function sortirHero($id){
    $cle_hero = -1;
    if(is_array($this->idHero))
    foreach($this->idHero as $key => $value)
    {
    if($value['id'] == $id)
    {
    $cle_hero = $key;
    }
    }
    if($cle_hero != -1 )
    {
    unset($this->idHero[$cle_hero]);
    }
    }

    function ajoutIdVille($id,$nom){
    if(is_array($this->idHero))
    array_push($this->idVille, array('id'=>$id,'nom'=>$nom));
    else
    $this->idHero[0]=array('id'=>$id,'nom'=>$nom);
    }

    function supprIdVille($id){
    $keys=array_keys($this->idVille);
    if(is_array($keys))
    foreach($keys as $key ){
    if($id != $key)
    $sort[$key]=$this->idVille[$key];
    }
    $this->idVille=$sort;
    }

    function deleteIdVille($id){
    foreach($this->idVille as $key => $value){
    if($value['id'] == $id){
    unset($this->idVille[$key]);
    }
    }
    }

    function deleteIdHero($id){
    foreach($this->idHero as $key => $value){
    if($value['id'] == $id){
    unset($this->idHero[$key]);
    }
    }
    }

    function suppressionVilles(){
    if(isset($this->id))
            $GLOBALS["db"]->query('DELETE FROM j_ville WHERE idCompte='.$this->id);
    }

    function suppressionHeros(){
    if(isset($this->id))
            $GLOBALS["db"]->query('DELETE FROM j_hero WHERE idCompte='.$this->id);
    }

    function augRess(){
        //echo 'augRess';
    /*		if(!isset($_SESSION['AugJour']) OR $_SESSION['AugAJour']==0){
          echo 'Calcul Aug Ressource';
                 $this->calculAugRess();
                }*/


    $time = time();
    $dure= $time - $this->date_dern_action;
    $augOrs= round($dure*($this->augOrsParMinute/60),2);
    $augBois= round($dure*($this->augBoisParMinute/60),2);
        $this->ors+=$augOrs;
    $this->bois+=$augBois;

    if($this->ors > $this->orsMax)
        $this->ors=$this->orsMax;
    if($this->bois > $this->boisMax)
        $this->bois=$this->boisMax;
    $this->date_dern_action = time();
    //$GLOBALS['bois'] = $this->bois;
    //$GLOBALS['ors'] = $this->ors;
    //$sql = 'UPDATE j_compte SET `date_dern_action` = \''.$time.'\',`ors` = \''.$this->ors.'\', `bois` = \''.$this->bois.'\' WHERE `j_compte`.`id` = '.$this->id.' LIMIT 1;'; 
    //$GLOBALS["db"]->query($sql);
    }

    function calculAugRess(){   //Mise à jour de la production
    //echo 'calculAugRess';

    $mine=0;
    $sci=0;
    $mineur=0;
    $bucheron=0;
    $nivTotalHdv=0;
    $nivTotalMine=0;
    $nivTotalScie=0;
    if(is_array($this->idVille))
    foreach( $this->idVille as $key => $ville){

        $obJville = new Ville();
        $obJville->load($ville['id']);

        $mine=$obJville->mine;
        $sci=$obJville->scierie;
        $mineur=$obJville->mineur;
        $bucheron=$obJville->bucheron;

        $entrepot = $obJville->entrepot;
        $nivTotalHdv=$obJville->hdv;
        $nivTotalMine=$obJville->mine;
        $nivTotalScie=$obJville->scierie;

        $augOrsParMinutes += round(((COEF_AUG_ORS*GAIN_PAYSANS_ORS*$mineur)*60),2);
        $augBoisParMinutes += round(((COEF_AUG_BOIS*GAIN_PAYSANS_BOIS*$bucheron)*60),2);

        $OrsMax = $augOrsParMinutes*60*5;
        $BoisMax = $augBoisParMinutes*60*5;

        //$OrsMax +=($nivTotalHdv*COEF_HDV)+($nivTotalMine*COEF_MINE)+100+($entrepot*CAPACITE_ENTREPOT);
                    //$BoisMax +=($nivTotalHdv*COEF_HDV+$nivTotalScie*COEF_SCIE)+100+($entrepot*CAPACITE_ENTREPOT);;

    }
    //echo 'vitesse = '.$this->vitesse;
    $this->augOrsParMinute = $augOrsParMinutes*$this->vitesse;
    $this->augBoisParMinute = $augBoisParMinutes*$this->vitesse;
    $this->orsMax=$OrsMax*$this->vitesse;
    $this->boisMax=$BoisMax*$this->vitesse;

    $this->save();

    //echo 'Calcul Aug Ressource';
    $_SESSION['AugAJour'] = 1;
    }

    function maxRess(){
    if((!isset($_SESSION['AugAJour']) OR $_SESSION['AugAJour'] != 1) OR !isset($_SESSION['position'])){
      $this->calculAugRess();
    }
    }

    //Recrutement Pyasans et Unites
    function recruterPaysans($nb){
    $recrut = 0;
    for($x=0;$x<$nb;$x++){
        if($this->ors >= COUT_PAYSANS_ORS AND $this->bois >= COUT_PAYSANS_BOIS){
            $this->ors-=COUT_PAYSANS_ORS;
            $this->bois-=COUT_PAYSANS_BOIS;
            $recrut++;
        }
    }
    $this->save();
    $stat=new Stat();
    $stat->load($this->id);
    $stat->calculHab();
    $stat->save();
    return $recrut;
    }

    function recruterUnite($type,$nb,&$ville){
    $max = 0;
    $this->recherche->loadVille($ville);
     $nb = round($nb);
    if( $this->recherche->UniteFaisable($type) == 0 AND $nb>=1){

      $maxOrs = $this->ors/$GLOBALS['Ounite'][$type];
      $maxBois = $this->bois/$GLOBALS['Bunite'][$type];

      if($maxOrs >= $maxBois){
        $max = floor($maxBois);
      }else{
        $max = floor($maxOrs);
      }

      if($max >= $nb){
        $max = $nb;
      }

        $this->bois -= $GLOBALS['Bunite'][$type]*$max;
        $this->ors -= $GLOBALS['Ounite'][$type]*$max;
        $this->save();

    }else{
    $max = 0;
    }
        return $max;

    }	

    function afficherMenuGauche($get){
        $html= '<img src="./skin/original/texte/ville.png" alt="ville"/><ul>';
          if(is_array($this->idVille)){
                foreach($this->idVille as $key => $value){

                        if($_SESSION['position'][0] == "v" AND $_SESSION['position'][1] == $value['id']){
                            $html.= '<li><a href="#" onclick="ajaxVilleLoad('.$value['id'].');");">'.$value['nom'].' <font color="red">*</font></a></li>';
                        }else{
                            $html.= '<li><a href="#" onclick="ajaxVilleLoad('.$value['id'].');");">'.$value['nom'].' </a></li>';
                        }
                        //$html.= '<li><a href="#" onclick="ajaxLoad("contenu","data.php?div=contenu&v='.$key.'");">'.$value->nom.'</a></li>';
                }
                }
        $html.= '</ul><br /><br />
        <img src="'.$GLOBALS['skin'].'texte/hero.png" alt="héros"/>
        <ul>';
        if(is_array($this->idHero)){
        foreach($this->idHero as $key => $value){

          if($_SESSION['position'][0] == "h" AND $_SESSION['position'][1] == $value['id']){
                   $html.= '<li><a href="#" onclick="ajaxHeroLoad('.$value['id'].');");">'.$value['nom'].'<font color="red">*</font></a></li>';
                }else{
                   $html.= '<li><a href="#" onclick="ajaxHeroLoad('.$value['id'].');");">'.$value['nom'].'</a></li>';
                }
        }
    }
        $html.= '</ul>';
        return $html;
    }

    function afficheContenu($get){
    $html = "";

    if($this->position != 0){

    if($this->position == 1){
    $html.='<div id="newmessage"><img src="'.$GLOBALS['skin'].'design/newmessage.png" alt="nouveau message" /></div>';
    }
    if($this->position == 2){
    $html.='<div id="newrapport"><img src="'.$GLOBALS['skin'].'design/newrapport.png" alt="nouveau rapport" /></div>';
    }
    if($this->position == 3){
    $html.='<div id="newmessage"><img src="'.$GLOBALS['skin'].'design/newmessage.png" alt="nouveau message" /></div>';
    $html.='<div id="newrapport"><img src="'.$GLOBALS['skin'].'design/newrapport.png" alt="nouveau rapport" /></div>';
    }

    }

    if($this->typePartie == "debut" AND (!isset($_SESSION['tuto']) OR $_SESSION['tuto'] < 1)){
    echo '<div id="needhelp">
    <img src="'.$GLOBALS['skin'].'design/needhelp.png" width="150" /></div>';
    }

    if(isset($get['lvlup']) AND isset($get['v'])){

    if($this->ville[$get['v']]->construction == 0 && $this->ville[$get['v']]->constructionPossible($get['lvlup'])){
        $cout = coutBat($get['lvlup'],$this->ville[$get['v']]->$get['lvlup']+1);
        if($this->bois >= $cout['bois'] AND $this->ors >= $cout['ors']){
            //$cout['temps'] = (int)($cout['temps'] * $GLOBALS['vitesse']);
            $action = new Action();
            $id = $action->newAction("bat",$get['v'],$get['lvlup'],( $cout['temps'] / $GLOBALS['vitesse']));

            $this->ville[$get['v']]->construction = $get['lvlup'];
                        $this->ville[$get['v']]->idActionConstruction = $id;
                        $this->ville[$get['v']]->time = ($cout['temps']/$GLOBALS['vitesse'] + time());
            //echo date("H:i:s",time()).' + '.date("H:i:s",($cout['temps']/$this->vitesse)).' = '.date("H:i:s",($cout['temps']/$this->vitesse + time()));
            $this->bois -= $cout['bois'];
            $this->ors -= $cout['ors'];
            $this->maxRess();
            $this->save();
                        $this->ville[$get['v']]->save();
        }
    }
    }

        if(!isset($get['v']) AND !isset($get['h']) AND !isset($get['general'])){
                if(isset($_SESSION['position'])){
                $get[$_SESSION['position'][0]]=$_SESSION['position'][1];

                }else{
                $get['v']=$this->idVille[0]['id'];//Ville par defaut
                }
        }

        if(isset($get['v'])){ // Si on a cliqué sur une ville

            $_SESSION['position'][0] = "v";
            $_SESSION['position'][1] = $get['v'];
            $this->augRess();
            $this->maxRess();
            //$this->save();

            $html .= '
                    <div class="contenu">
                    <div class="contenu_header_fond">
                    <table width="100%">
                        <tr align="center">
                            <td class="tab_ressources">'.icoBois().'<span id="ressources">'.formRess($this->augBoisParMinute).'/min</span></td>
                            <td class="tab_ressources">'.icoOrs().'<span id="ressources">'.formRess($this->augOrsParMinute).'/min</span></td>
                        </tr>
                        <tr align="center">
                            <td><span id="capacite_ressource">'.formRess(round($this->bois)).'/'.formRess($this->boisMax).'</td></span>
                            <td><span id="capacite_ressource">'.formRess(round($this->ors)).'/'.formRess($this->orsMax).'</td></span>
                        </tr>  
                    </table>             
                    </div>';
                    if(is_object($this->ville[$get['v']])){
                        $html.= $this->ville[$get['v']]->affiche($get);
                    }
                    $html.= '</div>';
            return $html;
            break;
        }
        if(isset($get['h'])){ // Si on a cliqué sur un héro
                    $_SESSION['position'][0] = 'h';
                    $_SESSION['position'][1] = $get['h'];
                    $html = "";
                    if(is_object($this->hero[$get['h']])){
                       $html.= $this->hero[$get['h']]->affiche($get);
                    }

            return $html;
            break;
        }

        if(isset($get['general'])){
                if($get['general']=="compte"){
                        $html= $this->afficherMonCompte($get);
                }
        return $html;
        break;
        }
    }

    function afficherMonCompte($get){

    $html= '<div class="contenu">
    <div class="contenu_header_fond"><div class="menu_hero"><div align="center"><img src="'.$_SESSION['skin'].'/design/optionscompte.png" /></div></div></div>
    <div class="contenu_fond_centre">
    <div class="optionscompte">
    <br /><br />
    Compte : '.$this->login.'
    <table>
    <tr><td width="200px"><h3>Changement du mot de passe :</h3></td></tr>
    <tr><td>Tape ton ancien mot de passe :</td></tr>
    <tr><td><input id="oldPass" class="inputText" name="oldPass" maxlength="50" autocomplete="off" type="password" value=""></td></tr>
    <tr><td>Tape ton nouveau mot de passe :</td></tr>
    <tr><td><input id="newPass" class="inputText" name="newPass" maxlength="50" autocomplete="off" type="password" value=""></td></tr>
    <tr><td><button class="search" type="submit" title="Search" onClick="sendFormPass()"></button></td></tr>
    </tr>
    <tr><td><div id="returnPass"> </div></td></tr>

    </table>


    <br />


    <table>
    <tr><td width="200px"><h3>Changement de l\'adresse mail :</h3></td></tr>

    <tr><td><input id="newMail" class="inputText" name="oldPass" maxlength="50" autocomplete="off" type="text" value="'.$this->email.'"></td></tr>
    <tr><td><button class="search" type="submit" title="Search" onClick="sendFormMail()"></button></td></tr>

    <tr><td><div id="returnMail"> </div></td><td></td></tr>
    </table>
    <br />';
    if($this->etat != 1 && $this->etat != 2 )
    {
    $html .=	'<table>
    <tr>
    <td><h3>Quitter la partie en cours</h3></td>
    </tr>
    <tr>
    <td>'.ahref('<div class="quitter"><img src="skin/original/design/btn-quitter.png" /></div>','data.php?div=contenu&action=quitterPartie',"contenu").'</td>
    </tr> 
    </table>';
    }

    $html .= '
    </div>
    <br /><br /><br /><div align="center"><img src="skin/original/design/header4.png" /></div>
    </div>

    </div>';
    return $html;
    }

    function changerAlliance($jid,$id_ally,$nom_ally,$tag_ally){
    $j = new Joueur();
    $j->loadSimple($jid);

                           $escuse = "";
               if($j->partie > 0){

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

                if(is_array($j->idHero) AND count($j->idHero) > 0 ){
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

                 if($escuse == ""){ // Pas d'excuse on peut changer d'alliance

                 if(is_array($j->idVille) AND count($j->idVille) > 0 ){
                   foreach($j->idVille as $key => $value){
                      $ville = new Ville();
                      $ville->load($value['id']);
                      $ville->idAlliance = $id_ally;
                      $case = new CaseObject($ville->X,$ville->Y,$ville->map);
                      $case->ville[$ville->id]['alliance'] = $tag_ally;
                      $case->ville[$ville->id]['id_alliance'] = $id_ally;
                      $case->save();
                      $ville->save();
                   }
                }

                if(is_array($j->idHero) AND count($j->idHero) > 0 ){
                   foreach($j->idHero as $key => $value){
                      $hero = new Hero();
                      $hero->load($value['id']);
                      $hero->idAlliance = $id_ally;
                      $hero->save();
                      $case = new CaseObject($hero->X,$hero->Y,$hero->map);
                      $case->hero[$hero->id]['alliance'] = $tag_ally;
                      $case->hero[$hero->id]['id_alliance'] = $id_ally;
                      $case->save();

                   }
                }
                $j->alliance = $id_ally;
                $j->guilde = $id_ally;
                $j->save();
                    return 0;
                          //echo 'Lancement...';
                                //exit();

                 }else{
                  //echo $escuse;
                  return $escuse;
                 }
                 //print_r($this->idHero);
               }

    }
}
?>
