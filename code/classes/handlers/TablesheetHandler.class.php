<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      TablesheetHandler.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pTablesheetHandler extends pHandler{

	public $_template, $_tablesheetModel, $_tables, $_types, $_tabs;

	// Constructor needs to set up the template as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());

		if(isset(pRegister::arg()['id'])){
			// Getting the tables (modes)
			$this->_tables = (new pDataModel('modes'))->setCondition(" WHERE type_id = ".pRegister::arg()['id'])->getObjects()->fetchAll();	
			// Getting ALL the types
			$this->_types = (new pDataModel('types'))->setCondition(" WHERE inflect_not = 0 ")->getObjects()->fetchAll();		
		}

	}

	
	public function render(){
		
	}

	// This will alter the behavior of action catching for this handler.
	public function catchAction($action, $template, $arg = null){

		$this->_template = new pTablesheetTemplate($this);

		if(p::StartsWith($action, 'new')){
			if(!isset(pRegister::arg()['ajax']))
				return $this->_template->renderNew();
		}
		if($action == 'edit' AND isset(pRegister::arg()['table_id'])){
			if(!isset(pRegister::arg()['ajax'])){
				return $this->_template->renderEdit((new pDataModel('modes'))->setCondition(" WHERE id = " . pRegister::arg()['table_id'])->getObjects()->fetchAll()[0]);
			}
		}
		if($action == 'preview'){
			return pParadigm::preview(pRegister::post()['name'], pRegister::post()['headings'], pRegister::post()['rows'], pRegister::post()['columns']);
		}

		// Other actions are handled as default




		return parent::catchAction($action, $template, $arg);

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