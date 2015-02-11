<?php
class Garnison{

    var $unite = array();
    var $caracs = array();
    var $stats = array();
    var $objet = array();
    var $xp;
    var $niveau;
    
    var $pCaracs = 0; // Points de caracs à répartir
    
    var $degat_att;
    var $degat_def;
    
    function Garnison(){

    }
    
    
    function AjoutUnite($type,$nb){
        $this->unite[$type]+=$nb;
    }
    
    function SortirUnite($type,$nb){
        if($this->unite[$type]>=$nb){
            $this->unite[$type]=$this->unite[$type]-$nb;
            return $nb;
        }else{
        $uniteEnleve = $this->unite[$type];
            $this->unite[$type]=0;
            return $uniteEnleve;
        }
    }
    
    function nouvelleRecrue($newUnite){
        if(isset($newUnite[0])){
            foreach($newUnite as $unite){
                $this->AjoutUnite($unite,1);
            }
        }
    }
    
    function compterUnite(){
      $nb=0;
      if(is_array($this->unite))
      foreach($this->unite as $key => $value){

        $nb += $value;
      }
      return $nb;
    }
    
    function appliquerDegat(){
    
    //while($this->degat_att > 500 OR $this->degat_def > 500){
    //do{
      if($this->degat_att > 0){
        if(is_array($this->unite))
        foreach($this->unite as $key => $value){
          if($this->degat_att < 200){
            $key = mt_rand(0,(count($this->unite)-1));
          }
          if($this->unite[$key] > 0){
                if($this->degat_att <200){
                  $max = 2;
                }else{
                  $max = $this->unite[$key];
                }
              for($x=1;$x<=$max;$x++){
                if($GLOBALS['PVunite'][$key] < $this->degat_att){
                  $this->degat_att = $this->degat_att - $GLOBALS['PVunite'][$key];
                  $this->SortirUnite($key,1);
                }
              }
            
          }
        }
      }
      //}while($this->degat_att > 50 OR $this->compterUnite() == 0);
      
     // do{
      if($this->degat_def > 0){

        if(is_array($this->unite))
        foreach($this->unite as $key => $value){
            if($this->degat_def < 200){
              $key = mt_rand(0,(count($this->unite)-1));
            }
          if($this->unite[$key] > 0){
                if($this->degat_def <200){
                  $max = 2;
                }else{
                  $max = $this->unite[$key];
                }
            for($x=1;$x<=$this->unite[$key];$x++){
              if($GLOBALS['PVunite'][$key] < $this->degat_def){
                $this->degat_def = $this->degat_def - $GLOBALS['PVunite'][$key];
                $this->SortirUnite($key,1);
              }
            }
          }
        }
      }
      
    // }while($this->degat_def > 50 OR $this->compterUnite() == 0);
     // }
      
      if($this->compterUnite() > 0){
        return 0; // Encore vivant
      }else{
        return 1; // THE END
      }
      
    }
    
    function deplacerUnite($idVille,$idHero,$sens,$uVille,$uHero){
        $ville = new Ville();
      	$ville->load($idVille);
      	$hero = new Hero();
      	$hero->load($idHero);
      	if($hero->X != $ville->X || $hero->Y != $ville->Y || $hero->map != $ville->map || $hero->idCompte != $GLOBALS['idjoueur']) 
          return 'Le héro n\est pas sur la ville.'; 
        if($sens == 0 ){ // Ville => Hero
          for($i=0;$i<count($GLOBALS['unite']);$i++){
              $uniteEnleve = $ville->garnison->SortirUnite($i,$uVille[$i]);
              $hero->garnison->AjoutUnite($i,$uniteEnleve);              
            }                 
        }elseif ($sens == 1){ // Hero => Ville
           for($i=0;$i<count($GLOBALS['unite']);$i++){             
              $uniteEnleve = $hero->garnison->SortirUnite($i,$uHero[$i]);
              $ville->garnison->AjoutUnite($i,$uniteEnleve);             
            }
        }
        $ville->save();
        $hero->save();    
        //echo ' error' ;
    }
    
    
    function AfficherUnite($image = 0){
      $html = "";
        if($image != 0){
            $html.= '<table cellspacing="1" cellpadding="2" width="130">  
            <tr >    
              <td width="22"></td>
            </tr>          
            <tr>
              <td>  ';
        }
        
        $html.= '<table border ="1">';
        if(is_array($this->unite)){
           $nb = 0;
            foreach($this->unite as $key => $unite){
                if($this->unite[$key]!=0){
                  $nb++;
                    $html.= '<tr>';
                    if($image != 0){
                        $html.='<td>'.icoUnite($key,20).'</td>';
                    }
                    $html .= '<td width="60" align="center">'.$GLOBALS['unite'][$key].'</td><td align="right">'.$this->unite[$key].'</td></tr>';
                }
            }

        }
        $html .= '</table>';

        if($image != 0){
            $html.= '</td>  
            </tr>
            <tr >
              <td></td>
            </tr>
          </table> ';
        }
        
                     if($nb == 0){
              $html.= "<center>Pas d'unités en garnison</center>";
            }
        
        return $html; 
    }
    
    
    function AfficherTransfert($vNom,$v,$hNom,$h,$pHero){
		    $html .= '<br />';
        $html .= '<div class="batiment"><h3>Gestion des unités</h3>';  
        $html .= '<div align="center" style="height-min:500px;">';
        $html .= '<div id="alert"></div>';
        $html .='<table align="center" width="100%" cellspacing="0" cellpadding="0">';
        $html .='<tr>';
        $html .='<th width="80">Ville</th><th colspan="7"></th><th width="80">Héros</th>';
        $html .='</tr>';
        $html .= '<tr><td align="center">'.$vNom.'</td><td align="center" colspan="7" height="30" valign="top">&nbsp;<button class="grandGauche" type="submit" title="Gauche" onClick="depUnite('.$v->id.','.$h->id.',0,'.$pHero.')"></button></td><td align="center">'.$hNom.'</td></tr>';
        $nb=0;
        for($i=0;$i<count($GLOBALS['unite']);$i++){
          if($v->garnison->unite[$i] !=0 || $h->garnison->unite[$i] != 0){ 
          $nb++;
              //foreach($vGarnison->unite as $key => $unite){
              if($nb%2==0){ 
              $html .='<tr class = "tr_gris" align="center" height="38">';
              }else{
              $html .='<tr class = "tr_noir" align="center" height="38">';
              }
              
            	$html .='<td align="center">'.icoUnite($i).'</td>';
            	$html .='<td align="center" width="67">'.$v->garnison->unite[$i].'</td>';
            	$html .="<td width='40'><button class=\"petiteDroite\" type=\"submit\" title=\"Déplacer\" onClick=\"document.getElementById('vu".$i."').value=".$v->garnison->unite[$i]."\"></button></td>";//remplirInput("vu$i",'.$v->garnison->unite[$i].')
            	$html .='<td align="left" width="45"><input id="vu'.$i.'" class="inputSmallInt" name="vu'.$i.'" maxlength="8" autocomplete="off" type="text" value="0"> </td>';
            	$html .='<td></td>';
              $html .='<td align="right" width="45"> <input id="hu'.$i.'" class="inputSmallInt" name="hu'.$i.'" maxlength="8" autocomplete="off" type="text" value="0"> </td>';
            	$html .="<td width='40'><button class=\"petiteGauche\" type=\"submit\" title=\"Déplacer\" onClick=\"document.getElementById('hu".$i."').value=".$h->garnison->unite[$i]."\"></button></td>";
            	$html .='<td align="center" width="67">'.$h->garnison->unite[$i].'</td>';		
    			   $html .='<td align="center">'.icoUnite($i).'</td>';
    			   $html .='</tr>';
    		  
    		  }
    		}
    		$html .= '<tr><td></td><td colspan="7" align="center" height="30" valign="bottom">&nbsp;<button class="grandDroite" type="submit" title="Droite" onClick="depUnite('.$v->id.','.$h->id.',1,'.$pHero.')"></button></td></tr>';
    		$html .= '</table></div></div>';
        return $html; 
    }
    
    
    function AfficherInventaire($var=0){

      $html = 'Points d\'éxpérience : '.$this->xp.'<br />Niveau : '.$this->niveau.' (+'.($this->niveau*10).'%)<br />
	  <br /> Manque '.$this->XPManquant($this->xp).' XP Pour niveau '.($this->niveau+1).'';
	  if($var ==1){
	  $html.= '
      <table class="paysan">
        <tr><td width="130">Points de caracs disponible </td><td colspan="2" align="left"><div id="totalPaysans">'.$this->pCaracs.'</div></td></tr>
        <tr><td width="130">Attaque :  </td><td>'.$this->caracs['att'].'</td><td>&nbsp;<button class="plus" onClick="changeCaracs(0)"></td></tr>
        <tr><td width="130">Defense :  </td><td>'.$this->caracs['def'].'</td><td>&nbsp;<button class="plus" onClick="changeCaracs(1)"></td></tr>
        <tr><td width="130">Vitesse :  </td><td>'.$this->caracs['vit'].'</td><td>&nbsp;<button class="plus" onClick="changeCaracs(2)"></td></tr>
        <tr><td width="130">Motivation :  </td><td>'.$this->caracs['mot'].'</td><td>&nbsp;<button class="plus" onClick="changeCaracs(3)"></td></tr>
      </table>
      <div id="changeCaracs"> </div>
    ';
	  
	  }else{
	  	  $html.='<br /><br />Caractéristiques :<br />
       Attaque : '.$this->caracs['att'].'<br />
       Défense : '.$this->caracs['def'].'<br />
       Vitesse : '.$this->caracs['vit'].'<br />
       Motivation :'.$this->caracs['mot'].'<br />
       Points de caracs :'.$this->pCaracs.'';
	  }



	   
	   
	   $html.='<br />
	   Troupes :'.$this->AfficherUnite(0).'
	   <br /><br /> Attaque globale : '.$this->stats['att'].'<br />
       Defense globale : '.$this->stats['def'].'<br /><br />
	   
	   
	   ';

	   
    return $html;
    }
    
    function AjoutObjet($type){
      if(isset($this->objet[$type])){
        return false;
      }else{
        $this->objet[$type]=0;
        return true;
      }
    }
    
    function SortirObjet($type){
        unset($this->objet[$type]);
    }
    
    function ActiverObjet($type){
        $this->objet[$type]=1;
    }
    
    function initCaracs(){
        $this->caracs['att']=2;
        $this->caracs['def']=2;
        $this->caracs['vit']=1;
        $this->caracs['mot']=2;
        
        $this->stats['att']=0;
        $this->stats['def']=0;
        
        $this->xp=100;
        $this->niveau=1;
    }
    
  function genererStats(){
    //Attaque
    if($this->xp < 100){
      $this->initCaracs();
    }
    $this->appliquerDegat();
    $this->appliquerDegat();
  	$this->niveau = $this->Niv($this->xp);
      $att = $this->caracs['att']*$this->niveau;
      $def = $this->caracs['def']*$this->niveau;
        if(is_array($this->unite))
        foreach($this->unite as $key => $value){
          $att+=($value*($GLOBALS['Aunite'][$key]))+(($value*($GLOBALS['Aunite'][$key]))/10)*$this->niveau;// NB unite * AttaqueUnite            + 10% par niveau du  héro
          $def+=($value*($GLOBALS['Dunite'][$key]))+(($value*($GLOBALS['Dunite'][$key]))/10)*$this->niveau;// NB unite * DefenseUnite           + 10% par niveau du  héro
        }
      $this->stats['att']=round($att+(($att/10)*$this->caracs['att']),2)+$this->caracs['mot'];// Attaque Globale +  10% par points de caracs 
      $this->stats['def']=round($def+(($def/10)*$this->caracs['def']),2)+$this->caracs['mot'];// Defense Globale +  10% par points de caracs 
      $this->appliquerDegat();
    if($this->appliquerDegat() == 1){
      return 1;
    }
      
      return 0;
  }
	
	
	// CALCUL DE NIVEAU
	function Niv($XP){
       $NIV_exact = log($XP/100) / log(1.1) + 1 ;
       $NIV_inf = floor($NIV_exact);
       return $NIV_inf; // indique le niveau du joueur
	} 

	function NivSup($XP){
		$NIV_sup = $this->Niv($XP) + 1 ;
		return $NIV_sup; // indique le prochain niveau du joueur
	}



	function XPManquant($XP){
	   $niveau = $this->NivSup($XP) ;
	   $XP_suivant =    round(100*exp(($niveau-1)*log(1.1)));  
	   $XP_manquant =  $XP_suivant - $XP;
	   return $XP_manquant ;// indique le nombre de XP avant le prochain niveau 
	}
}
?>
