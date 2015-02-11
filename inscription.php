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
              <br /><div align="center"><img src="./skin/original/design/bienvenueterre.png" /></div>
          </div>
          <div class="contenu_fond_centre"  align="left">
            <br />

           
      <div align="center"> Tiens, voilà de la bleusaille qui se sent d'attaque...<br /> Si tu es sûr que tu ne t'es pas trompé de porte, remplis ce formulaire.<br /><br />
      </div>


              	    
      <div align="center"><table>
	     <tr><td width="100%" colspan="2" align="center"><h2>Bulletin&nbsp;-&nbsp;Inscription<h2></td></tr>
	     <tr><td colspan="2">&nbsp;</td></tr>
       <tr><td colspan="2">Ce sera ta seule identité dans ce monde !</td></tr>
	     <tr>
          <td> Nom de code&nbsp;: </td>
          <td> <input id="lns_login" class="inputText" name="lns_login" maxlength="50" autocomplete="off" type="text" value=""></td>
       </tr>
 
       <tr><td colspan="2">&nbsp;</td></tr>
       <tr><td colspan="2">Et la preuve de ton identité.</td></tr>
       <tr>  
          <td> Passe &nbsp;: </td>
          <td> <input id="ins_pass" class="inputText" name="ins_pass" maxlength="50" autocomplete="off" type="password" value=""></td>
       </tr>

       <tr><td colspan="2">&nbsp;</td></tr>    
       <tr><td colspan="2">Afin de vérifier que tu possèdes un chez toi,<br /> et que tu n'est pas une machine...</td></tr>   
       <tr>  
          <td> Mail @&nbsp;: </td>
          <td> <input id="ins_mail" class="inputText" name="ins_mail" maxlength="50" autocomplete="off" type="text" value=""></td>
       </tr>
       <tr><td colspan="2">&nbsp;</td></tr>
       <tr>
          <td colspan="2" align="center">  &nbsp;<button class="search" type="submit" title="Search" onClick="sendFormInscription()"></button></td>   
       </tr>
       <tr>
          <td colspan="2"> </td>
       </tr>
      </table>

      <div id="return"> </div>
      </div>
	    </div>
      </div>

      </div>
<?php
include("./templates/footer.php");
?>