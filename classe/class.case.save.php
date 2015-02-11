<?php
//HERO ETAT 1 En defense , 2 en déplacement , 3 Combat
//Case->HERO->ETAT 1 SUR LA CASE | 2 en train d'arriver | 3 En train de partir | 4 va combatre | 5 combat
class CaseObject{
  var $map;
  var $X;
  var $Y;

  var $hero = array(); //tableau d'hero avec pour chaque hero ($hero['id'] $hero['proprietaire'] $hero['alliance'])
  var $ville = array(); //tableau de ville
  var $description;
  var $mob; // tableau de mob 
  var $route; //[1][2][3][4]
  var $combat;
  var $mine=0;
  
  function CaseObject($X,$Y,$map){

      $sql = 'SELECT * FROM '.$map.' WHERE X = "'.$X.'" AND Y = "'.$Y.'" ';
      $return = $GLOBALS['db']->query($sql);
      $donnees = mysql_fetch_array($return);
      $Case = unserialize($donnees['object']);
     if(is_object($Case)){ // Si ya déja un objet case definie sur celle qu'on appelle ca la charge
          //$this = $Case;
          $this->map = $Case->map;
          $this->X = $Case->X;
          $this->Y = $Case->Y;
          $this->hero = $Case->hero;
          $this->ville = $Case->ville;
          $this->description = $Case->description;
          $this->mob = $Case->mob;
          $this->combat = $Case->combat;
          $this->mine = $Case->mine;
          

  
      }else{ // Sinon on en crée une nouvelle avec les données
          $this->X = $X;
          $this->Y = $Y;
          $this->map = $map;
      }
  }
  
  	function terminerCombat(){ // Ouf le DEBUT de la fin
	 
	 //echo 'FIN COMBAT';
	
	   $this->combat->log("Fin du combat");
	   $rapport = $this->combat->rapportCombat();
	   $nb = $this->combat->compterGarnisonDebout();
	   $nb_att = $nb[0]; // Nombre de garnison en attaque debout
		 $nb_def = $nb[1]; // Nombre de garnison en defense debout
		 
		 if($this->combat->type_defenseur == "h"){ // Un héro à été attaqué
    		 /*if($nb_att==0){ // Les attaquants sont mort
    		  $this->combat->log("Les attaquants sont morts");
    		 }
    		 if($nb_def==0){ // Les défenseurs sont mort
    		  $this->combat->log("Les défenseurs ont perdu");
    		 }*/
    		 $rapport = $this->combat->rapportCombat();
  		    if(is_array($this->combat->garnisons))
  		  	foreach($this->combat->garnisons as $key => $value){ // Boucle infernale
  
    				  if($value['type'] == "v"){
    				      $ville = new Ville();
    				      $ville->load($this->combat->garnisons[$key]['id']);
    				      $this->combat->garnisons[$key]['garnison']->degat_att = 0;
    				      $this->combat->garnisons[$key]['garnison']->degat_def = 0;
    				      $ville->garnison = $this->combat->garnisons[$key]['garnison'];
    				      $ville->etat = 1;
    				      $ville->save();
    				      
    				      envoyer_rapport($ville->idCompte,'Rapport de combat sur '.$ville->nom.''.date("d.m.y.   H:i:s",time()),$rapport);  
    				  }else{
    				      $hero = new Hero();
    				      $hero->load($this->combat->garnisons[$key]['id']);
    				      $this->combat->garnisons[$key]['garnison']->degat_att = 0;
    				      $this->combat->garnisons[$key]['garnison']->degat_def = 0;
    				      $hero->garnison = $this->combat->garnisons[$key]['garnison'];
    				      $hero->etat = 1;
    				      $hero->save();
    				      envoyer_rapport($hero->idCompte,'Rapport de combat sur '.$hero->nom.' '.date("d.m.y.   H:i:s",time()),$rapport);
    				  }
  				}
		 }
		 
		 if($this->combat->type_defenseur == "v"){
  		 //$addon_rapport[] = "Avant retour au boulot des paysans".$this->combat->garnisons[$this->combat->type_defenseur.'_'.$this->combat->id_defenseur]['garnison']->unite[0]." ";
  		 $this->combat->garnisons[$this->combat->type_defenseur.'_'.$this->combat->id_defenseur]['garnison']->unite[0]-=$this->combat->nb_paysans_def;
  		 //$addon_rapport[] = "Aprés retour au boulot des paysans".$this->combat->garnisons[$this->combat->type_defenseur.'_'.$this->combat->id_defenseur]['garnison']->unite[0]." ";
     
       if($this->combat->garnisons[$this->combat->type_defenseur.'_'.$this->combat->id_defenseur]['garnison']->unite[0] < 0){
  		    $this->combat->garnisons[$this->combat->type_defenseur.'_'.$this->combat->id_defenseur]['garnison']->unite[0] = 0;
  		 }
		      
    		 switch($this->combat->param_att){
    		      case "pillage":
    		      
                		 if($nb_att==0){ // Les attaquants sont mort
                		  $this->combat->log("Les attaquants sont morts");
                		  $addon_rapport[] = "Les attaquant se sont fais poutrer.";
                		 }
                		 if($nb_def==0){ // Les défenseurs sont mort
                  		  $addon_rapport[] = " Les défenseurs ont perdu";
                  		  $this->combat->log("Les défenseurs ont perdu");
                  		  $ville = new Ville();
                        $ville->load($this->combat->id_defenseur);
                        $nom_ville_def = $ville->nom; 
                        $joueur = new Joueur();
                        $joueur->loadSimple($ville->idCompte);
  
                        $bois_pris = ($joueur->bois/100)*coef_pillage_bois(); 
                        $ors_pris = ($joueur->ors/100)*coef_pillage_ors(); 
                        
                        $joueur->ors = $joueur->ors-$ors_pris;
                        $joueur->bois = $joueur->bois-$bois_pris;
                        $joueur->save();
                        unset($joueur);
                        unset($ville);
                        $ville = new Ville();
                        $ville->load($this->combat->id_attaquant);
                        $joueur = new Joueur();
                        $joueur->loadSimple($ville->idCompte);
                        $joueur->ors+=$ors_pris;
                        $joueur->bois+=$bois_pris;
                        $joueur->save();
                        $addon_rapport[] = "Pillage de la ville ".$nom_ville_def." ";
                        $addon_rapport[] = "Butin : ".$ors_pris." ".icoOrs()." ".$bois_pris." ".icoBois()." ";
                     }
                     $rapport = $this->combat->rapportCombat($addon_rapport);
                        if(is_array($this->combat->garnisons))
                		  	foreach($this->combat->garnisons as $key => $value){ // Boucle infernale
                
                  				  if($value['type'] == "v"){
                  				      $ville = new Ville();
                  				      $ville->load($this->combat->garnisons[$key]['id']);
                  				      $this->combat->garnisons[$key]['garnison']->degat_att = 0;
                  				      $this->combat->garnisons[$key]['garnison']->degat_def = 0;
                  				      $ville->garnison = $this->combat->garnisons[$key]['garnison'];
                  				      $ville->etat = 1;
                  				      $ville->save();
                  				      
                  				      envoyer_rapport($ville->idCompte,'Rapport de combat '.date("d.m.y.   H:i:s",time()),$rapport);  
                  				  }else{
                  				      $hero = new Hero();
                  				      $hero->load($this->combat->garnisons[$key]['id']);
                  				      $this->combat->garnisons[$key]['garnison']->degat_att = 0;
                  				      $this->combat->garnisons[$key]['garnison']->degat_def = 0;
                  				      $hero->garnison = $this->combat->garnisons[$key]['garnison'];
                  				      $hero->etat = 1;
                  				      $hero->save();
                  				      
                  				      envoyer_rapport($hero->idCompte,'Fin de bataille '.date("d.m.y.   H:i:s",time()),$rapport);
                  				  }
                				}
    		      break;
    		      
    		      case "dest": // DESTRUCTION !!!!!!!!!!!!!!!!!!!!!!!!!!! C T MECHANT
    		      
    		      break;
    		      
    		      case "prise":
    		      
              break;
                            
              default: 
                	if($nb_att==0){ // Les attaquants sont mort
              		  $this->combat->log("Les attaquants sont morts");
              		  $addon_rapport[] = "Les attaquant se sont fais poutrer.";
              		 }
              		 if($nb_def==0){ // Les défenseurs sont mort
              		  $addon_rapport[] = " Les défenseurs ont perdu";
              		  $this->combat->log("Les défenseurs ont perdu");
                   }
                   $rapport = $this->combat->rapportCombat($addon_rapport);
                    if(is_array($this->combat->garnisons))
            		  	foreach($this->combat->garnisons as $key => $value){ // Boucle infernale
            				  if($value['type'] == "v"){
            				      $ville = new Ville();
            				      $ville->load($this->combat->garnisons[$key]['id']);
            				      $this->combat->garnisons[$key]['garnison']->degat_att = 0;
            				      $this->combat->garnisons[$key]['garnison']->degat_def = 0;
            				      $ville->garnison = $this->combat->garnisons[$key]['garnison'];
            				      $ville->etat = 1;
            				      $ville->save();
            				      
            				      envoyer_rapport($ville->idCompte,'Rapport de combat '.date("d.m.y.   H:i:s",time()),$rapport);  
            				  }else{
                          $hero = new Hero();
            				      $hero->load($this->combat->garnisons[$key]['id']);
            				      $this->combat->garnisons[$key]['garnison']->degat_att = 0;
            				      $this->combat->garnisons[$key]['garnison']->degat_def = 0;
            				      $hero->garnison = $this->combat->garnisons[$key]['garnison'];
            				      $hero->etat = 1;
            				      $this->hero[$hero->id]['etat']= 1;
            				      $hero->save();
            				      if($this->combat->garnisons[$key]['garnison']->compterUnite() <= 0){
                            $joueur = new Joueur();
                            $joueur->load($hero->idCompte);
                            $joueur->sortirHero($hero->id);
                            $addon_rapport[] = " Le héro ".$hero->nom." de ".$joueur->login." est mort au combat.";
                            $joueur->save();
                            $this->enleverHero($hero->id);
                            $hero->delet();
                          }
            				      envoyer_rapport($hero->idCompte,'Fin de bataille '.date("d.m.y.   H:i:s",time()),$rapport);
            				  }
            				}
              break;
    		 }
		 }
	}

  function verifHero(){ // Verifie les déplacement héro et un peu tout sur la case
      $update = 0; // Pour l'instant pas la peine d'enregistrer la case
      if(is_object($this->combat)){//Si un combat est lancé
         if($this->combat->etat == 1){ // Et qu'il est terminé  //  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $this->terminerCombat();
            if(is_array($this->hero))
            foreach($this->hero as $key => $value){ // on débloque les héros

                if($value['def_type_attaque']=="att"){

                }elseif($value['def_type_attaque']== "attretour" OR $value['def_type_attaque']== "prise" OR $value['def_type_attaque']== "dest" OR $value['def_type_attaque']== "pillage"){
                  // Si le héro avait un type d'attaque avec retour , on le fais repartir d'ou il vient
                  $retourX = $this->hero[$key]['X'];
                  $retourY = $this->hero[$key]['Y'];
                  
                  $hero = new Hero();
                  $hero->load($key);
                  $hero->etat = 2;
                 
                  
                  $temps = $hero->calculDeplacement($retourX,$retourY,$this->X,$this->Y);
                  $time_arrive = $temps + time();
                  
                  $case = new CaseObject($retourX,$retourY,$hero->map);

                  $alliance = new Alliance();
                  $alliance->load($this->hero[$key]['id_alliance']);
                  
                  $joueur = new Joueur();
                  $joueur->load($this->hero[$key]['id_proprietaire']);
                  
                  $case->ajouterHero($hero->id,$joueur->login,$joueur->id,$alliance->nom,$joueur->alliance,$hero->nom);
                  
                  $case->etatHero($hero->id,2,$this->X,$this->Y,$time_arrive,$get['type'],$get['id'],$get['att']); 
                  //$case->enleverHero($this->id);
                  $case->save();
                  unset($case);
                  
                  $hero->etat = 2;
                  $hero->X = $retourX;
                  $hero->Y = $retourY;
                  $hero->temps = $time_arrive;
                  $hero->save();
                  
                  // ICI QUI FAUT RAJOUTER LACTION SI BESOIN

                  $this->etatHero($key,3,$retourX,$retourY,$time_arrive);
                  $update++;
                }else{
                  $this->hero[$key]['etat'] = 1;
                  $hero = new Hero();
                  $hero->load($key);
                  $hero->etat = 1;
                  $hero->save();
                }

            }
            if(is_array($this->ville))            
            foreach($this->ville as $key => $value){ // on débloque les (la) ville
              if($value['etat'] == 5){
                $this->ville[$key]['etat'] = 1;
                $ville = new Ville();
                $ville->load($key);
                $ville->etat = 1;
                $ville->save();
              }
            }
            
            unset($this->combat);// On supprime le combat
            $this->verifHero();
            $update++; // et on active la save à la fin
          }else{ // Si le combat est pas finis
            echo 'combat en cour';
            $this->combat->calculCombat(); // On lance un calcul 
            $update++; // et on sauvegarde
          }
      }

    // Verification des déplacement
    if(is_array($this->hero)){
    foreach($this->hero as $key => $value){ // Analyse de tous les héros présent sur la case
        if($value['etat'] == 2){ // Si l 'état est 2 donc en train d'arriver
          if($value['timeEnd'] <= time() ){ // et que le temps est écoulé
            $update++;
            $this->hero[$key]['etat'] = 1;
            $this->hero[$key]['X'] = $this->X;//////
            $this->hero[$key]['Y'] = $this->Y;//////On le met à jour sur cette case
            $this->hero[$key]['timeEnd'] = 0;///////
            $hero = new Hero();
            $hero->load($value['id']);
            $hero->etat = 1; // Et dans la table
            $hero->temps = 0;//
            $hero->save();
          }
        }
        if($value['etat'] == 3){// Le héro est en route vers une autre case
          if($value['timeEnd'] <= time() ){ // le héro est arrivé
              $update++;
              $this->enleverHero($key); // On le sort de la
          }
        }
        if($value['etat'] == 4){ // Ya un mec véner avec la folle envie de casser quelqu'un
            if(is_object($this->combat)){ // Si ya déja un combat en cour 
              // Si ya déje un combat en cour on fais rien on atend
              $this->hero[$key]['etat'] = 1; // On le calme 
              $update++;
            }else{
              if($value['timeEnd'] <= time() ){ // le héro est arrivé lancement du combat
                $update++;
                
                $type_attaque = $this->hero[$key]['def_type_attaque'];
                $this->hero[$key]['etat'] = 5;
                //$this->hero[$key]['X'] = $this->X;// il veut peut etre repartir aprés ;-)
                //$this->hero[$key]['Y'] = $this->Y;
                $this->hero[$key]['timeEnd'] = 0;
                
                $hero = new Hero();
                $hero->load($value['id']);
                $hero->etat = 3; // On bloque le héro sur la table aussi
                $hero->temps = 0;
                $hero->save();
                
                $id_defenseur = $this->hero[$key]['def_id'];
                if($this->hero[$key]['def_type'] == "h"){ // Combat contre un hero
                    $this->hero[$id_defenseur]['etat']=5; // On le bloque et on récup ses infos
                    $def = new Hero();
                    $def->load($id_defenseur);
                    $def->etat = 3;
                    $def->save();
                    $alliance_def = $this->hero[$id_defenseur]['id_alliance'];
                    $type = "h";
                }elseif($this->hero[$key]['def_type'] == "v"){ // Combat contre une ville
                      $this->ville[$id_defenseur]['etat']=5;  // On la bloque et on récup ses infos
                      $def = new Ville();
                      $def->load($id_defenseur);
                      $def->etat = 3;
                      $def->save();
                      $alliance_def = $this->ville[$id_defenseur]['id_alliance'];
                      $type = "v";
                }
                //echo 'Debut COMBAT';
                //
                //echo 'Def ID :'.$def->id.' '.$hero->id.' ';
                if($hero->id != 0 AND $def->id !=0){ // Derniere verif avant le début du combat
                  $this->combat = new Combat();
                  $this->combat->debutCombat($hero->id,"h",$hero->idAlliance,$hero->garnison,$def->id,$type,$alliance_def,$def->garnison,$type_attaque);
    
                  $update++; // C'est partis et on save 
                }
                
              }
            }
        }
        
        if($value['etat'] == 1 AND is_object($this->combat)){ // Un héro inocupé ? ya pas un combat en cour pour lui ?
        //echo '1 potentiel ->'.$value['nom'].' ';
          if(is_array($this->hero))
          foreach($this->hero as $key_hero => $value_hero){  // Ya pas un héro en train de combattre ?
            if($value_hero['etat'] == 5){
             
              if( ($value_hero['id_alliance'] == $value['id_alliance'] AND $value['id_alliance'] !=0) OR $value_hero['id_proprietaire'] == $value['id_proprietaire']){ // Un Allié est en combat
                 //Si c'est un allié à lui , il le rejoin
                
                $ally_combat = $this->combat->searchAlly($value_hero['id'],"h");
                $hero_entrant = new Hero();
                $hero_entrant->load($value['id']);
                $garnison = $hero_entrant->garnison;
                $hero_entrant->etat = 3;
                $hero_entrant->save();
                $this->hero[$value['id']]['etat'] = 5;
                $this->combat->ajouterGarnison($value['id'],"h",$ally_combat,$garnison);
                $update++;
              }
            }
          }
          if(is_array($this->ville))          
          foreach($this->ville as $key_ville => $value_ville){ // Ya pas une ville en train de combattre ?
            if($value_ville['etat'] == 5){
             
              if( ($value_ville['id_alliance'] == $value['id_alliance'] AND $value['id_alliance'] !=0) OR $value_ville['id_proprietaire'] == $value['id_proprietaire']){ // Un Allié est en combat
                  //Si c'est un allié à lui , il l'aide 
                
                $ally_combat = $this->combat->searchAlly($value_ville['id'],"v");
                $hero_entrant = new Hero();
                $hero_entrant->load($value['id']);
                $garnison = $hero_entrant->garnison;
                $hero_entrant->etat = 3;
                $hero_entrant->save();
                $this->hero[$value['id']]['etat'] = 5;
                $this->combat->ajouterGarnison($value['id'],"h",$ally_combat,$garnison);
                $update++;
              }
            }
          }
          
          }
          
          
          if(is_object($this->combat)){
            if(is_array($this->ville))
            foreach($this->ville as $key_ville => $value_ville){ // Ya pas une ville qui peu aider un héro ?
              if($value_ville['etat'] == 1){
                if(is_array($this->hero))
                 foreach($this->hero as $key_hero => $value_hero){
                  if($value_hero['etat'] == 5){
                    if( ($value_hero['id_alliance'] == $value_ville['id_alliance'] AND $value_ville['id_alliance'] !=0) OR $value_hero['id_proprietaire'] == $value_ville['id_proprietaire']){ // Un Allié est en combat
                       //echo 'Combat de'.$value_hero['nom'].' ';
                      //echo 'un allié combat et la ville devrait rejoindre ';
                      //echo 'Value hero que la ville doit rejoindre='.$value_hero['id'].' |';
                      $ally_combat = $this->combat->searchAlly($value_hero['id'],"h");
                      //echo 'Ally combat='.$ally_combat.' |';
                      $ville_entrant = new Ville();
                      $ville_entrant->load($value_ville['id']);
                      $garnison = $ville_entrant->garnison;
                      $garnison->genererStats();
                      $ville_entrant->etat = 3;
                      $ville_entrant->save();
                      $this->ville[$key_ville]['etat'] = 5;
                      //echo $value_ville['id'].'        ';
                      $this->combat->ajouterGarnison($value_ville['id'],"v",$ally_combat,$garnison);
                      $update++;
                    }
                  }
                }
              }
            }
          }
          
          
        
        
      }
    }
    if($update > 0){ // Si une des conditions étaient propice à sauvegarder 
      $this->save();  // On sauvegarde la case
    }

  }
  
  function libre(){ // La case est libre ?
    if( count($this->hero) != 0 ||  count($this->ville) != 0 || count($this->mob) != 0)
      return false;
    return true;
  }

  function ajouterHero($id,$proprietaire,$id_proprietaire,$alliance,$id_alliance,$nom){
    $this->hero[$id]['id']= $id;
    $this->hero[$id]['proprietaire']= $proprietaire;
    $this->hero[$id]['id_proprietaire']= $id_proprietaire;
    $this->hero[$id]['alliance']= $alliance;
    $this->hero[$id]['id_alliance']= $id_alliance;
    $this->hero[$id]['nom']= $nom;
    $this->hero[$id]['etat']= 1;
  }
  
  function etatHero($id,$etat,$X,$Y,$timeEnd,$type=0,$id_enemis=0,$type_attaque=0){
/*
ETAT 1 Sur la case 
ETAT 2 En train d'arriver 
ETAT 3 En train de partir 
ETAT 4 Vien combatre 
ETAT 5 En combat
    
*/
    $action = new Action();
    $param['X'] = $X;
    $param['Y'] = $Y;
    $param['map'] = $this->map;
    
    $action->newAction("deplacement",0,$param,$timeEnd);
    
    $this->hero[$id]['etat']= $etat; 
    // Si etat 3    X et Y = destination
    //Si etat 2ou4  X et Y = point de départ
    $this->hero[$id]['X']= $X;
    $this->hero[$id]['Y']= $Y;
    $this->hero[$id]['timeEnd']= $timeEnd; // Heure ou se termine le trajet en cour

    //Si attaque 
    $this->hero[$id]['def_type']=$type; // Il attaque quoi ? une ville v ? un héro h ? autre ?
    $this->hero[$id]['def_id']=$id_enemis; // L'id de celui sur qui il veut taper
    $this->hero[$id]['def_type_attaque']=$type_attaque; // De quelle maniére il veut le taper
  }
  
  
  function ajouterRoute($nord,$sud,$est,$ouest){
    if($nord ==0 AND $sud == 0 AND $est == 0 AND $ouest==0){
    
    }else{
    $this->route['1']= $nord;
    $this->route['2']= $sud;
    $this->route['4']= $est;
    $this->route['3']= $ouest;
    echo 'Route ajouté';
    }
  }
  
  
  function ajouterVille($id,$proprietaire,$id_proprietaire,$alliance,$id_alliance,$nom){
    $this->ville[$id]['id']= $id;
    $this->ville[$id]['proprietaire']= $proprietaire;
    $this->ville[$id]['id_proprietaire']= $id_proprietaire;
    $this->ville[$id]['alliance']= $alliance;
    $this->ville[$id]['id_alliance']= $id_alliance;
    $this->ville[$id]['nom']= $nom;
    $this->ville[$id]['etat']= 1;
  }
  

  
  function enleverHero($id){
    if(isset($this->hero[$id])){
      unset($this->hero[$id]);
    }
  }
  
  function ajouterMine(){
    $this->mine = 1;
  }

  function save(){ // Fonction magique :) Mise à jour de la case sur la table MAP
    if(count($this->hero) == 0 AND count($this->ville)==0 AND !is_array($this->route) AND $this->mine == 0) { // ya plus rien dans la case , on la netoie :D
      $object = "";
      $GLOBALS['db']->query("UPDATE $this->map SET object = '$object' WHERE X= '".$this->X."' AND Y= '".$this->Y."' ");
    }else{ // Ya encore de la chair dans cette case , et on save l'objet
      $object = serialize($this);
      $GLOBALS['db']->query("UPDATE $this->map SET object = '$object' WHERE X= '".$this->X."' AND Y= '".$this->Y."' ");
    }
  }

  function ajouterDescription(){

  }

  function ajouterMob(){

  }
  

  function afficheResume(){ // Affiche le résumé de contenu gauche
  $this->verifHero();
    $ally_here = 0;
    $nb_garnison = 0;
    if(is_array($this->hero))
    foreach($this->hero as $key => $value){
      if(etatDiplomatie($value['id_alliance'],$value['id_proprietaire'])>0){
        $ally_here = 1;
      }
    }

    $html = '';

    
    if(is_array($this->ville)){
      foreach($this->ville as $key => $value){
        $html.= 'Ville : '.$value['nom'].' ';
        if(attPossible($value['alliance'],$value['id_proprietaire'])){
          $html.= ahref(icoAttack(),'data.php?div=contenu&o=attaque&type=v&x='.$this->X.'&y='.$this->Y.'&id='.$value['id'],"contenu"); 
        }
        $html.='<br />Proprietaire : '.$value['proprietaire'].'<br />';
        $nb_garnison++;
        if($value['alliance']!= ""){
          $html.= 'Alliance : '.$value['alliance'];
        }
        $html.= '<br />';
      }
    }
    
    if(is_array($this->hero)){

      foreach($this->hero as $key => $value){
        if($value['etat'] > 1){ // Déplacement en cour
          if($ally_here == 1){
                    $nb_garnison++;
                    $html.= '<br />Hero : '.$value['nom'].' ';
                    if(attPossible($value['alliance'],$value['id_proprietaire'])){
                      $html.= ahref(icoAttack(),'data.php?div=contenu&o=attaque&type=h&x='.$this->X.'&y='.$this->Y.'&id='.$value['id'],"contenu"); 
                    }
                    $html.= '<br />Proprietaire : '.$value['proprietaire'].'<br />';
                    if($value['alliance']!= ""){
                      $html.= 'Alliance : '.$value['alliance'];
                    }
                    if($value['etat'] == 2){
                      $html.= '<br />Arrivé dans :<div id="timer'.$_SESSION['timer']++.'">'.temp_seconde($value['timeEnd']-time()).'</div><br />';
                    }elseif($value['etat'] == 3){
                      $html.= '<br />Partis vers X:'.$value['X'].' Y:'.$value['Y'].' arrivés dans :<div id="timer'.$_SESSION['timer']++.'">'.temp_seconde($value['timeEnd']-time()).'</div><br />';
                    }else{
                    
                    }
                    $html.= '<br />';
          }
        }else{ // Qunlqun ici
                    $nb_garnison++;
                    $html.= '<br />Hero : '.$value['nom'].' ';
                    if(attPossible($value['alliance'],$value['id_proprietaire'])){
                      $html.= ahref(icoAttack(),'data.php?div=contenu&o=attaque&type=h&x='.$this->X.'&y='.$this->Y.'&id='.$value['id'],"contenu"); 
                    }
                    $html.= '<br />Proprietaire : '.$value['proprietaire'].'<br />';
                    if($value['alliance']!= ""){
                      $html.= 'Alliance : '.$value['alliance'];
                    }
                    $html.= '<br />';
        }       
      }
    }
    
    if($nb_garnison > 3){ // Trop de garnison pour afficher
      $html = $nb_garnison.' Garnison sur cette case <br />';
    }
     $html.= ahref('Détail','data.php?div=contenu&o=detail&cc=1&x='.$this->X.'&y='.$this->Y,"contenu");
   
 
    return $html;

  }
  //Case->HERO->ETAT 1 SUR LA CASE | 2 en train d'arriver | 3 En train de partir | 4 va combatre | 5 combat
  function afficheResumeSurVille(){
    $this->verifHero();
    $ally_here = 0;
    $nb_garnison = 0;
    $combat = false;
    $html = '';   
    if(is_object($this->combat)){
      $html.= "Votre ville subit une attaque.";
    }
    if(is_array($this->hero)){
      foreach($this->hero as $key => $value){
        if($value['etat'] == 5){//combat en cour
            $combat = true;
            
          }
        if($value['etat'] > 1 && $value['etat'] < 5){ // deplacement en cour
            $nb_garnison++;
            $dip=etatDiplomatie($value['alliance'],$value['id_proprietaire']);
            if($value['etat'] == 2){
              if($dip == 0)//enemis
                $html.=icoEnemisGauche();
              if($dip == 1)//ally
                $html.=icoAllyGauche();
              if($dip == 2)//moi
                $html.=icoMoiGauche();
            }elseif($value['etat'] == 3){//En train de partir
              if($dip == 0)//enemis
                $html.=icoEnemisDroite();
              if($dip == 1)//ally
                $html.=icoAllyDroite();
              if($dip == 2)//moi
                $html.=icoMoiDroite();
            }elseif($value['etat'] == 4){//combat
              if($dip == 0)//enemis
                $html.=icoEnemisAtt();
              if($dip == 2)//moi
                $html.=icoMoiAtt();
            }
            $html.= '<span id="timer'.$_SESSION['timer']++.'">'.temp_seconde($value['timeEnd']-time()).'</span><br />';
        }
        
        if($value['etat'] == 1){
          $dip=etatDiplomatie($value['alliance'],$value['id_proprietaire']);
            if($dip == 0)//enemis
                $html.=icoEnemisDroite();
            if($dip == 1)//enemis
                $html.=icoAllyDroite();
            if($dip == 2)//enemis
                $html.=icoMoiDroite();
                
            $html.= '<span>'.$value['nom'].'</span><br />';
        }
      }
    }
    return $html;  
  }
  
  function afficheTout($type=0,$id=0){

  $this->verifHero();

    $ally_here = 0;
    if(is_array($this->hero))
    foreach($this->hero as $key => $value){
      if(etatDiplomatie($value['id_alliance'],$value['id_proprietaire'])>0){
        $ally_here = 1;
      }
    }

    $html = '';
    $html.= '<table cellspacing="1" cellpadding="2" class="message">';
    
    if(is_array($this->ville)){
              $html.='<tr class="tr_message">
    <td>Nom Ville</td><td>Compte</td><td>Alliance</td><td>Etat</td>
    </tr>';
      foreach($this->ville as $key => $value){

        if($type=="v" AND $value['id']==$id){ // Si on vise cette ville
          $html.= '<tr class="tr_rouge">';
        }else{
          $html.= '<tr>';
        }
        $html.='
        <td>Ville : '.$value['nom'].'</td><td>'.$value['proprietaire'].'</td><td>';
        if($value['alliance']!= ""){
          $html.= $value['alliance'];
        }
        $html.= ' </td><td>';
        
        if(attPossible($value['alliance'],$value['id_proprietaire'])){
          $html.= ahref(icoAttack(),'data.php?div=contenu&o=attaque&type=v&x='.$this->X.'&y='.$this->Y.'&id='.$value['id'],"contenu"); 
        }
        
        $html.='</td></tr>';
      }
    }
    $nb_hero = 0;
    $heroANous = false; // pour la construction de ville
    if(is_array($this->hero)){
      $html.='<tr class="tr_message"><td>Nom Héro</td><td>Compte</td><td>Alliance</td><td>Etat</td></tr>';

          foreach($this->hero as $key => $value){
              if($value['etat'] > 1){ // Déplacement en cour
                if($ally_here == 1){
                      $nb_hero++;
      
                      if($type=="h" AND $value['id']==$id){ // Si on vise ce héro 
                        $html .= '<tr class="tr_rouge">';
                      }else{
                        if ($nb_hero%2 == 0){
                          $html .= '<tr class="tr_noir">';
                        }else{
                          $html .= '<tr class="tr_gris" >';
                        }
                      }
                      
                      $html.='<td>'.$value['nom'].'</td><td>'.$value['proprietaire'].'</td><td>';
                      if($value['alliance']!= ""){
                        $html.= ''.$value['alliance'];
                      }
                      $html.= '</td><td>';
                      if($value['etat'] == 2){
                        $html.= '<span> <-- <div id="timer'.$_SESSION['timer']++.'">'.temp_seconde($value['timeEnd']-time()).'</span>';
                      }elseif($value['etat'] == 3){
                        $html.= '<span><div id="timer'.$_SESSION['timer']++.'">'.temp_seconde($value['timeEnd']-time()).'</div> --> X:'.$value['X'].' Y:'.$value['Y'].' </span>';
                      }else{
                      
                      }
                      $html.= ' </td></tr>';
                }
              }else{ // Qunlqun ici
                  $nb_hero++;
                  if(etatDiplomatie($value['id_alliance'],$value['id_proprietaire']) == 2)
                    $heroANous = true;
                      if($type=="h" AND $value['id']==$id){ // Si on vise ce héro 
                        $html .= '<tr class="tr_rouge">';
                      }else{
                        if ($nb_hero%2 == 0){
                          $html .= '<tr class="tr_noir">';
                        }else{
                          $html .= '<tr class="tr_gris" >';
                        }
                      }
                      
                      $html.='<td>'.$value['nom'].'</td><td>'.$value['proprietaire'].'</td><td>';
                      if($value['alliance']!= ""){
                        $html.= ''.$value['alliance'];
                      }
                      $html.= '</td><td>';
                      
                      if(attPossible($value['alliance'],$value['id_proprietaire'])){
                        $html.= ahref(icoAttack(),'data.php?div=contenu&o=attaque&type=h&x='.$this->X.'&y='.$this->Y.'&id='.$value['id'],"contenu"); 
                      }
                      
                      $html.= ' </td></tr>';
              }
             
            }

    }
     $html.='<tr class="tr_message">
    <td> </td><td> </td><td> </td><td> </td>
    </tr>';
    $html.= '</table>';
    
    if($this->mine == 1 && $heroANous){//une mine et un de nos hero sur la mine
      $html.= '<br /><br />';
      $html.= ahref("Implanter une nouvelle ville ici",'data.php?div=contenu&o=nville&x='.$this->X.'&y='.$this->Y,"contenu");
    }

    return $html;
  }
  
  function renvoyerIdHeros(){
    if(is_array($this->hero)){
      foreach($this->hero as $key => $value){
        if($value['id_proprietaire']==$_SESSION['jid']){
          $array[$key] = $key;
        }
      }
      return $array;
    }
  }
  
  function heroSurCase($id){
    if(is_array($this->hero)){
        return (array_key_exists($id, $this->hero));
    }
    return false;
  }
  
  function affichageMap($X,$Y,$x,$y,$zone0,$zone1){ // Affichage de la map
  $this->verifHero();

    $html = '';
 
  if(count($this->ville)>0){
    //$html.= ahref('<img src="'.$GLOBALS['skin'].'map/ville1.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
    foreach($this->ville as $key => $value){
      if($value['id_proprietaire'] == $_SESSION['jid']){
        $html.= ahref('<img src="'.$GLOBALS['skin'].'map/ville/ville_moi.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
      }elseif($value['id_alliance'] == 0 AND $value['id_proprietaire'] != $_SESSION['jid']){
        $html.= ahref('<img src="'.$GLOBALS['skin'].'map/ville/ville_enemis.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
      }elseif($value['id_alliance'] != $GLOBALS['alliance']){
        $html.= ahref('<img src="'.$GLOBALS['skin'].'map/ville/ville_enemis.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
      }elseif($value['id_alliance'] == $GLOBALS['alliance'] ){
        $html.= ahref('<img src="'.$GLOBALS['skin'].'map/hero/ville_ally.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
      }else{
        print_r('Error 068');
      }
    }
  }
 
    if(count($this->hero)>0){
      foreach($this->hero as $key => $value){
        if($value['id_proprietaire'] == $_SESSION['jid']){
          if($value['etat'] == 1){
          $html.= ahref('<img src="'.$GLOBALS['skin'].'map/hero/hero_me.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
          }elseif($value['etat'] == 2 OR $value['etat']== 4){
          $html.= ahref('<img src="'.$GLOBALS['skin'].'map/hero/hero_me_move.gif" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
          }
        }elseif($value['id_alliance'] == 0 AND $value['id_proprietaire'] != $_SESSION['jid']){
          $html.= ahref('<img src="'.$GLOBALS['skin'].'map/hero/hero_enemis.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
        }elseif($value['id_alliance'] != $GLOBALS['alliance']){
          $html.= ahref('<img src="'.$GLOBALS['skin'].'map/hero/hero_enemis.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
        }elseif($value['id_alliance'] == $GLOBALS['alliance'] AND $value['id_proprietaire'] != $_SESSION['jid'] AND $value['id_alliance']!=0){
          $html.= ahref('<img src="'.$GLOBALS['skin'].'map/hero/hero_ally.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
        }else{
           print_r('Error 068');
        }
      }
    }
    
    if(is_array($this->route))
      $html.= ahref('<img src="'.$GLOBALS['skin'].'map/route'.$this->route['1'].''.$this->route['2'].''.$this->route['3'].''.$this->route['4'].'.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
    if($this->mine == 1)
       $html.= ahref('<img src="'.$GLOBALS['skin'].'map/ville1.png" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$zone0.'&y='.$zone1,"contenu");
    
    return $html;
  }
  
}
?>