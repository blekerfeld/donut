<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      EntryObject.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pEntryHandler extends pHandler{

	private $_view, $_meta;

	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());

		// Now we need to know if there is a 
		if(!isset($this->_activeSection['entry_meta']))
			return trigger_error("pEntryHandler needs a **entry_meta** key in its array structure.", E_USER_ERROR);

		$this->_meta = $this->_activeSection['entry_meta'];
	}


	// This is the internal dispatcher for action of an entry
	public function catchAction($action, $view = '', $arg = null){

		// reset translations
		if($action == 'resetTranslations'){
			unset($_SESSION['returnLanguage']);
			p::Url('?entry/'.pRegister::arg()['id'], true);
		}

		// Discuss
		if($action == 'discuss'){
			p::Out(pRegister::tabs());
			// Create a view
			$this->_view = new $this->_activeSection['view']($this->_data[0], $this->_activeSection);
			// Setting the page title
			pTemplate::setTitle(sprintf(LEMMA_DISCUSS_TITLE, "*".(isset($this->_data[0]['native']) ? $this->_data[0]['native'] : (isset($this->_data[0]['translation']) ? $this->_data[0]['translation'] : ''))."*"));
			// Title
			$this->_view->discussTitle();
			p::Out(new pAjaxLoader(p::Url('?thread/'.$this->_section.'/view/'.pRegister::arg()['id'])));
		}

		if($action == 'proper'){
			return $this->render(true);
		}

	}

	public function statsData($subsection = null, $extra = 50){
		if($subsection == 'search'){
			$dM = new pDataModel('search_hits');
			$dM->complexQuery("SELECT count(word_id) AS num_word, word_id, user_id, hit_timestamp FROM search_hits GROUP BY word_id ORDER BY num_word DESC LIMIT $extra");
			return $dM->data()->fetchAll();
		}
		return array();
	}

	public function renderStats($subsection = null){


		// Passing the data on to the view
		$this->_view = new $this->_activeSection['view']($this->statsData($subsection), $this->_activeSection);

		if($subsection == 'search')
			return $this->_view->renderSearch();

		// Render the view
		return $this->_view->renderAll();

	} 


	public function renderYear($subsection = null){

		p::Out(pRegister::tabs()->addLink(1, 'Entries by attestation year')->setActive(1));

		// Well this will be simple and such!

		$this->changePagination(true);
		$this->setData("SELECT w.* FROM words AS w JOIN etymology as e ON w.id = e.word_id WHERE e.first_attestation = '".$subsection."'");

		$entries = (new pLemmaDataModel)->fish($this->_data);
		$pages = "<div class='pages'>".$this->pagePrevious()."<div class='holder'>".$this->pageSelect()."</div>".$this->pageNext()."</div>";

		p::Out($pages);

		foreach($entries as $lemma)
			p::Out($lemma->renderSearchResult(0, true));

	} 


	protected function doRandomEntry(){
		$dM = new pDataModel('words');
		$random = $dM->complexQuery("SELECT id FROM words ORDER BY RAND() LIMIT 1")->fetchAll()[0];
		echo "hoi!";
		return p::Url('?entry/'.p::HashId($random['id']), true);
	}

	public function render($proper = false){

		if($this->_section == 'random')
			return $this->doRandomEntry();

		// Stats gets sent to another function
		if($this->_section == 'stats' AND isset(pRegister::arg()['id'])){
			return $this->renderStats(pRegister::arg()['id']);
		}

		if($this->_section == 'year' AND isset(pRegister::arg()['id'])){
			return $this->renderYear(pRegister::arg()['id']);
		}


		if(isset(pRegister::arg()['query'], pRegister::arg()['dictionary']))
			return false;

		if(p::NoAjax())
			p::Out(pRegister::tabs());

		$this->_actionbar->generate((isset(pRegister::arg()['id'])) ? pRegister::arg()['id'] : -1);

		// Shortcut to the data
		$data = $this->dataModel->data()->fetchAll()[0];

		// Passing the data on to the view
		$this->_view = new $this->_activeSection['view']($data, $this->_activeSection);

		// We might pass this job to an object if that is specified in the metadata
		if(isset($this->_meta['parseAsObject']) AND class_exists($this->_meta['parseAsObject'])){
		
			$object = new $this->_meta['parseAsObject']($this->dataModel);
			
			$object->setView($this->_view);
			
			if(isset($this->_activeSection['subobjects']))
				$object->fillSubEntries($this->_activeSection['subobjects']);
			
			$object->passActionBar($this->_actionbar);

			if($proper)
				return $object->renderEntry();
			else{
				return p::Out(new pAjaxLoader(p::Url('?entry/'.$this->_section.'/'.pRegister::arg()['id'].'/proper/ajaxLoad')));
			}

		}
	}
}