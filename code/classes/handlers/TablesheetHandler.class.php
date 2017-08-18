<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      EntryObject.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pTablesheetHandler extends pHandler{

	public $_template, $_tablesheetModel, $_tables;

	// Constructor needs to set up the template as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());


		// Getting the tables (modes)
		$dM = new pDataModel('modes');
		$dM->setCondition(" WHERE type_id = ".pRegister::arg()['id']);
		$this->_tables = $dM->getObjects()->fetchAll();

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
		if($action == 'edit'){
			if(!isset(pRegister::arg()['ajax']))
				return $this->_template->renderEdit();
		}

		// Other actions are handled as default




		return parent::catchAction($action, $template, $arg);

	}

	public function ajaxEdit(){
		
	}

	public function ajaxRemove(){
	
	}

	public function ajaxNew($id = 0){
		
	}

}