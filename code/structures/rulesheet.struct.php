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
		'dataModel' => 'pRuleDataModel',
		'inflections' => array(
			'section_key' => 'inflections',
			'type' => 'pRulesheetHandler',
			'template' => 'pRulesheetTemplate',
			'table' => 'morphology',
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
		),
		'phonology' => array(
			'section_key' => 'phonology',
			'type' => 'pRulesheetHandler',
			'template' => 'pRulesheetTemplate',
			'table' => 'phonology_contexts',
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
		),

	
	);