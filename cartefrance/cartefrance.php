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


// fonction pour convertire la longitude et lattitude en pixel, les fonctions
// x et y sont adaptées pour la france, xs et ys sont addaptées à la corse
function x ($x) {
    $width = 1000;
    $x = ($width * ($x - (-6.169012258275)) / (10.000520192048 - (-6.169012258275)));

    return $x;

}

function y ($y) {
    $height = 1000;
    $y = ($height * ($y - 52.6170399) / (41.2829902 - 52.6170399));

    return $y;

}

function xs ($x) {
    $width = 1000;
    $x = ($width * ($x - (-5.210664)) / (10.286836 - (-5.210664)));

    return $x;

}

function ys ($y) {
    $height = 1000;
    $y = ($height * ($y - 52.999112) / (41.033322 - 52.999112));

    return $y;

}

//fonction pour donner l'impression d'arrondissement sur un rectangle
function ImageRectangleWithRoundedCorners(&$im, $x1, $y1, $x2, $y2, $radius, $color, $color2) {
// draw rectangle without corners
imagesetthickness($im, 20);
imagerectangle($im, $x1+$radius, $y1, $x2-$radius, $y2, $color);
imagerectangle($im, $x1, $y1+$radius, $x2, $y2-$radius, $color);

// draw circled corners
imagefilledellipse($im, $x1+$radius-10, $y1+$radius-10, $radius*2, $radius*2, $color);
imagefilledellipse($im, $x2-$radius+10, $y1+$radius-10, $radius*2, $radius*2, $color);
imagefilledellipse($im, $x1+$radius-10, $y2-$radius+10, $radius*2, $radius*2, $color);
imagefilledellipse($im, $x2-$radius+10, $y2-$radius+10, $radius*2, $radius*2, $color);
imagefilledrectangle($im, $x1+3, $y1+3, $x2-3, $y2-3, $color2);
}


// Définit la variable d'environnement pour GD
putenv('GDFONTPATH=' . realpath('.'));

// Nom de la police à utiliser (note qu'il n'y a pas d'extension .ttf)
$font_file = 'C:\wamp64\www\htdocs\cartefrance/Calistoga_EFDLE.ttf';

$pdo->beginTransaction();
$sql = 'SELECT nom_ville_impr1, nom_ville_impr2, nom_ville, rewrite_ville, geo_point_ville
    FROM ville 
    where actif_ville = 0 or actif_ville is null and geo_point_ville is not null and (id_dep != 761 or id_dep != 762)  limit 20' ;

    foreach  ($pdo->query($sql) as $row) {
        // on insert dans filename l'image que l'on va modifié
        $filename = "cartefrance.png";
        // on crée une nouvelle image à partir de l'image dans $filename
        $image = imagecreatefrompng ( $filename );
        // on sépare en deux le résultat du géo point, $loc retournera un tableau avec les deux valeurs 
        // attendu
        $loc = explode(',', $row['geo_point_ville']);
        
        // on initialise les couleurs que l'on aura besoin d'affecter à l'image
        $white = imagecolorallocate($image, 255, 255, 255);
        $green = imagecolorallocate($image, 0, 255, 0);
        $black = imagecolorallocate($image, 1, 1, 1);
        $red = imagecolorallocate($image, 239, 29, 32);
        $grey = imagecolorallocate($image, 112, 112, 112);

        // le fond de l'image deviens transparent
        imagecolortransparent($image, $black);

        $af = mb_strtoupper($row['nom_ville_impr1']); // retourne le nom de la ville en majuscule
        // on stock dans $bbox la taille que prendra un texte donnée pour une taille de police donnée (50 ici)
        $bf = mb_strtoupper($row['nom_ville_impr2']);

        // et pour une inclinaison donnée (0 ici) avec la police définit (stocker dans $font_file ici)
        // on rentre le texte dans la dernière position
        $bbox = imageftbbox(50, 0, $font_file, $af);

        $bboxs = imageftbbox(50, 0, $font_file, $bf);

        if (!empty($row['nom_ville_impr2'])) {
            $a = mb_strlen($row['nom_ville_impr1']);
            $b = mb_strlen($row['nom_ville_impr2']);
            if ($a > $b) {
                $xag = $bbox['2'] + 760;
            }
            else {
                $xag = $bboxs['2'] + 760;
            }
        }
        else {
            $xag = $bbox['2'] + 760; // position X en bas à droite en fonction de la taille du texte
        }
        
        $yag = 25;  // position y en haut à gauche
        if (empty($row['nom_ville_impr2'])) {
            $ybd = $bbox['3'] + 125;
        }
        else {
            $ybd = $bbox['3'] + 125 + 80; // position y bas droite en fonction de la taille du texte
        }

        $xagb = $bboxs['2'] + 760; // position X en bas à droite en fonction de la taille du texte
        $yagb = 25;  // position y en haut à gauche
        $ybdb = $bboxs['3'] + 125; // position y bas droite en fonction de la taille du texte



        
        
        // position x et y en pixel récupérer après les fonction x et y 
        $x = x($loc[1]);
        $y = y($loc[0]);

        
        
        // on crée une nouvelle image de la taille de l'image de départ (1000 pixel dans notre cas)
        // plus la taille du text
        if (!empty($row['nom_ville_impr2'])) {
            $a = mb_strlen($row['nom_ville_impr1']);
            $b = mb_strlen($row['nom_ville_impr2']);
            if ($a > $b) {
                $ximg = 770 + $bbox['2'] + 20;
            }
            else {
                $ximg = 770 + $bboxs['2'] + 20;
            }
        }
        else {
            $ximg = 770 + $bbox['2'] + 20;
        }
        
        // si $ximg est inférieur à 1000 ont le met à 1000 sinon la corse se fait couper 
        if ($ximg < 1000) {
            $ximg = 1000;
        }
        $image_p = imagecreatetruecolor($ximg, 1000);
        $blackc = imagecolorallocate($image_p, 0, 0, 0);
        // ont met notre image en transparent
        imagecolortransparent($image_p, $blackc);

        
        // ont fusionne les deux images en leurs donnant la taille adéquate pour que le texte rentre
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $ximg, 1000, $ximg, 1000);

        

        imagepng($image_p,'image/'.$row['rewrite_ville'].'_carte.png'); // on sauvegarde notre image dans le dossier choisit


        $filename = 'image/'.$row['rewrite_ville'].'_carte.png'; 
        $image2 = imagecreatefrompng ( $filename ); // on réouvre l'image modifier plus haut
        imagesetthickness($image2, 5); // on définis la taille des trait, à la position actuelle il modifie uniquement la ligne tracer plus bas
        imageline($image2, 700, $ybd, $x, $y, $grey); // trace le trait entre le rectangle et le point de la ville

        // on dessine le point où se situe la ville en fonction des coordonée récupérer
        imagefilledellipse($image2, $x, $y, 30, 30, $red);

        
        //imagerectangle($image2, 640, $yag, $xag , $ybd , $red);
        // on déssine le rectangle entourant le texte en fonction de la position de celui-ci
        imagesetthickness($image2, 10);
        ImageRectangleWithRoundedCorners($image2, 700, $yag, $xag , $ybd , 7,  $red, $white);
        
        
        imagefttext($image2, 50, 0, 730, 100, $black, $font_file, $af); // on écrit le texte aux coordonnée choisis
        imagefttext($image2, 50, 0, 730, 180, $black, $font_file, $bf);

        imagepng($image2,'image/'.$row['rewrite_ville'].'_carte.png'); //on sauvegarde l'image par dessus la sauvegarde précédente

        echo "L'image a bien été modifier<br/>";
        echo "<img src='image/".$row['rewrite_ville']."_carte.png' width='100px' height='100' /> ";



    }
//même procéder mais pour la corse
$sql = 'SELECT nom_ville_impr1, nom_ville_impr2, nom_ville, rewrite_ville, geo_point_ville
    FROM ville 
    where actif_ville = 0 or actif_ville is null and geo_point_ville is not null and (id_dep = 761 or id_dep = 762) limit 5' ;

    foreach  ($pdo->query($sql) as $row) {
        // Création d'une image
        $filename = "cartefrance.png";
        // on crée une nouvelle image à partir de l'image dans $filename
        $image = imagecreatefrompng ( $filename );
        // on sépare en deux le résultat du géo point, $loc retournera un tableau avec les deux valeurs 
        // attendu
        $loc = explode(',', $row['geo_point_ville']);
        
        // on initialise les couleurs que l'on aura besoin d'affecter à l'image
        $white = imagecolorallocate($image, 255, 255, 255);
        $green = imagecolorallocate($image, 0, 255, 0);
        $black = imagecolorallocate($image, 1, 1, 1);
        $red = imagecolorallocate($image, 239, 29, 32);
        $grey = imagecolorallocate($image, 112, 112, 112);

        // le fond de l'image deviens transparent
        imagecolortransparent($image, $black);

        $af = mb_strtoupper($row['nom_ville_impr1']); // retourne le nom de la ville en majuscule
        // on stock dans $bbox la taille que prendra un texte donnée pour une taille de police donnée (50 ici)
        $bf = mb_strtoupper($row['nom_ville_impr2']);
        // et pour une inclinaison donnée (0 ici) avec la police définit (stocker dans $font_file ici)
        // on rentre le texte dans la dernière position
        $bbox = imageftbbox(50, 0, $font_file, $af);

        $bboxs = imageftbbox(50, 0, $font_file, $bf);

        if (!empty($row['nom_ville_impr2'])) {
            $a = mb_strlen($row['nom_ville_impr1']);
            $b = mb_strlen($row['nom_ville_impr2']);
            if ($a > $b) {
                $xag = $bbox['2'] + 760;
            }
            else {
                $xag = $bboxs['2'] + 760;
            }
        }
        else {
            $xag = $bbox['2'] + 760; // position X en bas à droite en fonction de la taille du texte
        }
        
        $yag = 25;  // position y en haut à gauche
        if (empty($row['nom_ville_impr2'])) {
            $ybd = $bbox['3'] + 125;
        }
        else {
            $ybd = $bbox['3'] + 125 + 80; // position y bas droite en fonction de la taille du texte
        }

        $xagb = $bboxs['2'] + 760; // position X en bas à droite en fonction de la taille du texte
        $yagb = 25;  // position y en haut à gauche
        $ybdb = $bboxs['3'] + 125; // position y bas droite en fonction de la taille du texte



        
        
        // position x et y en pixel récupérer après les fonction x et y 
        $x = xs($loc[1]);
        $y = ys($loc[0]);

        
        
        // on crée une nouvelle image de la taille de l'image de départ (1000 pixel dans notre cas)
        // plus la taille du text
        if (!empty($row['nom_ville_impr2'])) {
            $a = mb_strlen($row['nom_ville_impr1']);
            $b = mb_strlen($row['nom_ville_impr2']);
            if ($a > $b) {
                $ximg = 770 + $bbox['2'] + 20;
            }
            else {
                $ximg = 770 + $bboxs['2'] + 20;
            }
        }
        else {
            $ximg = 770 + $bbox['2'] + 20;
        }
        
        // si $ximg est inférieur à 1000 ont le met à 1000 sinon la corse se fait couper 
        if ($ximg < 1000) {
            $ximg = 1000;
        }
        $image_p = imagecreatetruecolor($ximg, 1000);
        $blackc = imagecolorallocate($image_p, 0, 0, 0);
        // ont met notre image en transparent
        imagecolortransparent($image_p, $blackc);

        
        // ont fusionne les deux images en leurs donnant la taille adéquate pour que le texte rentre
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $ximg, 1000, $ximg, 1000);

        

        imagepng($image_p,'image/'.$row['rewrite_ville'].'_carte.png'); // on sauvegarde notre image dans le dossier choisit


        $filename = 'image/'.$row['rewrite_ville'].'_carte.png'; 
        $image2 = imagecreatefrompng ( $filename ); // on réouvre l'image modifier plus haut
        imagesetthickness($image2, 5); // on définis la taille des trait, à la position actuelle il modifie uniquement la ligne tracer plus bas
        imageline($image2, 700, $ybd, $x, $y, $grey); // trace le trait entre le rectangle et le point de la ville

        // on dessine le point où se situe la ville en fonction des coordonée récupérer
        imagefilledellipse($image2, $x, $y, 25, 25, $red);

        
        //imagerectangle($image2, 640, $yag, $xag , $ybd , $red); // on déssine le rectangle entourant le texte en fonction de la position de celui-ci
        imagesetthickness($image2, 10);
        ImageRectangleWithRoundedCorners($image2, 700, $yag, $xag , $ybd , 7,  $red, $white);
        
        
        imagefttext($image2, 50, 0, 730, 100, $black, $font_file, $af); // on écrit le texte aux coordonnée choisis
        imagefttext($image2, 50, 0, 730, 180, $black, $font_file, $bf);

        imagepng($image2,'image/'.$row['rewrite_ville'].'_carte.png'); //on sauvegarde l'image par dessus la sauvegarde précédente
        
        echo "L'image a bien été modifier<br/>";
        echo "<img src='image/".$row['rewrite_ville']."_carte.png' width='100px' height='100' /> ";




    }
    $pdo->commit();

?>