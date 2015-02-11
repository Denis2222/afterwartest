<?php
class Uarm{
    
    var $niveau;
    var $idVille;
    var $constructionVille;
    function Uarm($niveau,$idVille,$constructionVille){
        $this->niveau = $niveau;
        $this->idVille= $idVille;
        $this->constructionVille = $constructionVille;
    }
    
    
    function Affiche($get){
        $onglets =ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o=uarm&p=a','contenu').' |
        '.ahref('Description','data.php?div=contenu&v='.$this->idVille.'&o=uarm&p=d','contenu');
        $html= decoHaut($onglets);
         if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 
        
        
        case 'd':
            $html.= 'Description.<br /><br /><br />';
            break;
        
        default:
             $html.= '<div class="batiment"><h3>Usine d\'Armement</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/uarm.jpg"/>';
             $html.= '<p>Votre Usine d\'Armement est au niveau : <span class="gras">'.$this->niveau.'</span><p>';
             $cout = coutBat("uarm",$this->niveau+1);
             $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<span class="ico">'.icoTime().'</span>';
             $html.= '<p>'.$GLOBALS['descriptionUarm'].'</p>';
            if($this->constructionVille > 0){
                $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
            }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=uarm&p=a&lvlup=uarm','contenu').'></button>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=uarm&p=a&lvlup=uarm','contenu').'></button>';
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