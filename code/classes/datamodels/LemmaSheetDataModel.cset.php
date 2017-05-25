<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: RuleDataModel.cset.php



class pLemmaSheetDataModel extends pDataModel{

	public $_links, $_RuleID, $_translations, $_translationsinput, $_transIDLookUp;

	public function __construct($table, $id = 0){
		parent::__construct($table);
		$dfs = new pSet;
		$dfs->add(new pDataField('native'));
		$dfs->add(new pDataField('lexical_form'));
		$dfs->add(new pDataField('ipa'));
		$dfs->add(new pDataField('hidden'));
		$dfs->add(new pDataField('type_id'));
		$dfs->add(new pDataField('classification_id'));
		$dfs->add(new pDataField('subclassification_id'));
		$dfs->add(new pDataField('created'));
		$dfs->add(new pDataField('updated'));
		$dfs->add(new pDataField('created_by'));
		$this->setFields($dfs);
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

		$query = "SELECT *, translations.id AS real_id FROM translations INNER JOIN translation_words ON translations.id = translation_words.translation_id WHERE translation_words.word_id = ".$this->_lemma['id']." Order By language_id DESC;";


		$fetch = p::$db->cacheQuery($query);

		foreach($fetch->fetchAll() as $fetched){ 
			$translationResult = new pTranslation($fetched, 'translations');
			$translationResult->bindLemma($this->_lemma['id']);
			$this->_translations[$fetched['language_id']][$fetched['real_id']] = $translationResult;
			$this->_transIDLookUp[$fetched['translation'].'_'.$fetched['language_id'].'_'.$fetched['specification']] = $fetched['real_id'];
		}

		foreach($this->_translations as $key => $language){
			$temp = array();
			foreach ($language as $trans)
				$temp[] = $trans->getInputForm();
			$this->_translationsinput[$key] = implode('//', $temp);
		}

		return true; 
	}
	

	public function parseTranslations($translations){
		$output = array();
		$exploded = explode('//', $translations);
		$explodedProper = array();

		foreach($exploded as $trans)
			$explodedProper[] = explode('>', $trans);

		foreach($explodedProper as $trans)
			if($trans[0] != ''){
				if(isset($trans[1]))
					$output[] = array('trans' => $trans[0], 'spec' => $trans[1]);
				else
					$output[] = array('trans' => $trans[0], 'spec' => '');
			}

		return $output;
	}


	public function Basics($dictForm, $lexForm, $ipa, $lexcat, $gramcat, $tags, $update = false){
		// First change the basic info
		if(empty($dictForm) OR empty($lexcat) OR empty($gramcat))
			return false;
		if($update){
			$this->prepareForUpdate(array($dictForm, $lexForm, $ipa, 0, $lexcat, $gramcat, $tags, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), pUser::read('id')));
			return !(!$this->update());
		}else{
			$this->prepareForInsert(array($dictForm, $lexForm, $ipa, 0, $lexcat, $gramcat, $tags, null, date('Y-m-d H:i:s'), pUser::read('id')));
			$this->insert();
			$this->_lemma = array('id' => p::$db->lastInsertId());
			return true;
		}
	}


	public function updateTranslations($input){
		// For each input field
		foreach($input as $language => $translations){
			// Parse the translation string into an array
			$transParsed = $this->parseTranslations($translations);
			// Inserting or ignoring the stuff that needs to be inserted
			foreach($transParsed as $trans){
				$id = pTranslation::new($trans, $language, $this->_transIDLookUp);
				// If the ID is false the link does already exist
				if(!is_array($id)){
					$dM = new pDataModel('translation_words');
					$dM->prepareForInsert(array($this->_lemma['id'], $id, $trans['spec']));
					$dM->insert();
					unset($this->_translations[$language][$id]); 
				}
				else{
					unset($this->_translations[$language][$id[1]]); 
				}
			}
		}
		// Deleting what is left over needs to be deleted
		if($this->_translations != null AND !empty($this->_translations)){
			foreach($this->_translations as $languageHolder){
				foreach($languageHolder as $translation){
					$this->customQuery("DELETE FROM translation_words WHERE word_id = ".$this->_lemma['id']." AND translation_id = ".$translation->read('real_id')." AND specification = ".p::Quote($translation->_specification).";");
					// This will delete the translation if there are no links left.
					pTranslation::finalDelete($translation->read('real_id'));
				}
			}
		}
		
		// If we are alive, everything went well
		return true;

	}

}

