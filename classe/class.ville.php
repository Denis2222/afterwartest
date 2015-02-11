<?php
class Ville{
    var $id;
    var $nom;
    var $date_dern_action;
    var $X;
    var $Y;
    var $map;

    // Paysans et repartition
    var $bucheron;
    var $mineur;
    var $paysans;

    // Niveau des batiments
    var $hdv;
    var $mine;
    var $scierie;
    var $caserne;    
    var $tour;    
    var $uarm;
    var $entrepot;
    var $recherche;
    var $marche;
    //Conteneur
    var $batHoteldeville;
    var $batMine;
    var $batScierie;
    var $batCaserne;
    var $batTour;
    var $batUarm;
    var $batEntrepot;
    var $batRecherche;
    var $batMarche;

    //Construction en cour
    var $construction;
    var $idActionConstruction;
    var $time;

    var $marcheContent;
    var $garnison;
    var $entrainement;
    var $etat;
    var $idCompte;

    var $apercu;
        
    function load($id){
        //echo 'load Ville '.$id;
        
        $sql = $GLOBALS["db"]->query('SELECT * FROM j_ville WHERE id = '.$id)or die ('Erreur : '.mysql_error());
        $donnees = mysql_fetch_array($sql);

        $this->id = $donnees['id'];
        $this->idCompte = $donnees['idCompte'];
        $this->idAlliance = $donnees['idAlliance'];
        $this->nom = $donnees['nom'];
        $this->date_dern_action = $donnees['date_dern_action'];
        $this->X = $donnees['X'];
        $this->Y = $donnees['Y'];
        $this->map = $donnees['map'];
        $this->mineur = $donnees['mineur']; // NB paysan atribué à la mine
        $this->bucheron = $donnees['bucheron']; // NB paysan atribué à la scierie
        $this->paysans = $donnees['paysans'];

        $this->hdv = $donnees['hdv'];
        $this->mine = $donnees['mine']; // Niveau Mine
        $this->scierie = $donnees['scierie']; // Niveau Scierie
        $this->caserne = $donnees['caserne'];
        $this->tour = $donnees['tour'];
        $this->uarm = $donnees['uarm'];
        $this->entrepot = $donnees['entrepot'];
        $this->recherche = $donnees['recherche'];
        $this->marche = $donnees['marche'];

        $this->construction = $donnees['construction'];
        $this->idActionConstruction = $donnees['idActionConstruction'];
        $this->time = $donnees['time'];
        $this->garnison = unserialize($donnees['garnison']);
        $this->marcheContent = unserialize($donnees['marcheContent']);
        $this->tourContent = unserialize($donnees['tourContent']);
        $this->etat = $donnees['etat'];


        $time = time();

        $this->entrainement = unserialize($donnees['entrainement']);


        //$this->oldThis = $this;
        //echo $this->$bat;
        //echo 'Niv Scierie'.$this->scierie;
        //echo 'Construction :'.$this->construction.' END';

        /********************************************************************/
        /**********************MAINTENANCE VILLE*****************************/
        /********************************************************************/


        if($this->time < $time AND $this->time > 0){
        $_SESSION['AugAJour']=0;
        $bat = $this->construction;
        //echo 'Ville Auto :'.$this->construction.' Niv'.$this->$this->construction.'->'.$this->$this->construction++.'<br />';
        //echo $this->scierie;
        //$bat = $this->construction;
        $this->$bat++;
        $this->construction="";
        $this->idActionConstruction=0;
        $this->time=0;
        $this->save();

        $joueur = new Joueur();
        $joueur->loadSimple($this->idCompte);
        $joueur->maxRess();
        $joueur->save();
        $stat = new Stat();
        $stat->load($this->idCompte);
        $stat->calculHab();
        $stat->save();
        }


        if(is_object($this->tourContent)){
        if($this->tourContent->arriveTime <= time()){

            if($this->tourContent->arriveTime != 0){
                   $this->tourContent->genererMessage($this->tour,$this->idCompte);
                   $this->tourContent->arriveTime=0;
                   $this->save();
            }

            if($this->tourContent->finishTime <= time()){
                $this->tourContent->arriveTime = 0;
                $this->tourContent->finishTime = 0;
                $this->save();
            }

        }
        }


        if(is_object($this->entrainement)){
            $newUnite = $this->entrainement->terminerEntrainement();
        }else{
            $this->entrainement = new Entrainement();
            $this->save();
        }
        if(isset($newUnite[0])){
            $this->garnison->nouvelleRecrue($newUnite);
            $this->save();
        }

        if(!is_object($this->garnison)){
        $this->garnison = new Garnison();
        }
    
    }

    function loadSimple($id){

            $sql = $GLOBALS["db"]->query('SELECT * FROM j_ville WHERE id = '.$id)or die ('Erreur : '.mysql_error());
            $donnees = mysql_fetch_array($sql);

            $this->id = $donnees['id'];
            $this->idCompte = $donnees['idCompte'];
            $this->idAlliance = $donnees['idAlliance'];
            $this->nom = $donnees['nom'];
            $this->date_dern_action = $donnees['date_dern_action'];
            $this->X = $donnees['X'];
            $this->Y = $donnees['Y'];
            $this->map = $donnees['map'];
            $this->mineur = $donnees['mineur']; // NB paysan atribué à la mine
            $this->bucheron = $donnees['bucheron']; // NB paysan atribué à la scierie
            $this->paysans = $donnees['paysans'];

            $this->hdv = $donnees['hdv'];
            $this->mine = $donnees['mine']; // Niveau Mine
            $this->scierie = $donnees['scierie']; // Niveau Scierie
            $this->caserne = $donnees['caserne'];
            $this->tour = $donnees['tour'];
            $this->uarm = $donnees['uarm'];
            $this->entrepot = $donnees['entrepot'];
            $this->recherche = $donnees['recherche'];
            $this->marche = $donnees['marche'];

            $this->construction = $donnees['construction'];
            $this->idActionConstruction = $donnees['idActionConstruction'];
            $this->time = $donnees['time'];
            $this->garnison = unserialize($donnees['garnison']);
            //$this->marcheContent = unserialize($donnees['marcheContent']);
            //$this->tourContent = unserialize($donnees['tourContent']);
            $this->etat = $donnees['etat'];
            $time = time();
            //$this->entrainement = unserialize($donnees['entrainement']);
            //$this->oldThis = $this;
            //echo $this->$bat;
            //echo 'Niv Scierie'.$this->scierie;
            //echo 'Construction :'.$this->construction.' END';
    }
        
    function save(){
            echo '';
                  if($this != $this->oldThis){
                             echo 'save()';
                            if(!isset($this->construction)){$this->construction="";}
                            $garnison = serialize($this->garnison);
                            $entrainement = serialize($this->entrainement);
                            $marcheContent = serialize($this->marcheContent);
                            $tourContent = serialize($this->tourContent);


                            $sql = "UPDATE j_ville SET
                            idCompte = '".$this->idCompte."',
                            idAlliance = '".$this->idAlliance."',
                            nom = '".$this->nom."',
                            date_dern_action = '".$this->date_dern_action."',
                            mineur = '".$this->mineur."',
                            bucheron = '".$this->bucheron."',
                            paysans = '".$this->paysans."',
                            hdv = '".$this->hdv."',
                            mine = '".$this->mine."',
                            scierie = '".$this->scierie."',
                            caserne = '".$this->caserne."',
                            tour = '".$this->tour."',
                            uarm = '".$this->uarm."',
                            entrepot = '".$this->entrepot."',
                            recherche = '".$this->recherche."',
                            marche = '".$this->marche."',
                            X = '".$this->X."',
                            Y = '".$this->Y."',
                            map = '".$this->map."',
                            construction = '".$this->construction."',
                            idActionConstruction = '".$this->idActionConstruction."',
                            time = '".$this->time."',
                            garnison = '".$garnison."',
                            marcheContent = '".$marcheContent."',
                            tourContent = '".$tourContent."',
                            garnison = '".$garnison."',
                            etat = '".$this->etat."',
                            entrainement = '".$entrainement."'
                            WHERE `id` = '".$this->id."' ;";

                            echo $sql;
                            $GLOBALS['db']->query($sql);
                  }
    }

    function init($idPartie,$posFix = false){

        $this->date_dern_action = time();
        $p=new Partie();
        $p->load($idPartie);
        $p->taille($mX,$mY);
        $rX=0;
        $rY=0;

        if($posFix != true){

            $rX=0;
            $rY=0;
            $stop = false;
            $case=0;

            while($stop == false){
                $rX=rand(1,$mX);
                $rY=rand(1,$mY);		  
                $case=new CaseObject($rX,$rY,$this->map);
                $stop = $case->libre();//la case ne contien ni ville,ni héro,ni mob 
            }

            $this->X = $rX;
            $this->Y = $rY;

        }else{
            $rX = (int)($mX/2)+1;
            $rY = (int)($mY/2)+1;
            $case=new CaseObject($rX,$rY,$this->map);
        }

        $j=new Joueur;
        $j->load($_SESSION['jid'],0,0,true);
        $ally=new Alliance();
        $ally->load($j->alliance);
        $case->ajouterVille($this->id,$j->login,$j->id,$ally->nom,$ally->id,$this->nom);
        $case->save();

        $this->paysans = 10;
        $this->mineur = 0; // NB paysan atribué à la mine
        $this->bucheron = 0; // NB paysan atribué à la scierie
        $this->hdv = 1;
        $this->mine = 1; // Niveau Mine
        $this->scierie = 1; // Niveau Scierie
        $this->caserne = 0;
        $this->tour = 0;
        $this->X = $rX;
        $this->Y = $rY;
        $this->garnison = new Garnison();
        $this->garnison->genererStats();

        $this->entrainement = new Entrainement();
    }

    function implantation($idPartie,$X,$Y){

        $this->date_dern_action = time();

        $j=new Joueur;
        $j->loadSimple($_SESSION['jid']);
        $ally=new Alliance();
        $ally->load($j->alliance);
        $case=new CaseObject($X,$Y,$this->map);
        $case->ajouterVille($this->id,$j->login,$j->id,$ally->nom,$ally->id,$this->nom);
        $case->save();

        $this->X = $X;
        $this->Y = $Y;
        $this->mineur = 0; // NB paysan atribué à la mine
        $this->bucheron = 0; // NB paysan atribué à la scierie
        $this->hdv = 1;
        $this->mine = 0; // Niveau Mine
        $this->scierie = 0; // Niveau Scierie
        $this->caserne = 0;

        $this->garnison = new Garnison;
        $this->garnison->genererStats();

        $this->entrainement = new Entrainement;

    }

    function insert($idPartie,$map,$idCompte,$idAlliance,$nom){
      $sql = "INSERT INTO j_ville(idCompte,idAlliance,nom,map)
                            VALUES('".$idCompte."','".$idAlliance."','".$nom."','".$map."')";

      $GLOBALS['db']->query($sql);
      $cpt=$GLOBALS['db']->query("SELECT LAST_INSERT_ID() as nb FROM j_ville " );
      $compt = mysql_fetch_object($cpt);
      return $compt->nb;
    }

    function delet(){//suppresion de la ville chargé
      if($this->id > 0)
      $GLOBALS['db']->query('DELETE FROM j_ville WHERE id = '.$this->id);  
    }

    function suppression($id){//suppresion sans avoir chargé la ville
      $GLOBALS['db']->query('DELETE FROM j_ville WHERE id = '.$id);
    }
  
    function constructionPossible($bat){
            $GLOBALS['recherche']->loadVille($this);
            if($GLOBALS['recherche']->BatimentFaisable($bat) == 0){
                    return true;
            }else{
                    return false;
            }
    }
  
    function affiche($get){

        $html = '<div class="contenu_fond_gauche">
        <div class="statut">
        <div class="constructions"><img src="./skin/original/texte/construction.png" alt="construction"/></div>
        <div class="construction">
        <table>';

        if(is_array($GLOBALS['batiment']))
        foreach($GLOBALS['batiment'] as $key => $value){
             if($this->$value > 0){
                 $html.='<tr><td>'.ahref($GLOBALS['batiment_nom'][$key].' ['.$this->$value.']','data.php?div=contenu&v='.$this->id.'&o='.$value,'contenu').'</td></tr>';
             }
             if($this->$value == 0 && $this->constructionPossible($GLOBALS['batiment'][$key]))
                 $html.='<tr><td>'.ahref('Construire '.$GLOBALS['batiment_nom'][$key],'data.php?div=contenu&v='.$this->id.'&o='.$value,'contenu').'</td></tr>';
         }

        if($this->idActionConstruction>0){
             $html.='<tr><td><br />Amélioration :</td></tr>
             <tr><td>'.$GLOBALS['batiment_nom'][array_search($this->construction,$GLOBALS['batiment'])].' <span id="timer'.$_SESSION['timer']++.'"> '.temp_seconde(round($this->time - time())).'</span></td></tr>';
         }
        $html.='</table>';
        $html.='<br /><div class="heros"><img src="./skin/original/texte/heros_mvt.png" alt="Héros"/></div><br />';

        $case = new CaseObject($this->X,$this->Y,$this->map);

           //$case->CaseObject();

        $html.= $case->afficheResumeSurVille();

        $html.='</div></div>        
        </div>
        <div class="contenu_fond_centre">';
        if(!isset($get['o'])){$get['o'] = "aucun";}

        switch($get['o']){

              case "caserne":
                      $this->batCaserne = new Caserne($this->caserne,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batCaserne->Affiche($get);
                      break;
              case "hdv":
                 $this->batHotelDeVille = new HotelDeVille($this->hdv,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batHotelDeVille->Affiche($get);
                      break;

              case "mine":
                      $this->batMine = new Mine($this->mine,$this->id,$this->idActionConstruction);
                      $html.= $this->batMine->Affiche($get);
                      break;
              case "scierie":
                      $this->batScierie = new Scierie($this->scierie,$this->id,$this->idActionConstruction);
                      $html.= $this->batScierie->Affiche($get);
                      break;
              case "tour":
                      $this->batTour = new Tour($this->tour,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batTour->Affiche($get);
                      break;

              case "uarm":
                      $this->batUarm = new Uarm($this->uarm,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batUarm->Affiche($get);
                      break;

              case "entrepot":
                      $this->batEntrepot = new Entrepot($this->entrepot,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batEntrepot->Affiche($get);
                      break;

              case "recherche":
                      $this->batRecherche = new Recherche($this->recherche,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batRecherche->Affiche($get);
                      break;
              case "marche":
                      $this->batMarche = new Marche($this->marche,$this->id,$this->idActionConstruction,$this);
                      $html.= $this->batMarche->Affiche($get);
                      break;
              default:
                      $html .= $this->afficher_vue_ville();
                      break;
          }

        $html.= '</div>
        <div class="contenu_fond_droite">
          <div class="unites"><img src="./skin/original/texte/unites.png" alt="unités"/></div>
        <div class="unite">';

        if(is_object($this->garnison)){
            $html.=$this->garnison->AfficherUnite(1);
        }else{
            $this->garnison = new Garnison();
        }

        $html.='</div>
        <div class="productions"><img src="./skin/original/texte/production.png" alt="production"/></div>
        <div class="production">';

        $html.=$this->entrainement->afficheListe();
        $html.=' 
        </div>    		
        </div>';

          return $html;
    }

    function afficher_vue_ville(){
       $html = '<img src="'.$_SESSION['skin'].'vue_ville/ville_sans_bat.jpg" alt="ville">';
       if($this->caserne > 0){
        $html .= ahref('<img class="caserne" src="'.$_SESSION['skin'].'vue_ville/caserne.png" alt="Caserne">','data.php?div=contenu&v='.$this->id.'&o='.'caserne','contenu');
       }     
       if($this->scierie > 0){
        $html .= ahref('<img class="scierie" src="'.$_SESSION['skin'].'vue_ville/scierie.png" alt="Scierie">','data.php?div=contenu&v='.$this->id.'&o='.'scierie','contenu');
       }    
       if($this->mine > 0){
        $html .= ahref('<img class="mine" src="'.$_SESSION['skin'].'vue_ville/mine.png" alt="Mine">','data.php?div=contenu&v='.$this->id.'&o='.'mine','contenu');
       }
       if($this->tour > 0){
        $html .= ahref('<img class="tour" src="'.$_SESSION['skin'].'vue_ville/espionnage.png" alt="Tour d\'espionnage">','data.php?div=contenu&v='.$this->id.'&o='.'tour','contenu');
       }
       if($this->hdv > 0){
        $html .= ahref('<img class="hdv" src="'.$_SESSION['skin'].'vue_ville/hdv.png" alt="Hotel de ville">','data.php?div=contenu&v='.$this->id.'&o='.'hdv','contenu');
       }
       if($this->uarm > 0){
        $html .= ahref('<img class="uarm" src="'.$_SESSION['skin'].'vue_ville/uarm.png" alt="Usine d\'Armement">','data.php?div=contenu&v='.$this->id.'&o='.'uarm','contenu');
       }
       if($this->entrepot > 0){
        $html .= ahref('<img class="entrepot" src="'.$_SESSION['skin'].'vue_ville/entrepot.png" alt="Entrepôt">','data.php?div=contenu&v='.$this->id.'&o='.'entrepot','contenu');
       }
       if($this->recherche > 0){
        $html .= ahref('<img class="recherche" src="'.$_SESSION['skin'].'vue_ville/recherche.png" alt="Centre de recherche">','data.php?div=contenu&v='.$this->id.'&o='.'recherche','contenu');
       }
       if($this->marche > 0){
        $html .= ahref('<img class="marche" src="'.$_SESSION['skin'].'vue_ville/marche.png" alt="Magasin">','data.php?div=contenu&v='.$this->id.'&o='.'marche','contenu');
       }
       return $html;
    }
}