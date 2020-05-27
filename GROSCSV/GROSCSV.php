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

// fonction pour le rewrite, enlève tous les accents, 
//remplace les espaces par des tiret, met tout en minuscule
function catinseeville ($inseeville) {
    $inseeville = str_replace('A', '44', $inseeville);
    $inseeville = str_replace('B', '88', $inseeville);
    $inseeville = '1'.$inseeville;

    return $inseeville;
}

function catinseedep ($inseedep) {
    $inseedep = str_replace('A', '44', $inseedep);
    $inseedep = str_replace('B', '88', $inseedep);
    $inseedep = '2000'.$inseedep;

    return $inseedep;
}

function catinseegreg ($inseegreg) {
    $inseegreg = str_replace('A', '44', $inseegreg);
    $inseegreg = str_replace('B', '88', $inseegreg);
    $inseegreg = '4000'.$inseegreg;

    return $inseegreg;
}

function catinseereg ($inseereg) {
    $inseereg = str_replace('A', '44', $inseereg);
    $inseereg = str_replace('B', '88', $inseereg);
    $inseereg = '3000'.$inseereg;

    return $inseereg;
}

function limv ($lim) {
    $e = explode(',', $lim);
    for ($i=0; $i < count($e) ; $i++) { 
        if ($i == 0) {
            $a = '1'.$e[$i];
        }
        else {
            $a .= ',1'.$e[$i];
        }
                            
    }

    return $a;

}

function limd ($lim) {
    $e = explode(',', $lim);
    for ($i=0; $i < count($e) ; $i++) { 
        if ($i == 0) {
            $a = '2000'.$e[$i];
        }
        else {
            $a .= ',2000'.$e[$i];
        }
                            
    }

    return $a;

}

// Connextion à la BDD avc PDO
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (Exception $e) {
    die("Impossible de se connecter : " . $e->getMessage());
}

// Si le on reçoit 'insert' lance le script
if (isset($_POST['insert'])) {

        try {
            //Création du nom du fichier en fonction de la date,de l'information voulue et le nombre de
            //caractères
            $date = date("Y-m-d-H-i");
            $filename = $date.'.csv';
            //on ouvre le fichier CSV, 'w' permet de le crée s'il n'existe pas
            //s'il existe ont éfface tout et ont réécrit par dessus
            $fp = fopen('CSV/'.$filename, 'w');
            //initialise le ficher en utf8
            fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            $pdo->beginTransaction();
            $sql = 'SELECT code_insee_ville, nom_ville, id_dep, rewrite_ville, description_ville, meta_title_ville, meta_kw_ville, meta_desc_ville, code_postal_ville, population_ville, alias_ville, abreviation_ville, gentile_h_ville, gentile_f_ville, gentile_h_ville_impr1, gentile_h_ville_impr2, gentile_f_ville_impr1, gentile_f_ville_impr2, nom_ville_impr1, nom_ville_impr2, limitrophe_ville
                    FROM ville 
                    where actif_ville = 0 or actif_ville is null' ;
            //création d'un array avec le nom des collones
            $genti = array('Category ID','Name*','Parent category','URL rewritten','Description(max 1000)','Meta title(max70)','Meta keywords(max 5 mots séparés par une virgule sans espaces)','Meta description(max 160)','Postal code(chiffres ou lettres)','Population','Alias(max 70)','Abréviation','Gentile H(max 70)','Gentile F(max 70)','Gentilé H impression ligne 1','Gentilé H impression ligne 2','Gentilé F impression ligne 1','Gentilé F impression ligne 2','Nom ville impression ligne 1', 'Nom ville impression ligne 2','Villes limitrophes(category ID','Derby');
            //on écrit dans le fichier CSV les donnée du array
            fputcsv($fp,$genti);
            //on parcourt toutes les information de la requete %sql
            foreach  ($pdo->query($sql) as $row) {
                if (!empty($row['code_insee_ville'])){

                    if (!empty($row['limitrophe_ville'])) {
                        $lim = limv($row['limitrophe_ville']);

                    }
                    else {
                        $lim = '';
                    }
                    
                    $catid = catinseeville($row['code_insee_ville']);
                    $sqli = 'SELECT code_insee_dep 
                            FROM departement 
                            WHERE id_dep ='.$row['id_dep'] ;
                    $sth = $pdo->prepare($sqli);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    $parentcat = catinseedep($resultat['code_insee_dep']);
                    $array = array($catid,$row["nom_ville"],$parentcat,$row['rewrite_ville'],$row['description_ville'],$row['meta_title_ville'],$row['meta_kw_ville'],$row['meta_desc_ville'],$row['code_postal_ville'],$row['population_ville'],$row['alias_ville'],$row['abreviation_ville'],$row['gentile_h_ville'],$row['gentile_f_ville'],$row['gentile_h_ville_impr1'],$row['gentile_h_ville_impr2'],$row['gentile_f_ville_impr1'],$row['gentile_f_ville_impr2'],$row['nom_ville_impr1'],$row['nom_ville_impr2'],$lim);
                    fputcsv($fp, $array);
                }
                
                //prend la valeur du nombre de caractère stocker dans $information
                // toutes les information à écrire dans le fichier csv sont stocker dans un array
                
                //si $gentile n'est pas vide ont écrit dans le ficher csv
                //Cela permet d'éviter des lignes vide dans le cas où il n'y 
                //a pas de gentile homme ou femme
            }

            $sqld = 'SELECT code_insee_dep, nom_dep, id_greg, rewrite_dep, description_dep, meta_title_dep, meta_kw_dep, meta_desc_dep, num_dep, alias_dep, abreviation_dep, gentile_h_dep, gentile_f_dep, gentile_h_dep_impr1, gentile_h_dep_impr2, gentile_f_dep_impr1, gentile_f_dep_impr2, nom_dep_impr1, nom_dep_impr2, limitrophe_dep
                    FROM departement
                    WHERE actif_dep = 0 or actif_dep is null' ;
            foreach  ($pdo->query($sqld) as $row) {
                if (!empty($row['code_insee_dep'])){
                    $catid = catinseedep($row['code_insee_dep']);

                    $sqli = 'SELECT code_insee_greg 
                            FROM grande_region 
                            WHERE id_greg ='.$row['id_greg'] ;
                    $sth = $pdo->prepare($sqli);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    $parentcat = catinseegreg($resultat['code_insee_greg']);
                    if (!empty($row['limitrophe_dep'])) {
                        $lim = limd($row['limitrophe_dep']);
                    }

                    $array = array($catid,$row['nom_dep'],$parentcat,$row['rewrite_dep'],$row['description_dep'],$row['meta_title_dep'],$row['meta_kw_dep'],$row['meta_desc_dep'],$row['num_dep'],'',$row['alias_dep'],$row['abreviation_dep'],$row['gentile_h_dep'],$row['gentile_f_dep'],$row['gentile_h_dep_impr1'],$row['gentile_h_dep_impr2'],$row['gentile_f_dep_impr1'],$row['gentile_f_dep_impr2'],$row['nom_dep_impr1'],$row['nom_dep_impr2'],$lim);
                    fputcsv($fp, $array);
                }

            }

            $sqld = 'SELECT code_insee_reg, nom_reg, id_greg_reg, rewrite_reg, description_reg, meta_title_reg, meta_kw_reg, meta_desc_reg, alias_reg, abreviation_reg, nom_reg_impr1, nom_reg_impr2
                    FROM region_ancienne
                    WHERE actif_reg = 0 or actif_reg is null' ;
            foreach  ($pdo->query($sqld) as $row) {
                if (!empty($row['code_insee_reg'])){
                    $catid = catinseereg($row['code_insee_reg']);

                    $sqli = 'SELECT code_insee_greg 
                            FROM grande_region 
                            WHERE id_greg ='.$row['id_greg_reg'] ;
                    $sth = $pdo->prepare($sqli);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    $parentcat = catinseegreg($resultat['code_insee_greg']);

                    $array = array($catid,$row['nom_reg'],$parentcat,$row['rewrite_reg'],$row['description_reg'],$row['meta_title_reg'],$row['meta_kw_reg'],$row['meta_desc_reg'],'','',$row['alias_reg'],$row['abreviation_reg'],'','','','','','',$row['nom_reg_impr1'],$row['nom_reg_impr2']);
                    fputcsv($fp, $array);
                }
            }

            $sqld = 'SELECT code_insee_greg, nom_greg, rewrite_greg, description_greg, meta_title_greg, meta_kw_greg, meta_desc_greg, alias_greg, abreviation_greg, nom_greg_impr1, nom_greg_impr2
                    FROM grande_region
                    WHERE actif_greg = 0 or actif_greg is null' ;
            foreach  ($pdo->query($sqld) as $row) {
                if (!empty($row['code_insee_greg'])){
                    $catid = catinseegreg($row['code_insee_greg']);

                    $parentcat = 2;

                    $array = array($catid,$row['nom_greg'],$parentcat,$row['rewrite_greg'],$row['description_greg'],$row['meta_title_greg'],$row['meta_kw_greg'],$row['meta_desc_greg'],'','',$row['alias_greg'],$row['abreviation_greg'],'','','','','','',$row['nom_greg_impr1'],$row['nom_greg_impr2']);
                    fputcsv($fp, $array);
                }
            }
            //on ferme le fichier csv
            fclose($fp);
            $pdo->commit();
        }
        catch (Exception $e) {
            $pdo->rollback();
            echo('Erreur : ' . $e->getMessage());
        } 


    
    
    
?>

<?php

}


?>



    <form method = "POST" action = "GROSCSV.php" enctype="multipart/form-data" >
            <fieldset style="text-align: center;">
                <legend>Génération du CSV final</legend>
            </fieldset>
            <fieldset style="padding-left: 45%;">
                <?php 
                if (isset($_POST['insert'])) {
                    echo '<a  title="Télécharger le fichier .CSV" href="CSV/'.$filename.'" download="CSV/'.$filename.'" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-download"></i></a>';
                }
                ?>

                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-4">
                        <button title="Lancer la création du fichier .CSV" type="submit" name="insert"class="btn btn-primary" ><i class="far fa-save"></i> <span class="glyphicon glyphicon-send"></span></button>
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
