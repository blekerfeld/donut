<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: dictionary.admin.struct.php
	// The structure of the dictionary admin panel

$saveStrings = array(null, SAVE, SAVING, SAVED_EMPTY, SAVED_ERROR, SAVED, SAVE_LINKBACK);

$action_remove = array('remove', DA_DELETE, 'fa-trash', 'ttip-sub', null, null);

$action_edit = array('edit', DA_EDIT, 'fa-pencil', 'ttip-sub', null, null);


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
			'home' => array(
				'icon' => 'home',
				'surface' => DA_TITLE_HOME,
				'section' => 'home'
			),
			'content' => array(
				'icon' => 'library-books',
				'surface' => DA_TITLE_CONTENT,
				'section' => 'words',
			),
			'grammar' => array(
				'icon' => 'puzzle',
				'surface' => DA_TITLE_GRAMMAR,
				'section' => 'lexcat',
			),
			'table' => array(
				'icon' => 'table',
				'surface' => DA_TITLE_TABLES,
				'section' => 'headings'
			),
			'languages' => array(
				'icon' => 'translate',
				'surface' => DA_TITLE_LANGUAGES,
				'section' => 'languages'
			),
			'phonology' => array(
				'icon' => 'voice',
				'surface' => DA_TITLE_PHONOLOGY,
				'section' => 'phonology'
			),
			'ortography' => array(
				'icon' => 'sort-alphabetical',
				'surface' => DA_TITLE_ORTOGRAPHY,
				'section' => 'graphemes'
			),
		),

		'words' => array(
			'section_key' => 'words',
			'permission' => -1,
			'icon' => 'fa-font',
			'id_as_hash' => true,
			'hash_app' => 'entry',
			'menu' => 'content',
			'type' => 'pTableHandler',
			'surface' => DA_TITLE_WORDS,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'words',
			'datafields' => array(
				new pDataField('native', DA_WORDS_NATIVE, '15%', 'input', true, true, true),
				new pDataField('lexical_form', DA_WORDS_LEXICAL, '10%', 'input', true, true, false),
				new pDataField('ipa', DA_WORDS_IPA, '5%', 'input', true, true, true, 'small'),
				new pDataField('type_id', DA_LEXCAT, '10%', 'select', true, true, true, 'small-caps', false, new pSelector('types', null, 'name', true, 'lexcat')),
				new pDataField('classification_id', DA_GRAMCAT, '10%', 'select', true, true, true, 'small-caps xxxsmall', false, new pSelector('classifications', null, 'name', true, 'gramcat')),
				new pDataField('subclassification_id', DA_GRAMTAG, '10%', 'select', true, true, false, 'small-caps', false, new pSelector('subclassifications', null, 'name', true, 'gramtags')),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_WORDS_NEW_EXTERN, 'fa-plus-circle fa-12', 'btAction no-float small', null, null, 
				),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
					'examples' => array(
						'section' => 'examples', 
						'surface' => "Examples", 
						'icon' => 'fa-quote-right', 
						'table'=> 'idiom_words', 
						'field' => 'word_id'
					),
					'synonyms' => array(
						'section' => 'words', 
						'surface' => "Synonyms", 
						'icon' => 'fa-clone', 
						'table'=> 'synonyms', 
						'field' => 'word_id_2',
						'double_parent' => 'word_id_1',
						),
					'antonyms' => array(
					 		'section' => 'words', 
					 		'surface' => "Antonyms", 
					 		'icon' => 'fa-venus-mars', 
					 		'table'=> 'antonyms', 
					 		'field' => 'word_id_2',
					 		'double_parent' => 'word_id_1'
					 ),
					'homophones' => array(
					 		'section' => 'words', 
					 		'surface' => "Homephones", 
					 		'icon' => 'box-shadow', 
					 		'table'=> 'homophones', 
					 		'field' => 'word_id_2',
					 		'double_parent' => 'word_id_1'
					 ),
					'translations' => array(
						'section' => 'translations', 
						'surface' => "Translations", 
						'icon' => 'fa-language', 
						'table'=> 'translation_words', 
						'field' => 'word_id'
					),
				),
			'incoming_links' => array(
				'translations' => array(
						'section' => 'translations',
						'table' => 'translation_words',
						'parent' => 'word_id',
						'child' => 'translation_id',
						'fields' => array(
								(new pDataField('specification', DA_TABLE_LINK_SPECIFICATION)),
							),
						'show_parent' => 'translation',
						'show_child' => 'native',
				),
				'etymo' => array(
						'section' => 'words',
						'table' => 'etymology',
						'parent' => 'id',
						'child' => 'desc',
						'show_parent' => 'desc',
						'show_child' => 'id',
				),
				'synonyms' => array(
						'section' => 'words',
						'table' => 'synonyms',
						'parent' => 'word_id_1',
						'child' => 'word_id_2',
						'show_parent' => 'native',
						'show_child' => 'native',
						'fields' => array(
								(new pDataField('score', "Score")),
						),
						'double_parent' => true,
				),
				'antonyms' => array(
						'section' => 'words',
						'table' => 'antonyms',
						'parent' => 'word_id_1',
						'child' => 'word_id_2',
						'show_parent' => 'native',
						'show_child' => 'native',
						'fields' => array(
								(new pDataField('score', "Score")),
						),
						'double_parent' => true,
				),
				'homophones' => array(
						'section' => 'words',
						'table' => 'homophones',
						'parent' => 'word_id_1',
						'child' => 'word_id_2',
						'show_parent' => 'native',
						'show_child' => 'native',
						'double_parent' => true,
				),
			),
		),

		'translations' => array(
			'section_key' => 'translations',
			'permission' => -1,
			'icon' => 'fa-globe',
			'id_as_hash' => true,
			'hash_app' => 'entry/translation',
			'menu' => 'content',
			'type' => 'pTableHandler',
			'surface' => DA_TRANSLATIONS,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'translations',
			'datafields' => array(
				new pDataField('translation', DA_TRANSLATION, '20%', 'input', true, true, true),
				new pDataField('language_id', DA_LANG_1, '5%', 'select', true, true, true, 'small-caps', false, new pSelector('languages', null, 'name', true, 'languages')),
				new pDataField('description', TRANSLATION_DESC, '40%', 'markdown', true, true, false)
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', "New translations", 'fa-plus-circle fa-12', 'btAction no-float small', null, null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
					'words' => array(
					 		'section' => 'words', 
					 		'surface' => "Words", 
					 		'icon' => 'fa-font', 
					 		'table'=> 'translation_words', 
					 		'field' => 'translation_id'
					 ),
				),
			'incoming_links' => array(
				'words' => array(
						'section' => 'words',
						'table' => 'translation_words',
						'parent' => 'translation_id',
						'child' => 'word_id',
						'fields' => array(
								(new pDataField('specification', DA_TABLE_LINK_SPECIFICATION)),
							),
						'show_parent' => 'native',
						'show_child' => 'translation',
				),
			),
		),

		'etymo' => array(
			'section_key' => 'etymo',
			'icon' => 'source-merge',
			'menu' => 'content',
			'type' => 'pTableHandler',
			'surface' => "Etymology",
			'condition' => false,
			'items_per_page' => 5,
			'disable_pagination' => false,
			'table' => 'etymology',
			'datafields' => array(

			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle fa-12', 'btAction no-float small', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
				'words' => array(
					'section' => 'words', 
					'surface' => "Words", 
					'icon' => 'fa-font', 
					'table'=> 'idiom_words', 
					'field' => 'idiom_id'
				),
			),
			'incoming_links' => array(
				'words' => array(
						'section' => 'examples',
						'table' => 'idiom_words',
						'parent' => 'idiom_id',
						'child' => 'word_id',
						'fields' => array(
								(new pDataField('keyword', DA_TABLE_LINK_KEYWORD)),
							),
						'show_parent' => 'native',
						'show_child' => 'idiom',
				),
			)
		),


		'examples' => array(
			'section_key' => 'examples',
			'icon' => 'fa-quote-right',
			'menu' => 'content',
			'type' => 'pTableHandler',
			'surface' => DA_TITLE_EXAMPLES,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'idioms',
			'datafields' => array(
				new pDataField('idiom', 'Example', '60%', 'input', true, true, true),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', DA_LANG_NEW, 'fa-plus-circle fa-12', 'btAction no-float small', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
				'words' => array(
					'section' => 'words', 
					'surface' => "Words", 
					'icon' => 'fa-font', 
					'table'=> 'idiom_words', 
					'field' => 'idiom_id'
				),
			),
			'incoming_links' => array(
				'words' => array(
						'section' => 'words',
						'table' => 'idiom_words',
						'parent' => 'idiom_id',
						'child' => 'word_id',
						'fields' => array(
								(new pDataField('keyword', DA_TABLE_LINK_KEYWORD)),
							),
						'show_parent' => 'native',
						'show_child' => 'idiom',
				),
			)
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
		'lexcat' => array(
			'section_key' => 'lexcat',
			'menu' => 'grammar',
			'icon' => 'fa-sitemap',
			'type' => 'pTableHandler',
			'surface' => DA_LEXCAT_TITLE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'types',
			'datafields' => array(
				new pDataField('name', 'Category name', 'auto', 'input', true, true, true, 'small-caps medium', false),
				new pDataField('short_name', DA_ABBR, 'auto', 'input', true, true, true, 'tooltip medium em', false),
				new pDataField('inflect', 'Inflections', 'auto', 'boolean', true, true, true, '', false),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', 'Add category', 'fa-plus-circle fa-12', 'btAction no-float small', null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
					
				),
			'incoming_links' => array(
				'headings' => array(
						'section' => 'headings',
						'table' => 'submode_apply',
						'parent' => 'mode_type_id',
						'child' => 'submode_id',
						'fields' => array(),
						'show_parent' => 'name',
						'show_child' => 'name',
				),
			),
		),
		'gramcat' => array(
			'section_key' => 'gramcat',
			'menu' => 'grammar',
			'icon' => 'fa-code-fork',
			'type' => 'pTableHandler',
			'surface' => DA_GRAMCAT_TITLE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'classifications',
			'datafields' => array(
				new pDataField('name', 'Category name', '40%', 'input', true, true, true, 'small-caps medium', false),
				new pDataField('short_name', 'Abbrivation', '20%', 'input', true, true, true, 'tooltip medium em', false),
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
			'incoming_links' => array(
				
			),
		),
		'gramtags' => array(
			'section_key' => 'gramtags',
			'icon' => 'fa-tags',
			'menu' => 'grammar',
			'type' => 'pTableHandler',
			'surface' => DA_GRAMTAGS_TITLE,
			'condition' => false,
			'items_per_page' => 10,
			'disable_pagination' => false,
			'table' => 'subclassifications',
			'datafields' => array(
				new pDataField('name', 'Tag name', '40%', 'input', true, true, true, 'small-caps medium', false),
				new pDataField('short_name', 'Abbrivation', '20%', 'input', true, true, true, 'tooltip medium em', false),
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


		// Table sections
		'headings' => array(
			'section_key' => 'headings',
			'permission' => -1,
			'icon' => 'fa-header',
			'menu' => 'table',
			'type' => 'pTableHandler',
			'surface' => DA_HEADINGS,
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'submodes',
			'datafields' => array(
				new pDataField('name', DA_TRANSLATION, '45%', 'input', true, true, true),
				new pDataField('short_name', DA_TRANSLATION, '20%', 'input', true, true, true),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', "New translations", 'fa-plus-circle fa-12', 'btAction no-float small', null, null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
				'lexcat' => array(
						'section' => 'lexcat', 
						'surface' => "Lexical categories", 
						'icon' => 'fa-sitemap', 
						'table'=> 'submode_apply', 
						'field' => 'submode_id'
					),
					
				),
			'incoming_links' => array(
				
			),
		),
		'rows' => array(
			'section_key' => 'rows',
			'permission' => -1,
			'icon' => 'fa-header',
			'menu' => 'table',
			'type' => 'pTableHandler',
			'surface' => "ROWS",
			'condition' => false,
			'items_per_page' => 20,
			'disable_pagination' => false,
			'table' => 'numbers',
			'datafields' => array(
				new pDataField('name', DA_TRANSLATION, '67%', 'input', true, true, true),
			),
			'actions_item' => array(
				'edit' => $action_edit,
				'remove' => $action_remove,
			),
			'actions_bar' => array(
				'new' => array('new', "New translations", 'fa-plus-circle fa-12', 'btAction no-float small', null, null, null),
			),
			'save_strings' => $saveStrings,
			'outgoing_links' => array(
					
				),
			'incoming_links' => array(
				
			),
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