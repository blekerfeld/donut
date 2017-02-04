<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: dictionary.functions.php


	// Function to search in the dictionary
	function pSearchDict($searchlang, $returnlang, $search, $wholeword)
	{

		$r = array();


		if($wholeword)
			$ww = "REGEXP '[[:<:]]".trim(pEscape($search))."[[:>:]]'";
		else
			$ww = "LIKE \"%".trim(pEscape($search))."%\"";
	

		if($searchlang == 0)
						$q = "SELECT * FROM (SELECT DISTINCT id AS word_id, 0 AS is_inflection, 0 as inflection FROM words WHERE native ".$ww." OR native LIKE '".pEscape($search)."'  ORDER BY INSTR('".pEscape(trim($search))."', words.native) DESC) AS a UNION ALL SELECT * FROM (SELECT DISTINCT word_id, 1 AS is_inflection, inflection FROM inflection_cache WHERE inflection ".$ww." OR inflection LIKE '".pEscape($search)."' ORDER BY INSTR('".pEscape(trim($search))."', inflection) DESC) as b";	
		else
			$q = "SELECT * FROM (SELECT DISTINCT translation_words.word_id, 0 AS is_inflection, 0 AS is_alternative, 0 AS inflection, 0 AS trans_id
					FROM words 
					INNER JOIN translation_words ON translation_words.word_id=words.id 
					INNER JOIN translations ON translations.id=translation_words.translation_id
					WHERE translations.translation ".$ww." AND translations.language_id = '".$searchlang."' ORDER BY INSTR('".pEscape(trim($search))."', translations.translation) DESC) AS a UNION ALL
					SELECT * FROM (SELECT DISTINCT word_id, 0 AS is_inflection, 1 AS is_alternative, alternative, translation_alternatives.translation_id AS trans_id FROM translation_words INNER JOIN translations ON translations.id = translation_words.translation_id INNER JOIN translation_alternatives WHERE translation_alternatives.alternative ".$ww." AND translation_words.translation_id = translation_alternatives.translation_id AND translations.language_id = '".$searchlang."' ORDER BY INSTR('".pEscape(trim($search))."', translations.translation) DESC) AS b;";

        $rs = pQuery($q);

 		if($rs->rowCount() != 0){

			while($fc = $rs->fetchObject())
				$r[] = $fc;

			return $r;
		}

		return false;

	}

	function pGetWordByHash($hash){

		if(is_numeric($hash))
			return pGetWord($hash);	
		elseif(ctype_alpha($hash))
		{
			$decode = pHashId($hash, true);
			if(array_key_exists(0, $decode)){
				return pGetWord($decode[0]);	
			}
			else
				$hash = $word->id;
		}
	}


	// Word link
	function pWordLinks($text){

		return preg_replace_callback('/\[\[([^\]]+)\]\]/', function($matches) {

		$link_par = explode("|", $matches[1]);


				if(is_numeric($link_par[0])){
					$word = pGetWord($link_par[0]);
					$link_par[1] = $word->native;		
				}
				elseif(ctype_alpha($link_par[0]))
				{
					$decode = pHashId($link_par[0], true);
					if(array_key_exists(0, $decode) and $id = $decode[0]){
						if(!$word = pGetWord($decode[0]))
							return "";
						$link_par[0] = $word->id;	
						if(!array_key_exists(1, $link_par))
							$link_par[1] = $word->native;		
					}
					else{
						if(!($word = pGetWordByNative($link_par[0])))
							return '<a class="wordLink tooltip broken" href="'.pUrl('?nonexistant&search='.urlencode($link_par[0])).'">'.(array_key_exists(1, $link_par) ? $link_par[1] : $link_par[0]).'</a>';
						$link_par[0] = $word->id;
						if(!array_key_exists(1, $link_par))
							$link_par[1] = $word->native;	
					}
				}

				$link = "?lemma=".pHashId($link_par[0]);


		    return '<a class="wordLink tooltip" href="' . pUrl($link) . '"><span class="native">' . $link_par[1] . '</span></a>';
		}, $text);

	}


	// NOTE RETURN ARRAY, NOT OBJECT!
	function pGetWordsByStart($start, $start_not = array()){

		global $donut;

		$start_not_string = '';

		foreach ($start_not as $start_not_instance) {
			$start_not_string .= " AND native NOT LIKE '".$start_not_instance."%'";
		}

		$words = pQuery("SELECT * FROM words WHERE hidden = 0 AND native LIKE '".$start."%' ".$start_not_string .";"); 

		return $words->fetchAll();

	}

	function pGetWord($id)
	{
		if(!is_numeric($id))
			return pGetWordByHash($id);
		$rs = pQuery("SELECT * FROM words WHERE id = ".$id." LIMIT 1;");
		if($rs->rowCount() != 0)
			return $rs->fetchObject();
		else
			return false;
	}

	function pGetWordByNative($native)
	{
		$rs = pQuery("SELECT * FROM words WHERE native = '".pEscape($native)."' LIMIT 1;");
		if($rs->rowCount() != 0)
			return $rs->fetchObject();
		else
			return false;
	}

	
	
	function pWordDelete($id)
	{
		pQuery("DELETE FROM words WHERE id = $id;");
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

		global $donut;

		if(!$clone)
			return pQuery("SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = $word_id AND translations.language_id = $language_id;");
		else{
			return pQuery("SELECT *, , translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE (translation_words.word_id = $word_id OR translation_words.word_id = $clone_id) AND translations.language_id = $language_id;");
		}

	}

	// Returns 0 or id, in case of existing translation, very useful for the add sequence
	function pTranslationExist($translation, $lang){

		global $donut;

		$get = pQuery("SELECT id FROM translations WHERE language_id = $lang AND translation = '$lang' LIMIT 1;");

		if($get->rowCount() == 1){
			$row = $get->fetchObject();
			return $row->id;
		}
		else{
			return 0;
		}

	}

	function pGetTranslationsByStart($start, $lang, $start_not = array()){

		global $donut;

		$start_not_string = '';

		foreach ($start_not as $start_not_instance) {
			$start_not_string .= " AND translations.translation NOT LIKE '".$start_not_instance."%'";
		}

		$translations = pQuery("SELECT *, translation_words.specification AS specification, translations.id AS id FROM translations INNER JOIN translation_words ON translation_words.translation_id = translations.id WHERE translations.translation LIKE '".$start."%' AND translations.language_id = $lang $start_not_string;"); 

		return $translations->fetchAll();

	}

	function pGetWordsByTranslation($translation){

		global $donut;

		$words = pQuery("SELECT *, words.id AS word_id, translation_words.specification AS specification FROM translation_words INNER JOIN words ON words.id = translation_words.word_id WHERE translation_words.translation_id = $translation
			ORDER BY case 
			when translation_words.specification = '' then 1
			else 2 end;");


		return $words->fetchAll();

	}



	function pGetSynonyms($word_id){

		global $donut;

		return pQuery("SELECT *, word_id_1 AS selected_word, word_id_2 AS selected_word FROM synonyms WHERE ((word_id_1 = $word_id) OR (word_id_2 = $word_id)) 
		Order By score DESC");


	}

	function pGetIdiomsOfWords($word_id){

		global $donut;

		return pQuery("SELECT words.id, idioms.id AS idiom_id, idioms.idiom, idiom_words.keyword FROM words JOIN idiom_words ON idiom_words.word_id = words.id JOIN idioms ON  idioms.id = idiom_words.idiom_id  WHERE words.id = $word_id;");


	}

	function pGetTranslationOfIdiomByLang($idiom_id){
		return pQuery("SELECT * FROM idiom_translations WHERE idiom_id = $idiom_id ORDER BY language_id;");
	}


	// For use on word page
	function pGetDerivations($word_id){

		global $donut;

		$words =  pQuery("SELECT * FROM words WHERE derivation_of = $word_id");

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
		global $donut;
		$q = "SELECT * FROM derivations WHERE id = '".$id."' LIMIT 1;";
		$rs = pQuery($q);
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

		global $donut;

		return pQuery("SELECT * FROM etymology WHERE word_id = $word_id;");


	}


	function pGetAntonyms($word_id){

		global $donut;

		return pQuery("SELECT * FROM antonyms WHERE ((word_id_1 = $word_id) OR (word_id_2 = $word_id)) 
		Order By score DESC");


	}


	function pGetTranslationsByLang($word_id, $lang_id = 0, $clone = false, $clone_id = 0){

		global $donut;

		if($lang_id == 0)
			$lang_text = "";
		else
			$lang_text = " AND language_id = $lang_id";

		if(!$clone)
			return pQuery("SELECT * FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = $word_id $lang_text  Order By language_id DESC;");
		else
			return pQuery("SELECT * FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE (translation_words.word_id = $clone_id OR translation_words.clone_id) $lang_text Order By language_id DESC;");


	}


	function pGetDescription($translation_id, $tooltip = false){
		global $donut;
		$rr = pQuery("SELECT content FROM descriptions WHERE translation_id = '$translation_id' LIMIT 1;");
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
	
	function pWordShowNative($translation, $lang, $onlywords = false, $show_no_buttons = false, $class_prefix = 'd', $url_extra = 'searchresult', $before_word = "")
	{
		global $donut;

		$donut['hidden_words'] = 0;

		if(!is_numeric($translation))
			$word = pGetWord($translation->word_id);
		else
			$word = pGetWord($translation);

		if($word->hidden == 1){
			$donut['hidden_words']++;
			return false;
		}
		$type = pGetType($word->type_id);
		$classification = pGetClassification($word->classification_id);

		if(!is_numeric($translation))
			if(in_array($translation->word_id, $donut['taken_care_of_words']))
				return false;

		$text = '';

		// More info button

			$text .= "<span class='floatright'><br />";

			if(!isset($_GET['wordsonly']) and !($show_no_buttons))
				$text .= "<a class='actionbutton readmore' href='".pUrl('?searchresult&lemma='.pHashId($word->id))."'><i class='fa fa-12 fa-info-circle'></i> ".DICT_READMORE."</a>";

			// The logged in buttons
			if(pLogged() and !isset($_GET['wordsonly']) and !($show_no_buttons))
				$text .= "<a class='actionbutton readmore' href='javascript:void(0);'><i class='fa fa-12 fa-angle-double-down'></i></a>";

			$text .= "</span>";


		// We need to start
		$text .= "<div id='fadeOut_".$word->id."'><div class='".$class_prefix."WordWrapper'><input type='hidden' value='".$lang."' id='ajax_lang_".html_entity_decode($word->id)."' /> ";

		if(isset($translation->is_inflection) and $translation->is_inflection == 1 and $before_word == "")
			$before_word =  "<em class='medium'><span class='native'><a class='tooltip wordLink'>".$translation->inflection."</a></span></em><br /><div class='tab'>↳";

		// Then we need to show the native word

		$text .= "".$before_word."<strong class='".$class_prefix."Word' id='ajax_nat_".$word->id."'><a href='".pUrl('?'.$url_extra.'&lemma='.pHashId($word->id).'')."'><span class='native'>".html_entity_decode($word->native)."</span></a></strong> ";

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
			$text .= "<ol ".(($all_translations->rowCount() > 3) ? "style = 'columns:200px 2;-moz-columns:200px 2;width:100%;'" : "").">"; 


		while($show_translation = $all_translations->fetchObject())
		{

			$text .= "<li><span>";

			if(!$onlywords)
				$text .= (($show_translation->specification != '') ? ('<em>('.$show_translation->specification.')</em> ') : (''));

		if(isset($translation->is_alternative) and $translation->is_alternative == 1 and $translation->trans_id == $show_translation->real_id and isset($translation->inflection))
					$text .=  "<em><span class='native'><span class='translation tooltip'>".$translation->inflection."</a></span></em><br /><span class='tab'>↳";

			$text .= "<span class='translation tooltip'>".$show_translation->translation."</span>";

		


			if($description = pGetDescription($show_translation->id))
				$text .= "<br /><p class='desc'>".$description."</p>";


			if(isset($translation->is_alternative) and $translation->is_alternative == 1 and $translation->trans_id == $show_translation->real_id and isset($translation->inflection))
				$text .= "</span>";
		


			$text .= "</span></li>";

			$donut['taken_care_of'][] = $show_translation->word_id;

		}


		$donut['taken_care_of_words'][] = $word->id;

		// The end
		if($all_translations->rowCount() != 0)
			$text .= "</ol>";

		if(isset($translation->is_inflection) and $translation->is_inflection == 1)
			$text .= "</div>";


		$text .= "</div></div>";

		// $text .= $editbutton.$conjugate."<em class='dPrefix'>".$prefix."</em><strong class='dWord' >".html_entity_decode($word->native)."</strong> ".$pron." <br />  <span id='ajax_type_".$word->id."'><em class='dType'>".$type."</em> <em class='dGender'>".pWordGender(true,$word->gender)."</em></span> <br />
		// <span class='dTranslation' id='ajax_word_".$word->id."'>".html_entity_decode($word->slang)."</span><br />
		// <div id='ajax_desc_".$word->id."'>".html_entity_decode($desc)."</div><div class='dialog".$word->id." dialogedit'></div></div></div>";

		return $text;
	}



	function pAddWord($native, $ipa, $type_id, $classification_id, $subclassification_id, $translations){

			global $donut;

			pQuery("INSERT INTO words(native, ipa, type_id, classification_id, subclassification_id) VALUES(".$donut['db']->quote(pEscape($native)).", ".$donut['db']->quote(pEscape($ipa)).", ".$donut['db']->quote(pEscape($type_id)).", ".$donut['db']->quote(pEscape($classification_id)).", ".$donut['db']->quote(pEscape($subclassification_id)).");");

		
			// We need to get a hold of the last inserted id
			$new_word_id = $donut['db']->lastInsertId(); 

			// Adding the translations
			pAddTranslations($translations, $new_word_id);

			return pAllInflections(pGetWord($new_word_id), pGetType($type_id));

		}


	function pAddTranslations($translation_string, $word_id){

		// Needed
		global $donut;

		// Exploding the translation input
		$translations_all = explode(",", $_REQUEST['translations']);

		// Boolean for checking the status
		$status = false;
				
		foreach ($translations_all as $string_trans) {
			// Splitting specifications from actual translations
			$translations = explode('|', $string_trans);
			

			//	If there is a specification, let's specify it
				$specification = '';
				if(count($translations) == 2 AND  $translations[1] != '')
				$specification = $translations[1];
			
			// We need to check if the translation already exists
			$previous_id = pTranslationExist($translations[0], pEditorLanguage($_SESSION['pUser']));

			// If it does not exists, we need to add it of couuuurse! ;)
			if($previous_id == 0)
				$status =  pQuery("INSERT INTO translations(language_id, translation) VALUES(".$donut['db']->quote(pEditorLanguage($_SESSION['pUser'])).", ".$donut['db']->quote($translations[0]).");SET @TRANSLATIONID=LAST_INSERT_ID();INSERT INTO translation_words(word_id, translation_id, specification) VALUES (".$word_id.", @TRANSLATIONID, ".$donut['db']->quote($specification).");");
			
			// The translation existed already, hooray, half the job it is then.
			$status = pQuery("INSERT INTO translation_words(word_id, translation_id, specification) VALUES (".$word_id.", ".$previous_id.", ".$donut['db']->quote($specification).");");
			
			
		}

		// Returning something!
		return (($status !== false) ? true : false);

	}




	

 ?>		