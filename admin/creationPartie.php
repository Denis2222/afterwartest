<?php


require_once("../include.php");



if(!empty($_POST['nom']) && !empty($_POST['vitesse']) && !empty($_POST['tailleX']) && !empty($_POST['tailleY']) && !empty($_POST['mines']))
{
  if(is_numeric($_POST['vitesse']) && is_numeric($_POST['tailleX']) && is_numeric($_POST['tailleY']) && is_numeric($_POST['mines']))
  {
// REGLAGE -------------------------------------------
//Décommenter las réglages souhaités ...


    //---------------------------------------
    //Réglace d'une partie classique
    $date_debut=mktime($_POST['hour'], $_POST['minute'],0, $_POST['month'], $_POST['day'], $_POST['year']);
    $vitesse=$_POST['vitesse'];
    $tailleX=$_POST['tailleX'];
    $tailleY=$_POST['tailleY'];
    $type=$_POST['type']; // rush  => course aux ressources
	                // mort => match a mort
	                // mortequipe => match a mort par equipe 
    $nom=$_POST['nom'];
    $population_mine=$_POST['mines'];//pourcentage
    //------------------------------------------

/*    
    //---------------------------------------
    //Réglace d'une partie debutant
    $date_debut=time();
    $vitesse=1;
    $tailleX=5;
    $tailleY=5;
    $type='debut';
    $nom='Initiation';
    $population_mine=0;//pourcentage
    //------------------------------------------
*/
// FIN REGLAGE ---------------------------------------

//------------------------------------------
//Création de la partie et de la carte
    
    $p=new Partie();
	$nPartie =  $p->newPartie($date_debut,$vitesse,$tailleX,$tailleY,$type,$nom);
    $p->load($nPartie);
    $p->newMap($population_mine);
    if($type == 'prise' )
    {
        $p->initPartiePrise();
    }
//------------------------------------------


//-----------------------------------------------
//Ajout de mines
  if($population_mine != 0)//si on veut des mines sur la map
    for($i=0;$i<$tailleX;$i++){
      for($j=0;$j<$tailleY;$j++){
        if(rand(0,100) < $population_mine){
          $c= new CaseObject($i,$j,prefixMapPartie($type).$nPartie);
          $c->ajouterMine();
          $c->save();
        }
      }
    }
//-------------------------------------------------
  }
  else
  {
  echo 'erreur de saisie';
  }
echo 'Création réussie !';
}
else
{

?>
<form method="post" action="creationPartie.php">
   <p>Nom : <input type="text" name="nom" /></p>
   <p>Date de debut
   <select name="year">
					<option value="2009" selected="selected">2009</option>
					<option value="2010">2010</option>
					<option value="2011">2011</option>
					<option value="2012">2012</option>
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
				</select> / 
				<select name="month">
					<option value="01">Jan</option>
					<option value="02">Feb</option>
					<option value="03">Mar</option>
					<option value="04">Apr</option>

					<option value="05">May</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Aug</option>
					<option value="09">Sep</option>
					<option value="10">Oct</option>

					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select> / 
				<select name="day">
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>

					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>

					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>

					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>

					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
					<option value="25">25</option>
					<option value="26">26</option>
					<option value="27">27</option>

					<option value="28">28</option>
					<option value="29">29</option>
					<option value="30">30</option>
					<option value="31">31</option>
				</select>  &nbsp;
				<select name="hour">

					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>

					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>

					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>

					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>

				</select> :
				<select name="minute">
					<option value="00">00</option>
					<option value="05">05</option>
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>

					<option value="25">25</option>
					<option value="30">30</option>
					<option value="35">35</option>
					<option value="40">40</option>
					<option value="45">45</option>
					<option value="50">50</option>

					<option value="55">55</option>
				</select>			
   </p>
   <p>Vitesse : <input type="text" name="vitesse" /></p>
   <p>Taille X : <input type="text" name="tailleX" /></p>
   <p>Taille Y : <input type="text" name="tailleY" /></p>
   <p>Mines : <input type="text" name="mines" /></p>
   <p>
       Veuillez indiquer letype de partie :<br />
       <input type="radio" name="type" value="rush" id="rush" checked="checked" /> <label for="rush">Rush</label><br />
       <input type="radio" name="type" value="mort" id="mort" /> <label for="mort">Match a mort</label><br />
       <input type="radio" name="type" value="mortequipe" id="mortequipe" /> <label for="mortequipe">Match a mort par equipe</label><br />
       <input type="radio" name="type" value="prise" id="prise" /> <label for="prise">Prise de la ville du milieu</label><br />
   </p>
   <p>
       <input type="submit" /> <input type="reset" />
   </p>

</form>
<?php
}
?>
