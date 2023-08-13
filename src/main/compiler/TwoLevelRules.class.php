<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: TwoLevelRules.class.php

// A holder for two-level rules

class pTwolcRules{

	public $rules = array();

	public function __construct($table){
		$dataModel = new pDataModel($table);
		//$dataModel->setOrder(" sorter ASC ");
		$dataModel->getObjects();
		foreach($dataModel->getObjects()->fetchAll() as $rule)
			$this->rules[] = $rule['rule'];
	} 

	public function toArray(){
		return $this->rules;
	}

}