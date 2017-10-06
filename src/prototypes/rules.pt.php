<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      rulesheet.struct.php
	// The structure of the rule file system


$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'default_permission' => -3,
		),
		'browser' => array(
			'section_key' => 'browser',
			'icon' => 'fa-book',
			'type' => 'pSetHandler',
			'view' => 'pView',
			'table' => 'rulesets',
			'surface' => "Rules",
			'condition' => false,
			'disable_enter' => true,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'editor' => 'rulesheet',
			'hitOn' => 'ruleset',
			'sets' => array(
				'inflection' => array('morphology', 'inflection', 'cards-variant'),
				'context' => array('phonology_contexts', 'context', 'altimeter'),
				'ipa' => array('phonology_ipa_generation', 'ipa', 'voice'),
			),
			'sets_strings' => array(
				'inflection' => 'Inflective Rule',
				'context' => 'Phonological Context Rule',
				'ipa' => 'IPA Generation Rule',
			),
			'sets_name' => array(
				'inflection' => 'name',
				'context' => 'name',
				'ipa' => 'name',
			),
			'sets_fields' => 'id, rule, name, ruleset',
			'actions_item' => array(
				'edit' => array('edit', 'edit', 'fa-pencil', 'lemma-code discussion float-right', null, null, 'words', 'dictionary-admin', null, -3),
			),
			'actions_bar' => array(
				'edit' => new pAction('edit', 'edit', 'fa-pencil', 'lemma-code discussion float-right', null, null, 'words', 'dictionary-admin', null, -3),
				'remove' => new pAction('remove', 'remove', 'fa-times', 'lemma-code discussion float-right', null, null, 'words', 'dictionary-admin', null, -3),
			),
			'save_strings' => $saveStrings,
		),
			
	);