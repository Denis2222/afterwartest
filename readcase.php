<?php
include("/home/epilog/www/include.php");
echo '<pre>';

//for($i=1;$i<15;$i++){
//for($j=1;$j<15;$j++){

//$case = new CaseObject(11,7,"e_map1");

$case = new CaseObject(30,30,"p_map24");

print_r($case);

//}
//}

echo '</pre>';

echo '<br />';
foreach( $case->ville as $villes )
  echo $villes['id_alliance'];
?>