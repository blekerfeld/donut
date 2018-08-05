<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: TablesheetHandler.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pTablesheetHandler extends pHandler{

	public $_view, $_tablesheetModel, $_tables, $_types, $_tabs;

	// Constructor needs to set up the view as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());

		// Getting ALL the types
			$this->_types = (new pDataModel('types'))->setCondition(" WHERE inflect = 1 ")->getObjects()->fetchAll();	

		if(isset(pRegister::arg()['id'])){
			// Getting the tables (modes)
			$this->_tables = (new pDataModel('modes'))->setCondition(" WHERE type_id = ".pRegister::arg()['id'])->getObjects()->fetchAll();		
		}

	}

	
	public function render(){
		$this->_view = new pTablesheetView($this);
		return $this->_view->renderOverview();
	}

	// This will alter the behavior of action catching for this handler.
	public function catchAction($action, $view, $arg = null){

		$this->_view = new pTablesheetView($this);

		if(p::StartsWith($action, 'new')){
			if(!isset(pRegister::arg()['ajax']))
				return $this->_view->renderNew();
		}
		if($action == 'edit' AND isset(pRegister::arg()['table_id'])){
			if(!isset(pRegister::arg()['ajax'])){
				return $this->_view->renderEdit((new pDataModel('modes'))->setCondition(" WHERE id = " . pRegister::arg()['table_id'])->getObjects()->fetchAll()[0]);
			}
		}
		if($action == 'preview'){
			pParadigm::preview(pRegister::post()['name'], pRegister::post()['headings'], pRegister::post()['rows'], pRegister::post()['columns']);
			return p::Out("<br id='cl' />");
		}
		if($action == 'randompreview'){
			if(!isset($this->_data[0]))
				return p::Out('nope.');

			// Let's get a random word
			$lemma = (new pDataModel('words'))->setCondition(" WHERE hidden = 0 AND type_id=" . $this->_data[0]['id'])->setLimit('1')->setOrder(' rand() ')->getObjects()->fetchAll()[0];
			$this->_inflector = new pInflector((new pLemma($lemma)), (new pTwolcRules('phonology_contexts'))->toArray());
			$this->_inflector->compile();
			p::Out('<div class="pRandomPreview">');
			p::Out($this->_inflector->render('pInflectionTable', (new pDataModel('modes'))->setCondition(" WHERE id = " . pRegister::arg()['table_id'])->getObjects()->fetchAll()[0]));
			p::Out('</div>');
			return true;
		}

		// Other actions are handled as default
		return parent::catchAction($action, $view, $arg);

	}

	protected function findOrMakeRecords(array $names){
		// Prepare an empty array
		$output = array();

		foreach($names as $key => $holder){

			if($key == 'headings')
				$key = 'submodes';
			elseif($key == 'rows')
				$key = 'numbers';

			// Let's quote all names
			foreach($holder as $keyH => $holded)
				$holder[$keyH] = p::Quote($holded);

			// Let's find all records
			$output[$key] = (new pDataModel(($key == 'headings' ? 'submodes' : ($key == 'rows' ? 'numbers' : 'columns'))))->setCondition(" WHERE name IN ".implode(', ', $holder))->getObjects()->fetchAll();

			// foreach($output[$key] as $record)
			// 	unset(NULL);

		}

		return $output;
	}

	public function ajaxEdit(){
		
	}

	public function ajaxRemove(){
	
	}

	public function ajaxNew($id = 0){
		
	}

}