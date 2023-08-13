<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: lemma.class.php


// This represents a word
class pLemma extends pEntry{

	public $_translations, $_examples, $__lemmaDataObject, $_type, $_class, $_subclass, $_isInflection, $_hitTranslation = null, $_query, $_inflector;

	public $word;

	// The constructur will call the parent (pEntry) with its arguments and then do a list of aditional tasks...
	public function __construct($id){

		// First we are calling the parent's constructor
		parent::__construct($id, 'words');

		$this->_lemmaDataObject = new pLemmaDataModel($this->_entry);

		// Getting type (part of speech), classification (grammatical category) and grammatical tag
			if($this->_entry['type_id'] != 0)
				$this->_type = pRegister::cacheCallBack('types', $this->_entry['type_id'], function(){
					return (new pDataModel('types', null, false, $this->_entry['type_id']))->data()->fetchAll()[0];
				});

			if($this->_entry['classification_id'] != 0)
				$this->_class = pRegister::cacheCallBack('classifications', $this->_entry['classification_id'], function(){
					return (new pDataModel('classifications', null, false, $this->_entry['classification_id']))->data()->fetchAll()[0];
				});

			if($this->_entry['subclassification_id'] != 0)
				$this->_subclass = pRegister::cacheCallBack('subclassifications', $this->_entry['subclassification_id'], function(){
					return (new pDataModel('subclassifications', null, false, $this->_entry['subclassification_id']))->data()->fetchAll()[0];
				});

			// Make it easier to access the word itself
			$this->word = $this->_entry['native'];

			$this->_view = new pLemmaView($this, null);

			// Now we might do some statistics and stuff
			if(isset(pRegister::arg()['is:result'], pRegister::session()['searchQuery'])){

				if(isset(pRegister::session()['last_hit']) AND pRegister::session()['last_hit'] == date('H').'_'.$this->read('id'))
					return;

				pRegister::session('last_hit', date('H').'_'.$this->read('id'));
				$dMSearchHit = new pDataModel('search_hits');
				$dMSearchHit->prepareForInsert(array($this->read('id'), pUser::read('id'),date('Y-m-d H:i:s')))->insert();
			}

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

	// This function renders a simple link to the entry page for the preview field
	public function renderPreviewLink($result = false, $extra = '', $class = ''){
		return "<a class='".$class." 'href='javascript:void(0)' onClick='$(\".preview-load\").load(\"".$this->renderSimpleHref($result)."/ajaxLoad/is:preview/ajax\");'>".$this->_entry['native']."</a> ";
	}

	// This function renders a simple link to the entry page
	public function renderSimpleHref($result = false){
		return p::Url('?entry/'.p::HashId($this->_entry['id']).($result ? '/is:result' : ''));
	}


	// Renders the word into a native span.
	public function native(){
		return "<span class='native'>".$this->_entry['native']."</span>";
	}

	// This function is called to render the lemma as an entry
	public function renderEntry(){
		
		// Setting the page title
		pTemplate::setTitle($this->_entry['native']);

		// For the entry we need all possible bindings
		$this->bindAll();

		// The Subentries need to be prepared
		$this->compileSubEntries();

		p::Out($this->_view->title($this->_type, $this->_class, $this->_subclass));

		p::Out($this->_view->renderInfo());

		if($this->_translations != null) 
			p::Out($this->_view->parseTranslations($this->_translations));

		if($this->_type['inflect'] == 1){
			$this->bindInflector();
			p::Out($this->_view->parseInflections($this->_inflector));
		}

		if($this->_examples != null) 
			p::Out($this->_view->parseExamples($this->_examples));

		// Let's throw the subentries through their view
		p::Out($this->_view->parseSubEntries($this->_subEntries));


	}

	// This function will generate an infostring
	public function generateInfoString(){
		$output = "<a href='javascript:void(0);' class='tooltip ttip' title='".$this->_type['name']."'>".$this->_type['short_name']."</a> <a href='javascript:void(0);' class='ttip tooltip' title='".$this->_class['name']."'>".$this->_class['short_name']."</a>";
		if($this->_subclass != null)
			$output .= "<a href='javascript:void(0);' class='tooltip ttip' title='".$this->_subclass['name']."'>".$this->_subclass['short_name']."</a>";
		return $output;
	}

	// This function will make it able to highlight the searchterm
	public function setSearchQuery($query){
		$this->_query = $query;
	}

	// This function is called by the search object onto the found lemmas, to print a pretty preview :)
	public function renderSearchResult($searchlang = 0, $noTransStatus = false, $noPreview = false){
		return $this->_view->renderSearchResult($searchlang, $noTransStatus, $noPreview);
	}

	public function renderDictionaryEntry(){
		$dictionaryView = new pDictionaryView;
		return $dictionaryView->renderEntry($this);
	}

	public function renderStatus(){
		return ($this->_entry['hidden'] == 1 ? "<span class='pExtraInfo'>".(new pIcon('fa-eye-slash', 12))." ".LEMMA_HIDDEN."</span>" : '');
	}


	public function parseListItem(){
		return $this->_view->parseListItem($this->_entry);
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
