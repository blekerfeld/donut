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
		$rs = pQuery($q);
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
			$rs = pQuery($q);
			return $rs;
	}


	function pGetInflections($word_id, $type, $classification, $mode, $submode, $number, $subclassification = 0)
	{
		global $donut;


		// Is the current mode setting in combination with a number part of a group?
		$q = "SELECT submode_group_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification'";

		// if($subclassification != 0)
		// 	$q .= " AND subclassification_id = '$subclassification'";


		$check_for_submode_group = pQuery($q);
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
		$check_for_irregular = pQuery($q);
		if($check_for_irregular->rowCount() != 0)
			return $check_for_irregular;
		else{
			return pQuery("SELECT * FROM inflections WHERE irregular = '0' AND type_id = '".$type."' AND ".$submodetext."");
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

		$check_for_submode_group = pQuery($q);
		
		if($check_for_submode_group->rowCount() != 0)
		{

			$submode = $check_for_submode_group->fetchObject();
			$submode_id = $submode->submode_group_id;


			$q = "SELECT * FROM stems WHERE word_id = $word_id AND applies_submode_group = 1 AND submode_group_id = $submode_id LIMIT 1;";

			$get_stems = pQuery($q);

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

		// Is the current mode setting in combination with a number part of a group?
		$q = "SELECT submode_group_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification'";

		$check_for_submode_group = pQuery($q);
		
		if($check_for_submode_group->rowCount() != 0)
		{

			$submode = $check_for_submode_group->fetchObject();
			$submode_id = $submode->submode_group_id;


			$q = "SELECT script_id FROM inflection_script_submode_groups WHERE submode_group_id = $submode_id LIMIT 1;";

			$get_script = pQuery($q);

			if($get_script->rowCount() != 0){

				$script_link = $get_script->fetchObject();

				// Getting the script then.

				$q = "SELECT name FROM inflection_scripts WHERE (id = $script_link->script_id  AND execute_prior_to_inflection = $place) OR (execute_always = 1) ORDER BY inflection_scripts.weight DESC";


				$get_script_name = pQuery($q);
				$return = array();

				if($get_script->rowCount() != 0){

					while($script_name = $get_script_name->fetchObject())
						$return[] = $script_name->name;

					return $return;
				}

				else
					return false; 

			}
			else
				return false;



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
					require_once $donut['root_path'].'/library/scripts/'.$script_name_prior.'.script.php';
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

			// Replacing placeholders

		
			$inflect_this = str_replace(array('**', '&&'), array('<em>', '</em>'), $inflect_this);


			// Caching
			if($override_word != '~')
				if(!(strip_tags($inflect_this) == $word->native))
					pCacheInflection($inflect_this, $word_id, md5($mode.$submode.$number.$classification.$subclassification));

			// Do we need to get an aux?

			// Allowing aux level only to four

			if(!($aux_level > 1) AND $force_no_aux != 1)
			{


				$q = "SELECT submode_group_id, aux_mode_id FROM submode_group_members WHERE mode_id = '$mode' AND submode_id = '$submode' AND number_id = '$number' AND classification_id = '$classification' LIMIT 1;";
				$submode_group_get = pQuery($q);

				if($submode_group_get->rowCount() != 0)
				{




					$submode_group = $submode_group_get->fetchObject();
					$submode_group_id = $submode_group->submode_group_id;
					$submode_group_entry = pGetRulegroup($submode_group_id);

					if($submode_group_entry->type_id == $word->type_id){

						$q = "SELECT * FROM aux_conditions WHERE submode_group_id = '$submode_group_id';";

						$check_for_aux = pQuery($q);

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
					require_once $donut['root_path'].'/library/scripts/'.$script_name_after.'.script.php';
					$inflect_this = $function_name($inflect_this, $word, $mode, $submode, $number);
				}

			}





			// return our inflected word
			return html_entity_decode($inflect_this);
	}


	function pCacheInflection($inflection, $word, $inflection_hash){

		// Let's see if this exists
		$check = pQuery("SELECT * FROM inflection_cache WHERE word_id = $word AND inflection_hash = '$inflection_hash' LIMIT 1;");
		if($check->rowCount() != 0 AND ($cache = $check->fetchObject()))
			if($cache->inflection != pEscape(strip_tags($inflection)))
				pQuery("DELETE FROM inflection_cache WHERE id = ".$cache->id);
			else
				return false;
		
		// Insert the new one, only if we are still alive
		$check = pQuery("SELECT * FROM inflection_cache WHERE inflection = '".pEscape(strip_tags($inflection))."';");
		if($check->rowCount() == 0)
			return pQuery("INSERT INTO inflection_cache VALUES (NULL, '".pEscape(strip_tags($inflection))."', $word,  '".pEscape($inflection_hash)."');");
		else
			return false;

	}


function pAllInflections($word, $type){
	
	$output = '';

	// Get modes
	$modes = pGetModes($word->type_id);

	// Mode scopus

	foreach($modes as $mode){

		// Get numbers
		$numbers = pGetNumbers($mode->mode_type_id);




		// Number scopus
		if (is_array($numbers) and count($numbers) != 0)
		{

			// Start the table
			$output .= '<div class="floatleft" style="width: 300px;margin-right: 4px;"><table class="verbs mode_'.$mode->id.'">
				<tr class="temps"><td colspan=2><b>'.strtoupper($mode->name).'</b></td></tr>';


			foreach($numbers as $number){

				// Check if this number maybe needs to be excluded...?
				if(pExcludeFromInflections('numbers', 'modes', $number->id, $mode->id))
					continue;

				// Getting the submodes
				$submodes = pGetSubModes($mode->mode_type_id);

				$output .=  '<tr><td class="number" colspan=2><b>'.$number->name.'</b></td></tr>';


				foreach($submodes as $submode){

					$td_contents = "";

					$submode_name = pGetSubmodeNative($submode, $number);
		

					$td_contents .= '<tr class="mode_'.$mode->id.'_number_'.$number->id.'"><td class="singa rowname">'.$submode_name.'</td>';

					$gotten_classifications = array();
					$show_classification_name = false;

					// Does this word need to be inflected by its classification types?
					if($type->inflect_classifications == 1){
						// YES we need to get all the classifications
						$gotten_classifications = pGetClassifications($type->id);
						$show_classification_name = true;
					}
					else
						// Just getting one for the sake of the loop!
						$gotten_classifications = pGetClassifications($type->id, true, $word->classification_id);
					
					if($show_classification_name)
						$td_contents .= '<td class="sing inflection" style="padding: 0!important;margin:0px;">';
					else
						$td_contents .= '<td class="sing inflection" style="width:50%">';

					if($show_classification_name)
							$td_contents .= '<table class="verbs inside_'.$submode->id.'" style="margin:0px;">';

					foreach ($gotten_classifications as $inflect_classification) {
							// The main inflection time!


						if($show_classification_name)
							$td_contents .= "<tr><td style='width:50%' class='classa rowname'>".$inflect_classification->name."</td><td class='singb inflection' style='width:50%'>";	

						$inflections = pGetInflections($word->id, $word->type_id, $inflect_classification->id, $mode->id, $submode->id, $number->id, $word->subclassification_id);

						if($inflections->rowCount() == 0)
								$td_contents .= "<span class='native'>".pInflect($word->id, false, $number->id, $mode->id, $submode->id, $word->classification_id, 0, '', 0, $word->subclassification_id)."</span><br />";
						else{
							while($inflection = $inflections->fetchObject()){
								$td_contents .= "<span class='native'>".pInflect($word->id, $inflection, $number->id, $mode->id, $submode->id, $word->classification_id, 0, '', 0, $word->subclassification_id)."</span><br />";
							}
						}

						if($show_classification_name)
							$td_contents .= "</td>";

					}

					if($show_classification_name)
						$td_contents .= '</table>';


						$td_contents .= '</td></tr>';


					
					if (strpos($td_contents, '@//DISABLE\\') === false)
					    $output .= $td_contents; 

					}
				


					$output .= pJSCompactTables($number->id, 'mode_'.$mode->id.'_number_');

				}

						$output .=  '</table></div>';
			}

			// The javascript to remove duplicate inflections


			

		}



	return $output;	

}


function pJSCompactTables($id, $prefix = "number_"){
	return "<script>
				var seen = {};

				$('tr.".$prefix.$id.":has(\"td.inflection\")').each(function() {
					var appendTxt = '';
					var restore = {};
				    var txt = $(this).find('td.inflection').text();
				    var txt_name = $(this).find('td.rowname').text();
				    if (seen[txt] && $(this).children().length != 1){

				    	$(this).hide();
				    	
				        var results = $('tr.".$prefix.$id."  td.tested').filter(function() {
				            return $(this).text() === txt;
				        });

				        selector = 'td.rowname:not(:contains(\"'+ txt_name +'\"))';

				        ding = results.parent().find(selector);
						
						appendTxt = '/' + txt_name;
						ding.append(appendTxt);
						
				    }
				    else{
				        seen[txt] = true;
				        $(this).find('td.inflection').removeClass('inflection').addClass('tested');
				    }

				});
			</script>";

}


// Check whether a certain field should be excluded from a table or not

function pExcludeFromInflections($exclude_table, $from_table, $exclude_id, $from_id){

	$check = pQuery("SELECT id FROM inflections_exclude_from_table WHERE exclude_table = '$exclude_table' AND from_table = '$from_table' AND exclude_id = '$exclude_id' AND from_id = '$from_id';");



	if($check->rowCount() == 0)
		return false;
	return true;
}
