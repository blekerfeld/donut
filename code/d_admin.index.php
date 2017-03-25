<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: d_admin.index.php

	if(!pLogged() OR !pCheckAdmin())
		die(pUrl('', true));
		
	pOut("<div class='header dictionary home wiki'><div class='title_header'><div class='header-icon'><i class='fa fa-book'></i></div> ".DA_TITLE."</div></div>");


	// datafields: name, surface, width, type, show-in-table, show-in-form
	$adminParse = (new pStructure("dictionary", "admin"))->load();

	// What are we getting?

	$currentSection = $adminParse[$_GET['section']];

	// Parsing, compiling and rendering of the admin page
	$adminParser = new pAdminParser($adminParse, $currentSection);
	$adminParser->compile();

	$donut['page']['title'] = "Admin panel - ".$donut['page']['title'];

	// If there is some action to do, we need to do that action, duh
	if(isset($_REQUEST['action']))
		goto action_page;

	// If there is an offset, we need to define that
	if(isset($_REQUEST['offset']))
		$adminParser->setOffset($_REQUEST['offset']);

	main_page:

	$adminParser->runData();
	$adminParser->render();

	action_page:
	if(isset($_REQUEST['action'])){
		$adminParser->compile();
		if(isset($_REQUEST['id']) AND !in_array($_REQUEST['action'], array('link-table')))
			$adminParser->runData($_REQUEST['id']);
		$adminParser->action($_REQUEST['action'], (boolean)isset($_REQUEST['ajax']), ((isset($_REQUEST['linked']) ? $_REQUEST['linked'] : null)));
	}


