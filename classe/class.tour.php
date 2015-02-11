<?php
class Tour{
    var $niveau;
    var $idVille;
    var $constructionVille;
    var $ville;
    

    function Tour($niveau,$idVille,$constructionVille,&$ville){
        $this->niveau = $niveau;
        $this->idVille= $idVille;

        $this->constructionVille = $constructionVille;
        $this->ville = &$ville;
    }
    
    
    function Affiche($get){
        $onglets =' '.ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o=tour&p=a','contenu').' |
        '.ahref('Aperçu','data.php?div=contenu&v='.$this->idVille.'&o=tour&p=v','contenu').' |
        '.ahref('Espionnage','data.php?div=contenu&v='.$this->idVille.'&o=tour&p=t','contenu').' ';
        $html= decoHaut($onglets);// Affichage onglets
        
        if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 
        
          case 'v': // click sur l'Onglet entrainement
  
              if($this->ville->tour > 0){
                    $apercu = new Apercu($this->ville->x,$this->ville->y,$this->ville->map,$this->niveau,$GLOBALS['partie']);
                    $html .= $apercu->genererApercu();
              }else{
                $html.= 'Votre Tour d\'observation n\'est pas construite';
              }
              break;
              
          case 't' : // recrutement du Hero
            if($this->ville->tour > 0){
                if(!is_object($this->ville->tourContent)){
                  $this->ville->tourContent = new Espionnage();
                  $this->ville->save();
                }
                $this->ville->tourContent->espion_max = $this->ville->tour;
                $html.= $this->ville->tourContent->AfficherInterface();
                
                
            }else{
               $html.= 'Votre Tour d\'observation n\'est pas construite';
            }
          break;
          
          default:
               $html.='<div class="batiment"><h3>Tour d\'observation</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/tour.jpg"/>';
               $html.= '<p>Votre Tour d\'observation est au niveau : <span class="gras">'.$this->niveau.'</span></p>';
               $cout = coutBat("tour",$this->niveau+1);
               $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<span class="ico">'.icoTime().'</span>';
               $html.= '<br /><p>'.$GLOBALS['descriptionTour'].'</p>';

              if($this->constructionVille > 0){
                  $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
              }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=tour&p=a&lvlup=tour','contenu').'></button></div>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=tour&p=a&lvlup=tour','contenu').'></button></div>';
                }
                else
                  $html.= '<p><span class="italic">Vous n\'avez pas assez de ressources.</span></p>';

              }
          break;
        }
        $html.= $GLOBALS['decoBas'];
    return $html;
    }
}
?>
