<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: rulesheet.struct.php
	// The structure of the rule file system


$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-list',
			'default_permission' => -3,
		),
		'translate' => array(
			'section_key' => 'translate',
			'icon' => 'fa-language',
			'type' => 'pAssistantHandler',
			'template' => 'pTemplate',
			'table' => 'words',
			'surface' => "Batch Translating",
			'condition' => false,
			'disable_enter' => true,
			'items_per_page' => 20,
			'disable_pagination' => true,
			'actions_item' => array(
				
			),
			'actions_bar' => array(
				
			),
			'save_strings' => $saveStrings,
		),
			
	);