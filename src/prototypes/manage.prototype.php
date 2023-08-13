<?php
// Donut: open source dictionary toolkit
// version    0.12-dev
// author     Emma de Roo
// license    MIT
// file:      dictionary.admin.struct.php
	// The structure of the dictionary admin panel

$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

$action_remove = array('remove', DA_DELETE, 'fa-trash', 'ttip-sub', null, null);

$action_edit = array('edit', DA_EDIT, 'fa-edit', 'ttip-sub', null, null);


// Datafields: __construct($name, $surface = '', $width= '20%', $type = '', $showTable = true, $showForm = true, $required = false, $class = '', $disableOnNull = false, $selection_values = null)
// Action array: array('name', 'surface', 'icon', 'class', 'follow-up-tables', 'follow-up-field(s)')
return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'other_apps' => array(
				array(
					'app' => 'wiki-admin',
					'icon' => 'local-library',
					'surface' => 'Wiki settings'
				),
				array(
					'app' => 'pages-admin',
					'icon' => 'fa-files-o',
					'surface' => 'Pages'
				),
				array(
					'app' => 'general-admin',
					'icon' => 'fa-cog',
					'surface' => 'General settings'
				),
			),
			'default_permission' => -4,
		),
		'MAGIC_MENU' => array(
			'config' => array(
				'icon' => 'fa-cog',
				'surface' => DA_CONFIG_SURFACE,
				'section' => 'config'
			),

			'languages' => array(
				'icon' => 'translate',
				'surface' => DA_TITLE_LANGUAGES,
				'section' => 'languages'
			),

			'ortography' => array(
				'icon' => 'sort-alphabetical',
				'surface' => DA_TITLE_ORTOGRAPHY,
				'section' => 'graphemes'
			),
		),
		'config' => array(
			'section_key' => 'config',
			'icon' => 'fa-cog',
			'type' => 'pTableHandler',
			'surface' => DA_CONFIG_SURFACE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'config',
			'datafields' => array(
				new pDataField('SETTING_NAME', DA_CONFIG_NAME, '40%', 'hidden-show', true, true, true),
				new pDataField('SETTING_VALUE', DA_CONFIG_VALUE, '40%', 'input', true, true, true),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle', 'btAction no-float small', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(),
		),
		'languages' => array(
			'section_key' => 'languages',
			'icon' => 'fa-language',
			'type' => 'pTableHandler',
			'surface' => DA_LANG_SURFACE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'languages',
			'datafields' => array(
				new pDataField('name', DA_LANG_NAME, '40%', 'input', true, true, true),
				new pDataField('showname', DA_LANG_SHOWNAME, '40%', 'input', true, true, true),
				new pDataField('flag', DA_LANG_FLAG, '5%', 'flag', true, true, false, '', false),
				new pDataField('activated', DA_LANG_ACTIVATED, '10%', 'boolean', true, true, true, '', true),
				new pDataField('locale', DA_LANG_LOCALE, '10%', 'input', true, true, false, '', false),
				new pDataField('color', DA_LANG_COLOR, '10%', 'color', true, true, false, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle', 'btAction no-float small', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(),
		),
		// Ortography section
		'graphemes' => array(
			'section_key' => 'graphemes',
			'icon' => 'fa-hashtag',
			'menu' => 'ortography',
			'type' => 'pTableHandler',
			'surface' => "Graphemes",
			'condition' => false,
			'items_per_page' => 30,
			'disable_pagination' => false,
			'table' => 'graphemes',
			'datafields' => array(
				new pDataField('grapheme', 'Lowercase', '40%', 'input', true, true, true, '', false),
				new pDataField('uppercase', 'Uppercase', '40%', 'input', true, true, false, '', false),
				new pDataField('in_alphabet', 'In alphabet', '20%', 'boolean', true, true, true, 'tooltip medium em', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction no-float small', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
			),
		),

		'lemma' => array(
			'section_key' => 'lemma',
			'type' => 'pLemmasheetHandler',
			'view' => 'pLemmasheetView',
			'table' => 'words',
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