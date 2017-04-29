<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dispatch.struct.php
	// The structure of donut's routing


return array(
	'home' => array(
		'page_title' => CONFIG_SITE_TITLE,
		'default_section' => 'home',
		'arguments' => array(
		),
	),
	'dictionary-admin' => array(
		'page_title' => 'Admin panel',
		'default_section' => 'lexcat',
		'arguments' => array(
			0 => 'section',
			1 => 'action',
			2 => 'id',
			3 => 'linked',
		),
	),
	'entry' => array(
		'page_title' => 'Entry',
		'default_section' => 'lemma',
		'arguments' => array(
			1 => 'id',
			2 => 'action',
		),
	),		
	'search' => array(
		'page_title' => 'Search',
		'default_section' => 'search',
		'arguments' => array(
			0 => 'section',
			1 => 'query',
		),
	),	
	'docs' => array(
		'page_title' => 'Documentation',
		'default_section' => 'docs',
		'arguments' => array(
			0 => 'section',
		),
	),	
	'auth' => array(
		'page_title' => 'Login',
		'default_section' => 'login',
		'arguments' => array(
			0 => 'section',
		),
	)
);