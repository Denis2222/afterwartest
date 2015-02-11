
<?php
function heure($time){
$retour = getdate($time);
$h = $retour["hours"];
$m = $retour["minutes"];
$s = $retour["seconds"];
$z = "0";
$h2 = ($h < 10)?$z.$h:$h;
$m2 = ($m < 10)?$z.$m:$m;
$texte = $h2.":".$m2;
return $texte;
} 

function date_ga($time){
$retour = getdate($time);
$j = $retour["mday"];
$m = $retour["mon"];
$a = $retour["year"];
$z = "0";
$j2 = ($j < 10)?$z.$j:$j;
$m2 = ($m < 10)?$z.$m:$m;
$texte = $j2.".".$m2.".".$a;
return $texte; 
} 

function nb_topic($id_cat){
$req = mysql_query("SELECT COUNT(*) as cpt FROM f_topic WHERE id_categorie='".$id_cat."'");
$row = mysql_fetch_array($req);
$nb = $row['cpt']; 
return $nb;
}

function nb_reponse_by_cat($id_cat){ // NB de reponsse par categorie
$req = mysql_query("
SELECT COUNT(f_reponse.ID) as cpt 
FROM f_reponse,f_topic,f_categorie 
WHERE 
f_reponse.ID_topic=f_topic.ID AND
f_topic.ID_categorie=f_categorie.ID AND
f_categorie.ID='".$id_cat."'
");

$row = mysql_fetch_array($req);
$nb = $row['cpt']; 
return $nb;
}
function nb_reponse_by_topic($id_topic){ // NB de reponsse par categorie
$req = mysql_query("
SELECT COUNT(*) as cpt 
FROM f_reponse
WHERE 
ID_topic='".$id_topic."'
");
$row = mysql_fetch_array($req);
$nb = $row['cpt']; 
return $nb;
}

function dernier_msg($id_topic){
$req = mysql_query("SELECT  ID_membre as ID_membre, date as date FROM f_reponse WHERE ID_topic='".$id_topic."' ORDER BY id DESC limit 1 ");
$row = mysql_fetch_array($req); 
return $row;
}

function dernier_sujet($id_cat){
$req = mysql_query("SELECT  nom as nom, id as id FROM f_topic WHERE ID_categorie='".$id_cat."' ORDER BY id DESC limit 1 ");
$row = mysql_fetch_array($req); 
return $row;
}

function formulaire_nouveau_topic($style_dir){

echo '
<div class="formulaire_forum">
<FORM name="formulaire_forum" method="post" action="forum.php?action=post&f='.$_GET['f'].'&cat='.$_GET['cat'].'">
Créer un nouveau sujet.
<TABLE BORDER=0>
<TR>
	<TD>Titre :</TD><TD><INPUT type=text name="titre"/></TD>
  </TD>
	<td ROWSPAN=2>
<input type="button" value="Image Perso" onClick="insertion(\'<img src=\u0022 URL DE L\u0027IMAGE \u0022/>\', \'\');"/><br/>
	  <a href="#1" onClick="insertion(\' :-) \', \'\');"><img src="'.$style_dir.'/forum/icones/visage.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-d \', \'\');"><img src="'.$style_dir.'/forum/icones/rire.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' ;-) \', \'\');"><img src="'.$style_dir.'/forum/icones/clin.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-$ \', \'\');"><img src="'.$style_dir.'/forum/icones/emotion.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-l \', \'\');"><img src="'.$style_dir.'/forum/icones/lent.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' 8-) \', \'\');"><img src="'.$style_dir.'/forum/icones/lunette.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-n \', \'\');"><img src="'.$style_dir.'/forum/icones/nah.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-( \', \'\');"><img src="'.$style_dir.'/forum/icones/bad.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-pc \', \'\');"><img src="'.$style_dir.'/forum/icones/pc.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-b \', \'\');"><img src="'.$style_dir.'/forum/icones/banane.gif" border="none" value=":-)"/></a>
  <br/>
 <input type="button" value="Bold" onClick="insertion(\'<b>\', \'</b>\');">
  <input type="button" value="<h2>" onClick="insertion(\'<h2>\', \'</h2>\');">
  <input type="button" value="Italique" onClick="insertion(\'<i>\', \'</i>\');">
  <input type="button" value="Souligné" onClick="insertion(\'<u>\', \'</u>\');">
  <input type="button" value="Barré" onClick="insertion(\'<s>\', \'</s>\');">
  </td>
  </TR>

<TR>
	<TD>Description :</TD>
	<TD>
	<INPUT type=text name="description">
	
</TR>



<TR>
	<TD>Message :</TD>
	<TD colspan="2">
	<TEXTAREA rows="8" cols="50" name="message"  >

</TEXTAREA>
	</TD>
</TR>

<TR>
	<TD COLSPAN=2>
	<INPUT type="submit" value="Créer">
	</TD>
</TR>
</TABLE>
</FORM>
</div>
';
}
function formulaire_reponse($style_dir){
echo '
<div class="formulaire_forum">
<FORM name="formulaire_forum" method="post" action="forum.php?action=repond&t='.$_GET['t'].'&f='.$_GET['f'].'&cat='.$_GET['cat'].'">
Repondre au sujet.
<TABLE BORDER=0>

<TR>

	<td ROWSPAN=2>
<input type="button" value="Image Perso" onClick="insertion(\'<img src=\u0022 URL DE L\u0027IMAGE \u0022/>\', \'\');"/><br/>
	  <a href="#1" onClick="insertion(\' :-) \', \'\');"><img src="'.$style_dir.'/forum/icones/visage.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-d \', \'\');"><img src="'.$style_dir.'/forum/icones/rire.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' ;-) \', \'\');"><img src="'.$style_dir.'/forum/icones/clin.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-$ \', \'\');"><img src="'.$style_dir.'/forum/icones/emotion.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-l \', \'\');"><img src="'.$style_dir.'/forum/icones/lent.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' 8-) \', \'\');"><img src="'.$style_dir.'/forum/icones/lunette.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-n \', \'\');"><img src="'.$style_dir.'/forum/icones/nah.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-( \', \'\');"><img src="'.$style_dir.'/forum/icones/bad.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-pc \', \'\');"><img src="'.$style_dir.'/forum/icones/pc.jpg" border="none" value=":-)"/></a>
  <a href="#1" onClick="insertion(\' :-b \', \'\');"><img src="'.$style_dir.'/forum/icones/banane.gif" border="none" value=":-)"/></a>
  <br/>
 <input type="button" value="Bold" onClick="insertion(\'<b>\', \'</b>\');">
  <input type="button" value="<h2>" onClick="insertion(\'<h2>\', \'</h2>\');">
  <input type="button" value="Italique" onClick="insertion(\'<i>\', \'</i>\');">
  <input type="button" value="Souligné" onClick="insertion(\'<u>\', \'</u>\');">
  <input type="button" value="Barré" onClick="insertion(\'<s>\', \'</s>\');">
  </td>
  </TR>
  <tr>
	<TD>
	<TEXTAREA rows="8" cols="50" name="message" >

</TEXTAREA>
	</TD>
</TR>

<TR>
	<TD COLSPAN=2>
	<INPUT type="submit" value="Répondre">
	</TD>
</TR>
</TABLE>
</FORM>
</div>
';
}


?>
