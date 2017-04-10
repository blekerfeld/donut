<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dictionary.functions.php



function pEL_Basics($id, $native, $type, $class, $subclass){

	// The donut is EVERY WHERE
	global $donut;

	return pQuery("UPDATE words SET native = ".$donut['db']->quote(pEscape($native)).", type_id = ".$donut['db']->quote(pEscape($type)).", classification_id = ".$donut['db']->quote(pEscape($class)).", subclassification_id = ".$donut['db']->quote(pEscape($subclass))." WHERE id = '$id';");

}

function pEL_IPA($id, $ipa){

	// The donut is EVERY WHERE
	global $donut;

	return pQuery("UPDATE words SET ipa = ".$donut['db']->quote(pEscape($ipa))." WHERE id = '$id';");

}

function pEL_UsageNotes($id, $text){

	// The donut is EVERY WHERE

	global $donut;

	$check = pGetUsageNotes($id);

	if($check->rowCount() != 0)
		pQuery("DELETE FROM usage_notes WHERE id = ".$check->fetchObject()->id);

	return pQuery("INSERT INTO usage_notes (word_id, created_on	, user_id, note) VALUES($id, NOW(), ".pUser().",".$donut['db']->quote($text).")");

}


function pEL_StemOverview($id){
	$stems = pQuery("SELECT * FROM stems WHERE word_id = $id;");
	$stems_array = array();
	while($stem = $stems->fetchObject())
		$stems_array[] = $stem->stem_override."-";
	return implode(', ', $stems_array);
}

function pEL_IrregularOverview($id){
	$irregulars = pQuery("SELECT DISTINCT irregular_override FROM inflections WHERE irregular_word_id = $id;");
	$irregulars_array = array();
	while($irregular = $irregulars->fetchObject())
		$irregulars_array[] = $irregular->irregular_override;
	return implode(', ', $irregulars_array);
}


function pDeleteLemma($id){

	$long_query = "
		DELETE FROM usage_notes WHERE word_id = $id;
		DELETE FROM stems WHERE word_id = $id;
		DELETE FROM words_wiki WHERE word_id = $id;
		DELETE FROM translation_words WHERE word_id = $id;
		DELETE FROM translation_exceptions WHERE word_id = $id;
		DELETE FROM audio_words WHERE word_id = $id;
		DELETE FROM aux_conditions WHERE aux_id = $id;
		DELETE FROM inflection_cache WHERE word_id = $id;
		DELETE FROM discussions WHERE word_id = $id;
		DELETE FROM etymology WHERE word_id = $id;
		DELETE FROM idiom_words WHERE word_id = $id;
		DELETE FROM inflections WHERE irregular = 1 AND irregular_word_id = $id;
		DELETE FROM synonyms WHERE word_id_1 = $id OR word_id_2 = $id;
		DELETE FROM antonyms WHERE word_id_1 = $id OR word_id_2 = $id;
		DELETE FROM words WHERE id = $id;
		";


	// Make sure we delete all links related to the lemma as well
	return pQuery($long_query) && pCleanCache();;



}