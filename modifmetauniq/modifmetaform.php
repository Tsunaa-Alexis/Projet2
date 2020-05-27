<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/768b55194c.js" crossorigin="anonymous"></script>
</head>
<body>


<?php

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);

// Infos de connexion
$host = 'localhost';
$db   = 'testest';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$i = 0;

// assignation des donnée de conextion à pdo
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (Exception $e) {
    die("Impossible de se connecter : " . $e->getMessage());
}



// Quand on a valider le formulaire le script se lance
if (isset($_POST['insert'])) {
    $pdo->beginTransaction();
    $i = $_POST['te'];
    //on récupère toutes les information du formulaire
    $desc = $_POST['desc'];
    $metadesc = $_POST['metadesc'];
    $kw = $_POST['kw'];
    $title = $_POST['title'];
    $choix = $_POST['desc'];
    $id = $_POST["id"];
    if (isset($_POST['descman'])){
        $descman = 1;
    }
    else {
        $descman = 0;
    }

    if (isset($_POST['metadescman'])){
        $metadescman = 1;
    }
    else {
        $metadescman = 0;
    }

    if (isset($_POST['metakwman'])){
        $metakwman = 1;
    }
    else {
        $metakwman = 0;
    }

    if (isset($_POST['metatitleman'])){
        $metatitleman = 1;
    }
    else {
        $metatitleman = 0;
    }




    // on prépare la requette sql qui va permettre de récupérer dans la bdd
    // toutes les informations utiles
    $sql = 'SELECT nom_ville,id_ville, code_postal_ville, gentile_h_ville, gentile_f_ville, population_ville, limitrophe_ville, nom_dep, num_dep, gentile_h_dep, gentile_f_dep, nom_reg, nom_greg
            FROM ville v, departement d, grande_region g, region_ancienne r
            WHERE v.id_dep = d.id_dep and d.id_greg = g.id_greg and d.id_reg = r.id_reg and id_ville ='.$id;

    // on parcours les résultat un à un pour donner un résultat adapter pour chaque ville récupérer
    foreach  ($pdo->query($sql) as $row) {

    // on récupère dans des variables chaque information dont ont aura besoin
        $nom = $row['nom_ville'];
        $cp = $row['code_postal_ville'];
        $gh = $row['gentile_h_ville'];
        $gf = $row['gentile_f_ville'];
        $pop = $row['population_ville'];
        $nomdep = $row['nom_dep'];
        $depgf = $row['gentile_f_dep'];
        $depgh = $row['gentile_h_dep'];
        $nomreg = $row['nom_reg'];
        $nomgreg = $row['nom_greg'];
        $numdep = $row['num_dep'];


        // on crée la variable lim pour y insérer les ville limitrophe si demander
        $lim = 0;
        // si on trouve <lim> dans aucun des champs du formulaire on ne fait rien, 
        // sinon on lance le script pour récuperer les limitrophes
        if (stristr($desc,'<lim>') === FALSE AND stristr($metadesc,'<lim>') === FALSE AND stristr($kw,'<lim>') === FALSE AND stristr($title,'<lim>') === FALSE ) {

                    
        }

        else {

            $sqld = 'SELECT GROUP_CONCAT(nom_ville) as ville_lim FROM ville WHERE code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,1,5) FROM ville WHERE id_ville ='.$row['id_ville'].') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,7,5) FROM ville WHERE id_ville = '.$row['id_ville'].') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,13,5) FROM ville WHERE id_ville = '.$row['id_ville'].') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,19,5) FROM ville WHERE id_ville = '.$row['id_ville'].') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,25,5) FROM ville WHERE id_ville = '.$row['id_ville'].')' ;
            // on prépare la requete sql dans $sqld pour récupérer le nom des limitrophe
            $sth = $pdo->prepare($sqld);
            // on éxécute la requete
            $sth->execute();
            // ont insert dans $resultat les valeurs récuperer
            $resultat = $sth->fetch(PDO::FETCH_ASSOC);
            // on rentre dans $lim la resultat que nous avons besoin
            $lim = $resultat['ville_lim'];
        }

        // on remplace les chaine de caractère demander par 
        // la variable choisis de la ville parcourue pour chaque champ
        // $remp1 correspond au champ description, remp2 lui à meta description, etc ...
        $remp1 = str_replace("<nomville>", " ".$nom." ",$desc);
        $remp1 = str_replace("<cp>", " ".$cp." ",$remp1);
        $remp1 = str_replace("<gh>", " ".$gh." ",$remp1);
        $remp1 = str_replace("<gf>", " ".$gf." ",$remp1);
        $remp1 = str_replace("<pop>", " ".$pop." ",$remp1);
        $remp1 = str_replace("<lim>", " ".$lim." ",$remp1);
        $remp1 = str_replace("<nomdep>", " ".$nomdep." ",$remp1);
        $remp1 = str_replace("<numdep>", " ".$numdep." ",$remp1);
        $remp1 = str_replace("<ghdep>", " ".$depgh." ",$remp1);
        $remp1 = str_replace("<gfdep>", " ".$depgf." ",$remp1);
        $remp1 = str_replace("<nomreg>", " ".$nomreg." ",$remp1);
        $remp1 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp1);

        $desc = $remp1;




        $remp2 = str_replace("<nomville>", " ".$nom." ",$metadesc);
        $remp2 = str_replace("<cp>", " ".$cp." ",$remp2);
        $remp2 = str_replace("<gh>", " ".$gh." ",$remp2);
        $remp2 = str_replace("<gf>", " ".$gf." ",$remp2);
        $remp2 = str_replace("<pop>", " ".$pop." ",$remp2);
        $remp2 = str_replace("<lim>", " ".$lim." ",$remp2);
        $remp2 = str_replace("<nomdep>", " ".$nomdep." ",$remp2);
        $remp2 = str_replace("<numdep>", " ".$numdep." ",$remp2);
        $remp2 = str_replace("<ghdep>", " ".$depgh." ",$remp2);
        $remp2 = str_replace("<gfdep>", " ".$depgf." ",$remp2);
        $remp2 = str_replace("<nomreg>", " ".$nomreg." ",$remp2);
        $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp2);

        $metadesc = $remp2;




        $remp3 = str_replace("<nomville>", " ".$nom." ",$kw);
        $remp3 = str_replace("<cp>", " ".$cp." ",$remp3);
        $remp3 = str_replace("<gh>", " ".$gh." ",$remp3);
        $remp3 = str_replace("<gf>", " ".$gf." ",$remp3);
        $remp3 = str_replace("<pop>", " ".$pop." ",$remp3);
        $remp3 = str_replace("<lim>", " ".$lim." ",$remp3);
        $remp3 = str_replace("<nomdep>", " ".$nomdep." ",$remp3);
        $remp3 = str_replace("<numdep>", " ".$numdep." ",$remp3);
        $remp3 = str_replace("<ghdep>", " ".$depgh." ",$remp3);
        $remp3 = str_replace("<gfdep>", " ".$depgf." ",$remp3);
        $remp3 = str_replace("<nomreg>", " ".$nomreg." ",$remp3);
        $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp3);

        $metakw = $remp3;



        $remp4 = str_replace("<nomville>", " ".$nom." ",$title);
        $remp4 = str_replace("<cp>", " ".$cp." ",$remp4);
        $remp4 = str_replace("<gh>", " ".$gh." ",$remp4);
        $remp4 = str_replace("<gf>", " ".$gf." ",$remp4);
        $remp4 = str_replace("<pop>", " ".$pop." ",$remp4);
        $remp4 = str_replace("<lim>", " ".$lim." ",$remp4);
        $remp4 = str_replace("<nomdep>", " ".$nomdep." ",$remp4);
        $remp4 = str_replace("<numdep>", " ".$numdep." ",$remp4);
        $remp4 = str_replace("<ghdep>", " ".$depgh." ",$remp4);
        $remp4 = str_replace("<gfdep>", " ".$depgf." ",$remp4);
        $remp4 = str_replace("<nomreg>", " ".$nomreg." ",$remp4);
        $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp4);

        $metatitle = $remp4;
                
        // on crée le tableau $data qui va stocker nos informations utile pour le update
        $data = [
                            
                    'id' => $row['id_ville'],
                            
                            
                ];

        // on rempli le début de la requete dans $sqli, ensuite on va tester pour chaque cas si le champ à été rempli,
        // si le champ n'a pas été rempli on ne fait rien sinon on ajoute le champ
        // qu'il nous faudra remplire et on y ajoute dans data les valeurs recupérer
        $sqli = "UPDATE ville SET";
        if (!empty($remp1)) {
            $sqli .= " description_ville = :descr";
            $data['descr'] = $remp1;
        }

        if ($descman == 1 or $descman == 0) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " desc_manuel_ville = :descman";

            }
            else {
                $sqli .= ", desc_manuel_ville = :descman";
            }
            $data['descman'] = $descman;
        }

        if ($metadescman == 1 or $metadescman == 0) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " meta_desc_manuel_ville = :metadescman";

            }
            else {
                $sqli .= ", meta_desc_manuel_ville = :metadescman";
            }
            $data['metadescman'] = $metadescman;
        }

        if ($metakwman == 1 or $metakwman == 0) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " meta_kw_manuel_ville = :metakwman";

            }
            else {
                $sqli .= ", meta_kw_manuel_ville = :metakwman";
            }
            $data['metakwman'] = $metakwman;
        }

        if ($metatitleman == 1 or $metatitleman == 0) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " meta_title_manuel_ville = :metatitleman";

            }
            else {
                $sqli .= ", meta_title_manuel_ville = :metatitleman";
            }
            $data['metatitleman'] = $metatitleman;
        }

        if (!empty($remp2)) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " meta_desc_ville = :metadesc";

            }
            else {
                $sqli .= ", meta_desc_ville = :metadesc";
            }
            $data['metadesc'] = $remp2;
        }

        if (!empty($remp3)) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " meta_kw_ville = :kw";
            }
            else {
                $sqli .= ", meta_kw_ville = :kw";
            }
            $data['kw'] = $remp3;
        }

        if (!empty($remp4)) {
            if ($sqli == "UPDATE ville SET") {
                $sqli .= " meta_title_ville = :title";
            }
            else {
                $sqli .= ", meta_title_ville = :title";
            }
            $data['title'] = $remp4;
        }


        // on ajoute les condition, si le champ est rempli la condition 
        // est d'ajouter uniquement pour ceux dont **_manuel_ville vaut nul
        $sqli .= " WHERE id_ville = :id";

        // la requete dans $sqli est complete, ont la prépare puis l'éxecute avec les donnée 
        // du array $data
        $stmt = $pdo->prepare($sqli)->execute($data);
    }
    $pdo->commit(); 
    
}


if ($i == 0) {
    $val = $_POST['city'];
    $val = str_replace(' (', ",", $val);
    $val = str_replace(')',"",$val);
    $val = explode(",",$val);
    $test = $_POST['city-hidden'];

    try {

        $pdo->beginTransaction();
        $sql = 'SELECT id_ville, description_ville, nom_ville, desc_manuel_ville,meta_desc_ville,meta_desc_manuel_ville,meta_kw_ville,meta_kw_manuel_ville,meta_title_ville,meta_title_manuel_ville FROM ville WHERE id_ville ="'.$test.'"' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);  
        $id = $resultat['id_ville'];
        $nom = $resultat['nom_ville'];
        $desc = $resultat['description_ville'];
        $descman = $resultat['desc_manuel_ville'];
        $metadesc = $resultat['meta_desc_ville'];
        $metadescman = $resultat['meta_desc_manuel_ville'];
        $metakw = $resultat['meta_kw_ville'];
        $metakwman = $resultat['meta_kw_manuel_ville'];
        $metatitle = $resultat['meta_title_ville'];
        $metatitleman = $resultat['meta_title_manuel_ville'];
        $pdo->commit(); 
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 
}

   
    





?>


    <form method = "POST" action = "modifmetaform.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend >Modification meta et description de <?php echo $nom ?></legend>
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Liste des variables
                    </button>
                </p>
                <div class="collapse" id="collapseExample">
                    <div id="ville" class="card card-body">
                        <table  class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Nom de la ville   </td>
                                    <td>&#60;nomville&#62;</td>
                                    <td>Nom du département  </td>
                                    <td>&#60;nomdep&#62;</td>
                                </tr>
                                <tr>
                                    <td>Code Postal  </td>
                                    <td>&#60;cp&#62;</td>
                                    <td>Numéro du département  </td>
                                    <td>&#60;numdep&#62;</td>
                                </tr>
                                <tr>
                                    <td>Gentile homme  </td>
                                    <td>&#60;gh&#62;</td>
                                    <td>Gentile homme du département  </td>
                                    <td>&#60;ghdep&#62;</td>
                                </tr>
                                <tr>
                                    <td>Gentile femme  </td>
                                    <td>&#60;gf&#62;</td>
                                    <td>Gentile femme du département  </td>
                                    <td>&#60;gfdep&#62;</td>
                                </tr>
                                <tr>
                                    <td>Population  </td>
                                    <td>&#60;pop&#62;</td>
                                    <td>Nom de l'ancienne région  </td>
                                    <td>&#60;nomreg&#62;</td>
                                </tr>
                                <tr>
                                    <td>Limitrophe  </td>
                                    <td>&#60;lim&#62;</td>
                                    <td>Nom de la grande région  </td>
                                    <td>&#60;nomgreg&#62;</td>
                                </tr>
                            </tbody>                            
                        </table>
                    </div>

                
            </fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <div class="form-group" style="padding-top: 20px;">
                    <label for="exampleFormControlTextarea1">Description</label>
                    <div class="custom-control custom-switch">
                        <?php
                        if (empty($descman) or $descman == 0) {
                            echo "<input type='checkbox' name='descman' value='descon' class='custom-control-input ' id='customSwitch1' >";
                        }
                        else {
                            echo "<input type='checkbox' name='descman' value='descon' class='custom-control-input' id='customSwitch1' checked>";
                        }
                        ?>
                        
                        <label class="custom-control-label" for="customSwitch1" >Bleu = <i class="fas fa-lock"></i> <br/></label>
                    </div>
                    <textarea class="form-control"  name="desc" id="exampleFormControlTextarea1" <?php if (empty($desc)){
                                echo "placeholder='Null'";
                            }?> rows="2"><?php if (!empty($desc)) { echo $desc; }?></textarea>
                </div> 
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Meta description</label>
                    <div class="custom-control custom-switch">
                        <?php
                        if (empty($metadescman) or $metadescman == 0) {
                            echo "<input type='checkbox' name='metadescman' value='descon' class='custom-control-input ' id='customSwitch2' >";
                        }
                        else {
                            echo "<input type='checkbox' name='metadescman' value='descon' class='custom-control-input' id='customSwitch2' checked>";
                        }
                        ?>
                        
                        <label class="custom-control-label" for="customSwitch2" >Bleu = <i class="fas fa-lock"></i> <br/></label>
                    </div>
                    <textarea class="form-control" name="metadesc" id="exampleFormControlTextarea1" <?php if (empty($metadesc)){
                                echo "placeholder='Null'";
                            }?> rows="2"><?php if (!empty($metadesc)) { echo $metadesc; }?></textarea>
                </div> 
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Meta KW</label>
                    <div class="custom-control custom-switch">
                        <?php
                        if (empty($metakwman) or $metakwman == 0) {
                            echo "<input type='checkbox' name='metakwman' value='descon' class='custom-control-input ' id='customSwitch3' >";
                        }
                        else {
                            echo "<input type='checkbox' name='metakwman' value='descon' class='custom-control-input' id='customSwitch3' checked>";
                        }
                        ?>
                        
                        <label class="custom-control-label" for="customSwitch3" >Bleu = <i class="fas fa-lock"></i> <br/></label>
                    </div>
                    <textarea class="form-control" name="kw" id="exampleFormControlTextarea1" <?php if (empty($metakw)){
                                echo "placeholder='Null'";
                            }?> rows="2"><?php if (!empty($metakw)) { echo $metakw; }?></textarea>
                </div> 
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Meta Title</label>
                    <div class="custom-control custom-switch">
                        <?php
                        if (empty($metatitleman) or $metatitleman == 0) {
                            echo "<input type='checkbox' name='metatitleman' value='descon' class='custom-control-input ' id='customSwitch4' >";
                        }
                        else {
                            echo "<input type='checkbox' name='metatitleman' value='descon' class='custom-control-input' id='customSwitch4' checked>";
                        }
                        ?>
                        
                        <label class="custom-control-label" for="customSwitch4" >Bleu = <i class="fas fa-lock"></i> <br/></label>
                    </div>
                    <textarea class="form-control" name="title" id="exampleFormControlTextarea1"<?php if (empty($metatitle)){
                                echo "placeholder='Null'";
                            }?> rows="2"><?php if (!empty($metatitle)) { echo $metatitle; }?></textarea>
                </div>  
                <input type="text" name="id" value="<?php echo $id; ?>" hidden>
                <input type="text" name="te" value="1" hidden>        
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-4">
                        <button style="padding-left: 50%; padding-right: 50%; margin-left: 50%;" type="submit" name="insert"class="btn btn-primary" ><i class="far fa-save"></i> <span class="glyphicon glyphicon-send"></span></button>
                    </div>
                </div>
            </fieldset>
        </fieldset>
    </form>



    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>








</body>
</html>
