<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: d_admin.index.php

	
	$pStructure = new pAdminStructure('dictionary', 'admin', 'dictionary-admin', 'lexcat', 'Admin Panel');

	$pStructure->load();
	$pStructure->compile();
	$pStructure->render();

	return;