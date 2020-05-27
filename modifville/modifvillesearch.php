<html>
    <head>
        <meta charset="utf-8"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="../css/tablelistclient.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/768b55194c.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.css">
        <style>
            #city-container{display: inline-block;position: relative;vertical-align: middle;width: 360px;}
            #city-container input{width:100%}
            #city-container ul{left:0 !important;right:0 !important;max-height:320px;overflow-y:auto;overflow-x:hidden;}
        </style>
<script>
/* initialisation paramètres globaux : */
var cache = {}; /* tableau cache de tous les termes */
var term = null; /* terme renseigné dans le champ input */
var baseUrl = 'localhost'; /* url du site */
baseUrl = '';

$(document).ready(function() {
    /* city autocomplete */
    $('#city').autocomplete({
        minLength:2, /* nombre de caractères minimaux pour lancer une recherche */
        delay:200, /* delais après la dernière touche appuyée avant de lancer une recherche */
        scrollHeight:320,
        appendTo:'#city-container', /* div ou afficher la liste des résultats, si null, ce sera une div en position fixe avant la fin de </body> */
        
        /* dès qu'une recherche se lance, source est executé, il peut contenir soit un tableau JSON de termes, soit une fonctions qui retournera un résultat */
        source:function(e,t){
            term = e.term; /* récupération du terme renseigné dans l'input */
            if(term in cache){ /* on vérifie que la clé "term" existe dans le tableau "cache", si oui alors on affiche le résultat */
                t(cache[term]);
            }else{ /* sinon on fait une requête ajax vers city.php pour rechercher "term" */
                $('#loading').attr('style','');
                $.ajax({
                    type:'GET',
                    url:'ville.php',
                    data:'name='+e.term,
                    dataType:"json",
                    async:true,
                    cache:true,
                    success:function(e){
                        cache[term] = e; /* vide ou non, on stocke la liste des résultats avec en clé "term" */
                        if(!e.length){ /* si aucun résultats, on renvoi un tableau vide pour informer qu'on a rien trouvé */
                            var result = [{
                                label: 'Aucune ville trouvée...',
                                value: null,
                                id: null,
                            }];
                            t(result); /* envoit du résultat à source */
                        }else{ /* sinon on renvoi toute la liste */
                            t($.map(e, function (item){
                                return{
                                    label: item.label,
                                    value: item.value,
                                    id: item.id,
                                }
                            }));  /* envoit du résultat à source avec map() de jQuery (permet d'appliquer une fonction pour tous les éléments d'un tableau */
                        }
                        $('#loading').attr('style','display:none;');
                    }
                });
            }
        },
        
        /* sélection depuis la liste des résultats (flèches ou clic) > ajout du résultat automatique et callback */
        select: function(event, ui) {
            $('form input[name="city-hidden"]').val(ui.item ? ui.item.id : ''); /* on récupère juste l'id qu'on stocke dans l'autre input */
        },
        open: function() {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function() {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        },
    });
});
</script>


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
?>
    <p> <a href='creationville.php'><span style="font-size: 3em; margin: 1%; position: absolute;"><i class="fas fa-plus" lass="well form-horizontal" id="contact_form"></i></span></a></p>


        <form class="form-inline" method = "POST" action = "modifvilleform.php">
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend >Modification des informations d'une ville</legend>
                <p>Rechercher par nom de ville (minuscule sans accent) OU code postal OU code insee</p>
                <span id="city-container">
                    <input class="form-control mr-sm-2" type="search" placeholder="Choisir une ville" aria-label="Search" name="city" id="city">
                </span>
                <button class="btn btn-outline-success my-2 my-sm-0" name="env" type="submit">Search</button>
                <span id="loading" style="display:none;"><i class="fa fa-circle-o-notch fa-spin"></i></span>
                <input type="hidden" name="city-hidden">
        </form>

        <table class="table" data-toggle="table" data-pagination="true" data-search="true"  data-page-size="10" data-page-list="[ 10, 25, 50]" data-sort-name="date" data-sort-order="desc">
            <thead class="thead-dark">
                <tr>
                    <th data-sortable="true" data-field="date" scope="col">ID Ville</th>
                    <th data-field="cid" scope="col">Nom</th>
                    <th data-field="Nom" scope="col">Code Insee</th>
                    <th data-field="espaceclient" scope="col">Code Postal</th>                    
                    <th data-field="Modifier" scope="col">Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pdo->beginTransaction();
                $sql = 'SELECT id_ville, nom_ville, code_insee_ville, code_postal_ville, (gentile_h_ville is null) + (gentile_f_ville is null) + (nom_ville is null) + (code_insee_ville is null) + (code_postal_ville is null) + (population_ville is null) + (derby_ville is null) + (alias_ville is null) + (rewrite_ville is null) + (abreviation_ville is null) + (description_ville is null) + (meta_desc_ville is null) + (meta_kw_ville is null) + (meta_title_ville is null) + (limitrophe_ville is null) + (geo_point_ville is null) + (geo_shape_ville is null) + (nom_ville_impr1 is null) + (gentile_f_ville_impr1 is null) + (gentile_f_ville_impr1 is null) as test 
                        FROM ville 
                        WHERE actif_ville = 0 or actif_ville is null
                        ORDER BY test DESC LIMIT 100' ;
                foreach  ($pdo->query($sql) as $row) {
                    echo    '<tr>
                                <td>'.$row["id_ville"].'</td>
                                <td>'.$row["nom_ville"].'</td>
                                <td>'.$row["code_insee_ville"].'</td>
                                <td>'.$row["code_postal_ville"].'</td>
                                <td><form  method = "POST" action = "modifvilleform.php"><input type="text" name="city-hidden" value="'.$row['id_ville'].'" hidden><input type="submit" name="Modifier" value="Modifier"></form></td>
                            </tr>';
                }
                ?>
                <tr>

                </tr>
            </tbody>
        </table>

        <script src="https://unpkg.com/bootstrap-table@1.15.5/dist/bootstrap-table.min.js"></script>

    </body>
</html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
