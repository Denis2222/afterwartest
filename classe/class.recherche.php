<?php
class Recherche{
    
    var $niveau;
    var $idVille;
    var $constructionVille;
    function Recherche($niveau,$idVille,$constructionVille,$ville){
        $this->niveau = $niveau;
        $this->idVille= $idVille;
        $this->constructionVille = $constructionVille;
        $this->ville = $ville;
    }
    
    
    function Affiche($get){
        $onglets =ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o=recherche&p=a','contenu').' |
        '.ahref('Recherches','data.php?div=contenu&v='.$this->idVille.'&o=recherche&p=r','contenu').' | '.ahref('Description','data.php?div=contenu&v='.$this->idVille.'&o=recherche&p=d','contenu');
        $html= decoHaut($onglets);
         if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 
        
        
            
        case 'd': //description
            $html.= '<br />';
            $html.='<div class="batiment"><h3>Centre de recherches</h3>';
            $html.='<img src="'.$GLOBALS['skin'].'vue_ville/batiment/recherche.jpg" class="img_bat" />';
            $html.= '<div align="center">'.$GLOBALS['descriptionRecherche'].'</div></div>';
            break;            
            
        case 'r':
            if($this->niveau > 0){
            $GLOBALS['recherche']->loadVille($this->ville);
            
            $html.= $GLOBALS['recherche']->afficherRechercheFaiteOuFaisable();
            $html.= $GLOBALS['recherche']->afficherTechnologie();
            }else{
              $html.= 'Votre centre de Recherche n\'est pas construit';
            }
           
            break;
        
        default:
             $html.= '<br />'; 
             $html.= '<div class="batiment"><h3>Centre de recherches</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/recherche.jpg"/>';
             $html.= '<p>Votre Centre de Recherche est au niveau : <span class="gras">'.$this->niveau.'</span><p>';
             $cout = coutBat("recherche",$this->niveau+1);
             $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<span class="ico">'.icoTime().'</span>';
             $html.= '<br /><br /><br />';
            if($this->constructionVille > 0){
                $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
            }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=recherche&p=a&lvlup=recherche','contenu').'></button>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=recherche&p=a&lvlup=recherche','contenu').'></button>';
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