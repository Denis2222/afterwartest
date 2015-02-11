<?php
class HotelDeVille{
    
    var $niveau;
    var $idVille;
    var $constructionVille;
    var $ville; // Contien l'objet ville supérieur

    function HotelDeVille($niveau,$idVille,$constructionVille,$ville){
        $this->niveau = $niveau;
        $this->idVille= $idVille;
        $this->constructionVille = $constructionVille;
        $this->ville = $ville;
    }
    
    
    function Affiche($get){
        $onglets =ahref('Amélioration','data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=a','contenu').' |
        '.ahref('Garnison','data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=g','contenu').' |
        '.ahref('Paysans','data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=p','contenu').' |
        '.ahref('Construction','data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=c','contenu').' |
        '.ahref('Description','data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=d','contenu');
        
        $html= decoHaut($onglets);
         if(!isset($get['p'])){ $get['p'] = "aucun";}
        switch ($get['p']){ // Onglet 

        case 'c': //description
            $html.= '<br />';
            $GLOBALS['recherche']->loadVille($this->ville);
            $html.= $GLOBALS['recherche']->BatimentAfficherTechnologie();
            
        break;
            
            
        case 'g'://garnison

            $case = New CaseObject($this->ville->X,$this->ville->Y,$this->ville->map);
            $arrayHero = $case->renvoyerIdHeros();
            
            $hero = 1;
            if(is_array($arrayHero)){
              foreach($arrayHero as $key => $value){
                $unhero = new Hero();
                $unhero->load($key);
                if($unhero->etat == 1){
                  $hSurVille[$hero] = $unhero;
                  $hero++;
                }
              }
            }
            
            //$html.='=====================================';
            if(count($hSurVille)==0){
              $html.= $this->ville->garnison->AfficherUnite(1);
            }else{
              if(count($hSurVille)==1)
                $html.= $this->ville->garnison->AfficherTransfert($this->ville->nom,$this->ville,$hSurVille[1]->nom,$hSurVille[1],1);
              else {        
                if(!isset($get['he']))
                  $get['he']=1;
                $n=1;
                if(is_array($hSurVille))
                foreach($hSurVille as $key => $value){
                  if($value->etat == 1)
                    $n++;
                }
                if($n >2){
                $html.='<div class="onglet_garnison" align="center">';
                $nh=1;
                if(is_array($hSurVille))
                foreach($hSurVille as $key => $value){
                  if($value->etat == 1){
		    if($get['he'] ==$nh)
		      $html.='<span class="onglet_actif"><a href="#"  title="'.$value->nom.'"'.onClick('data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=g&he='.$nh,'contenu').'>'.$value->nom.'</a></span>';
		    else
		      $html.='<span class="onglet"><a href="#" class="onglet" title="'.$value->nom.'"'.onClick('data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=g&he='.$nh,'contenu').'>'.$value->nom.'</a></span>';
                  
                    $nh++;
                  }
  
                }
                $html.='</div><br />';
                }
                if($hSurVille[$get['he']]->etat == 1 )
                  $html.= $this->ville->garnison->AfficherTransfert($this->ville->nom,$this->ville,$hSurVille[$get['he']]->nom,$hSurVille[$get['he']],$get['he']);
              }
            }
		break;
        
        case 'p'://paysans
            $html.= '<br /><br />';
            $html.= '<div class="batiment"><h3>Gestion des paysans</h3>
              <div id="changePaysans" align="center">
              <table >
                <tr><td width="160">Paysans disponible : </td><td colspan="2" align="left"><div id="totalPaysans">'.$this->ville->paysans.'</div></td></tr>
                <tr><td width="160">Paysans dans la mine :  </td><td>'.$this->ville->mineur.' / '.($this->ville->mine*NB_PAYSANS_PA_NIV).' </td><td>&nbsp;<button class="plus10" onClick="changePaysans(0,10)"></button><button class="plus" onClick="changePaysans(0,1)"></button>&nbsp;<button class="moins" onClick="changePaysans(1,1)"></button>&nbsp;<button class="moins10" onClick="changePaysans(1,10)"></button></td></tr>
                <tr><td width="160">Paysans à la scierie :  </td><td>'.$this->ville->bucheron.' / '.($this->ville->scierie*NB_PAYSANS_PA_NIV).'</td><td>&nbsp;<button class="plus10" onClick="changePaysans(2,10)"></button><button class="plus" onClick="changePaysans(2,1)"></button>&nbsp;<button class="moins" onClick="changePaysans(3,1)"></button>&nbsp;<button class="moins10" onClick="changePaysans(3,10)"></button></td></tr>
              </table>
              </div>
            ';
            $html.='<br/><br/>Recruter un paysan coute :<br/> &#160;&#160;'.COUT_PAYSANS_BOIS.'<span class="ico">'.icoBois().'</span>et  '.COUT_PAYSANS_ORS.'  <span class="ico">'.icoOrs().'</span>';            
            $html.='<br /><br />	
              <table class="paysan">
                <tr><td colspan="2" align="center">Nombre de paysan à recruter:</td></tr>                   
                <tr><td><input id="nbPaysans" class="inputInt" name="oldPass" maxlength="50" autocomplete="off" type="text" value="0"></td><td align="right">&nbsp;<button class="search" type="submit" title="Search" onClick="recrutePaysans()"></button></td></tr>
                <tr><td><div id="returnPaysans"> </div></td></tr>
                
              </table>
              <center>
              <table align="center">
              <tr><td colspan="2"><center>
                    Sinon vous pouvez toujours tenter de <br />
                    recruter des paysans dans la jungle.<br />
                    Laisse trainer cette adresse : <br />http://www.after-war.com/help.php?i='.$_SESSION['jid'].'
                  </td>
              </tr>
              
              </table>
              </center>
              
              </div><br/>';
            
            break;
            
        case 'd': //description
            $html.= '<br />';
            $html.='<div class="batiment"><h3>Hotel de ville</h3>';
            $html.='<img src="'.$GLOBALS['skin'].'vue_ville/batiment/hdv.jpg" class="img_bat" />';
            $html.= '<div align="center">'.$GLOBALS['descriptionHdv'].'<br /><br />
            
                <table>
  	
                    <tr><td>Tape le nouveau nom de ta ville :</td></tr>
                    <tr><td align="center"><input id="newVilleName" class="inputText" name="newVilleName" maxlength="50" autocomplete="off" type="text" value="'.$this->ville->nom.'"></td></tr>
                    <tr><td align="center"><button class="search" type="submit" title="Search" onClick="sendFormNewVilleName()"></button></td></tr>
                    </tr>
                    <tr><td><div id="returnPass"> </div></td></tr>

                </table>

                </div></div>';
            
        break;
            
        default:
             $html.= '<br />'; 
             $html.= '<div class="batiment"><h3>Hotel de ville</h3><img class="img_bat" src="'.$GLOBALS['skin'].'vue_ville/batiment/hdv.jpg" />';
             $html.= '<p>Votre Hotel de ville est au niveau : <span class="gras">'.$this->niveau.'</span></p>';
             $cout = coutBat("hdv",$this->niveau+1);
             $html.= 'Pour l\'améliorer il vous faut :<br />'.$cout['bois'].'<span class="ico">'.icoBois().'</span> | '.$cout['ors'].'<span class="ico">'.icoOrs().' </span> | '.temp_seconde($cout['temps']/$GLOBALS['vitesse']).'<span class="ico">'.icoTime().'</span>';
             $html.= '<br /><br /><br />';
            if($this->constructionVille > 0){
                $html.= '<p><span class="italic">Une amélioration est en cours dans cette ville.</span></p>';
            }else{
                if($GLOBALS['ors'] >= $cout['ors'] && $GLOBALS['bois'] >= $cout['bois'])
                {
                  if($this->niveau == 0 )
                    $html.='&nbsp;<button class="construire" type="submit" title="Construire" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=a&lvlup=hdv','contenu').'></button></div>';
                  else
                    $html.='&nbsp;<button class="ameliorer" type="submit" title="Améliorer" '.onClick('data.php?div=contenu&v='.$this->idVille.'&o=hdv&p=a&lvlup=hdv','contenu').'></button></div>';
                }
                else
                  $html.= '<p><span class="italic">Vous n\'avez pas assez de ressources.</span></p>';

            }
/*
for($i=0;$i<15;$i++){
$cout = coutBat("hdv",$i);
$html.= 'lvl '.$i.' c '.temp_seconde(round($cout['temps']/$GLOBALS['vitesse'])).'<br />';
}
*/
            break;
        }
        $html.= $GLOBALS['decoBas'];
    return $html;
    }
}
?>
