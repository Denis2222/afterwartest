<?php
function formulaire($post=FALSE){

  if($post==FALSE){ // Si on affiche la page sans avoir envoyé le formulaire ( l'index ) On initialise
    $post['RANDOMNESS']=DEF_RANDOMNESS;
    $post['SPARSENESS']=DEF_SPARSENESS;
	$post['DEADENDS']=DEF_DEADENDS;
    $post['WIDTH']=DEF_WIDTH;
    $post['HEIGHT']=DEF_HEIGHT;
    $post['ROOM_MIN_WIDTH']=DEF_ROOM_MIN_WIDTH;
    $post['ROOM_MAX_WIDTH']=DEF_ROOM_MAX_WIDTH;
    $post['ROOM_MIN_HEIGHT']=DEF_ROOM_MIN_HEIGHT;
    $post['ROOM_MAX_HEIGHT']=DEF_ROOM_MAX_HEIGHT;
    $post['ROOMCOUNT']=DEF_ROOMCOUNT;
	
	$_SESSION['param']['RANDOMNESS']= $post['RANDOMNESS'];
    $_SESSION['param']['SPARSENESS']=$post['SPARSENESS'];
	$_SESSION['param']['DEADENDS']=$post['DEADENDS'];
    $_SESSION['param']['WIDTH']=$post['WIDTH'];
    $_SESSION['param']['HEIGHT']= $post['HEIGHT'];
    $_SESSION['param']['ROOM_MIN_WIDTH']= $post['ROOM_MIN_WIDTH'];
    $_SESSION['param']['ROOM_MAX_WIDTH']= $post['ROOM_MAX_WIDTH'];
    $_SESSION['param']['ROOM_MIN_HEIGHT']=$post['ROOM_MIN_HEIGHT'];
    $_SESSION['param']['ROOM_MAX_HEIGHT']= $post['ROOM_MAX_HEIGHT'];
    $_SESSION['param']['ROOMCOUNT']= $post['ROOMCOUNT'];
	
	
    $post['INITX']=-3;
    $post['INITY']=3;
	$post['DOOR']=10;
	$post['DIR1DOOR']=1;
  }elseif(isset($_SESSION['param']['RANDOMNESS'])){

	$_SESSION['param']['RANDOMNESS']= $post['RANDOMNESS'];
    $_SESSION['param']['SPARSENESS']=$post['SPARSENESS'];
	$_SESSION['param']['DEADENDS']=$post['DEADENDS'];
    $_SESSION['param']['WIDTH']=$post['WIDTH'];
    $_SESSION['param']['HEIGHT']= $post['HEIGHT'];
    $_SESSION['param']['ROOM_MIN_WIDTH']= $post['ROOM_MIN_WIDTH'];
    $_SESSION['param']['ROOM_MAX_WIDTH']= $post['ROOM_MAX_WIDTH'];
    $_SESSION['param']['ROOM_MIN_HEIGHT']=$post['ROOM_MIN_HEIGHT'];
    $_SESSION['param']['ROOM_MAX_HEIGHT']= $post['ROOM_MAX_HEIGHT'];
    $_SESSION['param']['ROOMCOUNT']= $post['ROOMCOUNT'];
	
	
	$post['RANDOMNESS']=$_SESSION['param']['RANDOMNESS'];
    $post['SPARSENESS']=$_SESSION['param']['SPARSENESS'];
	$post['DEADENDS']=$_SESSION['param']['DEADENDS'];
    $post['WIDTH']=$_SESSION['param']['WIDTH'];
    $post['HEIGHT']=$_SESSION['param']['HEIGHT'];
    $post['ROOM_MIN_WIDTH']=$_SESSION['param']['ROOM_MIN_WIDTH'];
    $post['ROOM_MAX_WIDTH']=$_SESSION['param']['ROOM_MAX_WIDTH'];
    $post['ROOM_MIN_HEIGHT']=$_SESSION['param']['ROOM_MIN_HEIGHT'];
    $post['ROOM_MAX_HEIGHT']=$_SESSION['param']['ROOM_MAX_HEIGHT'];
    $post['ROOMCOUNT']=$_SESSION['param']['ROOMCOUNT'];
	
  }
    $post['INITX']=-3;
    $post['INITY']=3;
	$post['DOOR']=10;
	$post['DIR1DOOR']=1;

	/*
	
	*/
  echo '<form name="dungeon" method="post" action="'.$_SERVER['PHP_SELF'].'">
<table>
<tr><td> Le coté aléatoire : [1->100]'.tips("Plus la valeur est faible plus les couloirs seront droits. ").'</td><td> <input type="text" name="RANDOMNESS" value="'.$post['RANDOMNESS'].'"> </td></tr>
<tr><td>Densité: [0->50] '.tips("Densité du donjon . 0 Aucune case du donjon n'est pas un couloir. 30 et plus , Trés peu de couloir. ").'</td><td><input type="text" name="SPARSENESS" value="'.$post['SPARSENESS'].'"></td></tr>
<tr><td>Cul de sac : [0->100] '.tips("Pourcentage de cul de sac laissé dans le donjon.").'</td><td><input type="text" name="DEADENDS" value="'.$post['DEADENDS'].'"></td></tr>
<tr><td>Largeur du donjon : '.tips("Indiquez la Largeur du donjon en nombre de case.").'</td><td><input type="text" name="WIDTH" value="'.$post['WIDTH'].'"></td></tr>
<tr><td>Hauteur du donjon : '.tips("Indiquez la Hauteur du donjon en nombre de case.").'</td><td><input type="text" name="HEIGHT" value="'.$post['HEIGHT'].'"></td></tr>
<tr><td>Nombre de salle : '.tips("Indiquez le nombre de salle du donjon. Attention Trop de salle pour une taille trop faible provoque des incohérences.").'</td><td> <input type="text" name="ROOMCOUNT" value="'.$post['ROOMCOUNT'].'"></td></tr>
<tr><td>Largeur minimum d\'une salle : </td><td><input type="text" name="ROOM_MIN_WIDTH" value="'.$post['ROOM_MIN_WIDTH'].'"></td></tr>
<tr><td>Largeur maximum d\'une salle : </td><td><input type="text" name="ROOM_MAX_WIDTH" value="'.$post['ROOM_MAX_WIDTH'].'"></td></tr>
<tr><td>Hauteur minimum d\'une salle : </td><td><input type="text" name="ROOM_MIN_HEIGHT" value="'.$post['ROOM_MIN_HEIGHT'].'"></td></tr>
<tr><td>Hauteur maximum d\'une salle : </td><td><input type="text" name="ROOM_MAX_HEIGHT" value="'.$post['ROOM_MAX_HEIGHT'].'"></td></tr>
  ';/*
  <hr>
  INIT X : <input type="text" name="INITX" value="'.$post['INITX'].'">Coordonnée X du départ du générateur( Valeur négative pour un point de départ aléatoire )<br />
  INIT Y : <input type="text" name="INITY" value="'.$post['INITY'].'">Coordonnée Y<br />
  <br /><br />
  Porte : <input type="text" name="DOOR" value="'.$post['DOOR'].'">%<br />
  Une porte par direction : <input type="text" name="DIR1DOOR" value="'.$post['DIR1DOOR'].'">(0 sans | 1 avec)<br /> 
  */
  echo '</table><br /><input type="submit" value="Generer"></form>';
  
  return $post;
}

function tips($texte){
	return '<a href="#" title="<div class=\'infobulle\'>'.$texte.'</div>" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)"><img src="./img/help.png"/></a>';
}

function trips($texte){
	if($texte == 0){
		$description = "Creuser mur";
		return '<a href="#" title="<div class=\'infobulle\'>'.$description.'</div>" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)">'.$texte.'</a>';
	}
	if($texte == 1){
		$description = "Remplir mur";
		return '<a href="#" title="<div class=\'infobulle\'>'.$description.'</div>" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)">'.$texte.'</a>';
	}
}

function tooltip(){
	return 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)"';
}

function formulaire_editeur($post=FALSE){
 if($post==FALSE){ // Si on affiche la page sans avoir envoyé le formulaire ( l'index ) On initialise
    $post['dungeon']=".djn";
  }
 
echo '<form name="dungeon" method="post" action="'.$_SERVER['PHP_SELF'].'">
  donjon à charger :

  <input type="submit" value="Envoyer">
  </form>';

  return $post;
}



function listeTimeLimit($nb){

$html = '<select name="timelimit"> ';
for($x=1;$x<=$nb;$x++){
	$sec = 86400*$x;
	$html .= '<option value="'.$sec.'">'.$x.' Jours</option>';
}
$html .= '</select> ';
return $html;
}
?>