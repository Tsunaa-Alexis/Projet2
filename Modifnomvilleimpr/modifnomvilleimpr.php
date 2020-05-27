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

try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } 
    catch (Exception $e) {
        die("Impossible de se connecter : " . $e->getMessage());
    }

$i=0;

if (isset($_POST['insert'])) {
    $i = $_POST['te'];
    if (empty($_POST['nomville1'])){
        $nomville1 = NULL;
    }
    else{
        $nomville1 = $_POST['nomville1'];
    }
    
    if (empty($_POST['nomville2'])){
        $nomville2 = NULL;
    }
    else{
        $nomville2 = $_POST['nomville2'];
    }
    $id1 = $_POST['id'];

        $data = [
                    'nomville1' => $nomville1,
                    'nomville2' => $nomville2,
                    'id' => $id1,
                ];
        $sql = "UPDATE ville
                SET nom_ville_impr1 = :nomville1, nom_ville_impr2 = :nomville2, nom_modif = 1
                WHERE id_ville = :id";
        $stmt = $pdo->prepare($sql)->execute($data);


        $sql = 'SELECT id_ville,nom_ville, nom_ville_impr1, nom_ville_impr2 FROM ville WHERE id_ville ='.$id1 ;
        $std = $pdo->prepare($sql);
        $std->execute();
        $resultat = $std->fetch(PDO::FETCH_ASSOC);
        
        $nomville1 = $resultat['nom_ville_impr1'];
        $nomville2 = $resultat['nom_ville_impr2'];
        $id1 = $resultat['id_ville'];


?>

<form method = "POST" action = "modifnomvilleimpr.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend ><?php 
                    echo $resultat['nom_ville'];
                 ?></legend>
                    <div class="col">
                        <label for="formGroupExampleInput">Ligne 1</label>
                        <p id="compteur"><?php echo mb_strlen($nomville1)?> Caractère(s)</p>
                        <input id="message" type="text"  onkeyup="afficher()" class="form-control" <?php 
                            echo "value='".$nomville1."'";
                            echo "name='nomville1'";
                         ?>>
                    </div>
                    <div class="col" style="margin-top: 20px;">
                        <label for="formGroupExampleInput">Ligne 2</label>
                        <p id="compteurs"><?php echo mb_strlen($nomville2)?> Caractère(s)</p>
                        <input id="messages" onkeyup="afficher2()" type="text" name='nomville2' class="form-control" 
                        <?php
                            if (empty($nomville2)){
                                    echo "placeholder='Null'";
                                }
                                else {
                                    echo "value='".$nomville2."'"; 
                                };

                         ?>>
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
    header('Refresh: 1;URL=modifnomvilleimpr.php');
}



if ($i == 0) {
    
    // Insertion des données dans la BDD
    try {
        $pdo->beginTransaction();
        $sql = 'SELECT id_ville,nom_ville, nom_ville_impr1, nom_ville_impr2 FROM ville WHERE LENGTH(nom_ville_impr1) >= 15 and nom_modif is null LIMIT 1' ;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $resultat = $sth->fetch(PDO::FETCH_ASSOC);
        
        $nomville1 = $resultat['nom_ville_impr1'];
        $nomville2 = $resultat['nom_ville_impr2'];
        $id1 = $resultat['id_ville'];
    }
    catch (Exception $e) {
        $pdo->rollback();
        echo('Erreur : ' . $e->getMessage());
    } 
    $pdo->commit();

}

if ($i == 0) {
?>



    <form method = "POST" action = "modifnomvilleimpr.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend ><?php 
                    echo $resultat['nom_ville'];
                 ?></legend>
                    <div class="col">
                        <label for="formGroupExampleInput">Ligne 1</label>
                        <p id="compteur"><?php echo mb_strlen($nomville1)?> Caractère(s)</p>
                        <input id="message" type="text"  class="form-control" onkeyup="afficher()" <?php 
                            echo "value='".$nomville1."'";
                            echo "name='nomville1'";

                         ?>>
                    </div>
                    <div class="col" style="margin-top: 20px;">
                        <label for="formGroupExampleInput">Ligne 2</label>
                        <p id="compteurs"><?php echo mb_strlen($nomville2)?> Caractère(s)</p>
                        <input id="messages" type="text" name='nomville2' class="form-control" onkeyup="afficher2()" 
                        <?php
                            if (empty($nomville2)){
                                    echo "placeholder='Null'";
                                }
                                else {
                                    echo "value='".$nomville2."'"; 
                                };

                         ?>>
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
<?php } ?>
    <form method = "POST" action = "modifnomvilleimpr.php" enctype="multipart/form-data" >
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
</script>

  <!--  <script>
$(document).ready(function(e) {
 
  $('#message').keyup(function() {
 
    var nombreCaractere = $(this).val().length;
 
    var msg =  nombreCaractere + ' Caractere(s)';
    $('#compteur').text(msg);
 
  })
 
});

$(document).ready(function(f) {
 
  $('#messages').keyup(function() {
 
    var nombreCaractere = $(this).val().length;
    
    if($(this).val() === '') {
        nombreMots = 0;
    }
 
    var msg =  nombreCaractere + ' Caractere(s)';
    $('#compteurs').text(msg);
 
  })
 
});
</script>-->


</body>
</html>
