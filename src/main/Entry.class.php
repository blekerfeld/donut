<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      lemma.class.php


// This represents a word

class pEntry{

	public $_id, $_entry, $dataModel, $_data, $_subEntries, $_view, $_actionbar;

	public function __construct($dataModel, $table = ''){
		if(is_a($dataModel, "pDataModel") OR is_subclass_of($dataModel, "pDataModel")){
			$this->dataModel = $dataModel;
			$this->_id = $this->dataModel->_singleId;
			$this->_entry = $this->dataModel->data()->fetchAll()[0];
		}
		elseif(is_array($dataModel) ANd $table != ''){
			$this->_id = $dataModel['id'];
			$this->dataModel = new pDataModel($table);
			$this->_entry  = $dataModel;
		}
		elseif($table != ''){
			$this->dataModel = new pDataModel($table);
			$this->_id = $dataModel;
			$this->dataModel->getSingleObject($this->_id);
			$this->_entry = $this->dataModel->data()->fetchAll()[0];
		}
		else
			return die ("Fatal error creating the entry object");

		$this->_subEntries = new pSet;
	}

	public function setView($view){
		$this->_view = $view;
	}


	public function passActionBar($ab){
		$this->_actionbar = $ab;
	}

	// This function will save the subentries defined in the structure.
	public function fillSubEntries($subObjects){
		foreach($subObjects as $subEntry)
			$this->_subEntries->add($subEntry);
	}

	// This function will compile all subobjects, so that they can pass their data to views
	public function compileSubEntries(){
		foreach($this->_subEntries->get() as $subEntry){
			$subEntry->setID($this->_id);
			$subEntry->compile();
		}
	}

	public function read($var){
		if(isset($this->_entry[$var]))
			return $this->_entry[$var];
		return null;
	}

}
