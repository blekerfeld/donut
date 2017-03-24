<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: d_admin.index.php

	if(!pLogged() OR !pCheckAdmin())
		die(pUrl('', true));
		

	// Let's get ourselves a header
	pOut('<span class="title_header"><div class="icon-box throw"><i class="fa fa-book"></i></div> '.DA_TITLE.'</span><br /><br />', true);


	// datafields: name, surface, width, type, show-in-table, show-in-form
	$structure = new pStructure("dictionary", "admin");
	$adminParse = $structure->load();

	// What are we getting?

	$currentSection = $adminParse[$_GET['section']];

	// Parsing, compiling and rendering of the admin page
	$adminParser = new pAdminParser($currentSection);
	$adminParser->compile();

	// If there is some action to do, we need to do that action, duh
	if(isset($_REQUEST['action']))
		goto action_page;

	// If there is an offset, we need to define that

	main_page:

	$adminParser->runData();
	$adminParser->render();

	action_page:
	if(isset($_REQUEST['action'])){
		$adminParser->compile();
		if(isset($_REQUEST['id']))
			$adminParser->runData($_REQUEST['id']);
		$adminPaser->action($_REQUEST['action']);

	}


