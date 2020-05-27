<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$array = array();
	$responseCode = 500;
	
	/* si la requête est bien en Ajax et la méthode en GET ... */
	if((strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') && ($_SERVER['REQUEST_METHOD'] == 'GET')){
		/* on récupère le terme et on le duplique en terme en transformant les espaces en tirets et tirets en espaces (au cas ou) */
		$qb = $_REQUEST['name'];
		$q = str_replace("''","'",$_REQUEST['name']);
		$q = strtolower(str_replace("'","''",$q));
		$qTiret = str_replace(' ','-',$q);
		$qSpace = str_replace('-',' ',$q);

		
		$array = array();
		
		/* connexion SQL  (avec PDO car Mysql_connect sera déprécié dès php 7 :P) */
		$host='localhost';
		$port='3306';
		$database='testest';
		$user='root';
		$password='root';
		$connexion = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$database, $user, $password);
		
		/* creation de la requête SQL */
		$query=$connexion->query('SELECT ts.id_ville, ts.nom_ville, ts.code_postal_ville, ts.rewrite_ville FROM ville ts WHERE (ts.nom_ville LIKE \'%'.$q.'%\' OR ts.nom_ville LIKE \'%'.$qTiret.'%\' OR ts.nom_ville LIKE \'%'.$qSpace.'%\' OR ts.nom_ville LIKE \'%'.$qb.'%\'OR ts.code_postal_ville LIKE \'%'.$q.'%\' OR ts.rewrite_ville LIKE \'%'.$q.'%\' OR ts.rewrite_ville LIKE \'%'.$qTiret.'%\' OR ts.rewrite_ville LIKE \'%'.$qSpace.'%\' OR CONCAT(ts.nom_ville,ts.rewrite_ville,code_postal_ville) LIKE \'%'.$q.'%\' OR CONCAT(ts.nom_ville,ts.rewrite_ville,code_postal_ville) LIKE \'%'.$qb.'%\' OR CONCAT(ts.nom_ville,ts.rewrite_ville,code_postal_ville) LIKE \'%'.$qTiret.'%\' OR CONCAT(ts.nom_ville,ts.rewrite_ville,code_postal_ville) LIKE \'%'.$qSpace.'%\') ORDER BY ts.nom_ville ASC');
		$query->setFetchMode(PDO::FETCH_OBJ);
		
		/* remplissage du tableau avec les termes récupéré en requete (ou non) */
		while($q = $query->fetch()){
			$name = $q->nom_ville;
			$postalcode = $q->code_postal_ville;
			$id = $q->id_ville;
			$array[] = array(
					'id' => $id,
					'label' => $name." (".$postalcode.")",
					'value' => $name." (".$postalcode.")",
			);
		}
		$query->closeCursor();
				
		//die(print_r($array));
		
		$responseCode = 200;
	}
	
	/* génération réponse JSON */
	http_response_code($responseCode);
	header('Content-Type: application/json');
	echo json_encode($array);
?>