<?php

function pMarkDownParse($text){
	
	// We need to require the parsedown files, but only once.
	require_once pFromRoot('library/parsedown.require.php');
	require_once pFromRoot('library/parsedown_extra.require.php');

	$parse = new ParsedownExtra;

	return $parse->text($text);
}


function pLanguageDelete($id){

	global $donut;
	// First we need to loop through all the translation
	$q = "DELETE translation_words FROM translation_words JOIN translations ON translations.id = translation_words.translation_id WHERE translations.language_id = $id;
	DELETE FROM idiom_translations WHERE language_id = $id;
	DELETE FROM translations WHERE language_id = $id;
	UPDATE users SET editor_lang = 1 WHERE editor_lang = $id;
	DELETE FROM languages WHERE id = $id";
	return $donut['db']->query($q);
}

function pLanguageUpdate($id, $name, $hidden_entry, $flag, $activated){

	global $donut;
	$q = "UPDATE languages SET name = '$name', hidden_native_entry  = '$hidden_entry',  flag = '$flag', activated = '$activated' WHERE id = '$id';";
	return $donut['db']->query($q);
}

function pLanguageAdd($name, $hidden_entry, $flag,  $activated){

	global $donut;
	$q = "INSERT INTO languages (`name`, `hidden_native_entry`, `flag`, `activated`) VALUES ('$name', '$hidden_entry', '$flag', '$activated');";
	return $donut['db']->query($q);
}


function pGetLanguages($activated = true, $offset = ''){

	global $donut;
	$q = "SELECT * FROM languages WHERE id <> 0 $offset";
	if($activated)
		$q .= ' AND activated = "1"';
	return $donut['db']->query($q);
}

function pDisabledLanguage($id){

	$lang = pGetLanguage($id);

	if($lang->activated == 0)
		return true;
	else
		return false;

}

function pGetLanguageZero(){
	global $donut;
	$q = "SELECT * FROM languages WHERE id = 0";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return $rs->fetchObject();
}

function pGetLanguage($id){
	global $donut;
	$q = "SELECT * FROM languages WHERE id = $id";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return $rs->fetchObject();
}


function pLanguageName($id)
{
	global $donut;
	$q = "SELECT * FROM languages WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}





function pGetClassificationsApply($id, $offset = ''){
	global $donut;
	$q = "SELECT * FROM classification_apply WHERE classification_id = ".$id." $offset";
	$rs = $donut['db']->query($q);
	return $rs;
}

function pCountClassificationsApply($id){
	global $donut;
	$q = "SELECT * FROM classification_apply WHERE classification_id = ".$id;
	$rs = $donut['db']->query($q);
	return $rs->rowCount();
}


function pExistClassificationApply($classification_id, $type_id){
	global $donut;
	$q = "SELECT * FROM classification_apply WHERE type_id = '$type_id' AND classification_id = '$classification_id'";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return true;
}


function pGetClassifications($type = 0, $force = false, $force_get = 0, $offset = ''){

	global $donut;
	if($type == 0){
		$q = "SELECT * FROM classifications $offset";
		$rs = $donut['db']->query($q);
		return $rs;
	}
	elseif(!$force){
		$q = "SELECT * FROM classification_apply WHERE type_id = ".$type." $offset";
		$rs = $donut['db']->query($q);
		$results = array();
		if($rs->rowCount() == 0)
			return false;
		else{
			while($classification_apply = $rs->fetchObject()){
				$results[] = pGetClassification($classification_apply->classification_id);
			}
		return $results;
		}
	}
	else{
		$q = "SELECT * FROM classifications WHERE id = ".$force_get." $offset";
		$rs = $donut['db']->query($q);
		$results = array();
		if($rs->rowCount() == 0)
			return false;
		else{
			while($classification_apply = $rs->fetchObject()){
				$results[] = $classification_apply;
			}
		return $results;
		}
	}

}

function pGetClassification($id)
{
	global $donut;
	$q = "SELECT * FROM classifications WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}



function pClassificationUpdate($id, $name, $shortname, $entry, $entry_short){

	global $donut;
	$q = "UPDATE classifications SET name = '$name', short_name = '$shortname', native_hidden_entry  = '$entry', native_hidden_entry_short  = '$entry_short' WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pClassificationAdd($name, $shortname, $entry, $entry_short){

	global $donut;
	$q = "INSERT INTO classifications VALUES (NULL, '$name', '$shortname', '$entry', '$entry_short');";
	return $donut['db']->query($q);
}

function pClassificationApplyAdd($classification_id, $type_id){

	global $donut;
	$q = "INSERT INTO classification_apply VALUES (NULL, '$classification_id', '$type_id');";
	return $donut['db']->query($q);
}


function pClassificationDelete($id){

	global $donut;
	$q = "DELETE FROM classifications WHERE id = $id";
	return $donut['db']->query($q);
}

function pClassificationApplyDelete($type_id, $classification_id){

	global $donut;
	$q = "DELETE FROM classification_apply WHERE type_id = '$type_id' AND classification_id = '$classification_id';";
	return $donut['db']->query($q);
}


function pClassificationName($id)
{
	global $donut;
	$q = "SELECT * FROM classifications WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}




function pGetSubclassificationsApply($id, $offset = ''){
	global $donut;
	$q = "SELECT * FROM subclassification_apply WHERE subclassification_id = ".$id." $offset";
	$rs = $donut['db']->query($q);
	return $rs;
}

function pCountSubclassificationsApply($id){
	global $donut;
	$q = "SELECT * FROM subclassification_apply WHERE subclassification_id = ".$id;
	$rs = $donut['db']->query($q);
	return $rs->rowCount();
}


function pExistSubclassificationApply($subclassification_id, $type_id){
	global $donut;
	$q = "SELECT * FROM subclassification_apply WHERE classification_id = '$type_id' AND subclassification_id = '$subclassification_id'";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return true;
}


function pGetSubclassifications($type = 0, $force = false, $force_get = 0, $offset = ''){

	global $donut;
	if($type == 0){
		$q = "SELECT * FROM subclassifications $offset";
		$rs = $donut['db']->query($q);
		return $rs;
	}
	elseif(!$force){
		$q = "SELECT * FROM subclassification_apply WHERE type_id = ".$type." $offset";
		$rs = $donut['db']->query($q);
		$results = array();
		if($rs->rowCount() == 0)
			return false;
		else{
			while($subclassification_apply = $rs->fetchObject()){
				$results[] = pGetSubclassification($subclassification_apply->subclassification_id);
			}
		return $results;
		}
	}
	else{
		$q = "SELECT * FROM subclassifications WHERE id = ".$force_get." $offset";
		$rs = $donut['db']->query($q);
		$results = array();
		if($rs->rowCount() == 0)
			return false;
		else{
			while($subclassification_apply = $rs->fetchObject()){
				$results[] = $subclassification_apply;
			}
		return $results;
		}
	}

}

function pGetSubclassification($id)
{
	global $donut;
	$q = "SELECT * FROM subclassifications WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}



function pSubclassificationUpdate($id, $name, $shortname, $entry, $entry_short){

	global $donut;
	$q = "UPDATE subclassifications SET name = '$name', short_name = '$shortname', native_hidden_entry  = '$entry', native_hidden_entry_short  = '$entry_short' WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pSubclassificationAdd($name, $shortname, $entry, $entry_short){

	global $donut;
	$q = "INSERT INTO subclassifications VALUES (NULL, '$name', '$shortname', '$entry', '$entry_short');";
	return $donut['db']->query($q);
}

function pSubclassificationApplyAdd($subclassification_id, $type_id){

	global $donut;
	$q = "INSERT INTO subclassification_apply VALUES (NULL, '$subclassification_id', '$type_id');";
	return $donut['db']->query($q);
}


function pSubclassificationDelete($id){

	global $donut;
	$q = "DELETE FROM subclassifications WHERE id = $id";
	return $donut['db']->query($q);
}

function pSubclassificationApplyDelete($type_id, $subclassification_id){

	global $donut;
	$q = "DELETE FROM subclassification_apply WHERE classification_id = '$type_id' AND subclassification_id = '$subclassification_id';";
	return $donut['db']->query($q);
}


function pSubclassificationName($id)
{
	global $donut;
	$q = "SELECT * FROM subclassifications WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}



function pGetNumbers($type = 0, $force = false, $force_get = 0, $offset = ''){

	global $donut;
	if($type == 0){
		$q = "SELECT * FROM numbers $offset";
		$rs = $donut['db']->query($q);
		return $rs;
	}
	elseif(!$force){
		$q = "SELECT * FROM number_apply WHERE type_id = ".$type. " $offset";
		$rs = $donut['db']->query($q);
		$results = array();
		if($rs->rowCount() == 0)
			return false;
		else{
			while($number_apply = $rs->fetchObject()){
				$results[] = pGetNumber($number_apply->number_id);
			}
		return $results;
		}
	}
	else{
		$q = "SELECT * FROM numbers WHERE id = ".$force_get." $offset";
		$rs = $donut['db']->query($q);
		$results = array();
		if($rs->rowCount() == 0)
			return false;
		else{
			while($number_apply = $rs->fetchObject()){
				$results[] = $number_apply;
			}
		return $results;
		}
	}

}


function pCountNumbersApply($id){
	global $donut;
	$q = "SELECT * FROM number_apply WHERE number_id = ".$id;
	$rs = $donut['db']->query($q);
	return $rs->rowCount();
}


function pExistNumberApply($number_id, $type_id){
	global $donut;
	$q = "SELECT * FROM number_apply WHERE type_id = '$type_id' AND number_id = '$number_id'";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return true;
}

function pGetNumbersApply($id, $offset = ''){
	global $donut;
	$q = "SELECT * FROM number_apply WHERE number_id = ".$id." $offset";
	$rs = $donut['db']->query($q);
	return $rs;
}

function pNumberApplyDelete($type_id, $number_id){

	global $donut;
	$q = "DELETE FROM number_apply WHERE type_id = '$type_id' AND number_id = '$number_id';";
	return $donut['db']->query($q);
}

function pNumberApplyAdd($number_id, $type_id){

	global $donut;
	$q = "INSERT INTO number_apply VALUES (NULL, '$number_id', '$type_id');";
	return $donut['db']->query($q);
}


function pNumberUpdate($id, $name, $shortname, $entry, $entry_short){

	global $donut;
	$q = "UPDATE numbers SET name = '$name', short_name = '$shortname', native_hidden_entry  = '$entry', native_hidden_entry_short  = '$entry_short' WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pNumberAdd($name, $shortname, $entry, $entry_short){

	global $donut;
	$q = "INSERT INTO numbers VALUES (NULL, '$name', '$shortname', '$entry', '$entry_short');";
	return $donut['db']->query($q);
}


function pNumberDelete($id){

	global $donut;
	$q = "DELETE FROM numbers WHERE id = $id";
	return $donut['db']->query($q);
}


function pNumberName($id)
{
	global $donut;
	$q = "SELECT * FROM numbers WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}







function pGetModes($type = 0, $offset = ''){

	global $donut;

	if($type == 0)
		$q = "SELECT * FROM modes $offset";
	else
		$q = "SELECT * FROM mode_apply WHERE type_id = ".$type." $offset";
	
	$rs = $donut['db']->query($q);
	$results = array();

	if($type == 0)
		return $rs;


	if($rs->rowCount() == 0)
		return false;
	else{
		while($mode_apply = $rs->fetchObject()){
			$results[] = pGetMode($mode_apply->mode_id);
		}
		return $results;
	}
}

function pGetModeTypes($type = 0, $offset = ''){

	global $donut;

	$q = "SELECT * FROM mode_types $offset";
	
	return $donut['db']->query($q);

}


function pModeTypeName($id){

	global $donut;

	$q = "SELECT name FROM mode_types WHERE id = $id LIMIT 1;";

	$rs = $donut['db']->query($q);

	if(!($rr = $rs->fetchObject()))
		return '';

	return $rr->name;

}


function pGetModeType($id){

	global $donut;

	$q = "SELECT * FROM mode_types WHERE id = $id LIMIT 1;";

	$rs = $donut['db']->query($q);

	if(!($rr = $rs->fetchObject()))
		return '';

	return $rr;

}

function pModeTypeDelete($id){

	global $donut;
	$q = "DELETE FROM mode_types WHERE id = '$id';";
	return $donut['db']->query($q);
}

function pModeTypeAdd($name){

	global $donut;
	$q = "INSERT INTO mode_types VALUES (NULL, '$name');";
	return $donut['db']->query($q);
}

function pModeTypeUpdate($id, $name){

	global $donut;
	$q = "UPDATE mode_types SET name = '$name' WHERE id = $id;";
	return $donut['db']->query($q);
}



function pGetMode($id)
{
	global $donut;
	$q = "SELECT * FROM modes WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}


function pCountModesApply($id){
	global $donut;
	$q = "SELECT * FROM mode_apply WHERE mode_id = ".$id;
	$rs = $donut['db']->query($q);
	return $rs->rowCount();
}


function pExistModeApply($mode_id, $type_id){
	global $donut;
	$q = "SELECT * FROM mode_apply WHERE type_id = '$type_id' AND mode_id = '$mode_id'";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return true;
}

function pGetModesApply($id, $offset = ''){
	global $donut;
	$q = "SELECT * FROM mode_apply WHERE mode_id = ".$id."$offset";
	$rs = $donut['db']->query($q);
	return $rs;
}

function pModeApplyDelete($type_id, $mode_id){

	global $donut;
	$q = "DELETE FROM mode_apply WHERE type_id = '$type_id' AND mode_id = '$mode_id';";
	return $donut['db']->query($q);
}

function pModeApplyAdd($mode_id, $type_id){

	global $donut;
	$q = "INSERT INTO mode_apply VALUES (NULL, '$mode_id', '$type_id', '');";
	return $donut['db']->query($q);
}


function pModeUpdate($id, $name, $shortname, $entry, $mode_type_id){

	global $donut;
	$q = "UPDATE modes SET name = '$name', short_name = '$shortname', hidden_native_entry  = '$entry', mode_type_id = '$mode_type_id'
	 WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pModeAdd($name, $shortname, $entry, $mode_type_id){

	global $donut;
	$q = "INSERT INTO modes VALUES (NULL, '$name', '$shortname', '$entry', '$mode_type_id');";
	if($rs = $donut['db']->query($q))
	{
		return true;
	}
	else
		return false;
}


function pModeDelete($id){

	global $donut;
	$q = "DELETE FROM modes WHERE id = $id";
	return $donut['db']->query($q);
}


function pModeName($id)
{
	global $donut;
	$q = "SELECT name FROM modes WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}


function pModeShortName($id)
{
	global $donut;
	$q = "SELECT short_name FROM modes WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->short_name;
	}
	else
	{
		return false;
	}
}




function pGetType($id)
{
	global $donut;
	$q = "SELECT * FROM types WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}

function pGetTypes($offset = ''){

	global $donut;
	$q = "SELECT * FROM types $offset";
	return $donut['db']->query($q);
}


function pTypeUpdate($id, $name, $shortname, $entry, $entry_short, $inflect_classifications){

	global $donut;
	$q = "UPDATE types SET name = '$name', short_name = '$shortname', native_hidden_entry  = '$entry', native_hidden_entry_short  = '$entry_short', inflect_classifications = '$inflect_classifications' WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pTypeAdd($name, $shortname, $entry, $entry_short, $inflect_classifications){

	global $donut;
	$q = "INSERT INTO types  VALUES (NULL, '$name', '$shortname', '$entry', '$entry_short', '$inflect_classifications');";
	return $donut['db']->query($q);
}


function pTypeDelete($id){

	global $donut;
	$q = "DELETE FROM types WHERE id = $id";
	return $donut['db']->query($q);
}


function pTypeName($id)
{
	global $donut;
	$q = "SELECT * FROM types WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}

function pTypeInflectClassifications($id)
{		global $donut;
	$q = "SELECT * FROM types WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return ($rt->inflect_classifications == 1);
	}
	return false;
}



function pGetSubModes($type = 0, $offset = ''){

	global $donut;


	if($type == 0)
		$q = "SELECT * FROM submodes $offset";
	else
		$q = "SELECT * FROM submode_apply WHERE type_id = ".$type." $offset";
	


	$rs = $donut['db']->query($q);
	$results = array();

	if($type == 0)
		return $rs;


	if($rs->rowCount() == 0)
		return false;
	else{
		while($submode_apply = $rs->fetchObject()){
			$results[] = pGetSubMode($submode_apply->submode_id);
		}
		return $results;
	}
}



function pGetSubMode($id)
{
	global $donut;
	$q = "SELECT * FROM submodes WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}


function pCountSubmodesApply($id, $offset = ''){
	global $donut;
	$q = "SELECT * FROM submode_apply WHERE submode_id = ".$id." $offset";
	$rs = $donut['db']->query($q);
	return $rs->rowCount();
}


function pExistSubmodeApply($submode_id, $type_id){
	global $donut;
	$q = "SELECT * FROM submode_apply WHERE type_id = '$type_id' AND submode_id = '$submode_id'";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() == 0)
		return false;
	else
		return true;
}

function pGetSubmodesApply($id){
	global $donut;
	$q = "SELECT * FROM submode_apply WHERE submode_id = ".$id;
	$rs = $donut['db']->query($q);
	return $rs;
}

function pSubModeApplyDelete($type_id, $submode_id){

	global $donut;
	$q = "DELETE FROM submode_apply WHERE type_id = '$type_id' AND submode_id = '$submode_id';";
	return $donut['db']->query($q);
}

function pSubModeApplyAdd($submode_id, $type_id){

	global $donut;
	$q = "INSERT INTO submode_apply VALUES (NULL, '$submode_id', '$type_id');";
	return $donut['db']->query($q);
}


function pSubModeUpdate($id, $name, $shortname, $entry, $mode_type_id){

	global $donut;
	$q = "UPDATE submodes SET name = '$name', short_name = '$shortname', hidden_native_entry  = '$entry', 
		mode_type_id = '$mode_type_id'
	 WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pSubModeAdd($name, $shortname, $entry, $mode_type_id){

	global $donut;
	$q = "INSERT INTO submodes VALUES (NULL, '$name', '$shortname', '$entry', '$mode_type_id');";
	if($rs = $donut['db']->query($q))
	{
		if($aux_mode_id == '0')
		{
			$id = $donut['db']->lastInsertId();
			$q = "UPDATE modes SET aux_mode_id = '$id' WHERE id = '$id';";
			return $donut['db']->query($q);
		}
	}
	else
		return false;
}


function pSubModeDelete($id){

	global $donut;
	$q = "DELETE FROM modes WHERE id = $id";
	return $donut['db']->query($q);
}


function pSubModeName($id)
{
	global $donut;
	$q = "SELECT name FROM submodes WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}


function pSubModeShortName($id)
{
	global $donut;
	$q = "SELECT short_name FROM submodes WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->short_name;
	}
	else
	{
		return false;
	}
}




function pGetRulegroups($offset = ''){

	global $donut;
	return $donut['db']->query("SELECT * FROM submode_groups $offset;");

}


function pGetRulegroupMembers($id, $offset = ''){

	global $donut;

	$q = "SELECT * FROM submode_group_members WHERE submode_group_id = $id $offset";
	
	$rs = $donut['db']->query($q);
	$results = array();

	if($type == 0)
		return $rs;

}



function pGetRulegroup($id)
{
	global $donut;
	$q = "SELECT * FROM submode_groups WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}



function pRulegroupUpdate($id, $name, $type){

	global $donut;
	$q = "UPDATE submode_groups SET name = '$name', type_id = '$type'
	 WHERE id = '$id';";
	return $donut['db']->query($q);
}


function pRulegroupAdd($name, $type){

	global $donut;
	$q = "INSERT INTO submode_groups VALUES (NULL, '$name', '$type');";
	if($rs = $donut['db']->query($q))
	{
		return true;
	}
	else
		return false;
}


function pRulegroupDelete($id){

	global $donut;
	$q = "DELETE FROM submode_groups WHERE id = $id";
	return $donut['db']->query($q);
}


function pRulegroupName($id)
{
	global $donut;
	$q = "SELECT name FROM submode_groups  WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt->name;
	}
	else
	{
		return false;
	}
}



function pGetNumber($id)
{
	global $donut;
	$q = "SELECT * FROM numbers WHERE id = '".$id."' LIMIT 1;";
	$rs = $donut['db']->query($q);
	if($rs->rowCount() != 0)
	{
		$rt = $rs->fetchObject();
		return $rt;
	}
	else
	{
		return false;
	}
}

?>