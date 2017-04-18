<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: apps.functions.php
*/

	function pGetAlphabet()
	{
		global $donut;
		$alphabet = pQuery("SELECT * FROM graphemes WHERE in_alphabet = 1 ORDER by graphemes.order");
		return $alphabet;
	}

	function pGetAlphabetByLength()
	{
		global $donut;
		$alphabet = pQuery("SELECT * FROM graphemes WHERE in_alphabet = 1 ORDER BY LENGTH(grapheme);");
		return $alphabet;
	}

	function pGetAlphabetByStart($start)
	{
		global $donut;
		$alphabet = pQuery("SELECT * FROM graphemes WHERE in_alphabet = 1 AND grapheme LIKE '$start%'");
		return $alphabet;
	}

	function pGetAlphabetGroupString($group)
	{
		global $donut;
		$alphabet = pQuery("SELECT * FROM graphemes WHERE groupstring = '$group' ORDER by graphemes.order");
		$string = '';
		while($letter = $alphabet->fetchObject()){
			$string .= $letter->grapheme;
		}
		return $string;
	}

	function pGetIPA($string){
			global $donut;
			$rs = pQuery("SELECT * FROM ipa_regex ORDER BY sort;");
			// Get our groups
			$group_con = pGetAlphabetGroupString('CON');
			$group_vow = pGetAlphabetGroupString('CON');
			$regex_search = array();
			$regex_replace = array();
			while ($regex_item = $rs->fetchObject()) {
       			$regex_search[] = "/".str_replace(array("-CON-","-VOW-"), array($group_con,$group_vow), $regex_item->search)."/";
       			$regex_replace[] = pParseIPA_Char($regex_item->replace);
       		}
       		$string = preg_replace($regex_search, $regex_replace, $string);
       		return pAlphabetIPAReplace($string);
	}


	function pIPA_name($mode_id, $place_id, $articulation_id, $vowel = false){

		global $donut;

		if(!$vowel){

			$mode_g = pQuery("SELECT * FROM ipa_c_mode WHERE id = $mode_id LIMIT 1;");
			$mode = $mode_g->fetchObject();

			$place_g = pQuery("SELECT * FROM ipa_c_place WHERE id = $place_id LIMIT 1;");
			$place = $place_g->fetchObject();

			$art_g = pQuery("SELECT * FROM ipa_c_articulation WHERE id = $articulation_id LIMIT 1;");
			$art = $art_g->fetchObject();

			// Mode name neednt be shown if there is only one
			$mode_check = pQuery("SELECT id FROM ipa_c WHERE (c_mode_id = 1 OR c_mode_id = 2) AND c_place_id = $place_id AND c_articulation_id = $articulation_id");

			if($mode_check->rowCount() == 1)
				$mode_name = '';
			else
				@$mode_name = $mode->name." ";

			if($art->id == 2)
				$art_name = 'Stop';
			else
				$art_name = $art->name;

			return strtolower($mode_name.$place->name." ".$art_name);


		}

		else{

		

			$mode_g = pQuery("SELECT * FROM ipa_v_mode WHERE id = $mode_id LIMIT 1;");
			$mode = $mode_g->fetchObject();

			$place_g = pQuery("SELECT * FROM ipa_v_place WHERE id = $place_id LIMIT 1;");
			$place = $place_g->fetchObject();

			$art_g = pQuery("SELECT * FROM ipa_v_articulation WHERE id = $articulation_id LIMIT 1;");
			$art = $art_g->fetchObject();

			// Mode name neednt be shown if there is only one
			$mode_check = pQuery("SELECT id FROM ipa_v WHERE (v_mode_id = 1 OR v_mode_id = 2) AND v_place_id = $place_id AND v_articulation_id = $articulation_id");

			
			$mode_name = $mode->name." ";

			$art_name = $art->name;

			return @strtolower($mode_name.$place->name." ".$art_name);

		

	}
}

	function pGetPolyphthongs(){

		global $donut;

		return pQuery("SELECT * FROM ipa_polyphthongs;");

	}


	function pParseIPA_Char($char){

		$char_out = '';

		if(pStartsWith($char, 'v_'))
			{
				$id = ltrim($char, 'v_');

				$char_g = pGetIPASingle_V($id);

				if($char_g == false)
				{
					$char_out.= "^IPA_ERROR_CHAR_NOT_FOUND^";
				}
				else
				{
					$char_out.= $char_g->symbol;
				}

				

			}

			elseif(pStartsWith($char, 'c_'))
			{
				$id = ltrim($char, 'c_');

				$char_g = pGetIPASingle_C($id);

				if($char_g == false)
				{
					$char_out.= "^IPA_ERROR_CHAR_NOT_FOUND^";
				}
				else
				{
					$char_out.= $char_g->symbol;
				}

			}

			elseif(pStartsWith($char, 'p_'))
			{

				$id = ltrim($char, 'p_');

				$char_g = pGetIPASingle_P($id);

				if($char_g == false)
				{
					$char_out.= "^IPA_ERROR_CHAR_NOT_FOUND^";	
				}
				else
				{
					$char_out.= pParsePolyphthong($char_g->combination);
				}
				

			}

		return $char_out;

	}

	function pParsePolyphthong($combination){

		echo $combination;

		$exploded = explode(',', $combination);

		$polyphtong = '';

		while($char = $exploded->fetchObject())
		{
			$polyphtong .= pParseIPA_Char($char);
		}

		return $polyphtong;

	}

	function pLoadIPA_V($active = false){

		global $donut;

		$ipa = array();

		if($active)
			$ipa_get = pQuery("SELECT * FROM ipa_v WHERE active = 1;");
		else
			$ipa_get = pQuery("SELECT * FROM ipa_v");


		while($ipa_row = $ipa_get->fetchObject()){

			// checking for graphmes
			$ipa_graph = pQuery("SELECT ipa_v.symbol AS symbol, ipa_v.active AS active, graphemes_ipa_v.grapheme_id, graphemes.grapheme AS grapheme FROM ipa_v INNER JOIN graphemes_ipa_v ON graphemes_ipa_v.ipa_v_id = ipa_v.id INNER JOIN graphemes ON graphemes.id = graphemes_ipa_v.grapheme_id WHERE ipa_v.active = 1 AND ipa_v.id = ".$ipa_row->id.";");

			$graphs = array();

			if($ipa_graph->rowCount() != 0){

				while($ipa_g = $ipa_graph->fetchObject()) {
					$graphs[] = $ipa_g['grapheme'];
				}

			}

			if($ipa_row->is_copy == 1){
				$ipa['copies_'.$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][] = array($ipa_row->symbol, $ipa_row->active, $ipa_row->id);
				$ipa['copies_'.$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $graphs;
				$ipa['copies_'.$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $ipa_row->v_articulation_id;
				$ipa['copies_'.$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $ipa_row->v_place_id;
				$ipa['copies_'.$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $ipa_row->v_mode_id;
			}
			else{
				$ipa[$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][] = array($ipa_row->symbol, $ipa_row->active, $ipa_row->id);
				$ipa[$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $graphs;
				$ipa[$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $ipa_row->v_articulation_id;
				$ipa[$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $ipa_row->v_place_id;
				$ipa[$ipa_row->v_mode_id][$ipa_row->v_place_id][$ipa_row->v_articulation_id][0][] = $ipa_row->v_mode_id;
			}

			

			

		}

		return $ipa;


	}

	function pLoadIPA_C($active = false){

		global $donut;

		$ipa = array();

		if($active)
			$ipa_get = pQuery("SELECT * FROM ipa_c WHERE active = 1;");
		else
			$ipa_get = pQuery("SELECT * FROM ipa_c");


		while($ipa_row = $ipa_get->fetchObject()){

			$ipa[$ipa_row->c_mode_id][$ipa_row->c_place_id][$ipa_row->c_articulation_id][] = array($ipa_row->symbol, $ipa_row->active, $ipa_row->id);

			// checking for graphmes
			$ipa_graph = pQuery("SELECT ipa_c.symbol AS symbol, ipa_c.active AS active, graphemes_ipa_c.grapheme_id, graphemes.grapheme AS grapheme FROM ipa_c INNER JOIN graphemes_ipa_c ON graphemes_ipa_c.ipa_c_id = ipa_c.id INNER JOIN graphemes ON graphemes.id = graphemes_ipa_c.grapheme_id WHERE ipa_c.active = 1 AND ipa_c.id = ".$ipa_row->id.";");

			$graphs = array();

			if($ipa_graph->rowCount() != 0){

				while($ipa_g = $ipa_graph->fetchObject()){
					$graphs[] = $ipa_g->grapheme;
				}

			}

			$ipa[$ipa_row->c_mode_id][$ipa_row->c_place_id][$ipa_row->c_articulation_id][0][] = $graphs;
			$ipa[$ipa_row->c_mode_id][$ipa_row->c_place_id][$ipa_row->c_articulation_id][0][] = $ipa_row->c_articulation_id;
			$ipa[$ipa_row->c_mode_id][$ipa_row->c_place_id][$ipa_row->c_articulation_id][0][] = $ipa_row->c_place_id;
			$ipa[$ipa_row->c_mode_id][$ipa_row->c_place_id][$ipa_row->c_articulation_id][0][] = $ipa_row->c_mode_id;

		}

		return $ipa;


	}

	function pGetIPASingle_C($id){

		global $donut;

		$get = pQuery("SELECT * FROM ipa_c WHERE id = $id LIMIT 1;");

		if($get->rowCount() == 1)
			return $get->fetchObject();
		else
			return false;

	}

	function pGetIPASingle_V($id){

		global $donut;

		$get = pQuery("SELECT * FROM ipa_v WHERE id = $id LIMIT 1;");

		if($get->rowCount() == 1)
			return $get->fetchObject();
		else
			return false;

	}


	function pGetIPASingle_P($id){

		global $donut;

		$get = pQuery("SELECT * FROM ipa_polyphthongs WHERE id = $id LIMIT 1;");

		if($get->rowCount() == 1)
			return $get->fetchObject();
		else
			return false;

	}

	function pGetIPA_CSymbol($mode, $place, $articulation, $active = false){

		global $donut;

		if($active)
			return pQuery("SELECT * FROM ipa_c WHERE c_mode_id = $mode AND c_place_id = $place AND c_articulation_id = $articulation AND active = 1;");
		else
			return pQuery("SELECT * FROM ipa_c WHERE c_mode_id = $mode AND c_place_id = $place AND c_articulation_id = $articulation;");


	}

	function pGetAlphabetArray(){
		global $donut;
		$alphabet = pQuery("SELECT * FROM graphemes WHERE in_alphabet = 1 ORDER by graphemes.order");
		$return_array = array();
		while($letter = $alphabet->fetchObject()) {
			$return_array[] = $letter->grapheme;
		}
		return $return_array;
	}

	function pAlphabetBar(){
		$alphabet = pGetAlphabet();
		$text = '<div class="alphabetbar">';
		while($grapheme = $alphabet->fetchObject())
			$text .= "<a href='".pUrl('?alphabet='.$grapheme->id)."' class='".((isset($_GET['alphabet']) AND ($_GET['alphabet'] == $grapheme->id)) ? 'selected' : '')." ".((isset($_GET['alphabetresult']) AND ($_GET['alphabetresult'] == $grapheme->id)) ? 'selected' : '')."'>".$grapheme->grapheme."</a>";
		$text .= '</div>';
		return $text;
	}

	function pGetGrapheme($id){
		global $donut;
		$get_grapheme = pQuery("SELECT * FROM graphemes WHERE id = $id;");
		if($grapheme = $get_grapheme->fetchObject())
			return $grapheme;
		else
			return false;
	}


	function pStrSplitUnicode($str, $l = 0) {
	    if ($l > 0) {
	        $ret = array();
	        $len = mb_strlen($str, "UTF-8");
	        for ($i = 0; $i < $len; $i += $l) {
	            $ret[] = mb_substr($str, $i, $l, "UTF-8");
	        }
	        return $ret;
	    }
	    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}

  
	function pLetterToNum($alphabet, $data) {
	    if($data == '' or $data == NULL)
	        return false;
	    $alpha_flip = array_flip($alphabet);
	    $return_value = -1;
	    $length = mb_strlen($data, "UTF-8");
	    if(in_array($data, $alphabet)){
	      $return_value = array_search($data, $alphabet);
	    }

	    return $return_value;
	}

	function pAlphabetIPAReplace($string){

		$alphabet = pGetAlphabetByLength();

		foreach ($alphabet as $letter) {
			if($letter['ipa'] != '')
				$string = str_replace($letter['grapheme'], pParseIPA_Char($letter['ipa']), $string);
		}

		return $string;

	}


	function pIsConsecutive($array) {
	  	if(empty($array))
	    	return false;
	    else
	    	return ((int)max($array)-(int)min($array) == (count($array)-1));
	}

	function pSortByAlphabet($words, $alphabet){

		$words_array = array();
		$count = 0;
		foreach($words as $word){

		  if(!($original_word = @$word['native']))
		  	$original_word = $word['translation'];

		  // Before we split the words, we need to loop through the array to sort it
		  $alphabet_copy = $alphabet;
		  $filter = array();
		  usort($alphabet_copy, function($a, $b) {
		    global $filter;
		    if(mb_strlen($a, "UTF-8") != 1)
		      $filter[] = $a;
		    return mb_strlen($b, "UTF-8")  - mb_strlen($a, "UTF-8");
		  });

		  if(!($word_work_with = @pStrSplitUnicode($word['native'])))
		  	$word_work_with = pStrSplitUnicode($word['translation']);

		  foreach($filter as $substr){

		    $chars = str_split_unicode($substr);
		    $poss = array();
		    foreach($chars as $char)
		    {
		      $extra_pos = strpos($original_word, $char);
		      if(!($extra_pos === false))
		        $poss[] = $extra_pos;
		    }

		    if(isConsecutive($poss) and (count($poss) == mb_strlen($substr, "UTF-8")))
		     foreach($poss as $restore_char_key => $restore_char){
		        if($restore_char_key === 0)
		          $word_work_with[$restore_char] = $substr;
		        else
		          $word_work_with[$restore_char] = NULL;
		     }
		  }


		$max = 255;

		$count = 0;

		$big_number = '';

		foreach($word_work_with as $letter){
		  
			$number = pLetterToNum($alphabet, $letter) + 1;
		 
			if($number < 10)
				$number_string = '0'.((string)$number).'9 ';
			elseif($number < 100)
				$number_string = ((string)$number).'9 ';
			else
				$number_string = "999 ";

			$big_number .= $number_string;

			$count++;

		}

		$index = str_pad($big_number, ($max - $count)*3,"000 ").$count.$word['id'];


		$words_array[$index] = array($index, $word);
		

		$big_number = '';

		}

		ksort($words_array);


		return $words_array;
	}


 ?>