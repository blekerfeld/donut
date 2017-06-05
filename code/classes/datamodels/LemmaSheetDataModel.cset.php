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
			$subEntries = array(new pEntryDataModel('synonyms', array('word_id_1', 'word_id_2'), 'fa-clone', false, null, 'synonyms'),
					new pEntryDataModel('antonyms', array('word_id_1', 'word_id_2'), '', false, null, 'antonyms'),
					new pEntryDataModel('homophones', array('word_id_1', 'word_id_2'), '', false, null, 'homophones'),
					new pEntryDataModel('homophones', array('word_id_1', 'word_id_2'), '', false, null, 'homophones'),
					new pEntryDataModel('idiom_words', 'word_id', array('idiom_id', 'keyword'), false, null, 'idiom_words'),
					);
			
			$this->_links['usage_notes'] = $this->usageNotes();

			$this->_links['synonyms'] = array();
			$this->_links['antonyms'] = array();
			$this->_links['homophones'] = array();
			$this->_links['idiom_words'] = array();

			foreach($subEntries as $entry){
				$entry->setID($this->_lemma['id']);
				$entry->compile();
				foreach($entry->data() as $link)
					if(isset($link['word_id_1'])){
						if($link['word_id_1'] == $this->_lemma['id']){
							$this->_links[$entry->_table][$link['word_id_2']]['value'] = $link['word_id_2'];
							$this->_links[$entry->_table][$link['word_id_2']]['score'] = $link['score'];
						}
						else{
							$this->_links[$entry->_table][$link['word_id_1']]['value'] = $link['word_id_1'];
							$this->_links[$entry->_table][$link['word_id_1']]['score'] = $link['score'];
						}
					}
					elseif(isset($link['word_id'])){
						$this->_links[$entry->_table][$link[$entry->icon[0]]]['value'] = $link[$entry->icon[0]];
						$this->_links[$entry->_table][$link[$entry->icon[0]]]['score'] = $link[$entry->icon[1]];
					}
			}
		}
	}


	public function preloadSpecification($table, $extra = ''){
		if(!isset($this->_links[$table]))
			return '';
		$output = '';
		foreach($this->_links[$table] as $link)
			$output .= "<option role='load' data-value='".$link['value']."' data-attr='".$link['score'].$extra."' />";
		return $output;
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


	public function Basics($dictForm, $lexForm, $ipa, $lexcat, $gramcat, $tags, $hidden, $update = false){
		// First change the basic info
		if(empty($dictForm) OR empty($lexcat) OR empty($gramcat))
			return false;
		if($update){
			$this->prepareForUpdate(array($dictForm, $lexForm, $ipa, $hidden, $lexcat, $gramcat, $tags, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), pUser::read('id')));
			return !(!$this->update());
		}else{
			$this->prepareForInsert(array($dictForm, $lexForm, $ipa, $hidden, $lexcat, $gramcat, $tags, null, date('Y-m-d H:i:s'), pUser::read('id')));
			$this->insert();
			// We have to construct this class again, with the id we got
			$this->__construct('words', p::$db->lastInsertId());
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

	// Updating the semantic links
	public function updateLinks($table, $input, $single = false, $field = 'word_id_2', $attribute = 'score'){
		if($input == '' OR empty($input))
			return false;
		foreach($input as $id){
			// If the link is already there, nothing needs to be done
			if(!isset($this->_links[$table][$id['value']])){
				$dM = new pDataModel($table);
				// Making a link, by default the score is 50
				if($single)
					$precent = $id['keyword'];
				else
					$precent = substr($id['keyword'], 0, -1);

				$dfs = new pSet;
				$dfs->add(new pDataField(($single ? 'word_id' : 'word_id_1')));
				$dfs->add(new pDataField($field));
				$dfs->add(new pDataField($attribute));
				$dM->setFields($dfs);
				$dM->prepareForInsert(array($this->_lemma['id'], $id['value'], $precent));
				$dM->insert();
			}
			unset($this->_links[$table][$id['value']]);
		}
		// Links that are left need to be destroyed
		foreach($this->_links[$table] as $id => $values)
			if(!$single)
				$this->customQuery("DELETE FROM $table WHERE (word_id_1 = ".$this->_lemma['id'] . " AND word_id_2 = $id) OR (word_id_2 = ".$this->_lemma['id'] . " AND word_id_1 = $id);");
			else
				$this->customQuery("DELETE FROM $table WHERE  (word_id = ".$this->_lemma['id'] . " AND ".$field." = $id);");
		// If we are alive everthing went very well
		return true;
	}

	// Deleting all links if needed
	public function deleteLinks($table, $single = false){
		if(!$single)
			$this->customQuery("DELETE FROM $table WHERE (word_id_1 = ".$this->_lemma['id'].") OR (word_id_2 = ".$this->_lemma['id'].");");
		else
			$this->customQuery("DELETE FROM $table WHERE word_id  = ".$this->_lemma['id'].";");
		return true;
	}

	// Usage notes
	public function usageNotes(){
		$dM = new pDataModel('usage_notes');
		$dM->setCondition(" WHERE word_id = '".$this->_lemma['id']."' ");
		$dM->getObjects();
		if(isset($dM->data()->fetchAll()[1]))
			return $dM->data()->fetchAll()[1]['note'];
		else
			return false;
	}

	public function updateUsageNotes($text){
		$dM = new pDataModel('usage_notes');
		if($this->_links['usage_notes'] == false){
			$dM->prepareForInsert(array($this->_lemma['id'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), pUser::read('id'), $text));
			$dM->insert();
		}
		else{
			$this->customQuery("UPDATE usage_notes SET note = ".p::Quote($text)." AND last_update = '".date('Y-m-d H:i:s')."' WHERE word_id = ".$this->_lemma['id']);
		}
		// If we are still alive, it went very well
		return true;
	}

}

