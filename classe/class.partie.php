<?php
class Partie{
	var $id;
	var $debut;
	var $vitesse;
	var $tailleX;
	var $tailleY;
	var $type; // debut => parie d'innitiataion
	           // rush  => course aux ressources
	           // mort => match a mort
	           // mortequipe => match a mort par equipe 
	var $nom;
	var $hab;
	var $off;
	var $def;
	var $cambat;
	var $gagnant;//0=personne, -1 le méchant (parie type = debut), >0=id du joueur ou alliance(celon type de partie)


  
	function load($id){
		
		if($id == null){
			echo 'Load Partie id = null';
		}
		
		$sql = 'SELECT * FROM j_partie WHERE id = '.$id;
		$result = $GLOBALS["db"]->query($sql);
		$donnees = mysql_fetch_array($result);
		$this->id = $donnees['id'];
		$this->debut = $donnees['debut'];
		$this->vitesse = $donnees['vitesse'];
		$this->tailleX = $donnees['tailleX'];
		$this->tailleY = $donnees['tailleY'];
		$this->type = $donnees['type'];
		$this->nom = $donnees['nom'];
		$this->hab = $donnees['hab'];
		$this->off = $donnees['off'];
		$this->def = $donnees['def'];
		$this->combat = $donnees['combat'];
		$this->gagnant = $donnees['gagnant'];
	}
	
	function save(){
		$sql = "UPDATE j_partie SET
		debut = '".$this->debut."',
		vitesse = '".$this->vitesse."',
		tailleX = '".$this->tailleX."',
		tailleY = '".$this->tailleY."',
		type = '".$this->type."',
		nom = '".$this->nom."',
		hab = '".$this->hab."',
		off = '".$this->off."',
		def = '".$this->def."',
		combat = '".$this->combat."',
		gagnant = '".$this->gagnant."' 
		WHERE `id` = '".$this->id."' ;";

		$GLOBALS['db']->query($sql);
	}
  
  function saveUnChamp($champ,$val,$id){
        if($id == NULL || !isset($id) || $id == 0 )
          $id = $this->id;
        if($id != 0 && $id != NULL && $champ != NULL && $val != NULL )
        {
	        $sql =" UPDATE j_partie SET
                 $champ = '".$val."' 
                 WHERE `id` = '".$id."' ;";
          $GLOBALS['db']->query($sql);
        }
        else
		echo 'Erreur lors de la modification d\'un champ Partie';
  }
  
  
  function suppression(){
		$GLOBALS["db"]->query('DELETE FROM j_partie WHERE id='.$this->id);
  }
  
  
  function listeParties(){
  $sql = $GLOBALS["db"]->query('SELECT id FROM j_partie ');
    $i=0;
    while($donnees = mysql_fetch_array($sql)){
    $tab[$i]=$donnees['id'];
    $i++;
    }
  return $tab;
  }  
  
  function dernierePartieRush()
  {
    $sql = $GLOBALS["db"]->query("SELECT MAX(id) AS maxid FROM j_partie WHERE type = 'rush'",1);

    $donnees = mysql_fetch_assoc($sql);
	echo 'return 5555: '.$donnees['maxid'];
    return $donnees['maxid'];
  }

  function dernierePartiePrise()
  {
    $sql = $GLOBALS["db"]->query("SELECT MAX(id) AS maxid FROM j_partie WHERE type = 'prise'");

    $donnees = mysql_fetch_array($sql);
	
	if($donnees['maxid'] > 0){
		return $donnees['maxid'];
	}else{
		
		return 0;
	}
  }

  function idPartieJouable($type,$vitesse)//id de la partie mort vitesse normal jouable ou -1 si non
  {
    if($type == 'mort' && $vitesse == 'normal' )
        $sql = $GLOBALS["db"]->query("SELECT id FROM j_partie WHERE type = 'mort' AND debut < ".time()." AND debut > ".(time()-TEMPS_OUVERTURE_ENTREE_MORT)." AND gagnant = 0 AND vitesse < 2");
    if($type == 'mort' && $vitesse == 'rapide' )
        $sql = $GLOBALS["db"]->query("SELECT id FROM j_partie WHERE type = 'mort' AND debut < ".time()." AND debut > ".(time()-TEMPS_OUVERTURE_ENTREE_MORT)." AND gagnant = 0 AND vitesse >= 2");
    if($type == 'mortequipe' && $vitesse == 'normal' )
        $sql = $GLOBALS["db"]->query("SELECT id FROM j_partie WHERE type = 'mortequipe' AND debut < ".time()." AND debut > ".(time()-TEMPS_OUVERTURE_ENTREE_MORTEQUIPE)." AND gagnant = 0 AND vitesse < 2");
    if($type == 'mortequipe' && $vitesse == 'rapide' )
        $sql = $GLOBALS["db"]->query("SELECT id FROM j_partie WHERE type = 'mortequipe' AND debut < ".time()." AND debut > ".(time()-TEMPS_OUVERTURE_ENTREE_MORTEQUIPE)." AND gagnant = 0 AND vitesse >= 2");
    $donnees = mysql_fetch_array($sql);
    if(is_null($donnees['id'])){
      if($vitesse == 'normal')
        $vit = 1;
      else
        $vit = 5;
      $nPartie = Partie::newPartie(time(),$vit,'15','15',$type,titrePartie());
      $p=new Partie();
      $p->load($nPartie);
      $p->newMap(10);
      $p->save();
      return $nPartie;
    }
    return $donnees['id'];
  }
 
  
  //retourn : true => partie presentente dans j_partie , false => si non
  function partiePresente($sonId)
  {
      $out = false;
      $tab = array();
      $sql = $GLOBALS["db"]->query('SELECT * FROM j_partie ');
      $i=0;
      while($donnees = mysql_fetch_array($sql)){
        $tab[$i]=$donnees['id'];
        $i++;
      }
      foreach ( $tab as $val)
      {
        if ( $val == $sonId)
          $out = true;
      }
      return $out;
  }
  
  //retourn : true => joueur est déjà sortie de la partie , false => si non
  function joueurDejaSortie($sonId)
  {
      $out = false;
      $sql = $GLOBALS["db"]->query('SELECT bois FROM j_compte WHERE id = '.$sonId);
      $donnees = mysql_fetch_array($sql);
      if ( $donnees['bois'] == 0 )
          $out = true;      
      return $out;
  }

	function taille(&$x,&$y)
	{
		$x = $this->tailleX;
		$y = $this->tailleY;    
	}

	function heroPresent($id,$nom){
		$sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE partie = '.$id);
		while($donnees = mysql_fetch_array($sql)){
                    $joueur = new Joueur();
                    $joueur->loadSimple($donnees['id']);
                    if(is_array($joueur->idHero))
                    foreach ( $joueur->idHero as  $j)    
                    if($j['nom'] == $nom)
                    return true;
                    unset($joueur);
		}
		return false;
	}

	function villePresent($id,$nom){
		$sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE partie = '.$id);
		while($donnees = mysql_fetch_array($sql)){
		  $joueur = new Joueur();
		  $joueur->loadSimple($donnees['id']);
		  if(is_array($joueur->idVille))
		  foreach ( $joueur->idVille as  $j)    
			if($j['nom'] == $nom)
			  return true;
		  unset($joueur);
		}
		return false;
	}
	
	function calculStat(){
		$n_hab = 0 ;
		$n_off = 0 ;
		$n_def = 0 ;
		$n_combat = 0 ;
		$sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE partie = '.$this->id);
		
		while($donnees = mysql_fetch_array($sql)){
			$s = new Stat();
			$s->load($donnees['id']);
			$n_hab += $s->hab;
			$n_off += $s->off;
			$n_def += $s->def;
			$n_combat += $s->combat;
		}
		
		$this->hab= $n_hab;
		$this->off= $n_off;
		$this->def= $n_def;
		$this->combat= $n_combat/2;
	}

    function newPartie($debut,$vitesse,$tailleX,$tailleY,$type,$nom){
        $sql = "
        INSERT INTO j_partie 
        VALUES(
        '',
        '$debut',
        '$tailleX',
        '$tailleY',
        '$vitesse',
        '$type',
        '$nom',
        '',
        '',
        '',
        '',
        '')
        ";
        $GLOBALS['db']->query ($sql);  

        $cpt=$GLOBALS['db']->query("SELECT LAST_INSERT_ID() as nb FROM j_partie " );
        $compt = mysql_fetch_object($cpt);
        return $compt->nb;
    }

    function initPartiePrise(){

        $map = prefixMapPartie($this->type).$this->id;
        $j = new Joueur();
        $j->init(NOM_ADMIN,MDP_ADMIN,'');
        $idJ = $j->insert();
        $j->loadSimple($idJ);
        $j->etat = 4;
        $j->allance = 1;
        $j->partie = $this->id;


        $idVille = Ville::insert($this->id,$map,$idJ,1,NOM_VILLE);
        $j->ajoutIdVille($idVille,NOM_VILLE);
        $j->save();

        $ville = new Ville();
        $ville->load($idVille);
        $ville->hdv = 20;

        $ville->mine = 20; // Niveau Mine
        $ville->scierie = 20; // Niveau Scierie
        $ville->caserne = 20;
        $ville->tour = 20;
        $ville->uarm = 20;
        $ville->entrepot = 20;
        $ville->recherche = 20;
        $ville->marche = 20;

        $ville->mineur = 50; // NB paysan atribué à la mine
        $ville->bucheron = 50; // NB paysan atribué à la scierie

        $ville->garnison->AjoutUnite(0,5000);
        $ville->garnison->AjoutUnite(1,5000);
        $ville->garnison->AjoutUnite(3,5000);
        $ville->save();

        $pX = ceil($this->tailleX / 2);
        $pY = ceil($this->tailleY / 2);

        $case=new CaseObject($pX,$pY,$map);  
        $case->ajouterVille($idVille,$j->login,$j->id,'ADMIN',1,$ville->nom);
        $case->save();
    }

    function joueurSurPartie(){

        $out = array();
        $i=0;
        $sql = $GLOBALS["db"]->query('SELECT id FROM j_compte WHERE partie = "'.$this->id.'"');
        while($donnees = mysql_fetch_array($sql)){
          $out[$i] = $donnees['id'];
          $i++;
        }

        return $out;
    }

    function joueurSurPartieSansVille(){

      $out = array();
      $i=0;
      $sql = $GLOBALS["db"]->query('SELECT id FROM j_compte WHERE partie = "'.$this->id.'" AND idVille="a:0:{}" ');
      while($donnees = mysql_fetch_array($sql)){
        $out[$i] = $donnees['id'];
        $i++;
      }
      return $out;
    }
  
    function joueurActifSurPartie(){
        $out = array();
        $i=0;
        $sql = $GLOBALS["db"]->query('SELECT id FROM j_compte WHERE partie = "'.$this->id.'" AND date_dern_action > '.(time() - TIME_INACTIF_MAX).' AND etatPartie = \'jeu\'');
        while($donnees = mysql_fetch_array($sql)){
            $out[$i] = $donnees['id'];
            $i++;
        }
        return $out;
    }


    function fini(){
            $joueurSave = new Joueur();
            switch ($this->type){
                    case 'debut' : 
                            $attEnVie = false;
                            $map = prefixMapPartie($this->type).$this->id;
                            $tabJoueurSurPartie = $this->joueurSurPartie();
                            $case = new CaseObject(3,3,$map);  
                            $tabHero = $case->hero;

                            if(is_array($tabHero))
                            foreach($tabHero as $hero)
                            {  
                                    if($hero['id_proprietaire'] == 1)
                                    {
                                            $attEnVie = true;
                                            if($hero['etat']==4)
                                            {
                                                    //echo 'en cour d\'arrivé';
                                            }
                                            elseif($hero['etat']==1)
                                            {
                                                    //echo 'il m\'a poutré';
                                                    $joueurSave->saveUnChamp('etatPartie','perdu',$tabJoueurSurPartie[0] );      
                                                    $this->saveUnChamp('gagnant',-1,$this->id);
                                            }
                                    }
                            }
                            if ( ! $attEnVie)//le hero attaquant est mort !
                            {
                                    //echo 'J\'ai gagné';
                                    $joueurSave->saveUnChamp('etatPartie','gagne',$tabJoueurSurPartie[0] );
                                    $this->saveUnChamp('gagnant',$tabJoueurSurPartie[0],$this->id);
                            }
                    break;

                    case 'prise' :
                                            $tabJoueurSurPartie = $this->joueurSurPartieSansVille();
                                            foreach ($tabJoueurSurPartie as $idJoueur )
                                            {
                                              if($idJoueur != 0 AND $idJoueur != ""){
                                                    $j = new Joueur();
                                                    $j->loadSimple($idJoueur);
                                                    if($j->etatPartie == 'jeu' )
                                                    {
                                                      if(count ($j->idVille) == 0 )
                                                      {
                                                            $joueurSave->saveUnChamp('etatPartie','perdu',$j->id );
                                                      }
                                                    }
                                              }
                                            }
                                            $pX = ceil($this->tailleX / 2);
                                            $pY = ceil($this->tailleY / 2);
                                            $map = prefixMapPartie($this->type).$this->id;
                                              $case=new CaseObject($pX,$pY,$map);
                                              foreach( $case->ville as $villes )
                                              {
                                              if($villes['id_alliance'] != 1)
                                                      {
                                                            $tabJoueurSurPartie = $this->joueurSurPartie();
                                                            foreach ($tabJoueurSurPartie as $idJoueur )
                                                            {
                                                              if($idJoueur != 0 AND $idJoueur != "" ){
                                                                    $j = new Joueur();
                                                                    $j->loadSimple($idJoueur);
                                                                    if($j->alliance != 1)
                                                                    {
                                                                      if($j->alliance == $case->ville[1]['id_alliance'] )
                                                                      {
                                                                            $joueurSave->saveUnChamp('etatPartie','gagne',$j->id );
                                                                            $this->saveUnChamp('gagnant',$j->alliance,$this->id);
                                                                      }
                                                                      else
                                                                      {
                                                                            $joueurSave->saveUnChamp('etatPartie','perdu',$j->id );
                                                                      }
                                                                    }
                                                              }
                                                            }
                                                      }
                                              }
                    break;

                    case 'rush' :
                            $tabJoueurSurPartie = $this->joueurSurPartie();
                            foreach ($tabJoueurSurPartie as $idJoueur )
                            {
                                    if($idJoueur != 0 AND $idJoueur != ""){
                                            $j = new Joueur();
                                            $j->loadSimple($idJoueur);
                                            if($j->etatPartie == 'jeu' )
                                            {
                                                    if($j->augBoisParMinute >= FIN_RUSH_PROD_BOIS && $j->augOrsParMinute >= FIN_RUSH_PROD_ORS ){
                                                            //echo 'le joueur '.$idJoueur.' a fini la partie';
                                                            $joueurSave->saveUnChamp('etatPartie','gagne',$j->id );
                                                    }

                                                    if(count ($j->idVille) == 0 ){
                                                            $joueurSave->saveUnChamp('etatPartie','perdu',$j->id );
                                                    }
                                            }
                                    }
                            }
                    break;
                    case 'mort' :
                            /*
                            apres 48h - un joueur => il a gagné
                                       - plusieurs joueurs 
                                                    - perdu = s'il a plus de ville
                                                    - gagné = s'il est seul sur la partie
                            */
                            $tabJoueurSurPartie = $this->joueurActifSurPartie();
                            if ( count($tabJoueurSurPartie) == 1 && time() > ($this->debut + TEMPS_MINI_AVANT_FIN_MORT  )  )
                            {
                                    //echo ' joueur gagné ';
                                    //le joueur $tabJoueurSurPartie[0] a gagné
                                    $joueurSave->saveUnChamp('etatPartie','gagne',$tabJoueurSurPartie[0] );
                                    $this->saveUnChamp('gagnant',$tabJoueurSurPartie[0],$this->id);
                                    $map = prefixMapPartie($this->type).$this->id;
                                    Map::suppression($map);
                            }
                            else
                            {
                                    foreach ($tabJoueurSurPartie as $idJoueur )
                                    {
                                            $j = new Joueur();
                                            $j->loadSimple($idJoueur);
                                            if( count($j->idVille) < 1 ){
                                                    //echo 'le joueur '.$idJoueur.' a fini la partie';
                                                    $joueurSave->saveUnChamp('etatPartie','perdu',$j->id );
                                                    $this->finPartie($idJoueur,$this->id,'perdu');
                                            }
                                    }
                            }
                    break;

                    case 'mortequipe' :

                                    /*
                                    apres 48h - une seul alliance => ils ont gagné
                                               - plusieurs alliance
                                                            - perdu =  joueur qui n'a plus de ville      
                                    */
                                    $tabJoueurSurPartie = $this->joueurActifSurPartie();
                                    $uneAlly = true;
                                    $j = new Joueur();
                                    $j->loadSimple($tabJoueurSurPartie[0]);
                                    $allyP=$j->alliance;
                                    //echo 'ally 1 = '.$j->alliance;
                                    foreach($tabJoueurSurPartie as $idJoueur ){
                                            $j = new Joueur();
                                            $j->loadSimple($idJoueur);

                                            //echo 'ally = '.$j->alliance;

                                            if( $j->alliance != $allyP ){
                                                    $uneAlly = false;
                                            }
                                    }

                                    if ( $uneAlly && time() > ( $this->debut + TEMPS_MINI_AVANT_FIN_MORTEQUIPE)){

                                            $tabJoueurSurPartie = $this->joueurActifSurPartie();
                                            foreach ($tabJoueurSurPartie as $idJoueur ){
                                                    $joueurSave->saveUnChamp('etatPartie','gagne',$idJoueur );
                                            }

                                            $map = prefixMapPartie($this->type).$this->id;
                                            Map::suppression($map);
                                            $this->saveUnChamp('gagnant',$allyP,$this->id);
                                            echo 'partie '.$this->id .' est fini . ';

                                    }else{
                                            foreach ($tabJoueurSurPartie as $idJoueur ){
                                                    $j = new Joueur();
                                                    $j->loadSimple($idJoueur);
                                                    if( count($j->idVille) < 1 ){
                                                            //echo 'le joueur '.$idJoueur.' a fini la partie';
                                                            $joueurSave->saveUnChamp('etatPartie','perdu',$j->id );
                                                            $this->finPartie($idJoueur,$this->id,'perdu');
                                                    }
                                            }
                                    }
                    break;
            }
    }
	

  function finPartie($idJ,$idP,$etatP){
      if ( Partie::partiePresente($idP) )
      {
      //partie pas supprimée
          $p=new Partie();
          $p->load($idP);
          switch ($p->type){
          case 'debut' :
              $tabJoueurSurPartie = $p->joueurSurPartie();
              $j=new Joueur();
              $j->loadSimple($tabJoueurSurPartie[0]);
              $map = prefixMapPartie($p->type).$p->id;
              Map::suppression($map);
              Hero::suppression($p->off);
              if( $etatP == 'gagne' )
                $j->etat = 2;

              //suppression de cette partie   
              //$p->suppression();
          break;
          case 'rush' :
              $j=new Joueur();
              $j->loadSimple($idJ);

              
              $map = prefixMapPartie($p->type).$p->id;
              //suppression des heros sur la map et dans j_hero
              if(is_array($j->idHero))
              foreach ( $j->idHero as $tabHero )
              {
                $h = new Hero();
                $h->load($tabHero['id']);
                $c = new CaseObject($h->X,$h->Y,$h->map);
                $c->enleverHero($tabHero['id']);
                $c->save();
                $h->delet();
              }
              //suppression des villes sur la map et dans j_ville
              if ($etatP == 'gagne' ){
                if(is_array($j->idVille))
                foreach ( $j->idVille as $tabVille )
                {
                  $v = new Ville();
                  $v->load($tabVille['id']);
                  $c = new CaseObject($v->X,$v->Y,$v->map);
                  $c->enleverVille($tabVille['id']);
                  $c->save();
                  $v->delet();
                }
                
                
              }
              $j->etat = 3;
              //netoyage du joueur
         
              //mise a jour des stats du joueur
              
          break;
          case 'mort' :
              $j=new Joueur();
              $j->loadSimple($idJ);

              
              $map = prefixMapPartie($p->type).$p->id;
              //suppression des heros sur la map et dans j_hero
              if(is_array($j->idHero))
              foreach ( $j->idHero as $tabHero )
              {
                $h = new Hero();
                $h->load($tabHero['id']);
                $c = new CaseObject($h->X,$h->Y,$h->map);
                $c->enleverHero($tabHero['id']);
                $c->save();
                $h->delet();
              }
              //suppression des villes sur la map et dans j_ville
              if ($etatP == 'gagne' ){
                if(is_array($j->idVille))
                foreach ( $j->idVille as $tabVille )
                {
                  $v = new Ville();
                  $v->load($tabVille['id']);
                  $c = new CaseObject($v->X,$v->Y,$v->map);
                  $c->enleverVille($tabVille['id']);
                  $c->save();
                  $v->delet();
                }
                  //$p->gagnant = $idJ;
                  //$p->save();
              }
           
              
          break;
          case 'mortequipe' :
              $j=new Joueur();
              $j->loadSimple($idJ);
              //mise a jour des stats du joueur

              
              $map = prefixMapPartie($p->type).$p->id;
              //suppression des heros sur la map et dans j_hero
              if(is_array($j->idHero))
              foreach ( $j->idHero as $tabHero )
              {
                $h = new Hero();
                $h->load($tabHero['id']);
                $c = new CaseObject($h->X,$h->Y,$h->map);
                $c->enleverHero($tabHero['id']);
                $c->save();
                $h->delet();
              }
              //suppression des villes sur la map et dans j_ville
              if ($etatP == 'gagne' ){
                if(is_array($j->idVille))
                foreach ( $j->idVille as $tabVille )
                {
                  $v = new Ville();
                  $v->load($tabVille['id']);
                  $c = new CaseObject($v->X,$v->Y,$v->map);
                  $c->enleverVille($tabVille['id']);
                  $c->save();
                  $v->delet();
                }
                  //$p->gagnant = $idJ;
                  //$p->save();
              }
          
                        
          break;
          case 'prise' :
              $j=new Joueur();
              $j->loadSimple($idJ);
              //mise a jour des stats du joueur

              
              $map = prefixMapPartie($p->type).$p->id;
              //suppression des heros sur la map et dans j_hero
              if(is_array($j->idHero))
              foreach ( $j->idHero as $tabHero )
              {
                $h = new Hero();
                $h->load($tabHero['id']);
                $c = new CaseObject($h->X,$h->Y,$h->map);
                $c->enleverHero($tabHero['id']);
                $c->save();
                $h->delet();
              }
              //suppression des villes sur la map et dans j_ville
              if ($etatP == 'gagne' ){
                if(is_array($j->idVille))
                foreach ( $j->idVille as $tabVille )
                {
                  $v = new Ville();
                  $v->load($tabVille['id']);
                  $c = new CaseObject($v->X,$v->Y,$v->map);
                  $c->enleverVille($tabVille['id']);
                  $c->save();
                  $v->delet();
                }
                  //$p->gagnant = $idJ;
                  //$p->save();
              }
         
                        
          break;
          }

          $stat = new Stat();
          $stat->load($idJ);
          $stat->finPartie($idP);
          $stat->save();
          
          $j->suppressionVilles();
          $j->suppressionHeros();    
           
          $j->idVille = array();
          $j->idHero = array();
          $j->bois = 0;
      		$j->ors = 0;
      		$j->boisMax = 0;
      		$j->orsMax = 0;
      		$j->augOrsParMinute = 0;
      		$j->augBoisParMinute = 0;
      		$j->partie = 0;
      		//$j->etatPartie = 'jeu';
          $j->save();     

      }
      else
      {
      //partie déjà supprimée
      }
	}
	
    function afficheFinPartie($idJ,$idP,$etatP){
        $html = '<div class="contenu">
        <div class="contenu_header_fond" align="center">
        <img src="./skin/original/design/fin_partie.png" alt="Fin de partie" class="fin_titre"/>
        </div>
        <div class="contenu_fond_centre"><br />';

        if ( ! Partie::joueurDejaSortie($idJ) ){//on la supprime
            Partie::finPartie($idJ,$idP,$etatP);
        }
        
        $stat = new Stat();
        $stat->load($idJ);
        $dernPartie = $stat->dernierPartieJoue();

        if($dernPartie != -1 )
        {
            $pa = new Partie();
            $pa->load($dernPartie);
            $switchType = $pa->type;
        }
        else
        {
            $switchType = 'debut';
        }
        
        switch ($switchType){
            /*
            case 'debut':
                if( $etatP == 'gagne' ){
                      $html .= '<div class="fin_partie">';
                      $html .= $GLOBALS['htmlDebutGagne'];
                      $html .= '<br /></div>';
                      $_SESSION['tuto']=0;
                }else{ // if ($etatP == 'perdu')
                      $html .= '<div class="fin_partie">';
                      $html .= $GLOBALS['htmlDebutPerdu'];
                      $html .= '<br /></div>';
                      $_SESSION['tuto']=0;              
                }
            break;
            
            case 'rush':
                if( $etatP == 'gagne' ){
                    $html .= '<div class="fin_partie">';
                    $html .= $GLOBALS['htmlRushGagne'];
                    $html .= '<br /></div>';
                }else{ //if ($etatP == 'perdu')
                    $html .= '<div class="fin_partie">';
                    $html .= $GLOBALS['htmlRushPerdu'];
                    $html .= '<br /></div>';       
                }
            break;
            
            case 'mort':
                if( $etatP == 'gagne' ){
                    $html .= '<div class="fin_partie">';
                    $html .= $GLOBALS['htmlMortGagne'];
                    $html .= '<br /></div>';
                }else{ //if ($etatP == 'perdu')
                    $html .= '<div class="fin_partie">';
                    $html .= $GLOBALS['htmlMortPerdu'];
                    $html .= '<br /></div>';           
                }
            break;
            
            case 'mortequipe':
                if( $etatP == 'gagne' ){
                      $html .= '<div class="fin_partie">';
                      $html .= $GLOBALS['htmlMortEquipeGagne'];
                      $html .= '<br /></div>';
                }else{ //if ($etatP == 'perdu')
                      $html .= '<div class="fin_partie">';
                      $html .= $GLOBALS['htmlMortEquipePerdu'];
                      $html .= '<br /></div>';     
                }      

            break;
            
            case 'prise':
                
                if( $etatP == 'gagne' ){
                    $html .= '<div class="fin_partie">';
                    $html .= $GLOBALS['htmlPriseGagne'];
                    $html .= '<br /></div>';
                }else{ //if ($etatP == 'perdu')
                    $html .= '<div class="fin_partie">';
                    $html .= $GLOBALS['htmlPrisePerdu'];
                    $html .= '<br /></div>';            
                }      

            break;
            */
            default ://dans tous les autres cas (bug, parties indéterminées ...)
                if( $etatP == 'gagne' ){
                    $html .= '<div class="fin_partie">';
                    $html .= $htmlAutreGagne;
                    $html .= '<br /></div>';
                    $_SESSION['tuto']=0;
                }else{ //if ($etatP == 'perdu')
                    $html .= '<div class="fin_partie">';
                    $html .= $htmlAutrePerdu;
                    $html .= '<br /></div>';
                    $_SESSION['tuto']=0;                   
                }
            break;
        }


        $html .= '<br /><br /><div align="center" >&nbsp;<button class="search" type="submit" title="Continuer" '.onClick('data.php?p=finir','contenu').' ></button><br /><br /><br /><br /><br />';
            $html .= '<img src="./skin/original/design/header4.png" alt="" /></div>';
        $html .= '</div>
                      </div>';

        echo $html;
        exit();
    }
	
    function newMap($population_mine){
        $x=1;
        $y=1;
        $x_max=$this->tailleX;
        $y_max=$this->tailleY;

        /* Coef du décor */
        $arbre=40;
        $meka=25;

        $souche=5;
        $cratere=5;
        $squelete=1;
        //$population_mine = 25; // % De mine 

        $mapDB = prefixMapPartie($this->type).$this->id;


        $GLOBALS['db']->query ("CREATE TABLE IF NOT EXISTS $mapDB (
                                `X` int(11) NOT NULL,
                                `Y` int(11) NOT NULL,
                                `mvt` smallint(6) NOT NULL,
                                `type` varchar(30) character set latin1 NOT NULL,
                                `object` blob NOT NULL,
                                `param2` int(11) NOT NULL,
                                `param3` int(11) NOT NULL,
                                `param4` int(11) NOT NULL,
                                KEY `X` (`X`,`Y`)
                               ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
                              ") ;

        for ($x=1;$x<=$x_max;$x++){
         for ($y=1;$y<=$y_max;$y++){
           $rand=rand(0,100);
            $type=$x."_".$y;
            $mvt=0;
            $vide="";
            $GLOBALS['db']->query ('INSERT INTO '.$mapDB.' VALUES("'.$x.'","'.$y.'","'.$mvt.'","'.$type.'","'.$vide.'","'.$vide.'","'.$vide.'","'.$vide.'")') or die (mysql_error()) ;
          }
        }


        if($population_mine != 0)//si on veut des mines sur la map
            for($i=0;$i<$this->tailleX;$i++){
              for($j=0;$j<$this->tailleY;$j++){
                if(rand(0,100) < $population_mine){
                  $c= new CaseObject($i,$j,prefixMapPartie($this->type).$this->id);
                  $c->ajouterMine();
                  $c->save();
                }
              }
            }
    }
}
?>
