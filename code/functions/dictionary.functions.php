<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: dictionary.functions.php
*/



	function pSearchDict($searchlang, $returnlang, $search, $wholeword)
	{

		global $pol;
		$r = array();
		$type = "";
		// if(polStartsWith($search, "t="))
		// {
		// 	$search1 = explode(";", $search);
		// 	$type= "type = '".substr($search1[0], 2)."' AND ";
		// 	@$search = $search1[1];
		// 	$wholeword = false;
		// }
		// if(polStartsWith($search, "flag="))
		// {
		// 	$search1 = explode(";", $search);
		// 	$type= "flag = '".substr($search1[0], 5)."' AND ";
		// 	$search = $search1[1];
		// }
		if($wholeword)
			$ww = "REGEXP '[[:<:]]".trim($search)."[[:>:]]'";
		else
			$ww = "LIKE \"%".trim($search)."%\"";

		//$q = "SELECT *, ".$searchlang." as slang, ".$returnlang." as rlang, INSTR('".htmlentities(trim($search))."', ".$searchlang.")  AS relevancy FROM dictionary  WHERE ".$type.$searchlang." ".$ww."ORDER BY relevancy DESC, rlang ASC;";	

	
		if($searchlang == 0){


			$q = "SELECT DISTINCT translation_words.word_id  
					FROM words 
					INNER JOIN translation_words ON translation_words.word_id=words.id 
					INNER JOIN translations ON translations.id=translation_words.translation_id
					WHERE native ".$ww." AND translations.language_id = '".$returnlang."' ORDER BY INSTR('".trim($search)."', translations.translation) DESC;";
	
		}
		else{

			$q = "SELECT DISTINCT translation_words.word_id FROM translations JOIN translation_words ON translation_words.translation_id = translations.id WHERE language_id = '".$searchlang."' AND translation ".$ww." ORDER BY INSTR('".trim($search)."', translation)  DESC;";		
		}



        @$rs = $pol['db']->query($q);
		if($rs->rowCount() != 0)
		{
			while($fc = $rs->fetchObject()) {
				$r[] = $fc;
			}
			return $r;
		}
		else
		{
			return false;
		}
	}


	// NOTE RETURN ARRAY, NOT OBJECT!
	function pGetWordsByStart($start, $start_not = array()){

		global $pol;

		$start_not_string = '';

		foreach ($start_not as $start_not_instance) {
			$start_not_string .= " AND native NOT LIKE '".$start_not_instance."%'";
		}

		$words = $pol['db']->query("SELECT * FROM words WHERE hidden = 0 AND native LIKE '".$start."%' ".$start_not_string .";"); 

		return $words->fetchAll();

	}

	function pGetWord($id)
	{
		global $pol;
		$q = "SELECT * FROM words WHERE id = ".$id." LIMIT 1;";
		$rs = $pol['db']->query($q);
		if($rs->rowCount() != 0)
		{
			return $rs->fetchObject();
		}
		else
		{
			return false;
		}
	}

	
	
	function pWordDelete($id)
	{
		global $pol;
		$pol['db']->query("DELETE FROM words WHERE id = $id;");
	}
	

	function pWordGender($text = false, $wordgender = "", $wordid = ""){
		$genders = array("animate","inanimate");
		if($text)
		{
			$rg="";
			foreach($genders as $g) 
    		{
    			if($wordgender==$g)
    			{
    				$rg=$g;
    			}
    		}
			return $rg;
		}
		else
		{
			$rtext = '<select id="gender'.$wordid.'">
                        <option value="">none (please choose one in case of a noun)</option>';
            foreach($genders as $g) 
    		{
    			if($wordgender==$g)
    			{
    				$rtext.='<option value="'.$g.'" selected>'.$g.'</option>';
    			}
    			else{
    				$rtext.='<option value="'.$g.'" >'.$g.'</option>';
    			}
    		}   
            $rtext .= '</select>';
            return $rtext;
		}
	}

	function pGetTranslations($word_id, $search = '', $language_id, $clone = false, $clone_id = 0){

		global $pol;

		if(!$clone)
			return $pol['db']->query("SELECT * FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = $word_id AND translations.language_id = $language_id;");
		else{
			return $pol['db']->query("SELECT * FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE (translation_words.word_id = $word_id OR translation_words.word_id = $clone_id) AND translations.language_id = $language_id;");
		}

	}

	// Returns 0 or id, in case of existing translation, very useful for the add sequence
	function pTranslationExist($translation, $lang){

		global $pol;

		$get = $pol['db']->query("SELECT id FROM translations WHERE language_id = $lang AND translation = '$lang' LIMIT 1;");

		if($get->rowCount() == 1){
			$row = $get->fetchObject();
			return $row->id;
		}
		else{
			return 0;
		}

	}

	function pGetTranslationsByStart($start, $lang, $start_not = array()){

		global $pol;

		$start_not_string = '';

		foreach ($start_not as $start_not_instance) {
			$start_not_string .= " AND translations.translation NOT LIKE '".$start_not_instance."%'";
		}

		$translations = $pol['db']->query("SELECT *, translation_words.specification AS specification, translations.id AS id FROM translations INNER JOIN translation_words ON translation_words.translation_id = translations.id WHERE translations.translation LIKE '".$start."%' AND translations.language_id = $lang $start_not_string;"); 

		return $translations->fetchAll();

	}

	function pGetWordsByTranslation($translation){

		global $pol;

		$words = $pol['db']->query("SELECT *, words.id AS word_id, translation_words.specification AS specification FROM translation_words INNER JOIN words ON words.id = translation_words.word_id WHERE translation_words.translation_id = $translation
			ORDER BY case 
			when translation_words.specification = '' then 1
			else 2 end;");


		return $words->fetchAll();

	}



	function pGetSynonyms($word_id){

		global $pol;

		return $pol['db']->query("SELECT *, word_id_1 AS selected_word, word_id_2 AS selected_word FROM synonyms WHERE ((word_id_1 = $word_id) OR (word_id_2 = $word_id)) 
		Order By score DESC");


	}

	function pGetIdiomsOfWords($word_id){

		global $pol;

		return $pol['db']->query("SELECT words.id, idioms.id AS idiom_id, idioms.idiom, idiom_words.keyword FROM words JOIN idiom_words ON idiom_words.word_id = words.id JOIN idioms ON  idioms.id = idiom_words.idiom_id  WHERE words.id = $word_id;");


	}

	function pGetTranslationOfIdiomByLang($idiom_id){
		global $pol;
		return $pol['db']->query("SELECT * FROM idiom_translations WHERE idiom_id = $idiom_id ORDER BY language_id;");
	}


	// For use on word page
	function pGetDerivations($word_id){

		global $pol;

		$words =  $pol['db']->query("SELECT * FROM words WHERE derivation_of = $word_id");

		if($words->rowCount() === 0)
			return false;

		$return = array();

		while($word = $words->fetchObject())
		{

			//Now we are getting the name

			if($word->derivation_name != 0){
				$derivation_name = pDerivationName($word->derivation_name);
				$derivation_id = $word->derivation_name;
			}
			else{
				$derivation_name = pTypeName($word->derivation_type);
				$derivation_id = $word->derivation_type;
			}

			$return[$derivation_id]['name'] = $derivation_name ;
			$return[$derivation_id]['words'][] = $word;

		}

		return $return;

	}

	function pDerivationName($id){
		global $pol;
		$q = "SELECT * FROM derivations WHERE id = '".$id."' LIMIT 1;";
		$rs = $pol['db']->query($q);
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
	
	function pGetEtymology($word_id){

		global $pol;

		return $pol['db']->query("SELECT * FROM etymology WHERE word_id = $word_id;");


	}


	function pGetAntonyms($word_id){

		global $pol;

		return $pol['db']->query("SELECT * FROM antonyms WHERE ((word_id_1 = $word_id) OR (word_id_2 = $word_id)) 
		Order By score DESC");


	}


	function pGetTranslationsByLang($word_id, $clone = false, $clone_id = 0){

		global $pol;

		if(!$clone)
			return $pol['db']->query("SELECT * FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = $word_id  Order By language_id DESC;");
		else
			return $pol['db']->query("SELECT * FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = $clone_id OR translation_words.clone_id  Order By language_id DESC;");


	}


	function pGetDescription($translation_id, $tooltip = false){
		global $pol;
		$rr = $pol['db']->query("SELECT content FROM descriptions WHERE translation_id = '$translation_id' LIMIT 1;");
		if(($rr->rowCount() != 0) AND ($rs = $rr->fetchObject()))
		{
			if($tooltip)
				return 'title="'.htmlentities($rs->content).'"';
			else
				return $rs->content;
		}
		else
			return false;
	}
	
	function pWordShowNative($translation, $lang, $onlywords = false, $show_no_buttons = false, $class_prefix = 'd', $url_extra = 'searchresult')
	{
		global $pol;

		$pol['hidden_words'] = 0;

		if(!is_numeric($translation))
			$word = pGetWord($translation->word_id);
		else
			$word = pGetWord($translation);

		if($word->hidden == 1){
			$pol['hidden_words']++;
			return false;
		}
		$type = pGetType($word->type_id);
		$classification = pGetClassification($word->classification_id);

		if(!is_numeric($translation))
			if(in_array($translation->word_id, $pol['taken_care_of_words']))
				return false;

		$text = '';
		// if(logged())
		// 	$editbutton = "<a class='actionbutton floatright' href='javascript:void(0);' onClick='$(\"#fadeOut_".$word->id."\").fadeOut();$(\".loadDelete\").load(\"".pUrl('?deleteword='.$word->id.'&ajax')."\")'><i class='fa fa-times' style='font-size: 12px!important;'></i> Delete word</i></a> 
		// <a class='actionbutton floatright' onClick='$(\".dialogaddnew\").slideUp();$(\".dialogedit\").slideUp();$(\".dialog".$word->id."\").hide().load(\"".pUrl('?editword='.$word->id.'&ajaxOUT')."\", function(){
		// 	$(\".dialog".$word->id."\").slideDown();
		// })' href='javascript:void(0);' target='_blank'><i class='fa fa-pencil' style='font-size: 12px!important;'></i> Edit word</i></a>";

		// We need to start
		$text .= "<div id='fadeOut_".$word->id."'><div class='".$class_prefix."WordWrapper'><input type='hidden' value='".$lang."' id='ajax_lang_".html_entity_decode($word->id)."' /> ";

		// Then we need to show the native word

		$text .= "<strong class='".$class_prefix."Word' id='ajax_nat_".$word->id."'><a href='".pUrl('?'.$url_extra.'&word='.$word->id.'')."'><span class='native'>".html_entity_decode($word->native)."</span></a></strong> ";

		// Previewing the inflections
		$preview_inflections =  pGetPreviewInflections($type->id);
		if($preview_inflections->rowCount() != 0)
		{
			$text .= '<span class="'.$class_prefix.'Inflection">(';
			$all = array();
			while($pi = $preview_inflections->fetchObject()){
				$all[] = $pi->name.' <em>'.pSearchAndInflect($word, $type->id, $classification->id, $pi->mode_id, $pi->submode_id, $pi->number_id, '~', $pi->no_aux).'</em>';
			}
			$text .= implode(' <em><span style="font-weight:bold!imporant">;</span></em> ', $all).")</span> ";
		}



		// Show the type and classification
		$text .= '<em><a href="javascript:void(0);"  class="tooltip" title="'.htmlentities($type->name).'">'.html_entity_decode($type->short_name).'</a></em> ';
		if(!pTypeInflectClassifications($type->id))
			$text .= '<em><a href="javascript:void(0);" class="tooltip"  title="'.htmlentities($classification->name).'">'.html_entity_decode($classification->short_name).'</a></em> ';
		if($word->subclassification_id != 0 and !pTypeInflectClassifications($type->id)){
			$subclassification = pGetSubclassification($word->subclassification_id);
			$text .= '<em><a href="javascript:void(0);"  class="tooltip" title="'.htmlentities($subclassification->name).'">'.html_entity_decode($subclassification->short_name).'</a></em> ';
		}



		// Getting the translations
		$all_translations = pGetTranslations($word->id, '', $lang);

		if($all_translations->rowCount()  != 0)
			$text .= "<ol>"; 


		while($show_translation = $all_translations->fetchObject())
		{

			$text .= "<li><span>";

			if(!$onlywords)
				$text .= (($show_translation->specification != '') ? ('<em>('.$show_translation->specification.')</em> ') : (''));

			$text .= "<span class='translation tooltip'>".$show_translation->translation."</span>";

			if($description = pGetDescription($show_translation->id))
				$text .= "<br /><p class='desc'>".$description."</p>";

			$text .= "</span></li>";

			$pol['taken_care_of'][] = $show_translation->word_id;

		}


		$pol['taken_care_of_words'][] = $word->id;

		// The end
		if($all_translations->rowCount() != 0)
			$text .= "</ol>";

		// More info button
		if(!isset($_GET['wordsonly']) and !($show_no_buttons))
			$text .= "<a class='actionbutton' href='".pUrl('?searchresult&word='.$word->id)."'><i class='fa fa-12 fa-info-circle'></i> Read more</a>";

		// The logged in buttons
		if(logged() and !isset($_GET['wordsonly']) and !($show_no_buttons))
			$text .= "<a class='actionbutton' href='javascript:void(0);'>...</a>";

		$text .= "</div></div>";

		// $text .= $editbutton.$conjugate."<em class='dPrefix'>".$prefix."</em><strong class='dWord' >".html_entity_decode($word->native)."</strong> ".$pron." <br />  <span id='ajax_type_".$word->id."'><em class='dType'>".$type."</em> <em class='dGender'>".pWordGender(true,$word->gender)."</em></span> <br />
		// <span class='dTranslation' id='ajax_word_".$word->id."'>".html_entity_decode($word->slang)."</span><br />
		// <div id='ajax_desc_".$word->id."'>".html_entity_decode($desc)."</div><div class='dialog".$word->id." dialogedit'></div></div></div>";

		return $text;
	}


	function pAddWord($dov, $eng, $nld, $type, $gender, $desc, $audiofile = '', $flag='')
	{
		global $pol;
		if(pCanAdd($dov, $eng, $nld, $type, $gender, $desc, $audiofile, $flag))
			return $pol['db']->query("INSERT INTO dictionary VALUES('', ".$pol['db']->quote(htmlentities($type)).", ".$pol['db']->quote(htmlentities($dov)).", ".$pol['db']->quote(htmlentities($eng)).", ".$pol['db']->quote(htmlentities($nld)).", ".$pol['db']->quote(htmlentities($gender)).", ".$pol['db']->quote(htmlentities($audiofile)).", ".$pol['db']->quote(htmlentities($desc)).", ".$pol['db']->quote(htmlentities($flag)).");");
		else
			return true; // Word isn't added
	}


	

 ?>		