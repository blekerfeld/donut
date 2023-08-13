<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: rulesheet.struct.php
	// The structure of the rulesheet


$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'default_permission' => -3,
		),
		'lemma' => array(
			'section_key' => 'lemma',
			'type' => 'pEditorHandler',
			'view' => 'pEditorView',
			'table' => 'words',
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
		'translation' => array(
			'section_key' => 'translation',
			'type' => 'pLemmasheetHandler',
			'view' => 'pLemmasheetView',
			'table' => 'translations',
			'disable_enter' => true,
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => array(
			),
			'actions_bar' => array(
				'edit' => new pAction('edit', 'edit', 'fa-pencil', 'lemma-code discussion float-right', null, null, 'words', 'dictionary-admin', null, -3),
				'remove' => new pAction('remove', 'remove', 'fa-times', 'lemma-code discussion float-right', null, null, 'words', 'dictionary-admin', null, -3),
			),
			'save_strings' => $saveStrings,
		)
	
	);