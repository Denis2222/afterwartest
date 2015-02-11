<?php
class Espionnage{

var $espion_max=0;
var $espion=0;
var $arriveTime=0;
var $finishTime=0;
var $gotoX=0;
var $gotoY=0;
var $X;
var $Y;
var $map="";
var $rapport;

var $vitesse=250;

  function Espionnage(){
  
  }

  function AfficherInterface(){
    $html.= 'Vous avez '.$this->espion_max.' Espions ';
    $html.= '<br /><br /><br />
    <div id="return"></div>
    ';
    if($this->finishTime == 0){
      $html.='
      <table>
      <tr><td colspan ="2">Envoyer des espion en :</td></tr>
      <tr><td>X  :</td><td><input id="Xspy" class="inputInt" name="Xspy" maxlength="4" autocomplete="off" type="text" value=""></td></tr>
      <tr><td>Y  :</td><td><input id="Yspy" class="inputInt" name="Yspy" maxlength="4" autocomplete="off" type="text" value=""></td></tr>
      <tr><td colspan ="2">&nbsp;<button class="search" type="submit" title="Envoyer" onClick="sendFormEspion()"></button></td></tr>
      </table>';
    }else{
      if($this->arriveTime > time() AND $this->finishTime > time()){
        $html.='Les espions seront de retour de X:'.$this->gotoX.' Y:'.$this->gotoY.' dans <span id="timer'.$_SESSION['timer']++.'">'.temp_seconde(($this->arriveTime-time())).'</span> s ';
      }
    }
    return $html;
  }
  
  function envoyerEspion($x,$y,$map,$hereX,$hereY){
    $this->gotoX = $x;
    $this->gotoY = $y;
    $this->X = $hereX;
    $this->Y = $hereY;
    $this->map = $map;
    $temps = $this->calculDeplacement($this->gotoX,$this->gotoY,$hereX,$hereY);
    $this->arriveTime = $temps+time();
    $this->finishTime = $temps+$temps+time();

  }
  
  
  function calculDeplacement($x,$y,$X,$Y){ // Pythagore est dans la place
    if($x>$X){
      $xX = $x-$X;
    }else{
      $xX = $X-$x;
    }
    if($y>$Y){
      $yY = $y-$Y;
    }else{
      $yY = $Y-$y;
    }
    $diag = sqrt( pow($xX,2) + pow($yY,2) ); // PythPyth 
    
    
    $time_classique = ($diag*$GLOBALS['TempsCase'])/10; // Diagonale * le temps de parcourir une case 
    $time = $time_classique;
    $time = round($time);
    return $time;
  }
  
  function genererMessage($nivTour,$jid){

    $case = new CaseObject($this->gotoX,$this->gotoY,$this->map);
    if(count($case->ville) > 0 OR count($case->ville) > 0){
    if($nivTour >0){
    
    $rapport = "";
    $ville = new Ville();
     foreach($case->ville as $key => $value){
        $ville->loadSimple($value['id']);    
     }



    $rapport.= "Rapport de la ville ".$ville->nom." de ".$value['proprietaire']."<br /><br />";
    foreach($GLOBALS['batiment'] as $keybat => $valuebat){
      if($ville->$valuebat > 0){
        $rapport.= $GLOBALS['batiment_nom'][$keybat]." :".$ville->$valuebat."<br />";
      }
    }
        

    }  
    if($nivTour >1){
      $rapport .= "<br /> Nombre de paysans : ".($ville->paysans+$ville->mineur+$ville->bucheron)."<br />";
    }
    

    
    /*
    
    3 pop
    
    if($nivTour >2){
      $rapport .= "<br /> Ressources : ".$ville->coefAugBois."<br />";
    }
    */
    if($nivTour ==5){
      $rapport .= "Garnison de ".$ville->nom."<br />";
      $rapport .= $ville->garnison->AfficherUnite(1);
      if(count($case->hero) > 0){
      $rapport.='<br /> Hero en garnison :<br />';
        foreach($case->hero as $key => $value){
          $rapport .= "- ".$value['nom']." Alliance : ".$value['alliance']."<br />";
        }
      }
    }
    
    if($nivTour >5){
      $rapport .= "Garnison de ".$ville->nom."<br />";
      if(is_object($ville)){
        $rapport .= $ville->garnison->AfficherUnite(1);
      }
      if(count($case->hero) > 0){
        $rapport.='<br /> Hero en garnison :<br />';
        foreach($case->hero as $key => $value){
          $hero = new Hero();
          $hero->load($value['id']);
          $rapport.= "- ".$value['nom']." ";
          
          if($value['alliance'] != "")
          $rapport.= "Alliance : ".$value['alliance']."";
          
          $rapport.= "".$hero->garnison->AfficherUnite(1);
          $rapport.= "";
        }
      }
    }
    /*
    4 hero
    
    5  garnison
    
    
    */
    
    }else{
      $rapport .=" Case vide";
    }
      $time = time();
      $rapport.="<br />Date rapport : ".date("m j, Y, G:i:s",$time)." ";
      $rapport = addslashes($rapport);
      envoyer_rapport($jid,'Rapport espionnage',$rapport);
    $this->rapport = $rapport;
  }
}
?>