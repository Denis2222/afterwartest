<?php
include ("./templates/header.php");
?>
	<body <?php if(isset($_SESSION['jid'])){ echo 'onLoad="Chargement();"'; }?> class="low medium huge">
<?php 

include("./include.php");
$GLOBALS['home'] = 1;


?>
	<div id="global">
		<div id="menu_haut"><?php echo menuHaut() ?></div>
		<div id="loading" valign="center"></div>
		<div id="menu_gauche"></div>
		<div id="contenu">
    
    <?php
      include("./data.login.php");
    ?>
    </div>
		
<?php
include("./templates/footer.php");
?>