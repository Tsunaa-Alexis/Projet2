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

function rewrite ($nomville) {
    $nomvillepetit = trim($nomville);
    $nomvillepetit = preg_replace('#Ç#', 'C', $nomvillepetit);
    $nomvillepetit = preg_replace('#ç#', 'c', $nomvillepetit);
    $nomvillepetit = preg_replace('#è|é|ê|ë#', 'e', $nomvillepetit);
    $nomvillepetit = preg_replace('#È|É|Ê|Ë#', 'E', $nomvillepetit);
    $nomvillepetit = preg_replace('#à|á|â|ã|ä|å#', 'a', $nomvillepetit);
    $nomvillepetit = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $nomvillepetit);
    $nomvillepetit = preg_replace('#ì|í|î|ï#', 'i', $nomvillepetit);
    $nomvillepetit = preg_replace('#Ì|Í|Î|Ï#', 'I', $nomvillepetit);
    $nomvillepetit = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $nomvillepetit);
    $nomvillepetit = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $nomvillepetit);
    $nomvillepetit = preg_replace('#ù|ú|û|ü#', 'u', $nomvillepetit);
    $nomvillepetit = preg_replace('#Ù|Ú|Û|Ü#', 'U', $nomvillepetit);
    $nomvillepetit = preg_replace('#ý|ÿ#', 'y', $nomvillepetit);
    $nomvillepetit = preg_replace('#Ý#', 'Y', $nomvillepetit);
    $nomvillepetit = preg_replace("#'#", "-", $nomvillepetit);
    $nomvillepetit = preg_replace("# #", "-", $nomvillepetit);
    $nomvillepetit = strtolower($nomvillepetit);

    return $nomvillepetit;
}




if (isset($_POST['insert'])) {

    $information = $_POST['customRadioInline1'];
    if (empty($_POST['min'])) {
        $min = 1;
    }
    else {
        $min = $_POST['min'];
    }

    if (empty($_POST['max'])) {
        $max = 500;
    }
    else {
        $max = $_POST['max'];
    }

    

    if ($_POST['customRadioInline1'] = "gentile_h_ville"){
        $info1 = "gentile_h_ville_impr1";
        $info2 = "gentile_h_ville_impr2";
    }

    elseif ($_POST['customRadioInline1'] = "gentile_f_ville"){
        $info1 = "gentile_f_ville_impr1";
        $info2 = "gentile_f_ville_impr2";
    }

    elseif ($_POST['customRadioInline1'] = "nom_ville"){
        $info1 = "nom_ville_impr1";
        $info2 = "nom_ville_impr2";
    }





    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } 
    catch (Exception $e) {
        die("Impossible de se connecter : " . $e->getMessage());
    }
    // Insertion des données dans la BDD
    try {
        $date = date("Y-m-d-H-i");
        $filename = $date.'-'.$information.'-'.$min.'-'.$max.'.csv';

        $fp = fopen('CSV/'.$filename, 'w');
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        $pdo->beginTransaction();
        $sql = 'SELECT '.$information.','.$info1.','.$info2.' FROM ville WHERE CHAR_LENGTH('.$information.') BETWEEN '.$min.' and '.$max;
        $genti = array('rewrite',$info1, $info2, 'nomcaractère');
        fputcsv($fp,$genti);
        foreach  ($pdo->query($sql) as $row) {
            $rewrite = rewrite($row[$information]);
            $gentile = $row[$information];
            $taille = mb_strlen($row[$information]);
            if (stristr($row[$information],'-') === FALSE and stristr($row[$information],' ') === FALSE and stristr($row[$information],',') === FALSE) {
                $array = array($rewrite,$row[$info1],$row[$info2],$taille);
            }
            else {
                
                $array = array($rewrite,$row[$info1],$row[$info2]);
            }
            
            if (!empty($gentile)){
                fputcsv($fp, $array);
            }

        }
        fclose($fp);
        $pdo->commit();
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 
    
?>
<a  style="margin-left : 50%; margin-top:10px ;"href="CSV/<?php echo $filename; ?>" download="CSV/<?php echo $filename; ?>" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-download"></i></a>

<?php

}


?>



    <form method = "POST" action = "CSVIMPR.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend >Création d'un fichier CSV</legend>

                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline1" name="customRadioInline1" value="gentile_h_ville" class="custom-control-input" required="">
                    <label class="custom-control-label" for="customRadioInline1">Gentile Homme</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline2" name="customRadioInline1" value="gentile_f_ville"class="custom-control-input" required>
                    <label class="custom-control-label" for="customRadioInline2">Gentile Femme</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline3" name="customRadioInline1" value="nom_ville" class="custom-control-input" required>
                    <label class="custom-control-label" for="customRadioInline3">Nom de ville</label>
                </div>

                <div class="row" style="padding-top : 15px;">
                    <div class="col">
                        <label for="formGroupExampleInput">Caractère min</label>
                        <input type="text" name='min' class="form-control" placeholder="Ex : 10">
                    </div>
                    <div class="col">
                        <label for="formGroupExampleInput">Caractère max</label>
                        <input type="text" name='max' class="form-control" placeholder="Ex : 50">
                    </div>
                </div>
            </fieldset>
            <fieldset style="padding-left: 50%;">
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-4">
                        <button type="submit" name="insert"class="btn btn-primary" ><i class="far fa-save"></i> <span class="glyphicon glyphicon-send"></span></button>
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
