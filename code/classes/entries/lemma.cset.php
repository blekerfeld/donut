<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemma.cset.php


// This represents a word
class pLemma extends pEntry{

	private $_translations, $_examples, $__lemmaDataObject, $_type, $_class, $_subclass, $_isInflection, $_hitTranslation = null, $_query, $_inflector;

	public $word;

	// The constructur will call the parent (pEntry) with its arguments and then do a list of aditional tasks...
	public function __construct($id){
		// First we are calling the parent's constructor
		parent::__construct($id, 'words');

		$this->_lemmaDataObject = new pLemmaDataModel($this->_id);

		// Getting type (part of speech), classification (grammatical category) and grammatical tag
			if($this->_entry['type_id'] != 0)
				$this->_type = (new pDataModel('types', null, false, $this->_entry['type_id']))->data()->fetchAll()[0];

			if($this->_entry['classification_id'] != 0)
				$this->_class = (new pDataModel('classifications', null, false, $this->_entry['classification_id']))->data()->fetchAll()[0];

			if($this->_entry['subclassification_id'] != 0)
				$this->_subclass = (new pDataModel('subclassifications', null, false, $this->_entry['subclassification_id']))->data()->fetchAll()[0];

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
	public function renderSimpleLink($result = false, $extra = '', $class = ''){
		return "<a class='".$class." 'href='".$this->renderSimpleHref($result)."'>".$this->_entry['native']."</a> ";
	}

	// This function renders a simple link to the entry page
	public function renderSimpleHref($result = false){
		return p::Url('?entry/'.p::HashId($this->_entry['id']).($result ? '/is:result' : ''));
	}


	// Renders the word into a native span.
	public function native(){
		return "<span class='native'>".$this->_entry['native']."</span>";
	}


	// Used by the discuss action
	public function renderDiscussion(){
		p::Out("DISCUSSION OF THIS LEMMA");
	}

	// This function is called to render the lemma as an entry
	public function renderEntry(){
		
		global $donut;

		// Setting the page title
		pMainTemplate::setTitle($this->_entry['native']);

		// For the entry we need all possible bindings
		$this->bindAll((isset(pAdress::session()['returnLanguage']) ? pAdress::session()['returnLanguage'] : false));

		// The Subentries need to be prepared
		$this->compileSubEntries();

		p::Out($this->_template->title($this->_type, $this->_class, $this->_subclass));

		if($this->_translations != null) 
			p::Out($this->_template->parseTranslations($this->_translations));

		if($this->_type['inflect_not'] == 0){
			$this->bindInflector();
			p::Out($this->_template->parseInflections($this->_inflector));
		}

		if($this->_examples != null) 
			p::Out($this->_template->parseExamples($this->_examples));

		// Let's throw the subentries through their template
		p::Out($this->_template->parseSubEntries($this->_subEntries));

		p::Out($this->_actionbar->output);
	}

	// This function will generate an infostring
	private function generateInfoString(){
		$output = "<a href='javascript:void(0);' class='tooltip' title='".$this->_type['name']."'>".$this->_type['short_name']."</a> <a href='javascript:void(0);' class='tooltip' title='".$this->_class['name']."'>".$this->_class['short_name']."</a>";
		if($this->_subclass != null)
			$output .= "<a href='javascript:void(0);' class='tooltip' title='".$this->_subclass['name']."'>".$this->_subclass['short_name']."</a>";
		return $output;
	}

	// This function will make it able to highlight the searchterm
	public function setSearchQuery($query){
		$this->_query = $query;
	}

	// This function is called by the search object onto the found lemmas, to print a pretty preview :)
	public function renderSearchResult($searchlang = 0){

		$this->_template = new pLemmaTemplate($this->_entry, null);

		if(!($this->_hitTranslation == null))
			$hitTranslation = '<em class="dHitTranslation">'.p::Highlight($this->_query, $this->_hitTranslation, '<strong class="dQueryHighlight">', '</strong>').'</em> · ';
		else
			$hitTranslation = '';

		if($searchlang == 0)
			$linkToWord = "<a href='".$this->renderSimpleHref(true)."'>".p::Highlight($this->_query, $this->_entry['native'], '<strong class="dQueryHighlight">','</strong>')."</a>";
		else
			$linkToWord = $this->renderSimpleLink(true);

		p::Out("<tr class='hSearchResult'>");
		p::Out('<td><div class="dWordWrapper">'.$hitTranslation.'<strong class="dWord"><span class="native">'.$linkToWord."</span></strong><span class='dType'> · ".$this->generateInfoString()."</span> <br />".$this->_template->parseTranslations($this->_translations, true)."</div></td></tr>");
	}


	public function parseListItem(){
		return "<li><span><span class='lemma lemma_".$this->_entry['id']." tooltip'><strong class='dWordTranslation'><a href='".p::Url('?entry/'.$this->_entry['id'])."'>".$this->_entry['native']."</a></strong></span><span class='dType'>
				".$this->generateInfoString()."</span></span></li>";
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

	// Binding inflections
	public function bindInflector(){
		$this->_inflector = new pInflector($this, (new pTwolcRules('phonology_contexts'))->toArray());
		$this->_inflector->compile();
	}

}
