<?php
/*

	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT

	ENGLISH TRANSLATION

*/


// Dictionary
define('DICT_TITLE', 'Dictionary');
define('DICT_READMORE', 'Go to lemma');
define('DICT_SEARCH', 'Search');
define('DICT_MATCH', 'Your query yielded %s result.');
define('DICT_MATCHES', 'Your query yielded %s results.');

// Lemma information
define('LEMMA_ERROR', "The requested lemma doesn't exist, please look for errors in the URL.");
define("LEMMA_INFLECTIONS", "Inflections");
define("LEMMA_TRANSLATIONS", 'Meaning and translations');
define("LEMMA_TRANSLATIONS_INTO", 'Translations into %s');
define("LEMMA_TRANSLATIONS_MEANINGS", 'Other meanings/alternate forms in %s');
define("LEMMA_USAGE_NOTES", "Usage notes");

// Lemma edit
define('LEMMA_EDIT', "Edit lemma");
define('LEMMA_EDIT_MODE', "Edit lemma");
define('LEMMA_EDIT_BASICS', "Change basic information");
define('LEMMA_EDIT_DELETE', "Delete Lemma");
define('LEMMA_EDIT_DELETE_SURE', "Are you sure you want to delete this lemma?");

define("LEMMA_EDIT_STEMS", "Manage stems");
define("LEMMA_EDIT_IRREGULAR", "Manage irregularities");
define("LEMMA_EDIT_TRANSLATIONS", "Edit translations");
define("LEMMA_CLONE_TRANSLATIONS", "Clone translations");
define("LEMMA_EDIT_TRANSLATIONS_INFO", "If you want to edit translations in another language, you need to <a href'".pUrl("?editorlanguage")."'>change your editor language</a>.");



// Edit basics
define('LE_BASICS_TITLE', "Edit basic information");
define('LE_BASICS_WORD', "Word in %s");
define('LE_BASICS_TYPE', "Part of speech:");
define('LE_BASICS_CLASSIFICATION', "Classification:");
define('LE_BASICS_TAGS', "Tag:");

// Edit IPA

define('LE_IPA_TITLE', "Edit IPA transcription");
define('LE_IPA', "IPA transcription");


// D_admin
define('DA_DEFAULT', "Default");
define('DA_TITLE', "Dictionary settings");
define('DA_EDIT', "Edit item");
define('DA_EDITING', "Editing the item -");
define('DA_DELETE', "Delete item");
define('DA_DELETE_LINK', "Delete link");
define('DA_DELETE_SURE', "Are you sure you want to delete this item?");
define('DA_DELETE_SURE_LINK', "Are you sure you want to delete the link between %s and %s? This might remove additional data.");
// D_admin languges settings
define('DA_LANG_SURFACE', "Languages");
define('DA_LANG_FLAG', "Flag");
define('DA_LANG_NAME', "Language name");
define('DA_LANG_ACTIVATED', "Activated");
define('DA_LANG_LOCALE', "Language code");
define('DA_LANG_EDIT', "Edit language");
define('DA_LANG_NEW', "Add a new language");

define('DA_NO_RECORDS', "No records found.");

define('DA_PAGE_X_OF_Y', "Page %s of %s");


// Link tables
define('DA_TABLE_LINKS', "Relations");
define('DA_TABLE_LINKS_CHILD_ID', "Child ID");
define('DA_TABLE_LINKS_CHILD', "Child");
define('DA_TABLE_LINKS_CHILDREN', "Children");
define('DA_TABLE_LINKS_PARENT', "Parent");
define('DA_TABLE_LINK_TITLE', "Relational table: %s");
define('DA_TABLE_NEW_LINK', "New relation");
define('DA_TABLE_NEW_RELATION', "New relation in ");
define('DA_TABLE_RELATION_EXIST', "This relation already exists.");
define('DA_TABLE_RELATION_ADDED', "This relation is succesfully created");

define('DA_ABBR', "Abbreviation");
define('DA_SUBINFLECTIONS', "Sub-inflections");

define("DA_LEXCAT_TITLE", "Lexical categories");
define("DA_GRAMCAT_TITLE", "Grammatical categories");
define("DA_GRAMTAGS_TITLE", "Grammatical tags");