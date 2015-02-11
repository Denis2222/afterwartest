<?php
Class MJ{
	var $id;
	var $login;
	var $mdp;
	var $level;
	var $partie;
	var $action;


	function load($id){
		$sql = $GLOBALS["db"]->query('SELECT * FROM e_mj WHERE id = '.$id);
		$donnees = mysql_fetch_array($sql);
		$this->id = $donnees['id'];
		$this->login = $donnees['login'];
		$this->mdp = $donnees['mdp'];
		$this->level = $donnees['level'];
		$this->partie = $donnees['partie'];
		$this->action = $donnees['action'];
	}
	
	function save(){
    $sql = "UPDATE e_mj SET
    login = '".$this->login."',
    mdp = '".$this->mdp."',
    level = '".$this->level."',
    partie = '".$this->partie."',
    action = '".$this->action."'

    WHERE `id` = '".$this->id."' ;";
    
    $GLOBALS['db']->query($sql);
  }
  
}
?>