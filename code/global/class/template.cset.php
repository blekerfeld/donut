<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: templates.cset.php

class pTemplate{

	public $_data, $activeStructure;

	public function __construct($data, $activeStructure){

		$this->_data = $data;
		$this->activeStructure = $activeStructure;

	}

	public function render(){
		
	}

}

class pEntryTemplate extends pTemplate{

	public function dataObject(){

	}

}
