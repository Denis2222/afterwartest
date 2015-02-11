<?php
class Map{
    var $name;
    var $zone;  //$zone = array('5','15','4')
    var $map; // tableau X Y
    
    function Map($name,$zone){
        $this->name = $name;
        $this->zone = $zone;
    }
    
    function affiche(){
	
		$html = "";
		switch($this->zone[2]){
			case 4:
				$marge = 0;
				break;
			case 3:
				$marge = 1;
				break;
			case 2:
				$marge = 2;
				break;
			case 1:
				$marge = 3;
				break;
			default:
				$marge = 0;
				break;
		}
		$X =$marge;
		$Y =$marge;
		for($x=$this->zone[0]-$this->zone[2];$x<=$this->zone[0]+$this->zone[2];$x++){
			for($y=$this->zone[1]-$this->zone[2];$y<=$this->zone[1]+$this->zone[2];$y++){
				//$html .= ' $x'.$x.' $y'.$y.' ';
				if(isset($this->map[$x][$y])){
					if(isset($_GET['tox']) AND $_GET['tox'] == $x AND isset($_GET['toy']) AND $_GET['toy'] == $y){
					  $html.= ahref('<img src="'.$GLOBALS['skin'].'map/'.$this->map[$x][$y]['type'].'.jpg" class="d'.$X.''.$Y.' cursor "/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$this->zone[0].'&y='.$this->zone[1],"contenu");
					}else{
					  $html.= ahref('<img src="'.$GLOBALS['skin'].'map/'.$this->map[$x][$y]['type'].'.jpg" class="d'.$X.''.$Y.'"/>','data.php?div=contenu&action=move&tox='.$x.'&toy='.$y.'&cc=1&x='.$this->zone[0].'&y='.$this->zone[1],"contenu");
					}
					if(isset($this->map[$x][$y]['object']) AND is_object($this->map[$x][$y]['object'])){
					  
					  $case = $this->map[$x][$y]['object'];
					   //echo '[_______]';
					  $html .= $case->affichageMap($X,$Y,$x,$y,$this->zone[0],$this->zone[1]);
					   //echo '[_______]<br />';
					}
				}else{
					$html.= '<img src="'.$GLOBALS['skin'].'map/bord.png" class="d'.$X.''.$Y.'"/>';
				}
				$Y++;
			}
			$Y=$marge;
			$X++;
		}
		$html.= ahref('<img src="./skin/original/map/avancer.png" class="d'.($this->zone[2]+$marge).'0"/>','data.php?div=contenu&cc=1&x='.$this->zone[0].'&y='.($this->zone[1]-1),"contenu");
		$html.= ahref('<img src="./skin/original/map/reculer.png" class="d'.($this->zone[2]+$marge).''.(4+$this->zone[2]+$marge).'"/>','data.php?div=contenu&cc=1&x='.$this->zone[0].'&y='.($this->zone[1]+1),"contenu");
		$html.= ahref('<img src="./skin/original/map/gauche.png" class="d0'.($this->zone[2]+$marge).'"/>','data.php?div=contenu&cc=1&x='.($this->zone[0]-1).'&y='.($this->zone[1]),"contenu");
		$html.= ahref('<img src="./skin/original/map/droite.png" class="d'.(4+$this->zone[2]+$marge).''.($this->zone[2]+$marge).'"/>','data.php?div=contenu&cc=1&x='.($this->zone[0]+1).'&y='.($this->zone[1]),"contenu");
		return $html;
    
    }
    
    function afficheCase($x,$y){
      $object = $this->map[$x][$y]['object'];
      if(is_object($object)){
        $case = $object;
        //print_r($case);
        $html = $case->afficheResume();
      }
        
        //$html.= '<br /><br />Type de case : '.$this->map[$x][$y]['type'];
      
      
      
      return $html;
    }
    
    function loadMap(){
         print_r($this);
        $x=$this->zone[0]-$this->zone[2];
        $y=$this->zone[1]-$this->zone[2];
        $X=$this->zone[0]+$this->zone[2];
        $Y=$this->zone[1]+$this->zone[2];

        $sql="SELECT *
        FROM ".$this->name."
        WHERE 
            (Y >= ".$y.")AND 
            (Y <= ".$Y.")AND 
            (X >= ".$x.")AND 
            X <= ".$X;
        
        echo $sql;


        $return = $GLOBALS['db']->query($sql);


        while($row = mysql_fetch_array($return)){
            $this->map[$row['X']][$row['Y']]['mvt']=$row['mvt'];
            $this->map[$row['X']][$row['Y']]['type']=$row['type'];
            $this->map[$row['X']][$row['Y']]['object']=unserialize($row['object']);
        }
        
        /*$sql="SELECT *
        FROM j_hero
        WHERE 
            (Y >= ".$y.")AND 
            (Y <= ".$Y.")AND 
            (X >= ".$x.")AND 
            X <= ".$X;
        $return = $GLOBALS['db']->query($sql);
        while($row = mysql_fetch_array($return)){
            $this->map[$row['X']][$row['Y']]['id']=$row['id'];
            $this->map[$row['X']][$row['Y']]['nom']=$row['nom'];
        }
        */
    }
    
    function suppression($map){
        $GLOBALS["db"]->query("DROP TABLE $map");
    }
}
?>