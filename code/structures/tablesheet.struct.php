<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: rulesheet.struct.php
	// The structure of the rulesheet


$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'default_permission' => -3,
		),
		'tablesheet' => array(
			'section_key' => 'tablesheet',
			'type' => 'pTablesheetHandler',
			'template' => 'pTablesheetTemplate',
			'table' => 'types',
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