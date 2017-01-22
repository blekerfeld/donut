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

$donut['lang_sub_files'] = array('date');
	
// 404 text
define('ERROR_404_TITLE', 'Oops. Page not found. ');
define('ERROR_404_MESSAGE', 'The page or file you requested could not be found.');

// Some basic information about the language itself
define('LANGUAGE_NAME', 'English (US)');
define('LANGUAGE_CODE', 'en');
define('LANGUAGE_NUMBER_DECIMALS_SEPARATOR', '.');
define('LANGUAGE_NUMBER_DECIMALS', 2);
define('LANGUAGE_NUMBER_THOUSANDS_SEPARATOR', ',');

// Strings that are used pretty much everywhere
define('FOOTER_LANGUAGE', 'Language: ');
define('FOOTER_PAGE_GENERATED', 'Page generated in %s ms with %s queries.');

// Menu strings
define('MMENU_DICTIONARY', "Dictionary");
define('MMENU_MANAGE', "Control panel");
define('MMENU_EDITORLANG', "Editor languge: ");
define('MMENU_EDITORLANGCHANGE', "Change");
define('MMENU_LOGGEDIN', "Welcome, 	");
define('MMENU_LOGIN', "Editor log in");
define('MMENU_LOGOUT', "Log out");

// Login string
define('LOGIN_TITLE', "Welcome, please log in to be able to edit");
define('LOGIN_TITLE_SHORT', "Log in");
define('LOGIN_USERNAME', "Username");
define('LOGIN_PASSWORD', "Password");
define('LOGIN_PROCEED', "Proceed");
define('LOGIN_ERROR_FIELDS', "Please submit both an username and a password.");
define('LOGIN_ERROR_WRONG', "Password or username is wrong.");
define('LOGIN_SUCCESS', "Succesfully logged in, you will be redirected...");
define('LOGIN_CHECKING', "Checking...");


// Batch translating
define('BTRANS_TITLE', "Batch translating into ");
define('BTRANS_TITLE_SINGLE', "Translating into ");
define('BTRANS_SKIP', "Skip translation ");
