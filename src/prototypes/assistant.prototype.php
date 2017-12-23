<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: rulesheet.struct.php
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
			'permission' => -3,
			'icon' => 'fa-circle-o',
			'type' => 'pAssistantHandler',
			'view' => 'pView',
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
			'permission' => -3,
			'icon' => new pIcon('translate', 24),
			'type' => 'pAssistantHandler',
			'view' => 'pView',
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