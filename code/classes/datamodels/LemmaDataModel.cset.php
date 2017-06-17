<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemmaDataModel.cset.php



class pLemmaDataModel extends pDataModel{

	private $_lemma;

	public function __construct($id = 0){
		parent::__construct('words');
		if($id != 0){
			$this->getSingleObject($id);
			$this->_lemma = $this->data()->fetchAll()[0];
		}
	}

	public function search($searchlang, $returnlang, $search, $wholeword)
	{

		$limit = ($this->_limit != null ? "LIMIT ".$this->_limit : '');

		$results = array();

		$search = str_replace('\\', "\\\\", $search);
		$search = str_replace('\'', "\\'", $search);
		$search = str_replace('"', '\\"', $search);

		if($wholeword)
			$ww = "REGEXP '[[:<:]]".trim(p::Escape($search))."[[:>:]]'";
		else
			$ww = "LIKE \"%".trim(p::Escape($search))."%\"";

		$extra = '';

		if(!pUser::noGuest() OR !pUser::checkPermission(-2))
			$extra = " (words.hidden = 0) AND ";


		if($searchlang == 0)
			$q = "SELECT * FROM (SELECT DISTINCT id AS word_id, 0 AS is_inflection, 0 as inflection FROM words WHERE $extra native ".$ww." OR native = '".p::Escape($search)."'  
				ORDER BY CASE
    						WHEN words.native = '".p::Escape(trim($search))."' THEN 1
    						WHEN words.native LIKE '".p::Escape(trim($search))."%' THEN 2
    						WHEN words.native LIKE '%".p::Escape(trim($search))."' THEN 3
    						ELSE 4
						END DESC) AS a 
				UNION ALL SELECT * FROM (SELECT DISTINCT lemma_id AS word_id, 1 AS is_inflection, inflected_form FROM lemmatization WHERE inflected_form ".$ww." OR inflected_form LIKE '".p::Escape($search)."' ORDER BY INSTR('".p::Escape(trim($search))."', inflected_form) DESC) as b 
				
				UNION ALL SELECT * FROM (SELECT DISTINCT word_id, 1 AS is_inflection, irregular_form AS inflection FROM morphology_irregular WHERE irregular_form ".$ww." ORDER BY INSTR('".p::Escape(trim($search))."', irregular_form) DESC) AS c ".$limit;	
		else
			$q = "SELECT * FROM (SELECT DISTINCT translation_words.word_id, 0 AS is_inflection, 0 AS is_alternative, 0 AS inflection, translations.id AS trans_id, translations.translation AS translation
					FROM words 
					INNER JOIN translation_words ON translation_words.word_id=words.id 
					INNER JOIN translations ON translations.id=translation_words.translation_id
					WHERE $extra translations.translation ".$ww." AND translations.language_id = '".$searchlang."' ORDER BY INSTR('".p::Escape(trim($search))."', translations.translation) DESC) AS a UNION ALL
					SELECT * FROM (SELECT DISTINCT word_id, 0 AS is_inflection, 1 AS is_alternative, alternative, translation_alternatives.translation_id AS trans_id, translations.translation AS translation FROM translation_words INNER JOIN translations ON translations.id = translation_words.translation_id INNER JOIN translation_alternatives WHERE translation_alternatives.alternative ".$ww." AND translation_words.translation_id = translation_alternatives.translation_id AND translations.language_id = '".$searchlang."' ORDER BY 
						CASE
    						WHEN translations.translation LIKE '".p::Escape(trim($search))."' THEN 1
    						WHEN translations.translation LIKE '".p::Escape(trim($search))."%' THEN 2
    						WHEN translations.translation LIKE '%".p::Escape(trim($search))."' THEN 3
    						ELSE 4
						END DESC, INSTR('".p::Escape(trim($search))."', translations.translation) DESC) AS b ".$limit; 

        $fetch = p::$db->cacheQuery($q);

        $noDoubles = array();

 		if($fetch->rowCount() != 0)
			while($fetched = $fetch->fetchObject()){
				$lemmaResult = new pLemma($fetched->word_id, 'words');
				if(isset($fetched->translation)){
					$lemmaResult->setHitTranslation($fetched->translation);
				}
				else{
					if($fetched->is_inflection == 1)
					$lemmaResult->setHitTranslation($fetched->inflection);
				}
				$lemmaResult->bindTranslations(($searchlang == 0) ? $returnlang : $searchlang);

				if(!array_key_exists($fetched->word_id, $results) AND $searchlang == 0)
					$results[$fetched->word_id] = $lemmaResult;
				if($searchlang != 0 AND !isset($results[$fetched->trans_id]))
					if(!isset($results[$fetched->translation][$fetched->word_id]))
						$results[$fetched->translation][$fetched->word_id] = $lemmaResult;
			}

		return $results;	
	}

	public function getTranslations($lang_id = false){

		if($lang_id == false)
			$lang_text = "";
		else
			$lang_text = " AND language_id = $lang_id";

		$results = array();

		if($this->_lemma['derivation_clonetranslations'] == '0')
			$query = "SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = ".$this->_lemma['id']." $lang_text  Order By language_id DESC;";
		else
			$query = "SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = ".$this->_lemma['derivation_of']." $lang_text  Order By language_id DESC;";


		$fetch = p::$db->cacheQuery($query);

		foreach($fetch->fetchAll() as $fetched){
			$translationResult = new pTranslation($fetched, 'translations');
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

