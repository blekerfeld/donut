<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: structure.cset.php

class pStructure{

	private $_name, $_type;

	public function __construct($name, $type){
		$this->_name = $name;
		$this->_type = $type;
	}

	public function load(){
		try {
			return require_once pFromRoot("code/structures/".$this->_name.".".$this->_type.".struct.php");
		} catch (Exception $e) {
			die();
		}
		
	}

}