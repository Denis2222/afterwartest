<?php
class Marche{
    var $niveau;
    var $idVille;
    var $constructionVille;
    var $ville;
    var $nom = "Magasin";
    var $code = "marche";
    
    function Marche($niveau,$idVille,$constructionVille,$ville){
        $this->niveau = $niveau;
        $this->idVille= $idVille;

        $this->constructionVille = $constructionVille;
        $this->ville = $ville;
    }
    
    
    function Affiche($get){
        $onglets =' '.ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o='.$this->code.'&p=a','contenu').' |
        '.ahref('Aperçu','data.php?div=contenu&v='.$this->idVille.'&o='.$this->code.'&p=v','contenu').' |
        '.ahref('Autre','data.php?div=contenu&v='.$this->idVille.'&o='.$this->code.'&p=t','contenu').' ';
        $html= decoHaut($onglets);// Affichage onglets
        
        if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 
        
          case 'v': // 
  

          break;
              
          case 't' : 
            if($this->ville->tour > 0){
                
                $html .='Data autre';
            }else{
               $html.= 'Votre '.$this->nom.' n\'est pas construit';
            }
          break;
          
          default:
               $html.='<div class="batiment"><h3>'.$this->nom.'</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/'.$this->code.'.jpg"/>';
               $html.= '<p>Votre '.$this->nom.' est au niveau : <span class="gras">'.$this->niveau.'</span></p>';
               $cout = coutBat($this->code,$this->niveau+1);
               $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<span class="ico">'.icoTime().'</span>';
               $html.= '<br /><p>'.$GLOBALS['descriptionMarche'].'</p>';

              if($this->constructionVille > 0){
                  $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
              }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o='.$this->code.'&p=a&lvlup='.$this->code.'','contenu').'></button></div>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o='.$this->code.'&p=a&lvlup='.$this->code.'','contenu').'></button></div>';
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
