<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: rulesheet.struct.php
	// The structure of the rulesheet


$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'default_permission' => -3,
		),
		'inflection' => array(
			'section_key' => 'inflection',
			'type' => 'pTablesheetHandler',
			'template' => 'pTableTemplate',
			'table' => 'modes',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => array(
			),
			'actions_bar' => array(
			),
			'save_strings' => $saveStrings,
		),

	
	);