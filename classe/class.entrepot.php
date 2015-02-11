<?php
class Entrepot{
    
    var $niveau;
    var $idVille;
    var $constructionVille;
    function Entrepot($niveau,$idVille,$constructionVille){
        $this->niveau = $niveau;
        $this->idVille= $idVille;
        $this->constructionVille = $constructionVille;
    }
    
    
    function Affiche($get){
        $onglets =ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o=entrepot&p=a','contenu').' |
        '.ahref('Description','data.php?div=contenu&v='.$this->idVille.'&o=entrepot&p=d','contenu');
        $html= decoHaut($onglets);
         if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 
        
        
        case 'p':
            $html.= 'Description.<br /><br /><br />';
            $html.= '<p>'.$GLOBALS['descriptionEntrepot'].'</p>';
            break;

        case 'd': //description
            $html.= '<br />';
            $html.='<div class="batiment"><h3>Entrepôt</h3>';
            $html.='<img src="'.$GLOBALS['skin'].'vue_ville/batiment/entrepot.jpg" class="img_bat" />';
            $html.= '<div align="center">'.$GLOBALS['descriptionEntrepot'].'</div></div>';
            break;
            
                    
        default:
             $html.= '<br />';
             $html.= '<div class="batiment"><h3>Entrepôt</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/entrepot.jpg"/>';
             $html.= '<p>Votre Entrepôt est au niveau : <span class="gras">'.$this->niveau.'</span><p>';
             $cout = coutBat("entrepot",$this->niveau+1);
             $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<span class="ico">'.icoTime().'</span>';
             $html.= '<br /><br /><br />';
            if($this->constructionVille > 0){
                $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
            }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=entrepot&p=a&lvlup=entrepot','contenu').'></button>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=entrepot&p=a&lvlup=entrepot','contenu').'></button>';
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