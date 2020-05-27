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
    $id = $_POST["id"];
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
    
    if (empty($_POST['rewrite'])) {
        $rewrite = NULL;
    }
    else {
        $rewrite = $_POST['rewrite'];
    }

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
    
   ///////////////////////////////////////////

    if (isset($_POST['actif'])){
        $actif = 0;
    }
    else {
        $actif = 1;
    }
                
        // on crée le tableau $data qui va stocker nos informations utile pour le update
    $data = [
                            
                'id' => $id,
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
            ];

    // on rempli le début de la requete dans $sqli, ensuite on va tester pour chaque cas si le champ à été rempli,
    // si le champ n'a pas été rempli on ne fait rien sinon on ajoute le champ
    // qu'il nous faudra remplire et on y ajoute dans data les valeurs recupérer
        $sql = "UPDATE ville 
                SET nom_ville = :nom, code_insee_ville = :codeinsee, code_postal_ville = :codepostal, population_ville = :population, actif_ville = :actif, gentile_h_ville = :gentileh, gentile_f_ville = :gentilef, gentile_h_ville_impr1 = :gentilehimpr1, gentile_h_ville_impr2 = :gentilehimpr2, gentile_f_ville_impr1 = :gentilefimpr1, gentile_f_ville_impr2 = :gentilefimpr2, alias_ville = :alias, abreviation_ville = :abreviation, rewrite_ville = :rewrite, nom_ville_impr1 = :nomville1, nom_ville_impr2 = :nomville2, limitrophe_ville = :codeinseelim, derby_ville = :derby, geo_point_ville = :geopoint, geo_shape_ville = :geoshape
                WHERE id_ville = :id";
        


        // on ajoute les condition, si le champ est rempli la condition 
        // est d'ajouter uniquement pour ceux dont **_manuel_ville vaut nul

        // la requete dans $sqli est complete, ont la prépare puis l'éxecute avec les donnée 
        // du array $data
        $stmt = $pdo->prepare($sql)->execute($data);

        $sqld = 'SELECT GROUP_CONCAT(nom_ville) as ville_lim, GROUP_CONCAT(code_insee_ville) as codeinsee FROM ville WHERE code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,1,5) FROM ville WHERE id_ville ='.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,7,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,13,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,19,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,25,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,31,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,37,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,43,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,49,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,55,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,61,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,67,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,73,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,79,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,85,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,91,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,97,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,103,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,109,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,115,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,121,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,127,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,133,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,139,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,145,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,151,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,157,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,163,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,169,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,175,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,181,5) FROM ville WHERE id_ville = '.$id.')' ;
            // on prépare la requete sql dans $sqld pour récupérer le nom des limitrophe
            $sth = $pdo->prepare($sqld);
            // on éxécute la requete
            $sth->execute();
            // ont insert dans $resultat les valeurs récuperer
            $resultat = $sth->fetch(PDO::FETCH_ASSOC);
            // on rentre dans $lim la resultat que nous avons besoin
            $nomvillelim = $resultat['ville_lim'];
            $codeinseelim = $resultat['codeinsee'];
        $pdo->commit(); 
    
}


if ($i == 0) {
    $test = $_POST['city-hidden'];

    try {

        $pdo->beginTransaction();
        $sql = 'SELECT * 
                FROM ville 
                WHERE id_ville ="'.$test.'"' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);  
        $id = $resultat['id_ville'];
        $nom = $resultat['nom_ville'];
        $codeinsee = $resultat['code_insee_ville'];
        $codepostal = $resultat['code_postal_ville'];
        $population = $resultat['population_ville'];
        $actif = $resultat['actif_ville'];
        $gentileh = $resultat['gentile_h_ville'];
        $gentilef = $resultat['gentile_f_ville'];
        $gentilehimpr1 = $resultat['gentile_h_ville_impr1'];
        $gentilehimpr2 = $resultat['gentile_h_ville_impr2'];
        $gentilefimpr1 = $resultat['gentile_f_ville_impr1'];
        $gentilefimpr2 = $resultat['gentile_f_ville_impr2'];
        $alias = $resultat['alias_ville'];
        $abreviation = $resultat['abreviation_ville'];
        $rewrite = $resultat['rewrite_ville'];
        $nomville1 = $resultat['nom_ville_impr1'];
        $nomville2 = $resultat['nom_ville_impr2'];
        $derby = $resultat['derby_ville'];
        $geopoint = $resultat['geo_point_ville'];
        $geoshape = $resultat['geo_shape_ville'];

        $sqld = 'SELECT GROUP_CONCAT(nom_ville) as ville_lim, GROUP_CONCAT(code_insee_ville) as codeinsee FROM ville WHERE code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,1,5) FROM ville WHERE id_ville ='.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,7,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,13,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,19,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,25,5) FROM ville WHERE id_ville = '.$id.') 
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,31,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,37,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,43,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,49,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,55,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,61,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,67,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,73,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,79,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,85,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,91,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,97,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,103,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,109,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,115,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,121,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,127,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,133,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,139,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,145,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,151,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,157,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,163,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,169,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,175,5) FROM ville WHERE id_ville = '.$id.')
                            or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,181,5) FROM ville WHERE id_ville = '.$id.')' ;
            // on prépare la requete sql dans $sqld pour récupérer le nom des limitrophe
            $sth = $pdo->prepare($sqld);
            // on éxécute la requete
            $sth->execute();
            // ont insert dans $resultat les valeurs récuperer
            $resultat = $sth->fetch(PDO::FETCH_ASSOC);
            // on rentre dans $lim la resultat que nous avons besoin
            $nomvillelim = $resultat['ville_lim'];
            $codeinseelim = $resultat['codeinsee'];

        $pdo->commit(); 
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 
}

   
    





?>


    <form method = "POST" action = "modifvilleform.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-top: 2em;">
                <legend style="text-align: center;"><?php echo $nom ?></legend>
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
                                        <label>ID Ville</label>
                                        <input type="text" class="form-control" name="id" value="<?php echo $id; ?>" disabled="disabled">
                                    </div>
                                    <div class="col">
                                        <label>Nom de la ville</label>
                                        <input type="text" class="form-control" name="nom" value="<?php echo $nom; ?>" placeholder="Last name">
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <?php 
                                            if ($actif == 1){
                                                echo '<input type="checkbox" class="custom-control-input" id="customSwitch1" name="actif">';
                                            }
                                            else {
                                                echo '<input type="checkbox" class="custom-control-input" id="customSwitch1" name="actif" checked>';
                                            }
                                        ?>
                                        <label class="custom-control-label" for="customSwitch1">Ville Actif</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Code insee</label>
                                        <input type="text" class="form-control" name="codeinsee" 
                                        <?php
                                            if (empty($codeinsee)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$codeinsee.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                    <div class="col">
                                        <label>Code Postal</label>
                                        <input type="text" class="form-control" name="codepostal" 
                                        <?php
                                            if (empty($codepostal)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$codepostal.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                    <div class="col">
                                        <label>Population</label>
                                        <input type="text" class="form-control" name="population"
                                        <?php
                                            if (empty($population)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$population.'"';
                                            }
                                        ?>
                                        >
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
                                        <input type="text" class="form-control" name="gentileh"
                                        <?php
                                            if (empty($gentileh)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$gentileh.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                    <div class="col">
                                        <label>Gentilé femme</label>
                                        <input type="text" class="form-control" name="gentilef"
                                        <?php
                                            if (empty($gentilef)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$gentilef.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Gentilé homme ligne 1</label>
                                        <input type="text" class="form-control" name="gentilehimpr1"
                                        <?php
                                            if (empty($gentilehimpr1)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$gentilehimpr1.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                    <div class="col">
                                        <label>Gentilé femme ligne 1</label>
                                        <input type="text" class="form-control" name="gentilefimpr1"
                                        <?php
                                            if (empty($gentilefimpr1)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$gentilefimpr1.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Gentilé homme ligne 2</label>
                                        <input type="text" class="form-control" name="gentilehimpr2"
                                        <?php
                                            if (empty($gentilehimpr2)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$gentilehimpr2.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                    <div class="col">
                                        <label>Gentilé femme ligne 2</label>
                                        <input type="text" class="form-control" name="gentilefimpr2"
                                        <?php
                                            if (empty($gentilefimpr2)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$gentilefimpr2.'"';
                                            }
                                        ?>
                                        >
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
                                    <input type="text" class="form-control" name="alias"
                                    <?php
                                        if (empty($alias)){
                                            echo 'placeholder="Null"';
                                        }
                                        else {
                                            echo 'value="'.$alias.'"';
                                        }
                                    ?> 
                                    >
                                </div>
                                <div class="col">
                                    <label>Abréviation</label>
                                    <input type="text" class="form-control" name="abreviation" 
                                    <?php
                                        if (empty($abreviation)){
                                            echo 'placeholder="Null"';
                                        }
                                        else {
                                            echo 'value="'.$abreviation.'"';
                                        }
                                    ?>
                                    >
                                </div>
                                <div class="col">
                                    <label>Rewrite</label>
                                    <input type="text" class="form-control" name="rewrite"
                                    <?php
                                        if (empty($rewrite)){
                                            echo 'placeholder="Null"';
                                        }
                                        else {
                                            echo 'value="'.$rewrite.'"';
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label>Nom de la ville ligne 1</label>
                                    <input type="text" class="form-control" name="nomville1"
                                    <?php
                                        if (empty($nomville1)){
                                            echo 'placeholder="Null"';
                                        }
                                        else {
                                            echo 'value="'.$nomville1.'"';
                                        }
                                    ?>>
                                </div>
                                <div class="col">
                                    <label>Nom de la ville ligne 2</label>
                                    <input type="text" class="form-control" name="nomville2"
                                    <?php
                                        if (empty($nomville2)){
                                            echo 'placeholder="Null"';
                                        }
                                        else {
                                            echo 'value="'.$nomville2.'"';
                                        }
                                    ?>
                                    >
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

                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="codeinseelim" 
                                        <?php
                                                if (empty($codeinseelim)){
                                                    echo 'placeholder="Null"';
                                                }
                                        ?>
                                        ><?php
                                                if (!empty($codeinseelim)){
                                                    echo $codeinseelim;
                                                }
                                        ?></textarea>
                                    </div>
                                    <div class="col">
                                        <label>Nom des villes limitrophes</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" disabled="disabled"
                                        <?php
                                                if (empty($nomvillelim)){
                                                    echo 'placeholder="Null"';
                                                }
                                        ?>
                                        ><?php
                                                if (!empty($nomvillelim)){
                                                    echo $nomvillelim;
                                                }
                                        ?></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label>Derby</label>
                                        <input type="text" class="form-control" name="derby"
                                        <?php
                                            if (empty($derby)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$derby.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                    <div class="col">
                                        <label>Géo point</label>
                                        <input type="text" class="form-control" name="geopoint" 
                                        <?php
                                            if (empty($geopoint)){
                                                echo 'placeholder="Null"';
                                            }
                                            else {
                                                echo 'value="'.$geopoint.'"';
                                            }
                                        ?>
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col">
                                        <label>Géo Shape</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="geoshape"
                                        <?php
                                                if (empty($geoshape)){
                                                    echo 'placeholder="Null"';
                                                }
                                        ?>
                                        ><?php
                                                if (!empty($geoshape)){
                                                    echo $geoshape;
                                                }
                                        ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                
            </fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                 
                <input type="text" name="te" value="1" hidden>
                <input type="text" name="id" value="<?php echo $id; ?>" hidden>
                         
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
