<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under GNUv3

	//	++	File: structure.cset.php

class pStructure{

	public $_name, $_meta, $_type, $_structure, $_app, $_menu, $_menu_content, $_default_section, $_page_title;

	public function __construct($name, $type, $app, $default_section, $page_title){
		$this->_name = $name;
		$this->_type = $type;
		$this->_app = $app;
		$this->_default_section = $default_section;
		$this->_page_title = $page_title;
	}

	public function load(){
		try {
			$this->_structure = require_once pFromRoot("code/structures/".$this->_name.".".$this->_type.".struct.php");
			$this->_meta = $this->_structure['MAGIC_META'];
			$this->_menu = $this->_structure['MAGIC_MENU'];
			unset($this->_structure['MAGIC_META']);
			unset($this->_structure['MAGIC_MENU']);
		} catch (Exception $e) {
			die();
		}
		
	}

	public function compile(){

	}

}

