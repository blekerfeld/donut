<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemma.cset.php


// This represents a word

class pLemma extends pEntry{

	private $_translations, $_examples, $__lemmaDataObject, $_type, $_class, $_subclass, $_isInflection, $_hitTranslation = null;

	public $word;

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

			// Make it easier to access the word itself
			$this->word = $this->_entry['native'];
	}

	// For search results
	public function setInflection($inflection){
		$this->_isInflection = true;
		$this->_inflection = $inflection;
	}

	public function setHittranslation($hitTranslation){
		$this->_hitTranslation = $hitTranslation;
	}

	// This function renders a simple link to the entry page
	public function renderSimpleLink($class = ''){
		return "<a class='".$class." 'href='".pUrl('?entry/'.pHashId($this->_entry['id']))."'>".$this->_entry['native']."</a> ";
	}


	// Renders the word into a native span.
	public function native(){
		return "<span class='native'>".$this->_entry['native']."</span>";
	}


	// Used by the discuss action
	public function renderDiscussion(){
		pOut("DISCUSSION OF THIS LEMMA");
	}

	// This function is called to render the lemma as an entry
	public function renderEntry(){
		
		global $donut;

		// For search results
		pOut("<div class='searchResults'></div>");
		
		// Setting the page title
		$donut['page']['title'] = $this->_entry['native']." - ".CONFIG_SITE_TITLE;

		// For the entry we need all possible bindings
		$this->bindAll((isset(pAdress::session()['returnLanguage']) ? pAdress::session()['returnLanguage'] : false));

		// The Subentries need to be prepared
		$this->compileSubEntries();

		pOut($this->_template->title($this->_type, $this->_class, $this->_subclass));

		if($this->_translations != null) 
			pOut($this->_template->parseTranslations($this->_translations));

		if($this->_examples != null) 
			pOut($this->_template->parseExamples($this->_examples));

		// Let's throw the subentries through their template
		pOut($this->_template->parseSubEntries($this->_subEntries));

	}

	// This function is called by the search object onto the found lemmas, to print a pretty preview :)
	public function renderSearchResult(){
		if(!($this->_hitTranslation == null OR $this->_hitTranslation === true))
			pOut("<tr class='hSearchResultTitle'><td>".$this->_hitTranslation . "</td></tr>");
		pOut("<tr class='hSearchResult'>");
		pOut('<td><div class="dWordWrapper"><strong class="dWord">'.$this->renderSimpleLink()."</div></td></tr>");
	}

	// This will bind both translations and examples to the lemma
	public function bindAll($lang_id = false){
		$this->bindTranslations($lang_id);
		$this->bindExamples();
	}

	// This binds the translations to the lemma object
	public function bindTranslations($lang_id = false){
		$translations = $this->_lemmaDataObject->getTranslations($lang_id);
		if(count($translations) == 0)
			return $this->_translations = null;
		foreach($translations as $translation)
			$this->_translations[$translation->language][] = $translation;
		// Sorting languages on id
		ksort($this->_translations);
	}

	/// Binding examples to the lemma
	public function bindExamples(){
		$this->_examples = $this->_lemmaDataObject->getIdioms();
	}

}
