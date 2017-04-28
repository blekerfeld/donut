<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entryDataObject.cset.php

class pEntryDataObject extends pDataObject{

	private $_field, $_join;
	public $ID, $icon, $templateFunction;

	public function __construct($table, $field, $icon = 'fa-square-o', $paginated = false, $join = null, $template = 'varDump'){
		$this->icon = $icon;
		$this->_field = $field;
		$this->_fields = null;
		$this->_join = $join;
		$this->_table = $table;
		$this->_paginated = $paginated;
		$this->templateFunction = $template;
	}

	public function setID($value){
		$this->ID = $value;
	}

	public function compile(){
		$this->generateFieldString();

		if(is_array($this->_field)){
			$conditionString = ' WHERE '.$this->_field[0].' = "'.$this->ID.'"';
			unset($this->_field[0]);
			foreach($this->_field AS $field)
				$conditionString .= " OR $field = '$this->ID' ";
			$this->setCondition($conditionString);
		}
		else
			$this->setCondition(" WHERE $this->_field = '$this->ID'");

		$this->getObjects();
		$this->_data = $this->_data->fetchAll();
	}

}

