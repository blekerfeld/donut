<?php
/*
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT

	ENGLISH TRANSLATION

*/

global $donut;

$donut['lang_sub_files'] = array('date', 'dictionary', 'admin');
	

// 404 text
define('ERROR_404_TITLE', 'Page not found!');
define('ERROR_404_MESSAGE', 'We are sorry, the page or file you requested could not be found.');

// Some basic information about the language itself
define('LANGUAGE_NAME', 'English (US)');
define('LANGUAGE_CODE', 'en');
define('LANGUAGE_NUMBER_DECIMALS_SEPARATOR', '.');
define('LANGUAGE_NUMBER_DECIMALS', 2);
define('LANGUAGE_NUMBER_THOUSANDS_SEPARATOR', ',');

define('LOADING', "Loading...");
define('ACTIONS', "Actions");
define('DL_ID', "ID");
define('DL_ENABLED', "Enabled");
define('DL_DISABLED', "Disabled");
define('DL_OPTIONAL', "Optional");
define('BACK', "Back");
define('PREVIOUS', "Previous page");
define('NEXT', "Next page");
define('SAVE', "Save changes");
define('EDIT', "Edit");
define('SAVING', "Saving changes...");
define('SAVED', "Changes succesfully saved");
define('SAVE_LINKBACK', "Click here to go back.");
define('SAVED_REDIRECT', SAVED.", you will be redirected.");
define('SAVED_ERROR', "An error occured while saving");
define('SAVED_EMPTY', "Please submit all required information");

// Menu strings
define('MMENU_DICTIONARY', "Dictionary");
define('MMENU_DICTIONARYADMIN', "Dictionary settings");
define('MMENU_SETTINGS', "Settings");
define('MMENU_EDITORMENU', "Editor menu");
define('MMENU_WIKI', "Wiki");
define('MMENU_DASHBOARD', "Dashboard");
define('MMENU_BLOG', "Blog");
define('MMENU_ARTICLES', "Grammar");
define('MMENU_PHONOLOGY', "Phonology");
define('MMENU_TEXTS', "TEXTS");
define('MMENU_EDITORLANG', "Editor language: ");
define('MMENU_EDITORLANGCHANGE', "Change");
define('MMENU_LOGGEDIN', "Welcome, 	");
define('MMENU_LOGIN', "Editor log in");
define('MMENU_LOGOUT', "Log out");

// Login string
define('LOGIN_TITLE', "Editor login");
define('LOGIN_TITLE_SHORT', "Log in");
define('LOGIN_USERNAME', "Username");
define('LOGIN_PASSWORD', "Password");
define('LOGIN_PROCEED', "Proceed");
define('LOGIN_ERROR', "Username and/or password are not correct.");
define('LOGIN_SUCCESS', "Succesfully logged in, you will be redirected...");
define('LOGIN_CHECKING', "Trying to log in...");

// Batch translating
define('BTRANS_TITLE', "Batch translating into ");
define('BTRANS_TITLE_SINGLE', "Translating into ");
define('BTRANS_SKIP', "Skip translation ");

// Word dicussion
define('WD_TITLE', "Discussion");
define('WD_TITLE_MORE', "Discussing lemma %s");
define('WD_POINT', "point");
define('WD_POINTS', "points");
define('WD_NEW_THREAD', "New thread");
define('WD_NEW_THREAD_TITLE', "Submit a new thread");
define('WD_REPLY', "reply");
define('WD_REPLY_TITLE', "Replying to a message");
define('WD_REPLY_PLACEHOLDER', "Write your message.");
define('WD_REPLY_SEND', "Send");
define('WD_REPLY_EMPTY', "You cannot post an empty reply.");
define('WD_REPLY_ERROR', "Something went wrong, your reaction is not posted.");
define('WD_UPVOTE', "upvote");
define('WD_DOWNVOTE', "downvote");
define('WD_DELETE', "Delete");
define('WD_DELETE_CONFIRM', "Are you sure you want to delete this message and all of its underlying comments?");
define('WD_BACK_TO_THREAD', "Back to thread");
define('WD_BACK_TO_WORD', "Back to entry");
define('WD_NO_THREADS', "There are no threads yet");


// Dashboard strings
define('DB_TITLE', "Dashboard");
define('DB_', "");


// Wiki string
define('WIKI_TITLE', "Wiki");
define('WIKI_SEARCH_PLACEHOLDER', "Search the wiki");
define('WIKI_NO_ARTICLES', "There are no articles matching your search criteria.");
define('WIKI_SEARCH', "Search");
define('WIKI_SEARCH_RESULTS', "Search results for ");
define('WIKI_MENU_WIKI', "Wiki homepage");
define('WIKI_MENU_RANDOM', "Random article");
define('WIKI_UNCHANGED', "Unchanged");
define('WIKI_SPECIFICATION', "Explain your changes shortly (optional)");
define('WIKI_MENU_EDIT', "Edit article");
define('WIKI_MENU_BACK', "Back to article");
define('WIKI_MENU_HISTORY', "History");
define('WIKI_MENU_DISCUSS', "Discussion");
define('WIKI_PERMALINK', "This is an older version (from %s), the content might be heavily changed by now.");
define('WIKI_HISTORY_OF', "History of %s");
define('WIKI_DISCUSSION_OF', "Discussing %s");
define('WIKI_EDITING', "Editing %s");
define('WIKI_EDIT_SAVE', "Save article");
define('WIKI_EDIT_ERROR', "The article could not be saved");
define('WIKI_EDIT_PREVIEW', "Preview");
define('WIKI_EDIT_PREVIEWING', "Preview of formated article");
define('WIKI_CURRENT', "Current");
define('WIKI_INITIAL', "Initial Creation");
define('WIKI_REVERT', "Revert to this version");
define('WIKI_DOES_NOT_EXIST', "This article does not exist!");
define('WIKI_CREATE', "Create article");
define('WIKI_ENTER_CONTENT', "Enter your content here...");
define('WIKI_DOES_NOT_EXIST_MAKE', "This article does not exist, but if you need it you can create it below.");
define('WIKI_REVERTED_S', "Succesfully reverted to an earlier version.");
define('WIKI_REVERTED_ERROR', "You cannot revert this article to the current version.");
define('WIKI_REVERTED', "Reverted to the version of %s by %s");
define('WIKI_LOADING_RANDOM', "Loading a random article");