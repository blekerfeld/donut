<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemma.cset.php


// This represents a word

class pLemma extends pEntry{

	private $_translations, $__lemmaDataObject, $_type, $_class, $_subclass;

	// The constructur will call the parent (pEntry) with its arguments and then do a list of aditional tasks...
	public function __construct(){
		// First we are calling the parent's constructor
		call_user_func_array('parent::__construct', func_get_args());

		$this->_lemmaDataObject = new pLemmaDataObject($this->_id);

		// Getting type (part of speech), classification (grammatical category) and grammatical tag
			if($this->_entry['type_id'] != 0)
				$this->_type = (new pDataObject('types', null, false, $this->_entry['type_id']))->data()->fetchAll()[0];

			if($this->_entry['classification_id'] != 0)
				$this->_class = (new pDataObject('classifications', null, false, $this->_entry['classification_id']))->data()->fetchAll()[0];

			if($this->_entry['subclassification_id'] != 0)
				$this->_subclass = (new pDataObject('subclassifications', null, false, $this->_entry['subclassification_id']))->data()->fetchAll()[0];
	}

	public function render(){
		return "<a href='".pUrl('?entry/'.pHashId($this->_entry['id']))."'>".$this->_entry['native']."</a> ";
	}

	public function native(){
		return "<span class='native'>".$this->_entry['native']."</span>";
	}


	// Used by the discuss action
	public function renderDiscussion(){
		pOut("DISCUSSION OF THIS LEMMA");
	}


	public function renderEntry(){
		global $donut;
		// Setting the page title
		$donut['page']['title'] = $this->_entry['native']." - ".CONFIG_SITE_TITLE;

		pOut("<br />");
		// We need to get the translations
		$this->bindTranslations();
		$this->compileSubEntries();

		var_dump($this->_lemmaDataObject->getIdioms());

		pOut($this->_template->title($this->_type, $this->_class, $this->_subclass));

		if($this->_translations != null) 
			pOut($this->_template->parseTranslations($this->_translations));
		pOut($this->_template->parseSubEntries($this->_subEntries));

	}

	public function bindTranslations($lang_id = false){
		$translations = $this->_lemmaDataObject->getTranslations($lang_id);
		if(count($translations) == 0)
			return $this->_translations = null;
		foreach($translations as $translation)
			$this->_translations[$translation->language][] = $translation;
		// Sorting languages on id
		ksort($this->_translations);
	}

}
