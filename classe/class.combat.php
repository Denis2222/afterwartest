<?php
class Combat{
	var $garnisons; // Array (id_hero,ally,garnison)
	var $log; // Texte chronologique

	var $duree; // Aproximative en fonction des garnisons actuelle
	
  var $id_attaquant;
  var $type_attaquant;
  var $id_defenseur;
  var $type_defenseur;

  // Si c'est une ville on stoke le nombre de paysans
  var $nb_paysans_def;

  var $ally_att;
	var $ally_def;
	
	var $beginTime;
	var $lastTime;
	
var $coef_perte = 1;
var $tempsCalcul = 10;// Plus c elevé plus les dégats seront faible et plus le combat dureras en nb round
	
	var $Att; // Puissance offenssive
	var $Def; // Puissance defensive
	
	var $etat = 0; // 0 en cour , 1 Terminé
	
	function debutCombat($id_hero_Att,$type_att,$ally_Att,$garnison_Att,$id_hero_Def,$type_def,$ally_Def,$garnison_Def,$param_att=0){ // Att de l'ally AttAlly attaque Def de l'ally DefAlly


    //Fix Alliance
		if($ally_Def == ""){
		  $ally_Def=0;
		}
		if($ally_Att == ""){
		  $ally_Att=0;
		}
    if($ally_Att == $ally_Def){
			$ally_Att=1;
			$ally_Def=2;
		}
		$this->param_att = $param_att; // Type d'attaque , pillage ,destruction, vol
		
		$this->ally_att = $ally_Att;
		$this->ally_def = $ally_Def;
		
		$this->id_attaquant= $id_hero_Att;
    $this->type_attaquant = $type_att;
    
    $this->id_defenseur = $id_hero_Def;
    $this->type_defenseur = $type_def;

		
		
		$this->ajouterGarnison($id_hero_Att,$type_att,$ally_Att,$garnison_Att);
		$this->ajouterGarnison($id_hero_Def,$type_def,$ally_Def,$garnison_Def);
		
		if($type_def == "v"){
		  $ville = new Ville();
		  $ville->load($id_hero_Def);
		  $nb_paysans = 0;
      $nb_paysans += $ville->bucheron;
      $nb_paysans += $ville->mineur;
	    $nb_paysans += $ville->paysans;
	    $this->nb_paysans_def = $nb_paysans;
	    $this->garnisons[$type_def.'_'.$id_hero_Def]['garnison']->AjoutUnite(0,$this->nb_paysans_def);
		}
		//echo 'NB PAYSANS:'.$this->nb_paysans_def.'|';
		
		$time = time();
		$this->log("DEBUT COMBAT !"); 
		$this->beginTime = $time;
		$this->lastTime = $time-$this->tempsCalcul;
	}
	
	function ajouterGarnison($id_hero,$type,$ally,$garnison){



		if($ally == $this->ally_att OR $ally == $this->ally_def){

		  if($type == "v"){
		    $chose = new Ville();
		    $chose->load($id_hero);
		  }
		  if($type == "h"){
		    $chose = new Hero();
		    $chose->load($id_hero);
		  }
		  
		  $this->garnisons[$type.'_'.$id_hero]['nom']=$chose->nom;
			$this->garnisons[$type.'_'.$id_hero]['ally']=$ally;
			$this->garnisons[$type.'_'.$id_hero]['garnison']=$garnison;
			$this->garnisons[$type.'_'.$id_hero]['type']=$type;
			$this->garnisons[$type.'_'.$id_hero]['id']=$id_hero;
			
			if($ally == $this->ally_att){
				$this->log("Un héro vient de se joindre aux attaquants");
			}else{
				$this->log("Un héro vient de se joindre aux defenseurs");
			}
		}else{
		}
	}
	
	function calculCombat(){
$this->coef_perte = 1;
$this->tempsCalcul = 10;

	if($this->etat == 0){

  		$time = time();
  		if($this->lastTime <= $time-$this->tempsCalcul){ // Le calcul peut commencer 
    			$tempsEcoulee = $time-$this->lastTime;
    			$Att=0; // Puissance offenssive de l'attaquant;
    			$Def=0; // Puissance defensive de l'attaquant;
          if(is_array($this->garnisons))
    			foreach($this->garnisons as $key => $value){ // On calcule les puissances des armées en jeux
    				if($value['ally'] == $this->ally_att){ // On tien un attaquant
    					$value['garnison']->genererStats(); // On regénere les stats histoire d'être à jour
    					$Att += $value['garnison']->stats['att'];
    				}elseif($value['ally'] == $this->ally_def){ //On tien un defenseur
    					$value['garnison']->genererStats(); 
    					$Def += $value['garnison']->stats['def'];
    				}
    			}
    			$this->Att = $Att;
    			$this->Def = $Def;
    			if($this->Att > $this->Def+10){
    				$this->log("Les attaquants partent gagnant att:".$this->Att." def:".$this->Def." !"); 
    			}elseif($this->Def > $this->Att+10){
    				$this->log("Les defenseurs partent gagnant att:".$this->Att." def:".$this->Def." !"); 
    			}else{
    				$this->log("Le combat vient de commencer les deux armées se valent ça va être un massacre att:".$this->Att." def:".$this->Def." !");
    			}
    			$duree = $tempsEcoulee/$this->tempsCalcul;
    			$this->log("round ecoulee :".$duree." ");
    			$degat_att = ($this->Def/$this->coef_perte)*$duree;
    			$degat_def = ($this->Att/$this->coef_perte)*$duree;
    			$this->log("dA:".$degat_att." dD:".$degat_def." ;");
    			
    			$nb = $this->compterGarnisonDebout();
    			$nb_att = $nb[0]; // Nombre de garnison en attaque debout
    			$nb_def = $nb[1]; // Nombre de garnison en defense debout
    			 
           if($nb_att == 0){
    			   $this->etat = 1;
    			   return 1;
    			}
    			if($nb_def == 0){
    			   $this->etat = 1;
    			   return 1;
    			}
    			
    			$degat_att = $degat_att/$nb_att; // division des dégats recu sur les attaquant toujours vivant
    			$degat_def = $degat_def/$nb_def; // division des dégats recu sur les defenseur toujours vivant
    			if(is_array($this->garnisons))
          foreach($this->garnisons as $key => $value){ // On insére les dégats perdus dans les garnisons
    				if($value['ally'] == $this->ally_att){ // On tien un attaquant
    				  if($this->garnisons[$key]['garnison']->genererStats()==0){
    				    $this->garnisons[$key]['garnison']->degat_att+= $degat_att;
    					  $this->garnisons[$key]['garnison']->genererStats(); 
    					}
    				}elseif($value['ally'] == $this->ally_def){ //On tien un defenseur
    				  if($this->garnisons[$key]['garnison']->genererStats()==0){
      				  $this->garnisons[$key]['garnison']->degat_def+= $degat_def;
      					$this->garnisons[$key]['garnison']->genererStats(); 
    					}
    				}
    			}
    			$this->lastTime = $time;
    			
    			$nb = $this->compterGarnisonDebout();
    			$nb_att = $nb[0]; // Nombre de garnison en attaque debout
    			$nb_def = $nb[1]; // Nombre de garnison en defense debout
    			
    			if($nb_att == 0){
    			   $this->etat = 1;
    			   return 1;
    			}
    			if($nb_def == 0){
    			   $this->etat = 1;
    			   return 1;
    			}
  		}
		}
		return 0;
	}
	
	function compterGarnisonDebout(){
	
			$nb_def = 0; // nb de garnison en def en vie
			$nb_att = 0; // nb de garnison en att en vie
			if(is_array($this->garnisons))
			foreach($this->garnisons as $key => $value){ 
				if($value['ally'] == $this->ally_att){ // On tien un attaquant

          if($this->garnisons[$key]['garnison']->genererStats()==0){ // Si encore en vie
				    $nb_att++; // Il peut encaisser des dégats
				     $this->garnisons[$key]['garnison']->genererStats();
				  }
				}elseif($value['ally'] == $this->ally_def){ //On tien un defenseur

            if($this->garnisons[$key]['garnison']->genererStats()==0){ // Si encore en vie
				      $nb_def++; // Il peut encaisser encore
				      
				      $this->garnisons[$key]['garnison']->genererStats();
				    }
				}
			}
			
			
		$table[0] = $nb_att;
		$table[1] = $nb_def;
	
	return $table;
	}
	

	
	function rapportCombat($addon = 0){

	 $html = "";  	  
   
   	$html.= '<table class="message"><tr class="tr_message">';
  $html.= "<td align=\"center\"> ATTAQUANT </td></tr>";
  $html.= "</table>";
        	  		   	         
	 if(is_array($this->garnisons))
	 foreach($this->garnisons as $key => $value){
	 
    if($value['ally'] == $this->ally_att){

	   if($value['type'] == "v"){

	     $raport[$key] = "";
  	   $ville = new Ville();

    	 $ville->load($this->garnisons[$key]['id']);
    	 $joueur=new Joueur();

    	 $joueur->load($ville->idCompte);
    	 //TH
       $raport[$key].= '<table class="message">';
    	 $raport[$key].= '<tr class="tr_message">';
    	 $raport[$key].= "<td colspan=".count($GLOBALS['unite']).">Ville : ".$ville->nom." de ".$joueur->login."</td><td></td>";
    	 $raport[$key].= "</tr>";
    	 // UNITE IMAGE
       $raport[$key].= '<tr>';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".icoUnite($cle,20)."</td>";
    	 }
    	 $raport[$key].= "<td></td>";
       $raport[$key].= "</tr>";
       
       // UNITE DEBUT
       $raport[$key].= '<tr class="tr_noir">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".rap0($ville->garnison->unite[$cle])."</td>";
    	 }
    	 $raport[$key].= "<td> Soldats </td>";
       $raport[$key].= "</tr>";
       
       // UNITE FIN
       $raport[$key].= '<tr class="tr_gris">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".($ville->garnison->unite[$cle]-$this->garnisons['v_'.$ville->id]['garnison']->unite[$cle])."</td>";
    	 }
    	 $raport[$key].= "<td> Pertes </td>";
       $raport[$key].= "</tr>";
    	 
    	 $raport[$key].= "</table>";
    	 
    	 $html.= $raport[$key];
	   }
	   if($value['type'] == "h"){

	     $raport[$key] = "";
  	   $hero = new Hero();
    	 $hero->load($this->garnisons[$key]['id']);
    	 $joueur=new Joueur();

    	 $joueur->load($hero->idCompte);
    	     	 	   	   	  //echo 'lol'; 
    	 //TH
       $raport[$key].= '<table class="message">';
    	 $raport[$key].= '<tr class="tr_message">';
    	 $raport[$key].= "<td colspan=".count($GLOBALS['unite']).">Héro ".$hero->nom." de ".$joueur->login."</td><td></td>";
    	 $raport[$key].= "</tr>";
    	 // UNITE IMAGE
       $raport[$key].= '<tr>';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".icoUnite($cle,20)."</td>";
    	 }
    	 $raport[$key].= "<td></td>";
       $raport[$key].= "</tr>";
       
       // UNITE DEBUT
       $raport[$key].= '<tr class="tr_noir">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".rap0($hero->garnison->unite[$cle])."</td>";
    	 }
    	 $raport[$key].= "<td> Soldats </td>";
       $raport[$key].= "</tr>";
       
       // UNITE FIN
       $raport[$key].= '<tr class="tr_gris">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".($hero->garnison->unite[$cle]-$this->garnisons['h_'.$hero->id]['garnison']->unite[$cle])."</td>"; //
    	 }
    	 $raport[$key].= "<td> Pertes </td>";
       $raport[$key].= "</tr>";
    	 
    	 $raport[$key].= "</table>";
    	 
    	 $html.= $raport[$key];
  	 }
	 }
	}
	// FIN RAPPORT ATTAQUANT
	
	$html.= '<table class="message"><tr class="tr_message">';
  $html.= "<td align=\"center\">VS</td></tr>";
  $html.= "</table>";
	
	//DEBUT RAPPORT DEF
	
		$html.= '<table class="message"><tr class="tr_message">';
  $html.= "<td align=\"center\">DEFENSEUR</td></tr>";
  $html.= "</table>";

	
		 if(is_array($this->garnisons))
	 foreach($this->garnisons as $key => $value){
	 
    if($value['ally'] == $this->ally_def){

	   if($value['type'] == "v"){

	     $raport[$key] = "";
  	   $ville = new Ville();

    	 $ville->load($this->garnisons[$key]['id']);
    	 $joueur=new Joueur();

    	 $joueur->load($ville->idCompte);
    	 //TH
       $raport[$key].= '<table class="message">';
    	 $raport[$key].= '<tr class="tr_message">';
    	 $raport[$key].= "<td colspan=".count($GLOBALS['unite']).">Ville : ".$ville->nom." de ".$joueur->login."</td><td></td>";
    	 $raport[$key].= "</tr>";
    	 // UNITE IMAGE
       $raport[$key].= '<tr>';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".icoUnite($cle,20)."</td>";
    	 }
    	 $raport[$key].= "<td></td>";
       $raport[$key].= "</tr>";
       
       // UNITE DEBUT
       $raport[$key].= '<tr class="tr_noir">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".rap0($ville->garnison->unite[$cle])."</td>";
    	 }
    	 $raport[$key].= "<td> Soldats </td>";
       $raport[$key].= "</tr>";
       
       // UNITE FIN
       $raport[$key].= '<tr class="tr_gris">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".($ville->garnison->unite[$cle]-$this->garnisons['v_'.$ville->id]['garnison']->unite[$cle])."</td>";
    	 }
    	 $raport[$key].= "<td> Pertes </td>";
       $raport[$key].= "</tr>";
    	 
    	 $raport[$key].= "</table>";
    	 
    	 $html.= $raport[$key];
	   }
	   if($value['type'] == "h"){

	     $raport[$key] = "";
  	   $hero = new Hero();
    	 $hero->load($this->garnisons[$key]['id']);
    	 $joueur=new Joueur();

    	 $joueur->load($hero->idCompte);
    	     	 	   	   	  //echo 'lol'; 
    	 //TH
       $raport[$key].= '<table class="message">';
    	 $raport[$key].= '<tr class="tr_message">';
    	 $raport[$key].= "<td colspan=".count($GLOBALS['unite']).">Héro ".$hero->nom." de ".$joueur->login."</td><td></td>";
    	 $raport[$key].= "</tr>";
    	 // UNITE IMAGE
       $raport[$key].= '<tr>';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".icoUnite($cle,20)."</td>";
    	 }
    	 $raport[$key].= "<td></td>";
       $raport[$key].= "</tr>";
       
       // UNITE DEBUT
       $raport[$key].= '<tr class="tr_noir">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".rap0($hero->garnison->unite[$cle])."</td>";
    	 }
    	 $raport[$key].= "<td> Soldats </td>";
       $raport[$key].= "</tr>";
       
       // UNITE FIN
       $raport[$key].= '<tr class="tr_gris">';
       if(is_array($GLOBALS['unite']))
       foreach($GLOBALS['unite'] as $cle => $unite){
    	 $raport[$key].= "<td>".($hero->garnison->unite[$cle]-$this->garnisons['h_'.$hero->id]['garnison']->unite[$cle])."</td>"; //
    	 }
    	 $raport[$key].= "<td> Pertes </td>";
       $raport[$key].= "</tr>";
    	 
    	 $raport[$key].= "</table>";
    	 
    	 $html.= $raport[$key];

  	 }
	 }
	}
	
	
	
	
	// FIN RAPPORT DEF
	
 if(is_array($addon)){
  if(count($addon) > 0){
      foreach($addon as $key => $info){
        $addon_info.= '<table class="message">';
    	  $addon_info.= '<tr class="tr_message">';
    	  $addon_info.= '<td align="left">'.$info.'</td>';
    	  $addon_info.= '</tr>';
    	  $addon_info.= '</table>';
      }
  }
 }
 
 
 
 
 
 
 
 
 $html.=$addon_info;

	return $html;
	}
	
	function afficherLog(){
		$html = "";
		if(is_array($this->log)){
  		foreach($this->log as $key => $value){
  			$html.= "<br />".date('H-i-s',$key)." => ".$value." <br />";
  		}
		}
		return $html;
	}
	
	function log($texte){
		$time = time();
		if(isset($this->log[$time])){
			$this->log[$time].= '
       =>'.$texte;
		}else{
			$this->log[$time] = $texte;
		}
	}
	
	function afficherResume(){
		$html = "";
    $html.= "att:".$this->Att." def:".$this->Def;
		return $html;
	}
	
	function afficherCombat(){
	 //print_r($this);
	$nb_colonne = (count($GLOBALS['unite'])+1);
	 $html = "";
	 $html.='<table class="message">

   <tr class="tr_message" >
   <td colspan="'.($nb_colonne-4).'">
      Combat en cour depuis : '.temp_seconde(time()-$this->beginTime).' 
   </td>
   <td align="right" colspan="4">
      Prochain Rapport : <span id="timer'.$_SESSION['timer']++.'">'.temp_seconde(($this->lastTime+$this->tempsCalcul)-time()).'</span>
   </td>
   </tr>';
   $html.='<tr><td>Garnison</td>';
   if(is_array($GLOBALS['unite']))
	 foreach($GLOBALS['unite'] as $key => $value){
	   $html.= '<td>'.icoUnite($key).'</td>';
	 }
	 $html.= '</tr>';
	 $html.='<tr class="tr_message"><td colspan="'.(count($GLOBALS['unite'])+1).'">Attaquant</td></tr>';
if(is_array($this->garnisons))
foreach($this->garnisons as $key_garnison => $value_garnison){
				if($value_garnison['ally'] == $this->ally_att){
				  	$html.='<tr><td>'.$value_garnison['nom'].'D:'.round($value_garnison['garnison']->degat_att).'</td>';	
				  	if(is_array($GLOBALS['unite']))
				  	foreach($GLOBALS['unite'] as $key_unite => $value_unite){
				  	   $html.='<td>'.rap0($this->garnisons[$key_garnison]['garnison']->unite[$key_unite]).'</td>';
				  	}
				  	$html.='</tr>';	
				}
}
$html.='<tr class="tr_message"><td colspan="'.(count($GLOBALS['unite'])+1).'">Défenseur</td></tr>';
if(is_array($this->garnisons))
foreach($this->garnisons as $key_garnison => $value_garnison){
				if($value_garnison['ally'] == $this->ally_def){
				  	$html.='<tr><td>'.$value_garnison['nom'].'D:'.round($value_garnison['garnison']->degat_def).'</td>';	
				  	if(is_array($GLOBALS['unite']))
				  	foreach($GLOBALS['unite'] as $key_unite => $value_unite){
				  	   $html.='<td>'.rap0($this->garnisons[$key_garnison]['garnison']->unite[$key_unite]).'</td>';
				  	}
				  	$html.='</tr>';	
				}
}
$html.='<tr class="tr_message"><td colspan="'.(count($GLOBALS['unite'])+1).'">Force Attaquant : '.$this->Att.' Force Defenseur : '.$this->Def.'</td></tr>';
//Force Attaquant : '.$this->Att.' Force Defenseur : '.$this->Def.'
   $html.='</table>';
//$this->garnisons[1]['garnison']->unite[5]++;

   
  //$_SESSION['combattest'] = serialize($this);
	return $html;
	}
	
	
	function searchAlly($id,$type){
    	if(isset($this->garnisons[$type.'_'.$id]['ally'])){
    	   $ally = $this->garnisons[$type.'_'.$id]['ally'];
    	   return $ally;
    	}else{
    	   echo 'PAS BONNE ALLY';
    	}
     
	}
}
?>