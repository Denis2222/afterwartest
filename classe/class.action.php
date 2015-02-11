<?php
class Action{
    var $id; // ID de l'action
    var $type; // bat ; Batiment // move , Arrivé d'armée // Entrainement Entrainement terminé 
    var $idAction;  // ID de se qui à terminé
    var $param;
    //var $param2;
    var $date; // Date d'execution
    
    function Action(){
    
    }
    
    function newAction($type,$idAction,$param = 0,$temps){

        $this->type = $type;
        $this->idAction = $idAction;
        $this->param = $param;
        //$this->param2 = $param2;
        if($this->type == "bat")//le temps contiens le temps a ajouter
					$this->date = time()+$temps;
					
				if($this->type == "deplacement")
				  $this->date = $temps;
				  
				if($this->type == "combat")
				  $this->date = $temps;
        
        //$this->date--;	
        	
        $object = serialize($this);
        $sql = "INSERT INTO e_actions ( id ,time ,object) VALUES ('','".$this->date."', '$object' )";
        $GLOBALS['db']->query($sql);
        $id=mysql_insert_id();
        return $id;
    }
    
    function faireAction(){
        
        print_r($this);
    $html = "";
        if($this->type == "bat"){ // Si c'est la construction d'un batiment qui se termine 
          $html .= 'Core -> Batiment Maintenance'.$this->idAction.'<br />';
              $ville = new Ville();
              $ville->load($this->idAction);
        }
        
         if($this->type == "deplacement"){
            $html.= 'Core -> Vérif case X:'.$this->param['X'].' Y:'.$this->param['Y'].' Map:'.$this->param['map'].'<br />';
            $case = new CaseObject($this->param['X'],$this->param['Y'],$this->param['map']);
            $case->verifHero();
            
            if(is_object($case->combat)){
              $action = new Action();
              $timeEnd = time()+DUREE_COMBAT_CORE;
              $action->newAction("combat",0,$this->param,$timeEnd);
            }
            
         }
         
         if($this->type == "combat"){
            $html.= 'Core -> Vérification du combat sur X:'.$this->param['X'].' Y:'.$this->param['Y'].' map:'.$this->param['map'].'<br />';
            $case = new CaseObject($this->param['X'],$this->param['Y'],$this->param['map']);
            $case->verifHero(1);
            
            if(is_object($case->combat)){
              $timeEnd = time()+DUREE_COMBAT_CORE;
              $html.= 'Core -> Combat toujours en cours relancement dans '.$timeEnd.'<br />';
              $action = new Action();
             
              $action->newAction("combat",0,$this->param,$timeEnd);
            }else{
            $html.= 'Core -> Combat terminé';
            }
         }
         return $html;
    }
    
    function destroy($id){
        $sql = 'DELETE FROM e_actions WHERE id='.$id ;
        $GLOBALS['db']->query($sql);
    }
}
?>
