<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: language.cset.php

class pLanguage{

	public $id, $data, $dataObject;


	// This would work only with 'new pLanguage'
	public function __toString(){
		return $this->id;
	}

	// Constructure accepts both a language code or a number
	public function __construct($id){
		$this->dataObject = new pDataObject('languages');
		if(is_numeric($id))
			$this->dataObject->getSingleObject($id);
		else{
			$this->dataObject->setCondition(" WHERE locale = '".$id."'");
			$this->dataObject->getObjects();
			if($this->dataObject->data()->rowCount() == 0 OR $this->dataObject->data()->rowCount() > 1)
				die("Could not fetch language");
		}
		$this->load($this->dataObject->data()->fetchAll()[0]);
		
	}

	private function load($data){
		$this->id = $data['id'];
		$this->data = $data; 
	}

	public function parse(){
		return $this->data['name'];
	}

	// This will read out the given field
	public function read($key){
		if(array_key_exists($key, $this->data))
			return $this->data[$key];
		return false;
	}
	

}