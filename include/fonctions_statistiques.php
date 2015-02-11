<?php

//-----------------------------------------
//------------STAT GLOBALS-----------------
//-----------------------------------------

function statBdd($table)
{
  $tous=array();
  $sql = $GLOBALS["db"]->query('SELECT * FROM '.$table);
  while($donnees = mysql_fetch_array($sql))
		{
		$i=0; 
    $un=array();
    if(is_array($donnees))
		foreach( $donnees as $key =>$val)
      {
      if($i % 2 ==1)
		    $un[$key]=$val;
      $i++;
      }
		$tous[$donnees['id']]=$un;		
		}
    return $tous;
}

function triCarac($stat,$carac,$carac2 = ''){
  if(is_array($stat))
  foreach($stat as $un){
    $valeur=0;
    if(is_array($un))
    foreach($un as $key =>$val){     
      if($key=='id' )
        $clef=$val;
      if($key==$carac || $key==$carac2)
        $valeur+=$val;     
      }
      $data[$clef]=$valeur;
    }
  arsort($data);
  if(is_array($data))
  foreach($data as $key => $val)
    $sort[]=$key;
  return $sort;
}

function triAllianceCarac($alliance,$joueur,$stat,$carac,$carac2 = '')
{ 
  if(is_array($alliance) && count($alliance) > 0)
  foreach($alliance as $uneAlliance){
    if(is_array($stat) && count($stat) > 0)	
    foreach($stat as $un){
      if($joueur[$un['id']]['alliance'] == $uneAlliance['id'])
        {
        if(is_array($un))
        foreach($un as $key =>$val){ 
          if($key==$carac)
            $data[$uneAlliance['id']]+=$un[$carac]+$un[$carac2];
        }
      }      
    }    
  }
  if(is_array($data))
  arsort($data);
  return $data;
}

function statAlliance($tri,$page){
  $joueur = statBdd('j_compte');
  $stat = statBdd('j_stat');
  $alliance = statBdd('j_alliance');

  $tabTriHab=triAllianceCarac($alliance,$joueur,$stat,'hab','totalHab');
  $tabTriDef=triAllianceCarac($alliance,$joueur,$stat,'def','totalDef');
  $tabTriOff=triAllianceCarac($alliance,$joueur,$stat,'off','totalOff');
  $tabTriCombat=triAllianceCarac($alliance,$joueur,$stat,'combat','totaltotamCombat');

  $sort=array();
  if($tri == 'hab'){
    if(is_array($tabTriHab))
    foreach($tabTriHab as $key => $val)
      $sort[]=$key; 
  }
  if($tri == 'def'){
    if(is_array($tabTriDef))	
    foreach($tabTriDef as $key => $val)
      $sort[]=$key; 
  }
  if($tri == 'off'){
    if(is_array($tabTriOff))	
    foreach($tabTriOff as $key => $val)
      $sort[]=$key;   
   }   
  if($tri == 'combat'){
    if(is_array($tabTriCombat))	
    foreach($tabTriCombat as $key => $val)
      $sort[]=$key;   
   }      
   
      echo '<br /><table cellspacing="1" cellpadding="2" class="statistiques">  
          <tr class="tr_message">    
              <td>Rang</td>
              <td>Nom</td>    
              <td>'.ahref('Population','./include/statistiques.php?type=a&tri=hab',"contenu").'</td>
              <td>'.ahref('Attaque','./include/statistiques.php?type=a&tri=off',"contenu").'</td>
              <td>'.ahref('Défense','./include/statistiques.php?type=a&tri=def',"contenu").'</td>
              <td>'.ahref('Combat','./include/statistiques.php?type=a&tri=combat',"contenu").'</td>
          </tr> '; 	
      if(is_array($sort))	
      foreach($sort as $key =>$val)
      {
        echo '<tr>
              <td>'.($key+1).'</td>    
              <td>'.$alliance[$val]['nom'].'</td>
              <td>'.$tabTriHab[$val].'</td>
              <td>'.$tabTriOff[$val].'</td>
              <td>'.$tabTriDef[$val].'</td>
              <td>'.$tabTriCombat[$val].'</td>
              </tr>';		  
  		}
          echo ' <tr class="tr_message">
                    <td>&nbsp;</td>         
                    <td class="s7"colspan="4">';
          echo '<input type="text" class="inputText" id="nomRecherche"/>&nbsp;<button class="search" type="submit" title="Supprimer" onClick="window.alert(\'recherche de \'+document.getElementById(\'nomRecherche\').value)"></button>';
          echo '&nbsp;&nbsp;<span id="nbSelect"></span></td>         
                <td align="right"><span class="c"><b>&laquo;</b></span><a href="index.php?m=1&n=10">&raquo;</a>&nbsp;</td>     
                </tr>';
          echo '</table>';
  
}

function infoJoueur($id)
{
  $j = new Joueur();
  $j->loadSimple($id);

  /*$sql = $GLOBALS["db"]->query('SELECT * FROM j_compte WHERE id ='.$id);
  $compte = mysql_fetch_array($sql);*/
/*  
  $sql = $GLOBALS["db"]->query('SELECT * FROM j_stat WHERE id ='.$id);
  $stat = mysql_fetch_array($sql);
*/
  $sql = $GLOBALS["db"]->query('SELECT * FROM j_alliance WHERE id ='.$j->alliance);
  $alliance = mysql_fetch_array($sql);


  $stat = new Stat();
  $stat->load($id);
  
  
  echo decoHaut($compte['login']).'<br />';
  
  echo '<table class="message" cellspacing="1" cellpadding="2" style="width:200px">
        <tbody>
        <tr class="tr_gris">
        <td> Alliance </td>
        <td> '.$alliance['nom'].' </td>
        </tr>
        <tr class="tr_noir">
        <td> Habitant </td>
        <td> '.$stat->hab.' </td>
        </tr>
        <tr class="tr_gris">
        <td> Offenssif </td>
        <td> '.$stat->off.' </td>
        </tr>
        <tr class="tr_noir">
        <td> Defenssif </td>
        <td> '.$stat->def.' </td>
        </tr>
        </table>
        
        <table>
        <tr >
        <td> Ville : </td>
        <td>  </td>
        </tr>';

        foreach($j->idVille as $key => $value){
            if($key%2 == 0){echo '<tr class="tr_gris">';} else { echo '<tr class="tr_noir">';}
            echo '<td> '.$value['nom'].' </td>';
            

              $v = new Ville();
                
              $v->loadSimple($value['id']);
              echo '<td>'.ahref('Voir','data.php?div=contenu&cc=1&x='.$v->X.'&y='.$v->Y.'','contenu').'</td>';
                   

            
            echo '</tr>';
        }
        
        
        echo '</tbody>
        </table>';
  /*
  echo '<table><tr><td colspan="2">Liste des parties jouées</td></tr>
        <tbody>
        <tr class="tr_gris">
        <td> Alliance </td>
        <td> '.$alliance['nom'].' </td>
        </tr>
        <tr class="tr_noir">
        <td> Habitant </td>
        <td> '.$stat->hab.' </td>
        </tr>
        <tr class="tr_gris">
        <td> Offenssif </td>
        <td> '.$stat->off.' </td>
        </tr>
        <tr class="tr_noir">
        <td> Defenssif </td>
        <td> '.$stat->def.' </td>
        </tr>
        </tbody>
        </table>';
  foreach($stat->parties as $parties)
  {
    echo ($parties);
  }
  */
  //print_r($stat);
  echo $GLOBALS['decoBas'];
}


function statJoueur($sort,$page,$cherche = '')
{

  $joueur = statBdd('j_compte');
  $stat = statBdd('j_stat');
  $alliance = statBdd('j_alliance');
  
  if($sort == 'hab')
      $tabTri=triCarac($stat,'hab','totalHab');
  if($sort == 'def')
      $tabTri=triCarac($stat,'def','totalDef');
  if($sort == 'off')
      $tabTri=triCarac($stat,'off','totalOff');
  if($sort == 'combat')
      $tabTri=triCarac($stat,'combat','totalCombat');
  $messParPage=18;

  $i=1;
  $suite=0;
  if($cherche != ''){
    $trouver = false;
    if(is_array($tabTri))
    foreach($tabTri as $key =>$val){
      if($joueur[$val]['login'] == $cherche){
        $page = (int)($i / $messParPage);
        $trouver = true;
      }
      $i++;
    }
  }
  echo '<br /><table cellspacing="1" cellpadding="2" class="statistiques">  
      <tr class="tr_message">    
          <td>Rang</td>
          <td>Login</td>
          <td>Alliance</td>    
          <td>'.ahref('Population','./include/statistiques.php?type=j&tri=hab',"contenu").'</td>
          <td>'.ahref('Attaque','./include/statistiques.php?type=j&tri=off',"contenu").'</td>
          <td>'.ahref('Défense','./include/statistiques.php?type=j&tri=def',"contenu").'</td>
          <td>'.ahref('Combat','./include/statistiques.php?type=j&tri=combat',"contenu").'</td>
      </tr> '; 	

  $premierMess=($page*$messParPage)+1;
  $dernierMess=$page*$messParPage+$messParPage;
  $i=1;
  $suite=0;      	
  if(is_array($tabTri))
  foreach($tabTri as $key =>$val)
  {
    if($i >= $premierMess && $i <= $dernierMess){
    	if($joueur[$val]['login'] == $cherche)
          echo '<tr class="plus" id="'.$joueur[$val]['login'].'">';
    	else
          echo '<tr id="'.$joueur[$val]['login'].'">';
      //
    	echo '<td>'.($key+1).'</td>    
    		<td class="s7">'.ahref($joueur[$val]['login'],'./include/statistiques.php?jid='.$joueur[$val]['id'],"contenu").'</td>
    		<td>'.$alliance[$joueur[$val]['alliance']]['nom'].'</td>
    		<td>'.($stat[$joueur[$val]['id']]['hab']+$stat[$joueur[$val]['id']]['totalHab']).'</td>
    		<td>'.($stat[$joueur[$val]['id']]['off']+$stat[$joueur[$val]['id']]['totalOff']).'</td>
    		<td>'.($stat[$joueur[$val]['id']]['def']+$stat[$joueur[$val]['id']]['totalDef']).'</td>
    		<td>'.($stat[$joueur[$val]['id']]['combat']+$stat[$joueur[$val]['id']]['totalCombat']).'</td>
    		</tr>';	
    }	  
    $i++;
  }
  
  echo '<tr class="tr_message">
            <td>&nbsp;</td>         
            <td class="s7"colspan="5">';
  echo '<input type="text" class="inputText" id="nomRecherche"/>&nbsp;<button class="search" type="submit" title="Supprimer" onClick="statFindJoueur();"></button>';//chgfond(document.getElementById(\'nomRecherche\').value);
  if(isset($trouver) && $trouver != true)
    echo '&nbsp; Login incorrect &nbsp;</td>';   
  else
    echo '&nbsp;&nbsp;</td>';      
  echo '<td align="right"><span class="c">';
  if($page > 0)
    echo ahref('&laquo;','./include/statistiques.php?type=j&&tri='.$sort.'&p='.($page-1),"contenu");
  echo '</span><span class="c">';
  if($i > ($page*$messParPage+$messParPage))
    echo ahref('&raquo;','./include/statistiques.php?type=j&&tri='.$sort.'&p='.($page+1),"contenu");
  echo '</span>&nbsp;</td>
        </tr>';
  echo '</table><br /><br />';
//onClick="document.getElementById(\'nomRecherche\').style.backgroudcolor=\'#111111\'"></button>';
}

//-----------------------------------------
//------------STAT PARTIES-----------------
//-----------------------------------------

function statJoueurPartie($partie,$sort,$page,$cherche = '')
{
  $joueur = statBdd('j_compte WHERE partie ='.$partie);
  $stat = statBdd('j_stat WHERE id IN (SELECT id FROM j_compte WHERE partie ='.$partie.')');
  $alliance = statBdd('j_alliance WHERE id IN (SELECT alliance FROM j_compte WHERE partie ='.$partie.')');
  
  if($sort == 'hab')
      $tabTri=triCarac($stat,'hab');
  if($sort == 'def')
      $tabTri=triCarac($stat,'def');
  if($sort == 'off')
      $tabTri=triCarac($stat,'off');
  if($sort == 'combat')
      $tabTri=triCarac($stat,'combat');
  $messParPage=18;

  $i=1;
  $suite=0;
  if($cherche != ''){
    $trouver = false;
    if(is_array($tabTri))
    foreach($tabTri as $key =>$val){
      if($joueur[$val]['login'] == $cherche){
        $page = (int)($i / $messParPage);
        $trouver = true;
      }
      $i++;
    }
  }
  echo '<br /><table cellspacing="1" cellpadding="2" class="statistiques">  
      <tr class="tr_message">    
          <td>Rang</td>
          <td>Login</td>
          <td>Alliance</td>    
          <td>'.ahref('Population','./include/statistiques.php?type=j&tri=hab',"contenu").'</td>
          <td>'.ahref('Attaque','./include/statistiques.php?type=j&tri=off',"contenu").'</td>
          <td>'.ahref('Défense','./include/statistiques.php?type=j&tri=def',"contenu").'</td>
          <td>'.ahref('Combat','./include/statistiques.php?type=j&tri=combat',"contenu").'</td>
      </tr> '; 	

  $premierMess=($page*$messParPage)+1;
  $dernierMess=$page*$messParPage+$messParPage;
  $i=1;
  $suite=0;      	
  if(is_array($tabTri))
  foreach($tabTri as $key =>$val)
  {
    if($i >= $premierMess && $i <= $dernierMess){
    	if($joueur[$val]['login'] == $cherche)
          echo '<tr class="plus" id="'.$joueur[$val]['login'].'">';
    	else
          echo '<tr id="'.$joueur[$val]['login'].'">';
      //
    	echo '<td>'.($key+1).'</td>    
    		<td class="s7">'.ahref($joueur[$val]['login'],'./include/statistiques.php?jid='.$joueur[$val]['id'],"contenu").'</td>
    		<td>'.$alliance[$joueur[$val]['alliance']]['nom'].'</td>
    		<td>'.($stat[$joueur[$val]['id']]['hab']).'</td>
    		<td>'.($stat[$joueur[$val]['id']]['off']).'</td>
    		<td>'.($stat[$joueur[$val]['id']]['def']).'</td>
    		<td>'.($stat[$joueur[$val]['id']]['combat']).'</td>
    		</tr>';	
    }	  
    $i++;
  }
  
  echo '<tr class="tr_message">
            <td>&nbsp;</td>         
            <td class="s7"colspan="5">';
  echo '<input type="text" class="inputText" id="nomRecherche"/>&nbsp;<button class="search" type="submit" title="Supprimer" onClick="statFindJoueur();"></button>';//chgfond(document.getElementById(\'nomRecherche\').value);
  if(isset($trouver) && $trouver != true)
    echo '&nbsp; Login incorrect &nbsp;</td>';   
  else
    echo '&nbsp;&nbsp;</td>';      
  echo '<td align="right"><span class="c">';
  if($page > 0)
    echo ahref('&laquo;','./include/statistiques.php?type=j&&tri='.$sort.'&p='.($page-1),"contenu");
  echo '</span><span class="c">';
  if($i > ($page*$messParPage+$messParPage))
    echo ahref('&raquo;','./include/statistiques.php?type=j&&tri='.$sort.'&p='.($page+1),"contenu");
  echo '</span>&nbsp;</td>
        </tr>';
  echo '</table><br /><br />';
//onClick="document.getElementById(\'nomRecherche\').style.backgroudcolor=\'#111111\'"></button>';
}

/*
function statAlliancePartie($partie,$tri,$page){
  $joueur = statBdd('j_compte WHERE partie ='.$partie);
  $stat = statBdd('j_stat WHERE id IN (SELECT id FROM j_compte WHERE partie ='.$partie.')');
  $alliance = statBdd('j_alliance WHERE id IN (SELECT alliance FROM j_compte WHERE partie ='.$partie.')');
  
  $tabTriHab=triAllianceCarac($alliance,$joueur,$stat,'hab');
  $tabTriDef=triAllianceCarac($alliance,$joueur,$stat,'def');
  $tabTriOff=triAllianceCarac($alliance,$joueur,$stat,'off');
  $tabTriCombat=triAllianceCarac($alliance,$joueur,$stat,'combat');

  $sort=array();
  if($tri == 'hab'){
    if(is_array($tabTriHab))
    foreach($tabTriHab as $key => $val)
      $sort[]=$key; 
  }
  if($tri == 'def'){
    if(is_array($tabTriDef))	
    foreach($tabTriDef as $key => $val)
      $sort[]=$key; 
  }
  if($tri == 'off'){
    if(is_array($tabTriOff))	
    foreach($tabTriOff as $key => $val)
      $sort[]=$key;   
   }   
  if($tri == 'combat'){
    if(is_array($tabTriCombat))	
    foreach($tabTriCombat as $key => $val)
      $sort[]=$key;   
   }      
   
      echo '<br /><table cellspacing="1" cellpadding="2" class="statistiques">  
          <tr class="tr_message">    
              <td>Rang</td>
              <td>Nom</td>    
              <td>'.ahref('Population','./include/statistiques.php?type=a&tri=hab',"contenu").'</td>
              <td>'.ahref('Attaque','./include/statistiques.php?type=a&tri=off',"contenu").'</td>
              <td>'.ahref('Défense','./include/statistiques.php?type=a&tri=def',"contenu").'</td>
              <td>'.ahref('Combat','./include/statistiques.php?type=a&tri=combat',"contenu").'</td>
          </tr> '; 	
      if(is_array($sort))	
      foreach($sort as $key =>$val)
      {
        echo '<tr>
              <td>'.($key+1).'</td>    
              <td>'.$alliance[$val]['nom'].'</td>
              <td>'.$tabTriHab[$val].'</td>
              <td>'.$tabTriOff[$val].'</td>
              <td>'.$tabTriDef[$val].'</td>
              <td>'.$tabTriCombat[$val].'</td>
              </tr>';		  
  		}
          echo ' <tr class="tr_message">
                    <td>&nbsp;</td>         
                    <td class="s7"colspan="4">';
          echo '<input type="text" class="inputText" id="nomRecherche"/>&nbsp;<button class="search" type="submit" title="Supprimer" onClick="window.alert(\'recherche de \'+document.getElementById(\'nomRecherche\').value)"></button>';
          echo '&nbsp;&nbsp;<span id="nbSelect"></span></td>         
                <td align="right"><span class="c"><b>&laquo;</b></span><a href="index.php?m=1&n=10">&raquo;</a>&nbsp;</td>     
                </tr>';
          echo '</table>';
  
}
*/