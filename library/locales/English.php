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
$transfer['lang_sub_files'] = array('date', 'dictionary');
	

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
p::defineLocale('ADD_ITEM', "Add item");
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
p::defineLocale('LOGIN_TITLE', "Log in");
p::defineLocale('LOGIN_TITLE_SHORT', "Log in");
p::defineLocale('LOGIN_REGISTER_SHORT', "Sign up");
p::defineLocale('LOGIN_USERNAME', "Username");
p::defineLocale('LOGIN_USERNOTFOUND', "User not found");
p::defineLocale('LOGIN_PASSWORD', "Password");
p::defineLocale('LOGIN_PASSWORD_REPEAT', "Repeat password");
p::defineLocale('LOGIN_PROCEED', "Proceed");
p::defineLocale('LOGIN_ERROR', "Username and/or password are not correct.");
p::defineLocale('LOGIN_ERROR_ACTIVATED', "This account is not activated yet. Please check your mail for the activation link or contact the server administrator.");
p::defineLocale('LOGIN_ERROR_BANNED', "Your account has been deactivated by the server administrator.");
p::defineLocale('LOGIN_SUCCESS', "Succesfully logged in, you will be redirected...");
p::defineLocale('LOGIN_CHECKING', "Trying to log in...");
p::defineLocale('LOGIN_USERG_0', "Administrator");
p::defineLocale('LOGIN_USERG_1', "Editor");
p::defineLocale('LOGIN_USERG_2', "Translator");
p::defineLocale('LOGIN_USERG_3', "User");
p::defineLocale('LOGIN_USERG_4', "Guest");

p::defineLocale('AUTH_REGISTER_TITLE', "Register a new account");
p::defineLocale('AUTH_REGISTER_LOGIN', "Register");