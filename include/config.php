<?php
/* ===================================
======= Configuration ==============
==================================== */

	define('PRINT_SQL', 0);
	/* config pour affichage des dates */
	define('_CONFIG_DATE', 'd/m/Y à H:i:s');
	/* infos base de donnée */
	
	define('DB_HOST', 'localhost');
	define('DB_USER', 'afterwar');
	define('DB_PASS', 'afterwar002');
	define('DB_DB', 'afterwar');
	
	//	Divers
	//	define('ID_FORUM_COMMENTAIRES',4);    // ID de la catégorie dédié aux commentaires  !!!!!!!!!!!!!!!!!
	//	define('NOM_ADMINISTRATEUR','Administrateur');  // Nom sur le forum de l'admin qui ajoute les jeux
	define('ROOT','./');
	define('BASE_REPERTOIRE','http://denis-moureu.fr/Test/afterwar/');
	//	define('NB_JEUX_PAR_PAGE',5);   // Nombre de jeux affiché par pages
	//	define('ORDRE_COMMENTAIRES','ORDER BY ID DESC LIMIT 5');   // Pour déterminer l'affichage des commentaires 
	//  ORDER BY RAND() LIMIT X  pour X commentaires aléatoirement
	// ORDER BY ID DESC LIMIT X pour afficher les X derniers commentaires
?>