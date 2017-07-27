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
p::defineLocale('DICT_TITLE', 'Dictionary');
p::defineLocale('DICT_READMORE', 'Go to lemma');
p::defineLocale('DICT_SEARCH', 'Search');
p::defineLocale('DICT_KEYWORD', 'Search the dictionary...');
p::defineLocale('DICT_EXACT_MATCH', 'exact match');
p::defineLocale('DICT_MATCH', 'Your query yielded %s result.');
p::defineLocale('DICT_SEARCH_RESULTS', 'Search results');
p::defineLocale('DICT_NO_HITS', 'Your query yielded no results.');
p::defineLocale('DICT_NO_QUERY', 'Please provide a search query.');
p::defineLocale('DICT_MATCHES', 'Your query yielded %s results.');

// Lemma information
p::defineLocale('LEMMA_ERROR', "The requested lemma doesn't exist, please look for errors in the URL.");
p::defineLocale('LEMMA_HIDDEN', "Hidden");
p::defineLocale("LEMMA_INFLECTIONS", "Inflections");
p::defineLocale("LEMMA_TRANSLATIONS", 'Meaning and translations');
p::defineLocale("TRANSLATION_LEMMAS", 'Linked entries in %s');
p::defineLocale("TRANSLATION_DESC", 'Description');
p::defineLocale("LEMMA_DECLENSION", 'Declension');
p::defineLocale("LEMMA_EXAMPLES", 'Examples and idiom');
p::defineLocale("LEMMA_EXAMPLE", 'Example/idiom');
p::defineLocale("LEMMA_EXAMPLE_IN", 'Example/idiom in %s');
p::defineLocale("LEMMA_TRANSLATION_INTO", 'Translation into %s');
p::defineLocale("LEMMA_TRANSLATION_ADDED", 'Translation added by %s on %s');
p::defineLocale("LEMMA_TRANSLATIONS_INTO", 'Translations into %s');
p::defineLocale("LEMMA_TRANSLATIONS_MEANINGS", 'Other meanings/alternate forms in %s');
p::defineLocale("LEMMA_TRANSLATIONS_RESET", 'Show all translations');
p::defineLocale("LEMMA_USAGE_NOTES", "Usage notes");
p::defineLocale("LEMMA_ANTONYMS", "Antonyms");
p::defineLocale("LEMMA_SYNONYMS", "Synonyms");
p::defineLocale("LEMMA_HOMOPHONES", "Homophones");

// Lemma edit
p::defineLocale('LEMMA_EDIT', "Edit lemma");
p::defineLocale('LEMMA_NEW', "New lemma");
p::defineLocale('LEMMA_VIEW_SHORT', "View");
p::defineLocale('LEMMA_EDIT_SHORT', "Edit");
p::defineLocale('LEMMA_DISCUSS_SHORT', "Discuss");
p::defineLocale('LEMMA_DISCUSS_TITLE', "Discussing %s");
p::defineLocale('LEMMA_EDIT_MODE', "Edit lemma");
p::defineLocale('LEMMA_EDIT_BASICS', "Change basic information");
p::defineLocale('LEMMA_EDIT_DELETE', "Delete Lemma");
p::defineLocale('LEMMA_EDIT_DELETE_SURE', "Are you sure you want to delete this lemma?");
p::defineLocale("LEMMA_EDIT_STEMS", "Manage stems");
p::defineLocale("LEMMA_EDIT_IRREGULAR", "Manage irregularities");
p::defineLocale("LEMMA_EDIT_TRANSLATIONS", "Edit translations");
p::defineLocale("LEMMA_CLONE_TRANSLATIONS", "Clone translations");


// Entry
p::defineLocale('ENTRY_NOT_FOUND', "The entry you requested is not found.");


// Discussion threads
// Word dicussion
p::defineLocale('WD_NEW_THREAD', "New thread");
p::defineLocale('WD_NEW_THREAD_TITLE', "Submit a new thread");
p::defineLocale('WD_REPLY', "reply");
p::defineLocale('WD_REPLY_TITLE', "Replying to a message");
p::defineLocale('WD_REPLY_PLACEHOLDER', "Write your message.");
p::defineLocale('WD_REPLY_SEND', "Send");
p::defineLocale('WD_REPLY_EMPTY', "You cannot post an empty reply.");
p::defineLocale('WD_EDIT', "Edit");
p::defineLocale('WD_DELETE', "Remove");
p::defineLocale('WD_DELETE_CONFIRM', "Are you sure you want to remove this message and all of its underlying comments?");
p::defineLocale('WD_BACK_TO_THREAD', "Back to thread");
p::defineLocale('WD_BACK_TO_WORD', "Back to entry");
p::defineLocale('WD_NO_THREADS', "There are no threads yet, feel free to create a discussion thread.");

// D_admin
p::defineLocale('DA_OPTIONAL', "optional");
p::defineLocale('DA_DEFAULT', "Default");
p::defineLocale('DA_PERMISSION_ERROR', "You don't currently have permission to access this section.");
p::defineLocale('DA_SECTION_ERROR', "The section you requested is not found, the default section is loaded instead.");
p::defineLocale('DA_TITLE', "Dictionary settings");
p::defineLocale('DA_EDIT', "Edit item");
p::defineLocale('DA_EDITING', "Editing the item -");
p::defineLocale('DA_DELETE', "Delete item");
p::defineLocale('DA_DELETE_LINK', "Delete link");
p::defineLocale('DA_DELETE_SURE', "Are you sure you want to delete this item?");
p::defineLocale('DA_DELETE_SURE_LINK', "Are you sure you want to delete the link between %s and %s? This might remove additional data.");
// D_admin languges settings
p::defineLocale('DA_LANG_SURFACE', "Languages");
p::defineLocale('DA_LANG_1', "Language");
p::defineLocale('DA_LANG_FLAG', "Flag");
p::defineLocale('DA_LANG_NAME', "Language name");
p::defineLocale('DA_LANG_ACTIVATED', "Activated");
p::defineLocale('DA_LANG_LOCALE', "Language code");
p::defineLocale('DA_LANG_EDIT', "Edit language");
p::defineLocale('DA_LANG_NEW', "Add a new language");

p::defineLocale('DA_NO_RECORDS', "No records found.");

p::defineLocale('DA_PAGE_X_OF_Y', "Page %s of %s");


// Link tables
p::defineLocale('DA_TABLE_ACTIONS', "Actions");
p::defineLocale('DA_TABLE_LINKS', "Relations");
p::defineLocale('DA_TABLE_LINKS_CHILD_ID', "Child ID");
p::defineLocale('DA_TABLE_LINKS_CHILD', "Child");
p::defineLocale('DA_TABLE_LINKS_CHILDREN', "Children");
p::defineLocale('DA_TABLE_LINKS_PARENT', "Parent");
p::defineLocale('DA_TABLE_LINK_TITLE', "Relational table: %s");
p::defineLocale('DA_TABLE_NEW_LINK', "New relation");
p::defineLocale('DA_TABLE_NEW_RELATION', "New relation in ");
p::defineLocale('DA_TABLE_LINK_KEYWORD', "Keyword");
p::defineLocale('DA_TABLE_LINK_SPECIFICATION', "Specification");
p::defineLocale('DA_TABLE_RELATION_EXIST', "This relation already exists.");
p::defineLocale('DA_TABLE_RELATION_ADDED', "This relation is succesfully created");

p::defineLocale('DA_ABBR', "Abbreviation");
p::defineLocale('DA_SUBINFLECTIONS', "Sub-inflections");

p::defineLocale("DA_LEXCAT_TITLE", "Lexical categories");
p::defineLocale("DA_LEXCAT", "Lexical category");
p::defineLocale("DA_GRAMCAT", "Grammatical category");
p::defineLocale("DA_GRAMCAT_TITLE", "Grammatical categories");
p::defineLocale("DA_GRAMTAG", "Grammatical tag");
p::defineLocale("DA_GRAMTAGS_TITLE", "Grammatical tags");


// Da titles
p::defineLocale("DA_TITLE_HOME", "Home");
p::defineLocale("DA_TITLE_CONTENT", "Content");
p::defineLocale("DA_TITLE_GRAMMAR", "Grammar");
p::defineLocale("DA_TITLE_TABLES", "Inflection tables");
p::defineLocale("DA_TITLE_LANGUAGES", "Languages");
p::defineLocale("DA_TITLE_PHONOLOGY", "Phonology");
p::defineLocale("DA_TITLE_ORTOGRAPHY", "Ortography");
p::defineLocale("DA_TITLE_WORDS", "Words");
p::defineLocale("DA_TITLE_EXAMPLES", "Idiom and examples");

// Da Words
p::defineLocale("DA_WORDS_NATIVE", "Entry");
p::defineLocale("DA_WORDS_LEXICAL", "Lexical form for inflection");
p::defineLocale("DA_WORDS_IPA", "IPA transcription");
p::defineLocale("DA_WORDS_NEW_EXTERN", "New word");

p::defineLocale("DA_TRANSLATIONS", "Translations");
p::defineLocale("DA_TRANSLATION", "Translation");

// Stats
p::defineLocale('STATS_MOSTSEARCH', 'The top %s most-searched words');
p::defineLocale('STATS_LASTSEARCHBY', 'Last search «%s»');

// Grammatical tables
p::defineLocale("DA_HEADINGS", "Headings");

// Rulesheet 
p::defineLocale('RS_DELETE_CONFIRM', 'Are you sure you want to delete this rule folder, all of its sub-folders and any rules they might contain?');
p::defineLocale('', '');
p::defineLocale('', '');


p::defineLocale('BATCH_CHOOSE_LANGUAGE', 'Choose language');
p::defineLocale('BATCH_TRANSLATE', 'Translate');
p::defineLocale('BATCH_CONTINUE', 'Next');
p::defineLocale('BATCH_TR_UNTRANS', 'Untranslatable');
p::defineLocale('BATCH_TR_SKIP', 'Skip');
p::defineLocale('BATCH_TR_PLACEHOLDER', 'Add a translation');
p::defineLocale('BATCH_TR_DESC1', 'Add as many translations as needed. Existing translations are linked and new ones are created on the fly.');
p::defineLocale('BATCH_TR_DESC2', 'The input format is %s translation %s or %s translation>specification %s');