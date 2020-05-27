<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/768b55194c.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include('..\index\include1.php'); ?>

<?php

ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);

// Infos de connexion
$host = 'localhost';
$db   = 'testest';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=3308;dbname=$db;charset=$charset";
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




    $sql = 'SELECT id_dep, nom_dep, num_dep, gentile_h_dep, gentile_f_dep, nom_reg, nom_greg, limitrophe_dep, chef_lieu_dep
                    FROM  departement d, grande_region g, region_ancienne r
                    WHERE  d.id_greg = g.id_greg and d.id_reg = r.id_reg and id_dep='.$id;

            foreach  ($pdo->query($sql) as $row) {
                $nomdep = $row['nom_dep'];
                $depgf = $row['gentile_f_dep'];
                $depgh = $row['gentile_h_dep'];
                $nomreg = $row['nom_reg'];
                $nomgreg = $row['nom_greg'];
                $numdep = $row['num_dep'];
                $cheflieudep = $row['chef_lieu_dep'];
                $lim=0;
                $cheflieu = 0;
                if (stristr($desc,'<limdep>') === FALSE AND stristr($metadesc,'<limdep>') === FALSE AND stristr($kw,'<limdep>') === FALSE AND stristr($title,'<limdep>') === FALSE ) {

                    
                }

                else {
                    $sqld = 'SELECT GROUP_CONCAT(nom_dep) as dep_lim FROM departement WHERE code_insee_dep = (SELECT SUBSTRING(limitrophe_dep,1,2) FROM departement WHERE id_dep = '.$row['id_dep'].') 
                        or code_insee_dep = (SELECT SUBSTRING(limitrophe_dep,4,2) FROM departement WHERE id_dep = '.$row['id_dep'].') 
                        or code_insee_dep = (SELECT SUBSTRING(limitrophe_dep,7,2) FROM departement WHERE id_dep = '.$row['id_dep'].') 
                        or code_insee_dep = (SELECT SUBSTRING(limitrophe_dep,10,2) FROM departement WHERE id_dep = '.$row['id_dep'].') 
                        or code_insee_dep = (SELECT SUBSTRING(limitrophe_dep,13,2) FROM departement WHERE id_dep = '.$row['id_dep'].')' ;
                    $sth = $pdo->prepare($sqld);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    $lim = $resultat['dep_lim'];
                }

                if (stristr($desc,'<cheflieudep>') === FALSE) {
                }
                else {
                    $sqlf = 'SELECT nom_ville FROM departement d , ville v WHERE chef_lieu_dep = code_insee_ville and d.id_dep ='.$row['id_dep'];
                    $sths = $pdo->prepare($sqlf);
                    $sths->execute();
                    $resultat = $sths->fetch(PDO::FETCH_ASSOC);
                    $cheflieu = $resultat['nom_ville'];
                }


                $remp1 = str_replace("<nomdep>", " ".$nomdep." ",$desc);
                $remp1 = str_replace("<numdep>", " ".$numdep." ",$remp1);
                $remp1 = str_replace("<ghdep>", " ".$depgh." ",$remp1);
                $remp1 = str_replace("<gfdep>", " ".$depgf." ",$remp1);
                $remp1 = str_replace("<nomreg>", " ".$nomreg." ",$remp1);
                $remp1 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp1);
                $remp1 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp1);
                $remp1 = str_replace("<limdep>", " ".$lim." ",$remp1);

                $desc = $remp1;


                $remp2 = str_replace("<nomdep>", " ".$nomdep." ",$metadesc);
                $remp2 = str_replace("<numdep>", " ".$numdep." ",$remp2);
                $remp2 = str_replace("<ghdep>", " ".$depgh." ",$remp2);
                $remp2 = str_replace("<gfdep>", " ".$depgf." ",$remp2);
                $remp2 = str_replace("<nomreg>", " ".$nomreg." ",$remp2);
                $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp2);
                $remp2 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp2);
                $remp2 = str_replace("<limdep>", " ".$lim." ",$remp2);

                $metadesc = $remp2;


                $remp3 = str_replace("<nomdep>", " ".$nomdep." ",$kw);
                $remp3 = str_replace("<numdep>", " ".$numdep." ",$remp3);
                $remp3 = str_replace("<ghdep>", " ".$depgh." ",$remp3);
                $remp3 = str_replace("<gfdep>", " ".$depgf." ",$remp3);
                $remp3 = str_replace("<nomreg>", " ".$nomreg." ",$remp3);
                $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp3);
                $remp3 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp3);
                $remp3 = str_replace("<limdep>", " ".$lim." ",$remp3);

                $metakw = $remp3;

                $remp4 = str_replace("<nomdep>", " ".$nomdep." ",$title);
                $remp4 = str_replace("<numdep>", " ".$numdep." ",$remp4);
                $remp4 = str_replace("<ghdep>", " ".$depgh." ",$remp4);
                $remp4 = str_replace("<gfdep>", " ".$depgf." ",$remp4);
                $remp4 = str_replace("<nomreg>", " ".$nomreg." ",$remp4);
                $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp4);
                $remp4 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp4);
                $remp4 = str_replace("<limdep>", " ".$lim." ",$remp4);
                
                $metatitle = $remp4;

                $data = [
                            
                            'id' => $row['id_dep'],
                            
                            
                        ];

                $sqli = "UPDATE departement SET";
                if (!empty($remp1)) {
                    $sqli .= " description_dep = :descr";
                    $data['descr'] = $remp1;
                }

                if ($descman == 1 or $descman == 0) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " desc_manuel_dep = :descman";

                    }
                    else {
                        $sqli .= ", desc_manuel_dep = :descman";
                    }
                    $data['descman'] = $descman;
                }

                if ($metadescman == 1 or $metadescman == 0) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " meta_desc_manuel_dep = :metadescman";

                    }
                    else {
                        $sqli .= ", meta_desc_manuel_dep = :metadescman";
                    }
                    $data['metadescman'] = $metadescman;
                }

                if ($metakwman == 1 or $metakwman == 0) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " meta_kw_manuel_dep = :metakwman";

                    }
                    else {
                        $sqli .= ", meta_kw_manuel_dep = :metakwman";
                    }
                    $data['metakwman'] = $metakwman;
                }

                if ($metatitleman == 1 or $metatitleman == 0) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " meta_title_manuel_dep = :metatitleman";

                    }
                    else {
                        $sqli .= ", meta_title_manuel_dep = :metatitleman";
                    }
                    $data['metatitleman'] = $metatitleman;
                }

                if (!empty($remp2)) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " meta_desc_dep = :metadesc";

                    }
                    else {
                        $sqli .= ", meta_desc_dep = :metadesc";
                    }
                    $data['metadesc'] = $remp2;
                }

                if (!empty($remp3)) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " meta_kw_dep = :kw";
                    }
                    else {
                        $sqli .= ", meta_kw_dep = :kw";
                    }
                    $data['kw'] = $remp3;
                }

                if (!empty($remp4)) {
                    if ($sqli == "UPDATE departement SET") {
                        $sqli .= " meta_title_dep = :title";
                    }
                    else {
                        $sqli .= ", meta_title_dep = :title";
                    }
                    $data['title'] = $remp4;
                }


                $sqli .= " WHERE id_dep = :id";

               

                $stmt = $pdo->prepare($sqli)->execute($data);
            }
        
    $pdo->commit(); 
    
}


if ($i == 0) {
    $val = $_POST['dep'];
    

    try {

        $pdo->beginTransaction();
        $sql = 'SELECT id_dep, description_dep, nom_dep, desc_manuel_dep,meta_desc_dep,meta_desc_manuel_dep,meta_kw_dep,meta_kw_manuel_dep,meta_title_dep,meta_title_manuel_dep FROM departement WHERE id_dep ="'.$val.'"' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);  
        $id = $resultat['id_dep'];
        $nomdep = $resultat['nom_dep'];
        $desc = $resultat['description_dep'];
        $descman = $resultat['desc_manuel_dep'];
        $metadesc = $resultat['meta_desc_dep'];
        $metadescman = $resultat['meta_desc_manuel_dep'];
        $metakw = $resultat['meta_kw_dep'];
        $metakwman = $resultat['meta_kw_manuel_dep'];
        $metatitle = $resultat['meta_title_dep'];
        $metatitleman = $resultat['meta_title_manuel_dep'];
        $pdo->commit(); 
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 
}

   
    





?>


    <form method = "POST" action = "modifmetaformdep.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend >Modification meta et description de <?php echo $nomdep; ?></legend>
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Liste des variables
                    </button>
                </p>
                <div class="collapse" id="collapseExample">
                    <div id="dep" class="card card-body" >
                         <table  class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Nom du département </td>
                                    <td>&#60;nomdep&#62;</td>
                                    <td>Nom de l'ancienne région  </td>
                                    <td>&#60;nomreg&#62;</td>
                                </tr>
                                <tr>
                                    <td>Numéro du département </td>
                                    <td>&#60;numdep&#62;</td>
                                    <td>Nom de la grande région  </td>
                                    <td>&#60;nomgreg&#62;</td>
                                </tr>
                                <tr>
                                    <td>Gentile homme  </td>
                                    <td>&#60;ghdep&#62;</td>
                                    <td>Chef lieu du département  </td>
                                    <td>&#60;cheflieudep&#62;</td>
                                </tr>
                                <tr>
                                    <td>Gentile femme  </td>
                                    <td>&#60;gfdep&#62;</td>
                                    <td>Limitrophe du département  </td>
                                    <td>&#60;limdep&#62;</td>
                                </tr>
                            </tbody>
                         </table>
                    </div>
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


<?php include('..\index\include2.php'); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>








</body>
</html>
