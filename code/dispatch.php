<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      dispatch.struct.php
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
				'name' => (new pIcon('fa-book', 12))." ".MMENU_DICTIONARY,
				'app' => 'home',
				'class' => 'ssignore',
			),
			'dictionary-admin' => array(
				'name' => MMENU_EDITORMENU." ".(new pIcon('chevron-down')),
				'permission' => -4,
				'class' => '',	
				'subitems' => array(
					'editor' => array(
						'name' => 'New lemma entry',
						'icon' => 'fa-pencil',
						'app' => 'editor/new',
						'permission' => -3,
					),
					'assistant' => array(
						'name' => 'Assistant',
						'icon' => 'assistant',
						'app' => 'assistant',
						'permission' => -2,
					),
					'rulesheet' => array(
						'name' => 'Grammar',
						'icon' => 'dna',
						'app' => 'grammar/browser',
						'permission' => -4,
					),
					'terminal' => array(
						'name' => 'Terminal',
						'icon' => 'fa-terminal',
						'app' => 'terminal',
						'permission' => -4,
					),
					'dictionary-admin' => array(
						'name' => 'Management panel',
						'icon' => 'tune',
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
		'permission' => 999,
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

	'grammar' => array(
		'page_title' => 'tablesheet',
		'default_section' => 'tablesheet',
		'arguments' => array(
			0 => 'action',
			1 => 'id',
			2 => 'table_id',
		),
		'menu' => 'dictionary-admin',
		'override_structure_type' => 'pTablesheetStructure',
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
		'default_section' => 'default',
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
		'permission' => -4,
		'template' => 'pTerminalTemplate',
		'metadata' => array(),
		'menu' => 'dictionary-admin',
	),

	'generate' => array(
		'page_title' => CONFIG_SITE_TITLE,
		'default_section' => 'terminal',
		'arguments' => array(
		),
		'override_structure_type' => 'pSimpleStructure',
		'permission' => -4,
		'template' => 'pGenerateTemplate',
		'metadata' => array(
			'default_permission' => -4,
		),
		'menu' => 'dictionary-admin',
	),
);