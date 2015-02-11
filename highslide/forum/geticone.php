<?php
Function geticone($string) 
	  {

$in=array(
           " :-)" , 
           " :-d" ,
           " :-(", 
           " :-b", 
           " ;-)", 
           " :-$", 
           " :-l", 
           " 8-)", 
           " :-n", 
           " :-pc"
           );

$out=array(
           '<img src=\"'.$style_dir.'./forum/icones/visage.jpg\" alt=\"visage.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/rire.jpg\" alt=\"rire.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/bad.jpg\" alt=\"bad.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/banane.gif\" alt=\"banane.gif\" />',
           '<img src=\"'.$style_dir.'./forum/icones/clin.jpg\" alt=\"clin.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/emotion.jpg\" alt=\"emotion.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/lent.jpg\" alt=\"lent.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/lunette.jpg\" alt=\"lunette.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/nah.jpg\" alt=\"nah.jpg\" />',
           '<img src=\"'.$style_dir.'./forum/icones/pc.jpg\" alt=\"/pc.jpg\" />'
           );

           return str_replace($in,$out,$string);
 	  } ;
?>
