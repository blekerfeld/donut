<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: dictionary.admin.struct.php
	// The structure of the dictionary admin panel

$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

$action_remove = array('remove', DA_DELETE, 'fa-12 fa-times', 'btAction no-float', null, null);

$action_edit = array('edit', DA_EDIT, 'fa-12 fa-pencil', 'btAction no-float', null, null);


// Data fields array('name', 'surface', 'width', 'type', show-in-table, show-in-form, required, class),
// Action array: array('name', 'surface', 'icon', 'class', 'follow-up-tables', 'follow-up-field(s)')
return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
		),
		'MAGIC_MENU' => array(
			'home' => array(
				'icon' => 'fa-home',
				'surface' => 'Home',
				'section' => 'home'
			),
			'content' => array(
				'icon' => 'fa-book',
				'surface' => 'Content',
				'section' => 'words',
			),
			'grammar' => array(
				'icon' => 'fa-rocket',
				'surface' => 'Grammar',
				'section' => 'lexcat',
			),
			'languages' => array(
				'icon' => 'fa-language',
				'surface' => 'Languages',
				'section' => 'languages'
			),
		),

		'words' => array(
			'section_key' => 'words',
			'icon' => 'fa-font',
			'menu' => 'content',
			'type' => 'pTableObject',
			'surface' => "Words",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'words',
			'datafields' => array(
				array('native', "Native", '40%', 'input', true, true, true, '', false),
				array('ipa', "Ipa", '5%', 'input', true, true, false, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(),
			'incoming_links' => array(
				'examples' => array(
						'table' => 'idiom_words',
						'parent' => 'word_id',
						'child' => 'idiom_id',
						'show_parent' => 'idiom',
						'show_child' => 'native',
					),
			),
		),


		'examples' => array(
			'section_key' => 'examples',
			'icon' => 'fa-quote-right',
			'menu' => 'content',
			'type' => 'pTableObject',
			'surface' => "Examples",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'idioms',
			'datafields' => array(
				array('idiom', "Example", '60%', 'input', true, true, true, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
				'words' => array(
					'section' => 'words', 
					'surface' => "Words", 
					'icon' => 'fa fa-font', 
					'table'=> 'idiom_words', 
					'field' => 'idiom_id'
				),
			),
		),



		'languages' => array(
			'section_key' => 'languages',
			'icon' => 'fa-language',
			'type' => 'pTableObject',
			'surface' => DA_LANG_SURFACE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'languages',
			'datafields' => array(
				array('name', DA_LANG_NAME, '40%', 'input', true, true, true, '', false),
				array('flag', DA_LANG_FLAG, '5%', 'flag', true, true, false, '', false),
				array('activated', DA_LANG_ACTIVATED, '10%', 'boolean', true, true, true, '', true),
				array('locale', DA_LANG_LOCALE, '10%', 'input', true, true, false, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(),
		),
		'lexcat' => array(
			'section_key' => 'lexcat',
			'menu' => 'grammar',
			'icon' => 'fa-sitemap',
			'type' => 'pTableObject',
			'surface' => DA_LEXCAT_TITLE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'types',
			'datafields' => array(
				array('name', 'Category name', '20%', 'input', true, true, true, 'small-caps medium', false),
				array('short_name', DA_ABBR, '5%', 'input', true, true, true, 'tooltip medium em', false),
				array('inflect_classifications', DA_SUBINFLECTIONS, '2%', 'boolean', true, true, true, '', false),
				array('inflect_not', 'Non-inflective', '2%', 'boolean', true, true, true, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
					'gramcat' => array(
						'section' => 'gramcat', 
						'surface' => DA_GRAMCAT_TITLE, 
						'icon' => 'fa fa-code-fork', 
						'table'=> 'classification_apply', 
						'field' => 'type_id'
					)
				),
			'incoming_links' => array(
				'gramcat' => array(
						'table' => 'classification_apply',
						'parent' => 'type_id',
						'child' => 'classification_id',
						'show_parent' => 'name',
						'show_child' => 'name',
					),
			),
		),
		'gramcat' => array(
			'section_key' => 'gramcat',
			'menu' => 'grammar',
			'icon' => 'fa-code-fork',
			'type' => 'pTableObject',
			'surface' => DA_GRAMCAT_TITLE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'classifications',
			'datafields' => array(
				array('name', 'Category name', '40%', 'input', true, true, true, 'small-caps medium', false),
				array('short_name', 'Abbrivation', '20%', 'input', true, true, true, 'tooltip medium em', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
				'lexcat' => array(
					'section' => 'lexcat', 
					'surface' => DA_LEXCAT_TITLE, 
					'icon' => 'fa fa-sitemap', 
					'table'=> 'classification_apply', 
					'field' => 'classification_id'
				),
			),
			'incoming_links' => array(
				'lexcat' => array(
						'table' => 'classification_apply',
						'parent' => 'classification_id',
						'child' => 'type_id',
						'show_parent' => 'name',
						'show_child' => 'name',
					),
				'gramtags' => array(
						'table' => 'subclassification_apply',
						'parent' => 'classification_id',
						'child' => 'subclassification_id',
						'show_parent' => 'name',
						'show_child' => 'name',
				),
			),
		),
		'gramtags' => array(
			'section_key' => 'gramtags',
			'icon' => 'fa-tags',
			'menu' => 'grammar',
			'type' => 'pTableObject',
			'surface' => DA_GRAMTAGS_TITLE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'subclassifications',
			'datafields' => array(
				array('name', 'Tag name', '40%', 'input', true, true, true, 'small-caps medium', false),
				array('short_name', 'Abbrivation', '20%', 'input', true, true, true, 'tooltip medium em', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
				'gramcat' => array(
					'section' => 'gramcat', 
					'surface' => DA_GRAMCAT_TITLE, 
					'icon' => 'fa fa-code-fork', 
					'table'=> 'subclassification_apply', 
					'field' => 'subclassification_id'
				),
			),
		),
	);