<?php
class Inventaire{
    
    var $objet;
    
    function Inventaire(){
        
    }
    
    function Afficher(){        
        $html= '<table cellspacing="1" cellpadding="2" class="message"><tr class="tr_message"><td width="22"></td></tr><tr><td>';
        $html.= 'Vous avez une usine d\'armement dans l\'inventaire.<br /><img src="'.$GLOBALS['skin'].'uarm.png"/><br /> LoL ...Pas encore codé... ';
        $html.= '</td></tr><tr class="tr_message"><td></td></tr></table>';       
    return $html; 
    }
}
?>