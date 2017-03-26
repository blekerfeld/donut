<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: d_admin.index.php

	
	if(!pLogged() OR !pCheckAdmin())
		die(pUrl('', true));

	$pStructure = new pAdminStructure('dictionary', 'admin', 'dictionary-admin', 'lexcat', 'Admin Panel');

	$pStructure->load();
	$pStructure->compile();
	$pStructure->render();

	return;



