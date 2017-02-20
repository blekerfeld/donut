<?php
/*

	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3

	ENGLISH TRANSLATION

*/

global $donut;

$donut['lang_sub_files'] = array('date', 'dictionary', 'admin');
	

// 404 text
define('ERROR_404_TITLE', 'Oops. Page not found. ');
define('ERROR_404_MESSAGE', 'The page or file you requested could not be found.');

// Some basic information about the language itself
define('LANGUAGE_NAME', 'English (US)');
define('LANGUAGE_CODE', 'en');
define('LANGUAGE_NUMBER_DECIMALS_SEPARATOR', '.');
define('LANGUAGE_NUMBER_DECIMALS', 2);
define('LANGUAGE_NUMBER_THOUSANDS_SEPARATOR', ',');

define('LOADING', "Loading...");
define('BACK', "Back");
define('SAVE', "Save changes");
define('SAVING', "Saving changes");
define('SAVED', "Changes succesfully saved");
define('SAVED_REDIRECT', SAVED.", you will be redirected.");
define('SAVED_ERROR', "An error occured while saving");
define('SAVED_EMPTY', "Please submit all required information");

// Menu strings
define('MMENU_DICTIONARY', "Dictionary");
define('MMENU_SETTINGS', "Settings");
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
define('LOGIN_TITLE', "Welcome, editor.");
define('LOGIN_TITLE_SHORT', "Log in");
define('LOGIN_USERNAME', "Username");
define('LOGIN_PASSWORD', "Password");
define('LOGIN_PROCEED', "Proceed");
define('LOGIN_FIELDS', "Please submit both an username and a password.");
define('LOGIN_WRONG', "Password or username is wrong.");
define('LOGIN_SUCCESS', "Succesfully logged in, you will be redirected...");
define('LOGIN_CHECKING', "Checking...");

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
define('WD_DELETE', "delete");
define('WD_DELETE_CONFIRM', "Are you sure you want to delete this message and all of its underlying comments?");
define('WD_BACK_TO_THREAD', "Back to thread");
define('WD_BACK_TO_WORD', "Back to entry");
define('WD_NO_THREADS', "There are no threads yet");


// Dashboard strings
define('DB_TITLE', "Dashboard");
define('DB_', "");