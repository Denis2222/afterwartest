<?php
include ("./templates/header.php");
?>
  <body>
<?php 
include("./include.php");
include("./include/histoire.php");
$GLOBALS['home'] = 1;
  ?>	
  <div id="global">		
    <div id="menu_haut">
      <?php echo menuHaut() ?>
    </div>		
    <div id="loading" valign="center">
    </div>		
    <div id="menu_gauche">
    </div>		
    <div id="contenu">
      <div class="contenu">
        <div class="contenu_header_fond">
          <img class="histoire" alt="histoire" src="./skin/original/design/histoire.png"/>
        </div>
        <div class="contenu_fond_centre" align="left">
          <br />
          <div class="histoire2">
              <?php
                if(!isset($_GET['p'])){
                  $_GET['p'] = 1;
                }              
                echo $GLOBALS['step'][$_GET['p']];
               
               echo '<div class="liens_histoire">';
               if($_GET['p'] < count($step)){
                  echo '<a href="./histoire.php?p='.($_GET['p']+1).'"><img id="menu_haut_onglet" src="./skin/original/design/suivant.png" class="lien_suivant" alt="Suivant"/></a>';
              }
               if($_GET['p'] > 1){
               echo '<a href="./histoire.php?p='.($_GET['p']-1).'"><img id="menu_haut_onglet" src="./skin/original/design/precedent.png" alt="Précédent" class="lien_precedent" /></a>';
               }
               echo '</div><br /><br />';
               
              ?>
          </div>
        </div>
      </div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7723891-1");
pageTracker._trackPageview();
} catch(err) {}</script>      
<?php
include("./templates/footer.php");
?>