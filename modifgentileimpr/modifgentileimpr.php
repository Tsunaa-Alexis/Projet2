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

$i=0;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (Exception $e) {
    die("Impossible de se connecter : " . $e->getMessage());
}


if (isset($_POST['insert'])) {

    $i = $_POST['te'];

    if (empty($_POST['gentileh1'])){
        $gentileh1 = NULL;
    }
    else{
        $gentileh1 = $_POST['gentileh1'];
    }

    if (empty($_POST['gentileh2'])){
        $gentileh2 = NULL;
    }
    else{
        $gentileh2 = $_POST['gentileh2'];
    }
    
    if (empty($_POST['gentilef1'])){
        $gentilef1 = NULL;
    }
    else{
        $gentilef1 = $_POST['gentilef1'];
    }

    if (empty($_POST['gentilef2'])){
        $gentilef2 = NULL;
    }
    else{
        $gentilef2 = $_POST['gentilef2'];
    }

    $id1 = $_POST['id'];

    $data = [
                'gentileh1' => $gentileh1,
                'gentileh2' => $gentileh2,
                'gentilef1' => $gentilef1,
                'gentilef2' => $gentilef2,
                'id' => $id1,
            ];
    $sql = "UPDATE ville
            SET gentile_h_ville_impr1 = :gentileh1, gentile_h_ville_impr2 = :gentileh2, gentile_f_ville_impr1 = :gentilef1, gentile_f_ville_impr2 = :gentilef2, modifgentileimpr = 1
            WHERE id_ville = :id";
    $stmt = $pdo->prepare($sql)->execute($data);


    $sql = 'SELECT id_ville,nom_ville, gentile_h_ville_impr1, gentile_h_ville_impr2, gentile_f_ville_impr1, gentile_f_ville_impr2 FROM ville WHERE id_ville ='.$id1 ;
        $std = $pdo->prepare($sql);
        $std->execute();
        $resultat = $std->fetch(PDO::FETCH_ASSOC);
        
        $gentileh1 = $resultat['gentile_h_ville_impr1'];
        $gentileh2 = $resultat['gentile_h_ville_impr2'];
        $gentilef1 = $resultat['gentile_f_ville_impr1'];
        $gentilef2 = $resultat['gentile_f_ville_impr2'];
        $id1 = $resultat['id_ville'];
?>

    <form method = "POST" action = "modifgentileimpr.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend ><?php echo $resultat['nom_ville']; ?></legend>
                <div class="col">
                    <label for="formGroupExampleInput">Gentile homme Ligne 1</label>
                    <p id="compteur"><?php echo mb_strlen($gentileh1)?> Caractère(s)</p>
                    <input type="text" id="message" onkeyup="afficher()" name='gentileh1' class="form-control" value="<?php echo $gentileh1; ?>">
                </div>
                <div class="col" style="margin-top: 20px;">
                    <label for="formGroupExampleInput">Gentile homme Ligne 2</label>
                    <p id="compteurs"><?php echo mb_strlen($gentileh2)?> Caractère(s)</p>
                    <input type="text" id="messages" onkeyup="afficher2()" name='gentileh2' class="form-control" 
                    <?php if (empty($gentileh2)){
                                echo "placeholder='Null'";
                            }
                            else {
                                echo "value='".$gentileh2."'"; 
                            }?>>
                                
                </div>
            </fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <div class="col">
                    <label for="formGroupExampleInput">Gentile femme Ligne 1</label>
                    <p id="compteur3"><?php echo mb_strlen($gentilef1)?> Caractère(s)</p>
                    <input type="text" id="message3" onkeyup="afficher3()" name='gentilef1' class="form-control" 
                    <?php if (empty($gentilef1)){
                                echo "placeholder='Null'";
                            }
                            else {
                                echo "value='".$gentilef1."'"; 
                            }?>>
                </div>
                <div class="col" style="margin-top: 20px;">
                    <label for="formGroupExampleInput">Gentile femme Ligne 2</label>
                    <p id="compteur4"><?php echo mb_strlen($gentilef2)?> Caractère(s)</p>
                    <input type="text" id="message4" onkeyup="afficher4()" name='gentilef2' class="form-control" 
                    <?php if (empty($gentilef2)){
                                echo "placeholder='Null'";
                            }
                            else {
                                echo "value='".$gentilef2."'"; 
                            }?>>
                                
                </div>
                <input type="text" name="te" value="1" hidden>
                <input type="text" name="id" value="<?php echo $id1 ?>" hidden>
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





<?php

}

if (isset($_POST['next'])) {
    header('Refresh: 1;URL=modifgentileimpr.php');
}

if ($i == 0) {

    try {
        $pdo->beginTransaction();
        $sql = 'SELECT id_ville,nom_ville, gentile_h_ville_impr1, gentile_h_ville_impr2, gentile_f_ville_impr1, gentile_f_ville_impr2 FROM ville WHERE modifgentileimpr is null and CHAR_LENGTH(gentile_h_ville_impr1) >= 15 and (gentile_h_ville_impr1 like "%-%" or gentile_h_ville_impr1 like "% %") LIMIT 1' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);
        $pdo->commit();
        $gentileh1 = $resultat['gentile_h_ville_impr1'];
        $gentileh2 = $resultat['gentile_h_ville_impr2'];
        $gentilef1 = $resultat['gentile_f_ville_impr1'];
        $gentilef2 = $resultat['gentile_f_ville_impr2'];
        $id1 = $resultat['id_ville'];
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 


?>



    <form method = "POST" action = "modifgentileimpr.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend ><?php echo $resultat['nom_ville']; ?></legend>
                <div class="col">
                    <label for="formGroupExampleInput">Gentile homme Ligne 1</label>
                    <p id="compteur"><?php echo mb_strlen($gentileh1)?> Caractère(s)</p>
                    <input type="text" id="message" onkeyup="afficher()" name='gentileh1' class="form-control" value="<?php echo $gentileh1; ?>">
                </div>
                <div class="col" style="margin-top: 20px;">
                    <label for="formGroupExampleInput">Gentile homme Ligne 2</label>
                    <p id="compteurs"><?php echo mb_strlen($gentileh2)?> Caractère(s)</p>
                    <input type="text" id="messages" onkeyup="afficher2()" name='gentileh2' class="form-control" 
                    <?php if (empty($gentileh2)){
                                echo "placeholder='Null'";
                            }
                            else {
                                echo "value='".$gentileh2."'"; 
                            }?>>
                                
                </div>
            </fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <div class="col">
                    <label for="formGroupExampleInput">Gentile femme Ligne 1</label>
                    <p id="compteur3"><?php echo mb_strlen($gentilef1)?> Caractère(s)</p>
                    <input type="text" id="message3" onkeyup="afficher3()" name='gentilef1' class="form-control" 
                    <?php if (empty($gentilef1)){
                                echo "placeholder='Null'";
                            }
                            else {
                                echo "value='".$gentilef1."'"; 
                            }?>>
                </div>
                <div class="col" style="margin-top: 20px;">
                    <label for="formGroupExampleInput">Gentile femme Ligne 2</label>
                    <p id="compteur4"><?php echo mb_strlen($gentilef2)?> Caractère(s)</p>
                    <input type="text" id="message4" onkeyup="afficher4()" name='gentilef2' class="form-control" 
                    <?php if (empty($gentilef2)){
                                echo "placeholder='Null'";
                            }
                            else {
                                echo "value='".$gentilef2."'"; 
                            }?>>
                                
                </div>
                <input type="text" name="te" value="1" hidden>
                <input type="text" name="id" value="<?php echo $id1 ?>" hidden>
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
<?php 
}
?>
    <form method = "POST" action = "modifgentileimpr.php" enctype="multipart/form-data" >
        <fieldset >
            <div class="form-group">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-4">
                    <button style="padding-left: 50%; padding-right: 50%; margin-left: 50%;" type="submit" name="next" class="btn btn-primary" ><i class="fas fa-arrow-right"></i> <span class="glyphicon glyphicon-send"></span></button>
                </div>
                </div>
        </fieldset>
    </form>


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>
    function afficher(){
        var saisie =document.getElementById("message").value;
        var nombreCaractere = saisie.length;
        var msg =  nombreCaractere + ' Caractère(s)';
        $('#compteur').text(msg);
    }

    function afficher2(){
        var saisie =document.getElementById("messages").value;
        var nombreCaractere = saisie.length;
        var msg =  nombreCaractere + ' Caractère(s)';
        $('#compteurs').text(msg);
    }

    function afficher3(){
        var saisie =document.getElementById("message3").value;
        var nombreCaractere = saisie.length;
        var msg =  nombreCaractere + ' Caractère(s)';
        $('#compteur3').text(msg);
    }

    function afficher4(){
        var saisie =document.getElementById("message4").value;
        var nombreCaractere = saisie.length;
        var msg =  nombreCaractere + ' Caractère(s)';
        $('#compteur4').text(msg);
    }
    </script>


</body>
</html>
