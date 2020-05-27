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


try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (Exception $e) {
    die("Impossible de se connecter : " . $e->getMessage());
}

$i=0;



if (isset($_POST['insert'])) {
    $i = $_POST['te'];
    if (empty($_POST['gentileh'])){
        $gentileh = NULL;
    }
    else{
        $gentileh = $_POST['gentileh'];
    }
    
    if (empty($_POST['gentilef'])){
        $gentilef = NULL;
    }
    else{
        $gentilef = $_POST['gentilef'];
    }

    $id1 = $_POST['id'];

    $data = [
                'gentileh' => $gentileh,
                'gentilef' => $gentilef,
                'id' => $id1,
            ];
    $sql = "UPDATE ville
            SET gentile_h_ville = :gentileh, gentile_f_ville = :gentilef, gentilemodif = 1
            WHERE id_ville = :id";
    $stmt = $pdo->prepare($sql)->execute($data);

    $sql = 'SELECT id_ville,nom_ville, gentile_h_ville, gentile_f_ville FROM ville WHERE id_ville ='.$id1 ;
        $std = $pdo->prepare($sql);
        $std->execute();
        $resultat = $std->fetch(PDO::FETCH_ASSOC);
        
        $gentileh = $resultat['gentile_h_ville'];
        $gentilef = $resultat['gentile_f_ville'];
        $id1 = $resultat['id_ville'];
?>

<form method = "POST" action = "modifgentilechiant.php" enctype="multipart/form-data" >
    <fieldset>
        <fieldset style="padding-left: 10%; padding-top: 2em;">
            <legend ><?php echo $resultat['nom_ville']; ?></legend>
            <a href="modifgentilechiant.php">🏠💩</a>             
            <div class="col">
                <label for="formGroupExampleInput">Gentile Homme</label>
                <input type="text" name='gentileh' class="form-control" value="<?php echo $gentileh; ?>">
            </div>
            <div class="col" style="margin-top: 20px;">
                <label for="formGroupExampleInput">Gentile Femme</label>
                <input type="text" name='gentilef' class="form-control" 
                <?php if (empty($gentilef)){
                            echo "placeholder='Null'";
                        }
                        else {
                            echo "value='".$gentilef."'"; 
                        }?>>
                <input type="text" name="te" value="1" hidden>
                <input type="text" name="id" value="<?php echo $id1 ?>" hidden>                   
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-4">
                    <button style="padding-left: 50%; padding-right: 50%;" type="submit" name="insert"class="btn btn-primary" ><i class="far fa-save"></i> <span class="glyphicon glyphicon-send"></span></button>
                </div>
            </div>
        </fieldset>
    </fieldset>
</form>


<?php 

}

if (isset($_POST['next'])) {
    header('Refresh: 1;URL=modifgentilechiant.php');
}

if ($i == 0) {

    try {
        $pdo->beginTransaction();
        $sql = 'SELECT id_ville,nom_ville, gentile_h_ville, gentile_f_ville FROM ville WHERE gentilemodif=2 and gentile_h_ville is not null LIMIT 1' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);
        $pdo->commit();
        $gentileh = $resultat['gentile_h_ville'];
        $gentilef = $resultat['gentile_f_ville'];
        $id1 = $resultat['id_ville'];
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 

?>



    <form method = "POST" action = "modifgentilechiant.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend ><?php echo $resultat['nom_ville']; ?></legend>
                <a href="modifgentile.php">🏠💩</a>

                
                    <div class="col">
                        <label for="formGroupExampleInput">Gentile Homme</label>
                        <input type="text" name='gentileh' class="form-control" value="<?php echo $gentileh; ?>">
                    </div>
                    <div class="col" style="margin-top: 20px;">
                        <label for="formGroupExampleInput">Gentile Femme</label>
                        <input type="text" name='gentilef' class="form-control" 
                        <?php if (empty($gentilef)){
                                    echo "placeholder='Null'";
                                }
                                else {
                                    echo "value='".$gentilef."'"; 
                                }?>>
                        <input type="text" name="te" value="1" hidden>
                        <input type="text" name="id" value="<?php echo $id1 ?>" hidden>
                                
                    </div>
 
            </fieldset>
            <fieldset>
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-4">
                        <button style="padding-left: 50%; padding-right: 50%;" type="submit" name="insert"class="btn btn-primary" ><i class="far fa-save"></i> <span class="glyphicon glyphicon-send"></span></button>
                    </div>
                </div>

            </fieldset>
        </fieldset>
    </form>

<?php
}

?>

    <form method = "POST" action = "modifgentilechiant.php" enctype="multipart/form-data" >
        <fieldset >
            <div class="form-group">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-4">
                    <button style="padding-left: 50%; padding-right: 50%;" type="submit" name="next" class="btn btn-primary" ><i class="fas fa-arrow-right"></i> <span class="glyphicon glyphicon-send"></span></button>
                </div>
                </div>
        </fieldset>
    </form>





    


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


</body>
</html>
