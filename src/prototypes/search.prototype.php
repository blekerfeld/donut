<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: dictionary.admin.struct.php
//            The structure of the dictionary admin panel

$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

// Datafields: __construct($name, $surface = '', $width= '20%', $type = '', $showTable = true, $showForm = true, $required = false, $class = '', $disableOnNull = false, $selection_values = null)
// Action array: array('name', 'surface', 'icon', 'class', 'follow-up-tables', 'follow-up-field(s)')
return array(
		'MAGIC_META' => array(
			'title' => DA_TITLE,
			'icon' => 'fa-book',
			'default_permission' => 0,
		),
		'search' => array(
			'section_key' => 'search',
			'type' => 'pSearchHandler',
			'view' => 'pLemmaView',
			'table' => 'words',
			// Beware: the information fields need to exitst in the structure's datafields array
			'entry_meta' => array(
				'title_field' => 'native',
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
			'actions_item' => array(
			),
			'actions_bar' => array(
				'new' => array('new', DA_WORDS_NEW_EXTERN, 'fa-plus-circle fa-12', 'btAction wikiEdit', null, null, p::Url('?addword')),
			),
			'save_strings' => $saveStrings,
			'subobjects' => array(
				new pEntryDataModel("Usage notes", 'usage_notes', 'word_id', false, null, 'pLemmaView'),
			),
		),

	);