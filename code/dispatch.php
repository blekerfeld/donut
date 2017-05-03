<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dispatch.struct.php
	// The structure of donut's routing

/*

Menu item:

		array(
			'name' => 'Dictionary',
			'app' => 'home',
			'icon' => 'fa-book',
			'subitems' => array(
				array(
					'name' => '',
					'app' => '',
					'icon' => ''
				),
			),
		),

*/ 

return array(
	'MAGIC_MENU' => array(
		'default_permission' => 0,
		'items' => array(
			'home' => array(
				'name' => MMENU_DICTIONARY,
				'app' => 'home',
			),
			'dictionary-admin' => array(
				'name' => MMENU_SETTINGS,
				'app' => 'dictionary-admin',
				'permission' => -4,
				'class' => 'admin',
				'icon' => 'fa-cog',
				'subitems' => array(
					'dictionary-admin' => array(
						'name' => 'Dictionary',
						'icon' => 'fa-book',
						'app' => 'dictionary-admin',
						'permission' => -4,
					),
				),
			),
		),
	),
	'home' => array(
		'page_title' => CONFIG_SITE_TITLE,
		'default_section' => 'home',
		'arguments' => array(
		),
		'override_structure_type' => 'pSimpleStructure',
		'template' => 'pHomeTemplate',
		'metadata' => array(),
		'menu' => 'home',
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
		'menu' => 'dictionary-admin',
	),
	'entry' => array(
		'page_title' => 'Entry',
		'default_section' => 'lemma',
		'arguments' => array(
			1 => 'id',
			2 => 'action',
			3 => 'sub_action',
			4 => 'sub_id'
		),
		'menu' => '',
	),		
	'search' => array(
		'page_title' => 'Search',
		'default_section' => 'search',
		'arguments' => array(
			0 => 'section',
			1 => 'query',
		),
		'menu' => '',
	),	
	'docs' => array(
		'page_title' => 'Documentation',
		'default_section' => 'docs',
		'arguments' => array(
			0 => 'section',
		),
		'menu' => '',
	),	
	'auth' => array(
		'page_title' => 'Login',
		'default_section' => 'login',
		'arguments' => array(
			0 => 'section',
		),
		'menu' => '',
	)
);