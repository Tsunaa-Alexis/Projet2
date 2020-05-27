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




// Quand on a valider le formulaire le script se lance
if (isset($_POST['insert'])) {
    $pdo->beginTransaction();
    //on récupère toutes les information du formulaire
    $nom = $_POST["nom"];

///////////////////////////////////////////

    if (empty($_POST['codeinsee'])) {
        $codeinsee = NULL;
    }
    else {
        $codeinsee = $_POST["codeinsee"];
    }

    ///////////////////////////////////////////

    if (empty($_POST['codepostal'])) {
        $codepostal = NULL;
    }
    else {
        $codepostal = $_POST["codepostal"];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['population'])) {
        $population = NULL;
    }
    else {
        $population = $_POST["population"];
    }

    ///////////////////////////////////////////

    if (empty($_POST['gentileh'])) {
        $gentileh = NULL;
    }
    else {
        $gentileh = $_POST['gentileh'];
    }

    ///////////////////////////////////////////

    if (empty($_POST['gentilef'])) {
        $gentilef = NULL;
    }
    else {
        $gentilef = $_POST['gentilef'];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['gentilehimpr1'])) {
        $gentilehimpr1 = NULL;
    }
    else {
        $gentilehimpr1 = $_POST['gentilehimpr1'];
    }

    ///////////////////////////////////////////

    if (empty($_POST['gentilehimpr2'])) {
        $gentilehimpr2 = NULL;
    }
    else {
        $gentilehimpr2 = $_POST['gentilehimpr2'];
    }

    ///////////////////////////////////////////

    if (empty($_POST['gentilefimpr1'])) {
        $gentilefimpr1 = NULL;
    }
    else {
        $gentilefimpr1 = $_POST['gentilefimpr1'];
    }

    ///////////////////////////////////////////

    if (empty($_POST['gentilefimpr2'])) {
        $gentilefimpr2 = NULL;
    }
    else {
        $gentilefimpr2 = $_POST['gentilefimpr2'];
    }

    ///////////////////////////////////////////
    
    
    if (empty($_POST['alias'])) {
        $alias = NULL;
    }
    else {
        $alias = $_POST['alias'];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['abreviation'])) {
        $abreviation = NULL;
    }
    else {
        $abreviation = $_POST['abreviation'];
    }

    ///////////////////////////////////////////
    
    $rewrite = rewrite($nom);

    ///////////////////////////////////////////
    
    if (empty($_POST['nomville1'])) {
        $nomville1 = NULL;
    }
    else {
        $nomville1 = $_POST['nomville1'];
    }
    
    ///////////////////////////////////////////
    
    if (empty($_POST['nomville2'])) {
        $nomville2 = NULL;
    }
    else {
        $nomville2 = $_POST['nomville2'];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['codeinseelim'])) {
        $codeinseelim = NULL;
    }
    else {
        $codeinseelim = $_POST['codeinseelim'];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['derby'])) {
        $derby = NULL;
    }
    else {
        $derby = $_POST['derby'];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['geopoint'])) {
        $geopoint = NULL;
    }
    else {
        $geopoint = $_POST['geopoint'];
    }

    ///////////////////////////////////////////
    
    if (empty($_POST['geoshape'])) {
        $geoshape = NULL;
    }
    else {
        $geoshape = $_POST['geoshape'];
    }
    
    //////////////////////////////////////////


    $dep = $_POST['dep'];


    if (isset($_POST['actif'])){
        $actif = 0;
    }
    else {
        $actif = 1;
    }
                
        // on crée le tableau $data qui va stocker nos informations utile pour le update
    $data = [
                            
                'nom' => $nom,
                'codeinsee' => $codeinsee,
                'codepostal' => $codepostal,
                'population' => $population,
                'actif' => $actif,
                'gentileh' => $gentileh,
                'gentilef' => $gentilef,
                'gentilehimpr1' => $gentilehimpr1,
                'gentilehimpr2' => $gentilehimpr2,
                'gentilefimpr1' => $gentilefimpr1,
                'gentilefimpr2' => $gentilefimpr2,
                'alias' => $alias,
                'abreviation' => $abreviation,
                'rewrite' => $rewrite,
                'nomville1' => $nomville1,
                'nomville2' => $nomville2,
                'codeinseelim' => $codeinseelim,
                'derby' => $derby,
                'geopoint' => $geopoint,
                'geoshape' => $geoshape,
                'dep' => $dep,                          
            ];

    // on rempli le début de la requete dans $sqli, ensuite on va tester pour chaque cas si le champ à été rempli,
    // si le champ n'a pas été rempli on ne fait rien sinon on ajoute le champ
    // qu'il nous faudra remplire et on y ajoute dans data les valeurs recupérer
        $sql = "INSERT INTO ville (nom_ville, code_insee_ville, code_postal_ville, population_ville, actif_ville, gentile_h_ville, gentile_f_ville, gentile_h_ville_impr1, gentile_h_ville_impr2, gentile_f_ville_impr1, gentile_f_ville_impr2, alias_ville, abreviation_ville, rewrite_ville, nom_ville_impr1, nom_ville_impr2, limitrophe_ville, derby_ville, geo_point_ville, geo_shape_ville,id_dep) 
                VALUES (:nom, :codeinsee, :codepostal, :population, :actif, :gentileh, :gentilef, :gentilehimpr1, :gentilehimpr2,  :gentilefimpr1, :gentilefimpr2, :alias, :abreviation, :rewrite, :nomville1, :nomville2, :codeinseelim, :derby, :geopoint, :geoshape, :dep)";
        


        // on ajoute les condition, si le champ est rempli la condition 
        // est d'ajouter uniquement pour ceux dont **_manuel_ville vaut nul

        // la requete dans $sqli est complete, ont la prépare puis l'éxecute avec les donnée 
        // du array $data
        $stmt = $pdo->prepare($sql)->execute($data);

        $pdo->commit(); 
    
}

?>


    <form method = "POST" action = "creationville.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-top: 2em;">
                <legend style="text-align: center;">Création d'une ville</legend>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" onclick="less1()" id="col11" aria-controls="collapseOne">
                                    <i class="fas fa-minus-square"></i> Informations sur la ville 
                                </button>
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"  onclick="more1()" id="col12" aria-controls="collapseOne" style="display: none;">
                                    <i class="fas fa-plus-square"></i> Informations sur la ville
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <label>Nom de la ville</label>
                                        <input type="text" class="form-control" name="nom" required>
                                    </div>
                                    <div class="col">
                                        <label>Code insee</label>
                                        <input type="text" class="form-control" name="codeinsee" required>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" name="actif">
                                        <label class="custom-control-label" for="customSwitch1">Ville Actif</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                    <label for="inputState">Département</label>
                                        <select id="inputState" name="dep" class="form-control" required>
                                            <option selected disabled>Choix du département</option>
                                            <?php 
                                            $pdo->beginTransaction();
                                            $sql = 'SELECT nom_dep, id_dep, num_dep
                                                        FROM departement';

                                                // on parcours les résultat un à un pour donner un résultat adapter pour chaque ville récupérer
                                                foreach  ($pdo->query($sql) as $row) {
                                                    echo "<option  value='".$row['id_dep']."'>".$row['nom_dep']." (".$row['num_dep'].")</option>";
                                                }
                                            $pdo->commit();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Code Postal</label>
                                        <input type="text" class="form-control" name="codepostal">
                                    </div>
                                    <div class="col">
                                        <label>Population</label>
                                        <input type="text" class="form-control" name="population">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



            <!-- //////////////////////////////////////////////////////////////////////////////-->  


                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" style="display: none;" onclick="less2()" id="col21" aria-controls="collapseTwo">
                                    <i class="fas fa-minus-square"></i> Gentilés 
                                </button>
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"  onclick="more2()" id="col22" aria-controls="collapseTwo" >
                                    <i class="fas fa-plus-square"></i> Gentilés
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <label>Gentilé homme</label>
                                        <input type="text" class="form-control" name="gentileh">
                                    </div>
                                    <div class="col">
                                        <label>Gentilé femme</label>
                                        <input type="text" class="form-control" name="gentilef">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Gentilé homme ligne 1</label>
                                        <input type="text" class="form-control" name="gentilehimpr1">
                                    </div>
                                    <div class="col">
                                        <label>Gentilé femme ligne 1</label>
                                        <input type="text" class="form-control" name="gentilefimpr1">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Gentilé homme ligne 2</label>
                                        <input type="text" class="form-control" name="gentilehimpr2">
                                    </div>
                                    <div class="col">
                                        <label>Gentilé femme ligne 2</label>
                                        <input type="text" class="form-control" name="gentilefimpr2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- //////////////////////////////////////////////////////////////////////////////--> 


                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" style="display: none;" onclick="less3()" id="col31" aria-controls="collapseThree">
                                    <i class="fas fa-minus-square"></i> Réécriture du nom de la ville 
                                </button>
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true"  onclick="more3()" id="col32" aria-controls="collapseThree" >
                                    <i class="fas fa-plus-square"></i> Réécriture du nom de la ville
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="form-row">
                                <div class="col">
                                    <label>Alias</label>
                                    <input type="text" class="form-control" name="alias">
                                </div>
                                <div class="col">
                                    <label>Abréviation</label>
                                    <input type="text" class="form-control" name="abreviation" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label>Nom de la ville ligne 1</label>
                                    <input type="text" class="form-control" name="nomville1" required>
                                </div>
                                <div class="col">
                                    <label>Nom de la ville ligne 2</label>
                                    <input type="text" class="form-control" name="nomville2">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- //////////////////////////////////////////////////////////////////////////////--> 


                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" style="display: none;" onclick="less4()" id="col41" aria-controls="collapseFour">
                                    <i class="fas fa-minus-square"></i> Informations externe à la ville 
                                </button>
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"  onclick="more4()" id="col42" aria-controls="collapseFour" >
                                    <i class="fas fa-plus-square"></i> Informations externe à la ville
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <label>Code insee des villes limitrophes</label>

                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="codeinseelim"></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Derby</label>
                                        <input type="text" class="form-control" name="derby">
                                    </div>
                                    <div class="col">
                                        <label>Géo point</label>
                                        <input type="text" class="form-control" name="geopoint" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col">
                                        <label>Géo Shape</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="geoshape"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                
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
    <script type="text/javascript">
        function less1() {

            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';

        }
        function more1(){
            document.getElementById('col11').style.display='block';
            document.getElementById('col12').style.display='none';
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';

        }

        function less2() {
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';
        }
        function more2(){
            document.getElementById('col21').style.display='block';
            document.getElementById('col22').style.display='none';
            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';
        }

        function less3() {
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';
        }
        function more3(){
            document.getElementById('col31').style.display='block';
            document.getElementById('col32').style.display='none';
            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';
        }

        function less4() {
            document.getElementById('col41').style.display='none';
            document.getElementById('col42').style.display='block';
            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
        }
        function more4(){
            document.getElementById('col41').style.display='block';
            document.getElementById('col42').style.display='none';
            document.getElementById('col12').style.display='block';
            document.getElementById('col11').style.display='none';
            document.getElementById('col21').style.display='none';
            document.getElementById('col22').style.display='block';
            document.getElementById('col31').style.display='none';
            document.getElementById('col32').style.display='block';
        }
        
    </script>









</body>
</html>
