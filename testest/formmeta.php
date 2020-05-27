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
$db   = 'newtest';
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


if (isset($_POST['insert'])) {
    $pdo->beginTransaction();
    $desc = $_POST['desc'];
    $metadesc = $_POST['metadesc'];
    $kw = $_POST['kw'];
    $title = $_POST['title'];
    $choix = $_POST['inlineRadioOptions'];
    if (empty($desc) && empty($metadesc) && empty($kw) && empty($title)) {
        echo "Veuillez remplir aumoins un des champs";
    }
    else {

        if ($choix == "ville") {
            $sql = 'SELECT nom_ville,id_ville, code_postal_ville, gentile_h_ville, gentile_f_ville, population_ville, limitrophe_ville, nom_dep, num_dep, gentile_h_dep, gentile_f_dep, nom_reg, nom_greg
                    FROM ville v, departement d, grande_region g, region_ancienne r
                    WHERE actif_ville is null and v.id_dep = d.id_dep and d.id_greg = g.id_greg and d.id_reg = r.id_reg ';

            foreach  ($pdo->query($sql) as $row) {
                $nom = $row['nom_ville'];
                $cp = $row['code_postal_ville'];
                $gh = $row['gentile_h_ville'];
                $gf = $row['gentile_f_ville'];
                $pop = $row['population_ville'];
                $nomdep = $row['nom_dep'];
                $depgf = $row['gentile_f_dep'];
                $depgh = $row['gentile_h_dep'];
                $nomreg = $row['nom_reg'];
                $nomgreg = $row['nom_greg'];
                $numdep = $row['num_dep'];
                $lim = 0;
                if (stristr($desc,'<lim>') === FALSE) {

                    
                }

                else {
                    $sqld = 'SELECT GROUP_CONCAT(nom_ville) as ville_lim FROM ville WHERE code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,1,5) FROM ville WHERE id_ville ='.$row['id_ville'].') 
                        or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,7,5) FROM ville WHERE id_ville = '.$row['id_ville'].') 
                        or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,13,5) FROM ville WHERE id_ville = '.$row['id_ville'].') 
                        or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,19,5) FROM ville WHERE id_ville = '.$row['id_ville'].') 
                        or code_insee_ville = (SELECT SUBSTRING(limitrophe_ville,25,5) FROM ville WHERE id_ville = '.$row['id_ville'].')' ;
                    $sth = $pdo->prepare($sqld);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    $lim = $resultat['ville_lim'];
                }
                /*for ($i=0; $i < count($limi); $i++) { 
                    $numlim = $limi[$i];
                    $sqld = 'SELECT nom_ville FROM ville WHERE code_insee_ville ="'.$numlim.'"' ;
                    $sth = $pdo->prepare($sqld);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    if ($i == 0) {
                        $lim = $resultat['nom_ville'];
                    }
                    else {
                        $lim .= ", ".$resultat['nom_ville'];
                    }           
                }*/

                $remp1 = str_replace("<nomville>", " ".$nom." ",$desc);
                $remp1 = str_replace("<cp>", " ".$cp." ",$remp1);
                $remp1 = str_replace("<gh>", " ".$gh." ",$remp1);
                $remp1 = str_replace("<gf>", " ".$gf." ",$remp1);
                $remp1 = str_replace("<pop>", " ".$pop." ",$remp1);
                $remp1 = str_replace("<lim>", " ".$lim." ",$remp1);
                $remp1 = str_replace("<nomdep>", " ".$nomdep." ",$remp1);
                $remp1 = str_replace("<numdep>", " ".$numdep." ",$remp1);
                $remp1 = str_replace("<ghdep>", " ".$depgh." ",$remp1);
                $remp1 = str_replace("<gfdep>", " ".$depgf." ",$remp1);
                $remp1 = str_replace("<nomreg>", " ".$nomreg." ",$remp1);
                $remp1 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp1);
                $remp1 = str_replace("<lim>", " ".$lim." ",$remp1);



                $remp2 = str_replace("<nomville>", " ".$nom." ",$metadesc);
                $remp2 = str_replace("<cp>", " ".$cp." ",$remp2);
                $remp2 = str_replace("<gh>", " ".$gh." ",$remp2);
                $remp2 = str_replace("<gf>", " ".$gf." ",$remp2);
                $remp2 = str_replace("<pop>", " ".$pop." ",$remp2);
                $remp2 = str_replace("<lim>", " ".$lim." ",$remp2);
                $remp2 = str_replace("<nomdep>", " ".$nomdep." ",$remp2);
                $remp2 = str_replace("<numdep>", " ".$numdep." ",$remp2);
                $remp2 = str_replace("<ghdep>", " ".$depgh." ",$remp2);
                $remp2 = str_replace("<gfdep>", " ".$depgf." ",$remp2);
                $remp2 = str_replace("<nomreg>", " ".$nomreg." ",$remp2);
                $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp2);
                $remp2 = str_replace("<lim>", " ".$lim." ",$remp2);



                $remp3 = str_replace("<nomville>", " ".$nom." ",$kw);
                $remp3 = str_replace("<cp>", " ".$cp." ",$remp3);
                $remp3 = str_replace("<gh>", " ".$gh." ",$remp3);
                $remp3 = str_replace("<gf>", " ".$gf." ",$remp3);
                $remp3 = str_replace("<pop>", " ".$pop." ",$remp3);
                $remp3 = str_replace("<lim>", " ".$lim." ",$remp3);
                $remp3 = str_replace("<nomdep>", " ".$nomdep." ",$remp3);
                $remp3 = str_replace("<numdep>", " ".$numdep." ",$remp3);
                $remp3 = str_replace("<ghdep>", " ".$depgh." ",$remp3);
                $remp3 = str_replace("<gfdep>", " ".$depgf." ",$remp3);
                $remp3 = str_replace("<nomreg>", " ".$nomreg." ",$remp3);
                $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp3);
                $remp3 = str_replace("<lim>", " ".$lim." ",$remp3);


                $remp4 = str_replace("<nomville>", " ".$nom." ",$title);
                $remp4 = str_replace("<cp>", " ".$cp." ",$remp4);
                $remp4 = str_replace("<gh>", " ".$gh." ",$remp4);
                $remp4 = str_replace("<gf>", " ".$gf." ",$remp4);
                $remp4 = str_replace("<pop>", " ".$pop." ",$remp4);
                $remp4 = str_replace("<lim>", " ".$lim." ",$remp4);
                $remp4 = str_replace("<nomdep>", " ".$nomdep." ",$remp4);
                $remp4 = str_replace("<numdep>", " ".$numdep." ",$remp4);
                $remp4 = str_replace("<ghdep>", " ".$depgh." ",$remp4);
                $remp4 = str_replace("<gfdep>", " ".$depgf." ",$remp4);
                $remp4 = str_replace("<nomreg>", " ".$nomreg." ",$remp4);
                $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp4);
                $remp4 = str_replace("<lim>", " ".$lim." ",$remp4);
                
                $data = [
                            
                            'id' => $row['id_ville'],
                            
                            
                        ];

                $sqli = "UPDATE ville SET";
                if (!empty($remp1)) {
                    $sqli .= " description_ville = :descr";
                    $data['descr'] = $remp1;
                }

                if (!empty($remp2)) {
                    if ($sqli == "UPDATE ville SET") {
                        $sqli .= " meta_desc_ville = :metadesc";

                    }
                    else {
                        $sqli .= ", meta_desc_ville = :metadesc";
                    }
                    $data['metadesc'] = $remp2;
                }

                if (!empty($remp3)) {
                    if ($sqli == "UPDATE ville SET") {
                        $sqli .= " meta_kw_ville = :kw";
                    }
                    else {
                        $sqli .= ", meta_kw_ville = :kw";
                    }
                    $data['kw'] = $remp3;
                }

                if (!empty($remp4)) {
                    if ($sqli == "UPDATE ville SET") {
                        $sqli .= " meta_title_ville = :title";
                    }
                    else {
                        $sqli .= ", meta_title_ville = :title";
                    }
                    $data['title'] = $remp4;
                }


                $sqli .= " WHERE id_ville = :id";

                if (!empty($remp1)) {
                    $sqli .= " and desc_manuel_ville is null";
                }

                if (!empty($remp2)) {
                    $sqli .= " and meta_desc_manuel_ville is null";
                }

                if (!empty($remp3)) {
                    $sqli .= " and meta_kw_manuel_ville is null";
                }

                if (!empty($remp4)) {
                    $sqli .= " and meta_title_manuel_ville is null";
                }

                $stmt = $pdo->prepare($sqli)->execute($data);
            }
        }


        elseif ($choix == "departement") {
            $sql = 'SELECT id_dep, nom_dep, num_dep, gentile_h_dep, gentile_f_dep, nom_reg, nom_greg, limitrophe_dep, chef_lieu_dep
                    FROM  departement d, grande_region g, region_ancienne r
                    WHERE actif_dep is null and d.id_greg = g.id_greg and d.id_reg = r.id_reg ';

            foreach  ($pdo->query($sql) as $row) {
                $nomdep = $row['nom_dep'];
                $depgf = $row['gentile_f_dep'];
                $depgh = $row['gentile_h_dep'];
                $nomreg = $row['nom_reg'];
                $nomgreg = $row['nom_greg'];
                $numdep = $row['num_dep'];
                $cheflieudep = $row['chef_lieu_dep'];
                $lim = 0;
                $cheflieu = 0;
                if (stristr($desc,'<limdep>') === FALSE) {

                    
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
                /*for ($i=0; $i < count($limi); $i++) { 
                    $numlim = $limi[$i];
                    $sqld = 'SELECT nom_ville FROM ville WHERE code_insee_ville ="'.$numlim.'"' ;
                    $sth = $pdo->prepare($sqld);
                    $sth->execute();
                    $resultat = $sth->fetch(PDO::FETCH_ASSOC);
                    if ($i == 0) {
                        $lim = $resultat['nom_ville'];
                    }
                    else {
                        $lim .= ", ".$resultat['nom_ville'];
                    }           
                }*/

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


                $remp2 = str_replace("<nomdep>", " ".$nomdep." ",$metadesc);
                $remp2 = str_replace("<numdep>", " ".$numdep." ",$remp2);
                $remp2 = str_replace("<ghdep>", " ".$depgh." ",$remp2);
                $remp2 = str_replace("<gfdep>", " ".$depgf." ",$remp2);
                $remp2 = str_replace("<nomreg>", " ".$nomreg." ",$remp2);
                $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp2);
                $remp2 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp2);
                $remp2 = str_replace("<limdep>", " ".$lim." ",$remp2);



                $remp3 = str_replace("<nomdep>", " ".$nomdep." ",$kw);
                $remp3 = str_replace("<numdep>", " ".$numdep." ",$remp3);
                $remp3 = str_replace("<ghdep>", " ".$depgh." ",$remp3);
                $remp3 = str_replace("<gfdep>", " ".$depgf." ",$remp3);
                $remp3 = str_replace("<nomreg>", " ".$nomreg." ",$remp3);
                $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp3);
                $remp3 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp3);
                $remp3 = str_replace("<limdep>", " ".$lim." ",$remp3);


                $remp4 = str_replace("<nomdep>", " ".$nomdep." ",$title);
                $remp4 = str_replace("<numdep>", " ".$numdep." ",$remp4);
                $remp4 = str_replace("<ghdep>", " ".$depgh." ",$remp4);
                $remp4 = str_replace("<gfdep>", " ".$depgf." ",$remp4);
                $remp4 = str_replace("<nomreg>", " ".$nomreg." ",$remp4);
                $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$remp4);
                $remp4 = str_replace("<cheflieudep>", " ".$cheflieu." ",$remp4);
                $remp4 = str_replace("<limdep>", " ".$lim." ",$remp4);
                
                $data = [
                            
                            'id' => $row['id_dep'],
                            
                            
                        ];

                $sqli = "UPDATE departement SET";
                if (!empty($remp1)) {
                    $sqli .= " description_dep = :descr";
                    $data['descr'] = $remp1;
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

                if (!empty($remp1)) {
                    $sqli .= " and desc_manuel_dep is null";
                }

                if (!empty($remp2)) {
                    $sqli .= " and meta_desc_manuel_dep is null";
                }

                if (!empty($remp3)) {
                    $sqli .= " and meta_kw_manuel_dep is null";
                }

                if (!empty($remp4)) {
                    $sqli .= " and meta_title_manuel_dep is null";
                }

                $stmt = $pdo->prepare($sqli)->execute($data);
            }
        }




        elseif ($choix == "granderegion") {
            $sql = 'SELECT id_greg, nom_greg, chef_lieu_greg
                    FROM  grande_region
                    WHERE actif_greg is null';

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


                $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$metadesc);
                $remp2 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp2);


                $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$kw);
                $remp3 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp3);


                $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$title);
                $remp4 = str_replace("<cheflieugreg>", " ".$cheflieu." ",$remp4);
                
                $data = [
                            
                            'id' => $row['id_greg'],
                            
                            
                        ];

                $sqli = "UPDATE grande_region SET";
                if (!empty($remp1)) {
                    $sqli .= " description_greg = :descr";
                    $data['descr'] = $remp1;
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

                if (!empty($remp1)) {
                    $sqli .= " and description_manuel_greg is null";
                }

                if (!empty($remp2)) {
                    $sqli .= " and meta_desc_manuel_greg is null";
                }

                if (!empty($remp3)) {
                    $sqli .= " and meta_kw_manuel_greg is null";
                }

                if (!empty($remp4)) {
                    $sqli .= " and meta_title_manuel_greg is null";
                }

                $stmt = $pdo->prepare($sqli)->execute($data);
            }
        }

        elseif ($choix == "ancienneregion") {
            $sql = 'SELECT id_reg, nom_greg, nom_reg 
                    FROM  grande_region g, region_ancienne r
                    WHERE actif_reg is null and r.id_greg_reg = g.id_greg';

            foreach  ($pdo->query($sql) as $row) {
                $nomgreg = $row['nom_greg'];
                $nomreg = $row['nom_reg'];




                $remp1 = str_replace("<nomgreg>", " ".$nomgreg." ",$desc);
                $remp1 = str_replace("<nomreg>", " ".$nomreg." ",$remp1);


                $remp2 = str_replace("<nomgreg>", " ".$nomgreg." ",$metadesc);
                $remp2 = str_replace("<nomreg>", " ".$nomreg." ",$remp2);


                $remp3 = str_replace("<nomgreg>", " ".$nomgreg." ",$kw);
                $remp3 = str_replace("<nomreg>", " ".$nomreg." ",$remp3);


                $remp4 = str_replace("<nomgreg>", " ".$nomgreg." ",$title);
                $remp4 = str_replace("<nomreg>", " ".$nomreg." ",$remp4);
                
                $data = [
                            
                            'id' => $row['id_reg'],
                            
                            
                        ];

                $sqli = "UPDATE region_ancienne SET";
                if (!empty($remp1)) {
                    $sqli .= " description_reg = :descr";
                    $data['descr'] = $remp1;
                }

                if (!empty($remp2)) {
                    if ($sqli == "UPDATE region_ancienne SET") {
                        $sqli .= " meta_desc_reg = :metadesc";

                    }
                    else {
                        $sqli .= ", meta_desc_reg = :metadesc";
                    }
                    $data['metadesc'] = $remp2;
                }

                if (!empty($remp3)) {
                    if ($sqli == "UPDATE region_ancienne SET") {
                        $sqli .= " meta_kw_reg = :kw";
                    }
                    else {
                        $sqli .= ", meta_kw_reg = :kw";
                    }
                    $data['kw'] = $remp3;
                }

                if (!empty($remp4)) {
                    if ($sqli == "UPDATE region_ancienne SET") {
                        $sqli .= " meta_title_reg = :title";
                    }
                    else {
                        $sqli .= ", meta_title_reg = :title";
                    }
                    $data['title'] = $remp4;
                }


                $sqli .= " WHERE id_reg = :id";

                if (!empty($remp1)) {
                    $sqli .= " and desc_manuel_reg is null";
                }

                if (!empty($remp2)) {
                    $sqli .= " and meta_desc_manuel_reg is null";
                }

                if (!empty($remp3)) {
                    $sqli .= " and meta_kw_manuel_reg is null";
                }

                if (!empty($remp4)) {
                    $sqli .= " and meta_title_manuel_reg is null";
                }

                $stmt = $pdo->prepare($sqli)->execute($data);
            }
        }

    }

    $pdo->commit();

}



?>


    <form method = "POST" action = "formmeta.php" enctype="multipart/form-data" >
        <fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <legend >Modification des meta</legend>
            </fieldset>
            <fieldset style="padding-left: 10%; padding-top: 2em;">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" value="ville" name="inlineRadioOptions" id="inlineRadio1" required>
                    <label class="form-check-label" for="inlineRadio1">Ville</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" value="departement" name="inlineRadioOptions" id="inlineRadio2" required>
                    <label class="form-check-label" for="inlineRadio2">Département</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" value="granderegion" name="inlineRadioOptions" id="inlineRadio2" required>
                    <label class="form-check-label" for="inlineRadio2">Grande Région</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" value="ancienneregion" name="inlineRadioOptions" id="inlineRadio2" required>
                    <label class="form-check-label" for="inlineRadio2">Ancienne région</label>
                </div>
                <div class="form-group" style="padding-top: 20px;">
                    <label for="exampleFormControlTextarea1">Description</label>
                    <textarea class="form-control" name="desc" id="exampleFormControlTextarea1" rows="2"></textarea>
                </div> 
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Meta description</label>
                    <textarea class="form-control" name="metadesc" id="exampleFormControlTextarea1" rows="2"></textarea>
                </div> 
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Meta KW</label>
                    <textarea class="form-control" name="kw" id="exampleFormControlTextarea1" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Meta Title</label>
                    <textarea class="form-control" name="title" id="exampleFormControlTextarea1" rows="2"></textarea>
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





</body>
</html>
