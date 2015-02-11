<?php
class Technologie{

  var $termine;
  var $recherche;
  var $encour;
  var $finishtime;
  var $ville;
  

  function Technologie(){

  }
  
  function loadVille($ville){
    $this->ville = $ville;
    

    unset($this->termine[0]);
    unset($this->termine['']);
    unset($this->termine[1]);

    
  }
  function verifRecherche(){
    if($this->finishtime <= time()){
		if($this->encour){
		  $this->termine[$this->encour]++;
		  $this->finishtime = 0;
		  $this->encour = 0;
		}
    }
  }


/* CENTRE DE RECHERCHE */
  function afficherTechnologie(){ // CENTRE DE RECHERCHE
    $html = "";
    //$html.= print_r($GLOBALS['rechercheRequire'][2],1);
    $html.= '<table class="message" align="left">';
    //$html.= print_r($GLOBALS['recherche_nom'],1);
    $html.= '<tr class="tr_message"><td>Recherche</td><td>Requis</td></tr>';
    foreach($GLOBALS['recherche_code'] as $key => $value){
      if($this->Faisable($GLOBALS['recherche_code'][$key])!=0){
      $html.= '<tr class="tr_gris"><td>'.$GLOBALS['recherche_nom'][$key]."</td><td>";

          if(count($GLOBALS['rechercheRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheRequire'][$value] as $key_req_rech =>$value_req_rech){
              $html.= $this->afficheCouleurRequireRecherche($key_req_rech,$value_req_rech);
              //$html.= "<br />";
            }
          }
          if(count($GLOBALS['rechercheBatimentRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheBatimentRequire'][$value] as $key_req_bat =>$value_req_bat){
              $html.= $this->afficheCouleurRequireBatiment($key_req_bat,$value_req_bat);
              //$html.= "<br />";
            }
          }         

      //$html.= print_r($GLOBALS['rechercheRequire'][$value],1);
      $html.= "</td></tr>";
      }
    }
    $html.="</table>";
    return $html;
  }
  
  
  function recherchePossible($rech){
  
          if(count($GLOBALS['rechercheRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheRequire'][$value] as $key_req_rech =>$value_req_rech){
              $html.= ' '.$key_req_rech.' -> '. $value_req_rech;
            }
          }
          if(count($GLOBALS['rechercheBatimentRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheBatimentRequire'][$value] as $key_req_rech =>$value_req_rech){
              $html.= ' '.$key_req_rech.' -> '. $value_req_rech;
            }
          }    
    
  }
  
  function afficherRechercheFaiteOuFaisable(){
        unset($this->termine[0]);
  //print_r($this);
    $html.= '<br /><table class="message">';
    $html.='<tr class="tr_message"><td width="130">Recherche</td><td>Niveau</td><td>Objectifs</td></tr>';
     foreach($GLOBALS['recherche_code'] as $key => $value){
      if($this->Faisable($GLOBALS['recherche_code'][$key])==0){

      $html.= '<tr class="tr_gris"><td align="left">'.$GLOBALS['recherche_nom'][$key].' </td><td>'.$this->returnNiveauRecherche($GLOBALS['recherche_code'][$key])."</td><td align=center>";
        $ok = 0;
          if(count($GLOBALS['rechercheRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheRequire'][$value] as $key_req_rech =>$value_req_rech){
              if($this->RequireRecherche($key_req_rech,$value_req_rech) == 0){
              
              }else{
                $ok=1;
              }
            }
          }
          if(count($GLOBALS['rechercheBatimentRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheBatimentRequire'][$value] as $key_req_bat =>$value_req_bat){
              if($this->RequireBatiment($key_req_bat,$value_req_bat) == 0){
              
              }else{
                $ok=1;
              }
            }
          
          }     
          
          if($this->finishtime != 0 AND $this->encour != "")  {
            $ok=2;
          }
          $cout = coutRech($value,$this->Ameliorer($value));
          if($GLOBALS['ors']< $cout['ors'] AND $GLOBALS['bois']< $cout['bois'] AND $this->finishtime==0){
            $ok=3;
            $html.='<br />';
            $html.= $cout['bois'].'&#160;'.icoBois().'&#160;&#160;&#160;'.$cout['ors'].'&#160;'.icoOrs().'&#160;&#160;&#160;'.temp_seconde($cout['temps']/$GLOBALS['vitesse']).'&#160;'.icoTime().'<br />';
          }elseif($this->finishtime==0 AND $ok == 0){
          $html.='<br />';
            $html.= $cout['bois'].'&#160;'.icoBois().'&#160;&#160;&#160;'.$cout['ors'].'&#160;'.icoOrs().'&#160;&#160;&#160;'.temp_seconde($cout['temps']/$GLOBALS['vitesse']).icoTime().'<br />';
          }
      if($ok == 0 AND $GLOBALS['ors']>= $cout['ors'] AND $GLOBALS['bois']>= $cout['bois']){
        $html.= ahref('<div class="ameliorer"><img src="'.$GLOBALS['skin'].'/design/btn-ameliorer.png" alt="améliorer" ></div>','data.php?div=contenu&v='.$_SESSION['position'][1].'&o=recherche&p=r&action=uprecherche&rech='.$value,"contenu");
        $html.='<br />';
       }elseif($ok == 2){
        
        if($value == $this->encour){
          $html.= 'Terminé dans <span id="timer'.$_SESSION['timer']++.'">'.temp_seconde($this->finishtime-time()).'</span> ';
        }

       }elseif($ok == 3){
        $html.= 'Ressources Insufisante';
        $html.='<br /><br />';
       }
      //$html.= print_r($GLOBALS['rechercheRequire'][$value],1);
      $html.= "</td></tr>";
      }
    }
      //print_r($this);
        $html.= '</table>';
    return $html;
  }  
  
  
  
  function Faisable($value){
        $ok = 0;
        
        if(!isset($GLOBALS['rechercheRequire'][$value])){
          //echo 'Recherche non valide';
          return 1;
        }
        
          if(count($GLOBALS['rechercheRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['rechercheRequire'][$value] as $key_req_rech =>$value_req_rech){
              if($this->RequireRecherche($key_req_rech,$value_req_rech) == 0){
              
              }else{
                //echo 'Manque param Recherche';
                $ok++;
              }
            }
          }
          if(count($GLOBALS['rechercheBatimentRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            //print_r($GLOBALS['rechercheBatimentRequire'][$value]);
            foreach($GLOBALS['rechercheBatimentRequire'][$value] as $key_req_bat =>$value_req_bat){
              if($this->RequireBatiment($key_req_bat,$value_req_bat) == 0){
              
              }else{
                //echo 'Manque param Batiment';
                $ok++;
              }
            }
          }         
      if($ok == 0){ return 0; }else{ return 1; }
  }  

  function Ameliorer($recherche){
    if(is_array($this->termine) AND count($this->termine)){
      foreach($this->termine as $key => $value){
        if($key == $recherche){
          return $value+1;
        }
      }
    }
    
    
    return 0;
  }
  /* CENTRE DE RECHERCHE  FIN*/
  
  
  
  
  
  
  
  
  
  function returnNiveauRecherche($recherche){
  $niveau = 0;
    if(is_array($this->termine) AND count($this->termine) > 0 ){
      foreach($this->termine as $key => $value){
        if($recherche == $key){
          $niveau = $value;
        }else{
          //$niveau = 0;
        }
      }
    }
    return $niveau;
  }
  
  function RechCodeToNom($code){
    $cleRecherche = -1;
    foreach($GLOBALS['recherche_code'] as $key => $value){

      if($value == $code){
        $cleRecherche = $key;
      }
    }


  $nom = $GLOBALS['recherche_nom'][$cleRecherche];

    return $nom;
  }
  
  function BatCodeToNom($code){
    $cleRecherche = -1;
    foreach($GLOBALS['batiment'] as $key => $value){

      if($value == $code){
        $cleRecherche = $key;
      }
    }
    return $GLOBALS['batiment_nom'][$cleRecherche];
  }
  
  function afficheCouleurRequireRecherche($req_rech,$lvl_req_rech){
    $html = "";
    if($this->returnNiveauRecherche($req_rech) >= $lvl_req_rech){ // BON
      //$html.= '<font color="#00AA00">'.$this->RechCodeToNom($req_rech).' -> '. $lvl_req_rech.'</font><br />';
    }else{ //PAS BON
      $html.= '<font color="#FF0000">'.$this->RechCodeToNom($req_rech).' -> '. $lvl_req_rech.'</font><br />';
    }
    return $html;
  }
  
  function returnNiveauBatiment($batiment){
  $niveau = 0;
    if(isset($this->ville->$batiment)){
      return $this->ville->$batiment;
    }else{
      return 0;
    }
  }
  
  function afficheCouleurRequireBatiment($req_bat,$lvl_req_bat){
    $html = "";
    if($this->returnNiveauBatiment($req_bat) >= $lvl_req_bat){ // BON
      //$html.= '<font color="#00AA00">'.$this->BatCodeToNom($req_bat).' -> '. $lvl_req_bat.'</font><br />';
    }else{ //PAS BON
      $html.= '<font color="#FF0000">'.$this->BatCodeToNom($req_bat).' -> '. $lvl_req_bat.'</font><br />';
    }
    return $html;
  }


  function RequireBatiment($req_bat,$lvl_req_bat){
    $non = 0;
    //echo 'niv HDV :'.$this->returnNiveauBatiment("hdv");
    if($this->returnNiveauBatiment($req_bat) >= $lvl_req_bat){ // BON

    }else{ //PAS BON
      $non++;

    }
    if($non == 0){return 0;}else{return 1;}
  }
  

  function RequireRecherche($req_rech,$lvl_req_rech){ // OK si return 0 , Pas ok si return 1
    $non = 0;
    if($this->returnNiveauRecherche($req_rech) >= $lvl_req_rech){ // BON

    }else{ //PAS BON
      $non++;

    }
    if($non == 0){return 0;}else{return 1;}
  }


  

  

  
  function lancerAmelioration($recherche,$temps){
    $this->encour = $recherche;
    $this->finishtime = ($temps/$GLOBALS['vitesse']) + time();
  }
  
  
  
  
  /* RECHERCHE POUR LA CASERNE  */
    function UniteFaisable($value){
        $ok = 0;
        
        if(!isset($GLOBALS['UniteRechRequire'][$value])){
          //echo 'Recherche non valide';
          return 1;
        }
        
          if(count($GLOBALS['UniteRechRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['UniteRechRequire'][$value] as $key_req_rech =>$value_req_rech){
              if($this->RequireRecherche($key_req_rech,$value_req_rech) == 0){
              
              }else{
                //echo 'Manque param Recherche';
                $ok++;
              }
            }
          }
          if(count($GLOBALS['UniteBatRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            //print_r($GLOBALS['rechercheBatimentRequire'][$value]);
            foreach($GLOBALS['UniteBatRequire'][$value] as $key_req_bat =>$value_req_bat){
              if($this->RequireBatiment($key_req_bat,$value_req_bat) == 0){
              
              }else{
                //echo 'Manque param Batiment';
                $ok++;
              }
            }
          }         
      if($ok == 0){ return 0; }else{ return 1; }
  }
  
    function UniteAfficherTechnologie(){
    $html = "";
    $nb_pasfais = 0;
    //$html.= print_r($GLOBALS['rechercheRequire'][2],1);
    $html.= '<table class="message" align="left">';
    //$html.= print_r($GLOBALS['recherche_nom'],1);
    $html.= '<tr class="tr_message"><td width="100">Recherche</td><td>Requis</td></tr>';
    foreach($GLOBALS['unite'] as $key => $value){
      if($this->UniteFaisable($key)!=0){
        $nb_pasfais++;
        
      $html.= '<tr class="tr_gris"><td>'.$GLOBALS['unite'][$key]."</td><td>";

          if(count($GLOBALS['UniteRechRequire'][$key]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['UniteRechRequire'][$key] as $key_req_rech =>$value_req_rech){
              $html.= $this->afficheCouleurRequireRecherche($key_req_rech,$value_req_rech);
              //$html.= "<br />";
            }
          }
          if(count($GLOBALS['UniteBatRequire'][$key]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['UniteBatRequire'][$key] as $key_req_bat =>$value_req_bat){
              $html.= $this->afficheCouleurRequireBatiment($key_req_bat,$value_req_bat);
              //$html.= "<br />";
            }
          }         

      //$html.= print_r($GLOBALS['rechercheRequire'][$value],1);
      $html.= "</td></tr>";
      }
    }
    $html.="</table>";
    
    if($nb_pasfais == 0){
      return 'Toutes les unités sont disponibles .';
    }
    return $html;
  }
  /* Recherche pour la caserne FIN */
  
  /* Recherche pour les batiments */ 




    function BatimentFaisable($value){
        $ok = 0;
        
        if(!isset($GLOBALS['batRechercheRequire'][$value])){
          //echo 'Recherche non valide';
          return 1;
        }
        
          if(count($GLOBALS['batRechercheRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['batRechercheRequire'][$value] as $key_req_rech =>$value_req_rech){
              if($this->RequireRecherche($key_req_rech,$value_req_rech) == 0){
              
              }else{
                //echo 'Manque param Recherche';
                $ok++;
              }
            }
          }
          if(count($GLOBALS['batRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            //print_r($GLOBALS['rechercheBatimentRequire'][$value]);
            foreach($GLOBALS['batRequire'][$value] as $key_req_bat =>$value_req_bat){
              if($this->RequireBatiment($key_req_bat,$value_req_bat) == 0){
              
              }else{
                //echo 'Manque param Batiment';
                $ok++;
              }
            }
          }         
      if($ok == 0){ return 0; }else{ return 1; }
  }
  
    function BatimentAfficherTechnologie(){
    $html = "";
    $nb_pasfais = 0;
    //$html.= print_r($GLOBALS['rechercheRequire'][2],1);
    $html.= '<table class="message" align="left">';
    //$html.= print_r($GLOBALS['recherche_nom'],1);
    $html.= '<tr class="tr_message"><td>Recherche</td><td>Requis</td></tr>';
    foreach($GLOBALS['batiment'] as $key => $value){
      if($this->BatimentFaisable($value)!=0){
        $nb_pasfais++;
        
      $html.= '<tr class="tr_gris"><td>'.$GLOBALS['batiment_nom'][$key]."</td><td>";

          if(count($GLOBALS['batRechercheRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['batRechercheRequire'][$value] as $key_req_rech =>$value_req_rech){
              $html.= $this->afficheCouleurRequireRecherche($key_req_rech,$value_req_rech);
              //$html.= "<br />";
            }
          }
          if(count($GLOBALS['batRequire'][$value]) > 0){ // Si ya du prerequis en recherche
            foreach($GLOBALS['batRequire'][$value] as $key_req_bat =>$value_req_bat){
              $html.= $this->afficheCouleurRequireBatiment($key_req_bat,$value_req_bat);
              //$html.= "<br />";
            }
          }         

      //$html.= print_r($GLOBALS['rechercheRequire'][$value],1);
      $html.= "</td></tr>";
      }
    }
    $html.="</table>";
    
    if($nb_pasfais == 0){
      return 'Tous les batiments sont disponibles .';
    }
    return $html;
  }

  /* Recherche pour les batiments FIN*/ 
  
  
}
?>