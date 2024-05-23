<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: Guide.php
	// The structure of donut's routing

return [
	'MAGIC_MENU' => [
		'default_permission' => 0,
		'items' => [
			'home' => [
				'name' => (new pIcon('fa-book', 12))." ".MMENU_DICTIONARY,
				'app' => 'home',
				'class' => 'ssignore',
			],
			'wiki' => [
				'name' => (new pIcon('library'))." ".WIKI_TITLE_MENU,
				'class' => '',	
				'subitems' => [
					'home' => [
						'name' => 'Home',
						'icon' => 'fa-home',
						'app' => 'wiki/article/home'
					],
				],
			],
			'dictionary-admin' => [
				'name' => (new pIcon('fa-bars'))." ".MMENU_EDITORMENU,
				'permission' => -4,
				'class' => 'admin',	
				'subitems' => [
					'editor' => [
						'name' => 'New lemma entry',
						'icon' => 'fa-edit',
						'app' => 'editor/new',
						'permission' => -3,
					],
					'grammar' => [
						'name' => GRAMMAR_TITLE,
						'icon' => 'dna',
						'app' => 'grammar',
						'permission' => -3,
					],
					'assistant' => [
						'name' => 'Assistant',
						'icon' => 'assistant',
						'app' => 'assistant',
						'permission' => -2,
					],
					'terminal' => [
						'name' => 'Terminal',
						'icon' => 'fa-terminal',
						'app' => 'terminal',
						'permission' => -4,
					],
					'dictionary-admin' => [
						'name' => DA_TITLE,
						'icon' => 'tune',
						'app' => 'manage/config',
						'permission' => -4,
					],
				],
			],

		],
		'simple_links' => [

		],
	],
	'home' => [
		'page_title' => 'Home',
		'default_section' => 'home',
		'arguments' => [
		],
		'override_structure_type' => 'pSimpleStructure',
		'permission' => 999,
		'view' => 'pHomeView',
		'metadata' => [],
		'menu' => 'home',
	],
	

	'manage' => [
		'page_title' => 'Admin panel',
		'default_section' => 'config',
		'override_structure_type' => 'pAdminStructure',
		'arguments' => [
			0 => 'section',
			1 => 'action',
			2 => 'id',
			3 => 'linked',
		],
		'menu' => 'dictionary-admin',
	],


	'wiki' => [
		'page_title' => WIKI_TITLE_MENU,
		'default_section' => 'article',
		'arguments' => [
			0 => 'url',
			1 => 'language',
			2 => 'revision',
			3 => 'action'
		],
		'menu' => 'wiki',
	],


	'grammar' => [
		'page_title' => 'Grammar',
		'default_section' => 'wordclasses',
		'arguments' => [
			0 => 'action',
			1 => 'id',
			2 => 'table_id',
		],
		'menu' => 'dictionary-admin',
	],


	'editor' => [
		'page_title' => 'Lemma editor',
		'default_section' => 'lemma',
		'arguments' => [
			0 => 'action',
			1 => 'id',
			2 => 'linked',
		],
		'menu' => 'dictionary-admin',
		'override_structure_type' => 'pEditorStructure',
	],


	'entry' => [
		'page_title' => 'Entry',
		'default_section' => 'lemma',
		'arguments' => [
			0 => 'id',
			1 => 'action',
			2 => 'sub_action',
			3 => 'sub_id'
		],
		'menu' => 'home',
	],		
	
	'search' => [
		'page_title' => 'Search',
		'default_section' => 'search',
		'arguments' => [
			0 => 'section',
			1 => 'query',
		],
		'menu' => '',
	],	

	'docs' => [
		'page_title' => 'Documentation',
		'default_section' => 'docs',
		'arguments' => [
			0 => 'section',
		],
		'menu' => '',
	],	

	'auth' => [
		'page_title' => 'Login',
		'default_section' => 'login',
		'arguments' => [
			0 => 'section',
			1 => 'id',
		],
		'menu' => '',
	],

	'assistant' => [
		'page_title' => 'Assistant',
		'default_section' => 'default',
		'arguments' => [
			0 => 'section',
			1 => 'action',
			2 => 'id',
		],
		'menu' => 'dictionary-admin',
	],

	'thread' => [
		'page_title' => 'Thread',
		'default_section' => 'tread',
		'arguments' => [
			0 => 'section',
			1 => 'action',
			2 => 'id',
			3 => 'thread_id',
		],
		'menu' => '',
	],

	'terminal' => [
		'page_title' => CONFIG_SITE_TITLE,
		'default_section' => 'terminal',
		'arguments' => [
		],
		'override_structure_type' => 'pSimpleStructure',
		'permission' => -4,
		'view' => 'pTerminalView',
		'metadata' => [],
		'menu' => 'dictionary-admin',
	],

	'generate' => [
		'page_title' => CONFIG_SITE_TITLE,
		'default_section' => 'terminal',
		'arguments' => [
		],
		'override_structure_type' => 'pSimpleStructure',
		'permission' => -4,
		'view' => 'pGenerateView',
		'metadata' => [
			'default_permission' => -4,
		],
		'menu' => 'dictionary-admin',
	],

	'about' => [
		'page_title' => 'About',
		'default_section' => 'about',
		'arguments' => [
		],
		'override_structure_type' => 'pSimpleStructure',
		'permission' => 999,
		'view' => 'pInternView',
		'view_function' => 'about',
		'metadata' => [],
		'menu' => 'home',
	],

	'MAGIC_MARKDOWN' => [
		'DIS' => [
			'app' => 'docs',
			'url' => 'DIS'
		],
	],

	'MAGIC_MARKDOWN_TITLES' => [
		'DIS' => 'Inflection Syntax',
	],

	'MAGIC_MARKDOWN_APPS' => [
		'docs' => [
			'name' => 'Documentation',
			'icon' => 'fa-question-circle'
		],
	], 
];