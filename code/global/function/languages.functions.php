<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: languages.functions.php
*/

// Languages! Let's load one. Just one. Or perhaps two, if we're feeling up to it, bilingualism is a good thing.
function pLoadLanguage($language = '') {
	
	global $donut;

	// So did we actually provide ourselves with a language? If the language is empty... it doesn't work, like at all
	if(empty($language))
		return false;

	// Let's create a variable to make things a little more readable
	$language_main_path = pFromRoot('library/languages/' . $language . '.php');

	// Does this language exist?
	if(!file_exists($language_main_path))
		return die("Language files were not found.");

	// It does exists, let's load the main file
	include_once $language_main_path;

	// Now all other files
	foreach($donut['lang_sub_files'] as $filename)
		include pFromRoot('library/languages/' . $language . '.' . $filename . '.php');
		
	// We might need this
	$donut['lang_current'] = $language;

	return true;

}
 ?>