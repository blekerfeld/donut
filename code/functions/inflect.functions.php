<?php

function pSearchAndInflect($word, $type, $classification, $mode, $submode, $number, $override_word, $force_no_aux, $subclassification = 0)
	{

		$inflections = pGetInflections($word->id, $type, $classification, $mode, $submode, $number, $subclassification);
		$inflected = array();


		if($inflections->rowCount() == 0)
			$inflected[] = (pInflect($word->id, false, $number, $mode, $submode, $classification, 0, $override_word, $force_no_aux, $subclassification));
		else{
			while($inflection = $inflections->fetchObject()){
				$inflected[] = (pInflect($word->id, $inflection, $number, $mode, $submode, $classification, 0, $override_word, $force_no_aux, $subclassification));
			}

		}

		return implode(", ", $inflected);

	}



	function pGetInflection($id)
	{
		global $donut;
		$q = "SELECT * FROM inflections WHERE id = ".$id." LIMIT 1;";
		$rs = $donut['db']->query($q);
		if($rs->rowCount() != 0)
		{
			return $rs->fetchObject();
		}
		else
		{
			return false;
		}
	}
	

	function pGetPreviewInflections($type = 0){

		global $donut;
			$q = "SELECT * FROM preview_inflections WHERE type_id = ".$type;
			$rs = $donut['db']->query($q);
			return $rs;
	}


	function pGetInflections($word_id, $type, $classification, $mode, $submode, $number, $subclassification = 0)
	{
		global $donut;


		// Is the current mode setting in combination with a number part of a group?
		$q = "SELECT submode_group_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification'";

		// if($subclassification != 0)
		// 	$q .= " AND subclassification_id = '$subclassification'";


		$check_for_submode_group = $donut['db']->query($q);
		$submodetext = '';
		if($check_for_submode_group->rowCount() != 0)
		{
			$submode_group = $check_for_submode_group->fetchObject();
			// We need to search after rules that apply on the subrule mode
			$submodetext = "(applies_submode_group = '1' AND submode_group_id = '".$submode_group->submode_group_id."') OR ";
		}

			$submodetext .= "(classification_id = '".$classification."' AND number_id = '".$number."' AND mode_id = '".$mode."' AND submode_id = '".$submode.'\')';
		// Is there maybe an irregular inflection?
		$q = "SELECT * FROM inflections WHERE irregular = '1' AND irregular_word_id =  '".$word_id."' AND type_id = '".$type."' AND ".$submodetext.";";
		$check_for_irregular = $donut['db']->query($q);
		if($check_for_irregular->rowCount() != 0)
			return $check_for_irregular;
		else{
			return $donut['db']->query("SELECT * FROM inflections WHERE irregular = '0' AND type_id = '".$type."' AND ".$submodetext."");
		}

	}

	function pGetInflections_aux($type, $classification, $number, $aux_id, $mode, $submode, $aux_level, $subclassification = 0)
	{
		global $donut;


		$aux_word = pGetWord($aux_id);

		// Find the aux inflection

		$inflections = pGetInflections($aux_word->id, $aux_word->type_id, $aux_word->classification_id, $mode, $submode, $number, $subclassification);


		if($inflections->rowCount() != 0){
			// Inflect the aux
			while($inflection = $inflections->fetchObject()){
					$aux = pInflect($aux_word->id, $inflection, $number, $mode, $submode, $classification, $aux_level + 1, $subclassification);
				}
		}
		else{
			$aux = pInflect($aux_word->id, false, $number, $mode, $submode, $classification, $aux_level + 1, $subclassification);
		}

		
		return $aux;
		
	}


	function pReplaceStem($word_id, $original, $type, $classification, $subclassification, $number, $mode, $submode){

		global $donut;

		// Is the current mode setting in combination with a number part of a group?
		$q = "SELECT submode_group_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification'";

		$check_for_submode_group = $donut['db']->query($q);
		
		if($check_for_submode_group->rowCount() != 0)
		{

			$submode = $check_for_submode_group->fetchObject();
			$submode_id = $submode->submode_group_id;


			$q = "SELECT * FROM stems WHERE word_id = $word_id AND applies_submode_group = 1 AND submode_group_id = $submode_id LIMIT 1;";

			$get_stems = $donut['db']->query($q);

			if($get_stems->rowCount() != 0){

				$stem = $get_stems->fetchObject();

				return $stem->stem_override;

			}
			else{

				return $original;

			}


		}
		else
			return $original;

	}



	function pFindScript($place, $type, $classification, $subclassification, $number, $mode, $submode){

		global $donut;

		// Is the current mode setting in combination with a number part of a group?
		$q = "SELECT submode_group_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification'";

		$check_for_submode_group = $donut['db']->query($q);
		
		if($check_for_submode_group->rowCount() != 0)
		{

			$submode = $check_for_submode_group->fetchObject();
			$submode_id = $submode->submode_group_id;


			$q = "SELECT script_id FROM inflection_script_submode_groups WHERE submode_group_id = $submode_id LIMIT 1;";

			$get_script = $donut['db']->query($q);

			if($get_script->rowCount() != 0){

				$script_link = $get_script->fetchObject();

				$q = "SELECT name FROM inflection_scripts WHERE id = $script_link->script_id  AND execute_prior_to_inflection = $place ORDER BY inflection_scripts.weight DESC";


				$get_script_name = $donut['db']->query($q);
				$return = array();

				if($get_script->rowCount() != 0){

					while($script_name = $get_script_name->fetchObject()){
						$return[] = $script_name->name;
					}

					return $return;
				}

				else{
					return false; 
				}

			}
			else{

				return false;

			}


		}
		else
			return false;

	}


	function pInflect($word_id, $inflection, $number, $mode, $submode, $classification, $aux_level = 0, $override_word = '', $force_no_aux = 0, $subclassification = 0){

			
			global $donut;

			$word = pGetWord($word_id);
			
			$mode_entry = pGetMode($mode);

			$inflect_this =  $word->native;
			if($override_word != '')
				$inflect_this  = $override_word;


			// Do we need a stem

			$inflect_this = pReplaceStem($word->id, $inflect_this, $word->type_id, $classification, $subclassification, $number, $mode, $submode);


			// Mark an irregular stem
		
			if($inflect_this != $word->native)
			{
				$inflect_this = "**".$inflect_this."&&";
			}


			// let's call for the script prior to inflection

			if($script_names_prior = pFindScript(1, $word->type_id, $classification, $subclassification, $number, $mode, $submode) and !($script_names_prior == false))
			{

				foreach ($script_names_prior as $script_name_prior) {
					$function_name = "pExecuteScript_".$script_name_prior;
					require_once $donut['root_path'].'/library/inflection_scripts/'.$script_name_prior.'.script.php';
					$inflect_this = $function_name($inflect_this, $word, $mode, $submode, $number);
				}

			}

			// If we have a zero inflection, we need to watch out!
			if($inflection != false){
				

				// Maybe irregular?
				if($inflection->irregular == 1)
					$inflect_this = $inflection->irregular_override;
				else{
					// we need to inflect

					// trim start
					@$inflect_this = pStr($inflect_this)->replacePrefix($inflection->trim_begin, '');

					// trim end
					@$inflect_this = pStr($inflect_this)->replaceSuffix($inflection->trim_end, '');

					// prefix
					if($inflection->prefix != '')
						$inflect_this = "<strong>".$inflection->prefix."</strong>". $inflect_this;

					// suffix
					if($inflection->suffix != '')
						$inflect_this = $inflect_this . "<strong>".$inflection->suffix."</strong>";
				}

			}

			// Do we need to get an aux?

			// Allowing aux level only to four

			if(!($aux_level > 1) AND $force_no_aux != 1)
			{


				$q = "SELECT submode_group_id, aux_mode_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification' LIMIT 1;";
				$submode_group_get = $donut['db']->query($q);

				if($submode_group_get->rowCount() != 0)
				{




					$submode_group = $submode_group_get->fetchObject();
					$submode_group_id = $submode_group->submode_group_id;
					$submode_group_entry = pGetRulegroup($submode_group_id);

					if($submode_group_entry->type_id == $word->type_id){

						$q = "SELECT * FROM aux_conditions WHERE submode_group_id = '$submode_group_id';";

						$check_for_aux = $donut['db']->query($q);

						$aux = "";

						if($check_for_aux->rowCount() != 0){

							$aux_condition = $check_for_aux->fetchObject();


							$aux = pGetInflections_aux($word->type_id, $word->classification_id, $number, $aux_condition->aux_id, $submode_group->aux_mode_id, $submode, $aux_level);

							// aux placement
							if(@$aux_condition->aux_placement == 0)
								$inflect_this = '<em>'.$aux . '</em> ' . $inflect_this;
							else
								$inflect_this .= ' '.$aux;

						}



					}

					
	

				}

			}


			// let's call for the script after inflection

			if($script_names_after = pFindScript(0, $word->type_id, $classification, $subclassification, $number, $mode, $submode) and !($script_names_after == false))
			{

				foreach ($script_names_after as $script_name_after) {
					$function_name = "pExecuteScript_".$script_name_after;
					require_once $donut['root_path'].'/library/inflection_scripts/'.$script_name_after.'.script.php';
					$inflect_this = $function_name($inflect_this, $word, $mode, $submode, $number);
				}

			}


			// Replacing placeholders

		
			$inflect_this = str_replace(array('**', '&&'), array('<em>', '</em>'), $inflect_this);


			// return our inflected word
			return html_entity_decode($inflect_this);
	}

?>