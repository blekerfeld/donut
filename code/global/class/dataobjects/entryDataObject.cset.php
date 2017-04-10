<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pEntryDataObject extends pDataObject{

	private $_field;
	public $value, $surface, $templateClass;

	public function __construct($surface, $table, $field, $paginated = false, $template){
		$this->surface = $surface;
		$this->_field = $field;
		$this->_fields = null;
		$this->_table = $table;
		$this->_paginated = $paginated;
		$this->templateClass = $template;
	}

	public function setValue($value){
		$this->value = $value;
	}

	public function compile(){
		$this->generateFieldString();
		$this->setCondition(" WHERE $this->_field = '$this->value'");
		$this->getObjects();
		$this->_data = $this->_data->fetchAll();
	}

	public function render(){
		$template = new $this->_templateClass($this->_data);
		return $template->dataObject();
	}
}