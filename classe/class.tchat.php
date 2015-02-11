<?php
class Tchat{

var $id;
var $message;
var $type;
var $type_ID;


  function Tchat($type,$type_ID){
  
  $sql = "SELECT * FROM e_tchat WHERE type = '".$type."' AND type_ID = '".$type_ID."' ; ";
  $response = $GLOBALS['db']->query($sql);
  
    if(mysql_num_rows($response)> 0){
      $data = mysql_fetch_array($response);
      $object = unserialize($data['object']);
      $this->message = $object->message;
      $this->type = $object->type;
      $this->type_ID = $object->type_ID;
      $this->id = $data['id'];
    }else{
      $this->type = $type;
      $this->type_ID = $type_ID;
      $data = serialize($this);
      $sql = "INSERT INTO e_tchat(id,type,type_ID,object) VALUES('','".$this->type."','".$this->type_ID."','".$data."')";
      $GLOBALS['db']->query($sql);
    }
  }
  
  function save(){
    $data = serialize($this);
    $sql = "UPDATE e_tchat SET
      object = '".$data."'
      
      WHERE `type` = '".$this->type."' AND `type_ID` = '".$this->type_ID."' ;";
    
    $GLOBALS['db']->query($sql);
  }
  
  
  function ajouterMessage($message,$login,$id){
    $nb = count($this->message);
    $this->message[$nb]['m'] = $message;
    $this->message[$nb]['l'] = $login;
    $this->message[$nb]['i'] = $id;
    $this->message[$nb]['t'] = time();
  }
  
  function afficherMessage(){
    $html = "";
    //echo '<pre>'.print_r(arsort($this->message)).'</pre>';
    $table = $this->message;
    
    if(is_array($table) AND count($this->message) > 0){
      $table = array_reverse($table, true);
      foreach($table as $key => $value){
        $html.= "<br /><strong>".$value['l'].":</strong> ".htmlspecialchars(html_entity_decode($value['m']), ENT_NOQUOTES)."";
      }
    }
    return $html;
  }

}?>