<?php
class Caserne{
    var $niveau;
    var $idVille;
    var $constructionVille;
    var $ville;
    function Caserne($niveau,$idVille,$constructionVille,$ville){
        $this->niveau = $niveau;
        $this->idVille= $idVille;
        $this->constructionVille = $constructionVille;
        $this->ville = $ville;
    }
    
    
    function Affiche($get){
        $onglets =' '.ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=a','contenu').' |
        '.ahref('Entrainement','data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=e','contenu').' |
        '.ahref('Recherche','data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=r','contenu').' |
        '.ahref('Recrutement','data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=h','contenu').' |
        '.ahref('Description','data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=d','contenu');
        $html= decoHaut($onglets);// Affichage onglets
        
        if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 

        case 'd': //description
            $html.= '<br />';
            $html.='<div class="batiment"><h3>Caserne</h3>';
            $html.='<img src="'.$GLOBALS['skin'].'vue_ville/batiment/caserne.jpg" class="img_bat" />';
            $html.= '<div align="center">'.$GLOBALS['descriptionCaserne'].'</div></div>';
            break;  
            
        case 'r': //recherches
            $html.= '<br />';  
            if($this->ville->caserne > 0){
              //$html.= ''.$this->ville->entrainement->afficheEntrainement($this->ville).'<br />';  
              
              $Tech = $GLOBALS['recherche'];
              $Tech->loadVille($this->ville);
              $html.= $Tech->UniteAfficherTechnologie();              
            }
        break;
            
        case 'e': // click sur l'Onglet entrainement

            if($this->ville->caserne > 0){
              $html.= ''.$this->ville->entrainement->afficheTableau().'<br />';
              $html.= ''.$this->ville->entrainement->afficheEntrainement($this->ville).'<br />';
              $html.= '<div id="recruteUnite"></div>';
              $this->ville->save();
              //$this->ville->afficheListe();
              //$this->ville->entrainement->ajouterGroupe(4,50);            
              //$action = new Action();
              //$action->newAction("entrainement",$this->ville->id,0,3);

            }else{
              $html.= 'Votre caserne n\'est pas construite';
            }
        break;
            
        case 'h' : // recrutement du Hero
            if($this->ville->caserne > 0){
            if(isset($_GET['h']))
              $html .='<br />'.$_GET["h"].' <br />';
  
              $j=new Joueur();
              $j->load($_SESSION['jid']);
              $count = count($j->idHero)+count($j->idVille);
              $html.= '<br />'; 
              $html.='<div class="batiment"><h3>Caserne</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/caserne.jpg"/>';
              $html .='<br /> Entrainer un nouveu Hero : <br /> ';
              $html .='<br /> Il vous faut : <br />'; 
              $html .= coutCreationHero($count,'bois').' '.icoBois().' et '.coutCreationHero($count,'ors').' '.icoOrs().'<br />
                       <div align="center"><table><tr><td>Nom : </td><td><input type="input" class="inputText" id="nameHero" />&nbsp;</td>
                       <td>&nbsp;<button class="search" type="submit" title="Créer un héro" onClick="sendFormRecruteHero()"></button></td></table></div>';
              $html .='<br /><div id="return"></div>';
          }else{
              $html.= 'Votre caserne n\'est pas construite';
          }
        break;
        
        default:
             $html.= '<br />'; 
             $html.='<div class="batiment"><h3>Caserne</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/caserne.jpg"/>';
             $html.= '<p>Votre Caserne est au niveau : <span class="gras">'.$this->niveau.'</span></p>';
             $cout = coutBat("caserne",$this->niveau+1);
             $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<span class="ico">'.icoTime().'</span>';
             
             $html.= '<br /><br /><br />';
            if($this->constructionVille > 0){
                $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
            }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=a&lvlup=caserne','contenu').'></button></div>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=caserne&p=a&lvlup=caserne','contenu').'></button></div>';
                }
                else
                  $html.= '<p><span class="italic">Vous n\'avez pas assez de ressources.</span></p>';
            }
            
            break;
        }
        $html.= $GLOBALS['decoBas'].'';
    return $html;
    }
}
?>
