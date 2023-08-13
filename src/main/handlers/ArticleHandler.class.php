<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: ArticleHandler.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pArticleHandler extends pHandler{

	public $_view, $_rulesheetModel, $_ID, $_articleMeta, $_articleProper, $_articleLanguages = [], $_activeLocale, $_isPermaLink = false, $_history, $_errorNotFound = false, $_activeRevision;

	// Constructor needs to set up the view as well
	public function __construct(){

		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel

		// Get the article's meta data
		$this->dataModel = new pDataModel('articles');
		$this->dataModel->setCondition(" WHERE url = ".p::Quote(pRegister::arg()['url']));
		$this->dataModel->getObjects();
		if(isset($this->dataModel->_data->fetchAll()[0]))
			$this->_articleMeta = $this->dataModel->_data->fetchAll()[0];
		else{
			$this->_errorNotFound = true;
			return false;
		}

		// Set locale if not specified
		if(!isset(pRegister::arg()['language']))
			pRegister::addArgPostMortem('language', CONFIG_WIKI_LOCALE);

		// Decide on which revision to get
		$this->decideRevision();

		// Get list of languages
		foreach((new pDataModel("article_revisions"))->complexQuery("SELECT DISTINCT language_locale FROM article_revisions WHERE article_id = '".$this->_articleMeta['id']."'")->fetchAll() as $fetchedLanguage)
				$this->_articleLanguages[] = new pLanguage($fetchedLanguage['language_locale']);
	}


	public function decideRevision(){

			$revision = '';
			// Add this part to the query when we have a timestamp
			
			if(isset(pRegister::arg()['revision'])){
				$revision = " AND id = ".p::Quote(p::HashId(substr(pRegister::arg()['revision'], 9), true)[0])." ";
				$this->_isPermaLink = true;
			}

			// And the latest revision
			$this->dataModel = new pDataModel('article_revisions');
			$target = $this->dataModel->setCondition(" WHERE article_id = " . $this->_articleMeta['id']  . " AND  language_locale = ".p::Quote(strtoupper(pRegister::arg()['language']))." ".$revision)->setOrder(" revision_date DESC ")->setLimit(1)->getObjects();

			if(!$target->fetchAll()){
				$this->_errorNotFound = true;
				return false;
			}

			$this->_articleProper = $this->dataModel->_data->fetchAll()[0];


		// For later reference
		$this->_activeLocale = strtoupper(pRegister::arg()['language']);
		$this->_activeRevision = $this->_articleProper['id'];

	}

	public function catchAction($action, $view, $arg = null){

		if($action == 'view')
			return $this->render();

		if($action == 'undo' && isset(pRegister::arg()['language'], pRegister::arg()['url'], pRegister::arg()['revision'])){
			$this->undoRevision();
			p::Url('?wiki/article/'.$this->_articleMeta['url'], true);
		}
		
		// Default behaviour
		//if($action != 'new' AND $action != 'edit' AND $action != 'remove')
			return parent::catchAction($action, $view, $arg);

	
	}

	private function undoRevision(){
		// Retrieving target
		
		if(!($target = (new pDataModel('article_revisions'))->setOrder('revision_date DESC')->setLimit(1)->setCondition("WHERE language_locale = '".$this->_activeLocale."' AND article_id = '".$this->_articleMeta['id']."' AND id < ".$this->_activeRevision)->getObjects()->fetchAll()[0]))
			return false;

		return (new pDataModel('article_revisions'))->prepareForInsert(array($this->_articleMeta['id'], pUser::read('id'), $this->_activeLocale, 'NOW()', $target['name'], array(p::QuoteOnly($target['content']), false), sprintf(WIKI_UNDO_REVISION_STRING, (new pUser($target['user_id']))->read('username'), $target['revision_date']), 1))->insert();
	}

	private function fetchHistory(){
		$this->_history = (new pDataModel('article_revisions'))->complexQuery("SELECT * FROM article_revisions WHERE language_locale = '".$this->_activeLocale."' AND article_id = '".$this->_articleMeta['id']."' ORDER BY revision_date DESC")->fetchAll();
	}

	public function render(){	

		// Error not found
		if($this->_errorNotFound)
			return 	p::Out(pTemplate::NoticeBox('fa-exclamation-triangle', WIKI_ARTICLE_NOT_FOUND, 'danger-notice medium'));

		// Fetch history if needed
		if(pRegister::arg()['section'] == 'history')
			$this->fetchHistory();


		
		// Pass self onto view
		$this->_view =  new pArticleView($this);
		
		if(pRegister::arg()['section'] == 'editor')
			return $this->_view->renderEditor();

		if(pRegister::arg()['section'] == 'history')
			return $this->_view->renderHistory();

		return $this->_view->renderView();
	}


}