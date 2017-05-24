<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: RuleDataModel.cset.php



class pLemmaSheetDataModel extends pDataModel{

	public $_links, $_RuleID, $_translations;

	public function __construct($table, $id = 0){
		parent::__construct($table);
		if($id != 0){
			$this->_LemmaID = $id;
			$this->getSingleObject($id);
			$this->_lemma = $this->data()->fetchAll()[0];
			$this->_translations = array();
			$this->getTranslations();
		}
	}


	public function getTranslations(){

		$results = array();

		if($this->_lemma['derivation_clonetranslations'] == '0')
			$query = "SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = ".$this->_lemma['id']." Order By language_id DESC;";
		else
			$query = "SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = ".$this->_lemma['derivation_of']." Order By language_id DESC;";


		$fetch = p::$db->cacheQuery($query);

		foreach($fetch->fetchAll() as $fetched){ 
			$translationResult = new pTranslation($fetched, 'translations');
			$translationResult->bindLemma($this->_lemma['id']);
			$this->_translations[$fetched['language_id']][$fetched['translation']] = $translationResult;
		}

		return true; 
	}
	

	protected function parseTranslations($translations){
		$output = array();
		$exploded = explode('//', $translations);
		$explodedProper = array();

		foreach($exploded as $trans)
			$explodedProper[] = explode('>', $trans);

		foreach($explodedProper as $trans)
			if(isset($trans[1]))
				$output[] = array($trans[0], $trans[1]);
			else
				$output[] = array($trans[0]);

		return $output;
	}

	protected function InsertTranslation($translation, $language){
		$dM = new pDataModel('translations');
		$dM->setCondition(" WHERE language_id = $language AND translation = ".p::Quote($translation)."");
		$dM->getObjects();
		if($dM->data()->rowCount() == 0){
			$dM->setCondition('');
			$dM->prepareForInsert(array($language, $translation, '', date('Y-m-d H:i:s'), pUser::read('id')));
			$dM->insert();
			return $this->InsertTranslation($translation, $language);
		}
		else
			return $dM->data()->fetchAll()[0]['id'];
	}

	public function updateTranslations($input){
		foreach($input as $language => $translations){
			foreach($translations as $trans){
				if(!in_array($this->_translations))
					return true;
			}
		}
	}

}

