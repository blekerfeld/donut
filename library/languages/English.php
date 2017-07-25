<?php
/*
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT

	ENGLISH TRANSLATION

*/

global $transfer;

$transfer['lang_sub_files'] = array('date', 'dictionary', 'admin');
	

// 404 text
p::defineLocale('ERROR_404_TITLE', 'Page not found!');
p::defineLocale('ERROR_404_MESSAGE', 'We are sorry, the page or file you requested could not be found.');

// Some basic information about the language itself
p::defineLocale('LANGUAGE_NAME', 'English (US)');
p::defineLocale('LANGUAGE_CODE', 'en');
p::defineLocale('LANGUAGE_NUMBER_DECIMALS_SEPARATOR', '.');
p::defineLocale('LANGUAGE_NUMBER_DECIMALS', 2);
p::defineLocale('LANGUAGE_NUMBER_THOUSANDS_SEPARATOR', ',');

p::defineLocale('COOKIES_MSG', 'This website uses cookies to ensure you get the best dictionary experience.');
p::defineLocale('COOKIES_ALRIGHT', 'Got it!');

p::defineLocale('RANDOM', "Random entry");
p::defineLocale('LOADING', "Loading...");
p::defineLocale('ACTIONS', "Actions");
p::defineLocale('DL_ID', "ID");
p::defineLocale('DL_ENABLED', "Enabled");
p::defineLocale('DL_DISABLED', "Disabled");
p::defineLocale('DL_OPTIONAL', "Optional");
p::defineLocale('BACK', "Back");
p::defineLocale('PREVIOUS', "Previous page");
p::defineLocale('NEXT', "Next page");
p::defineLocale('SAVE', "Save changes");
p::defineLocale('TIMES', " times");
p::defineLocale('TIME', " time");
p::defineLocale('EDIT', "Edit");
p::defineLocale('SAVING', "Saving changes...");
p::defineLocale('SAVED', "Changes succesfully saved");
p::defineLocale('SAVE_LINKBACK', "Click here to go back.");
p::defineLocale('SAVED_REDIRECT', "Saved, you will be redirected.");
p::defineLocale('SAVED_ERROR', "An error occured while saving");
p::defineLocale('SAVED_EMPTY', "Please submit all required information.");

// Menu strings
p::defineLocale('MMENU_DICTIONARY', "Dictionary");
p::defineLocale('MMENU_DICTIONARYADMIN', "Dictionary settings");
p::defineLocale('MMENU_SETTINGS', "Settings");
p::defineLocale('MMENU_EDITORMENU', "Editor menu");
p::defineLocale('MMENU_WIKI', "Wiki");
p::defineLocale('MMENU_DASHBOARD', "Dashboard");
p::defineLocale('MMENU_BLOG', "Blog");
p::defineLocale('MMENU_ARTICLES', "Grammar");
p::defineLocale('MMENU_PHONOLOGY', "Phonology");
p::defineLocale('MMENU_TEXTS', "TEXTS");
p::defineLocale('MMENU_EDITORLANG', "Editor language: ");
p::defineLocale('MMENU_EDITORLANGCHANGE', "Change");
p::defineLocale('MMENU_LOGGEDIN', "Welcome, 	");
p::defineLocale('MMENU_LOGIN', "Editor log in");
p::defineLocale('MMENU_LOGOUT', "Log out");

// Login string
p::defineLocale('LOGIN_TITLE', "Editor login");
p::defineLocale('LOGIN_TITLE_SHORT', "Log in");
p::defineLocale('LOGIN_USERNAME', "Username");
p::defineLocale('LOGIN_USERNOTFOUND', "User not found");
p::defineLocale('LOGIN_PASSWORD', "Password");
p::defineLocale('LOGIN_PROCEED', "Proceed");
p::defineLocale('LOGIN_ERROR', "Username and/or password are not correct.");
p::defineLocale('LOGIN_SUCCESS', "Succesfully logged in, you will be redirected...");
p::defineLocale('LOGIN_CHECKING', "Trying to log in...");

// Batch translating
p::defineLocale('BTRANS_TITLE', "Batch translating into ");
p::defineLocale('BTRANS_TITLE_SINGLE', "Translating into ");
p::defineLocale('BTRANS_SKIP', "Skip translation ");




// Dashboard strings
p::defineLocale('DB_TITLE', "Dashboard");
p::defineLocale('DB_', "");


// Wiki string
p::defineLocale('WIKI_TITLE', "Wiki");
p::defineLocale('WIKI_SEARCH_PLACEHOLDER', "Search the wiki");
p::defineLocale('WIKI_NO_ARTICLES', "There are no articles matching your search criteria.");
p::defineLocale('WIKI_SEARCH', "Search");
p::defineLocale('WIKI_SEARCH_RESULTS', "Search results for ");
p::defineLocale('WIKI_MENU_WIKI', "Wiki homepage");
p::defineLocale('WIKI_MENU_RANDOM', "Random article");
p::defineLocale('WIKI_UNCHANGED', "Unchanged");
p::defineLocale('WIKI_SPECIFICATION', "Explain your changes shortly (optional)");
p::defineLocale('WIKI_MENU_EDIT', "Edit article");
p::defineLocale('WIKI_MENU_BACK', "Back to article");
p::defineLocale('WIKI_MENU_HISTORY', "History");
p::defineLocale('WIKI_MENU_DISCUSS', "Discussion");
p::defineLocale('WIKI_PERMALINK', "This is an older version (from %s), the content might be heavily changed by now.");
p::defineLocale('WIKI_HISTORY_OF', "History of %s");
p::defineLocale('WIKI_DISCUSSION_OF', "Discussing %s");
p::defineLocale('WIKI_EDITING', "Editing %s");
p::defineLocale('WIKI_EDIT_SAVE', "Save article");
p::defineLocale('WIKI_EDIT_ERROR', "The article could not be saved");
p::defineLocale('WIKI_EDIT_PREVIEW', "Preview");
p::defineLocale('WIKI_EDIT_PREVIEWING', "Preview of formated article");
p::defineLocale('WIKI_CURRENT', "Current");
p::defineLocale('WIKI_INITIAL', "Initial Creation");
p::defineLocale('WIKI_REVERT', "Revert to this version");
p::defineLocale('WIKI_DOES_NOT_EXIST', "This article does not exist!");
p::defineLocale('WIKI_CREATE', "Create article");
p::defineLocale('WIKI_ENTER_CONTENT', "Enter your content here...");
p::defineLocale('WIKI_DOES_NOT_EXIST_MAKE', "This article does not exist, but if you need it you can create it below.");
p::defineLocale('WIKI_REVERTED_S', "Succesfully reverted to an earlier version.");
p::defineLocale('WIKI_REVERTED_ERROR', "You cannot revert this article to the current version.");
p::defineLocale('WIKI_REVERTED', "Reverted to the version of %s by %s");
p::defineLocale('WIKI_LOADING_RANDOM', "Loading a random article");