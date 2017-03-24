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

define("", '');


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
define('DA_TITLE', "Dictionary settings");