<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: dictionary.admin.struct.php
	// The structure of the dictionary admin panel

return array(
		'lexcat' => array(
			'type' => 'pTableObject',
			'section_key' => 'lexcat',
			'surface' => 'Lexical categories',
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'types',
			'datafields' => array(
				array('name', 'Category name', '40%', 'input', true, true),
				array('short_name', 'Abbrivation', '5%', 'input', true, true),
				array('inflect_classifications', 'Subinflections', '10%', 'boolean', true, true),
				array('inflect_not', 'Non-inflective', '20%', 'boolean', true, true),
			),
			'actions_item' => array(
				'edit' => array('edit', 'Edit category', 'fa-12 fa-pencil', 'actionbutton'),
			),
			'actions_bar' => array(
				'new' => array('new', 'Add category', 'fa-plus-circle fa-12', 'actionbutton'),
			),

		),
		'gramcat' => array(
			'type' => 'pTableObject',
			'section_key' => 'gramcat',
			'surface' => 'Grammatical categories',
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'classifications',
			'datafields' => array(
				array('name', 'Catergory name', '40%', 'input', true, true),
				array('short_name', 'Abbrivation', '20%', 'input', true, true),
			),
			'actions_item' => array(
				array('edit', 'Edit category', 'fa-12 fa-pencil', 'actionbutton'),
			),
			'actions_bar' => array(
				array('new', 'Add category', 'fa-plus-circle fa-12', 'actionbutton'),
			),

		),
	);