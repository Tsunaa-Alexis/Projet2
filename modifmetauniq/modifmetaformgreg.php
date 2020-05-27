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




    $sql = 'SELECT id_greg, nom_greg, chef_lieu_greg
                    FROM  grande_region
                    WHERE id_greg ='.$id;

            foreach  ($pdo->query($sql) as $row) {
                $nomgreg = $row['nom_greg'];
                $cheflieugreg = $row['chef_lieu_greg'];
                $cheflieu = 0;

                if (stristr($desc,'<cheflieugreg>') === FALSE) {
                }
                else {
                    $sqlf = 'SELECT nom_ville FROM grande_region g , ville v WHERE chef_lieu_greg = code_insee_ville and g.id_greg ='.$row['id_greg'];
                    $sths = $pdo->prepare($sqlf);
                    $sths->execute();
                    $resultat = $sths->fetch(PDO::FETCH_ASSOC);
                    $cheflieu = $resultat['nom_ville'];
                }


                $remp1 = str_replace("<nomgreg>", " ".$nomgreg." ",$desc);
                $remp1 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp1);

                $desc = $remp1;



                $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$metadesc);
                $remp2 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp2);

                $metadesc = $remp2;


                $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$kw);
                $remp3 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp3);

                $metakw = $remp3;


                $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$title);
                $remp4 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp4);

                $metatitle = $remp4;
                
                $data = [
                            
                            'id' => $row['id_greg'],
                            
                            
                        ];

                $sqli = "UPDATE grande_region SET";
                if (!empty($remp1)) {
                    $sqli .= " description_greg = :descr";
                    $data['descr'] = $remp1;
                }

                if ($descman == 1 or $descman == 0) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " description_manuel_greg = :descman";

                    }
                    else {
                        $sqli .= ", description_manuel_greg = :descman";
                    }
                    $data['descman'] = $descman;
                }

                if ($metadescman == 1 or $metadescman == 0) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " meta_desc_manuel_greg = :metadescman";

                    }
                    else {
                        $sqli .= ", meta_desc_manuel_greg = :metadescman";
                    }
                    $data['metadescman'] = $metadescman;
                }

                if ($metakwman == 1 or $metakwman == 0) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " meta_kw_manuel_greg = :metakwman";

                    }
                    else {
                        $sqli .= ", meta_kw_manuel_greg = :metakwman";
                    }
                    $data['metakwman'] = $metakwman;
                }

                if ($metatitleman == 1 or $metatitleman == 0) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " meta_title_manuel_greg = :metatitleman";

                    }
                    else {
                        $sqli .= ", meta_title_manuel_greg = :metatitleman";
                    }
                    $data['metatitleman'] = $metatitleman;
                }



                if (!empty($remp2)) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " meta_desc_greg = :metadesc";

                    }
                    else {
                        $sqli .= ", meta_desc_greg = :metadesc";
                    }
                    $data['metadesc'] = $remp2;
                }

                if (!empty($remp3)) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " meta_kw_greg = :kw";
                    }
                    else {
                        $sqli .= ", meta_kw_greg = :kw";
                    }
                    $data['kw'] = $remp3;
                }

                if (!empty($remp4)) {
                    if ($sqli == "UPDATE grande_region SET") {
                        $sqli .= " meta_title_greg = :title";
                    }
                    else {
                        $sqli .= ", meta_title_greg = :title";
                    }
                    $data['title'] = $remp4;
                }


                $sqli .= " WHERE id_greg = :id";

                $stmt = $pdo->prepare($sqli)->execute($data);
            }
        
    $pdo->commit(); 
    
}


if ($i == 0) {
    $val = $_POST['dep'];
    

    try {

        $pdo->beginTransaction();
        $sql = 'SELECT id_greg, description_greg, nom_greg, description_manuel_greg,meta_desc_greg,meta_desc_manuel_greg,meta_kw_greg,meta_kw_manuel_greg,meta_title_greg,meta_title_manuel_greg FROM grande_region WHERE id_greg ="'.$val.'"' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);  
        $id = $resultat['id_greg'];
        $nomgreg = $resultat['nom_greg'];
        $desc = $resultat['description_greg'];
        $descman = $resultat['description_manuel_greg'];
        $metadesc = $resultat['meta_desc_greg'];
        $metadescman = $resultat['meta_desc_manuel_greg'];
        $metakw = $resultat['meta_kw_greg'];
        $metakwman = $resultat['meta_kw_manuel_greg'];
        $metatitle = $resultat['meta_title_greg'];
        $metatitleman = $resultat['meta_title_manuel_greg'];
        $pdo->commit(); 
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 
}

   
    





?>


    <form method = "POST" action = "modifmetaformgreg.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend >Modification meta et description de <?php echo $nomgreg; ?></legend>
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Liste des variables
                    </button>
                </p>
                <div class="collapse" id="collapseExample">
                    <div id="greg" class="card card-body">
                        <table  class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Nom de la région </td>
                                    <td>&#60;nomgreg&#62;</td>
                                    <td>Chef lieu de la région  </td>
                                    <td>&#60;cheflieugreg&#62;</td>
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



    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>








</body>
</html>
