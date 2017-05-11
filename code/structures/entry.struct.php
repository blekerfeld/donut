<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dictionary.admin.struct.php
	// The structure of the dictionary admin panel

$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

// Datafields: __construct($name, $surface = '', $width= '20%', $type = '', $showTable = true, $showForm = true, $required = false, $class = '', $disableOnNull = false, $selection_values = null)
// Action array: array('name', 'surface', 'icon', 'class', 'follow-up-tables', 'follow-up-field(s)')
return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'default_permission' => 0,
		),
		'lemma' => array(
			'section_key' => 'lemma',
			'type' => 'pEntryHandler',
			'template' => 'pLemmaTemplate',
			'table' => 'words',
			// Beware: the information fields need to exitst in the structure's datafields array
			'entry_meta' => array(
				'title_field' => 'native',
				'parseAsObject' => 'pLemma',
				'information_fields' => array(
					'type_id', 'classification_id', 'subclassification_id',
				),
				'enable_categories' => false,
				'categories_table' => null,
				'categories_field' => 'word_id',
			),
			'permission' => 0,
			'icon' => 'fa-font',
			'id_as_hash' => true,
			'hash_app' => 'lemma',
			'surface' => DA_TITLE_WORDS,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'datafields' => array(
			),
			'actions_item' => array(
			),
			'actions_bar' => array(
				'discuss' => array('discuss', "Discuss", 'fa-comments', 'btAction', null, null, null),
				'edit' => new pAction('edit', 'Edit', 'fa-pencil', 'btAction', null, null, 'words', 'dictionary-admin', null, -3),
				'remove' => new pAction('remove', 'Delete item', 'fa-times', 'btAction red', null, null, 'words', 'dictionary-admin', null, -3),
			),
			'save_strings' => $saveStrings,
			'subobjects' => array(
				new pEntryDataModel('usage_notes', 'word_id', 'fa-info', false, null, 'usageNotes'),
				new pEntryDataModel('synonyms', array('word_id_1', 'word_id_2'), 'fa-clone', false, null, 'synonyms'),
				new pEntryDataModel('antonyms', array('word_id_1', 'word_id_2'), 'fa-venus-mars', false, null, 'antonyms'),
				new pEntryDataModel('homophones', array('word_id_1', 'word_id_2'), 'fa-microphone	', false, null, 'homophones'),
			),
		),

		'translation' => array(
			'section_key' => 'translation',
			'type' => 'pEntryHandler',
			'template' => 'pTranslationTemplate',
			'table' => 'translations',
			// Beware: the information fields need to exitst in the structure's datafields array
			'entry_meta' => array(
				'title_field' => 'translation',
				'parseAsObject' => 'pTranslation',
				'information_fields' => array(
					'type_id', 'classification_id', 'subclassification_id',
				),
				'enable_categories' => false,
				'categories_table' => null,
				'categories_field' => 'word_id',
			),
			'permission' => 0,
			'icon' => 'fa-font',
			'id_as_hash' => true,
			'hash_app' => 'lemma',
			'surface' => DA_TITLE_WORDS,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'datafields' => array(
			),
			'actions_item' => array(
			),
			'actions_bar' => array(
				'new' => array('new', DA_WORDS_NEW_EXTERN, 'fa-plus-circle fa-12', 'lemma-code discussion float-right', null, null, p::Url('?addword')),
			),
			'save_strings' => $saveStrings,
		),

		'example' => array(
			'section_key' => 'example',
			'type' => 'pEntryHandler',
			'template' => 'pExampleTemplate',
			'table' => 'idioms',
			// Beware: the information fields need to exitst in the structure's datafields array
			'entry_meta' => array(
				'title_field' => 'idiom',
				'parseAsObject' => 'pIdiom',
			),
			'permission' => 0,
			'icon' => 'fa-font',
			'id_as_hash' => true,
			'hash_app' => 'lemma',
			'surface' => DA_TITLE_WORDS,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'datafields' => array(
			),
			'actions_item' => array(
			),
			'actions_bar' => array(
				'new' => array('new', DA_WORDS_NEW_EXTERN, 'fa-plus-circle fa-12', 'lemma-code discussion float-right', null, null, p::Url('?addword')),
			),
			'save_strings' => $saveStrings,
		),

	);