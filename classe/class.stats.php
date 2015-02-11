<?php
class Stat{
    var $id; //même que celle d'un joueur
    var $hab;
    var $off; 
    var $def;
    var $combat;    
    var $totalHab;
    var $totalOff;
    var $totalDef;
    var $totalCombat;
    var $parties;
    
    
  function load($id){
    if($id!=null){

  		$sql = $GLOBALS["db"]->query('SELECT * FROM j_stat WHERE id = '.$id);
  		$donnees = mysql_fetch_array($sql);
		            
      $this->id = $donnees['id'];
      $this->hab = $donnees['hab'];
      $this->off = $donnees['off'];
      $this->def = $donnees['def'];
      $this->combat = $donnees['combat'];
      $this->totalHab = $donnees['totalHab'];
      $this->totalOff = $donnees['totalOff'];
      $this->totalDef = $donnees['totalDef'];
      $this->totalCombat = $donnees['totalCombat'];
      $this->parties = unserialize($donnees['parties']);
    }
	}
	
	function save(){
      $sql = "UPDATE j_stat SET
      hab = '".$this->hab."',
      off = '".$this->off."',
      def = '".$this->def."',
      combat = '".$this->combat."',
      totalHab = '".$this->totalHab."',
      totalOff = '".$this->totalOff."',
      totalDef = '".$this->totalDef."',
      totalCombat = '".$this->totalCombat."',
      parties = '".serialize($this->parties)."'
      WHERE `id` = '".$this->id."' ;";    
      $GLOBALS['db']->query($sql);
  }
	
	function insert($id)
  {  
    $a=array();
    $sql = "INSERT INTO j_stat(id,parties)
                          VALUES('".$id."','".serialize($a)."')";                  
    $GLOBALS['db']->query($sql);  
  }
  
  function calculHab(){
    $res = 0 ;
    $joueur = new Joueur();
    $joueur->loadSimple($this->id);
  	if(is_array($joueur->idVille)){
			foreach($joueur->idVille as $key => $value){
				$ville = new Ville();
				$ville->load($value['id']);
        
        $res += $ville->mineur ;
        $res += $ville->bucheron ;
        $res += $ville->paysans ;
        $res += ($ville->hdv) * 50 ;
        $res += ($ville->mine) * 50 ;
        $res += ($ville->scierie) * 50 ;
        $res += ($ville->caserne) * 50 ;
			}
		}
    $this->hab= $res;
  }
  
  /*
  $unite = array('Fantassin','Artilleur','Lance Flamme','Quad','Rock','Bob','Grosse Bertha'); // Nom unité
  valeur pour chaque unités,0 si auccune 
  */
  function ajoutOff($off){
    $this->off+=$off;
  }
  
  /*
  $unite = array('Fantassin','Artilleur','Lance Flamme','Quad','Rock','Bob','Grosse Bertha'); // Nom unité
  valeur pour chaque unités,0 si auccune
  */
  function ajoutDef($def){
    $this->def+=$def;
  }
  
  function ajoutCombat(){
    $this->combat++;
  }  
  
  /*
  calcul des points totaux
  rajout de la partie dans le liste des parties effectués
  réinitialisation des point de la partie
  */
  function finPartie($partie)
  {
      $this->calculHab();
      $this->totalHab += $this->hab;
      $this->totalOff += $this->off;
      $this->totalDef += $this->def;  
      $this->totalCombat += $this->combat;  
      $this->hab = 0;
      $this->off = 0;
      $this->def = 0;
      $this->combat = 0;
      array_push($this->parties, $partie);  
  }
  
  function dernierPartieJoue()
  {
      //echo ' p = '.end($this->parties);
      if(is_array($this->parties) && count($this->parties) > 1)
        return end($this->parties);
      else
        return -1;
  }


}
