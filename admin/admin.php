<?php
session_start();

function TRformTextarea($title,$name,$value,$row){

echo '
<TR>
	<TD> '.$title.'</TD>
	<TD>
	<TEXTAREA rows="'.$row.'" cols="70" name="'.$name.'">'.$value.'</TEXTAREA>
	</TD>
</TR>';
}

require("../include.php");



if(!isset($_SESSION['admin_logue'])){
  if(isset($_POST['admin']) AND isset($_POST['pass'])){
      echo 'Test';
  }
}
if($_SESSION['admin_logue'] == "login" ||true){

echo '<a href="">Accueil Admin</a> | <a href="?action=general">Options Générales</a> | <a href="?action=categorie">Catégories</a> 

<hr>';


if($_GET['action'] == "general"){
  if(isset($_POST['site_name'])){
    $settings->site_name = $_POST['site_name'];
    $settings->admin = $_POST['admin'];
    $settings->pass = $_POST['pass'];
    $settings->home_description1 = $_POST['home_description1'];
    $settings->home_description2 = $_POST['home_description2'];
    $settings->home_description3 = $_POST['home_description3'];
    $settings->home_description4 = $_POST['home_description4'];
    $settings->save();
  }
  
  ?>
  <FORM method=post action="?action=general">
<h2>Options Générales</h2>
<TABLE BORDER=0>
<TR>
	<TD>Nom du site</TD>
	<TD>
	<INPUT type=text name="site_name" value="<?php echo $settings->site_name;?>">
	</TD>
</TR>

<TR>
	<TD>Admin</TD>
	<TD>
	<INPUT type=text name="admin" value="<?php echo $settings->admin;?>">
	</TD>
</TR>

<TR>
	<TD>Pass</TD>
	<TD>
	<INPUT type=text name="pass" value="<?php echo $settings->pass;?>">
	</TD>
</TR>

<?php
TRformTextarea("Description Accueil 1","home_description1",$settings->home_description1,15);

TRformTextarea("Description Accueil 2","home_description2",$settings->home_description2,15);
TRformTextarea("Description Accueil 3","home_description3",$settings->home_description3,15);
TRformTextarea("Description Accueil 4","home_description4",$settings->home_description4,15);
?>



<TR>
	<TD COLSPAN=2>
	<INPUT type="submit" value="Envoyer">
	</TD>
</TR>


</TABLE>
</FORM>
  
  <?php

}
}else{

?>

<FORM method=post action="">
Login
<TABLE BORDER=0>
<TR>
	<TD>Nom</TD>
	<TD>
	<INPUT type=text name="admin">
	</TD>
</TR>

<TR>
	<TD>Pass</TD>
	<TD>
	<INPUT type=text name="pass">
	</TD>
</TR>
<TR>
	<TD COLSPAN=2>
	<INPUT type="submit" value="Envoyer">
	</TD>
</TR>


</TABLE>
</FORM>
<?php
}
?>