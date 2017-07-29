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
				'name' => MMENU_EDITORMENU." ".(new pIcon('fa-caret-down', 10)),
				'app' => 'dictionary-admin',
				'permission' => -4,
				'class' => '',	
				'subitems' => array(
					'editor' => array(
						'name' => 'New Lemma',
						'icon' => 'fa-plus-circle',
						'app' => 'editor/new',
						'permission' => -3,
					),
					'assistant' => array(
						'name' => 'Translation assistant',
						'icon' => 'fa-magic',
						'app' => 'assistant/translate',
						'permission' => -2,
					),
					'rulesheet' => array(
						'name' => 'Rules',
						'icon' => 'fa-book',
						'app' => 'rules',
						'permission' => -4,
					),
					'terminal' => array(
						'name' => 'Terminal',
						'icon' => 'fa-terminal',
						'app' => 'terminal',
						'permission' => -4,
					),
					'dictionary-admin' => array(
						'name' => 'Dictionary settings',
						'icon' => 'fa-cog',
						'app' => 'dictionary-admin',
						'permission' => -4,
					),
				),
			),
		),
		'simple_links' => array(

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

	'rulesheet' => array(
		'page_title' => 'rulesheet',
		'default_section' => 'inflection',
		'arguments' => array(
			0 => 'action',
			1 => 'id',
		),
		'menu' => 'dictionary-admin',
	),


	'editor' => array(
		'page_title' => 'Lemma editor',
		'default_section' => 'lemma',
		'arguments' => array(
			0 => 'action',
			1 => 'id',
			2 => 'linked',
		),
		'menu' => 'dictionary-admin',
		'override_structure_type' => 'pLemmasheetStructure',
	),

	'rules' => array(
		'page_title' => 'Rules',
		'default_section' => 'list',
		'arguments' => array(
			0 => 'action',
			1 => 'id',
		),
		'menu' => 'dictionary-admin',
	),



	'entry' => array(
		'page_title' => 'Entry',
		'default_section' => 'lemma',
		'arguments' => array(
			0 => 'id',
			1 => 'action',
			2 => 'sub_action',
			3 => 'sub_id'
		),
		'menu' => 'home',
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
			1 => 'id',
		),
		'menu' => '',
	),

	'assistant' => array(
		'page_title' => 'Assistant',
		'default_section' => 'translate',
		'arguments' => array(
			0 => 'section',
			1 => 'action',
			2 => 'id',
		),
		'menu' => 'dictionary-admin',
	),

	'thread' => array(
		'page_title' => 'Thread',
		'default_section' => 'tread',
		'arguments' => array(
			0 => 'section',
			1 => 'action',
			2 => 'id',
			3 => 'thread_id',
		),
		'menu' => '',
	),

	'terminal' => array(
		'page_title' => CONFIG_SITE_TITLE,
		'default_section' => 'terminal',
		'arguments' => array(
		),
		'override_structure_type' => 'pSimpleStructure',
		'template' => 'pTerminalTemplate',
		'metadata' => array(),
		'menu' => 'dictionary-admin',
	),
);