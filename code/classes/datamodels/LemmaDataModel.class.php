<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemmaDataModel.class.php



class pLemmaDataModel extends pDataModel{

	private $_lemma;

	public function __construct($id = 0){
		parent::__construct('words');
		if($id != 0 AND is_numeric($id)){
			$this->getSingleObject($id);
			$this->_lemma = $this->data()->fetchAll()[0];
		}
		elseif(!is_numeric($id)){
			$this->_lemma = $id;
		}
	}

	public function search($searchlang, $returnlang, $search, $wholeword)
	{

		// Setting a limit if we've got one
		$limit = ($this->_limit != null ? "LIMIT ".$this->_limit : '');

		// Preparing an empty array
		$results = array();

		// I am sorry, not so fancy, but... well... I guess we need this:
		$search = str_replace('\\', "\\\\", $search);
		$search = str_replace('\'', "\\'", $search);
		$search = str_replace('"', '\\"', $search);


		// The LIKE part
		$ww = "LIKE \"%".trim(p::Escape($search))."%\"";
		$extra = '';

		// If we don't have permission, we cannot find hidden lemmas
		if(!pUser::noGuest() OR !pUser::checkPermission(-2))
			$extra = " (words.hidden = 0) AND ";

		// The search query for lang-0: monolingual, searching both lemmata and translations
		if($searchlang == 0 AND $returnlang == 0)
			$q = "SELECT * FROM (SELECT DISTINCT id AS word_id, 0 AS is_inflection, 0 as inflection FROM words WHERE $extra native ".$ww." OR native = '".p::Escape($search)."'  
				ORDER BY CASE
    						WHEN words.native = '".p::Escape(trim($search))."' THEN 1
    						WHEN words.native LIKE '".p::Escape(trim($search))."%' THEN 2
    						WHEN words.native LIKE '%".p::Escape(trim($search))."' THEN 3
    						ELSE 4
						END DESC) AS a 
				UNION ALL
            SELECT * FROM (SELECT DISTINCT translation_words.word_id as word_id, 0 as is_inflection, 0 as inflection FROM translation_words JOIN translations WHERE ".$extra." translations.translation ".$ww." AND translations.language_id = 0 AND translation_words.translation_id = translations.id ORDER BY CASE
    						WHEN translations.translation = '".p::Escape(trim($search))."' THEN 1
    						WHEN translations.translation LIKE '".p::Escape(trim($search))."%' THEN 2
    						WHEN translations.translation LIKE '%".p::Escape(trim($search))."' THEN 3
    						ELSE 4
						END DESC) as b;
			";

		// Searching lemmata and inflected forms
		elseif($searchlang == 0)
			$q = "SELECT * FROM (SELECT DISTINCT id AS word_id, 0 AS is_inflection, 0 as inflection FROM words WHERE $extra native ".$ww." OR native = '".p::Escape($search)."'  
				ORDER BY CASE
    						WHEN words.native = '".p::Escape(trim($search))."' THEN 1
    						WHEN words.native LIKE '".p::Escape(trim($search))."%' THEN 2
    						WHEN words.native LIKE '%".p::Escape(trim($search))."' THEN 3
    						ELSE 4
						END DESC) AS a 
				UNION ALL SELECT * FROM (SELECT DISTINCT lemma_id AS word_id, 1 AS is_inflection, inflected_form FROM lemmatization WHERE inflected_form ".$ww." OR inflected_form LIKE '".p::Escape($search)."' ORDER BY INSTR('".p::Escape(trim($search))."', inflected_form) DESC) as b 
				
				UNION ALL SELECT * FROM (SELECT DISTINCT lemma_id AS word_id, 1 AS is_inflection, irregular_form AS inflection FROM morphology WHERE irregular_form ".$ww." ORDER BY INSTR('".p::Escape(trim($search))."', irregular_form) DESC) AS c ".$limit;	
		// The search query for reverse: only searching translations and their alternatives
		else
			$q = "SELECT * FROM (SELECT DISTINCT translation_words.word_id, 0 AS is_inflection, 0 AS is_alternative, 0 AS inflection, translations.id AS trans_id, translations.translation AS translation
					FROM words 
					INNER JOIN translation_words ON translation_words.word_id=words.id 
					INNER JOIN translations ON translations.id=translation_words.translation_id
					WHERE $extra translations.translation ".$ww." AND translations.language_id = '".$searchlang."' ORDER BY INSTR('".p::Escape(trim($search))."', translations.translation) DESC) AS a UNION ALL
					SELECT * FROM (SELECT DISTINCT word_id, 0 AS is_inflection, 1 AS is_alternative, alternative, translation_alternatives.translation_id AS trans_id, translations.translation AS translation FROM translation_words INNER JOIN translations ON translations.id = translation_words.translation_id INNER JOIN translation_alternatives WHERE  $extra translation_alternatives.alternative ".$ww." AND translation_words.translation_id = translation_alternatives.translation_id AND translations.language_id = '".$searchlang."' ORDER BY 
						CASE
    						WHEN translations.translation LIKE '".p::Escape(trim($search))."' THEN 1
    						WHEN translations.translation LIKE '".p::Escape(trim($search))."%' THEN 2
    						WHEN translations.translation LIKE '%".p::Escape(trim($search))."' THEN 3
    						ELSE 4
						END DESC, INSTR('".p::Escape(trim($search))."', translations.translation) DESC) AS b ".$limit; 

		// Running the query
        $fetch = p::$db->cacheQuery($q);
        // Creating an empty array for doubles
        $noDoubles = array();

        // Only proceed if we can
 		if($fetch->rowCount() != 0)
			while($fetched = $fetch->fetchObject()){

				// We won't accept any doubles
				if(isset($noDoubles[$fetched->word_id]))
					continue;

				// Creating the lemma
				$lemmaResult = new pLemma($fetched->word_id, 'words');
				// Double check for hidden lemma's
				if((!pUser::noGuest() OR !pUser::checkPermission(-2)) AND $lemmaResult->read('hidden') == '1')
					break;

				if(isset($fetched->translation))
					$lemmaResult->setHitTranslation($fetched->translation);
				elseif($fetched->is_inflection == 1)
					$lemmaResult->setHitTranslation($fetched->inflection);

				// Bind the translations
				$lemmaResult->bindTranslations(($searchlang == 0) ? $returnlang : $searchlang);

				if(!array_key_exists($fetched->word_id, $results) AND $searchlang == 0)
					$results[$fetched->word_id] = $lemmaResult;
				if($searchlang != 0 AND !isset($results[$fetched->trans_id]))
					if(!isset($results[$fetched->translation][$fetched->word_id]))
						$results[$fetched->translation][$fetched->word_id] = $lemmaResult;
			
				$noDoubles[$fetched->word_id] = true;
			}

		return $results;	
	}

	public function getTranslations($lang_id = false){

		if($lang_id === false)
			$lang_text = "";
		else
			$lang_text = " AND language_id = ".$lang_id;

		$results = array();
		
		$query = "SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = ".$this->_lemma['id']." $lang_text  Order By language_id DESC;";

		$fetch = p::$db->cacheQuery($query);


		foreach($fetch->fetchAll() as $fetchedTranslation){
			$translationResult = pRegister::cacheCallBack('pTranslations', $fetchedTranslation['real_id'], function($fetched){
					global $fetchedTranslation;
					return new pTranslation($fetched, 'translations');
			}, array($fetchedTranslation));
			$translationResult->bindLemma($this->_lemma['id']);
			$results[] = $translationResult;
		}

		return $results;
	}

	public function getIdioms(){

		$results = array();


		$fetch = p::$db->cacheQuery("SELECT words.id AS word_id, idioms.id AS id, idioms.idiom, idiom_words.keyword, idioms.created_on, idioms.user_id FROM words JOIN idiom_words ON idiom_words.word_id = words.id JOIN idioms ON idioms.id = idiom_words.idiom_id  WHERE words.id = ".$this->_lemma['id'].";");

		foreach($fetch->fetchAll() as $fetched){
			$idiomResult = new pIdiom($fetched, 'idioms');
			$idiomResult->bindLemma($this->_lemma['id']);
			$idiomResult->bindTranslations();
			$results[] = $idiomResult;
		}

		return $results;
	}

}

