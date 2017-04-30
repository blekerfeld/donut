<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemma.cset.php


// This represents a word

class pEntry{

	public $_id, $_entry, $_dataObject, $_data, $_subEntries, $_template;

	public function __construct($dataObject, $table = ''){

		if(is_a($dataObject, "pDataObject") OR is_subclass_of($dataObject, "pDataObject")){
			$this->_dataObject = $dataObject;
			$this->_id = $this->_dataObject->_singleId;
			$this->_entry = $this->_dataObject->data()->fetchAll()[0];
		}
		elseif(is_array($dataObject) ANd $table != ''){
			$this->_id = $dataObject['id'];
			$this->_dataObject = new pDataObject($table);
			$this->_entry  = $dataObject;
		}
		elseif($table != ''){
			$this->_dataObject = new pDataObject($table);
			$this->_id = $dataObject;
			$this->_dataObject->getSingleObject($this->_id);
			$this->_entry = $this->_dataObject->data()->fetchAll()[0];
		}
		else
			return die ("Fatal error creating the entry object");

		$this->_subEntries = new pSet;
	}

	public function setTemplate($template){
		$this->_template = $template;
	}


	// This function will save the subentries defined in the structure.
	public function fillSubEntries($subObjects){
		foreach($subObjects as $subEntry)
			$this->_subEntries->add($subEntry);
	}

	// This function will compile all subobjects, so that they can pass their data to templates
	public function compileSubEntries(){
		foreach($this->_subEntries->get() as $subEntry){
			$subEntry->setID($this->_id);
			$subEntry->compile();
		}
	}


	// This function will fetch all needed data, based on a entry data object.

	public function parseEntryDataObject($entryDataObject){
		if(!is_a($entryDataObject, 'pEntryDataObject'))
			return die("Subobjects of the entry need to be of type **pEntryDataObject**.");

		// First the EntryDataObject will be pleased with some kind of indentification, let's pass our ID

		$entryDataObject->setID($this->_id);
		$entryDataObject->compile();

		var_dump($entryDataObject);

	}

	public function read($var){
		if(isset($this->_entry[$var]))
			return $this->_entry[$var];
		return null;
	}

}
