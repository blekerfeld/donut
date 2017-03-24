<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: admin.cset.php

class pAdminParser{

	private $_section, $_app, $_data, $_paginated = true, $_condition, $_offset, $_adminObject, $_total;


	public function __construct($data, $app = 'dictionary-admin'){
		var_dump($data);
		$this->_app = $app;
		$this->_section = $data['section_key'];
		$this->_data =  $data;
		$this->_total = pCountTable($data['table']);

	}

	// Used to switch off the pagination if needed

	public function togglePagination(){
		$this->_paginated = !$this->_paginated;
	}

	// Used to give the SELECT query of the dataObject a condition 

	public function setCondition($condition){
		$this->_condition = $condition;
	}

	public function setOffset($offset){
		$this->_offset = $offset;
	}

	public function compile(){

		// Toggle thingies
		if($this->_data['condition'] != false)
			$this->setCondition($this->_data['condition']);
		if($this->_data['disable_pagination'] != false)
			$this->togglePagination();

		// Creating the field set
		$this->_fields = new pSet; 
		foreach($this->_data['datafields'] as $field)
			$this->_fields->add(new pDataField($field[0], $field[1], $field[2]));

		// Creating the actions per item set
		$this->_actions = new pSet;
		foreach($this->_data['actions_item'] as $action)
			$this->_actions->add(new pAction($action[0], $action[1], $action[2], $action[3], $this->_section, $this->_app));

		// Creating the actions per item set
		$this->_actionbar = new pSet;
		foreach($this->_data['actions_bar'] as $action)
			$this->_actionbar->add(new pAction($action[0], $action[1], $action[2], $action[3], $this->_section, $this->_app));

		// Compiling the parsing data into an tableObject
		switch ($this->_data['type']) {
			case 'pTableObject':
				$this->_adminObject = new pTableObject($this->_data['surface'], $this->_data['table'], $this->_data['items_per_page'], $this->_fields, $this->_actions, $this->_actionbar, $this->_section, $this->_app);
				break;
			
			default:
				
				break;
		}
		
	}

	// A shortcut to running the queries of the adminObject which runs it inside its dataObject
	public function runData($id = 0){
		return $this->_adminObject->getData($id);
	}

	public function render(){
		$this->_adminObject->render();
	}
}


// ↓ Table object
class pAdminObject{

	public $_surface, $_data, $_dfs, $_dataobject, $_section, $_app, $_actions, $_actionbar, $_paginated, $_offset, $_itemsperpage, $_condition = '';

	public function __construct($surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $section, $app = 'dictionary-admin'){
		$this->_surface = $surface;

		$this->_dfs = $dfs;
		$this->_actions = $actions;
		$this->_actionbar = new pActionBar($actionbar);

		$this->_paginated = true;
		$this->_offset = 0;
		$this->_itemsperpage = $itemsperpage;

		$this->_dataobject = new pDataObject($table, $this->_dfs);

		$this->_section = $section;
		$this->_app = $app;
	} 



	// This one lets the dataobject work! 

	public function getData($id = 0){
		if($id == 0)
			return $this->_data = $this->_dataobject->getObjects($this->_offset, $this->_itemsperpage, $this->_condition)->fetchAll();
		else
			return $this->_data = $this->_dataobject->getSingleObject($id);
	}


}

class pTableObject extends pAdminObject{
	// Used as last

	public function render(){

		// Generating the action bar
		$this->_actionbar->generate();
		
		pOut("<div class='btCard admin'>
			<div class='btTitle'>
				".$this->_surface."
			</div>");

		pOut("<table class='admin'>
			<thead>
			<tr class='title' role='row'>");

		// Building the table
		foreach ($this->_dfs->get() as $datafield) {
			pOut("<td style='width: ".$datafield->width."'>".$datafield->surface."</td>");
		}


		pOut("<td>".ACTIONS."</td>
			</tr></thead><tbody>");

		foreach($this->_data as $data){
			pOut("<tr>");

			// Go through the data fields
			foreach($this->_dfs->get() as $datafield){
				pOut("<td style='width: ".$datafield->width."'>".$data[$datafield->name]."</td>");
			}

			// The important actions and such
			pOut("<td class='actions'>");
			foreach($this->_actions->get() as $action)
				pOut($action->render($data['id']));
			pOut("</td>");

			pOut("</tr>");
		}

		pOut("</tbody></table>
		</thead>");

		pOut("
			<div class='btButtonBar'>".$this->_actionbar->output."</div>
		</div>");
		}
}