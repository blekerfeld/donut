<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: entryDataModel.class.php

class pEntryDataModel extends pDataModel{

	private $_field, $_join;
	public $ID, $icon, $viewFunction;

	public function __construct($table, $field, $icon = 'fa-square-o', $paginated = false, $join = null, $view = 'varDump'){
		$this->icon = $icon;
		$this->_field = $field;
		$this->_fields = null;
		$this->_join = $join;
		$this->_table = $table;
		$this->_paginated = $paginated;
		$this->viewFunction = $view;
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

