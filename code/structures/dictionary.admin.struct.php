<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: dictionary.admin.struct.php
	// The structure of the dictionary admin panel

$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

$action_remove = array('remove', DA_DELETE, 'fa-12 fa-times', 'actionbutton', null, null);

$action_edit = array('edit', DA_EDIT, 'fa-12 fa-pencil', 'actionbutton', null, null);


// Data fields array('name', 'surface', 'width', 'type', show-in-table, show-in-form, required, class),
// Action array: array('name', 'surface', 'icon', 'class', 'follow-up-tables', 'follow-up-field(s)')
return array(
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
				array('flag', DA_LANG_FLAG, '5%', 'flag', true, true, true, '', false),
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
			'icon' => 'fa-sitemap',
			'type' => 'pTableObject',
			'surface' => 'Lexical categories',
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'types',
			'datafields' => array(
				array('name', 'Category name', '40%', 'input', true, true, true, 'small-caps medium', false),
				array('short_name', 'Abbrivation', '5%', 'input', true, true, true, 'tooltip medium em', false),
				array('inflect_classifications', 'Subinflections', '10%', 'boolean', true, true, true, '', false),
				array('inflect_not', 'Non-inflective', '20%', 'boolean', true, true, true, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(),
			'incoming_links' => array(
				'gramcat' => array(
						'table' => 'classification_apply',
						'parent' => 'type_id',
						'child' => 'classification_id',
						'show_child' => 'name'
					),
			),
		),
		'gramcat' => array(
			'section_key' => 'gramcat',
			'icon' => 'fa-code-fork',
			'type' => 'pTableObject',
			'surface' => 'Grammatical categories',
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'classifications',
			'datafields' => array(
				array('name', 'Category name', '40%', 'input', true, true, true, '', false),
				array('short_name', 'Abbrivation', '20%', 'input', true, true, true, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array('lexcat'),
		),
	);