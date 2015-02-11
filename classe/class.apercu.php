<?php

class Apercu{

var $x;
var $y;
var $map;
var $niveau;

var $tailleX;
var $tailleY;


  function Apercu($x,$y,$map,$niveau,$partie = 0){
    $this->x = $x;
    $this->y = $y;
    $this->map = $map;
    $this->niveau = $niveau; 
    $this->partie = $partie;   
    
    if($partie == 0){
      $partie = $GLOBALS['partie'];
    }
    $partie = new Partie();
    $partie->load($this->partie);
    $this->tailleX = $partie->tailleX;
    $this->tailleY = $partie->tailleY;
  }
  
  function genererApercu(){
      $html = "<pre>".print_r($this,true)."</pre>";
      return $html;
  }
}