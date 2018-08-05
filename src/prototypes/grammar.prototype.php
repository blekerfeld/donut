<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: rulesheet.struct.php
	// The structure of the rulesheet


$saveStrings = [null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK];
$action_remove = ['remove', DA_DELETE, 'fa-trash', 'ttip-sub', null, null];
$action_edit = ['edit', DA_EDIT, 'fa-edit', 'ttip-sub', null, null];

return [
		'MAGIC_META' => [
			'title' => DA_TITLE,
			'icon' => 'dna',
			'default_permission' => -3,
			'tabs' => new pTabBar(GRAMMAR_TITLE,'dna', true, 'wordsearch nomargin above'),
			'tab_search' => true,
			'tab_home' => true,
		],

		'wordclasses' => [
			'section_key' => 'wordclasses',
			'menu' => 'grammar',
			'icon' => 'source-commit-end-local',
			'type' => 'pTableHandler',
			'surface' => DA_LEXCAT_TITLE,
			'condition' => false,
			'is_admin' => true,
			'items_per_page' => 7,
			'disable_pagination' => false,
			'table' => 'types',
			'show_tab' => true,
			'tab_sub_items' => [
				[
					'section' => 'wordclasses',
					'icon' => 'source-commit-end-local',
					'surface' => DA_LEXCAT_TITLE,
					'href' => p::Url("?grammar/wordclasses"),
				],
				[
					'section' => 'classes',
					'icon' => 'source-branch',
					'surface' => DA_GRAMCAT_TITLE,
					'href' => p::Url("?grammar/classes"),
				],
				[
					'section' => 'tags',
					'icon' => 'source-merge',
					'surface' => DA_GRAMTAGS_TITLE,
					'href' => p::Url("?grammar/tags"),
				],
			],
			'tab_name' => 'Grammar units',
			'tab_sections' => ['wordclasses', 'classes', 'tags'],
			'datafields' => [
				new pDataField('name', DA_LEXCAT_NAME, 'auto', 'input', true, true, true, 'medium', false),
				new pDataField('short_name', DA_ABBR, 'auto', 'input', true, true, true, 'tooltip medium em', false),
				new pDataField('inflect', DA_LEXCAT_ENABLE_I, 'auto', 'boolean', true, true, true, '', false),
			],
			'actions_item' => [
				'edit' => $action_edit,
				'remove' => $action_remove,
			],
			'actions_bar' => [
				'new' => ['new', DA_LEXCAT_ADD, 'fa-plus-circle fa-12', 'btAction no-float small', null, null],
			],
			'save_strings' => $saveStrings,
		],
		'browser' => [
			'section_key' => 'browser',
			'icon' => 'fa-book',
			'type' => 'pSetHandler',
			'view' => 'pView',
			'table' => 'rulesets',
			'surface' => "Browse Rules",
			'condition' => false,
			'disable_enter' => true,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'editor' => 'grammar',
			'hitOn' => 'ruleset',
			'tab_sections' => ['context', 'inflection', 'ipageneration'],
			'sets' => [
				'inflection' => ['morphology', 'inflection', 'cards-variant'],
				'context' => ['phonology_contexts', 'context', 'altimeter'],
				'ipageneration' => ['phonology_ipa_generation', 'ipageneration', 'voice'],
			],
			'sets_strings' => [
				'inflection' => 'Inflective Rule',
				'context' => 'Phonological Context Rule',
				'ipageneration' => 'IPA Generation Rule',
			],
			'sets_name' => [
				'inflection' => 'name',
				'context' => 'name',
				'ipageneration' => 'name',
			],
			'sets_fields' => 'id, rule, name, ruleset',
			'actions_item' => [
				'edit' => ['edit', 'edit', 'fa-pencil', 'lemma-code discussion float-right', null, null, 'words', 'dictionary-admin', null, -3],
			],
			'actions_bar' => [
			],
			'save_strings' => $saveStrings,
			'show_tab' => true,
		],
		'tablesheet' => [
			'section_key' => 'tablesheet',
			'type' => 'pTablesheetHandler',
			'view' => 'pTablesheetView',
			'table' => 'types',
			'icon' => 'fa-font',
			'surface' => TS_TITLE_TAB,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [
			],
			'actions_bar' => [
			],
			'save_strings' => $saveStrings,
			'show_tab' => true,
		],
		'preview' => [
			'section_key' => 'preview',
			'type' => 'pTablesheetHandler',
			'view' => 'pTablesheetView',
			'table' => 'types',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [
			],
			'actions_bar' => [
			],
			'save_strings' => $saveStrings,
		],
		'classes' => [
			'section_key' => 'classes',
			'menu' => 'grammar',
			'icon' => 'source-branch',
			'type' => 'pTableHandler',
			'surface' => DA_GRAMCAT_TITLE,
			'condition' => false,
			'items_per_page' => 7,
			'is_admin' => true,
			'disable_pagination' => false,
			'table' => 'classifications',
			'datafields' => [
				new pDataField('name', DA_GRAMCAT_NAME, 'auto', 'input', true, true, true, 'medium', false),
				new pDataField('short_name', DA_ABBR, 'auto', 'input', true, true, true, 'tooltip medium em', false),
			],
			'actions_item' => [
				'edit' => $action_edit,
				'remove' => $action_remove,
			],
			'actions_bar' => [
				['new', 'Add category', 'fa-plus-circle fa-12', 'btAction no-float small', null, null],
			],
			'save_strings' => $saveStrings,
			'outgoing_links' => [
				
			],
			'incoming_links' => [
				
			],
		],

		'tags' => [
			'section_key' => 'tags',
			'icon' => 'source-merge',
			'menu' => 'grammar',
			'type' => 'pTableHandler',
			'surface' => DA_GRAMTAGS_TITLE,
			'condition' => false,
			'items_per_page' => 7,
			'is_admin' => true,
			'disable_pagination' => false,
			'table' => 'subclassifications',
			'datafields' => [
				new pDataField('name', DA_GRAMTAGS_NAME, 'auto', 'input', true, true, true, 'medium', false),
				new pDataField('short_name', DA_ABBR, 'auto', 'input', true, true, true, 'tooltip medium em', false),
			],
			'actions_item' => [
				'edit' => $action_edit,
				'remove' => $action_remove,
			],
			'actions_bar' => [
				['new', 'Add category', 'fa-plus-circle fa-12', 'btAction no-float small', null, null],
			],
			'save_strings' => $saveStrings,
			'outgoing_links' => [
				
			],
		],
		'inflection' => [
			'section_key' => 'inflection',
			'type' => 'pRulesheetHandler',
			'view' => 'pRulesheetView',
			'table' => 'morphology',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [
			],
			'actions_bar' => [
			],
			'save_strings' => $saveStrings,
		],
		'context' => [
			'section_key' => 'context',
			'type' => 'pRulesheetHandler',
			'view' => 'pRulesheetView',
			'table' => 'phonology_contexts',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [
			],
			'actions_bar' => [
			],
			'save_strings' => $saveStrings,
		],
		'ipageneration' => [
			'section_key' => 'ipageneration',
			'type' => 'pRulesheetHandler',
			'view' => 'pRulesheetView',
			'table' => 'phonology_ipa_generation',
			'icon' => 'fa-font',
			'surface' => "Inflection",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'actions_item' => [
			],
			'actions_bar' => [
			],
			'save_strings' => $saveStrings,
		],



	
	]; 