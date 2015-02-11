<div id="aide">
<h1>test du autre page</h1>
Voici le modul d'aide ...<br><br>
<a href="#" onclick="changeHighslide('include/test.php');">
	Suite</a><br><br>
	<a href="#" onClick="setTimeout(document.getElementById('test').value='bordel',1000);ajaxLoad('contenu','data.php?div=contenu&choix=messagerie&m=6');initChek();hs.close(this);">Ecrire un message au staff</a>
</div>


<?php
//include_once("../include.php");
  echo 'test :)';
  echo '<br />test 2 <br />';
  echo 'une image : <img src="./skin/original/icones/vledac.png" alt="Or"/><br />';
  echo 'un lien :<a href="#" onClick="ajaxLoad(\'contenu\',\'data.php?div=contenu&choix=messagerie&m=6\');">Ecrire un message</a>';
?>