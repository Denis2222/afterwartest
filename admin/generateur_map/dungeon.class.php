<?php
class Dungeon {
	//Dungeon Array     $map[X][Y]
	var $map;  
	var $nom;
	
	//Dungeon Size
	var $width;
	var $height;
	
	//Current Cell
	var $ccX; 
	var $ccY;

	var $nb_non_visite;
	
	var $salle;
	var $nb_salle;
	
	var $nb_porte=0;
	var $porte;
	
	var $nb_teleporteur=0;
	var $teleporteur;
	
function init($width,$height){  // Initialisation du dungeon
    
      $this->width=$width;
      $this->height=$height;
      for($x=0;$x<=$this->width-1;$x++){
          for($y=0;$y<=$this->height-1;$y++){
              $this->map[$x][$y][1]=0; // 0 c'est à dire une porte(mur)
              $this->map[$x][$y][2]=0;
              $this->map[$x][$y][3]=0;
              $this->map[$x][$y][4]=0;
              $this->map[$x][$y]['v']=1; // 1: non visite, 2:visite, 3: visite plus de solution
			  $this->map[$x][$y]['c']=0;
			  $this->map[$x][$y]['s']=0;
			  $this->map[$x][$y]['m']=0; //monstre
			  $this->map[$x][$y]['a']=0; //action
			  $this->map[$x][$y]['p']=0; //prerequis
			  $this->map[$x][$y]['in']=0; //prerequis
			  
			  
			unset($this->porte);
			$this->nb_porte=0;
			
			unset($this->salle);
			$this->nb_salle=0;
			  
          }
      }
      $this->nb_non_visite =$this->width * $this->height;
	  $this->nb_salle=0;
	    
  }
  
function premiere_cellule($X,$Y) {   // On initialise la currentCell de départ
      if($X<0){
          $this->ccX=mt_rand(0,$this->width-1);
    	  $this->ccY=mt_rand(0,$this->height-1);
      }else{
          $this->ccX=$X-1;
    	  $this->ccY=$Y-1;    
      }
      $this->marquer_visite($this->ccX,$this->ccY);
  }
  
function marquer_visite($VX,$VY) { // Marquer la case comme visité
  
  	$this->map[$VX][$VY]['v']=2;
	$this->nb_non_visite--;
}
  
function choisir_direction($direction_precedente) { // Choisir direction de la boucle générer labyrinthe
  // renvoi 0 si invalide, 1 pour N,2 pour S, 3 pour E, 4 pour O
    //echo 'choix direction<br>';
    $array[0]=1;
	$array[1]=2;
	$array[2]=3;
	$array[3]=4;
	shuffle($array);
	//print_r($array);
	if($direction_precedente <> 0) {
		$piece=mt_rand(0,100);
		if($piece > RANDOMNESS) {
		   // on conserve la direction
		   $pos_cour=0;
		   $tmp=$array[$pos_cour];
		   $array[$pos_cour]=$direction_precedente;
		   $fini=0;
		   $pos_cour++;
		   while($fini=0 and $pos_cour<=3) {
		     if($array[$pos_cour]==$direction_precente) {
			    $fini=1;
				$array[$pos_cour]=$tmp;
			 }
			 $pos_cour++;
		   }
		}
	}
	$resultat=0;
	$nb_dir_teste = 1;
	$trouve =0;
	while($trouve==0 and $nb_dir_teste<=4) {
	  // Condition 1
	  $cond1=0;
	  $cond2=0;
	  //echo 'dir teste :'.$array[$nb_dir_teste-1].'<br>';
	  switch($array[$nb_dir_teste-1]) {
	  
	  	case 1:
					if(isset($this->map[$this->ccX][$this->ccY-1])) {
						$cond1=1; 
						if($this->map[$this->ccX][$this->ccY-1]['v']==1) $cond2=1;
					}
					break;
		
		case 2:
					if(isset($this->map[$this->ccX][$this->ccY+1])) {
						$cond1=1; 
						if($this->map[$this->ccX][$this->ccY+1]['v']==1) $cond2=1;
					
					}
					break;
	
		case 3:
					if(isset($this->map[$this->ccX+1][$this->ccY])) {
						$cond1=1; 
						if($this->map[$this->ccX+1][$this->ccY]['v']==1) $cond2=1;
					
					}
					break;
		case 4:
					if(isset($this->map[$this->ccX-1][$this->ccY])) {
						$cond1=1; 
						if($this->map[$this->ccX-1][$this->ccY]['v']==1) $cond2=1;
					
					}
					break;	
			
	  }
	  if($cond1==1 && $cond2==1) {
	    $trouve=1;
		$resultat=$array[$nb_dir_teste-1];
	  }
	  $nb_dir_teste++;
	
	}
	
	//echo 'direction choisi :'.$resultat.'<br>';
	return($resultat);	
  }
  
function casser_mur($dir) { // casser le mur depuis la Current Cell dans la direction indiqué
  
  	switch($dir) {
	
		case 1 :
		            $this->map[$this->ccX][$this->ccY][1]=1;
					$this->ccY=$this->ccY-1;
					$this->map[$this->ccX][$this->ccY][2]=1;
					break;
		case 2 :
					$this->map[$this->ccX][$this->ccY][2]=1;
					$this->ccY=$this->ccY+1;
					$this->map[$this->ccX][$this->ccY][1]=1;
					break;
		case 3 :
					$this->map[$this->ccX][$this->ccY][3]=1;
					$this->ccX=$this->ccX+1;
					$this->map[$this->ccX][$this->ccY][4]=1;
					break;
		case 4 :
					$this->map[$this->ccX][$this->ccY][4]=1;
					$this->ccX=$this->ccX-1;
					$this->map[$this->ccX][$this->ccY][3]=1;
					break;
					
	}
    
  }
  
function generer_labyrinthe() { // Genere le labyrinthe parfait
  
    $direction = 0;
	
	$nb_pour_tab = 0;
	echo '<table><tr>';
  	while($this->nb_non_visite>0) {
		
	    //echo 'nb non visite :'.$this->nb_non_visite.'<br>'; 
		
		$direction=$this->choisir_direction($direction);
		if($direction==0) {
		
		   $this->map[$this->ccX][$this->ccY]['v']=3;
		   
		   // Aucune direction valide
		   
		   $trouve=0;
		   $testX=0;
		   $testY=0;
		   while($trouve==0) {
		   
		    $testX = mt_rand(0,$this->width-1);
			$testY = mt_rand(0,$this->height-1);
			
			if($this->map[$testX][$testY]['v']==2) $trouve=1;
			
		   }
		   
		   $this->ccX=$testX;
		   $this->ccY=$testY;
		   
		} else {
		$nb_pour_tab ++;
		echo '<td>';
		//$this->afficherdebug();
		echo '</td>';
		if($nb_pour_tab == 5){
			echo '</tr><tr>';
			$nb_pour_tab = 0;
		}
		   // On casse le mur et on repositionne la cellule courante
		   
		   $this->casser_mur($direction);
		   
		   // Marquer visiter si necessaire
		
		   if($this->map[$this->ccX][$this->ccY]['v']==1) {
		   		$this->marquer_visite($this->ccX,$this->ccY);
		   }
		    
		}
		
	}
  	echo '</tr></table>';
  }
  
function recupCase($X,$Y){ // Recupere les infos d'une case
	$case=$this->map[$X][$Y];
    return $case;
}
    
function porte_ouverte($OX,$OY) { // Renvoi le nombre de porte ouverte d'une case
	$nb_ouverte = 0;
	if($OX>=0 AND $OY>=0 AND $OX<=$this->width-1 AND $OY<=$this->height-1){
		
		if($this->map[$OX][$OY][1]==1) $nb_ouverte++;
		if($this->map[$OX][$OY][2]==1) $nb_ouverte++;
		if($this->map[$OX][$OY][3]==1) $nb_ouverte++;
		if($this->map[$OX][$OY][4]==1) $nb_ouverte++;
	}
	return $nb_ouverte;
}

function fermer_tout($OX,$OY) {  // Fermer toute les portes d'une case

    $this->map[$OX][$OY]['c']=0;
	$this->map[$OX][$OY][1]=0;
	if(isset($this->map[$OX][$OY-1])) $this->map[$OX][$OY-1][2]=0;
	$this->map[$OX][$OY][2]=0;
	if(isset($this->map[$OX][$OY+1])) $this->map[$OX][$OY+1][1]=0;
	$this->map[$OX][$OY][3]=0;
	if(isset($this->map[$OX+1][$OY])) $this->map[$OX+1][$OY][4]=0;
	$this->map[$OX][$OY][4]=0;
	if(isset($this->map[$OX-1][$OY])) $this->map[$OX-1][$OY][3]=0;
}

function sparsify() {   // Enlever des couloir 

	$nb_sparse=0;
	while($nb_sparse< SPARSENESS) {
		for($x=0;$x<=$this->width-1;$x++) {
			for($y=0;$y<=$this->height-1;$y++) {
			   $res=$this->porte_ouverte($x,$y);
			   if($res==1) $this->map[$x][$y]['c']=1; else $this->map[$x][$y]['c']=0;
			}
		}
		for($x=0;$x<=$this->width-1;$x++) {
			for($y=0;$y<=$this->height-1;$y++) {
			   if($this->map[$x][$y]['c']==1) $this->fermer_tout($x,$y);
			}
		}

		//$this->afficher();
		$nb_sparse++;
	}


}

function choix_direction2($direction_precedente) {// Choix direction pour la boucle enlever cul de sac
 // renvoi 0 si invalide, 1 pour N,2 pour S, 3 pour E, 4 pour O
    //echo 'choix direction<br>';
    $array[0]=1;
	$array[1]=2;
	$array[2]=3;
	$array[3]=4;
	shuffle($array);
	//print_r($array);
	if($direction_precedente <> 0) {
		$piece=mt_rand(0,100);
		if($piece > RANDOMNESS) {
		   // on conserve la direction
		   $pos_cour=0;
		   $tmp=$array[$pos_cour];
		   $array[$pos_cour]=$direction_precedente;
		   $fini=0;
		   $pos_cour++;
		   while($fini=0 and $pos_cour<=3) {
		     if($array[$pos_cour]==$direction_precente) {
			    $fini=1;
				$array[$pos_cour]=$tmp;
			 }
			 $pos_cour++;
		   }
		}
	}
	$resultat=0;
	$nb_dir_teste = 1;
	$trouve =0;
	while($trouve==0 and $nb_dir_teste<=4) {
	  // Condition 1
	  $cond1=0;
	  
	  //echo 'dir teste :'.$array[$nb_dir_teste-1].'<br>';
	  switch($array[$nb_dir_teste-1]) {
	  
	  	case 1:
					if(isset($this->map[$this->ccX][$this->ccY-1]) and $this->map[$this->ccX][$this->ccY][1]==0) $cond1=1; 
					break;
		
		case 2:
					if(isset($this->map[$this->ccX][$this->ccY+1]) and $this->map[$this->ccX][$this->ccY][2]==0) $cond1=1; 
					break;
	
		case 3:
					if(isset($this->map[$this->ccX+1][$this->ccY]) and $this->map[$this->ccX][$this->ccY][3]==0) $cond1=1; 
					break;
		case 4:
					if(isset($this->map[$this->ccX-1][$this->ccY]) and $this->map[$this->ccX][$this->ccY][4]==0) $cond1=1; 
					break;	
			
	  }
	  if($cond1==1) {
	    $trouve=1;
		$resultat=$array[$nb_dir_teste-1];
	  }
	  $nb_dir_teste++;
	
	}
	
	//echo 'direction choisi :'.$resultat.'<br>';
	return($resultat);	

}

function enlever_cds($x,$y) { // Enlever un cul de sac (continuer le cul de sac jusqu'a un couloir ) lancé par deadends()

	//echo $x.','.$y.' a virer <br>';
	
	$termine=0;
	if($this->porte_ouverte($x,$y) >1) $termine=1;
	$this->ccX=$x;
	$this->ccY=$y;
	$direction=0;
	while($termine==0) {
	
		// choix direction
		
		$direction=$this->choix_direction2($direction);
		//echo 'dir choisi :'.$direction.'<br>';
		
		// tracage
		
		$this->casser_mur($direction);
      				
		// intersection ?
	  
	    if($this->porte_ouverte($this->ccX,$this->ccY) >1) $termine=1;
		
	}
	//$this->afficher();
}

function deadends() {  // Continuer LES culs de sac

	// repere ceux a traiter
	
	for($x=0;$x<=$this->width-1;$x++) {
		for($y=0;$y<=$this->height-1;$y++) {
			$res=$this->porte_ouverte($x,$y);
			if($res==1) {
			   $piece=mt_rand(0,100);
			   if($piece> DEADENDS) {
			   		$this->map[$x][$y]['c']=1;
			   } else $this->map[$x][$y]['c']=0;
			}
		}
	}
	
	// Traitement
	
	for($x=0;$x<=$this->width-1;$x++) {
			for($y=0;$y<=$this->height-1;$y++) {
			   if($this->map[$x][$y]['c']==1) $this->enlever_cds($x,$y);
			}
		}

}

function voisin_couloir($x,$y) { // Renvoi si la case à des couloir autour

    $point=0;
	
	if(isset($this->map[$x][$y-1])) {
	   if($this->porte_ouverte($x,$y-1) >=1) $point++;
	}
	if(isset($this->map[$x][$y+1])) {
	   if($this->porte_ouverte($x,$y+1) >=1) $point++;
	}
	if(isset($this->map[$x-1][$y])) {
	   if($this->porte_ouverte($x-1,$y) >=1) $point++;
	} 
	if(isset($this->map[$x+1][$y])) {
	   if($this->porte_ouverte($x+1,$y) >=1) $point++;
	}

	if(SPECIAL_AOM == 1){
		if($point>1){
			$point = 1;
		}else{
			$point = 100;
		}
	}
	
	return($point);
}

function contact_couloir($x,$y) { // Renvoi si ya un contact avec un couloir ( pour calculer_score() )

	if($this->porte_ouverte($x,$y)>0) return(1000); else return(0);
}

function contact_room($x,$y) { // Renvoi si ya un contact avec une salle 

	if($this->map[$x][$y]['s']>0) return(10000); else return(0);
}

function voisin_room($x,$y) { // Renvoi si ya une salle voisine à la case
 $point=0;
	
	if(isset($this->map[$x][$y-1])) {
	   if($this->map[$x][$y-1]['s'] ==1) $point=$point+100;
	}
	if(isset($this->map[$x][$y+1])) {
	   if($this->map[$x][$y+1]['s'] ==1) $point=$point+100;
	}
	if(isset($this->map[$x-1][$y])) {
	   if($this->map[$x-1][$y]['s'] ==1) $point=$point+100;
	} 
	if(isset($this->map[$x+1][$y])) {
	   if($this->map[$x+1][$y]['s'] ==1) $point=$point+100;
	}
	//echo 'X :'.$x.' Y: '.$y.' voisin :'.$point.'<br>';
	

	return($point);
}

function calculer_score($ox,$oy,$w_salle,$h_salle) { // Calcul du score pour chaque salle 

	$score=0;
	$scorev=0;
	$scorec=0;
	
	for($x=0;$x<=$w_salle-1;$x++) {
		for($y=0;$y<=$h_salle-1;$y++) {
			if(isset($this->map[$ox+$x][$oy+$y])) {
				$score+=$this->voisin_couloir($ox+$x,$oy+$y);
				$score+=$this->contact_couloir($ox+$x,$oy+$y);
				$score+=$this->contact_room($ox+$x,$oy+$y);
				$score+=$this->voisin_room($ox+$x,$oy+$y);
			} else {
			   $score = 1000000000;
			   $y=$h_salle;
			   $x=$w_salle;
			}
		}
	}
    //echo 'X :'.$ox.' Y: '.$oy.' score  :'.$score.'<br>';
	
	return($score);
}

function etude_case_salle($x,$y){ // Pour l'affichage des salles 

$salle[1]=0;
$salle[2]=0;
$salle[3]=0;
$salle[4]=0;

	if(isset($this->map[$x][$y-1])) {
	   if($this->map[$x][$y-1]['s'] ==1) $salle[1]=1;
	}
	if(isset($this->map[$x][$y+1])) {
	   if($this->map[$x][$y+1]['s'] ==1) $salle[2]=1;
	}
	if(isset($this->map[$x+1][$y])) {
	   if($this->map[$x+1][$y]['s'] ==1) $salle[3]=1;
	}
	if(isset($this->map[$x-1][$y])) {
	   if($this->map[$x-1][$y]['s'] ==1) $salle[4]=1;
	}

	return $salle;
}

function creer_porte($xD,$yD,$xA,$yA,$dir){ // Création d'une porte (X Départ Y Départ , X Arrivé Y Arrivé) 
	
	
	if(DIR1DOOR==1){
		if (!isset($this->salle[$this->nb_salle]['door'][$dir])){
			$depart=$this->recupCase($xD,$yD);
			$arrive=$this->recupCase($xA,$yA);
			if($depart['s']==1 AND $arrive['s']==0){
			$this->porte[$this->nb_porte]['salle']=$this->nb_salle;
			$this->porte[$this->nb_porte]['XD']=$xD;
			$this->porte[$this->nb_porte]['YD']=$yD;
			$this->porte[$this->nb_porte]['XA']=$xA;
			$this->porte[$this->nb_porte]['YA']=$yA;
			$this->porte[$this->nb_porte]['dir']=$dir;
			$this->salle[$this->nb_salle]['door'][$dir]=1;
			//echo 'XD:'.$xD.' YD:'.$yD.'--> XA:'.$xA.' YA:'.$yA.' Numporte:'.$this->nb_porte.' numsalle:'.$this->nb_salle.'<br />';
			//print_r($this->map[$xD][$yD]);
			//echo '<br/>';
			//print_r($this->map[$xA][$yA]);
			//echo '<br/>';
			$this->nb_porte++;
			}
		}

	}else{
		if(DOOR>=mt_rand(1,100)){
		$this->porte[$this->nb_porte]['salle']=$this->nb_salle;
		$this->porte[$this->nb_porte]['XD']=$xD;
		$this->porte[$this->nb_porte]['YD']=$yD;
		$this->porte[$this->nb_porte]['XA']=$xA;
		$this->porte[$this->nb_porte]['YA']=$yA;
		$this->porte[$this->nb_porte]['dir']=$dir;
		
		$this->salle[$this->nb_salle]['door'][$dir]=1;
		//echo 'XD:'.$xD.' YD:'.$yD.' XA:'.$xA.' YA:'.$yA.' NBporte:'.$this->nb_porte.' nbsalle:'.$this->nb_salle.'<br />';
		$this->nb_porte++;
		}
	}
}

function creation_porte($x,$y){  // Méthode qui regarde si on peu créer une porte à un endroit
		if($this->porte_ouverte($x,$y-1)>=1) { // Si couloir Nord
			$this->creer_porte($x,$y,$x,$y-1,1);
		}
		if($this->porte_ouverte($x,$y+1)>=1) { // Si un couloir Sud
			$this->creer_porte($x,$y,$x,$y+1,2);
		}
		if($this->porte_ouverte($x+1,$y)>=1) { // Si couloir l'est
			$this->creer_porte($x,$y,$x+1,$y,3);
		}
		if($this->porte_ouverte($x-1,$y)>=1) { // Si couloir l'ouest
			$this->creer_porte($x,$y,$x-1,$y,4);
		}
}

function salles() { // Méthode qui lance la création des salles sur le donjon

	$this->nb_salle=0;
	for($nb=0;$nb<ROOMCOUNT;$nb++) {
		$best_score= 10000000;
		// Generation Salle
		$w_salle = mt_rand(ROOM_MIN_WIDTH,ROOM_MAX_WIDTH);
		$h_salle = mt_rand(ROOM_MIN_HEIGHT,ROOM_MAX_HEIGHT);
		//echo 'L :'.$w_salle." H :".$h_salle.'<br>';
		for($x=0;$x<=$this->width-1;$x++) {
			for($y=0;$y<=$this->height-1;$y++) {
			   $res=$this->calculer_score($x,$y,$w_salle,$h_salle);
			   if($res<$best_score and $res>0) {
			   		$Xbest = $x;
					$Ybest = $y;
					$best_score = $res;
			   }
			}
		}

		// Placer piece
		$this->nb_salle++;
		$this->salle[$this->nb_salle]['nom']='salle'.$this->nb_salle;
		$this->salle[$this->nb_salle]['x']=$Xbest;
		$this->salle[$this->nb_salle]['y']=$Ybest;
		$this->salle[$this->nb_salle]['w']=$w_salle;
		$this->salle[$this->nb_salle]['h']=$h_salle;
		for($x=0;$x<=$w_salle-1;$x++) {
			for($y=0;$y<=$h_salle-1;$y++) {
				//echo ($Xbest+$x).','.($Ybest+$y).'<br>';
				$this->fermer_tout($Xbest+$x,$Ybest+$y);
				$this->map[$Xbest+$x][$Ybest+$y]['s']=1;
				
			}
		}
		if(SPECIAL_AOM == 1){
				$this->creation_porte($Xbest+1,$Ybest+0);
				$this->creation_porte($Xbest+0,$Ybest+1);
				$this->creation_porte($Xbest+2,$Ybest+1);
				$this->creation_porte($Xbest+1,$Ybest+2);
		}else{
			for($x=0;$x<=$w_salle-1;$x++) {
				for($y=0;$y<=$h_salle-1;$y++) {
					$this->creation_porte($Xbest+$x,$Ybest+$y);
				}
			}
		}

		


	
	}
}

function enregistrer(){ // Enregistre le donjon dans sous le  $filename 
	$fichier = serialize($this);
	$djnfile = fopen('./dungeon/'.$this->nom.'.djn', 'w');
	fseek($djnfile, 0); // On remet le curseur au début du fichier
	fputs($djnfile, $fichier); // On écrit le nouveau nombre de pages vues
	fclose($djnfile);
}

function export_xml($niveau){

	$xml = '<niveau x="'.$this->width.'" y="'.$this->height.'" z="'.$niveau.'">';
	$xml .= '<cases>';
	for($x=0;$x<=$this->width-1;$x++){
		for($y=0;$y<=$this->height-1;$y++){
			$xml .= '<case coord="'.$x.'|'.$y.'"';
			if($this->map[$x][$y]['in']) $xml .= ' in="'.$this->map[$x][$y]['in'].'"';
			$xml .= '>';
			for($dir=1;$dir<=4;$dir++){ // On traite les différentes directions
				$etat_porte=$this->map[$x][$y][$dir];
				for($nb=0;$nb<=$this->nb_porte-1;$nb++){
					if($x==$this->porte[$nb]['XD'] AND $y==$this->porte[$nb]['YD'] AND $this->porte[$nb]['dir']== $dir){
					$etat_porte = 2;
					}
				}	
				$xml .= '<p'.$dir.'>'.$etat_porte.'</p'.$dir.'>';				
			}
			if($this->map[$x][$y]['a']) $xml .= '<a>'.$this->map[$x][$y]['a'].'</a>'; else $xml .= '<a/>';
			if($this->map[$x][$y]['m']) $xml .= '<m>'.$this->map[$x][$y]['m'].'</m>'; else $xml .= '<m/>';
			if($this->map[$x][$y]['p']) $xml .= '<p>'.$this->map[$x][$y]['p'].'</p>'; else $xml .= '<p/>';
			
			$xml .= '<s>'.$this->map[$x][$y]['s'].'</s>';
			$xml .= '</case>';
		}
	}
	$xml .= '</cases>';
	$xml .= '<salles>';
	//for($nb=1;$nb<=$this->nb_salle;$nb++){
	foreach($this->salle as $salle){
		//$xml .='<s id="'.$nb.'" coord="'.$this->salle[$nb]['x'].'|'.$this->salle[$nb]['y'].'" dimension="'.$this->salle[$nb]['w'].'|'.$this->salle[$nb]['h'].'">';
		$xml .='<s id="'.$salle['nb'].'" coord="'.$salle['x'].'|'.$salle['y'].'" dimension="'.$salle['w'].'|'.$salle['h'].'">';
		$xml .='</s>';
	}
	$xml .= '</salles>';
	$xml .= '</niveau>';
		
	return $xml;
}


//==========================================================================================================
//========================================Méthode pour renvoyer les données lors de la partie &&Obsolete====
//==========================================================================================================
/*function renvoyer_donnees_joueur($x,$y){  // Renvoi les donnée de la case pour le joueur
	$donnees['cc']=$this->map[$x][$y];
	//$donnees['cc']=$this->map[$x][$y];
	
	$donnees['p'][1]=0;
	$donnees['p'][2]=0;
	$donnees['p'][3]=0;
	$donnees['p'][4]=0;
	
	$XP=$x;
	$YP=$y-1;
    for($i=0;$i<=$this->nb_porte-1;$i++){
		if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$XP AND $this->porte[$i]['YD']==$YP OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$XP][$YP]['s']==1){
		$donnees['p'][1]=1;
		}
    }
	$XP=$x;
	$YP=$y+1;
    for($i=0;$i<=$this->nb_porte-1;$i++){
		if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$XP AND $this->porte[$i]['YD']==$YP OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$XP][$YP]['s']==1){
		$donnees['p'][2]=1;
		}
    }
	$XP=$x+1;
	$YP=$y;
    for($i=0;$i<=$this->nb_porte-1;$i++){
		if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$XP AND $this->porte[$i]['YD']==$YP OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$XP][$YP]['s']==1){
		$donnees['p'][3]=1;
		}
    }
	$XP=$x-1;
	$YP=$y;

    for($i=0;$i<=$this->nb_porte-1;$i++){
		if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$XP AND $this->porte[$i]['YD']==$YP OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$XP][$YP]['s']==1){
		$donnees['p'][4]=1;
		}
    }
	
	return $donnees;
}
function renvoyer_donnees_salles(){ // Renvoi les données des salles en début de partie
	$donnees=$this->salle;
	return $donnees;
}
function renvoyer_donnees_portes(){ // Renvoi les données des portes en début de partie
	$donnees=$this->porte;
	$donnees['nb']=$this->nb_porte;
	return $donnees;
}*/
//====================================================================================================
//========================================Methode pour l'affichage ======================================
//====================================================================================================
function afficher() { // Affichage classique 
    	echo '<br><table border="0" cellpadding="0" cellspacing="0">';
    	for ($y= 0 ;$y<=	$this->height-1;$y++){
    		echo '<tr>';
  		  for ($x= 0 ;$x<=$this->width-1;$x++)  {
  		      $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['s']==0) {
    		     echo '<td><img src="./img/N'.$case[1].'S'.$case[2].'E'.$case[3].'O'.$case[4].'.jpg"/></td>';
			   } else {
			   
					  $porte=0;
					  if ($porte==0){
						 $salle=$this->etude_case_salle($x,$y);
						 echo '<td><img src="./img/salleN'.$salle[1].'S'.$salle[2].'E'.$salle[3].'O'.$salle[4].'.jpg"/></td>';
					  }
			   }
  		  }
    		echo '</tr>';
    	}
    	echo '</table>';
	}
function afficherdebug() {  // Affichage avec porte
    	echo '<br><table border="0" cellpadding="0" cellspacing="0">';
    	for ($y= 0 ;$y<=	$this->height-1;$y++){
    		echo '<tr>';
  		  for ($x= 0 ;$x<=$this->width-1;$x++)  {
  		      $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['s']==0) {
					
    		     echo '<td><img src="./img/N'.$case[1].'S'.$case[2].'E'.$case[3].'O'.$case[4].'.jpg"/></td>';
			   } else {
			   
					  $porte=0;
					  for($i=0;$i<=$this->nb_porte-1;$i++){
							if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$x AND $this->porte[$i]['YD']==$y OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$x][$y]['s']==1 AND $porte==0){
							echo '<td><font size=1>'.$this->porte[$i]['dir'].'</font></td>';
							$porte=1;
							}
					  }
					  if ($porte==0){
						 $salle=$this->etude_case_salle($x,$y);
						 echo '<td><img src="./img/salleN'.$salle[1].'S'.$salle[2].'E'.$salle[3].'O'.$salle[4].'.jpg"/></td>';
					  }
			   }
  		  }
    		echo '</tr>';
    	}
    	echo '</table>';
	}  
function afficherjoueur($jx,$jy,$jdir) { //Affichage avec la case du perssonnage
    	echo '<br><table border="0" cellpadding="0" cellspacing="0">';
    	for ($y= 0 ;$y<=	$this->height-1;$y++){
    		echo '<tr>';
  		  for ($x= 0 ;$x<=$this->width-1;$x++)  {
  		      $case=$this->recupCase($x,$y);
			  if($x==$jx AND $y==$jy){
					echo '<td><img src="./img/minimap/JPEG/j'.$jdir.'.jpg"/></td>';
			  }else{
				  if($this->map[$x][$y]['s']==0) {
	    		     echo '<td><img src="./img/minimap/JPEG/N'.$case[1].'S'.$case[2].'E'.$case[3].'O'.$case[4].'.jpg"/></td>';
				   } else {
				   
						  $porte=0;
						  if ($porte==0){
							 $salle=$this->etude_case_salle($x,$y);
							 echo '<td><img src="./img/minimap/JPEG/salleN'.$salle[1].'S'.$salle[2].'E'.$salle[3].'O'.$salle[4].'.jpg"/></td>';
						  }
				   }
			   }
  		  }
    		echo '</tr>';
    	}
    	echo '</table>';
	}
//====================================================================================================
//========================================Methode pour l'editeur =====================================
//====================================================================================================
function remplir_mur($dir) { // remplacer la porte par un mur depuis la Current Cell dans la direction indiqué
  
  	switch($dir) {
		case 1 :
		      $this->map[$this->ccX][$this->ccY][1]=0;
					$this->ccY=$this->ccY-1;
					$this->map[$this->ccX][$this->ccY][2]=0;
					break;
		case 2 :
					$this->map[$this->ccX][$this->ccY][2]=0;
					$this->ccY=$this->ccY+1;
					$this->map[$this->ccX][$this->ccY][1]=0;
					break;
		case 3 :
					$this->map[$this->ccX][$this->ccY][3]=0;
					$this->ccX=$this->ccX+1;
					$this->map[$this->ccX][$this->ccY][4]=0;
					break;
		case 4 :
					$this->map[$this->ccX][$this->ccY][4]=0;
					$this->ccX=$this->ccX-1;
					$this->map[$this->ccX][$this->ccY][3]=0;
					break;
					
	}
    
  }

function affichage_editeur_position($ccX,$ccY){

		echo '<table  class="mapediteur" border="0" cellpadding="0" cellspacing="0">';
		for ($y= 0 ;$y<=	$this->height-1;$y++){
		  echo '<tr>';
		  for ($x= 0 ;$x<=$this->width-1;$x++){
			  $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['s']==0) {// pas une salle
							echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/N'.$case[1].'S'.$case[2].'E'.$case[3].'O'.$case[4].'.jpg"/></a></td>';
			   } else {
					  $porte=0;
					  for($i=0;$i<=$this->nb_porte-1;$i++){
							if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$x AND $this->porte[$i]['YD']==$y OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$x][$y]['s']==1 AND $porte==0){
							echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/P'.$this->porte[$i]['dir'].'.jpg"/></a></td>';
							$porte=1;
							}
					  }
					  if ($porte==0){
							 $salle=$this->etude_case_salle($x,$y);
								echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/salleN'.$salle[1].'S'.$salle[2].'E'.$salle[3].'O'.$salle[4].'.jpg"/></a></td>';
					  }
			   }
		  }
		  echo '</tr>';
		}
		echo '</table>';
	}
	
function affichage_route(){

		echo '<table  class="mapediteur" border="0" cellpadding="0" cellspacing="0">';
		for ($y= 0 ;$y<=	$this->height-1;$y++){
		  echo '<tr>';
		  for ($x= 0 ;$x<=$this->width-1;$x++){
			  $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['s']==0) {// pas une salle
							echo '<td><img src="./img/route/route'.$case[1].''.$case[2].''.$case[4].''.$case[3].'.png"/></td>';
			   }
		  }
		  echo '</tr>';
		}
		echo '</table>';
	}

function affichage_editeur_monstre(){
		echo '<table  class="mapediteur2" border="0" cellpadding="0" cellspacing="0">';
		for ($y= 0 ;$y<=	$this->height-1;$y++){
		  echo '<tr>';
		  for ($x= 0 ;$x<=$this->width-1;$x++){
			  $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['m']!='0'){
					echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/monstre.png"/></a></td>';
			  }else{
					echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/vide.png"/></a></td>';
			  }
		  }
		  echo '</tr>';
		}
		echo '</table>';
	}
	
function affichage_editeur_action(){
		echo '<table  class="mapediteur3" border="0" cellpadding="0" cellspacing="0">';
		for ($y= 0 ;$y<=$this->height-1;$y++){
		  echo '<tr>';
		  for ($x= 0 ;$x<=$this->width-1;$x++){
			  $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['in']==1){
				echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/in.png"/></a></td>';
			  }else{
				  if($this->map[$x][$y]['a']!='0'){
						echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/action.png"/></a></td>';
				  }else{
						echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/vide.png"/></a></td>';
				  }
			  }
		  }
		  echo '</tr>';
		}
		echo '</table>';
	}
	
function affichage_editeur_prerequis(){
		echo '<table  class="mapediteur4" border="0" cellpadding="0" cellspacing="0">';
		for ($y= 0 ;$y<=	$this->height-1;$y++){
		  echo '<tr>';
		  for ($x= 0 ;$x<=$this->width-1;$x++){
			  $case=$this->recupCase($x,$y);
			  if($this->map[$x][$y]['p']!='0'){
					echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/prerequis.png"/></a></td>';
			  }else{
					echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/vide.png"/></a></td>';
			  }
		  }
		  echo '</tr>';
		}
		echo '</table>';
	}
	
function affichage_editeur_lien($ccX,$ccY){
		echo '<table  class="mapediteur5" border="0" cellpadding="0" cellspacing="0">';
		for ($y= 0 ;$y<=	$this->height-1;$y++){
		  echo '<tr>';
		  for ($x= 0 ;$x<=$this->width-1;$x++){
			$case=$this->recupCase($x,$y);
			if($x == $ccX AND $y == $ccY){
				echo '<td align="center" class="tdselect"><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img class="selected" src="./img/selection.png"/></a></td>';
			}else{
				echo '<td><a href="./editeur.php?X='.$x.'&Y='.$y.'"><img src="./img/vide.png"/></a></td>';
			}
		  }
		  echo '</tr>';
		}
		echo '</table>';
	}

function etat_case($x,$y){
		$case=$this->recupCase($x,$y);
		$trips0 = trips(0);
		$trips1 = trips(1);
		if($case['s']==0){ // Si c'est pas une salle
			if($case[1]==0){
				$icase[1]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=creuser&dir=1" title="<div class=\'infobulle\'>Creuser Mur</div>" '.tooltip().'>0</a>';
			}else $icase[1]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=remplir&dir=1" title="<div class=\'infobulle\'>Remplir Mur</div>" '.tooltip().'>1</a>';
			
			if($case[2]==0){
				$icase[2]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=creuser&dir=2" title="<div class=\'infobulle\'>Creuser Mur</div>" '.tooltip().'>0</a>';
			}else $icase[2]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=remplir&dir=2" title="<div class=\'infobulle\'>Remplir Mur</div>" '.tooltip().'>1</a>';
			
			if($case[3]==0){
				$icase[3]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=creuser&dir=3" title="<div class=\'infobulle\'>Creuser Mur</div>" '.tooltip().'>0</a>';
			}else $icase[3]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=remplir&dir=3" title="<div class=\'infobulle\'>Remplir Mur</div>" '.tooltip().'>1</a>';
			
			if($case[4]==0){
				$icase[4]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=creuser&dir=4" title="<div class=\'infobulle\'>Creuser Mur</div>" '.tooltip().'>0</a>';
			}else $icase[4]='<a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=remplir&dir=4" title="<div class=\'infobulle\'>Remplir Mur</div>" '.tooltip().'>1</a>';
			
			echo '<br />Etat de la case :<br /><table><tr align ="center"><td></td><td>'.$icase[1].'</td><td></td></tr>
			<tr align ="center"><td>'.$icase[4].'</td><td><img src="./img/N'.$case[1].'S'.$case[2].'E'.$case[3].'O'.$case[4].'.jpg"/></td><td>'.$icase[3].'</td></tr>
			<tr align ="center"><td></td><td>'.$icase[2].'</td><td></td></tr></table><a href="?X='.$x.'&Y='.$y.'&a=creerSalle">Créer une salle</a>';
		}elseif($case['s']==1){ // Si c'est une salle
		  echo '<a href="?X='.$x.'&Y='.$y.'&a=deleteSalle">Supprimer salle</a>';
		  $porte=0;
		  for($i=0;$i<=$this->nb_porte-1;$i++){
				if(isset($this->porte[$i])AND($this->porte[$i]['XD']==$x AND $this->porte[$i]['YD']==$y OR $this->porte[$i]['XA']==$x AND $this->porte[$i]['YA']==$y )AND $this->map[$x][$y]['s']==1 AND $porte==0){
						echo '<br />Ceci est la porte'.$i.'  <a href="./editeur.php?X='.$x.'&Y='.$y.'&a=deletePorte&id='.$i.'"><img src="./img/delete.png"/></a><br /><br />';
				$porte=1;
				}
		  }
		  if($porte==0){
			echo '<br />Creer Porte:
			
			<br /><a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=porte&dir=1">Haut</a>
			<br /><a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=porte&dir=2">Bas</a>
			<br /><a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=porte&dir=3">Droite</a>
			<br /><a href="'.$_SERVER['PHP_SELF'].'?X='.$x.'&Y='.$y.'&a=porte&dir=4">Gauche</a>';
		  }
		}
		if($this->map[$x][$y]['m']=='0'){ // Pas de monstre sur la case
		echo '<form name="dungeon" method="post" action="./editeur.php?X='.$x.'&Y='.$y.'&a=monstre">
			Ajouter un monstre 
			  <input type="text" name="syntaxe_monstre" value=""> '.tips("Entrez l'IDentifiant du monstre").'<br />
			  <input type="submit" value="Ajouter monstre">
			 </form>';
		}else{
		echo 'Monstre sur la case:  ID : ['.$this->map[$x][$y]['m'].']<a href="./editeur.php?X='.$x.'&Y='.$y.'&a=deletem"><img src="./img/delete.png"/></a><br /><br />';
		}
		
		
		if($this->map[$x][$y]['a']=='0'){ // Pas d'action
		$fichier = new fichier('saisieAction.php');
		echo $fichier->affiche();
		echo '<form name="formaction" method="post" action="./editeur.php?X='.$x.'&Y='.$y.'&a=action">
			Ajouter une action

			  <input id="action" type="text" name="syntaxe_action" value=""><a href="#" class="tooltip" onclick=\'javascript:ouvrirAide("helpAction")\'>Generer<em><span></span>Generer une action</em></a><br />
			  <input type="submit" value="Ajouter action">
			 </form>';
		}else{
		echo 'Action sur la case: ['.$this->map[$x][$y]['a'].']<a href="./editeur.php?X='.$x.'&Y='.$y.'&a=deletea"><img src="./img/delete.png"/></a><br /><br />';
		}
		
		
		if($this->map[$x][$y]['p']=='0'){ // Pas de prés-requis
		$fichier = new fichier('saisiePR.php');
		echo $fichier->affiche();
		echo '<form name="formprerequis" method="post" action="./editeur.php?X='.$x.'&Y='.$y.'&a=prerequis">
			Ajouter un prerequis
			  <input id="prerequis" type="text" name="syntaxe_prerequis" value=""><a href="#" class="tooltip" onclick=\'javascript:ouvrirAide("helpPr")\'>Generer<em><span></span>Generer un prérequis</em></a><br />
			  <input type="submit" value="Ajouter prerequis">
			 </form>';
		}else{
		echo 'Prerequis sur la case: ['.$this->map[$x][$y]['p'].']<a href="./editeur.php?X='.$x.'&Y='.$y.'&a=deletep"><img src="./img/delete.png"/></a><br /><br />';
		}
		
		if($this->map[$x][$y]['in']=='0'){ // Pas une case départ
		echo '<a href="./editeur.php?X='.$x.'&Y='.$y.'&a=in" title="<div class=\'infobulle\'>Cette case détermine l\'entrée du donjon</div>" '.tooltip().'>Faire de cette case une entré</a><br />';
		}else{
		echo 'cette case est une entrée <a href="./editeur.php?X='.$x.'&Y='.$y.'&a=deletein"><img src="./img/delete.png"/></a><br /><br />';
		}
	echo '<br><br>';
	echo '<br><br>';
	}
	
function cherche_salle($x,$y){
	$trouve=0;
	$nb=1;
		while($trouve==0 OR $nb<=$this->nb_salle){
				for($X=$this->salle[$nb]['x'];$X<=($this->salle[$nb]['x']+$this->salle[$nb]['w']-1);$X++) {
					for($Y=$this->salle[$nb]['y'];$Y<=($this->salle[$nb]['y']+$this->salle[$nb]['h']-1);$Y++) {
						if($x==$X AND $y==$Y){
							$trouve=1;
							$salle=$this->salle[$nb];
							$salle['nb']=$nb;
						}
					}
				}
			$nb++;
		}
	return $salle;
  }
  
function ajouter_porte($xD,$yD,$xA,$yA,$dir){//$nb_salle;
  			//$this->porte[$this->nb_porte]['salle']=$nb_salle;
			$this->porte[$this->nb_porte]['XD']=$xD;
			$this->porte[$this->nb_porte]['YD']=$yD;
			$this->porte[$this->nb_porte]['XA']=$xA;
			$this->porte[$this->nb_porte]['YA']=$yA;
			$this->porte[$this->nb_porte]['dir']=$dir;
			$this->salle[$this->nb_salle]['door'][$dir]=1;
			$this->nb_porte++;
  }
  
function ajouter_teleporteur($xD,$yD,$dungeonD,$xA,$yA,$dungeonA){
	$this->teleporteur[$this->nb_teleporteur]['XD']=$xD;
	$this->teleporteur[$this->nb_teleporteur]['YD']=$yD;
	$this->teleporteur[$this->nb_teleporteur]['dungeonD']=$dungeonD;
	$this->teleporteur[$this->nb_teleporteur]['XA']=$xA;
	$this->teleporteur[$this->nb_teleporteur]['YA']=$yA;
	$this->teleporteur[$this->nb_teleporteur]['dungeonA']=$dungeonA;
	$this->nb_teleporteur++;
  }
  
function chercher_teleporteur($x,$y){
		for($nb=0;$nb<=$this->nb_teleporteur;$nb++){
			if(isset($this->teleporteur[$nb]['XA'])){
				if(($this->teleporteur[$nb]['XA']==$x AND $this->teleporteur[$nb]['YA']==$y) OR ($this->teleporteur[$nb]['XD']==$x AND $this->teleporteur[$nb]['YD']==$y)){
					$teleporteur=$this->teleporteur[$nb];
					return $teleporteur;
				}
			}
		}
	}
	
function ajouter_monstre($x,$y,$syntaxe_monstre){
	$this->map[$x][$y]['m']=$syntaxe_monstre;
}

function ajouter_action($x,$y,$syntaxe_action){
	$this->map[$x][$y]['a']=$syntaxe_action;
}

function ajouter_prerequis($x,$y,$syntaxe_prerequis){
	$this->map[$x][$y]['p']=$syntaxe_prerequis;
}

function creerSalle($x,$y){
	$porteOuverte = 0;
	for($XX=-1;$XX<=1;$XX++){
		for($YY=-1;$YY<=1;$YY++){
			$porteOuverte += $this->porte_ouverte($x+$XX,$y+$YY);
		}
	}
	if($porteOuverte==0){
		for($XX=-1;$XX<=1;$XX++){
			for($YY=-1;$YY<=1;$YY++){
				$this->map[$x+$XX][$y+$YY]['s'] = 1;
			}
		}
		$this->nb_salle++;
		$this->salle[$this->nb_salle]['nom']='salle'.$this->nb_salle;
		$this->salle[$this->nb_salle]['x']=$x-1;
		$this->salle[$this->nb_salle]['y']=$y-1;
		$this->salle[$this->nb_salle]['w']=3;
		$this->salle[$this->nb_salle]['h']=3;
	}
}

function deleteSalle($x,$y){
	
	$salle=$this->cherche_salle($x,$y);
	if(is_array($salle)){
		unset($this->salle[$salle['nb']]);
		for($XX=-1;$XX<=1;$XX++){
			for($YY=-1;$YY<=1;$YY++){
				$this->map[$x+$XX][$y+$YY]['s'] = 0;
			}
		}
	}
}
}
?>