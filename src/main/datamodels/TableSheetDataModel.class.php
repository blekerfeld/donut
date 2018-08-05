<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: TableSheetDataModel.class.php



class pTableSheetDataModel extends pDataModel{

	public $_links, $_ModeID;

	public function __construct($table, $id = 0){
		parent::__construct($table);
		$fields = new pSet;
		$fields->add(new pDataField('name'));
		$fields->add(new pDataField('rule'));
		$fields->add(new pDataField('ruleset'));
		$fields->add(new pDataField('in_set'));
		$this->setFields($fields);
		if($id != 0){
			$this->_ModeID = $id;
			$this->getSingleObject($id);
			$this->_rule = $this->data()->fetchAll()[0];
		}
	}

}