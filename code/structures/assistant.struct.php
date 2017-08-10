<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: rulesheet.struct.php
	// The structure of the rule file system


$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-list',
			'default_permission' => -3,
		),
		'default' => array(
			'section_key' => 'default',
			'icon' => 'fa-circle-o',
			'type' => 'pAssistantHandler',
			'template' => 'pTemplate',
			'table' => 'config',
			'surface' => "",
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
		'translate' => array(
			'section_key' => 'translate',
			'icon' => new pIcon('translate', 24),
			'type' => 'pAssistantHandler',
			'template' => 'pTemplate',
			'table' => 'words',
			'surface' => BATCH_TRANSLATE_LONG,
			'desc' => BATCH_TRANSLATE_DESC,
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