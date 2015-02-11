<?php
class Alliance{
    var $id; 
    var $tag;
    var $nom; 
    var $descriptionI; //description Interne
    var $descriptionE; //description Externe
    var $droit =array(); // droits des joueurs array(driut=>array(idDesJoueurQuiOntSeDroit))
    			//total => control total
    			//membre => admission/suppression de membre
    			//rang =>rang des joueurs
    			//description => modifier la description
    			//si modification modifier dans supprMembre
    var $rang = array();//tableau associatif que sert a donner des infos
    										//array('fondateur'=>idDuJoueur,'diplo'=>idDuJoueur ...)
		var $demande = array(); // tableau des ids des joueur qui on fait une demande d'adhésion
    var $jMax; // nombre de joueur maximum dans l'alliance
    var $log=array();

	function load($id){
		$sql = $GLOBALS["db"]->query('SELECT * FROM j_alliance WHERE id = '.$id);
		$donnees = mysql_fetch_array($sql);
		            
      $this->id = $donnees['id'];
      $this->tag = stripslashes($donnees['tag']);
      $this->nom = stripslashes($donnees['nom']);
      $this->descriptionI = stripslashes($donnees['descriptionI']);
      $this->descriptionE = stripslashes($donnees['descriptionE']);
      $this->droit = unserialize($donnees['droit']);
      $this->rang = unserialize($donnees['rang']);
      $this->demande = unserialize($donnees['demande']);
      $this->jMax = $donnees['maxJoueur'];
      $this->log = unserialize($donnees['log']);
      $this->pimpMyDesc();
	}
	
	function save(){
      $sql = "UPDATE j_alliance SET
      tag = '".addslashes($this->tag)."',
      nom = '".addslashes($this->nom)."',
      descriptionI = '".addslashes($this->descriptionI)."',
      descriptionE = '".addslashes($this->descriptionE)."',
      droit = '".serialize($this->droit)."',
      rang = '".serialize($this->rang)."',
      demande = '".serialize($this->demande)."',
      jMax = '".$this->jMax."',
      log = '".serialize($this->log)."'
      WHERE `id` = '".$this->id."' ;";
      
      $GLOBALS['db']->query($sql);
  }
  
  function pimpMyDesc(){
  
  #BEGI# SRC #ENDI#
  //<img src=" "/>
    $desc = preg_replace('/#IMG#/','<img src="',$this->descriptionI);// <IMG>
    $desc = preg_replace('/#CIMG#/','" />',$desc);
    $this->descriptionItuned = $desc;
    
    $desc = preg_replace('/#CENTER#/','<center>',$desc);// <IMG>
    $desc = preg_replace('/#CCENTER#/','</center>',$desc);
    $this->descriptionItuned = $desc;
    
    $desc = preg_replace('/#IMG#/','<img src="',$this->descriptionE);// <IMG>
    $desc = preg_replace('/#CIMG#/','" />',$desc);
    $this->descriptionEtuned = $desc;
    
    $desc = preg_replace('/#CENTER#/','<center>',$desc);// <IMG>
    $desc = preg_replace('/#CCENTER#/','</center>',$desc);
    $this->descriptionEtuned = $desc;
  }
	
	function insert($tag,$nom,$descrI,$descrE,$idJoueur)
  {  

    $droit =serialize(array('total'=>array($idJoueur),'membre'=>array(),'rang'=>array(),'description'=>array()));
    $rang = serialize(array());
    $demande = serialize(array());
    $log =serialize(array(time()=>'création'));
    $jMax=10;
    $sql = "INSERT INTO j_alliance(tag,nom,descriptionI,descriptionE,droit,rang,demande,jMax,log)
                          VALUES('".$tag."','".$nom."','".$descrI."','".$descrE."','".$droit."','".$rang."','".$demande."','".$jMax."','".$log."')";
                  
    $GLOBALS['db']->query($sql);  
    $cpt=$GLOBALS['db']->query("SELECT LAST_INSERT_ID() as nb FROM j_alliance " );
    $compt = mysql_fetch_object($cpt);
    return $compt->nb;
  }
	
	function ajoutDemande($idJ){
	   $key = in_array($idJ, $this->demande);
	   if(!$key){
	     $nb=count($this->demande);
	     $this->demande[$nb+1]=$idJ;
	     return true;
	   }
	   else
	     return false;
	}
	
	function supprDemande($id){
	  $tab=array();
	  $n=0;
	  if(is_array($this->demande))
    foreach($this->demande as $d){
      if($d != $id){
        $tab[$n]=$d;
        $n++;
        //echo 'test';
      }
      //echo $d.' r '.$id;
    }
    //print_r($tab);
    $this->demande=$tab;
	}
	
	function supprAllDemande($idJ){
    $allys = Alliance::listeAlliance();
    if(is_array($allys))
    foreach ( $allys as $id){        
      $ally = new Alliance();
      $ally->load($id);
      $ally->supprDemande($idJ);
      $ally->save();
    }
    $this->supprDemande($idJ);
	}

	function accepterDemande($id){
    $joueur= new Joueur();
    $joueur->loadSimple($id);
    $return = $joueur->changerAlliance($id,$this->id,$this->nom,$this->tag);
      if($return == 0){
        $joueur->guilde = $this->id;
        $joueur->alliance = $this->id;
        $joueur->save();
        $this->supprAllDemande($id);
        $this->log[time()] = $joueur->login .' nous a rejoint';
      }else{
        echo 'Impossible d\'accepter pour le moment :'.$return;
      }
	}
	
	function enCourDemande($id){
	   $key = in_array($id, $this->demande);
	   if(!$key)
	     return false;	   
	   else
	     return true;	 
	}
	
	function supprMembre($id){
    $joueur= new Joueur();
    $joueur->loadSimple($id);
      $return = $joueur->changerAlliance($id,0,"","");
      if($return == 0){
        $joueur->guilde = 0;
        $joueur->save();   	
    	 if($this->aLeDroit('total',$id))//on doit sortir le droit
          $this->inverserDroit('total',$id);
    	 if($this->aLeDroit('membre',$id))//on doit sortir le droit
          $this->inverserDroit('membre',$id);
    	 if($this->aLeDroit('rang',$id))//on doit sortir le droit
          $this->inverserDroit('rang',$id);
    	 if($this->aLeDroit('description',$id))//on doit sortir le droit
          $this->inverserDroit('description',$id);
        $this->supprRang($id);
        $this->log[time()] = $joueur->login .' nous a quitté';	
      }else{
        echo 'Changement d\'alliance impossible :'.$return;
      }
  }
	
  function listeAlliance(){
    $sql = $GLOBALS["db"]->query('SELECT * FROM j_alliance ');
    while($donnees = mysql_fetch_array($sql)){
      $tab[$donnees['id']]=$donnees['id'];
    }
  return $tab;
  }
  
  function listeMembre(){
    $sql = $GLOBALS["db"]->query('SELECT id FROM j_compte WHERE guilde='.$this->id);
    $i=0;
    while($donnees = mysql_fetch_array($sql)){
      $tab[$i]=$donnees['id'];
      $i++;
    }
  return $tab;
  }
  
  function nbMembres()
  {
    $sql = $GLOBALS["db"]->query('SELECT count(*) AS nbMembres FROM j_compte WHERE guilde='.$this->id);
    $donnees = mysql_fetch_array($sql);
    return $donnees['nbMembres'];
  }
  
  function nomLibre($tag,$nom){//vérifi si le tag et le nom n'est pas déjà utilisé
    $sql = $GLOBALS["db"]->query('SELECT * FROM j_alliance WHERE tag = "'.$tag.'" OR nom ="'.$nom.'"');
		$donnees = mysql_fetch_array($sql);
		if(isset($donnees['id']))
		  return false;
		return true;  
  }
  
  function droitSufisant($type,$idJoueur){
  	$tab=$this->droit['total'];
  	if(is_array($tab) && in_array($idJoueur,$tab))
  		return true;
		
		$tab=$this->droit[$type];
  	if(is_array($tab) && in_array($idJoueur,$tab))
  		return true;
		return false;
	}

	function aLeDroit($type,$id){
	   return (is_array($this->droit[$type]) && in_array($id,$this->droit[$type]));
	}
	
	function inverserDroit($type,$id){
	 if($this->aLeDroit($type,$id)){//on doit sortir le droit
	   $key = array_search($id, $this->droit[$type]);
     array_splice($this->droit[$type], $key, 1); 
	 }else{//on doit ajouter le droit
	    if(is_array($this->droit))
	     array_push($this->droit[$type], $id);	 
      else
        $this->droit[$type]=$id;
	 }	
	}
	
  function ajoutRang($type,$id){
     $this->rang[$id]=$type;
  }
  
  function supprRang($id){
    $keys=array_keys($this->rang);
    if(is_array($keys))
    foreach($keys as $key ){
      if($id != $key)
        $sort[$key]=$this->rang[$key];
    }
    $this->rang=$sort;
  }
  
  
   
	 
	
}
